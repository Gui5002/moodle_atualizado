<?php

namespace block_eva_reports\output;

defined('MOODLE_INTERNAL') || die();

use moodle_url;
use renderable;
use renderer_base;
use templatable;

class reports implements renderable, templatable {
    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return stdClass
     */

    public function export_for_template(renderer_base $output) {
        global $CFG,$PAGE,$DB;

        require_once($CFG->libdir . '/filelib.php');

        $id = optional_param('id', null, PARAM_INT);

        $data = new \stdClass();

        $data->wwwroot = $CFG->wwwroot;

        $categoria = $DB->get_records_sql('SELECT DISTINCT upper(trim(`name`)) as categoria FROM moodle_prod.mdl_course_categories WHERE `parent` = 0 ORDER BY categoria ASC');
        $subcategoria = $DB->get_records_sql('SELECT DISTINCT upper(trim(`name`)) as subcategoria FROM moodle_prod.mdl_course_categories WHERE `parent` > 0 ORDER BY subcategoria ASC');
        $allCourse = $DB->get_records_sql('SELECT DISTINCT upper(trim(fullname)) as curso, id  FROM moodle_prod.mdl_course ORDER BY curso ASC');
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
                    $text .= '<option value="'.$row['curso'].'">'.$row['curso'].'</option>';
                }

                $text .= '
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <label style="font-size: 0.625em !important;">Categoria do curso</label>
                                <select id="filterCategory" name="filterCategory" class="form-control">
                                    <option value="">Selecione uma opção</option>';
                $arrCategoria = json_decode(json_encode($categoria,JSON_UNESCAPED_UNICODE),true);

                foreach($arrCategoria as $row){
                    $text .= '<option value="'.$row['categoria'].'">'.$row['categoria'].'</option>';
                }

                $text .= '
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <label style="font-size: 0.625em !important;">Sub-categoria do curso</label>
                                <select id="filterSubCategory" name="filterSubCategory" class="form-control">
                                    <option value="">Selecione uma opção</option>';
                $arrSubcategoria = json_decode(json_encode($subcategoria,JSON_UNESCAPED_UNICODE),true);

                foreach($arrSubcategoria as $row){
                     $text .= '<option value="'.$row['subcategoria'].'">'.$row['subcategoria'].'</option>';
                 }

                $text .= '
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <label style="font-size: 0.625em !important;">Status do curso</label>
                                <select id="filterStatus" name="filterStatus" class="form-control">
                                    <option value="">Selecione uma opção</option>
                                    <option value="OCULTO">OCULTO</option>
                                    <option value="EM ANDAMENTO">EM ANDAMENTO</option>
                                    <option value="ENCERRADO">ENCERRADO</option>
                                    <option value="PROGRAMADO">PROGRAMADO</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
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
                                    <th>Categoria</th>
                                    <th>Subcategoria</th>
                                    <th>Nome Curso</th>
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
                    <div class="col-sm-8 col-md-12 col-lg-12 col-xl-12">
                        <label id="arrowLabel" name="arrowLabel" class="em15 fGray negrito" style="cursor: pointer;">Filtro&nbsp<i id="arrow" name="arrow" class="fa fa-angle-down"></i></label>
                    </div>
                </div>
                <div class="card sombreamento" style="display: block;" id="cardFiltros" name="cardFiltros">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-3">
                                <label style="font-size: 0.625em !important;">Usuários</label>
                                <select id="filterUsers2" name="filterUsers2" class="form-control">
                                    <option value="">Selecione uma opção</option>';
                $arrUsers = json_decode(json_encode($usuarios,JSON_UNESCAPED_UNICODE),true);

                foreach($arrUsers as $row){
                    $text .= '<option value="'.$row['nome'].'">'.$row['nome'].'</option>';
                }

                $text .= '
                                </select>
                            </div>    
                            <div class="col-sm-8 col-md-3">
                                <label style="font-size: 0.625em !important;">Categoria</label>
                                <select id="filterCategory2" name="filterCategory2" class="form-control">
                                    <option value="">Selecione uma opção</option>';
                $categoria1 = $DB->get_records_sql('SELECT DISTINCT upper(trim(nome_categoria)) as categoria FROM vw_courses_per_user GROUP BY categoria ORDER BY categoria ASC');
                $arrCategoria = json_decode(json_encode($categoria1,JSON_UNESCAPED_UNICODE),true);

                foreach($arrCategoria as $row){
                    $text .= '<option value="'.$row['categoria'].'">'.$row['categoria'].'</option>';
                }

                $text .= '
                                </select>
                            </div>
                        
                        
                            <div class="col-sm-8 col-md-3">
                                <label style="font-size: 0.625em !important;">Cursos</label>
                                <select id="filterCursos2" name="filterCursos2" class="form-control">
                                    <option value="">Selecione uma opção</option>';
                $arrCursos = json_decode(json_encode($allCourse,JSON_UNESCAPED_UNICODE),true);

                foreach($arrCursos as $row){
                    $text .= '<option value="'.$row['curso'].'">'.$row['curso'].'</option>';
                }

                $text .= '
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <label style="font-size: 0.625em !important;">Matrícula</label>
                                <div class="input-group" id="matricula_div2">
                                    <input type="date" class="input-sm form-control" name="matriculaStart2" id="matriculaStart2" style="max-height: 27px;">
                                    <span class="input-group-addon">até</span>
                                    <input type="date" class="input-sm form-control" name="matriculaEnd2" id="matriculaEnd2" style="max-height: 27px;">
                                </div>
                            </div>
                       </div>
                            ';



                $text .= '
                            
                        <div class="row">
                            
                            
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
                            <thead style="background: #01101f; color: #fff;">
                                <tr>
                                    <th>Nome</th>
                                    <th>Curso</th>
                                    <th>Categoria</th>
                                    <th>Matrícula</th>
                                    <th style="text-align: center;">Carga Horaria</th>
                                    <th style="text-align: center;">Atividades <br> Concluidas <br> (Obrigatórias)</th>
                                    <th style="text-align: center;">Progresso <br>(%)</th>
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
                                <label style="font-size: 0.625em !important;">Usuários</label>
                                <select id="filterUsers3" name="filterUsers3" class="form-control">
                                    <option value="">Selecione uma opção</option>';
                                    $sql = "select 
                                                row_number() over() as id,
                                                upper(concat(trim(mu.firstname),' ',trim(mu.lastname))) as nome
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
                                                upper(concat(trim(mu.firstname),' ',trim(mu.lastname)))
                                            order by 
                                                upper(concat(trim(mu.firstname),' ',trim(mu.lastname))) asc;";
                                    $usuarios = $DB->get_records_sql($sql);
                                    $arrUsers = json_decode(json_encode($usuarios,JSON_UNESCAPED_UNICODE),true);

                                    foreach($arrUsers as $row){
                                        $text .= '<option value="'.$row['nome'].'">'.$row['nome'].'</option>';
                                    }

                                $text .= '
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-2">
                                <label style="font-size: 0.625em !important;">Cargo</label>
                                <select id="filterCargo3" name="filterCargo3" class="form-control">
                                    <option value="">Selecione uma opção</option>';
                                    $sql = "select 
                                                row_number() over() as id,
                                                upper(trim(mu.ds_cargo)) as cargo
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
                                                and mu.ds_cargo is not null
                                            group by 
                                                mu.ds_cargo 
                                            order by 
                                                mu.ds_cargo asc;";
                                    $cargos = $DB->get_records_sql($sql);
                                    $arrCargos = json_decode(json_encode($cargos,JSON_UNESCAPED_UNICODE),true);

                                    foreach($arrCargos as $row){
                                        $text .= '<option value="'.$row['cargo'].'">'.$row['cargo'].'</option>';
                                    }

                                $text .= '
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-2">
                                <label style="font-size: 0.625em !important;">Lotação</label>
                                <select id="filterLotacao3" name="filterLotacao3" class="form-control">
                                    <option value="">Selecione uma opção</option>';
                                    $sql = "select 
                                                row_number() over() as id,
                                                upper(trim(mu.lotacao)) as lotacao 
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
                                                and mu.lotacao is not null
                                                and mu.lotacao <> '' 
                                            group by 
                                                mu.lotacao 
                                            order by 
                                                mu.lotacao asc;";
                                    $lotacao = $DB->get_records_sql($sql);
                                    $arrLotacao = json_decode(json_encode($lotacao,JSON_UNESCAPED_UNICODE),true);

                                    foreach($arrLotacao as $row){
                                        $text .= '<option value="'.$row['lotacao'].'">'.$row['lotacao'].'</option>';
                                    }

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
                            <thead style="background: #01101f; color: #fff;">
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
                $arrCategoria = json_decode(json_encode($categoria,JSON_UNESCAPED_UNICODE),true);

                foreach($arrCategoria as $row){
                    $text .= '<option value="'.$row['categoria'].'">'.$row['categoria'].'</option>';
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
                $arrCursos = json_decode(json_encode($allCourse,JSON_UNESCAPED_UNICODE),true);

                foreach($arrCursos as $row){
                    $text .= '<option value="'.$row['curso'].'">'.$row['curso'].'</option>';
                }

                $text .= '
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <label style="font-size: 0.625em !important;">Matrícula</label>
                                <div class="input-group" id="matricula_div4">
                                    <input type="date" class="input-sm form-control" name="matriculaStart4" id="matriculaStart4" style="max-height: 27px;">
                                    <span class="input-group-addon">até</span>
                                    <input type="date" class="input-sm form-control" name="matriculaEnd4" id="matriculaEnd4" style="max-height: 27px;">
                                </div>
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
                            <thead style="background: #01101f; color: #fff;">
                                <tr>
                                    <th class="col-md-2">Nome</th>
                                    <th>E-mail</th>
                                    <th>Cidade</th>
                                    <th>Exercicio</th>
                                    <th>Cargo</th>
                                    <th>Lotacao</th>
                                    <th>Curso</th>
                                    <th>Categoria</th>
                                    <th>Matrícula</th>
                                    <th>Status</th>
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
                        <div class="container-fluid">
                            <div class="spinner-wrapper d-none">
                              <div class="spinner-border" role="status">
                                <span class="hidden">Loading...</span>
                              </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-3">
                                    <label style="">Usuários</label>
                                    <select id="filterUsers5" name="filterUsers5" class="form-control limparUser5">
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
                                    <label style="">Cursos</label>
                                    <select id="filterCursos5" name="filterCursos5" class="form-control" disabled></select>
                                </div>
                                <div class="col-sm-12 col-md-3 align-bottom align-self-end">
                                    <input type="button" style="width: 47%;" class="btn btn-primary btn-lg btn-ha" id="btnLimparFiltro5" name="btnLimparFiltro5" value="Limpar">
    
                                    <input type="button" style="width: 47%;" class="btn btn-primary btn-lg" id="btnVoltar" name="btnVoltar" value="Voltar">
                                </div>
                            </div>
                            <div class="row mt-2 text-center">
                                <div class="col-lg-12">
                                    <div class="p-2 border bg-light bold">EVA - Escola Virtual da AGU</div>
                                </div>
                            </div>
                        </div>
                        <div class="row m-1">
                            <div class="col-md-12">
                                <h4 class="bold">DADOS PESSOAIS</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="row" style="margin: 10px;">
                                    <div class="col-md-4">
                                        <span style="font-weight: bold; font-size: 12px;">INSCRIÇÃO:&nbsp;</span>
                                    </div>
                                    <div class="col-md-8">
                                        <span style="font-size: 0.625em;" id="txtInscricao">--</span>
                                    </div>
                                </div>
                                <div class="row" style="margin: 10px;">
                                    <div class="col-md-4">
                                        <span style="font-weight: bold; font-size: 12px;">NOME:&nbsp;</span>
                                    </div>
                                    <div class="col-md-8">
                                        <span style="font-size: 12px;" id="txtNome">--</span>
                                    </div>
                                </div>
                                <div class="row" style="margin: 10px;">
                                    <div class="col-md-4">
                                        <span style="font-weight: bold; font-size: 12px;">CARGO:&nbsp;</span>
                                    </div>
                                    <div class="col-md-8">
                                        <span style="font-size: 12px;" id="txtCargo">--</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="row" style="margin: 10px;">
                                    <div class="col-md-4">
                                        <span style="font-weight: bold; font-size: 12px;">LOTAÇÃO:&nbsp;</span>
                                    </div>
                                    <div class="col-md-8">
                                        <span style="font-size: 12px;" id="txtLotacao">--</span>
                                    </div>
                                </div>
                                <div class="row" style="margin: 10px;">
                                    <div class="col-md-4">
                                        <span style="font-weight: bold; font-size: 12px;">CERTIFICADO:&nbsp;</span>
                                    </div>
                                    <div class="col-md-8">
                                        <span style="font-size: 12px;" id="txtCertificado">--</span>
                                    </div>
                                </div>
                                <div class="row" style="margin: 10px;">
                                    <div class="col-md-4">
                                        <span style="font-weight: bold; font-size: 12px;">DATA EMISSÃO:&nbsp;</span>
                                    </div>
                                    <div class="col-md-8">
                                        <span style="font-size: 12px;" id="txtEmissao">--</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row m-1">
                            <div class="col-md-12">
                                <h4 class="bold">DADOS DO CURSO</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="row" style="margin: 10px;">
                                    <div class="col-md-4">
                                        <span style="font-weight: bold; font-size: 12px;">CATEGORIA:&nbsp;</span>
                                    </div>
                                    <div class="col-md-8">
                                        <span style="font-size: 12px;" id="txtCategoria">--</span>
                                    </div>
                                </div>
                                <div class="row" style="margin: 10px;">
                                    <div class="col-md-4">
                                        <span style="font-weight: bold; font-size: 12px;">CURSO:&nbsp;</span>
                                    </div>
                                    <div class="col-md-8">
                                        <span style="font-size: 12px;" id="txtCurso">--</span>
                                    </div>
                                </div>
                                <div class="row" style="margin: 10px;">
                                    <div class="col-md-4">
                                        <span style="font-weight: bold; font-size: 12px;">STATUS:&nbsp;</span>
                                    </div>
                                    <div class="col-md-8">
                                        <span style="font-size: 12px;" id="txtStatus">--</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="row" style="margin: 10px;">
                                    <div class="col-md-4">
                                        <span style="font-weight: bold; font-size: 12px;">SUB-CATEGORIA:&nbsp;</span>
                                    </div>
                                    <div class="col-md-8">
                                        <span style="font-size: 12px;" id="txtSubCategoria">--</span>
                                    </div>
                                </div>
                                <div class="row" style="margin: 10px;">
                                    <div class="col-md-4">
                                        <span style="font-weight: bold; font-size: 12px;">CARGA HORÁRIA:&nbsp;</span>
                                    </div>
                                    <div class="col-md-8">
                                        <span style="font-size: 12px;" id="txtCargaHoraria">--</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row m-1">
                            <div class="col-md-12">
                                <h4 class="bold">NOTA DO ALUNO</h4> 
                                <table class="table fz24 table-bordered">
                                    <tr style="bac">
                                        <th>Nome Exame</th>
                                        <th>Nota</th>
                                    </tr>                             
                                    <tbody id="notas">
                                    </tbody>                               
                                </table>  

                  
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-5 table-responsive">
                        <table cellspacing="0" style="width: 100%; font-size: 20px;" id="tab_sug5" name="tab_sug5" class="table table-hover table-bordered">
                            <thead style="background: #01101f; color: #fff;">
                                <tr>   
                                    <th>ID</th>
                                    <th style="width: 40%;">Módulo</th>
                                    <th style="width: 90%;">Recursos / Atividades</th>
                                    <th style="width: 10%;">Nota</th>
                                    <th style="width: 10%;">Menção</th>
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
                // $text .= '<div class="col-sm-12 col-md-3">
                //     <label>Matrícula</label>
                //     <div class="input-daterange input-group demo-6-matricula" id="matricula_div6">
                //         <input type="text" class="input-sm form-control" name="matriculaStart6" id="matriculaStart6" style="max-height: 27px;">
                //         <span class="input-group-addon">até</span>
                //         <input type="text" class="input-sm form-control" name="matriculaEnd6" id="matriculaEnd6" style="max-height: 27px;">
                //     </div>
                // </div>';
                // $text .= '<div class="col-sm-12 col-md-3">
                //     <label>Primeiro acesso</label>
                //     <div class="input-daterange input-group demo-6-acesso" id="primeiroAcesso_div6">
                //         <input type="text" class="input-sm form-control" name="primeiroAcessoStart6" id="primeiroAcessoStart6" style="max-height: 27px;">
                //         <span class="input-group-addon">até</span>
                //         <input type="text" class="input-sm form-control" name="primeiroAcessoEnd6" id="primeiroAcessoEnd6" style="max-height: 27px;">
                //     </div>
                // </div>';
                // $text .= '<div class="col-sm-12 col-md-3">
                //     <label>Cargo</label>
                //     <select id="filterCargo6" name="filterCargo6" class="form-control">
                //         <option value="">Selecione uma opção</option>';
                //         $arrCargos = json_decode(json_encode($cargos,JSON_UNESCAPED_UNICODE),true);

                //         foreach($arrCargos as $row){
                //             $text .= '<option value="'.$row['cargo'].'">'.$row['cargo'].'</option>';
                //         }

                //         $text .= '
                //     </select>
                // </div>';
                // $text .= '<div class="col-sm-12 col-md-3">
                //     <label>Lotação</label>
                //     <select id="filterLotacao6" name="filterLotacao6" class="form-control">
                //         <option value="">Selecione uma opção</option>';
                //         $arrLotacao = json_decode(json_encode($lotacao,JSON_UNESCAPED_UNICODE),true);

                //         foreach($arrLotacao as $row){
                //             $text .= '<option value="'.$row['lotacao'].'">'.$row['lotacao'].'</option>';
                //         }

                //         $text .= '
                //     </select>
                // </div>';
                // $text .= '<div class="col-sm-12 col-md-3">
                //     <label>Categoria</label>
                //     <select id="filterCategory6" name="filterCategory6" class="form-control">
                //         <option value="">Selecione uma opção</option>';
                //         $arrCategoria = json_decode(json_encode($categoria,JSON_UNESCAPED_UNICODE),true);

                //         foreach($arrCategoria as $row){
                //             $text .= '<option value="'.$row['categoryname'].'">'.$row['categoryname'].'</option>';
                //         }

                //         $text .= '
                //     </select>
                // </div>';
                $text .= '</div>';
                // $text .= '<div class="row">
                //     <div class="col-sm-12 col-md-3">
                //         <label>Cursos</label>
                //         <select id="filterCursos6" name="filterCursos6" class="form-control">
                //             <option value="">Selecione uma opção</option>';
                //             $arrCursos = json_decode(json_encode($cursos,JSON_UNESCAPED_UNICODE),true);

                //             foreach($arrCursos as $row){
                //                 $text .= '<option value="'.$row['fullname'].'">'.$row['fullname'].'</option>';
                //             }

                //             $text .= '
                //         </select>
                //     </div>
                //     <div class="col-sm-12 col-md-3">
                //         <label>Status</label>
                //         <select id="filterStatus6" name="filterStatus6" class="form-control">
                //             <option value="">Selecione uma opção</option>
                //             <option value="Aberto">Aberto</option>
                //             <option value="Encerrado">Encerrado</option>
                //         </select>
                //     </div>
                //     <div class="col-sm-12 col-md-3">
                //         <label>Menção</label>
                //         <select id="filterMencao6" name="filterMencao6" class="form-control">
                //             <option value="">Selecione uma opção</option>
                //             <option value="Aprovado">Aprovado</option>
                //             <option value="Reprovado">Reprovado</option>
                //         </select>
                //     </div>
                //     <div class="col-sm-12 col-md-3">
                //         <label>Matrícula</label>
                //         <div class="input-daterange input-group demo-6-matricula" id="matricula_div6">
                //             <input type="text" class="input-sm form-control" name="matriculaStart6" id="matriculaStart6" style="max-height: 27px;">
                //             <span class="input-group-addon">até</span>
                //             <input type="text" class="input-sm form-control" name="matriculaEnd6" id="matriculaEnd6" style="max-height: 27px;">
                //         </div>
                //     </div>
                // </div>';
                // $text .= '<div class="row">';
                // $text .= '<div class="col-sm-12 col-md-3">
                //     <label>Primeiro acesso</label>
                //     <div class="input-daterange input-group demo-6-acesso" id="primeiroAcesso_div6">
                //         <input type="text" class="input-sm form-control" name="primeiroAcessoStart6" id="primeiroAcessoStart6" style="max-height: 27px;">
                //         <span class="input-group-addon">até</span>
                //         <input type="text" class="input-sm form-control" name="primeiroAcessoEnd6" id="primeiroAcessoEnd6" style="max-height: 27px;">
                //     </div>
                // </div>';
                //     $text .= '<div class="col-sm-12 col-md-3">
                //         <label>Menção</label>
                //         <select id="filterMencao6" name="filterMencao6" class="form-control">
                //             <option value="">Selecione uma opção</option>
                //             <option value="Aprovado">Aprovado</option>
                //             <option value="Reprovado">Reprovado</option>
                //         </select>
                //     </div>';
                //     $text .= '<div class="col-sm-12 col-md-3">
                //         <label>Status</label>
                //         <select id="filterStatus6" name="filterStatus6" class="form-control">
                //             <option value="">Selecione uma opção</option>
                //             <option value="Aberto">Aberto</option>
                //             <option value="Encerrado">Encerrado</option>
                //         </select>
                //     </div>';
                //     $text .= '<div class="col-sm-12 col-md-3">
                //         <label>Último acesso</label>
                //         <div class="input-daterange input-group demo-6-ultacesso" id="ultacesso_div6">
                //             <input type="text" class="input-sm form-control" name="ultacessoStart6" id="ultacessoStart6" style="max-height: 27px;">
                //             <span class="input-group-addon">até</span>
                //             <input type="text" class="input-sm form-control" name="ultacessoEnd6" id="ultacessoEnd6" style="max-height: 27px;">
                //         </div>
                //     </div>

                // </div>';
                // $text .= '<div class="row d-flex justify-content-center align-items-center">';
                //     $text .= '<div class="col-sm-12 col-md-2 mt-4">
                //         <input type="button" style="width: 100%;" class="btn btn-primary btn-lg" id="btnLimparFiltro6" name="btnLimparFiltro6" value="Limpar">
                //     </div>
                //     <div class="col-sm-12 col-md-2 mt-4">
                //         <input type="button" style="width: 100%;" class="btn btn-primary btn-lg" id="btnVoltar" name="btnVoltar" value="Voltar">
                //     </div>';
                // $text .= '</div>';
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
                                    <h5 style="margin: 0 auto;font-weight: bold;">DADOS PESSOAIS</h5>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="row" style="margin: 10px;">
                                    <div class="col-md-4">
                                        <span style="font-weight: bold; font-size: 1em;">ID:&nbsp;</span>
                                    </div>
                                    <div class="col-md-8">
                                        <span style="font-size: 1em;" id="txtId6">--</span>
                                    </div>
                                </div>
                                <div class="row" style="margin: 10px;">
                                    <div class="col-md-4">
                                        <span style="font-weight: bold; font-size: 1em;">NOME:&nbsp;</span>
                                    </div>
                                    <div class="col-md-8">
                                        <span style="font-size: 1em;" id="txtNome6">--</span>
                                    </div>
                                </div>
                                <div class="row" style="margin: 10px;">
                                    <div class="col-md-4">
                                        <span style="font-weight: bold; font-size: 1em;">CARGO:&nbsp;</span>
                                    </div>
                                    <div class="col-md-8">
                                        <span style="font-size: 1em;" id="txtCargo6">--</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="row" style="margin: 10px;">
                                    <div class="col-md-4">
                                        <span style="font-weight: bold; font-size: 1em;">LOTAÇÃO:&nbsp;</span>
                                    </div>
                                    <div class="col-md-8">
                                        <span style="font-size: 1em;" id="txtLotacao6">--</span>
                                    </div>
                                </div>
                                <div class="row" style="margin: 10px;">
                                    <div class="col-md-4">
                                        <span style="font-weight: bold; font-size: 1em;">CPF:&nbsp;</span>
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
                                    <h4 style="margin: 0 auto;font-weight: bold;">CURSOS INSCRITO</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-5 table-responsive">
                        <table cellspacing="0" style="font-size: 1em; width: 100%;" id="tab_sug6" name="tab_sug6" class="table table-hover table-bordered">
                            <thead style="background: #01101f; color: #fff;">
                                <tr>
                                    <th style="width: 9%">ID</th>
                                    <th style="width: 9%">Nome</th>
                                    <th style="width: 9%">Curso</th>
                                    <th style="width: 9%">Categoria</th>
                                    <th style="width: 9%">Matrícula</th>
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

