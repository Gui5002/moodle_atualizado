<?php

namespace block_eva_about_databases\output;

defined('MOODLE_INTERNAL') || die;

use plugin_renderer_base;


class renderer extends plugin_renderer_base {


    public function render_about_databases(about_databases $about_databases) {
        return $this->render_from_template('block_eva_about_databases/databases', $about_databases->export_for_template($this));
    }

}
