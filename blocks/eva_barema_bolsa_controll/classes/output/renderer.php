<?php

namespace block_eva_barema_bolsa_controll\output;

defined('MOODLE_INTERNAL') || die;

use plugin_renderer_base;


class renderer extends plugin_renderer_base {


    public function render_barema_bolsa_controll(barema_bolsa_controll $barema_bolsa_controll) {
        $bolsacontroll = $this->render_from_template('block_eva_barema_bolsa_controll/barema_bolsa_controll', $barema_bolsa_controll->export_for_template($this));

        return $bolsacontroll;
    }

}
