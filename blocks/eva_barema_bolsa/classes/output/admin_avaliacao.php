<?php

namespace block_eva_barema_bolsa\output;

defined('MOODLE_INTERNAL') || die();

//require_once ('classes/output/criarbarema.php');
use mod_questionnaire\question\date;
use moodle_url;
use PhpOffice\PhpSpreadsheet\Calculation\DateTime;
use renderable;
use renderer_base;
use templatable;
use function PHPUnit\Framework\isNull;

class admin_avaliacao implements renderable, templatable {

    var $config;
    var $context;

    public function __construct($config, $context) {
        $this->config = $config;
        $this->context = $context;
    }


    private function filter_busca( ) {
        global $USER, $PAGE, $CFG, $DB;


        $anteprojeto = filter_input(INPUT_GET, 'anteprojeto', FILTER_SANITIZE_STRING);
        $avaliador = filter_input(INPUT_GET, 'avaliadores', FILTER_SANITIZE_STRING);
        $dataatribuicao = filter_input(INPUT_GET, 'data_atribuicao', FILTER_SANITIZE_STRING);
        $status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_STRING);


        $condicoes = [
            strlen($anteprojeto) ? ' anteprojeto LIKE "%'.str_replace(' ','%',$anteprojeto).'%"':null,
            strlen($avaliador) ? ' avaliador LIKE "%'.str_replace(' ','%',$avaliador).'%"':null,
            strlen($dataatribuicao) ? ' data_ini_avaliacao LIKE "%'.str_replace(' ','%',$dataatribuicao).'%"':null,
            strlen($status) ? ' status LIKE "%'.str_replace(' ','%',$status).'%"':null,
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
        $per_page = (isset($_GET['per_page']) && ($_GET['per_page'] <= 50)) ? (int)$_GET['per_page'] : 15;

        //POSITIONING
        $start = ($page > 1) ? ($page * $per_page) - $per_page : 0;

        $caderno['page'] = $page;
        $caderno['perPage'] = $per_page;
        $caderno['start'] = $start;
        $caderno = (object) $caderno;

        return  $caderno;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return stdClass
     */

    public function export_for_template(renderer_base $output) {
        global $DB, $USER, $PAGE;

        $data = new \stdClass();
        $id = $_GET['admin'];

        $clausulaWhere = $this->filter_busca();
        $where = $clausulaWhere ? 'WHERE'.$clausulaWhere : '';

        $caderno = $this->pagination_relatorio();

        $sql = "SELECT SQL_CALC_FOUND_ROWS `id`, anteprojeto, avaliador, data_ini_avaliacao, qt_dia_avaliacao, status 
                FROM vw_barema_bolsa_admin {$where} LIMIT {$caderno->start}, {$caderno->perPage}";

        $relatorios = $DB->get_records_sql($sql);

        $sqltotal = "SELECT FOUND_ROWS() as total";
        $resultado_pg = $DB->get_record_sql($sqltotal)->total;

        $quantidade_pg = ceil($resultado_pg / $caderno->perPage);

//        $sepera_path = explode('/', $PAGE->url->get_path());
//        $path_admin = $sepera_path[3].'?filters';
//        var_dump($path_admin);die();

        $selectfilter = [];
        foreach ($_GET as $key => $value) {
            if ($key === 'anteprojeto'){
                $selects .= '?anteprojeto='.$value;
            }elseif ($key === 'avaliador') {
                $selects .= '&avaliador='.$value;
            }elseif ($key === 'data_ini_avaliacao') {
                $selects .= '&data_ini_avaliacao='.$value;
            }elseif ($key === 'status') {
                $selects .= '&status='.$value;
            }
            $selectfilter[$key] = $value;
        }
        $max_links = 2;
        $primeiro   = $selects ? $selects.'&page='. 1 .'&per_page='.$caderno->perPage.'&admin='.$id : '?page='. 1 .'&per_page='.$caderno->perPage.'&admin='.$id;

        $i=0;
        for ($pag_ant = $caderno->page - $max_links; $pag_ant <= $caderno->page - 1; $pag_ant++) {
            if ($pag_ant >= 1) {
                $link  = $selects ? $selects.'&page='.$pag_ant.'&per_page='.$caderno->perPage.'&admin='.$id : '?page='. $pag_ant .'&per_page='.$caderno->perPage.'&admin='.$id;
                $link_ant[$i]['href_ant'] = $link;
                $link_ant[$i]['pg_ant'] = $pag_ant;
                $i++;
            }
        }
        $link  = $selects ? $selects.'&page='.$pag_ant.'&per_page='.$caderno->perPage.'&admin='.$id : '?page='. $pag_ant .'&per_page='.$caderno->perPage.'&admin='.$id;
        $link_atual[0]['select'] = 'active';
        $link_atual[0]['href_atual'] = $link;
        $link_atual[0]['pg_atual'] = $caderno->page;

        $i=0;
        for ($pag_dep = $caderno->page + 1; $pag_dep <= $caderno->page + $max_links; $pag_dep++) {
            if ($pag_dep <= $quantidade_pg) {
                $link  = $selects ? $selects.'&page='.$pag_dep.'&per_page='.$caderno->perPage.'&admin='.$id : '?page='. $pag_dep .'&per_page='.$caderno->perPage.'&admin='.$id;
                $link_dep[$i]['href_dep'] = $link;
                $link_dep[$i]['pg_dep'] = $pag_dep;
            }
            $i++;
        }
        if (($caderno->page + 3) <= $quantidade_pg) {
            $ultima_pg = $selects ? $selects.'&page='.$quantidade_pg.'&per_page='.$caderno->perPage.'&admin='.$id : '?page='. $quantidade_pg .'&per_page='.$caderno->perPage.'&admin='.$id;
            $link_ultimo[0]['href_ultimo'] = $ultima_pg;
            $link_ultimo[0]['pontos'] = '...';
            $link_ultimo[0]['ultima_pg'] = $quantidade_pg;
        }
        $ultima     = $selects ? $selects.'&page='.$quantidade_pg.'&per_page='.$caderno->perPage.'&admin='.$id : '?page='. $quantidade_pg .'&per_page='.$caderno->perPage.'&admin='.$id;


        //Linstando os dados
        $count = count($relatorios);
        if ($count > 0) {
            $i = 0;
            foreach ($relatorios as $atribuicao) {

//                var_dump($url_tb_avaliador_id);die();

                $avaliacoes[$i]['id_atribuicao'] = $atribuicao->id;
                $avaliacoes[$i]['id_user'] = $atribuicao->id;
                $avaliacoes[$i]['link'] = $atribuicao->link;
                $avaliacoes[$i]['anteprojeto'] = $atribuicao->anteprojeto;
                $avaliacoes[$i]['avaliador'] = $atribuicao->avaliador;

                $avaliacoes[$i]['status'] = $atribuicao->status;
                $avaliacoes[$i]['statuscolor'] = 'status_red';

                $tempo_atual = strtotime(date("Y-m-d")); // Data Atual
                $tempo = new \DateTime($atribuicao->data_ini_avaliacao);
                $tempo_evento = strtotime(date($tempo->format('Y-m-d'))); // Data Atribuido

                $tempo_zero = strtotime(date($tempo->format('Y-m-d')));
                //            $dia_zero = $tempo_atual - $tempo_zero ;

                $tempo->add(new \DateInterval('P' . $atribuicao->qt_dia_avaliacao . 'D'));
                $data_mais_qt_dia = strtotime(date($tempo->format('Y-m-d'))); // Data Atribuido

                $diferenca = $data_mais_qt_dia - $tempo_atual;
                $dias = intval($diferenca / 86400);

                $avaliacoes[$i]['data_ini_avaliacao'] = date('d/m/Y', strtotime($atribuicao->data_ini_avaliacao));

                /* ==== REGRAS =======
                Condiçao para deixar sem o LAPIS e com "--"
                Quando a data atual < data evento
                */
                if (($tempo_atual < $tempo_evento)) {
                    $qt_dia = '--';
//                $avaliacoes[$i]['lapis'] = '';

                } else if (($dias >= 0) && ($avaliacoes[$i]['status'] == 'Pendente')) {
                    $qt_dia = $dias;
                    $avaliacoes[$i]['enviar_email'] = '<i class="icon fa fa-envelope fa-fw " title="Enviar e-mail" role="img" aria-label=""></i>';

                } else if (($avaliacoes[$i]['status'] == 'Avaliado')) {
                    $qt_dia = '--';
                    $avaliacoes[$i]['lapis'] = '';
                    $avaliacoes[$i]['enviar_email'] = '';
                    $avaliacoes[$i]['statuscolor'] = 'status_blue';

                    $data_avaliacao = $DB->get_record('eva_bolsa_avaliacao', array('tb_atribuicao_id' => $atribuicao->id));
                    $avaliacoes[$i]['idavaliacao'] = $data_avaliacao->id;
                    $avaliacoes[$i]['data_avaliacao'] = date('d/m/Y', strtotime($data_avaliacao->data_avaliacao));
                } else if ($dias <= 0 && ($avaliacoes[$i]['status'] == 'Pendente')) {
                    $qt_dia = '<span style="color: red"> -esgotado- </span>';
                }

                $avaliacoes[$i]['timerestante'] = $qt_dia;
                $i++;
            }
        }

//==================================================================================================================================================================================

        $anteprojeto  = $_GET['anteprojeto'];
        $avaliador     = $_GET['avaliador'];
        $status      = $_GET['status'];


        $data->anteprojeto = $this->filtro_select_anteprojeto($anteprojeto);
        $data->avaliadores = $this->filtro_select_avaliador($avaliador);
        $data->status = $this->filtro_select_status($status);

        $data->avaliacao_bolsa = $avaliacoes;
        $data->iduser = $USER->id;


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

        return $data;

    }

    public function filtro_select_anteprojeto ($anteprojeto){
        global $DB;
        $sql = "SELECT anteprojeto FROM vw_barema_bolsa_admin GROUP BY anteprojeto ORDER BY anteprojeto ASC";
        $dados = $DB->get_records_sql($sql);

        $i=0;
        foreach ($dados as $key=>$dado) {
            $array[$i]['valor'] = $key;
            $array[$i]['selected'] = ($key == $anteprojeto) ? 'selected' : '';
            $array[$i]['selecao'] = $dado->anteprojeto;
            $i++;
        }
        return $array;
    }

    public function filtro_select_avaliador ($avaliador){
        global $DB;
        $sql = "SELECT avaliador FROM vw_barema_bolsa_admin GROUP BY avaliador ORDER BY avaliador ASC";
        $dados = $DB->get_records_sql($sql);

        $i=0;
        foreach ($dados as $key=>$dado) {
            $array[$i]['valor'] = $key;
            $array[$i]['selected'] = ($key == $avaliador) ? 'selected' : '';
            $array[$i]['selecao'] = $dado->avaliador;
            $i++;
        }
        return $array;
    }
    public function filtro_select_status ($status){
        global $DB;
        $sql = "SELECT status FROM vw_barema_bolsa_admin GROUP BY status ORDER BY status ASC";
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