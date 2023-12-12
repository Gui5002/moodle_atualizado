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

        $categoria = $DB->get_records_sql('SELECT DISTINCT upper(trim(`name`)) as categoria FROM mdl_course_categories WHERE `parent` = 0 ORDER BY categoria ASC');
        $subcategoria = $DB->get_records_sql('SELECT DISTINCT upper(trim(`name`)) as subcategoria FROM mdl_course_categories WHERE `parent` > 0 ORDER BY subcategoria ASC');
        $allCourse = $DB->get_records_sql('SELECT DISTINCT upper(trim(fullname)) as curso, id  FROM mdl_course ORDER BY curso ASC');
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
                                    <th style="text-align: center;">Concluido</th>
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
                            <div class="col-sm-12 col-md-3">
                                <label style="font-size: 0.625em !important;">Usuários</label>
                                <select id="filterUsers3" name="filterUsers3" class="form-control">
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
                                <select id="filterCargo3" name="filterCargo3" class="form-control">
                                    <option value="">Selecione uma opção</option>';
                $arrCargos = json_decode(json_encode($cargos,JSON_UNESCAPED_UNICODE),true);

                foreach($arrCargos as $row){
                    $text .= '<option value="'.$row['cargo'].'">'.$row['cargo'].'</option>';
                }

                $text .= '
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <label style="font-size: 0.625em !important;">Cursos</label>
                                <select id="filterCursos3" name="filterCursos3" class="form-control">
                                    <option value="">Selecione uma opção</option>';
                $arrCursos = json_decode(json_encode($allCourse,JSON_UNESCAPED_UNICODE),true);

                foreach($arrCursos as $row){
                    $text .= '<option value="'.$row['curso'].'">'.$row['curso'].'</option>';
                }

                $text .= '
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <label style="font-size: 0.625em !important;">Status</label>
                                <select id="filterStatus3" name="filterStatus3" class="form-control">
                                    <option value="">Selecione uma opção</option>
                                    <option value="Concluído!">CONCLUÍDO</option>
                                    <option value="Não Concluído">NÃO CONCLUÍDO</option>
                                    <option value="Não Iniciado">NÃO INICIADO</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-3">
                                <label style="font-size: 0.625em !important;">Matrícula</label>
                                <div class="input-group" id="matricula_div3">
                                    <input type="date" class="input-sm form-control" name="matriculaStart3" id="matriculaStart3" style="max-height: 27px;">
                                    <span class="input-group-addon">até</span>
                                    <input type="date" class="input-sm form-control" name="matriculaEnd3" id="matriculaEnd3" style="max-height: 27px;">
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-3 mt-4">
                                <input type="button" style="width: 100%;" class="btn btn-primary btn-lg" id="btnLimparFiltro3" name="btnLimparFiltro3" value="Limpar">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-5 table-responsive">
                        <table cellspacing="0" style="font-size: 1em; width: 100%;" id="tab_sug3" name="tab_sug3" class="table table-hover table-bordered">
                            <thead style="background: #01101f; color: #fff;">
                                <tr>
                                    <th class="col-sm-12 col-md-2">Nome</th>
                                    <th>E-mail</th>
                                    <th>Cidade</th>
                                    <th>Exercicio</th>
                                    <th>Cargo</th>
                                    <th class="col-sm-12 col-md-3">Curso</th>
                                    <th>Matrícula</th>
                                    <th>Status</th>
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

