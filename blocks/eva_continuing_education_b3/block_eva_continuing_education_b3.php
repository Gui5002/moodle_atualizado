<?php

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot. '/course/renderer.php');
require_once($CFG->dirroot. '/theme/evagu/ccn/block_handler/ccn_block_handler.php');

class block_eva_continuing_education_b3 extends block_base {
    function init() {
        $this->title = get_string('pluginname', 'block_eva_continuing_education_b3');
    }

    /**
     * The block is usable in all pages
     */
    function applicable_formats() {
        $ccnBlockHandler = new ccnBlockHandler();
        return $ccnBlockHandler->ccnGetBlockApplicability(array('all'));
    }

    /**
     * The block can be used repeatedly in a page.
     */
    function instance_allow_multiple() {
        return true;
    }

    public function specialization() {
        global $CFG, $DB;
        include($CFG->dirroot.'/theme/evagu/ccn/block_handler/specialization.php');
    }

    function get_content() {
        if ($this->content !== null) {
            return $this->content;
        }

        $renderable            = new \block_eva_continuing_education_b3\output\eva_continuing_education_b3($this->config, $this->context);
        $renderer              = $this->page->get_renderer('block_eva_continuing_education_b3');
        $this->content         = new stdClass;
        $this->content->text   = $renderer->render($renderable);
        $this->content->footer = '';

        return $this->content;
    }

    public function html_attributes() {
        global $CFG;
        $attributes = parent::html_attributes();
        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/attributes.php');
        return $attributes;
    }
}