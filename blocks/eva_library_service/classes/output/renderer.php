<?php

namespace block_eva_library_service\output;

defined('MOODLE_INTERNAL') || die;

use plugin_renderer_base;


class renderer extends plugin_renderer_base {


    public function render_library_service(library_service $library_service) {
        return $this->render_from_template('block_eva_library_service/lib_service', $library_service->export_for_template($this));
    }
}