<?php

namespace block_eva_reports_users\output;

defined('MOODLE_INTERNAL') || die;

use plugin_renderer_base;


class renderer extends plugin_renderer_base {
    public function render_form_library(reports_users $reports_users) {
        return $this->render_from_template('block_eva_reports/reports', $reports_users->export_for_template($this));
    }
}