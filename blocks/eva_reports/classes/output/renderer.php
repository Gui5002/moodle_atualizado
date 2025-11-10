<?php

namespace block_eva_reports\output;

defined('MOODLE_INTERNAL') || die;

use plugin_renderer_base;


class renderer extends plugin_renderer_base {
    public function render_form_library(reports $reports) {
        return $this->render_from_template('block_eva_reports/reports', $reports->export_for_template($this));
    }
}