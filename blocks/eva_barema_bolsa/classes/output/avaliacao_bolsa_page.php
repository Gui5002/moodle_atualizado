<?php
/**
 * @author Celio Pereira Batalha
 * email: celio.batalha@gmail.com
 */

namespace block_eva_barema_bolsa\output;

defined('MOODLE_INTERNAL') || die();

use moodle_url;
use renderable;
use renderer_base;
use templatable;

class avaliacao_bolsa_page implements renderable, templatable {

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

        if(!empty($this->config->title)){$data->title = $this->config->title;}
        if(!empty($this->config->subtitle)){$data->subtitle = $this->config->subtitle;}
        if(!empty($this->config->items)){$data->items = $this->config->items;}

//        $data->titulo = "teste hash";
//        $data->subtitulo = "subtitulo hash";
//        $data->items = "subtitulo hash";
//
//        $hash_data =  base64_encode(serialize($data));
//
//        $hash_data = unserialize(base64_decode($hash_data));





        $urlatrib = $_GET['atrib'];
        $sql = "SELECT * FROM mdl_eva_bolsa_atribuicao WHERE url_atrib = '{$urlatrib}' ";
        $dados_atribuicao = $DB->get_record_sql($sql);
        $modelo =  unserialize((base64_decode($dados_atribuicao->barema_modelo)));
        $data->items = $modelo->items;
        $data->url_atrib = $dados_atribuicao->url_avaliacao . $dados_atribuicao->url_atrib;
        $data->id_avaliador = $dados_atribuicao->avaliador_tb_user_id;
        $data->id_modelo = $dados_atribuicao->tb_bolsa_modelo_id;
        $data->id_anteprojeto = $dados_atribuicao->tb_anteprojeto_id;

        $doc_anteprojeto = $DB->get_record('eva_bolsa_anteprojeto', array('id'=>$dados_atribuicao->tb_anteprojeto_id));

        $data->namefile = $doc_anteprojeto->filename;
        $data->pathfile = $doc_anteprojeto->path;

        $jurido = explode(',', $doc_anteprojeto->juridico);
        $gestao = explode(',', $doc_anteprojeto->tecnicojuridico);
        $articulaçoes = array_merge($jurido, $gestao);

        $j=0;
        for ($x=0; $x < count($articulaçoes); $x++) {
            if ($articulaçoes[$x] > "0"){
                $areaprioritarias[$j]['numero'] = $articulaçoes[$x];
                $areaprioritarias[$j]['areas'] = $DB->get_field('eva_articulacao_prioritaria', 'articulacao', array('numero'=>$articulaçoes[$x]));
                $j++;
            }
        }

        if ($data->items > 0) {
            for ($i = 1; $i <= $data->items; $i++) {
                $qtd = $i;
                $criterio = 'criterio'.$i;
                $nota_maxima = 'notamaxima'.$i;
                $label_baixa = 'labelbaixa'.$i;
                $valor_baixa = 'valorbaixa'.$i;
                $label_media1 = 'labelmedia1'.$i;
                $valor_media1 = 'valormedia1'.$i;
                $label_media2 = 'labelmedia2'.$i;
                $valor_media2 = 'valormedia2'.$i;
                $label_media3 = 'labelmedia3'.$i;
                $valor_media3 = 'valormedia3'.$i;
                $label_alta = 'labelalta'.$i;
                $valor_alta = 'valoralta'.$i;

                if ($this->config->$label_baixa &&  $this->config->$valor_baixa)
                {$barema[$i-1]['hidden_baixa'] = '';}else{$barema[$i-1]['hidden_baixa'] = 'hidden';}
                if ($this->config->$label_media1 &&  $this->config->$valor_media1)
                {$barema[$i-1]['hidden_media1'] = '';}else{$barema[$i-1]['hidden_media1'] = 'hidden';}
                if ($this->config->$label_media2 &&  $this->config->$valor_media2)
                {$barema[$i-1]['hidden_media2'] = '';}else{$barema[$i-1]['hidden_media2'] = 'hidden';}
                if ($this->config->$label_media3 &&  $this->config->$valor_media3)
                {$barema[$i-1]['hidden_media3'] = '';}else{$barema[$i-1]['hidden_media3'] = 'hidden';}
                if ($this->config->$label_alta &&  $this->config->$valor_alta)
                {$barema[$i-1]['hidden_alta'] = '';}else{$barema[$i-1]['hidden_alta'] = 'hidden';}

                $barema[$i-1]['id'] = $i;
                $barema[$i-1]['criterio'] = $this->config->$criterio;
                $barema[$i-1]['nota_maxima'] = $this->config->$nota_maxima;
                $barema[$i-1]['label_baixa'] = $this->config->$label_baixa;
                $barema[$i-1]['valor_baixa'] = $this->config->$valor_baixa;
                $barema[$i-1]['label_media1'] = $this->config->$label_media1;
                $barema[$i-1]['valor_media1'] = $this->config->$valor_media1;
                $barema[$i-1]['label_media2'] = $this->config->$label_media2;
                $barema[$i-1]['valor_media2'] = $this->config->$valor_media2;
                $barema[$i-1]['label_media3'] = $this->config->$label_media3;
                $barema[$i-1]['valor_media3'] = $this->config->$valor_media3;
                $barema[$i-1]['label_alta'] = $this->config->$label_alta;
                $barema[$i-1]['valor_alta'] = $this->config->$valor_alta;
            }

        }

        $data->id_user =  md5('userid='.$USER->id);
        $data->qtd = $qtd;
        $data->anteprojeto = $doc_anteprojeto->anteprojeto;
        $data->programa = $doc_anteprojeto->programa;
        $data->pesquisa = $doc_anteprojeto->pesquisa;
        $data->areaprioritaria = $areaprioritarias;
        $data->barema = $barema;

        return $data;

    }
}