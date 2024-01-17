<?php

namespace block_eva_form_barema\output;

defined('MOODLE_INTERNAL') || die();

//require_once ('classes/output/criarbarema.php');
use moodle_url;
use renderable;
use renderer_base;
use templatable;

class form_barema implements renderable, templatable {

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

//        $coursecategories = $DB->get_records('course_categories', array("parent"=>0), 'id ASC');
//
//        $i = 0;
//        foreach ($coursecategories as $key=>$coursecategory){
//            $categoria[$i]['id_cat'] = (integer) $coursecategory->id;
//            $categoria[$i]['nome_cat'] = $coursecategory->name;
//            $i++;
//        }

//        $quiz_id = trim($_GET['quiz_id']);

//        $sql = "CALL GetCustomers('{$quiz_id}')";

//        $sqljoin = "SELECT id, userid FROM mdl_quiz_attempts WHERE quiz = '{$quiz_id}'";
//        $existe = $DB->record_exists_sql($sqljoin);
//
//        if ($existe){
//            $quizes = $DB->get_records_sql($sqljoin);
//            $i = 0;
//            foreach ($quizes as $key=>$quiz){
//
//                $sql = "SELECT * from vw_autocomplete_user where id = '{$quiz->userid}'";
//                $nome = $DB->get_record_sql($sql);
//                $alunos[$i]['id'] = $nome->id;
//                $alunos[$i]['aluno'] = $nome->fullname;
//                $i++;
//            }
//        }

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

        $data->id_user = $USER->id;
        $data->id_barema = $_GET['barema_id'];
        $data->id_avaliador = $_GET['avaliador_id'];
        $data->id_curso = $_GET['curso_id'];
        $data->id_quiz = $_GET['quiz_id'];
        $data->qtd = $qtd;
//        $data->cat = $categoria;
        $data->alunos = $alunos;
        $data->barema = $barema;

        return $data;

    }
}