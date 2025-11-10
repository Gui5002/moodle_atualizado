<?php

namespace block_eva_form_barema_controll\output;

defined('MOODLE_INTERNAL') || die;

use plugin_renderer_base;


class renderer extends plugin_renderer_base {


    public function render_barema_controll(barema_controll $barema_controll) {
        $returnbaremacontroll = $this->render_from_template('block_eva_form_barema_controll/barema_controll', $barema_controll->export_for_template($this));

        return $returnbaremacontroll;
    }

}
