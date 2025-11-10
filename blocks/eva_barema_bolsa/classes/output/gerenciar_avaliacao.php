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

class gerenciar_avaliacao implements renderable, templatable {

    var $config;
    var $context;

    public function __construct($config, $context) {
        $this->config = $config;
        $this->context = $context;
    }

    private function filter_busca( ) {
        global $USER, $PAGE, $CFG, $DB;


        $anteprojeto = filter_input(INPUT_GET, 'anteprojeto', FILTER_SANITIZE_STRING);
//        $avaliador = filter_input(INPUT_GET, 'avaliadores', FILTER_SANITIZE_STRING);
//        $dataatribuicao = filter_input(INPUT_GET, 'data_atribuicao', FILTER_SANITIZE_STRING);
//        $status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_STRING);


        $condicoes = [
            strlen($anteprojeto) ? ' anteprojeto LIKE "%'.str_replace(' ','%',$anteprojeto).'%"':null,
//            strlen($avaliador) ? ' avaliador LIKE "%'.str_replace(' ','%',$avaliador).'%"':null,
//            strlen($dataatribuicao) ? ' data_ini_avaliacao LIKE "%'.str_replace(' ','%',$dataatribuicao).'%"':null,
//            strlen($status) ? ' status LIKE "%'.str_replace(' ','%',$status).'%"':null,
        ];
        //REMOVE POSICOES VAZIAS
        $condicoes = array_filter($condicoes);

        //CLÁUSULA WHERE
        $where = implode(' AND ', $condicoes);

        return $where;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return stdClass
     */

    public function export_for_template(renderer_base $output) {
        global $DB, $USER;

        $data = new \stdClass();

        $id = $USER->id;

        $clausulaWhere = $this->filter_busca();
        $where = $clausulaWhere ? 'WHERE'.$clausulaWhere : 'WHERE 1=1 ';

        $sql = "SELECT id, anteprojeto, avaliador_tb_user_id, avaliador, data_ini_avaliacao, qt_dia_avaliacao, link, status 
                FROM vw_barema_bolsa_admin {$where} AND avaliador_tb_user_id = {$id} ";

        $bolsas = $DB->get_records_sql($sql);



        $i=0;
        foreach ($bolsas as $atribuicao){

            $sql = "SELECT id, fullname, email FROM vw_autocomplete_user where id = '{$id}'";
            $avaliador = $DB->get_record_sql($sql);

            $avaliacoes[$i]['userid'] = md5('userid='.$atribuicao->avaliador_tb_user_id);
            $avaliacoes[$i]['link'] = $atribuicao->link;
            $avaliacoes[$i]['anteprojeto'] = $atribuicao->anteprojeto;

            $avaliacoes[$i]['status'] = $atribuicao->status;
            $avaliacoes[$i]['statuscolor'] = 'status_red';


            $tempo_atual = strtotime(date("Y-m-d")); // Data Atual
            $tempo = new \DateTime($atribuicao->data_ini_avaliacao);
            $tempo_evento = strtotime(date($tempo->format('Y-m-d'))); // Data Atribuido

            $tempo_zero = strtotime(date($tempo->format('Y-m-d')));
//            $dia_zero = $tempo_atual - $tempo_zero ;


            $tempo->add(new \DateInterval('P'.$atribuicao->qt_dia_avaliacao.'D'));
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
                $avaliacoes[$i]['lapis'] = '';

            }else if (($dias >= 0) && ($avaliacoes[$i]['status'] == 'Pendente')) {
                $qt_dia = $dias;
                $avaliacoes[$i]['lapis'] = '<i class="icon fa fa-pencil fa-fw " title="Avaliar" role="img" aria-label=""></i>';

            }else if (($avaliacoes[$i]['status'] == 'Avaliado')) {
                $qt_dia = '--';
                $avaliacoes[$i]['lapis'] = '';
                $avaliacoes[$i]['preview'] = 'preview';
                $avaliacoes[$i]['statuscolor'] = 'status_blue';

                $data_avaliacao = $DB->get_record('eva_bolsa_avaliacao', array('tb_atribuicao_id'=>$atribuicao->id));
                $avaliacoes[$i]['idavaliacao'] = $data_avaliacao->id;
                $avaliacoes[$i]['data_avaliacao'] = date('d/m/Y', strtotime($data_avaliacao->data_avaliacao));
            }else if($dias <= 0 && ($avaliacoes[$i]['status'] == 'Pendente')) {
                $qt_dia = '<span style="color: red"> -esgotado- </span>';
                $avaliacoes[$i]['lapis'] = '';
            }

            $avaliacoes[$i]['timerestante'] = $qt_dia;

            $i++;
        }

        $anteprojeto  = $_GET['anteprojeto'];
        $data->anteprojeto = $this->filtro_select_anteprojeto($anteprojeto);

        $data->avaliador = $avaliador->fullname;
        $data->avaliacao_bolsa = $avaliacoes;
        $data->iduser = $_GET['gerenciar'];

        $data->gerecia = 'd-block';
        $data->admin = 'hidden';


        return $data;

    }
    public function filtro_select_anteprojeto ($anteprojeto){
        global $DB, $USER;
        $sql = "SELECT anteprojeto FROM vw_barema_bolsa_admin WHERE avaliador_tb_user_id = {$USER->id} GROUP BY anteprojeto ORDER BY anteprojeto ASC";
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
}