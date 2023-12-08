<?php

namespace block_eva_cards_post_internship\output;

defined('MOODLE_INTERNAL') || die;

use plugin_renderer_base;


class renderer extends plugin_renderer_base {


    public function render_post_internship(post_internship $post_internship) {
        return $this->render_from_template('block_eva_cards_post_internship/internship', $post_internship->export_for_template($this));
    }
}