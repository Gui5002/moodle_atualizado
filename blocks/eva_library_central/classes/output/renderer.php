<?php

namespace block_eva_library_central\output;

defined('MOODLE_INTERNAL') || die;

use plugin_renderer_base;


class renderer extends plugin_renderer_base {


    public function render_library_central(library_central $library_central) {
        return $this->render_from_template('block_eva_library_central/central', $library_central->export_for_template($this));
    }
}