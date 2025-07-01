<?php

namespace block_eva_barema_afastamento\output;

defined('MOODLE_INTERNAL') || die();

//require_once ('classes/output/criarbarema.php');
use moodle_url;
use renderable;
use renderer_base;
use templatable;

class anteprojeto_espelho_pdf implements renderable, templatable {

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


        $id = $_GET['id'];
        $dataview = $DB->get_records_sql("SELECT * FROM vw_relatorio_afastamento WHERE anteprojeto='$id'");

        $x=0;
        foreach ($dataview as $view){
            $nota = $DB->get_record('eva_afastamento_avaliacao', array('id'=>$view->id));
            $modelo = unserialize((base64_decode($view->barema_modelo)));

            $array_view[$x]['title'] = $modelo->title;
            $array_view[$x]['subtitle'] = $modelo->title;

            if ($modelo->items > 0){
                for ($i = 1; $i <= $modelo->items; $i++) {
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
                    {$array_view[$x]['barema'][$i-1]['hidden_baixa'] = '';}else{ $array_view[$x]['barema'][$i-1]['hidden_baixa'] = 'none';}
                    if ($modelo->$label_2 &&  $modelo->$valor_2)
                    {$array_view[$x]['barema'][$i-1]['hidden_media1'] = '';}else{ $array_view[$x]['barema'][$i-1]['hidden_media1'] = 'none';}
                    if ($modelo->$label_3 &&  $modelo->$valor_3)
                    {$array_view[$x]['barema'][$i-1]['hidden_media2'] = '';}else{ $array_view[$x]['barema'][$i-1]['hidden_media2'] = 'none';}
                    if ($modelo->$label_4 &&  $modelo->$valor_4)
                    {$array_view[$x]['barema'][$i-1]['hidden_media3'] = '';}else{ $array_view[$x]['barema'][$i-1]['hidden_media3'] = 'none';}
                    if ($modelo->$label_5 &&  $modelo->$valor_5)
                    {$array_view[$x]['barema'][$i-1]['hidden_alta'] = '';}else{ $array_view[$x]['barema'][$i-1]['hidden_alta'] = 'none';}

                    $array_view[$x]['barema'][$i-1]['id'] = $i;
                    $array_view[$x]['barema'][$i-1]['criterio'] = $modelo->$criterio;
                    $array_view[$x]['barema'][$i-1]['nota_maxima'] = $modelo->$nota_maxima;
                    $array_view[$x]['barema'][$i-1]['label_baixa'] = $modelo->$label_1;
                    $array_view[$x]['barema'][$i-1]['valor_baixa'] = $modelo->$valor_1;
                    $array_view[$x]['barema'][$i-1]['checked_1'] = ($nota->$notaFaixa == $modelo->$valor_1) ? 'x':'  ';
                    $array_view[$x]['barema'][$i-1]['label_media1'] = $modelo->$label_2;
                    $array_view[$x]['barema'][$i-1]['valor_media1'] = $modelo->$valor_2;
                    $array_view[$x]['barema'][$i-1]['checked_2'] = ($nota->$notaFaixa == $modelo->$valor_2) ? 'x':'  ';
                    $array_view[$x]['barema'][$i-1]['label_media2'] = $modelo->$label_3;
                    $array_view[$x]['barema'][$i-1]['valor_media2'] = $modelo->$valor_3;
                    $array_view[$x]['barema'][$i-1]['checked_3'] = ($nota->$notaFaixa == $modelo->$valor_3) ? 'x':'  ';
                    $array_view[$x]['barema'][$i-1]['label_media3'] = $modelo->$label_4;
                    $array_view[$x]['barema'][$i-1]['valor_media3'] = $modelo->$valor_4;
                    $array_view[$x]['barema'][$i-1]['checked_4'] = ($nota->$notaFaixa == $modelo->$valor_4) ? 'x':'  ';
                    $array_view[$x]['barema'][$i-1]['label_alta'] = $modelo->$label_5;
                    $array_view[$x]['barema'][$i-1]['valor_alta'] = $modelo->$valor_5;
                    $array_view[$x]['barema'][$i-1]['checked_5'] = ($nota->$notaFaixa == $modelo->$valor_5) ? 'x':'  ';
                    $array_view[$x]['barema'][$i-1]['notachecked'] =  $nota->$notaFaixa;

                }
            }

//            $array_view[$x]['avaliador'] = $view->avaliador;
            $array_view[$x]['avaliador'] = $x+1;
            $array_view[$x]['anteprojeto'] = $view->anteprojeto;
            $array_view[$x]['datatime'] = $view->data_avaliacao;
            $array_view[$x]['notaAvaliador'] = $view->nt_avaliador;

            $nt_capes = $view->notacapes;
            $x++;
        }

        $sql = "SELECT * FROM mdl_eva_afastamento_result_final rs where anteprojeto = '{$id}'";
        $notas = $DB->get_record_sql($sql);

        if(($notas->avaliador_1 > 0.00 && $notas->avaliador_2 > 0.00 && $notas->avaliador_3 > 0.00)){
            $media = ($notas->avaliador_1 + $notas->avaliador_2 + $notas->avaliador_3)/3;
        } else {
            $msg_pendente = "Existe avaliacão pendente!";
            $media = "0.00";
        }

        $data->nt_capes = $notas->nt_capes;
        $data->nt_media = number_format($media, 2, '.', '');
        $data->nt_final = $notas->nt_final;
        $data->msgpendente = $msg_pendente;

        $data->espelhos = $array_view;

        return $data;

    }
}