<?php

namespace block_eva_form_library\output;

defined('MOODLE_INTERNAL') || die;

use plugin_renderer_base;


class renderer extends plugin_renderer_base {


//    public function render_form_library(form_library $form_library) {
//        return $this->render_from_template('block_eva_form_library/formlib', $form_library->export_for_template($this));
//    }

    public function render_formlib(formlib $formlib) {
        $formlibs = $this->render_from_template('block_eva_form_library/formlib', $formlib->export_for_template($this));

        return $formlibs;
    }
}