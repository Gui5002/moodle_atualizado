<?php

namespace block_eva_continuing_education_b2\output;

defined('MOODLE_INTERNAL') || die;

use plugin_renderer_base;


class renderer extends plugin_renderer_base {


    public function render_eva_continuing_education_b2(eva_continuing_education_b2 $eva_continuing_education_b2) {
        return $this->render_from_template('block_eva_continuing_education_b2/services', $eva_continuing_education_b2->export_for_template($this));
    }
}