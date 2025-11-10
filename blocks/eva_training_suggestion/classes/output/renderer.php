<?php

namespace block_eva_training_suggestion\output;

defined('MOODLE_INTERNAL') || die;

use plugin_renderer_base;


class renderer extends plugin_renderer_base {
    public function render_form_library(training_suggestion $training_suggestion) {
        return $this->render_from_template('block_eva_training_suggestion/training_suggestion', $training_suggestion->export_for_template($this));
    }
}