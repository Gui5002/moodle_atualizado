<?php

namespace block_eva_barema_afastamento\output;

defined('MOODLE_INTERNAL') || die();

//require_once ('classes/output/criarbarema.php');
use moodle_url;
use renderable;
use renderer_base;
use templatable;

class gerenciar_espelho_pdf implements renderable, templatable {

    var $config;
    var $context;

    public function __construct($config, $context) {
        $modelo = $config;
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
//        if(!empty($modelo->title)){$data->title = $modelo->title;}
//        if(!empty($modelo->subtitle)){$data->subtitle = $modelo->subtitle;}
//        if(!empty($modelo->items)){$data->items = $modelo->items;}



        $id = $_GET['espelho'];
        $nota = $DB->get_record('eva_afastamento_avaliacao', array('id'=>$id));

        $dataview = $DB->get_record_sql("SELECT * FROM vw_relatorio_afastamento WHERE id='$id'");
        $modelo = unserialize((base64_decode($dataview->barema_modelo)));
        $data->title = $modelo->title;
        $data->subtitle = $modelo->subtitle;

        $data->items = $modelo->items;

        if ($data->items > 0) {
            for ($i = 1; $i <= $data->items; $i++) {
                $qtd = $i;
                $criterio = 'criterio'.$i;
                $nota_maxima = 'notamaxima'.$i;
                $label_1 = 'labelbaixa'.$i;
                $valor_1 = 'valorbaixa'.$i;
                $label_2 = 'labelmedia1'.$i;
                $valor_2 = 'valormedia1'.$i;
                $label_3 = 'labelmedia2'.$i;
                $valor_3 = 'valormedia2'.$i;
                $label_4 = 'labelmedia3'.$i;
                $valor_4 = 'valormedia3'.$i;
                $label_5 = 'labelalta'.$i;
                $valor_5 = 'valoralta'.$i;
                $notaFaixa = 'nt_faixa_'.$i;

                if ($modelo->$label_1 &&  $modelo->$valor_1)
                {$barema[$i-1]['hidden_baixa'] = '';}else{$barema[$i-1]['hidden_baixa'] = 'none';}
                if ($modelo->$label_2 &&  $modelo->$valor_2)
                {$barema[$i-1]['hidden_media1'] = '';}else{$barema[$i-1]['hidden_media1'] = 'none';}
                if ($modelo->$label_3 &&  $modelo->$valor_3)
                {$barema[$i-1]['hidden_media2'] = '';}else{$barema[$i-1]['hidden_media2'] = 'none';}
                if ($modelo->$label_4 &&  $modelo->$valor_4)
                {$barema[$i-1]['hidden_media3'] = '';}else{$barema[$i-1]['hidden_media3'] = 'none';}
                if ($modelo->$label_5 &&  $modelo->$valor_5)
                {$barema[$i-1]['hidden_alta'] = '';}else{$barema[$i-1]['hidden_alta'] = 'none';}

                $barema[$i-1]['id'] = $i;
                $barema[$i-1]['criterio'] = $modelo->$criterio;
                $barema[$i-1]['nota_maxima'] = $modelo->$nota_maxima;
                $barema[$i-1]['label_baixa'] = $modelo->$label_1;
                $barema[$i-1]['valor_baixa'] = $modelo->$valor_1;
                $barema[$i-1]['checked_1'] = ($nota->$notaFaixa == $modelo->$valor_1) ? 'x':'  ';
                $barema[$i-1]['label_media1'] = $modelo->$label_2;
                $barema[$i-1]['valor_media1'] = $modelo->$valor_2;
                $barema[$i-1]['checked_2'] = ($nota->$notaFaixa == $modelo->$valor_2) ? 'x':'  ';
                $barema[$i-1]['label_media2'] = $modelo->$label_3;
                $barema[$i-1]['valor_media2'] = $modelo->$valor_3;
                $barema[$i-1]['checked_3'] = ($nota->$notaFaixa == $modelo->$valor_3) ? 'x':'  ';
                $barema[$i-1]['label_media3'] = $modelo->$label_4;
                $barema[$i-1]['valor_media3'] = $modelo->$valor_4;
                $barema[$i-1]['checked_4'] = ($nota->$notaFaixa == $modelo->$valor_4) ? 'x':'  ';
                $barema[$i-1]['label_alta'] = $modelo->$label_5;
                $barema[$i-1]['valor_alta'] = $modelo->$valor_5;
                $barema[$i-1]['checked_5'] = ($nota->$notaFaixa == $modelo->$valor_5) ? 'x':'  ';
                $barema[$i-1]['notachecked'] =  $nota->$notaFaixa;

            }
        }
//        if (!$DB->get_field('eva_barema_permissao', 'user_id', array('user_id'=>$USER->id))) {
//            if (!$DB->get_field('eva_barema_avaliador', 'avaliador_tb_user_id', array('avaliador_tb_user_id'=>$USER->id))) {
//                $hidden = 'hidden';
//            }
//        }
        $data->avaliador = $dataview->avaliador;
        $data->anteprojeto = $dataview->anteprojeto;
        $data->datatime = $dataview->data_avaliacao;
        $data->notacapes = $dataview->notacapes;
        $data->notaAvaliador = $nota->nt_avaliador; //+ $dataview->notacapes;
        $data->barema = $barema;

        return $data;

    }
}