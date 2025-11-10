<?php

namespace block_eva_barema_bolsa_controll\output;

defined('MOODLE_INTERNAL') || die();

//require_once ('classes/output/criarbarema.php');
use moodle_url;
use renderable;
use renderer_base;
use templatable;

class barema_bolsa_controll implements renderable, templatable {

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

//        $data->hidden_novo = 'd-none';
//        $data->hidden_lista = 'd-none';
//        $data->hidden_info = 'd-none';
//
//        if (!$DB->get_field('eva_barema_permissao', 'user_id', array('user_id'=>$USER->id))) {
//            if (!$DB->get_field('eva_barema_avaliador', 'avaliador_tb_user_id', array('avaliador_tb_user_id'=>$USER->id))) {
//                if ($DB->get_field('eva_barema_avaliacao', 'aluno_tb_user_id', array('aluno_tb_user_id'=>$USER->id))){
//                    $data->hidden_lista = '';
//                }else{
//                    $data->hidden_info = '';
//                }
//            }else{
//                $data->hidden_lista = '';
//            }
//        }else{
//            $data->hidden_novo = '';
//            $data->hidden_lista = '';
//        }


        return $data;

    }
}