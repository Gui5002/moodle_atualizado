<?php

namespace block_eva_postgraduate_about\output;

defined('MOODLE_INTERNAL') || die();

use moodle_url;
use renderable;
use renderer_base;
use templatable;

class postgraduate_about implements renderable, templatable {

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

        $data = new \stdClass();
        if(!empty($this->config->title)){$data->title = $this->config->title;}
        if(!empty($this->config->subtitle)){$data->subtitle = $this->config->subtitle;}
        if(!empty($this->config->style)){$data->style = $this->config->style;} else {$data->style = 0;}

        //var_dump($this->content->style);die();

        if($data->style == 1) {
            $class = '';
            $img = 'left';
            $info = 'right';
        } else {
            $img = 'right';
            $info = 'left';
            $class = 'ccn-row-reverse';
        }

        //var_dump($this->context);die();

        $fs = get_file_storage();
        $files = $fs->get_area_files($this->context->id, 'block_eva_postgraduate_about', 'content');
        $this->config->image = $CFG->wwwroot . '/blocks/eva_postgraduate_services/img/services.png';
        foreach ($files as $file) {
            $filename = $file->get_filename();
            if ($filename <> '.') {
                $url = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), null, $file->get_filepath(), $filename);
                $this->config->image =  $url;
            }
        }
        //$teste = format_text($this->config->text1['text'], FORMAT_HTML, array('filter' => true, 'noclean' => true));
        //var_dump($teste);die();

        for ($i = 1; $i <= $this->config->slidesnumber; $i++) {
            $title = "title".$i;
            $text = "text".$i;
            $show = $i == 1 ? 'show' : '';
            $grades[$i-1]['id'] = $i;
            $grades[$i-1]['info_title'] = format_text($this->config->$title, FORMAT_HTML, array('filter' => true));
            $grades[$i-1]['ccnAccTitle'] = $title;
            $grades[$i-1]['info_text'] = $this->config->$text['text'];
            $grades[$i-1]['ccnAccBody'] = $text;
            $grades[$i-1]['info_show'] = $show;
        }

        $data->info = $grades;
        $data->reverso = $class;
        $data->posicion_img = $img;
        $data->posicion_info = $info;
        $data->img = $this->config->image;

        return $data;


    }

}
