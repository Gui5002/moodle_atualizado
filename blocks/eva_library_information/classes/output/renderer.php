<?php

namespace block_eva_library_information\output;

defined('MOODLE_INTERNAL') || die;

use plugin_renderer_base;


class renderer extends plugin_renderer_base {


    public function render_library_information(library_information $library_information) {
        return $this->render_from_template('block_eva_library_information/information', $library_information->export_for_template($this));
    }
}