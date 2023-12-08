<?php

defined('MOODLE_INTERNAL') || die();

class tabela_reports_users
{
    public function get_curso_categoria()
    {
        global $DB;

        $start = isset($_REQUEST['start']) ? $_REQUEST['start'] : "";
        $end   = isset($_REQUEST['end'])   ? $_REQUEST['end']   : "";
        $category = isset($_REQUEST['category']) ? $_REQUEST['category'] : "";
        $subcategory = isset($_REQUEST['subcategory']) ? $_REQUEST['subcategory'] : "";
        $curso = isset($_REQUEST['curso']) ? $_REQUEST['curso'] : "";
        $statusCurso = isset($_REQUEST['statusCurso']) ? $_REQUEST['statusCurso'] : "";

        if(!empty($start) and (!is_null($start))){
            $filtro2 .= " AND from_unixtime(mc.startdate) >= STR_TO_DATE('".$start."','%d/%m/%Y') ";
        }

        if(!empty($end) and (!is_null($end))){
            $filtro2 .= " AND from_unixtime(mc.enddate) <= STR_TO_DATE('".$end."','%d/%m/%Y') ";
        }

        if($category !== ""){
            $filtro .= " AND tab.categoryname like '".$category."' ";
        }

        if($subcategory !== ""){
            $filtro .= " AND tab.subcategoryname like '%".$subcategory."%' ";
        }

        if($curso !== ""){
            $filtro .= " AND tab.fullname like '".$curso."' ";
        }

        if($statusCurso !== ""){
            $filtro .= " AND tab.status_curso like '".$statusCurso."' ";
        }

        $sql = "select
                *,
                case
                    when tab.visible = 0 then 'CANCELADO' else 
                    case
                        when (tab.dta_inicio is null) and (tab.dta_final is null) then 'EM ANDAMENTO'
                        when (now() < tab.dta_inicio) then 'PROGRAMADO'
                        when (now() > tab.dta_final) then 'ENCERRADO'
                        when (now() <= tab.dta_final) then 'EM ANDAMENTO'
                        when (tab.dta_final is null) then 'EM ANDAMENTO'
                    end 
                end as status_curso
            from
                (
                select
                    mc.id,
                    upper(trim(vcc.categoryname)) as categoryname,
                    case 
                        when vcc.sub_subcategoryname is not null then upper(trim(concat(vcc.subcategoryname,' / ',vcc.sub_subcategoryname)))
                        else upper(trim(vcc.subcategoryname)) 
                    end as subcategoryname,
                    upper(trim(mc.fullname)) as fullname,
                    mecw.workload,
                    coalesce(vsic.total_inscritos,0) as total_inscritos,
                    coalesce(vucc.total_completos,0) as concluidos,
                    coalesce(vcpc.total_certi,0) as certificados,
                    case when mc.startdate = 0 then null else FROM_UNIXTIME(mc.startdate) end as dta_inicio,
                    case when mc.enddate = 0 then null else FROM_UNIXTIME(mc.enddate) end as dta_final,
                    mc.visible
                from
                    mdl_course mc 
                left join
                    vw_course_category vcc on 
                    vcc.id = mc.id 
                left join 
                    vw_studens_in_course vsic on
                    vsic.id = mc.id
                left join 
                    vw_user_course_complete vucc on
                    vucc.course = mc.id 
                left join 
                    vw_certificados_por_curso vcpc on
                    vcpc.course = mc.id 
                left join 
                    mdl_eva_course_workload mecw on mecw.courseid = mc.id
                where
                    1=1 ".$filtro2."
                ) tab 
            where 
                1=1" . $filtro;

        $rs = $DB->get_records_sql($sql);

        return $rs;
    }

    public function get_resultado_por_curso()
    {
        global $DB, $USER;

        $primeiroAcessoStart = isset($_REQUEST['primeiroAcessoStart']) ? $_REQUEST['primeiroAcessoStart'] : "";
        $primeiroAcessoEnd   = isset($_REQUEST['primeiroAcessoEnd'])   ? $_REQUEST['primeiroAcessoEnd']   : "";
        $matriculaStart      = isset($_REQUEST['matriculaStart'])      ? $_REQUEST['matriculaStart']      : "";
        $matriculaEnd        = isset($_REQUEST['matriculaEnd'])        ? $_REQUEST['matriculaEnd']        : "";
//        $ultacessoStart      = isset($_REQUEST['ultacessoStart'])      ? $_REQUEST['ultacessoStart']      : "";
//        $ultacessoEnd        = isset($_REQUEST['ultacessoEnd'])        ? $_REQUEST['ultacessoEnd']        : "";
        $periodoStart      = isset($_REQUEST['ultacessoStart'])      ? $_REQUEST['ultacessoStart']      : "";
        $periodoEnd        = isset($_REQUEST['ultacessoEnd'])        ? $_REQUEST['ultacessoEnd']        : "";

        //==========inicando uma nova busca por periodo==========================
//        $ultacessoEnd        = isset($_REQUEST['ultacessoEnd'])        ? $_REQUEST['ultacessoEnd']        : "";

        if(!empty($primeiroAcessoStart) and (!is_null($primeiroAcessoStart))){
            $filtro .= " AND STR_TO_DATE(vufca.data_acesso,'%d/%m/%Y') >= STR_TO_DATE('".$primeiroAcessoStart."','%d/%m/%Y') ";
        }

        if(!empty($primeiroAcessoEnd) and (!is_null($primeiroAcessoEnd))){
            $filtro .= " AND STR_TO_DATE(vufca.data_acesso,'%d/%m/%Y') <= STR_TO_DATE('".$primeiroAcessoEnd."','%d/%m/%Y') ";
        }

        if(!empty($matriculaStart) and (!is_null($matriculaStart))){
            $filtro .= " AND STR_TO_DATE(vued.timecreated,'%d/%m/%Y') >= STR_TO_DATE('".$matriculaStart."','%d/%m/%Y') ";
        }

        if(!empty($matriculaEnd) and (!is_null($matriculaEnd))){
            $filtro .= " AND STR_TO_DATE(vued.timecreated,'%d/%m/%Y') <= STR_TO_DATE('".$matriculaEnd."','%d/%m/%Y') ";
        }

//        if(!empty($ultacessoStart) and (!is_null($ultacessoStart))){
//            $filtro .= " AND from_unixtime(mul.timeaccess,'%Y-%m-%d') >= STR_TO_DATE('".$ultacessoStart."','%d/%m/%Y') ";
//        }
//
//        if(!empty($ultacessoEnd) and (!is_null($ultacessoEnd))){
//            $filtro .= " AND from_unixtime(mul.timeaccess,'%Y-%m-%d') <= STR_TO_DATE('".$ultacessoEnd."','%d/%m/%Y') ";
//        }

        if(!empty($periodoStart) and (!is_null($periodoStart))){
            $filtro .= " AND from_unixtime(cmc.timemodified,'%Y-%m-%d') >= STR_TO_DATE('".$periodoStart."','%d/%m/%Y') ";
        }

        if(!empty($periodoEnd) and (!is_null($periodoEnd))){
            $filtro .= " AND from_unixtime(cmc.timemodified,'%Y-%m-%d') <= STR_TO_DATE('".$periodoEnd."','%d/%m/%Y') ";
        }

        $sql = "select
                concat(mue.userid,'.',me.courseid) as id,
                mu.id as userid,
                upper(concat(trim(mu.firstname), ' ', trim(mu.lastname))) as nome,
                case
                    when mu.ds_cargo is not null then upper(trim(mu.ds_cargo))
                    else '-'
                end as cargo,
                upper(trim(mu.lotacao)) as lotacao,
                upper(trim(vcc.categoryname)) as categoria,
                cur.id as courseid,
                upper(trim(cur.fullname)) as curso_concluido,
                vued.timecreated as dta_matricula,                

                case
                    when mul.timeaccess > 0 then from_unixtime(mul.timeaccess, '%d/%m/%Y')
                end as ultimo_acesso
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
                mdl_course_modules_completion cmc on
                    cmc.userid = mu.id                    
            left join
            mdl_user_lastaccess mul on
                mul.userid = mue.userid
                and me.courseid = mul.courseid
            where
                1 = 1
                and mu.id = $USER->id ".$filtro."";

//
//                vufca.data_acesso as primeiro_acesso,

//        left join
//            vw_user_first_course_access vufca on
//                vufca.userid = mue.userid
//                and me.courseid = vufca.courseid

        $rs = $DB->get_records_sql($sql);

        return $rs;
    }

    public function get_calcula_pregresso_do_curso($iduser, $courseid)
    {
        global $DB;
        $compsql = "select cm.id, tab.its_done
            from mdl_course_modules cm
            join mdl_course c on c.id = cm.course
            join mdl_modules m on m.id = cm.module
            join mdl_course_sections cs on cs.id = cm.section
            and cs.course = c.id 
            left join (
                select distinct 
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
                from mdl_course_modules_completion cmc
                left join mdl_user u on cmc.userid = u.id
                left join mdl_course_modules cm on cmc.coursemoduleid = cm.id
                left join mdl_course c on cm.course = c.id
                left join mdl_course_sections cs on cs.course = c.id
                and cs.id = cm.`section` 
                join mdl_modules m on cm.module = m.id
                where u.id = $iduser 
                and c.id = $courseid order by cm.`section`, m.id
            ) 
                tab on tab.courseid = cm.course 
                and tab.sectionid = cm.section 
                and tab.activityid = cm.id
                where c.id = $courseid 
                and cm.completion > 0 
                and cm.visible = 1 order by cm.section,cm.id asc";

        $comp = $DB->get_records_sql($compsql);

        return $comp;
    }

    public function get_curso_usuario()
    {
        global $DB;

        $primeiroAcessoStart4 = isset($_REQUEST['primeiroAcessoStart4']) ? $_REQUEST['primeiroAcessoStart4'] : "";
        $primeiroAcessoEnd4   = isset($_REQUEST['primeiroAcessoEnd4'])   ? $_REQUEST['primeiroAcessoEnd4']   : "";
        $matriculaStart4      = isset($_REQUEST['matriculaStart4'])      ? $_REQUEST['matriculaStart4']      : "";
        $matriculaEnd4        = isset($_REQUEST['matriculaEnd4'])        ? $_REQUEST['matriculaEnd4']        : "";
        $ultacessoStart4      = isset($_REQUEST['ultacessoStart4'])      ? $_REQUEST['ultacessoStart4']      : "";
        $ultacessoEnd4        = isset($_REQUEST['ultacessoEnd4'])        ? $_REQUEST['ultacessoEnd4']        : "";

        if(!empty($primeiroAcessoStart4) and (!is_null($primeiroAcessoStart4))){
            $filtro .= " AND STR_TO_DATE(vufca.data_acesso,'%d/%m/%Y') >= STR_TO_DATE('".$primeiroAcessoStart4."','%d/%m/%Y') ";
        }

        if(!empty($primeiroAcessoEnd4) and (!is_null($primeiroAcessoEnd4))){
            $filtro .= " AND STR_TO_DATE(vufca.data_acesso,'%d/%m/%Y') <= STR_TO_DATE('".$primeiroAcessoEnd4."','%d/%m/%Y') ";
        }

        if(!empty($matriculaStart4) and (!is_null($matriculaStart4))){
            $filtro .= " AND STR_TO_DATE(vued.timecreated,'%d/%m/%Y') >= STR_TO_DATE('".$matriculaStart4."','%d/%m/%Y') ";
        }

        if(!empty($matriculaEnd4) and (!is_null($matriculaEnd4))){
            $filtro .= " AND STR_TO_DATE(vued.timecreated,'%d/%m/%Y') <= STR_TO_DATE('".$matriculaEnd4."','%d/%m/%Y') ";
        }

        if(!empty($ultacessoStart4) and (!is_null($ultacessoStart4))){
            $filtro .= " AND from_unixtime(mul.timeaccess,'%Y-%m-%d') >= STR_TO_DATE('".$ultacessoStart4."','%d/%m/%Y') ";
        }

        if(!empty($ultacessoEnd4) and (!is_null($ultacessoEnd4))){
            $filtro .= " AND from_unixtime(mul.timeaccess,'%Y-%m-%d') <= STR_TO_DATE('".$ultacessoEnd4."','%d/%m/%Y') ";
        }

        $sql = "select
                concat(mue.userid,'.',me.courseid) as id,
                mu.id as userid,
                upper(concat(trim(mu.firstname), ' ', trim(mu.lastname))) as nome,
                case
                    when mu.ds_cargo is not null then upper(trim(mu.ds_cargo))
                    else '-'
                end as cargo,
                upper(trim(mu.lotacao)) as lotacao,
                upper(trim(vcc.categoryname)) as categoria,
                cur.id as courseid,
                upper(trim(cur.fullname)) as curso_concluido,
                from_unixtime(mue.timecreated, '%d/%m/%Y') as dta_matricula,
                case
                    when mul.timeaccess > 0 then from_unixtime(mul.timeaccess, '%d/%m/%Y')
                end as ultimo_acesso
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
                mdl_user_lastaccess mul on
                mul.userid = mue.userid
                and me.courseid = mul.courseid
            where
                1 = 1
                and mu.id > 2 ".$filtro."";

        $rs = $DB->get_records_sql($sql);

        return $rs;
    }

}