<?php

namespace block_eva_postgraduate_services\output;

defined('MOODLE_INTERNAL') || die;

use plugin_renderer_base;


class renderer extends plugin_renderer_base {


    public function render_postgraduate_services(postgraduate_services $postgraduate_services) {
        return $this->render_from_template('block_eva_postgraduate_services/services', $postgraduate_services->export_for_template($this));
    }
}