<?php

namespace block_eva_form_barema\output;

defined('MOODLE_INTERNAL') || die();

//require_once ('classes/output/criarbarema.php');
use moodle_url;
use renderable;
use renderer_base;
use templatable;

class avaliador_config_lista implements renderable, templatable {

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

        $baremaid = $_GET['baremaid'];

        $data = new \stdClass();

//        if(!empty($this->config->title)){$data->title = $this->config->title;}
//        if(!empty($this->config->subtitle)){$data->subtitle = $this->config->subtitle;}
//        if(!empty($this->config->items)){$data->items = $this->config->items;}


//        $dadosbarema = $DB->get_record('eva_barema_avaliacao', array('id'=>$baremaid), '*');
//        $dataavaliador = $DB->get_records('eva_barema_avaliador', array("baremaid"=>$dadosbarema->id), 'id ASC');
//        var_dump('teste');die();
//        $count = count($dataavaliador);
//        if ($count > 0) {
//            $i = 0;
//            foreach ($dataavaliador as $key=>$avaliador) {
//                $id = $avaliador->avaliador_userid;
//                $datauser = $DB->get_record_sql("SELECT * FROM vw_autocomplete_user WHERE id='$id'");
//
//                $array[$i]['id'] = $avaliador->id;
//                $array[$i]['iduser'] = $datauser->id;
//                $array[$i]['nome'] = ucwords(strtolower($datauser->fullname));
//                $array[$i]['email'] = $datauser->email;
//                $i++;
//            }
//        }

        $dataavaliador = $DB->get_records('eva_barema_avaliador', array("tb_barema_id"=>$baremaid));
//        var_dump($dataavaliador);die();
        $count = count($dataavaliador);
        if ($count > 0) {
            $i = 0;
            foreach ($dataavaliador as $key=>$avaliador) {
                $id = $avaliador->avaliador_tb_user_id;
                $datauser = $DB->get_record_sql("SELECT * FROM vw_autocomplete_user WHERE id='$id'");

                $array[$i]['id'] = $avaliador->id;
                $array[$i]['iduser'] = $datauser->id;
                $array[$i]['nome'] = ucwords(strtolower($datauser->fullname));
                $array[$i]['email'] = $datauser->email;
                $i++;
            }
        }

        $data->baremaid = $dadosbarema->id;
        $data->barema = $dadosbarema->barema;
        $data->results = $count;
        $data->dadosuser = $array;



        return $data;

    }
}