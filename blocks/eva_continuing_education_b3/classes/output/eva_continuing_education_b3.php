<?php

namespace block_eva_continuing_education_b3\output;

defined('MOODLE_INTERNAL') || die();

use moodle_url;
use renderable;
use renderer_base;
use templatable;

class eva_continuing_education_b3 implements renderable, templatable {

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
        if(!empty($this->config->text)){$data->text = $this->config->text;} else {$data->text = '';}
        if(!empty($this->config->color)){$data->color = $this->config->color;} else {$data->color = '#f5f5f6';}
        if(!empty($this->config->title_color)){$data->title_color = $this->config->title_color;} else {$data->title_color = '#000000';}
        if(!empty($this->config->subtitle_color)){$data->subtitle_color = $this->config->subtitle_color;} else {$data->subtitle_color = '#e4e2e0';}
        if(!empty($this->config->text_color)){$data->text_color = $this->config->text_color;} else {$data->text_color = '#000000';}
        if(!empty($this->config->cards_text_color)){$data->cards_text_color = $this->config->cards_text_color;} else {$data->cards_text_color = '#000000';}
        if(!empty($this->config->cards_color)){$data->cards_color = $this->config->cards_color;} else {$data->cards_color = '#a7b7c7';}
        if(!empty($this->config->cards_color_hover)){$data->cards_color_hover = $this->config->cards_color_hover;} else {$data->cards_color_hover = '#3f9ed6';}

        $style = $CFG->wwwroot . '/blocks/eva_continuing_education_b3/style3.css';
        $data->styleb3 = $style;

        if (!empty($this->config) && is_object($this->config)) {
            $data->items = is_numeric($this->config->items) ? (int)$this->config->items : 20;
        } else {
            $data->items = 20;
        }

        if ($data->items > 0) {
            for ($i = 1; $i <= $data->items; $i++) {
                $icon = 'item_icon'.$i;
                $title = 'item_title'.$i;
                $subtitle = 'item_subtitle'.$i;
                $body = 'item_body'.$i;
                $link = 'item_link'.$i;
                
                $boxes[$i-1]['id'] = $i;
                $boxes[$i-1]['item_icon'] = $this->config->$icon;
                $boxes[$i-1]['item_titulo'] = $this->config->$title;
                $boxes[$i-1]['item_subtitle'] = $this->config->$subtitle;
                $boxes[$i-1]['item_body'] = $this->config->$body;
                $boxes[$i-1]['item_link'] = $this->config->$link;
            }
        }

        $color = 'color';
        $data->color = $this->config->$color;

        $data->info = $boxes;
        //echo '<pre>';var_dump();echo '</pre>';die;
        return $data;
    }

}
