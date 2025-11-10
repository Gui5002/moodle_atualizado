<?php

namespace block_eva_postgraduate_contact\output;

defined('MOODLE_INTERNAL') || die;

use plugin_renderer_base;


class renderer extends plugin_renderer_base {


    public function render_postgraduate_contact(postgraduate_contact $postgraduate_contact) {
        return $this->render_from_template('block_eva_postgraduate_contact/contact', $postgraduate_contact->export_for_template($this));
    }
}