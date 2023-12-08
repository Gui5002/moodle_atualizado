<?php

namespace block_eva_continuing_education_b2\output;

defined('MOODLE_INTERNAL') || die();

use moodle_url;
use renderable;
use renderer_base;
use templatable;

class eva_continuing_education_b2 implements renderable, templatable {

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

        if(!empty($this->config->title)){$data->title = $this->config->title;} else {$data->title = '';}
        if(!empty($this->config->blockquote)){$data->blockquote = $this->config->blockquote;} else {$data->blockquote = '';}
        if(!empty($this->config->text)){$data->text = $this->config->text;} else {$data->text = '';}
        if(!empty($this->config->button)){$data->button = $this->config->button;} else {$data->button = '';}
        if(!empty($this->config->url)){$data->url = $this->config->url;} else {$data->url = '';}
        if(!empty($this->config->color)){$data->color = $this->config->color;} else {$data->color = '#eff2fc';}
        if(!empty($this->config->title_color)){$data->title_color = $this->config->title_color;} else {$data->title_color = '#6f809e';}
        if(!empty($this->config->button_color)){$data->button_color = $this->config->button_color;} else {$data->button_color = '#6f809e';}
        if(!empty($this->config->button_color_hover)){$data->button_color_hover = $this->config->button_color_hover;} else {$data->button_color_hover = '#a4a4a5';}
        if(!empty($this->config->button_text_color)){$data->button_text_color = $this->config->button_text_color;} else {$data->button_text_color = '#ffffff';}

        $style = $CFG->wwwroot . '/blocks/eva_continuing_education_b2/style2.css';
        $data->style = $style;

        $fs = get_file_storage();
        $files = $fs->get_area_files($this->context->id, 'block_eva_continuing_education_b2', 'content');
        $this->config->image = $CFG->wwwroot . '/theme/evagu/images/about/8.jpg';
        foreach ($files as $file) {
            $filename = $file->get_filename();
            if ($filename <> '.') {
                $url = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), null, $file->get_filepath(), $filename);
                $this->config->image =  $url;
            }
        }

        $color = 'color';
        $data->color = $this->config->$color;

        $data->imgs = $this->config->image;
        // echo '<pre>';var_dump($this->config);echo '</pre>';die();
        return $data;

    }

}
