<?php

namespace block_eva_form_barema\output;

defined('MOODLE_INTERNAL') || die();

use moodle_url;
use renderable;
use renderer_base;
use templatable;

class customize_conf implements renderable, templatable {

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

        if ($this->config->dados_barema){
//            var_dump('entrou');die();
        }

//        $coursecategories = $DB->get_records_select('course_categories', 'parent = 0');
//        $coursecategories = $DB->get_records('course_categories', array("parent"=>0), 'id ASC');
//
//        $i = 1;
//        foreach ($coursecategories as $key=>$coursecategory){
//            $categoria[$i-1]['id_cat'] = (integer) $coursecategory->id;
//            $categoria[$i-1]['nome_cat'] = $coursecategory->name;
//            $i++;
//        }

        return $data;

    }
}