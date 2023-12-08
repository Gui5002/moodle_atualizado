<?php

namespace block_eva_barema_afastamento\output;

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

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return stdClass
     */

    public function export_for_template(renderer_base $output) {
        global $DB, $USER;

        $data = new \stdClass();

//        if(!empty($this->config->title)){$data->title = $this->config->title;}
//        if(!empty($this->config->subtitle)){$data->subtitle = $this->config->subtitle;}
//        if(!empty($this->config->items)){$data->items = $this->config->items;}

        $id = $USER->id;

//        $sql = "SELECT * FROM mdl_eva_afastamento_atribuicao WHERE avaliador_tb_user_id ='{$id}' AND status = 0 AND flag = 0 AND ativo > 0";
        $sql = "SELECT * FROM mdl_eva_afastamento_atribuicao WHERE avaliador_tb_user_id ='{$id}' AND ativo > 0";
        $afastamentos = $DB->get_records_sql($sql);

//        $afastamentos = $DB->get_records('eva_afastamento_atribuicao', array('avaliador_tb_user_id'=>$id, 'ativo'=>1));

        $ano = date('Y');
        $mes = date('m');
        $dia = date('d');

        $i=0;
//            $existe = $DB->record_exists('eva_afastamento_atribuicao', array('tb_anteprojeto_id'=>$anteprojeto->id, 'ativo'=>1));
        foreach ($afastamentos as $atribuicao){

            $sql = "SELECT id, fullname, email FROM vw_autocomplete_user where id = '{$id}'";
            $avaliador = $DB->get_record_sql($sql);


            $avaliacoes[$i]['userid'] = md5('userid='.$atribuicao->avaliador_tb_user_id);
            $avaliacoes[$i]['link'] = $atribuicao->url_avaliacao . $atribuicao->url_atrib;

            $avaliacoes[$i]['anteprojeto'] = $DB->get_record('eva_afastamento_anteprojeto', array('id'=>$atribuicao->tb_anteprojeto_id))->anteprojeto;

            if ($atribuicao->status == 0 && $atribuicao->flag == 0){
                $avaliacoes[$i]['status'] = 'Pendente';
                $avaliacoes[$i]['statuscolor'] = 'status_red';
            }else if ($atribuicao->status == 1 && $atribuicao->flag == 0){
                $avaliacoes[$i]['status'] = 'Avaliado';
                $avaliacoes[$i]['statuscolor'] = 'status_red';
            }else{
                $avaliacoes[$i]['status'] = 'Substituído';
            }
//            $avaliacoes[$i]['status'] = ($atribuicao->status == 0) ? 'Pendente' : 'Avaliado';



            $tempo_atual = strtotime(date("Y-m-d")); // Data Atual
            $tempo = new \DateTime($atribuicao->data_ini_avaliacao);
            $tempo_evento = strtotime(date($tempo->format('Y-m-d'))); // Data Atribuido

            $tempo_zero = strtotime(date($tempo->format('Y-m-d')));
//            $dia_zero = $tempo_atual - $tempo_zero ;


            $tempo->add(new \DateInterval('P'.$atribuicao->qt_dia_avaliacao.'D'));
            $data_mais_qt_dia = strtotime(date($tempo->format('Y-m-d'))); // Data Atribuido

            $diferenca = $data_mais_qt_dia - $tempo_atual;
            $dias = intval($diferenca / 86400);

//            $marcador = $diferenca % 86400;
//            $hora = intval($marcador / 3600);
//            $marcador = $marcador % 3600;
//            $minuto = intval($marcador / 60);
//            $segundos = $marcador % 60;
//            var_dump($tempo_evento .' - '. $tempo_atual);die();




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

                $data_avaliacao = $DB->get_record('eva_afastamento_avaliacao', array('tb_atribuicao_id'=>$atribuicao->id));
                $avaliacoes[$i]['idavaliacao'] = $data_avaliacao->id;
                $avaliacoes[$i]['data_avaliacao'] = date('d/m/Y', strtotime($data_avaliacao->data_avaliacao));
            }else if($dias <= 0 && ($avaliacoes[$i]['status'] == 'Pendente')) {
                $qt_dia = '<span style="color: red"> -esgotado- </span>';
                $avaliacoes[$i]['lapis'] = '';
            }else{
                $qt_dia = '<span style="color: red"> -- </span>';
                $avaliacoes[$i]['statuscolor'] = 'status_orange';
                $avaliacoes[$i]['lapis'] = '';
            }

            $avaliacoes[$i]['timerestante'] = $qt_dia;




            $i++;
        }

        $data->avaliador = $avaliador->fullname;
        $data->avaliacao_afastamento = $avaliacoes;

        $data->gerecia = 'd-block';
        $data->admin = 'hidden';


        return $data;

    }
}