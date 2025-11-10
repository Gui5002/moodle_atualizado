<?php

namespace block_eva_barema_bolsa\output;
defined('MOODLE_INTERNAL') || die();

//use block_rss_client\output\item;
use DateTime;
use moodleform;
use moodle_url;
use renderer_base;
use renderable;
use templatable;

require_once($CFG->libdir.'/formslib.php');

class criar_modelo_bolsa implements renderable, templatable
{
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

        $items = 1;
        $num = 1;
        for ($i=0; $i < $items; $i++) {
            if ($num == 1){
                $array[$i]['condition'] = "true";
                $array[$i]['show'] = "show";
            }else{
                $array[$i]['colapsed'] = "collapsed";
                $array[$i]['condition'] = "false";
            }
            $array[$i]['num'] = $num;
            $num++;
        }

        $data->tablequesito = $array;
        $data->nums = $items;

        return $data;

    }

}