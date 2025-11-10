<?php

namespace block_eva_permission_barema_controll\output;

defined('MOODLE_INTERNAL') || die;

use plugin_renderer_base;


class renderer extends plugin_renderer_base {


    public function render_permissin_controll(permission_controll $permission_controll) {
        $returnpermissioncontroll = $this->render_from_template('block_eva_permission_barema_controll/permission_controll', $permission_controll->export_for_template($this));

        return $returnpermissioncontroll;
    }

}
