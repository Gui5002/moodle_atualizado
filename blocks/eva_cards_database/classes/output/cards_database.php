<?php

namespace block_eva_cards_database\output;

use moodle_url;
use renderable;
use renderer_base;
use templatable;

defined('MOODLE_INTERNAL') || die();

class cards_database implements renderable, templatable {

    var $config;
    var $context;

    public function __construct($config, $context) {
        $this->config = $config;
        $this->context = $context;
    }

    public function export_for_template(renderer_base $output)
    {
        global $USER;

        $usernome = $USER->firstname . ' ' . $USER->lastname;
        $useremail = $USER->email;

        $data = new \stdClass();

        if(!empty($this->config->title)){
            $data->title = format_text($this->config->title, FORMAT_HTML, array('filter' => true));
        } else {$data->title = '';}
        if(!empty($this->config->body)){
            $data->body =  format_text($this->config->body['text'], FORMAT_HTML, array('filter' => true, 'noclean' => true));
        } else {$data->body = '';}
        if(!empty($this->config->style)){$data->style = $this->config->style;} else {$data->style = 0;}
        if(!empty($this->config->color_bg)){$data->color_bg = $this->config->color_bg;} else {$data->color_bg = '#fff';}
        if(!empty($this->config->color_title)){$data->color_title = $this->config->color_title;} else {$data->color_title = '#0a0a0a';}
        if(!empty($this->config->color_subtitle)){$data->color_subtitle = $this->config->color_subtitle;} else {$data->color_subtitle = '#6f7074';}


        if (!empty($this->config) && is_object($this->config)) {
            //$data = $this->config;
            $data->items = is_numeric($this->config->items) ? (int)$this->config->items : 4;
        } else {
            $data->items = 3;
        }


        $fs = get_file_storage();
        $files = $fs->get_area_files($this->context->id, 'block_eva_cards_post_internship', 'content');
        $this->config->image = $CFG->wwwroot . '/blocks/eva_cards_post_internship/img/internship.png';
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
                $body_color = 'color_body'.$i;
                $body_color_hover = 'color_body_hover'.$i;
                $color = 'color'.$i;
                $color_hover = 'color_hover'.$i;
                $title = 'title'.$i;
                $body = 'body'.$i;
                $link = 'link'.$i;
                $link_target = 'link_target'.$i;
                $nome = 'nome'.$i;
                $email = 'email'.$i;

                $boxes[$i-1]['id'] = $i;
                $boxes[$i-1]['icon'] = $this->config->$icon;
                $boxes[$i-1]['card_titulo'] = $this->config->$title;
                $boxes[$i-1]['card_body'] = $this->config->$body;
                $param1 = "";
                $param2 = "";
                if($this->config->$nome && !$this->config->$email){
                    $param1 = 'v1='.$usernome;
                } else if(!$this->config->$nome && $this->config->$email){
                    $param1 = 'v1='.$useremail;
                }
                if($this->config->$nome && $this->config->$email) {
                    $param1 = 'v1='.$usernome.'&';
                    $param2 = 'v2='.$useremail;
                }
                $boxes[$i-1]['link'] = $this->config->$link . $param1 . $param2;
//                var_dump($boxes[$i-1]['link']);die();
                $boxes[$i-1]['link_target'] = $this->config->$link_target;
                $boxes[$i-1]['color'] = $this->config->$color;
                $boxes[$i-1]['icon_color'] = $this->config->$icon_color;
                $boxes[$i-1]['title_color'] = $this->config->$title_color;
                $boxes[$i-1]['body_color'] = $this->config->$body_color;

                $boxes[$i-1]['icon_color_hover'] = $this->config->$icon_color_hover;
                $boxes[$i-1]['title_color_hover'] = $this->config->$title_color_hover;
                $boxes[$i-1]['body_color_hover'] = $this->config->$body_color_hover;
                $boxes[$i-1]['color_hover'] = $this->config->$color_hover;

            }
        }


        $data->cards = $boxes;
        $data->imgs = $this->config->image;

        return $data;

    }
}
