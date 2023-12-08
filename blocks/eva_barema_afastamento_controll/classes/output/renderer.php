<?php

namespace block_eva_barema_afastamento_controll\output;

defined('MOODLE_INTERNAL') || die;

use plugin_renderer_base;


class renderer extends plugin_renderer_base {


    public function render_barema_afastamento_controll(barema_afastamento_controll $barema_afastamento_controll) {
        $afastamentocontroll = $this->render_from_template('block_eva_barema_afastamento_controll/barema_afastamento_controll', $barema_afastamento_controll->export_for_template($this));

        return $afastamentocontroll;
    }

}
