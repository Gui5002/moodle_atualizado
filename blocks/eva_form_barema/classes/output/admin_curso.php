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


namespace block_eva_form_barema\output;

defined('MOODLE_INTERNAL') || die();

use renderable;
use renderer_base;
use templatable;

/**
 *  file description here.
 *
 * @package
 * @copyright  2023 celio <>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


class admin_curso implements renderable, templatable {

    var $config;
    var $context;

    public function __construct($config, $context) {
        $this->config = $config;
        $this->context = $context;
    }

    private function filter_busca( ) {
        global $USER, $PAGE, $CFG, $FULLME, $DB;

//        if (!$DB->record_exists('eva_barema_permissao', array('user_id'=>$USER->id, 'posgraduacao'=>1))) {
//            if (!$avaliadorid = $DB->get_field('eva_barema_avaliador', 'avaliador_tb_user_id', array('avaliador_tb_user_id'=>$USER->id))) {
//                $alunoid = $USER->id;
//            }
//        }

        //BUSCA
//        $avaliador = $avaliadorid ? fullname($USER) : filter_input(INPUT_GET, 'avaliador', FILTER_SANITIZE_STRING);

        $avaliador = filter_input(INPUT_GET, 'avaliadores', FILTER_SANITIZE_STRING);
//        $barema = filter_input(INPUT_GET, 'barema', FILTER_SANITIZE_STRING);
        $curso = filter_input(INPUT_GET, 'curso', FILTER_SANITIZE_STRING);
        $dataatribuicao = filter_input(INPUT_GET, 'data_atribuicao', FILTER_SANITIZE_STRING);
        $status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_STRING);

        //FILTRO DE STATUS
//        $status = in_array($status,[0,1]) ? $status : '';

        $condicoes = [
//            strlen($barema) ? ' barema LIKE "%'.str_replace(' ','%',$barema).'%"':null,
            strlen($avaliador) ? ' avaliadores LIKE "%'.str_replace(' ','%',$avaliador).'%"':null,
            strlen($curso) ? ' curso LIKE "%'.str_replace(' ','%',$curso).'%"':null,
            strlen($dataatribuicao) ? ' data LIKE "%'.str_replace(' ','%',$dataatribuicao).'%"':null,
            strlen($status) ? ' status LIKE "%'.str_replace(' ','%',$status).'%"':null,
//            strlen($status) ? ' status = '.$status : null
        ];
        //REMOVE POSICOES VAZIAS
        $condicoes = array_filter($condicoes);

        //CLÁUSULA WHERE
        $where = implode(' AND ', $condicoes);

        return $where;
    }

    private function pagination_relatorio (){

        //INPÚT GET
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $per_page = (isset($_GET['per_page']) && ($_GET['per_page'] <= 50)) ? (int)$_GET['per_page'] : 10;

        //POSITIONING
        $start = ($page > 1) ? ($page * $per_page) - $per_page : 0;

        $caderno['page'] = $page;
        $caderno['perPage'] = $per_page;
        $caderno['start'] = $start;
        $caderno = (object) $caderno;

        return  $caderno;
    }


    public function ordenaAvaliadores ($arr){
        array_multisort(array_map(function ($elemento){
            return $elemento['nomeavaliador'];
        }, $arr), SORT_ASC, $arr);

        return $arr;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return stdClass
     */

    public function export_for_template(renderer_base $output) {
        global $DB, $USER, $PAGE;


        $clausulaWhere = $this->filter_busca();
        $where = $clausulaWhere ? 'WHERE'.$clausulaWhere : '';

        $caderno = $this->pagination_relatorio();

        $sql = "SELECT SQL_CALC_FOUND_ROWS `id`, avaliadores, barema, curso, atividade, qtd_avaliados, qtd_alunos, `data`, color_status, status 
                FROM vw_gerencia_admin {$where} LIMIT {$caderno->start}, {$caderno->perPage}";

        $relatorios = $DB->get_records_sql($sql);

        $sqltotal = "SELECT FOUND_ROWS() as total";
        $resultado_pg = $DB->get_record_sql($sqltotal)->total;

        $quantidade_pg = ceil($resultado_pg / $caderno->perPage);

        $sepera_path = explode('/', $PAGE->url->get_path());
        $path_admin = $sepera_path[3].'?filters';

        $selectfilter = [];
        foreach ($_GET as $key => $value) {
            $selects = $path_admin;
            if ($key === 'barema'){
                $selects .= '?barema='.$value;
            }elseif ($key === 'curso') {
                $selects .= '&curso='.$value;
            }elseif ($key === 'atividade') {
                $selects .= '&atividade='.$value;
            }elseif ($key === 'avaliador') {
                $selects .= '&avaliador='.$value;
            }elseif ($key === 'data_atribuicao') {
                $selects .= '&data_atribuicao='.$value;
            }elseif ($key === 'status') {
                $selects .= '&status='.$value;
            }
            $selectfilter[$key] = $value;
        }

        $max_links = 2;
        $primeiro   = $selects ? $selects.'&page='. 1 .'&per_page='.$caderno->perPage.'&admin=curso' : '?page='. 1 .'&per_page='.$caderno->perPage.'&admin=curso';
        $i=0;
        for ($pag_ant = $caderno->page - $max_links; $pag_ant <= $caderno->page - 1; $pag_ant++) {
            if ($pag_ant >= 1) {
                $link  = $selects ? $selects.'&page='.$pag_ant.'&per_page='.$caderno->perPage.'&admin=curso' : '?page='. $pag_ant .'&per_page='.$caderno->perPage.'&admin=curso';
                $link_ant[$i]['href_ant'] = $link;
                $link_ant[$i]['pg_ant'] = $pag_ant;
                $i++;
            }
        }
        $link  = $selects ? $selects.'&page='.$pag_ant.'&per_page='.$caderno->perPage.'&admin=curso' : '?page='. $pag_ant .'&per_page='.$caderno->perPage.'&admin=curso';
        $link_atual[0]['select'] = 'active';
        $link_atual[0]['href_atual'] = $link;
        $link_atual[0]['pg_atual'] = $caderno->page;

        $i=0;
        for ($pag_dep = $caderno->page + 1; $pag_dep <= $caderno->page + $max_links; $pag_dep++) {
            if ($pag_dep <= $quantidade_pg) {
                $link  = $selects ? $selects.'&page='.$pag_dep.'&per_page='.$caderno->perPage.'&admin=curso' : '?page='. $pag_dep .'&per_page='.$caderno->perPage.'&admin=curso';
                $link_dep[$i]['href_dep'] = $link;
                $link_dep[$i]['pg_dep'] = $pag_dep;
            }
            $i++;
        }
        if (($caderno->page + 3) <= $quantidade_pg) {
            $ultima_pg = $selects ? $selects.'&page='.$quantidade_pg.'&per_page='.$caderno->perPage.'&admin=curso' : '?page='. $quantidade_pg .'&per_page='.$caderno->perPage.'&admin=curso';
            $link_ultimo[0]['href_ultimo'] = $ultima_pg;
            $link_ultimo[0]['pontos'] = '...';
            $link_ultimo[0]['ultima_pg'] = $quantidade_pg;
        }
        $ultima     = $selects ? $selects.'&page='.$quantidade_pg.'&per_page='.$caderno->perPage.'&admin=curso' : '?page='. $quantidade_pg .'&per_page='.$caderno->perPage.'&admin=curso';

//        if (!$DB->get_field('eva_barema_permissao', 'user_id', array('user_id'=>$USER->id))) {
//            if (!$DB->get_field('eva_barema_avaliador', 'avaliador_tb_user_id', array('avaliador_tb_user_id'=>$USER->id))) {
//                $hidden = 'hidden';
//            }
//        }


        //Linstando os dados
        $count = count($relatorios);
        if ($count > 0) {
            $i = 0;

            foreach ($relatorios as $key=>$relatorio) {

                $datatime = ($relatorio->data == null)?"--":date('d/m/Y', strtotime($relatorio->data));

                $url_tb_avaliador_id = $_SERVER['SCRIPT_NAME']."?admin=alunos&qt_aluno_por_curso=".$relatorio->id;
                $tb_avaliador_id = $_SERVER['SCRIPT_NAME']."?admin=alunos&replace_id=".$relatorio->id;

//                $data = ($relatorio->status == null)?"--":date('d/m/Y', strtotime($atrib->data_atribuicao));
                if ($relatorio->color_status == 1) {
                    $color = "status_verde";
                    $display = "d-none";
                } else {
                    $color = "status_red";
                    $display = "";
                }

//                var_dump($url_tb_avaliador_id);die();

                $array[$i]['id'] = $relatorio->id;
                $array[$i]['avaliador'] = ucwords(strtolower($relatorio->avaliadores));
                $array[$i]['barema'] = $relatorio->barema;
                $array[$i]['curso'] = $relatorio->curso;
                $array[$i]['atividade'] = $relatorio->atividade;
                $array[$i]['data_atribuicao'] = $datatime;
                $array[$i]['qtd_avaliados'] = $relatorio->qtd_avaliados;
                $array[$i]['qtd_alunos'] = $relatorio->qtd_alunos;
                $array[$i]['status'] = $relatorio->status;
                $array[$i]['s_color'] = $color;
                $array[$i]['linkalunos'] = $url_tb_avaliador_id;
                $array[$i]['subst_avaliador'] = $tb_avaliador_id;
                $array[$i]['display'] = $display;
                $i++;
            }
        }

        //======== Modal de Substituição do avaliador===========

        $selecoes = $DB->get_records('eva_barema_avaliadores', array('status'=>1));
        $i = 0;
        foreach ($selecoes as $selecao){
            $sql = "SELECT id, fullname, email FROM vw_autocomplete_user where id = '{$selecao->avaliador_id}'";
            $avaliador = $DB->get_record_sql($sql);
            $substituto[$i]['user_id'] = $selecao->avaliador_id;
            $substituto[$i]['ava_substituto'] = $avaliador->fullname;
            $i++;
        }


        $data = new \stdClass();

        $avaliador  = $_GET['avaliadores'];
        $barema     = $_GET['barema'];
        $curso      = $_GET['curso'];
        $atividade  = $_GET['atividade'];
        $data_atribuicao  = $_GET['data_atribuicao'];
        $status      = $_GET['status'];

        $data->avaliadores = $this->filtro_select_avaliador($avaliador);
        $data->baremas = $this->filtro_select_barema($barema);
        $data->cursos = $this->filtro_select_curso($curso);
        $data->atividades = $this->filtro_select_atividade($atividade);
        $data->data_atribuicao = $this->filtro_select_data_atribuicao($data_atribuicao);
        $data->status = $this->filtro_select_status($status);

        $data->avaliadores_substituto = $substituto;
        $data->listagrupoalunos = $array;
        $data->primeiro = $primeiro;
        $data->ultima = $ultima;
        $data->links_ant = $link_ant;
        $data->links_atual = $link_atual;
        $data->links_dep = $link_dep;
        $data->links_ultimo = $link_ultimo;

        $data->filters = $link;
        $data->page = $caderno->page;
        $data->results = $caderno->perPage;
        $data->registros = $resultado_pg;
//        $data->hidden = $hidden;


        return $data;

    }

    public function filtro_select_avaliador ($avaliador_user){
        global $DB;
        $sql = "SELECT avaliadores FROM vw_gerencia_admin GROUP BY avaliadores ORDER BY avaliadores ASC";
        $dados = $DB->get_records_sql($sql);
        $i=0;
        foreach ($dados as $key=>$dado) {
            $array[$i]['valor'] = $key;
            $array[$i]['selected'] = ($key == $avaliador_user) ? 'selected' : '';
            $array[$i]['selecao'] = $dado->avaliadores;
            $i++;
        }
        return $array;
    }

    public function filtro_select_barema ($barema){
        global $DB;
        $sql = "SELECT barema FROM vw_gerencia_admin GROUP BY barema";
        $dados = $DB->get_records_sql($sql);
        $i=0;
        foreach ($dados as $key=>$dado) {
            $array[$i]['valor'] = $key;
            $array[$i]['selected'] = ($key == $barema) ? 'selected' : '';
            $array[$i]['selecao'] = $dado->barema;
            $i++;
        }
        return $array;
    }
    public function filtro_select_curso ($curso){
        global $DB;
        $sql = "SELECT curso FROM vw_gerencia_admin GROUP BY curso";
        $dados = $DB->get_records_sql($sql);
        $i=0;
        foreach ($dados as $key=>$dado) {
            $array[$i]['valor'] = $key;
            $array[$i]['selected'] = ($key == $curso) ? 'selected' : '';
            $array[$i]['selecao'] = $dado->curso;
            $i++;
        }
        return $array;
    }
    public function filtro_select_atividade ($atividade){
        global $DB;
        $sql = "SELECT atividade FROM vw_gerencia_admin GROUP BY atividade";
        $dados = $DB->get_records_sql($sql);
        $i=0;
        foreach ($dados as $key=>$dado) {
            $array[$i]['valor'] = $key;
            $array[$i]['selected'] = ($key == $atividade) ? 'selected' : '';
            $array[$i]['selecao'] = $dado->atividade;
            $i++;
        }
        return $array;
    }

    public function filtro_select_data_atribuicao ($data_atribuicao){
        global $DB;
        $sql = "SELECT distinct data FROM vw_gerencia_admin GROUP BY data";
        $dados = $DB->get_records_sql($sql);
        $i=0;
        foreach ($dados as $key=>$dado) {
            $array[$i]['valor'] = $key;
            $array[$i]['selected'] = ($key == $data_atribuicao && $data_atribuicao != null) ? 'selected' : '';
            $array[$i]['selecao'] = date('d/m/Y', strtotime($dado->data));
            $i++;
        }
        return $array;
    }

    public function filtro_select_status ($status){
        global $DB;
        $sql = "SELECT status FROM vw_gerencia_admin GROUP BY status";
        $dados = $DB->get_records_sql($sql);
        $i=0;
        foreach ($dados as $key=>$dado) {
            $array[$i]['valor'] = $key;
            $array[$i]['selected'] = ($key == $status) ? 'selected' : '';
            $array[$i]['selecao'] = $dado->status;
            $i++;
        }
        return $array;
    }


}
