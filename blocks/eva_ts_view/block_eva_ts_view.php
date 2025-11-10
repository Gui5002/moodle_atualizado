<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * The eva_ts_view block
 * @package    ${PLUGINNAME}
 * @copyright  2024 ESAGU
 * @author     xangay® <xangay.gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

require_once ($CFG->dirroot . '/theme/evagu/ccn/block_handler/ccn_block_handler.php');
require_once ($CFG->libdir . '/blocklib.php');

class block_eva_ts_view extends block_base
{
    function init()
    {
        $this->title = get_string('eva_ts_view', 'block_eva_ts_view');
    }

    function applicable_formats()
    {
        return array('mod' => true);
    }

    function instance_allow_multiple()
    {
        return false;
    }

    function get_content()
    {
        global $CFG, $PAGE, $DB, $USER;
        require_once ($CFG->libdir . '/filelib.php');
        $PAGE->requires->css(new moodle_url($CFG->wwwroot . '/blocks/eva_ts_view/css/style.css'));
        $PAGE->requires->css(new \moodle_url('https://cdn.datatables.net/v/bs5/dt-1.13.8/datatables.min.css'));
        $PAGE->requires->js(new \moodle_url('https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js'), true);
        $PAGE->requires->js(new \moodle_url('https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js'), true);
        $PAGE->requires->js(new \moodle_url('https://cdn.datatables.net/v/bs5/dt-1.13.8/datatables.min.js'), true);
        if ($this->content !== NULL) {
            return $this->content;
        }
        if (empty($this->instance)) {
            $this->content = '';
            return $this->content;
        }
        if (!empty($this->config) && is_object($this->config)) {
            $this->content = new stdClass();
            $this->config->headtitle = 'Escola Virtual AGU';
            $this->config->column1 = 'Órgão';
            $this->config->column2 = 'Identificação';
            $this->config->column3 = 'Data da solicitação';
            $this->config->selectAll = 'Selecione todos';
            $this->config->clear = 'Limpar seleção';
            $this->config->filtro = 'Filtros';
            $this->config->schOrgan = 'Buscar órgão';
            $this->config->slcNome = 'Nome do usuário';
            $this->config->slcEixoNull = '';
            $this->config->slcEixo0 = '';
            $this->config->slcEixo1 = '';
            $this->config->slcEixo2 = '';
            $this->config->slcEixo3 = '';
            $rs = $DB->get_records_sql('SELECT * FROM {eva_superior_organ} WHERE st_status = 1');
            if ($rs) {
                $this->config->resultSet = $rs;
            }
            $rsAll = $DB->get_records_sql('SELECT * FROM {eva_training_suggestion}');
            if (!empty($this->config->headtitle)) {
                $this->content->headtitle = $this->config->headtitle;
            } else {
                $this->content->headtitle = '';
            }
            if (!empty($this->config->column1)) {
                $this->content->column1 = $this->config->column1;
            } else {
                $this->content->column1 = '';
            }
            if (!empty($this->config->column2)) {
                $this->content->column2 = $this->config->column2;
            } else {
                $this->content->column2 = '';
            }
            if (!empty($this->config->column3)) {
                $this->content->column3 = $this->config->column3;
            } else {
                $this->content->column3 = '';
            }
            if (!empty($this->config->selectAll)) {
                $this->content->selectAll = $this->config->selectAll;
            } else {
                $this->content->selectAll = '';
            }
            if (!empty($this->config->clear)) {
                $this->content->clear = $this->config->clear;
            } else {
                $this->content->clear = '';
            }
            if (!empty($this->config->filtro)) {
                $this->content->filtro = $this->config->filtro;
            } else {
                $this->content->filtro = '';
            }
            if (!empty($this->config->schOrgan)) {
                $this->content->schOrgan = $this->config->schOrgan;
            } else {
                $this->content->schOrgan = '';
            }
            if (!empty($this->config->slcNome)) {
                $this->content->slcNome = $this->config->slcNome;
            } else {
                $this->content->slcNome = '';
            }
        }
        $text = '';
        $text .= '<script src="' . $CFG->wwwroot . '/blocks/eva_ts_view/js/moment.js"></script>';
        $text .= '
        <div id="modalDiv"></div>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <label id="arrowLabel" name="arrowLabel" class="em15 fGray negrito" style="cursor: pointer;">' . $this->content->filtro . '&nbsp<i id="arrow" name="arrow" class="fa fa-angle-down"></i></label>
            </div>
        </div>
        <div class="card sombreamento" style="display: block;" id="cardFiltros" name="cardFiltros">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4 verticalLine">
                        <div class="em15 fGray negrito">
                            ' . $this->content->column1 . '
                        </div>
                        <div class="row">
                            <div style="width: 100%;" class="btn-group" role="group" aria-label="Botões">
                                <div class="col-md-8"><input type="button" style="float: right; padding: 0; border: none; background: none;" class="fGray ml-1 mt-2 mr-1" id="btnSlcAll" name="btnSlcAll" value="' . $this->content->selectAll . '"></div>
                                <div class="col-md-4"><input type="button" style="float: right; padding: 0; border: none; background: none;" class="fGray ml-1 mt-2 mr-1" id="btnLimpar" name="btnLimpar" value="' . $this->content->clear . '"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="input-group rounded">
                                    <input type="search" name="searchInput" id="searchInput" class="form-control" style="border-top: none; border-right: none; border-radius: 0; border-left: none;" placeholder="' . $this->content->schOrgan . '" aria-label="' . $this->content->schOrgan . '" aria-describedby="search-addon" />
                                    <span class="input-group-text border-0" onclick="buscaOrgaos();" name="search-addon" id="search-addon" style="cursor: pointer; background: none;">
                                        <i class="fa fa-search"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row ml-1 mt-1 mr-1">
                            <div id="box" name="box" class="checkContainer">
                                <div id="checkOptions" name="checkOptions">';
        $d = json_decode(json_encode($rs, JSON_UNESCAPED_UNICODE), true);
        foreach ($d as $row) {
            $text .= '<div class="form-check ml-1">';
            $text .= '<input class="form-check-input" type="checkbox" id="slcOrgan" name="slcOrgan[]" value="' . $row['id'] . '">';
            $text .= '<label class="form-check-label" for="defaultCheck1">';
            $text .= '<span>' . $row['no_organ'] . '</span>';
            $text .= '</label>';
            $text .= '</div>';
        }
        $text .= '
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4 verticalLine">
                        <div class="em15 fGray negrito">' . $this->content->column2 . '</div>
                        <div class="row">&nbsp;</div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 mt-2">
                                <select id="slcNome" name="slcNome" class="form-control" style="border-top: none; border-right: none; border-radius: 0; border-left: none;">
                                    <option value="">' . $this->content->slcNome . '</option>';
        $sql_user = "select
                                                    id,
                                                    concat(mu.firstname, ' ',mu.lastname) as name
                                                from
                                                    mdl_user mu
                                                order by
                                                    mu.firstname asc";
        $user = $DB->get_records_sql($sql_user);
        $u = json_decode(json_encode($user, JSON_UNESCAPED_UNICODE), true);
        foreach ($u as $row) {
            $text .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
        }
        $text .= '</select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 mt-2">
                                <select id="slcEixo" name="slcEixo" class="form-control" style="border-top: none; border-right: none; border-radius: 0; border-left: none;">
                                    <option value="">Eixo</option>
                                    <option value="0">Não aplicável</option>
                                    <option value="1">Combate à corrupção e recuperação de ativos</option>
                                    <option value="2">Judicialização da saúde pública</option>
                                    <option value="3">Mecanismos para resolver controvérsias e disputas em organizações internacionais</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-6 mt-2">
                                <select id="slcPubAlvo" name="slcPubAlvo" class="form-control" style="border-top: none; border-right: none; border-radius: 0; border-left: none;">
                                    <option value="">Público-alvo</option>';
        $pa = json_decode(json_encode($rsAll, JSON_UNESCAPED_UNICODE), true);
        foreach ($pa as $row) {
            $text .= '<option value="' . $row['ds_target_audience'] . '">' . $row['ds_target_audience'] . '</option>';
        }
        $text .= '</select>
                            </div>
                            <div class="col-sm-12 col-md-6 mt-2">
                                <select id="slcStatus" name="slcStatus" class="form-control" style="border-top: none; border-right: none; border-radius: 0; border-left: none;">
                                    <option value="">Status</option>
                                    <option value="1">Pendente</option>
                                    <option value="2">Atendido</option>
                                    <option value="0">Cancelado</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-6 mt-2">
                                <select id="slcModalidade" name="slcModalidade" class="form-control" style="border-top: none; border-right: none; border-radius: 0; border-left: none;">
                                    <option value="">Modalidade</option>
                                    <option value="1">Presencial</option>
                                    <option value="2">Transmissão interna ao vivo (teams)</option>
                                    <option value="3">Sala de estudo virtual (moodle)</option>
                                    <option value="4">Ciclo permanente de ações de treinamento a distância</option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-6 mt-2">
                                <select id="slcTema" name="slcTema" class="form-control" style="border-top: none; border-right: none; border-radius: 0; border-left: none;">
                                    <option value="">Tema AGU</option>';
        $t = json_decode(json_encode($rsAll, JSON_UNESCAPED_UNICODE), true);
        foreach ($t as $row) {
            $text .= '<option value="' . $row['ds_theme'] . '">' . $row['ds_theme'] . '</option>';
        }
        $text .= '</select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4">
                        <div class="em15 fGray negrito">' . $this->content->column3 . '</div>
                        <div class="row">&nbsp;</div>
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <label class="form-check-label" for="inpDateIni">Dt. inicial</label>
                                <input type="date" class="form-control" id="inpDateIni" name="inpDateIni" style="border-top: none; border-right: none; border-radius: 0; border-left: none;">
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="form-check-label" for="inpDateFim">Dt. final</label>
                                <input type="date" class="form-control" id="inpDateFim" name="inpDateFim" style="border-top: none; border-right: none; border-radius: 0; border-left: none;" placeholder="Dt. final">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-6 mt-4">
                                <input type="button" style="width: 100%;" class="btn btn-primary btn-lg" id="btnPesquisar" name="btnPesquisar" value="Pesquisar">
                            </div>
                            <div class="col-sm-12 col-md-6 mt-4">
                                <input type="button" style="width: 100%;" class="btn btn-primary btn-lg" id="btnLimparFiltro" name="btnLimparFiltro" value="Limpar">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-5 table-responsive">
                <table cellspacing="0" style="font-size: 1em; width: 100%;" id="tab_sug" name="tab_sug" class="table table-hover table-bordered">
                    <thead style="background: #185287; color: #fff;">
                        <tr>
                            <th style="width: 9%">Usuário</th>
                            <th style="width: 9%">Órgão</th>
                            <th style="width: 9%">Tema AGU</th>
                            <th style="width: 9%">Eixo jurídico</th>
                            <th style="width: 9%">Eixo técnico</th>
                            <th style="width: 9%">Necessidade</th>
                            <th style="width: 9%">Público</th>
                            <th style="width: 9%">Modalidade</th>
                            <th style="width: 9%">Valor</th>
                            <th style="width: 9%">Status</th>
                            <th style="width: 9%">Ação</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
        ';
        require_once ($CFG->dirroot . '/blocks/eva_ts_view/js/funcoesJs.php');
        $this->content = new stdClass;
        $this->content->text = '';
        $this->content->footer = '';
        return $this->content;
    }

    public function html_attributes()
    {
        global $CFG;
        $attributes = parent::html_attributes();
        include_once ($CFG->dirroot . '/theme/evagu/ccn/block_handler/attributes.php');
        return $attributes;
    }
}
