<?php

namespace block_eva_continuing_education_b1\output;

defined('MOODLE_INTERNAL') || die();

use moodle_url;
use renderable;
use renderer_base;
use templatable;

class eva_continuing_education_b1 implements renderable, templatable {

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
        if(!empty($this->config->subtitle)){$data->subtitle = $this->config->subtitle;} else {$data->subtitle = '';}
        if(!empty($this->config->style)){$data->style = $this->config->style;} else {$data->style = 0;}
        if(!empty($this->config->color_bg)){$data->color_bg = $this->config->color_bg;} else {$data->color_bg = '#fff';}
        if(!empty($this->config->color_title)){$data->color_title = $this->config->color_title;} else {$data->color_title = '#0a0a0a';}
        if(!empty($this->config->color_subtitle)){$data->color_subtitle = $this->config->color_subtitle;} else {$data->color_subtitle = '#6f7074';}
        if(!empty($this->config->color_title_main)){$data->color_title_main = $this->config->color_title_main;} else {$data->color_title_main = '#6f809e';}


        if (!empty($this->config) && is_object($this->config)) {
            //$data = $this->config;
            $data->items = is_numeric($this->config->items) ? (int)$this->config->items : 4;
        } else {
            $data->items = 4;
        }

        $style = $CFG->wwwroot . '/blocks/eva_continuing_education_b1/style.css';
        $data->style = $style;

        $fs = get_file_storage();
        $files = $fs->get_area_files($this->context->id, 'block_eva_continuing_education_b1', 'content');
        $this->config->image = $CFG->wwwroot . '/theme/evagu/images/about/8.jpg';
        foreach ($files as $file) {
            $filename = $file->get_filename();
            if ($filename <> '.') {
                $url = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), null, $file->get_filepath(), $filename);
                $this->config->image =  $url;
            }
        }

        $col_class = "";
        if ($data->items == 1) {
            $col_class = "col-sm-12 col-lg-12";
        } else if ($data->items == 2) {
            $col_class = "col-sm-6 col-lg-6";
        } else if ($data->items == 3) {
            $col_class = "col-sm-6 col-lg-4";
        } else {
            $col_class = "col-sm-6 col-lg-3";
        }
        if ($data->items > 0) {
            for ($i = 1; $i <= $data->items; $i++) {
                $icon = 'icon'.$i;
                $icon_color = 'color_icon'.$i;
                $icon_color_hover = 'color_icon_hover'.$i;
                $title_color = 'color_title'.$i;
                $title_color_hover = 'color_title_hover'.$i;
                $body_color = 'body_color'.$i;
                $body_color_hover = 'body_color_hover'.$i;
                $color = 'color'.$i;
                $color_hover = 'color_hover'.$i;
                $title = 'title'.$i;
                $body = 'body'.$i;
                $link = 'link'.$i;
                $link_target = 'link_target'.$i;

                $boxes[$i-1]['id'] = $i;
                $boxes[$i-1]['icon'] = $this->config->$icon;
                $boxes[$i-1]['titulo'] = $this->config->$title;
                $boxes[$i-1]['body'] = $this->config->$body;
                $boxes[$i-1]['link'] = $this->config->$link;
                $boxes[$i-1]['link_target'] = $this->config->$link_target;
                $boxes[$i-1]['color'] = $this->config->$color;
                $boxes[$i-1]['color_icon'] = $this->config->$icon_color;
                $boxes[$i-1]['title_color'] = $this->config->$title_color;
                $boxes[$i-1]['body_color'] = $this->config->$body_color;

                $boxes[$i-1]['color_icon_hover'] = $this->config->$icon_color_hover;
                $boxes[$i-1]['title_color_hover'] = $this->config->$title_color_hover;
                $boxes[$i-1]['body_color_hover'] = $this->config->$body_color_hover;
                $bgcolor[$i-1]['color_hover'] = $this->config->$color_hover;
            }
        }

        $color = 'color';
        $data->color = $this->config->$color;
        $color_title_main = 'color_title_main';
        $data->color_title_main = $this->config->$color_title_main;

        $data->info = $boxes;
        $data->colors = $bgcolor;
        $data->imgs = $this->config->image;
        // echo '<pre>';var_dump($this->config);echo '</pre>';die();

        return $data;

    }

}
