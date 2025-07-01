<?php

namespace block_eva_reports_users\output;

defined('MOODLE_INTERNAL') || die();

use moodle_url;
use renderable;
use renderer_base;
use templatable;

class reports_users implements renderable, templatable {
    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return stdClass
     */

    public function export_for_template(renderer_base $output) {
        global $CFG,$PAGE,$DB, $USER;

        require_once($CFG->libdir . '/filelib.php');

        $id = optional_param('id', null, PARAM_INT);

        $data = new \stdClass();

        $data->wwwroot = $CFG->wwwroot;

        $categoria = $DB->get_records_sql('SELECT vcs.categoryid,  upper(trim(vcs.categoryname)) as categoryname FROM vw_category_subcategory vcs GROUP BY vcs.categoryname ORDER BY vcs.categoryname');
        $subcategoria = $DB->get_records_sql('SELECT vcs.subcategoryid,  upper(trim(vcs.subcategoryname)) as subcategoryname FROM vw_category_subcategory vcs WHERE vcs.subcategoryid IS NOT NULL GROUP BY vcs.categoryname ORDER BY vcs.subcategoryname');
        $sql_curso = "select
                        mc.id,
                        upper(trim(mc.fullname)) as fullname 
                    from
                        mdl_user mu
                    join mdl_user_enrolments mue on
                        mue.userid = mu.id
                    join mdl_enrol me on
                        me.id = mue.enrolid
                    left join 
                        mdl_course mc on mc.id = me.courseid 
                    where
                        mu.id > 2
                    group by
                        mc.fullname
                    order by
                        mc.fullname asc";
        $cursos = $DB->get_records_sql($sql_curso);
        $allCourse = $DB->get_records_sql('SELECT id,  upper(trim(fullname)) as fullname FROM {course} ORDER BY fullname asc');
        $sql_users = "select
                        mu.id,
                        upper(concat(trim(mu.firstname),' ',trim(mu.lastname))) as nome
                    from
                        mdl_user mu
                    join mdl_user_enrolments mue on
                        mue.userid = mu.id
                    join mdl_enrol me on
                        me.id = mue.enrolid
                    where
                        mu.id > 2 
                    group by 
                        upper(concat(trim(mu.firstname),' ',trim(mu.lastname)))
                    order by 
                        upper(concat(trim(mu.firstname),' ',trim(mu.lastname))) asc";
        $usuarios = $DB->get_records_sql($sql_users);
        $sql_user_all = "select
                            mu.id,
                            upper(concat(trim(mu.firstname),' ',trim(mu.lastname))) as nome
                        from
                            mdl_user mu
                        where
                            mu.id > 2 
                        group by 
                            upper(concat(trim(mu.firstname),' ',trim(mu.lastname)))
                        order by
                            upper(concat(trim(mu.firstname),' ',trim(mu.lastname))) asc";
        $usuariosAll = $DB->get_records_sql($sql_user_all);
        $sql_lotacao = "select
                            upper(trim(mu.lotacao)) as lotacao 
                        from
                            mdl_user mu
                        join mdl_user_enrolments mue on
                            mue.userid = mu.id
                        join mdl_enrol me on
                            me.id = mue.enrolid
                        where 
                            mu.lotacao is not null and mu.lotacao <> ''
                            and mu.id > 2
                        group by
                            upper(trim(mu.lotacao))
                        order by
                            upper(trim(mu.lotacao)) asc";
        $lotacao = $DB->get_records_sql($sql_lotacao);
        $sql_cargo = "select
                        upper(trim(mu.ds_cargo)) as cargo 
                    from
                        mdl_user mu
                    join mdl_user_enrolments mue on
                        mue.userid = mu.id
                    join mdl_enrol me on
                        me.id = mue.enrolid
                    where 
                        mu.ds_cargo is not null and mu.ds_cargo <> ''
                        and mu.id > 2
                    group by
                        upper(trim(mu.ds_cargo))
                    order by
                        upper(trim(mu.ds_cargo)) asc";
        $cargos = $DB->get_records_sql($sql_cargo);

        $text = '';
        $text .= '<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />';

        if(empty($id) and is_null($id)){
            $text = '
            <div class="alert alert-warning" role="alert">
                  Nenhum código de relatório foi encontrado.
            </div>
            ';
        }else{
            if($id == 1){
                $text .= '
                <input id="idReport" name="idReport" value="' . $id . '" type="hidden">
                <div id="modalDiv"></div>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <label id="arrowLabel" name="arrowLabel" class="em15 fGray negrito" style="cursor: pointer;">Filtro&nbsp<i id="arrow" name="arrow" class="fa fa-angle-down"></i></label>
                    </div>
                </div>
                <div class="card sombreamento" style="display: block;" id="cardFiltros" name="cardFiltros">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-3">
                                <label style="font-size: 0.625em !important;">Nome do curso</label>
                                <select id="filterCursos" name="filterCursos" class="form-control">
                                    <option value="">Selecione uma opção</option>';
                $arrCursos = json_decode(json_encode($allCourse,JSON_UNESCAPED_UNICODE),true);

                foreach($arrCursos as $row){
                    $text .= '<option value="'.$row['fullname'].'">'.strtoupper($row['fullname']).'</option>';
                }

                $text .= '
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <label style="font-size: 0.625em !important;">Categoria do curso</label>
                                <select id="filterCategory" name="filterCategory" class="form-control">
                                    <option value="">Selecione uma opção</option>';
                $categoria1_sql = " select
                                    distinct
                                    vcc.categoryid,
                                    upper(trim(vcc.categoryname)) as categoryname
                                from
                                    mdl_course mc
                                left join
                                    vw_course_category vcc on
                                    vcc.id = mc.id
                                left join 
                                    (
                                    select
                                        logs.courseid,
                                        count(logs.courseid) as total_acessos
                                    from
                                        mdl_logstore_standard_log logs
                                    where
                                        logs.eventname like '%course_viewed%'
                                        and logs.contextlevel = 50
                                    group by
                                        logs.courseid) vca on
                                    vca.courseid = mc.id
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
                                    mdl_eva_course_workload mecw on
                                    mecw.courseid = mc.id
                                where 
                                    vcc.categoryname is not null
                                order by
                                    vcc.categoryname asc;";
                $categoria1 = $DB->get_records_sql($categoria1_sql);
                $arrCategoria = json_decode(json_encode($categoria1,JSON_UNESCAPED_UNICODE),true);

                foreach($arrCategoria as $row){
                    $text .= '<option value="'.$row['categoryname'].'">'.$row['categoryname'].'</option>';
                }

                $text .= '
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <label style="font-size: 0.625em !important;">Sub-categoria do curso</label>
                                <select id="filterSubCategory" name="filterSubCategory" class="form-control" disabled>
                                    <option value="">Selecione uma opção</option>';
                // $arrSubcategoria = json_decode(json_encode($subcategoria,JSON_UNESCAPED_UNICODE),true);

                // foreach($arrSubcategoria as $row){
                //     $text .= '<option value="'.$row['subcategoryname'].'">'.$row['subcategoryname'].'</option>';
                // }

                $text .= '
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <label style="font-size: 0.625em !important;">Status do curso</label>
                                <select id="filterStatus" name="filterStatus" class="form-control">
                                    <option value="">Selecione uma opção</option>
                                    <option value="CANCELADO">CANCELADO</option>
                                    <option value="EM ANDAMENTO">EM ANDAMENTO</option>
                                    <option value="ENCERRADO">ENCERRADO</option>
                                    <option value="PROGRAMADO">PROGRAMADO</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-3">
                                <label style="font-size: 0.625em !important;">Período</label>
                                <div class="input-daterange input-group demo-3" id="datepicker">
                                    <input type="text" class="input-sm form-control" name="start" id="start" style="max-height: 40px;font-size: 0.625em !important;max-height: 27px;">
                                    <span class="input-group-addon" style="font-size: 0.625em !important;">até</span>
                                    <input type="text" class="input-sm form-control" name="end" id="end" style="max-height: 40px;font-size: 0.625em !important;max-height: 27px;">
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-3 mt-4">
                                <input type="button" style="width: 100%;" class="btn" id="btnLimparFiltro" name="btnLimparFiltro" value="Limpar">
                            </div>
                            <div class="col-sm-12 col-md-3 mt-4">
                                <input type="button" style="width: 100%;" class="btn" id="btnVoltar" name="btnVoltar" value="Voltar">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-5 table-responsive">
                        <table cellspacing="0" style="font-size: 1em; width: 100%;" id="tab_sug" name="tab_sug" class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">ID</th>
                                    <th>Categoria</th>
                                    <th>Subcategoria</th>
                                    <th>Nome Curso</th>
                                    <th style="text-align: center;">Acessos</th>
                                    <th style="text-align: center;">Carga horária</th>
                                    <th style="text-align: center;">Total de inscritos</th>
                                    <th style="text-align: center;">Concluintes</th>
                                    <th style="text-align: center;">Certificados emitidos</th>
                                    <th>Data inicial</th>
                                    <th>Data final</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                ';
            }else if($id == 2){
                $text .= '
                <input id="idReport" name="idReport" value="' . $id . '" type="hidden">
                <div id="modalDiv"></div>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <label id="arrowLabel" name="arrowLabel" class="em15 fGray negrito" style="cursor: pointer;">Filtro&nbsp<i id="arrow" name="arrow" class="fa fa-angle-down"></i></label>
                    </div>
                </div>
                <div class="card sombreamento" style="display: block;" id="cardFiltros" name="cardFiltros">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-3">
                                <label style="font-size: 0.625em !important;">Usuário</label>
                                
                                <select id="filterUsers2" name="filterUsers2" class="form-control" disabled="disabled">';
//                                    <option value="">Selecione uma opção</option>';
                    //                $arrUsers = json_decode(json_encode($usuarios,JSON_UNESCAPED_UNICODE),true);
                    //
                    //                foreach($arrUsers as $row){
                    //                }
                                    $text .= '<option value="'.$USER->id.'">'.strtoupper(fullname($USER)).'</option>';

//                          $text .= '
//                                </select>
//                            </div>
//                            <div class="col-sm-12 col-md-3">
//                                <label style="font-size: 0.625em !important;">Cargo</label>
//                                <select id="filterCargo2" name="filterCargo2" class="form-control">
//                                    <option value="">Selecione uma opção</option>';
//                                    $arrCargos = json_decode(json_encode($cargos,JSON_UNESCAPED_UNICODE),true);
//
//                                    foreach($arrCargos as $row){
//                                        $text .= '<option value="'.$row['cargo'].'">'.$row['cargo'].'</option>';
//                                    }

//                           $text .= '
//                                </select>
//                            </div>
//
//                            <div class="col-sm-12 col-md-3">
//                                <label style="font-size: 0.625em !important;">Lotação</label>
//                                <select id="filterLotacao2" name="filterLotacao2" class="form-control">
//                                    <option value="">Selecione uma opção</option>';
//                                    $arrLotacao = json_decode(json_encode($lotacao,JSON_UNESCAPED_UNICODE),true);
//
//                                    foreach($arrLotacao as $row){
//                                        $text .= '<option value="'.$row['lotacao'].'">'.$row['lotacao'].'</option>';
//                                    }

                                    $text .= '
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <label style="font-size: 0.625em !important;">Categoria</label>
                                <select id="filterCategory2" name="filterCategory2" class="form-control">
                                    <option value="">Selecione uma opção</option>';
                                        $categoria = "select
                                                    vcc.categoryid,
                                                    upper(vcc.categoryname) as categoryname 
                                                    from
                                                        mdl_user mu
                                                    join mdl_user_enrolments mue on
                                                        mue.userid = mu.id
                                                    join mdl_enrol me on
                                                        me.id = mue.enrolid
                                                    left join 
                                                        mdl_course mc on mc.id = me.courseid 
                                                    left join 
                                                        vw_course_category vcc on vcc.id = me.courseid 
                                                    group by
                                                        mc.fullname
                                                    order by
                                                        mc.fullname asc";
                                                    $categoria = $DB->get_records_sql($categoria);
                                                    $arrCategoria = json_decode(json_encode($categoria,JSON_UNESCAPED_UNICODE),true);

                                                    foreach($arrCategoria as $row){
                                                        $text .= '<option value="'.$row['categoryname'].'">'.$row['categoryname'].'</option>';
                                                    }

                                                    $text .= '
                                </select>
                            </div>

                            <div class="col-sm-12 col-md-3">
                                <label style="font-size: 0.625em !important;">Cursos</label>
                                <select id="filterCursos2" name="filterCursos2" class="form-control">
                                    <option value="">Selecione uma opção</option>';
                                        $arrCursos = json_decode(json_encode($cursos,JSON_UNESCAPED_UNICODE),true);

                                        foreach($arrCursos as $row){
                                            $text .= '<option value="'.$row['fullname'].'">'.$row['fullname'].'</option>';
                                        }

                                        $text .= '
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-3">
                                <label style="font-size: 0.625em !important;">Matrícula</label>
                                <div class="input-daterange input-group demo-4-matricula" id="matricula_div">
                                    <input type="text" class="input-sm form-control" name="matriculaStart" id="matriculaStart" style="max-height: 27px;">
                                    <span class="input-group-addon">até</span>
                                    <input type="text" class="input-sm form-control" name="matriculaEnd" id="matriculaEnd" style="max-height: 27px;">
                                </div>
                            </div>
                            ';
                // $text .= '<div class="col-sm-12 col-md-3">
                //     <label>Menção</label>
                //     <select id="filterMencao2" name="filterMencao2" class="form-control">
                //         <option value="">Selecione uma opção</option>
                //         <option value="Aprovado">Aprovado</option>
                //         <option value="Reprovado">Reprovado</option>
                //     </select>
                // </div>';



//                            <div class="col-sm-12 col-md-3">
//                                <label style="font-size: 0.625em !important;">Primeiro acesso</label>
//                                <div class="input-daterange input-group demo-4-acesso" id="primeiroAcesso_div">
//                                    <input type="text" class="input-sm form-control" name="primeiroAcessoStart" id="primeiroAcessoStart" style="max-height: 27px;">
//                                    <span class="input-group-addon">até</span>
//                                    <input type="text" class="input-sm form-control" name="primeiroAcessoEnd" id="primeiroAcessoEnd" style="max-height: 27px;">
//                                </div>
//                            </div>
                                $text .= '
                            <div class="col-sm-12 col-md-3">
                                <label style="font-size: 0.625em !important;">Periodo</label>
                                <div class="input-daterange input-group demo-4-ultacesso" id="ultacesso_div">
                                    <input type="text" class="input-sm form-control" name="ultacessoStart" id="ultacessoStart" style="max-height: 27px;">
                                    <span class="input-group-addon">até</span>
                                    <input type="text" class="input-sm form-control" name="ultacessoEnd" id="ultacessoEnd" style="max-height: 27px;">
                                </div>
                            </div>

                            
                            
                            <div class="col-sm-12 col-md-3 mt-4">
                                <input type="button" style="width: 100%;" class="btn btn-primary btn-lg" id="btnLimparFiltro2" name="btnLimparFiltro2" value="Limpar">
                            </div>
                            <div class="col-sm-12 col-md-3 mt-4">
                                <input type="button" style="width: 100%;" class="btn btn-primary btn-lg" id="btnVoltar" name="btnVoltar" value="Voltar">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-5 table-responsive">
                        <table cellspacing="0" style="font-size: 1em; width: 100%;" id="tab_sug2" name="tab_sug2" class="table table-hover table-bordered">
                            <thead style="background: #185287; color: #fff;">
                                <tr>
                                    <th>ID</th>
                                    <th style="text-align: center;">ID</th>
                                    <th>Nome</th>
                                    <th>Cargo</th>
                                    <th>Lotação</th>
                                    <th>Categoria</th>
                                    <th>Curso</th>
                                    <th style="text-align: center;">Data da matrícula</th>
                                    <th style="text-align: center;">Carga horaria (horas)</th>
                                    <th style="text-align: center;">Progresso</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                ';
            }else if($id == 3){
                $text .= '
                <input id="idReport" name="idReport" value="' . $id . '" type="hidden">
                <div id="modalDiv"></div>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <label id="arrowLabel" name="arrowLabel" class="em15 fGray negrito" style="cursor: pointer;">Filtro&nbsp<i id="arrow" name="arrow" class="fa fa-angle-down"></i></label>
                    </div>
                </div>
                <div class="card sombreamento" style="display: block;" id="cardFiltros" name="cardFiltros">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-2">
                                <label style="font-size: 0.625em !important;">Usuário</label>
                                <select id="filterUsers3" name="filterUsers3" class="form-control" disabled="disabled">';
//                                    <option value="">Selecione uma opção</option>';
//                                    $sql = "select
//                                                row_number() over() as id,
//                                                upper(concat(trim(mu.firstname),' ',trim(mu.lastname))) as nome
//                                            from
//                                                mdl_user mu
//                                            join
//                                                mdl_role_assignments mra on mu.id = mra.userid
//                                            join
//                                                mdl_role mr on mra.roleid = mr.id
//                                            join
//                                                vw_course_access vca on vca.userid = mu.id
//                                            where
//                                                mu.id > 2
//                                                and mu.deleted = 0
//                                            group by
//                                                upper(concat(trim(mu.firstname),' ',trim(mu.lastname)))
//                                            order by
//                                                upper(concat(trim(mu.firstname),' ',trim(mu.lastname))) asc;";
//                                    $usuarios = $DB->get_records_sql($sql);
//                                    $arrUsers = json_decode(json_encode($usuarios,JSON_UNESCAPED_UNICODE),true);
//
//                                    foreach($arrUsers as $row){
//                                    }

                                        $text .= '<option value="'.$USER->id.'">'.strtoupper(fullname($USER)).'</option>';

//                                $text .= '
//                                </select>
//                            </div>
//                            <div class="col-sm-12 col-md-2">
//                                <label style="font-size: 0.625em !important;">Cargo</label>
//                                <select id="filterCargo3" name="filterCargo3" class="form-control">
//                                    <option value="">Selecione uma opção</option>';
//                                    $sql = "select
//                                                row_number() over() as id,
//                                                upper(trim(mu.ds_cargo)) as cargo
//                                            from
//                                                mdl_user mu
//                                            join
//                                                mdl_role_assignments mra on mu.id = mra.userid
//                                            join
//                                                mdl_role mr on mra.roleid = mr.id
//                                            join
//                                                vw_course_access vca on vca.userid = mu.id
//                                            where
//                                                mu.id > 2
//                                                and mu.deleted = 0
//                                                and mu.ds_cargo is not null
//                                            group by
//                                                mu.ds_cargo
//                                            order by
//                                                mu.ds_cargo asc;";
//                                    $cargos = $DB->get_records_sql($sql);
//                                    $arrCargos = json_decode(json_encode($cargos,JSON_UNESCAPED_UNICODE),true);
//
//                                    foreach($arrCargos as $row){
//                                        $text .= '<option value="'.$row['cargo'].'">'.$row['cargo'].'</option>';
//                                    }

                                $text .= '
                                </select>
                            </div>

                            <div class="col-sm-12 col-md-2">
                                <label style="font-size: 0.625em !important;">Perfil</label>
                                <select id="filterPerfil3" name="filterPerfil3" class="form-control">
                                    <option value="">Selecione uma opção</option>';
                                    $sql = "select
                                                row_number() over() as id,
                                                tab.perfil
                                            from
                                                (
                                                select 
                                                    case 
                                                        when mr.shortname = 'manager' then 'GERENTE'
                                                        when mr.shortname = 'coursecreator' then 'CRIADOR DO CURSO'
                                                        when mr.shortname = 'editingteacher' then 'PROFESSOR EDITOR'
                                                        when mr.shortname = 'teacher' then 'PROFESSOR'
                                                        when mr.shortname = 'student' then 'ESTUDANTE'
                                                        when mr.shortname = 'guest' then 'VISITANTE'
                                                        else 'USUÁRIO'
                                                    end as perfil
                                                from 
                                                    mdl_user mu
                                                join
                                                    mdl_role_assignments mra on mu.id = mra.userid 
                                                join 
                                                    mdl_role mr on mra.roleid = mr.id
                                                join 
                                                    vw_course_access vca on vca.userid = mu.id
                                                where
                                                    mu.id > 2
                                                    and mu.deleted = 0
                                                group by 
                                                    mu.lotacao
                                                ) tab
                                            group by 
                                                tab.perfil
                                            order by 
                                                tab.perfil asc;";
                                    $lotacao = $DB->get_records_sql($sql);
                                    $arrLotacao = json_decode(json_encode($lotacao,JSON_UNESCAPED_UNICODE),true);

                                    foreach($arrLotacao as $row){
                                        $text .= '<option value="'.$row['perfil'].'">'.$row['perfil'].'</option>';
                                    }

                                $text .= '
                                </select>
                            </div>
                            
                            
                            <div class="col-sm-12 col-md-3">
                                <label style="font-size: 0.625em !important;">Periodo</label>
                                <div class="input-daterange input-group demo-periodo" id="ultacesso_div">
                                    <input type="text" class="input-sm form-control" name="ultacessoStart" id="ultacessoStart" style="max-height: 27px;">
                                    <span class="input-group-addon"> até </span>
                                    <input type="text" class="input-sm form-control" name="ultacessoEnd" id="ultacessoEnd" style="max-height: 27px;">
                                </div>
                            </div>
                            
                            <div class="col-sm-12 col-md-2 mt-4">
                                <input type="button" style="width: 100%;" class="btn btn-primary btn-lg" id="btnLimparFiltro3" name="btnLimparFiltro3" value="Limpar">
                            </div>
                            <div class="col-sm-12 col-md-2 mt-4">
                                <input type="button" style="width: 100%;" class="btn btn-primary btn-lg" id="btnVoltar" name="btnVoltar" value="Voltar">
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-5 table-responsive">
                        <table cellspacing="0" style="font-size: 1em; width: 100%;" id="tab_sug3" name="tab_sug3" class="table table-hover table-bordered">
                            <thead style="background: #185287; color: #fff;">
                                <tr>
                                    <th style="text-align: center;">ID</th>
                                    <th>Nome</th>
                                    <th>Cargo</th>
                                    <th>Órgão lotação</th>
                                    <th style="text-align: center;">Perfil</th>
                                    <th style="text-align: center;">Carga horária total</th>
                                    <th style="text-align: center;">Cursos concluídos</th>
                                    <th style="text-align: center;">Último acesso</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                ';
            }else if($id == 4){
                $text .= '
                <input id="idReport" name="idReport" value="' . $id . '" type="hidden">
                <div id="modalDiv"></div>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <label id="arrowLabel" name="arrowLabel" class="em15 fGray negrito" style="cursor: pointer;">Filtro&nbsp<i id="arrow" name="arrow" class="fa fa-angle-down"></i></label>
                    </div>
                </div>
                <div class="card sombreamento" style="display: block;" id="cardFiltros" name="cardFiltros">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-3">
                                <label style="font-size: 0.625em !important;">Usuários</label>
                                <select id="filterUsers4" name="filterUsers4" class="form-control">
                                    <option value="">Selecione uma opção</option>';
                $arrUsers = json_decode(json_encode($usuarios,JSON_UNESCAPED_UNICODE),true);

                foreach($arrUsers as $row){
                    $text .= '<option value="'.$row['nome'].'">'.$row['nome'].'</option>';
                }

                $text .= '
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <label style="font-size: 0.625em !important;">Cargo</label>
                                <select id="filterCargo4" name="filterCargo4" class="form-control">
                                    <option value="">Selecione uma opção</option>';
                $arrCargos = json_decode(json_encode($cargos,JSON_UNESCAPED_UNICODE),true);

                foreach($arrCargos as $row){
                    $text .= '<option value="'.$row['cargo'].'">'.$row['cargo'].'</option>';
                }

                $text .= '
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <label style="font-size: 0.625em !important;">Lotação</label>
                                <select id="filterLotacao4" name="filterLotacao4" class="form-control">
                                    <option value="">Selecione uma opção</option>';
                $arrLotacao = json_decode(json_encode($lotacao,JSON_UNESCAPED_UNICODE),true);

                foreach($arrLotacao as $row){
                    $text .= '<option value="'.$row['lotacao'].'">'.$row['lotacao'].'</option>';
                }

                $text .= '
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <label style="font-size: 0.625em !important;">Categoria</label>
                                <select id="filterCategory4" name="filterCategory4" class="form-control">
                                    <option value="">Selecione uma opção</option>';
                $categoria = "select
                                vcc.categoryid,
                                upper(vcc.categoryname) as categoryname 
                            from
                                mdl_user mu
                            join mdl_user_enrolments mue on
                                mue.userid = mu.id
                            join mdl_enrol me on
                                me.id = mue.enrolid
                            left join 
                                mdl_course mc on mc.id = me.courseid 
                            left join 
                                vw_course_category vcc on vcc.id = me.courseid 
                            group by
                                mc.fullname
                            order by
                                mc.fullname asc";
                $categoria = $DB->get_records_sql($categoria);
                $arrCategoria = json_decode(json_encode($categoria,JSON_UNESCAPED_UNICODE),true);

                foreach($arrCategoria as $row){
                    $text .= '<option value="'.$row['categoryname'].'">'.$row['categoryname'].'</option>';
                }

                $text .= '
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-3">
                                <label style="font-size: 0.625em !important;">Cursos</label>
                                <select id="filterCursos4" name="filterCursos4" class="form-control">
                                    <option value="">Selecione uma opção</option>';
                $arrCursos = json_decode(json_encode($cursos,JSON_UNESCAPED_UNICODE),true);

                foreach($arrCursos as $row){
                    $text .= '<option value="'.$row['fullname'].'">'.$row['fullname'].'</option>';
                }

                $text .= '
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <label style="font-size: 0.625em !important;">Matrícula</label>
                                <div class="input-daterange input-group demo-5-matricula" id="matricula_div4">
                                    <input type="text" class="input-sm form-control" name="matriculaStart4" id="matriculaStart4" style="max-height: 27px;">
                                    <span class="input-group-addon">até</span>
                                    <input type="text" class="input-sm form-control" name="matriculaEnd4" id="matriculaEnd4" style="max-height: 27px;">
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <label style="font-size: 0.625em !important;">Primeiro acesso</label>
                                <div class="input-daterange input-group demo-5-acesso" id="primeiroAcesso_div4">
                                    <input type="text" class="input-sm form-control" name="primeiroAcessoStart4" id="primeiroAcessoStart4" style="max-height: 27px;">
                                    <span class="input-group-addon">até</span>
                                    <input type="text" class="input-sm form-control" name="primeiroAcessoEnd4" id="primeiroAcessoEnd4" style="max-height: 27px;">
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <label style="font-size: 0.625em !important;">Último acesso</label>
                                <div class="input-daterange input-group demo-5-ultacesso" id="ultacesso_div4">
                                    <input type="text" class="input-sm form-control" name="ultacessoStart4" id="ultacessoStart4" style="max-height: 27px;">
                                    <span class="input-group-addon">até</span>
                                    <input type="text" class="input-sm form-control" name="ultacessoEnd4" id="ultacessoEnd4" style="max-height: 27px;">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-3">
                                <label style="font-size: 0.625em !important;">Status do curso</label>
                                <select id="filterStatus4" name="filterStatus4" class="form-control">
                                    <option value="">Selecione uma opção</option>
                                    <option value="CURSANDO">CURSANDO</option>
                                    <option value="CONCLUÍDO">CONCLUÍDO</option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-3 mt-4">
                                <input type="button" style="width: 100%;" class="btn btn-primary btn-lg" id="btnLimparFiltro4" name="btnLimparFiltro4" value="Limpar">
                            </div>
                            <div class="col-sm-12 col-md-3 mt-4">
                                <input type="button" style="width: 100%;" class="btn btn-primary btn-lg" id="btnVoltar" name="btnVoltar" value="Voltar">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-5 table-responsive">
                        <table cellspacing="0" style="font-size: 1em; width: 100%;" id="tab_sug4" name="tab_sug4" class="table table-hover table-bordered">
                            <thead style="background: #185287; color: #fff;">
                                <tr>
                                    <th style="text-align: center;">ID</th>
                                    <th>Nome</th>
                                    <th>Cargo</th>
                                    <th>Lotação</th>
                                    <th>Categoria</th>
                                    <th>Curso</th>
                                    <th style="text-align: center;">Data da matrícula</th>
                                    <th style="text-align: center;">Primeiro acesso</th>
                                    <th style="text-align: center;">Último acesso</th>
                                    <th style=text-align: center;">Status</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                ';
            }else if($id == 5){
                $text .= '
                <input id="idReport" name="idReport" value="' . $id . '" type="hidden">
                <div id="modalDiv"></div>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <label id="arrowLabel" name="arrowLabel" class="em15 fGray negrito" style="cursor: pointer;">Filtro e cabeçalho&nbsp<i id="arrow" name="arrow" class="fa fa-angle-down"></i></label>
                    </div>
                </div>
                <div class="card sombreamento" style="display: block;" id="cardFiltros" name="cardFiltros">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-3">
                                <label style="font-size: 0.625em !important;">Usuários</label>
                                <select id="filterUsers5" name="filterUsers5" class="form-control">
                                    <option value="">Selecione uma opção</option>';
                                    $arrUser = json_decode(json_encode($usuarios,JSON_UNESCAPED_UNICODE),true);

                                    if(count($arrUser) > 0){
                                        foreach($arrUser as $row){
                                            $text .= '<option value="'.$row['id'].'">'.$row['nome'].'</option>';
                                        }
                                    }
                                    $text .= '
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <label style="font-size: 0.625em !important;">Cursos</label>
                                <select id="filterCursos5" name="filterCursos5" class="form-control" disabled></select>
                            </div>
                            <div class="col-sm-12 col-md-3 mt-4">
                                <input type="button" style="width: 100%;" class="btn btn-primary btn-lg" id="btnLimparFiltro5" name="btnLimparFiltro5" value="Limpar">
                            </div>
                            <div class="col-sm-12 col-md-3 mt-4">
                                <input type="button" style="width: 100%;" class="btn btn-primary btn-lg" id="btnVoltar" name="btnVoltar" value="Voltar">
                            </div>
                        </div>
                        <div class="row" style="margin-left: 0px; margin-right: 0px; margin-top: 30px;">
                            <div class="col-md-12 ml-1 mr-1" style="padding: 15px; border: 3px solid #f0f0f0;">
                                <div class="row">
                                    <h3 style="margin: 0 auto;">EVA- Escola Virtual da AGU</h3>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-left: 4px; margin-right: -2px; background: #bfbfbf;">
                            <div class="col-md-12 ml-1 mr-1" style="padding: 15px;">
                                <div class="row">
                                    <h4 style="margin: 0 auto;">HISTÓRICO POR ALUNO</h4>
                                </div>
                                <div class="row">
                                    <h5 style="margin: 0 auto;font-weight: 600;">DADOS PESSOAIS</h5>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="row" style="margin: 10px;">
                                    <div class="col-md-4">
                                        <span style="font-weight: 600; font-size: 0.625em;">INSCRIÇÃO:&nbsp;</span>
                                    </div>
                                    <div class="col-md-8">
                                        <span style="font-size: 0.625em;" id="txtInscricao">--</span>
                                    </div>
                                </div>
                                <div class="row" style="margin: 10px;">
                                    <div class="col-md-4">
                                        <span style="font-weight: 600; font-size: 0.625em;">NOME:&nbsp;</span>
                                    </div>
                                    <div class="col-md-8">
                                        <span style="font-size: 0.625em;" id="txtNome">--</span>
                                    </div>
                                </div>
                                <div class="row" style="margin: 10px;">
                                    <div class="col-md-4">
                                        <span style="font-weight: 600; font-size: 0.625em;">CARGO:&nbsp;</span>
                                    </div>
                                    <div class="col-md-8">
                                        <span style="font-size: 0.625em;" id="txtCargo">--</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="row" style="margin: 10px;">
                                    <div class="col-md-4">
                                        <span style="font-weight: 600; font-size: 0.625em;">LOTAÇÃO:&nbsp;</span>
                                    </div>
                                    <div class="col-md-8">
                                        <span style="font-size: 0.625em;" id="txtLotacao">--</span>
                                    </div>
                                </div>
                                <div class="row" style="margin: 10px;">
                                    <div class="col-md-4">
                                        <span style="font-weight: 600; font-size: 0.625em;">CERTIFICADO:&nbsp;</span>
                                    </div>
                                    <div class="col-md-8">
                                        <span style="font-size: 0.625em;" id="txtCertificado">--</span>
                                    </div>
                                </div>
                                <div class="row" style="margin: 10px;">
                                    <div class="col-md-4">
                                        <span style="font-weight: 600; font-size: 0.625em;">DATA EMISSÃO:&nbsp;</span>
                                    </div>
                                    <div class="col-md-8">
                                        <span style="font-size: 0.625em;" id="txtEmissao">--</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-left: 4px; margin-right: -2px; background: #bfbfbf;">
                            <div class="col-md-12 ml-1 mr-1" style="padding: 15px;">
                                <div class="row">
                                    <h4 style="margin: 0 auto;font-weight: 600;">DADOS DO CURSO</h4>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="row" style="margin: 10px;">
                                    <div class="col-md-4">
                                        <span style="font-weight: 600; font-size: 0.625em;">CATEGORIA:&nbsp;</span>
                                    </div>
                                    <div class="col-md-8">
                                        <span style="font-size: 0.625em;" id="txtCategoria">--</span>
                                    </div>
                                </div>
                                <div class="row" style="margin: 10px;">
                                    <div class="col-md-4">
                                        <span style="font-weight: 600; font-size: 0.625em;">CURSO:&nbsp;</span>
                                    </div>
                                    <div class="col-md-8">
                                        <span style="font-size: 0.625em;" id="txtCurso">--</span>
                                    </div>
                                </div>
                                <div class="row" style="margin: 10px;">
                                    <div class="col-md-4">
                                        <span style="font-weight: 600; font-size: 0.625em;">STATUS:&nbsp;</span>
                                    </div>
                                    <div class="col-md-8">
                                        <span style="font-size: 0.625em;" id="txtStatus">--</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="row" style="margin: 10px;">
                                    <div class="col-md-4">
                                        <span style="font-weight: 600; font-size: 0.625em;">SUB-CATEGORIA:&nbsp;</span>
                                    </div>
                                    <div class="col-md-8">
                                        <span style="font-size: 0.625em;" id="txtSubCategoria">--</span>
                                    </div>
                                </div>
                                <div class="row" style="margin: 10px;">
                                    <div class="col-md-4">
                                        <span style="font-weight: 600; font-size: 0.625em;">CARGA HORÁRIA:&nbsp;</span>
                                    </div>
                                    <div class="col-md-8">
                                        <span style="font-size: 0.625em;" id="txtCargaHoraria">--</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-5 table-responsive">
                        <table cellspacing="0" style="font-size: 1em; width: 100%;" id="tab_sug5" name="tab_sug5" class="table table-hover table-bordered">
                            <thead style="background: #185287; color: #fff;">
                                <tr>   
                                    <th>ID</th>
                                    <th style="width: 40%;">Módulo</th>
                                    <th style="width: 40%;">Atividade</th>
                                    <th style="width: 10%;">Instrumento</th>
                                    <th style="width: 10%;">Concluiu a atividade</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                ';
            }else if($id == 6){
                $text .= '
                <input id="idReport" name="idReport" value="' . $id . '" type="hidden">
                <div id="modalDiv"></div>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <label id="arrowLabel" name="arrowLabel" class="em15 fGray negrito" style="cursor: pointer;">Filtro e cabeçalho&nbsp<i id="arrow" name="arrow" class="fa fa-angle-down"></i></label>
                    </div>
                </div>
                <div class="card sombreamento" style="display: block;" id="cardFiltros" name="cardFiltros">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-3">
                                <label>Usuário</label>
                                <select id="filterUsers6" name="filterUsers6" class="form-control">
                                    <option value="">Selecione uma opção</option>';
                $arrUsers = json_decode(json_encode($usuarios,JSON_UNESCAPED_UNICODE),true);

                foreach($arrUsers as $row){
                    $text .= '<option value="'.$row['id'].'">'.$row['nome'].'</option>';
                }

                $text .= '
                                </select>
                            </div>';
                $text .= '
                            <div class="col-sm-12 col-md-3">
                                <label>Cursos</label>
                                <select id="filterCursos6" name="filterCursos6" class="form-control" disabled></select>
                            </div>';
                $text .= '<div class="col-sm-12 col-md-2 mt-4">
                            <input type="button" style="width: 100%;" class="btn btn-primary btn-lg" id="btnLimparFiltro6" name="btnLimparFiltro6" value="Limpar">
                        </div>
                        <div class="col-sm-12 col-md-2 mt-4">
                            <input type="button" style="width: 100%;" class="btn btn-primary btn-lg" id="btnVoltar" name="btnVoltar" value="Voltar">
                        </div>';
                $text .= '</div>';

                $text .= '<div class="row" style="margin-left: 0px; margin-right: 0px; margin-top: 30px;">
                            <div class="col-md-12 ml-1 mr-1" style="padding: 15px; border: 3px solid #f0f0f0;">
                                <div class="row">
                                    <h3 style="margin: 0 auto;">EVA- Escola Virtual da AGU</h3>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-left: 4px; margin-right: -2px; background: #bfbfbf;">
                            <div class="col-md-12 ml-1 mr-1" style="padding: 15px;">
                                <div class="row">
                                    <h3 style="margin: 0 auto;">HISTÓRICO POR CURSO EVAGU</h3>
                                </div>
                                <div class="row">
                                    <h5 style="margin: 0 auto;font-weight: 600;">DADOS PESSOAIS</h5>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="row" style="margin: 10px;">
                                    <div class="col-md-4">
                                        <span style="font-weight: 600; font-size: 1em;">ID:&nbsp;</span>
                                    </div>
                                    <div class="col-md-8">
                                        <span style="font-size: 1em;" id="txtId6">--</span>
                                    </div>
                                </div>
                                <div class="row" style="margin: 10px;">
                                    <div class="col-md-4">
                                        <span style="font-weight: 600; font-size: 1em;">NOME:&nbsp;</span>
                                    </div>
                                    <div class="col-md-8">
                                        <span style="font-size: 1em;" id="txtNome6">--</span>
                                    </div>
                                </div>
                                <div class="row" style="margin: 10px;">
                                    <div class="col-md-4">
                                        <span style="font-weight: 600; font-size: 1em;">CARGO:&nbsp;</span>
                                    </div>
                                    <div class="col-md-8">
                                        <span style="font-size: 1em;" id="txtCargo6">--</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="row" style="margin: 10px;">
                                    <div class="col-md-4">
                                        <span style="font-weight: 600; font-size: 1em;">LOTAÇÃO:&nbsp;</span>
                                    </div>
                                    <div class="col-md-8">
                                        <span style="font-size: 1em;" id="txtLotacao6">--</span>
                                    </div>
                                </div>
                                <div class="row" style="margin: 10px;">
                                    <div class="col-md-4">
                                        <span style="font-weight: 600; font-size: 1em;">CPF:&nbsp;</span>
                                    </div>
                                    <div class="col-md-8">
                                        <span style="font-size: 1em;" id="txtCpf6">--</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-left: 4px; margin-right: -2px; background: #bfbfbf;">
                            <div class="col-md-12 ml-1 mr-1" style="padding: 15px;">
                                <div class="row">
                                    <h4 style="margin: 0 auto;font-weight: 600;">CURSOS INSCRITO</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-5 table-responsive">
                        <table cellspacing="0" style="font-size: 1em; width: 100%;" id="tab_sug6" name="tab_sug6" class="table table-hover table-bordered">
                            <thead style="background: #185287; color: #fff;">
                                <tr>
                                    <th style="width: 9%">ID</th>
                                    <th style="width: 9%">Nome</th>
                                    <th style="width: 9%">Curso</th>
                                    <th style="width: 9%">Categoria</th>
                                    <th style="width: 9%">Data da matrícula</th>
                                    <th style="width: 9%">Primeiro acesso</th>
                                    <th style="width: 9%">Último acesso</th>
                                    <th style="width: 9%">Status</th>
                                    <th style="width: 9%">Média</th>
                                    <th style="width: 9%">Notas</th>
                                    <th style="width: 9%">AV1</th>
                                    <th style="width: 9%">AV2</th>
                                    <th style="width: 9%">AV3</th>
                                    <th style="width: 9%">AV4</th>
                                    <th style="width: 9%">AV5</th>
                                    <th style="width: 9%">AV6</th>
                                    <th style="width: 9%">AV7</th>
                                    <th style="width: 9%">AV8</th>
                                    <th style="width: 9%">AV9</th>
                                    <th style="width: 9%">AV10</th>
                                    <th style="width: 9%">AV11</th>
                                    <th style="width: 9%">AV12</th>
                                    <th style="width: 9%">AV13</th>
                                    <th style="width: 9%">AV14</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                ';
            }
        }

        $data->text = $text;

        return $data;
    }

}

//                            <div class="col-sm-12 col-md-2">
//                                <label style="font-size: 0.625em !important;">Lotação</label>
//                                <select id="filterLotacao3" name="filterLotacao3" class="form-control">
//                                    <option value="">Selecione uma opção</option>';
//                                    $sql = "select
//                                                row_number() over() as id,
//                                                upper(trim(mu.lotacao)) as lotacao
//                                            from
//                                                mdl_user mu
//                                            join
//                                                mdl_role_assignments mra on mu.id = mra.userid
//                                            join
//                                                mdl_role mr on mra.roleid = mr.id
//                                            join
//                                                vw_course_access vca on vca.userid = mu.id
//                                            where
//                                                mu.id > 2
//                                                and mu.deleted = 0
//                                                and mu.lotacao is not null
//                                                and mu.lotacao <> ''
//                                            group by
//                                                mu.lotacao
//                                            order by
//                                                mu.lotacao asc;";
//                                    $lotacao = $DB->get_records_sql($sql);
//                                    $arrLotacao = json_decode(json_encode($lotacao,JSON_UNESCAPED_UNICODE),true);
//
//                                    foreach($arrLotacao as $row){
//                                        $text .= '<option value="'.$row['lotacao'].'">'.$row['lotacao'].'</option>';
//                                    }
//
//                                $text .= '
//                                </select>
//                            </div>
