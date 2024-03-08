<?php

namespace block_eva_permission_barema\output;

defined('MOODLE_INTERNAL') || die;

use plugin_renderer_base;


class renderer extends plugin_renderer_base {


    public function render_permission_barema(permission_barema $permission_barema) {
        $returnpermission = $this->render_from_template('block_eva_permission_barema/users/create', $permission_barema->export_for_template($this));

        return $returnpermission;
    }

}
