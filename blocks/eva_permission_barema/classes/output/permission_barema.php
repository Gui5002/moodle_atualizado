<?php

namespace block_eva_permission_barema\output;

defined('MOODLE_INTERNAL') || die();

//require_once ('classes/output/criarbarema.php');
use moodle_url;
use renderable;
use renderer_base;
use templatable;

class permission_barema implements renderable, templatable {

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

        $sort = SORT_ASC;

        $dados = $DB->get_records('eva_barema_permissao', array(), $sort = 'name');
        $useradmin = $DB->record_exists('eva_barema_permissao', array('user_id'=>$USER->id,'admin'=>1));
        $admin = $DB->record_exists('eva_barema_permissao', array('admin'=>1));
        if($useradmin){
            $hidden = '';
        }else if($admin){
            $hidden = 'hidden';
        }else{
            $hidden = '';
        }

        $data = new \stdClass();
        if(!empty($this->config->title)){$data->title = $this->config->title;}
        if(!empty($this->config->subtitle)){$data->subtitle = $this->config->subtitle;}
        if(!empty($this->config->items)){$data->items = $this->config->items;}

        $i = 0;
        foreach($dados as $dado) {


            $array[$i]["id"] = $dado->id;
            $array[$i]["name"] = $dado->name;
            $array[$i]["pos"] = ($dado->posgraduacao == 1) ? 'checked' : '';
            $array[$i]["bolsa"] = ($dado->bolsa == 1) ? 'checked' : '';
            $array[$i]["afastamento"] = ($dado->afastamento == 1) ? 'checked' : '';
            $array[$i]["admin"] =  ($dado->admin == 1) ? 'checked' : '';
            $array[$i]["flag"] = ($dado->status == 1) ? 'checked' : '';
            $array[$i]["situation"] = ($dado->status == 1) ? 'ativado' : 'Dasativado';
            $array[$i]["status"] = $dado->status;
            $array[$i]["logs"] = $dado->logs;
            $array[$i]["hidden"] = $hidden;
            $array[$i]["disable"] = ($dado->status == 0) ? "disabled" : "";
            $array[$i]["icon_eye"] = ($dado->status == 0) ? "-slash" : "";
            $i++;
        }

        $data->users = $this->filtro_select_user();
        $data->hiden = $hidden;
        $data->permissoes = $array;

        return $data;

    }

    public function filtro_select_user (){
        global $DB;
        $sql = "SELECT id, fullname FROM vw_autocomplete_user ";
        $dados = $DB->get_records_sql($sql);
        $i=0;
        foreach ($dados as $key=>$dado) {
            $array[$i]['valor'] = $key;
            $array[$i]['selecao'] = $dado->fullname;
            $i++;
        }
        // var_dump($array);die();
        return $array;
    }
}