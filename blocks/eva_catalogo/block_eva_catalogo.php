<?php
global $CFG;
require_once($CFG->dirroot . '/theme/evagu/ccn/block_handler/ccn_block_handler.php');

class block_eva_catalogo extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_eva_catalogo');
    }

    public function get_content() {
        global $OUTPUT, $PAGE;
        if ($this->content !== null) {
            return $this->content;
        }

        $PAGE->requires->js(new moodle_url('/blocks/eva_catalogo/js/catalogo-dinamico.js'));

        $renderer = $PAGE->get_renderer('block_eva_catalogo');

        $this->content = new stdClass();
        $this->content->text = $renderer->render_catalogo_page($this->instance->id);
        $this->content->footer = '';

        return $this->content;
    }

    public function applicable_formats() {
        return ['all' => true];
    }
}