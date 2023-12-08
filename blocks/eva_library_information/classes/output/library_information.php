<?php

namespace block_eva_library_information\output;

defined('MOODLE_INTERNAL') || die();

use renderable;
use renderer_base;
use templatable;

class library_information implements renderable, templatable {

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
        global $USER, $OUTPUT, $PAGE, $CFG;

        require_once($CFG->libdir . '/filelib.php');
        if ($this->content !== null) {
            return $this->content;
        }

        $data = new \stdClass();
//        if(!empty($this->config->title)){$data->title = $this->config->title;} else {$this->config->title = '';}
//        if(!empty($this->config->body)){$data->inf_body = $this->config->body['text'];} else {$this->config->body = ''; }


        for ($i = 1; $i <= $this->config->items; $i++) {


            $info1 = 'title1'.$i;
            $subtitle1 = 'subtitle1'.$i;

            $info2 = 'title2'.$i;
            $subtitle2 = 'subtitle2'.$i;

            $info3 = 'title3'.$i;
            $subtitle3 = 'subtitle3'.$i;

            $show = $i == 1 ? 'show' : '';

            $title = 'title'.$i;
            $body = 'body'.$i;

            $info[$i-1]['id'] = $i;
            $info[$i-1]['title'] = $this->config->$title;
            $info[$i-1]['info_body'] = $this->config->$body['text'];

            $info[$i-1]['info_title1'] = $this->config->$info1;
            $info[$i-1]['info_subtitle1'] = $this->config->$subtitle1;

            $info[$i-1]['info_title2'] = $this->config->$info2;
            $info[$i-1]['info_subtitle2'] = $this->config->$subtitle2;

            $info[$i-1]['info_title3'] = $this->config->$info3;
            $info[$i-1]['info_subtitle3'] = $this->config->$subtitle3;

            if (empty($this->config->$info1) && (empty($this->config->$info2)) && (empty($this->config->$info3))) {
                $info[$i-1]['hidden'] = 'hidden';
            }

            $info[$i-1]['color'] = ($i % 2) ? 'rgb(245, 245, 246)' : 'rgb(253 253 253)';

        }

        $data->info = $info;

//        var_dump($data);die();

        return $data;

    }

}
