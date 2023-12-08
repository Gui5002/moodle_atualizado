<?php
require_once('../../../config.php');

defined('MOODLE_INTERNAL') || die();

global $CFG, $DB, $USER;

$idReport = isset($_REQUEST['id'])    ? (integer)$_REQUEST['id'] : 0;
$filtro   = "";
$x        = 0;

if($idReport == 5){
    $filterCursos5 = isset($_REQUEST['filterCursos5']) ? $_REQUEST['filterCursos5'] : "";
    $filterUsers5 = isset($_REQUEST['filterUsers5']) ? $_REQUEST['filterUsers5'] : "";
        
    if(!empty($filterUsers5) and !empty($filterCursos5)){
        $sql = "select
                    row_number() over() as id,
                    mu.id as userid,
                    upper(concat(trim(mu.firstname), ' ', trim(mu.lastname))) as nome,
                    case
                        when (mu.ds_cargo is not null) then upper(trim(mu.ds_cargo))
                        else '--'
                    end as cargo,
                    case
                        when (cert.userid is not null) then 'EMITIDO'
                        else '--'
                    end as certificado,
                    case
                        when (cert.timecreated is null) then '--'
                        else date_format(from_unixtime(cert.timecreated), '%d/%m/%Y')
                    end as dt_emissao_cert,
                    case
                        when (mu.lotacao is null) then '--'
                        when (mu.lotacao = '') then '--'
                        else upper(trim(mu.lotacao)) 
                    end as lotacao,
                    me.courseid as courseid,
                    upper(trim(cur.fullname)) as curso,
                    upper(trim(vcc.categoryname)) as categoryname,
                    mecw.workload,
                    case
                        when (vcc.sub_subcategoryname is not null) then upper(concat(vcc.subcategoryname, ' / ', vcc.sub_subcategoryname))
                        else upper(trim(vcc.subcategoryname))
                    end as subcategoryname,
                    case
                        when (cur.startdate > 0) then date_format(from_unixtime(cur.startdate), '%d/%m/%Y')
                        else '--'
                    end as data_inicio_curso,
                    case
                        when (cur.enddate > 0) then date_format(from_unixtime(cur.enddate), '%d/%m/%Y')
                        else '--'
                    end as data_fim_curso,
                    vued.timecreated as data_inscricao,
                    case
                        when (mue.timestart <> 0) then date_format(from_unixtime(mue.timestart), '%d/%m/%Y')
                        else '--'
                    end as data_inicio,
                    case
                        when (mue.timeend <> 0) then date_format(from_unixtime(mue.timeend), '%d/%m/%Y')
                        else '--'
                    end as data_fim
                from
                    mdl_user mu
                join mdl_user_enrolments mue on
                    mue.userid = mu.id
                join mdl_enrol me on
                    me.id = mue.enrolid
                left join mdl_course cur on
                    cur.id = me.courseid
                left join 
                    mdl_eva_course_workload mecw 
                    on mecw.courseid = me.courseid 
                left join vw_course_category vcc on
                    vcc.id = cur.id
                left join 
                    (select
                        msi.userid as userid,
                        msi.timecreated as timecreated,
                        ms.course as course
                    from
                        mdl_simplecertificate_issues msi
                    left join mdl_simplecertificate ms on
                        ms.id = msi.certificateid
                    where
                        (msi.timedeleted is null)) cert on cert.userid = mu.id and cert.course = me.courseid
                join 
                    vw_users_enrol_date vued on
                    vued.relateduserid = mue.userid and vued.courseid = me.courseid
                left join 
                    vw_user_first_course_access vufca on
                    vufca.userid = mue.userid and me.courseid = vufca.courseid
                left join 
                    mdl_user_lastaccess mul on
                    mul.userid = mue.userid and me.courseid = mul.courseid
                where 
                    mu.id > 2
                    and mue.userid =  " . $filterUsers5 . " 
                    and me.courseid = " . $filterCursos5 . "";
        $rs = $DB->get_records_sql($sql);
        $array = array();

        if($rs){
            $resultSet = json_decode(json_encode($rs,JSON_UNESCAPED_UNICODE),true); 

            foreach($resultSet as $row){
                $id = $row['userid'];
                $courseid = $row['courseid'];
                
                $sql2 = "select * from vw_all_progression ac where ac.userid = $id and ac.courseid = $courseid;";
                $rs2 = $DB->get_records_sql($sql2);
                $total = 0;
                $comp = 0;
                
                if($rs2){
                    $resultSet2 = json_decode(json_encode($rs2,JSON_UNESCAPED_UNICODE),true);

                    foreach($resultSet2 as $row2){
                        if(($row2['progress'] == "Completed") || ($row2['progress'] == "Completed with Pass")){
                            $comp++;
                        }
                        $total++;
                    }

                    $percent = ($comp * 100) / $total;

                    if($percent == 100){
                        $array['status'] = 'CONCLUÍDO';
                    }else{
                        $array['status'] = 'CURSANDO';
                    }
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
    }
}else if($idReport == 6){
    $filterUsers6 = isset($_REQUEST['filterUsers6']) ? $_REQUEST['filterUsers6'] : "";
        
    if(!empty($filterUsers6)){
        $sql = "select
                    row_number() over() as id,
                    mu.id as userid,
                    upper(concat(trim(mu.firstname), ' ', trim(mu.lastname))) as nome,
                    case
                        when (mu.ds_cargo is not null) then upper(trim(mu.ds_cargo))
                        else '--'
                    end as cargo,
                    case
                        when (cert.userid is not null) then 'EMITIDO'
                        else '--'
                    end as certificado,
                    case
                        when (cert.timecreated is null) then '--'
                        else date_format(from_unixtime(cert.timecreated), '%d/%m/%Y')
                    end as dt_emissao_cert,
                    case
                        when (mu.lotacao is null) then '--'
                        when (mu.lotacao = '') then '--'
                        else upper(trim(mu.lotacao)) 
                    end as lotacao,
                    me.courseid as courseid,
                    upper(trim(cur.fullname)) as curso,
                    upper(trim(vcc.categoryname)) as categoryname,
                    mecw.workload,
                    case
                        when (vcc.sub_subcategoryname is not null) then upper(concat(vcc.subcategoryname, ' / ', vcc.sub_subcategoryname))
                        else upper(trim(vcc.subcategoryname))
                    end as subcategoryname,
                    case
                        when (cur.startdate > 0) then date_format(from_unixtime(cur.startdate), '%d/%m/%Y')
                        else '--'
                    end as data_inicio_curso,
                    case
                        when (cur.enddate > 0) then date_format(from_unixtime(cur.enddate), '%d/%m/%Y')
                        else '--'
                    end as data_fim_curso,
                    vued.timecreated as data_inscricao,
                    case
                        when (mue.timestart <> 0) then date_format(from_unixtime(mue.timestart), '%d/%m/%Y')
                        else '--'
                    end as data_inicio,
                    case
                        when (mue.timeend <> 0) then date_format(from_unixtime(mue.timeend), '%d/%m/%Y')
                        else '--'
                    end as data_fim
                from
                    mdl_user mu
                join mdl_user_enrolments mue on
                    mue.userid = mu.id
                join mdl_enrol me on
                    me.id = mue.enrolid
                left join mdl_course cur on
                    cur.id = me.courseid
                left join 
                    mdl_eva_course_workload mecw 
                    on mecw.courseid = me.courseid 
                left join vw_course_category vcc on
                    vcc.id = cur.id
                left join 
                    (select
                        msi.userid as userid,
                        msi.timecreated as timecreated,
                        ms.course as course
                    from
                        mdl_simplecertificate_issues msi
                    left join mdl_simplecertificate ms on
                        ms.id = msi.certificateid
                    where
                        (msi.timedeleted is null)) cert on cert.userid = mu.id and cert.course = me.courseid
                join 
                    vw_users_enrol_date vued on
                    vued.relateduserid = mue.userid and vued.courseid = me.courseid
                left join 
                    vw_user_first_course_access vufca on
                    vufca.userid = mue.userid and me.courseid = vufca.courseid
                left join 
                    mdl_user_lastaccess mul on
                    mul.userid = mue.userid and me.courseid = mul.courseid
                where 
                    mu.id > 2
                    and mue.userid =  " . $filterUsers5 . "";
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
    }
}
?>