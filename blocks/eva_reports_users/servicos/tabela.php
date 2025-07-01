<?php
require_once('../../../config.php');
require_once 'tabela_reports_users.php';

defined('MOODLE_INTERNAL') || die();

global $CFG, $DB, $USER;

$idReport = isset($_REQUEST['id'])    ? (integer)$_REQUEST['id'] : 0;
$filtro   = "";
$filtro2  = "";
$x        = 0;


//===============================RELATORIO 1 - CURSOS E CATEGORIAS - ========================================

if($idReport == 1){

    $relatorio1 = new tabela_reports_users();
    $rs = $relatorio1->get_curso_categoria();

    $array = array();

    if($rs){
        $resultSet = json_decode(json_encode($rs,JSON_UNESCAPED_UNICODE),true);

        foreach($resultSet as $row){
            $id = $row['id'];

            $sql2 ="select
                        row_number() over() as id,
                        count(logs.courseid) as total_acessos
                    from
                        mdl_logstore_standard_log logs
                    where
                        logs.eventname like '%course_viewed%'
                        and logs.contextlevel = 50
                        and logs.userid > 2
                        and logs.courseid = " . $id;
            $rs2 = $DB->get_records_sql($sql2);

            if($rs2){
                $resultSet2 = json_decode(json_encode($rs2,JSON_UNESCAPED_UNICODE),true);
                $array['total_acessos'] = $resultSet2[1]['total_acessos'];
            }

            foreach($row as $k => $v){
                $array[$k] = $v;
            }

            $array['workload'] = (($row['workload'] !== NULL) ? $row['workload'] : '-');
            $array['dta_inicio'] = (($row['dta_inicio'] !== NULL) ? date('d/m/Y', strtotime($row['dta_inicio'])) : "-");
            $array['dta_final'] = (($row['dta_final'] !== NULL) ? date('d/m/Y', strtotime($row['dta_final'])) : "-");

            $arr[] = $array;
        }

        echo json_encode($arr,JSON_UNESCAPED_UNICODE);
    }else{
        $arr = '';
        echo json_encode($arr,JSON_UNESCAPED_UNICODE);
    }


//============================RELATORIO 2 - RESULTADOS POR CURSOS - ========================================

}else if($idReport == 2){

    $report2 = new tabela_reports_users();
    $rs = $report2->get_resultado_por_curso();

    $array = array();
    $passfail = "";

    if($rs){
        $resultSet = json_decode(json_encode($rs,JSON_UNESCAPED_UNICODE),true);

        foreach($resultSet as $row){
            $iduser = $row['userid'];
            $courseid = $row['courseid'];
            $carga = 0;
            $x = 0;

            //=====query para calcular o progresso do curso =======

            $comp = $report2->get_calcula_pregresso_do_curso($iduser, $courseid);

            if($comp){
                $resultSet2 = json_decode(json_encode($comp,JSON_UNESCAPED_UNICODE),true);
                $t = count($resultSet2);
                foreach($resultSet2 as $row2){
                    if(($row2['its_done'] == "Sim")){
                        $x++;
                    }
                }
                $percent = round(($x * 100) / $t);
            }else{
                $percent = 0;
            }

            $sql_wl = "select wl.courseid, wl.tempoemmin from mdl_eva_course_workload wl where wl.courseid = '{$courseid}';";
            $rs3 = $DB->get_records_sql($sql_wl);

            $workload = json_decode(json_encode($rs3,JSON_UNESCAPED_UNICODE),true);
            foreach($workload as $row3){
                $carga = (integer)$row3['tempoemmin'];

            }
            if($carga > 0){
                $carga = $carga / 60;
                $array['carga'] = number_format((float)$carga, 2, '.', '') . ' hrs';
            }else{
                $array['carga'] = '0 hrs';
            }

            $progress = '';
            $progress .= '
            <label for="file">'.$percent.'%</label><br>
            <progress id="file" value="'.$percent.'" max="100"></progress>
            ';

            $array['percent'] = $progress;

            foreach($row as $k => $v){
                $array[$k] = $v;
            }
            $arr[] = $array;
        }
        echo json_encode($arr,JSON_UNESCAPED_UNICODE);
    }else{
        $arr = '';
        echo json_encode($arr,JSON_UNESCAPED_UNICODE);
    }


//================================RELATORIO 3 - USUARIOS GERAL- ========================================

}else if($idReport == 3){

    $periodoStart      = isset($_REQUEST['ultacessoStart'])      ? $_REQUEST['ultacessoStart']      : "";
    $periodoEnd        = isset($_REQUEST['ultacessoEnd'])        ? $_REQUEST['ultacessoEnd']        : "";

    if(!empty($periodoStart) and (!is_null($periodoStart))){
        $filtro .= " AND from_unixtime(cmc.timemodified,'%Y-%m-%d') >= STR_TO_DATE('".$periodoStart."','%d/%m/%Y') ";
    }

    if(!empty($periodoEnd) and (!is_null($periodoEnd))){
        $filtro .= " AND from_unixtime(cmc.timemodified,'%Y-%m-%d') <= STR_TO_DATE('".$periodoEnd."','%d/%m/%Y') ";
    }

    $array = array();
    $report3 = new tabela_reports_users();

    $sql = "select 
                tab.*
            from
                (
                select 
                    mu.id,
                    upper(concat(trim(mu.firstname),' ',trim(mu.lastname))) as nome,
                    case when mu.ds_cargo is null then '-' else upper(trim(mu.ds_cargo)) end as cargo,
                    case when mu.lotacao is null then '-' else upper(trim(mu.lotacao)) end as lotacao,
                    case 
                        when mr.shortname = 'manager' then 'GERENTE'
                        when mr.shortname = 'coursecreator' then 'CRIADOR DO CURSO'
                        when mr.shortname = 'editingteacher' then 'PROFESSOR EDITOR'
                        when mr.shortname = 'teacher' then 'PROFESSOR'
                        when mr.shortname = 'student' then 'ESTUDANTE'
                        when mr.shortname = 'guest' then 'VISITANTE'
                        else 'USUÁRIO'
                    end as perfil,
                    case when mu.city is null then '-' else upper(trim(mu.city)) end as cidade,
                    case when mu.lastaccess = 0 then '-' else from_unixtime(mu.lastaccess,'%d/%m/%Y %h:%i') end as ultimo_acesso
                from 
                    mdl_user mu
                join
                    mdl_role_assignments mra on mu.id = mra.userid 
                join 
                    mdl_role mr on mra.roleid = mr.id
                where
                    mu.id = $USER->id
                    and mu.deleted = 0
                ) tab
            group by 
                tab.id;";
    $rs = $DB->get_records_sql($sql);



    if($rs){
        $resultSet = json_decode(json_encode($rs,JSON_UNESCAPED_UNICODE),true);

        foreach($resultSet as $row){
            $id = $row['id'];
            $percent =0;

                $sqlClomp = "select
                                distinct
                                cur.id,
                                mu.id as userid
    
                            from
                                mdl_user mu
                            join mdl_user_enrolments mue on
                                mue.userid = mu.id
                            join mdl_enrol me on
                                me.id = mue.enrolid
                            left join mdl_course cur on
                                cur.id = me.courseid
                            left join vw_course_category vcc on
                                vcc.id = cur.id
                            left join
                                mdl_course_modules_completion cmc on
                                cmc.userid = mu.id
                            left join
                            mdl_user_lastaccess mul on
                                mul.userid = mue.userid
                                and me.courseid = mul.courseid
                            where
                                    1 = 1
                                    and mu.id = $USER->id ".$filtro."";


            $rs2 = $DB->get_records_sql($sqlClomp);
            if($rs2){
//              ===========$resultSet2 => traz todos os cursod de todos os usuarios
                $resultSet2 = json_decode(json_encode($rs2,JSON_UNESCAPED_UNICODE),true);
                $contagem = array();
                $qtd = 0;
                $carga = 0;
                foreach($resultSet2 as $row2){
                    $hscomplete = false;
                    $userid = $row2['userid'];
                    $courseid = $row2['id'];

                    //===========SCRIPT PARA SABER QUANTOS CURSOS FORAM FINALIZADO==========================
                    $comp3 = $report3->get_calcula_pregresso_do_curso($userid, $courseid);
//                    if ($comp3) {
                    $result3 = json_decode(json_encode($comp3, JSON_UNESCAPED_UNICODE), true);

                    $t = count($result3);
                    $x = 0;

                    foreach ($result3 as $r3) {
                        if (($r3['its_done'] == "Sim")) {
                            $x++;
                        }
                    }
                    $percent = round(($x * 100) / $t);
                    if ($percent == 100) {
                        $qtd++;
                        $hscomplete = true;
                    }
//                    }
                    //========================================================================================
                    if($hscomplete){
                        $sql_wl = "select wl.courseid, wl.tempoemmin from mdl_eva_course_workload wl where wl.courseid = '{$courseid}';";
                        $rs3 = $DB->get_records_sql($sql_wl);

                        $workload = json_decode(json_encode($rs3,JSON_UNESCAPED_UNICODE),true);
                        foreach($workload as $row3){
                            $carga += (integer)$row3['tempoemmin'];

                        }
                    }
                }
                if($carga > 0){
                    $carga = $carga / 60;

                    $array['carga'] = number_format((float)$carga, 2, '.', '') . ' hrs';
                }else{
                    $array['carga'] = '0 hrs';
                }

                $array['cursos_completos'] = $qtd;

                foreach($row as $k => $v){
                    $array[$k] = $v;
                }
                $arr[] = $array;

                unset($carga);
            }
        }

        echo json_encode($arr,JSON_UNESCAPED_UNICODE);
    }else{
        $arr = '';
        echo json_encode($arr,JSON_UNESCAPED_UNICODE);
    }


//================================RELATORIO 4 - CURSOS POR USUARIO - ========================================


}else if($idReport == 4){
    $relatorio4 = new tabela_reports_users();
    $rs = $relatorio4->get_curso_usuario();

    $array = array();

    if($rs){
        $resultSet = json_decode(json_encode($rs,JSON_UNESCAPED_UNICODE),true);

        foreach($resultSet as $row){
            $iduser = $row['userid'];
            $courseid = $row['courseid'];

            $x = 0;

            $comp = $relatorio4->get_calcula_pregresso_do_curso($iduser, $courseid);

            if($comp){
                $resultSet2 = json_decode(json_encode($comp,JSON_UNESCAPED_UNICODE),true);
                $t = count($resultSet2);
                foreach($resultSet2 as $row2){
                    if(($row2['its_done'] == "Sim")){
                        $x++;
                    }
                }

                $percent = round(($x * 100) / $t);
            }else{
                $percent = 0;
            }

            if($percent == 100){
                $array['status'] = 'CONCLUÍDO';
            }else{
                $array['status'] = 'CURSANDO';
            }

            foreach($row as $k => $v){
                $array[$k] = $v;
            }

            $arr[] = $array;
        }

        echo json_encode($arr,JSON_UNESCAPED_UNICODE);
    }else{
        $arr = '';
        echo json_encode($arr,JSON_UNESCAPED_UNICODE);
    }


//================================RELATORIO 5 - HISTORICO POR ALUNO - ========================================


}else if($idReport == 5){
    $userid = isset($_REQUEST['filterUsers5']) ? $_REQUEST['filterUsers5'] : "";
    $courseid = isset($_REQUEST['filterCursos5']) ? $_REQUEST['filterCursos5'] : "";

    $sql = "select
                cm.id,
                CASE
                    WHEN m.name = 'assign'  THEN (SELECT name FROM mdl_assign WHERE id = cm.instance)
                    WHEN m.name = 'assignment'  THEN (SELECT name FROM mdl_assignment WHERE id = cm.instance)
                    WHEN m.name = 'book'  THEN (SELECT name FROM mdl_book WHERE id = cm.instance)
                    WHEN m.name = 'chat'  THEN (SELECT name FROM mdl_chat WHERE id = cm.instance)
                    WHEN m.name = 'choice'  THEN (SELECT name FROM mdl_choice WHERE id = cm.instance)
                    WHEN m.name = 'data'  THEN (SELECT name FROM mdl_data WHERE id = cm.instance)
                    WHEN m.name = 'feedback'  THEN (SELECT name FROM mdl_feedback WHERE id = cm.instance)
                    WHEN m.name = 'folder'  THEN (SELECT name FROM mdl_folder WHERE id = cm.instance)
                    WHEN m.name = 'forum' THEN (SELECT name FROM mdl_forum WHERE id = cm.instance)
                    WHEN m.name = 'glossary' THEN (SELECT name FROM mdl_glossary WHERE id = cm.instance)
                    WHEN m.name = 'h5pactivity' THEN (SELECT name FROM mdl_h5pactivity WHERE id = cm.instance)
                    WHEN m.name = 'imscp' THEN (SELECT name FROM mdl_imscp WHERE id = cm.instance)
                    WHEN m.name = 'label'  THEN (SELECT name FROM mdl_label WHERE id = cm.instance)
                    WHEN m.name = 'lesson'  THEN (SELECT name FROM mdl_lesson WHERE id = cm.instance)
                    WHEN m.name = 'lti'  THEN (SELECT name FROM mdl_lti  WHERE id = cm.instance)
                    WHEN m.name = 'page'  THEN (SELECT name FROM mdl_page WHERE id = cm.instance)
                    WHEN m.name = 'quiz'  THEN (SELECT name FROM mdl_quiz WHERE id = cm.instance)
                    WHEN m.name = 'resource'  THEN (SELECT name FROM mdl_resource WHERE id = cm.instance)
                    WHEN m.name = 'scorm'  THEN (SELECT name FROM mdl_scorm WHERE id = cm.instance)
                    WHEN m.name = 'survey'  THEN (SELECT name FROM mdl_survey WHERE id = cm.instance)
                    WHEN m.name = 'url'  THEN (SELECT name FROM mdl_url  WHERE id = cm.instance)
                    WHEN m.name = 'wiki' THEN (SELECT name FROM mdl_wiki  WHERE id = cm.instance)
                    WHEN m.name = 'workshop' THEN (SELECT name FROM mdl_workshop  WHERE id = cm.instance)
                ELSE \"Other activity\"
                END AS activityname,
                cm.course as courseid,
                c.fullname as coursename,
                cm.section as sectionid,
                case when cs.name is null then '--' else cs.name end as sectionname,
                tab.userid,
                case when tab.instrumento is null then '--' else tab.instrumento end as instrumento,
                case when tab.its_done is null then 'Não' else tab.its_done end as its_done
            from
                mdl_course_modules cm
            join
                mdl_course c on
                c.id = cm.course
            join 
                mdl_modules m on
                m.id = cm.module
            join 
                mdl_course_sections cs on
                cs.id = cm.section
                and cs.course = c.id
            left join 
                (
                select
                    distinct 
                    u.id as userid,
                    c.id as courseid,
                    m.name as instrumento,
                    cs.id as sectionid,
                    cs.name as sectionname,
                    cm.id as activityid,
                    case
                        when cmc.completionstate = 0 then 'Não'
                        when cmc.completionstate = 1 then 'Sim'
                        when cmc.completionstate = 2 then 'Sim'
                        when cmc.completionstate = 3 then 'Sim com falha'
                        else 'Não'
                    end as its_done
                from
                    mdl_course_modules_completion cmc
                left join mdl_user u on
                    cmc.userid = u.id
                left join mdl_course_modules cm on
                    cmc.coursemoduleid = cm.id
                left join mdl_course c on
                    cm.course = c.id
                left join mdl_course_sections cs on
                    cs.course = c.id
                    and cs.id = cm.`section`
                join mdl_modules m on
                    cm.module = m.id
                where
                    u.id = $userid and c.id = $courseid 
                order by
                    cm.`section`,
                    m.id
            ) tab on tab.courseid = cm.course and tab.sectionid = cm.section and tab.activityid = cm.id
            where 
                c.id = $courseid and cm.completion > 0 and cm.visible = 1 
            order by 
                cm.section,cm.id asc";

    $rs = $DB->get_records_sql($sql);

    $array = array();

    if($rs){
        $resultSet = json_decode(json_encode($rs,JSON_UNESCAPED_UNICODE),true);

        foreach($resultSet as $row){
            foreach($row as $k => $v){
                $array[$k] = $v;
            }

            $arr[] = $array;
        }

        echo json_encode($arr,JSON_UNESCAPED_UNICODE);
    }else{
        $arr = '';
        echo json_encode($arr,JSON_UNESCAPED_UNICODE);
    }
}else if($idReport == 6){
    // $primeiroAcessoStart6 = isset($_REQUEST['primeiroAcessoStart6']) ? $_REQUEST['primeiroAcessoStart6'] : "";
    // $primeiroAcessoEnd6   = isset($_REQUEST['primeiroAcessoEnd6'])   ? $_REQUEST['primeiroAcessoEnd6']   : "";
    // $matriculaStart6      = isset($_REQUEST['matriculaStart6'])      ? $_REQUEST['matriculaStart6']      : "";
    // $matriculaEnd6        = isset($_REQUEST['matriculaEnd6'])        ? $_REQUEST['matriculaEnd6']        : "";
    // $ultacessoStart6      = isset($_REQUEST['ultacessoStart6'])      ? $_REQUEST['ultacessoStart6']      : "";
    // $ultacessoEnd6        = isset($_REQUEST['ultacessoEnd6'])        ? $_REQUEST['ultacessoEnd6']        : "";

    // if(!empty($primeiroAcessoStart6) and (!is_null($primeiroAcessoStart6))){
    //     $filtro .= " AND STR_TO_DATE(vufca.data_acesso,'%d/%m/%Y') >= STR_TO_DATE('".$primeiroAcessoStart6."','%d/%m/%Y') ";
    // }

    // if(!empty($primeiroAcessoEnd6) and (!is_null($primeiroAcessoEnd6))){
    //     $filtro .= " AND STR_TO_DATE(vufca.data_acesso,'%d/%m/%Y') <= STR_TO_DATE('".$primeiroAcessoEnd6."','%d/%m/%Y') ";
    // }

    // if(!empty($matriculaStart6) and (!is_null($matriculaStart6))){
    //     $filtro .= " AND STR_TO_DATE(vued.timecreated,'%d/%m/%Y') >= STR_TO_DATE('".$matriculaStart6."','%d/%m/%Y') ";
    // }

    // if(!empty($matriculaEnd6) and (!is_null($matriculaEnd6))){
    //     $filtro .= " AND STR_TO_DATE(vued.timecreated,'%d/%m/%Y') <= STR_TO_DATE('".$matriculaEnd6."','%d/%m/%Y') ";
    // }

    // if(!empty($ultacessoStart6) and (!is_null($ultacessoStart6))){
    //     $filtro .= " AND STR_TO_DATE(mul.timeaccess,'%d/%m/%Y') >= STR_TO_DATE('".$ultacessoStart6."','%d/%m/%Y') ";
    // }

    // if(!empty($ultacessoEnd6) and (!is_null($ultacessoEnd6))){
    //     $filtro .= " AND STR_TO_DATE(mul.timeaccess,'%d/%m/%Y') <= STR_TO_DATE('".$ultacessoEnd6."','%d/%m/%Y') ";
    // }

    $filterUsers6 = isset($_REQUEST['filterUsers6']) ? $_REQUEST['filterUsers6'] : "";
    $filterCursos6 = isset($_REQUEST['filterCursos6']) ? $_REQUEST['filterCursos6'] : "";

    if(!empty($filterUsers6) and (!is_null($filterUsers6))){
        $filtro .= " AND mue.userid = $filterUsers6 ";
    }

    if(!empty($filterCursos6) and (!is_null($filterCursos6))){
        $filtro .= " AND me.courseid = $filterCursos6 ";
    }

    $sql = "select
                distinct 
                mu.id,
                upper(concat(trim(mu.firstname), ' ', trim(mu.lastname))) as nome,
                case
                    when mu.ds_cargo is not null then mu.ds_cargo
                    else '-'
                end as cargo,
                mu.lotacao,
                vcc.categoryname as categoria,
                cur.id as courseid,
                cur.fullname as curso_concluido,
                vued.timecreated as dta_matricula,
                vufca.data_acesso as primeiro_acesso,
                case
                    when mul.timeaccess > 0 then from_unixtime(mul.timeaccess, '%d/%m/%Y')
                end as ultimo_acesso,
                case
                    when (from_unixtime(cur.enddate,'%d/%m/%Y') > now()) and (mcc.timecompleted = 0) then 'CURSO ENCERRADO' 
                    when (mcc.timecompleted <> 0) then 'CONCLUÍDO'
                    else 'CURSANDO'
                end as st_status,
                vaug.AV1,
                vaug.AV2,
                vaug.AV3,
                vaug.AV4,
                vaug.AV5,
                vaug.AV6,
                vaug.AV7,
                vaug.AV8,
                vaug.AV9,
                vaug.AV10,
                vaug.AV11,
                vaug.AV12,
                vaug.AV13,
                vaug.AV14
            from
                mdl_user mu
            join mdl_user_enrolments mue on
                mue.userid = mu.id
            join mdl_enrol me on
                me.id = mue.enrolid
            left join mdl_course cur on
                cur.id = me.courseid
            left join vw_course_category vcc on
                vcc.id = cur.id
            join 
                vw_users_enrol_date vued on
                vued.relateduserid = mue.userid
                and vued.courseid = me.courseid
            left join 
                mdl_course_completions mcc on
                mcc.userid = mu.id
                and mcc.course = me.courseid
            left join
                vw_user_first_course_access vufca on
                vufca.userid = mue.userid
                and me.courseid = vufca.courseid
            left join
                mdl_user_lastaccess mul on mul.userid = mue.userid and me.courseid = mul.courseid 
            left join 
                vw_all_users_grades vaug on vaug.userid = mue.userid and me.courseid = vaug.courseid
            where 
                1=1 " . $filtro;
    $rs = $DB->get_records_sql($sql);
    // echo $sql;die;
    $array = array();

    if($rs){
        $resultSet = json_decode(json_encode($rs,JSON_UNESCAPED_UNICODE),true);

        foreach($resultSet as $row){
            $id = $row['id'];
            $courseid = $row['courseid'];

            $sql2 = 'select
                        vueg.userid,
                        vueg.courseid,
                        avg(vueg.pointsobtained) as media
                    from
                        vw_users_exams_grades vueg 
                    where 
                        vueg.userid = '.$id.' 
                        and vueg.courseid = '.$courseid.'';
            $rs2 = $DB->get_records_sql($sql2);

            if($rs2){
                $resultSet2 = json_decode(json_encode($rs2,JSON_UNESCAPED_UNICODE),true);

                foreach($resultSet2 as $row2){
                    $array['media']  = (!is_null($row2['media']) ? $row2['media'] : "--");
                    // $array['mencao'] = (!is_null($row2['mencao']) ? $row2['mencao'] : "--");
                }
            }

            foreach($row as $k => $v){
                $array[$k] = $v;

                $btn = '';
                $btn .= '
                <button type="button" class="btn btn-primary" onclick="modalShow('. $id .','. $courseid .');">
                    Notas
                </button>
                ';

                if($array['media'] !== "--"){
                    $array['acoes'] = $btn;
                }else{
                    $array['acoes'] = '--';
                }
            }

            $arr[] = $array;
        }

        echo json_encode($arr,JSON_UNESCAPED_UNICODE);
    }else{
        $arr = '';
        echo json_encode($arr,JSON_UNESCAPED_UNICODE);
    }
}

