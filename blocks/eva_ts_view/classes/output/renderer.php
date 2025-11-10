<?php

namespace block_eva_ts_view\output;

defined('MOODLE_INTERNAL') || die;

use plugin_renderer_base;


class renderer extends plugin_renderer_base {
    public function render_form_library(ts_view $ts_view) {
        return $this->render_from_template('block_eva_ts_view/ts_view', $ts_view->export_for_template($this));
    }
}