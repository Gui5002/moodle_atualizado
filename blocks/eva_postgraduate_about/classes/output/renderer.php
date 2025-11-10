<?php

namespace block_eva_postgraduate_about\output;

defined('MOODLE_INTERNAL') || die;

use plugin_renderer_base;


class renderer extends plugin_renderer_base {


    public function render_postgraduate_about(postgraduate_about $postgraduate_about) {
        return $this->render_from_template('block_eva_postgraduate_about/about', $postgraduate_about->export_for_template($this));
    }

}
