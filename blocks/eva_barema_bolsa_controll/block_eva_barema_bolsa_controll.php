<?php

require_once($CFG->dirroot. '/theme/evagu/ccn/block_handler/ccn_block_handler.php');


class block_eva_barema_bolsa_controll extends block_base
{
    /**
     * Start block instance.
     */
    function init() {
        $this->title = get_string('afastcontrolepg', 'block_eva_barema_bolsa_controll');
    }

    /**
     * The block is usable in all pages
     */

    /**
     * The block is usable in all pages
     */
    function applicable_formats() {
//        $ccnBlockHandler = new ccnBlockHandler();
//        return $ccnBlockHandler->ccnGetBlockApplicability(array('all'));
        return array('all' => true);
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
        global $CFG, $PAGE, $DB, $USER;

        require_once($CFG->libdir . '/filelib.php');

        if ($this->content !== NULL) {
            return $this->content;
        }



        $baremacontroll = new \block_eva_barema_bolsa_controll\output\barema_bolsa_controll($this->config, $this->context);

        $renderer = $this->page->get_renderer('block_eva_barema_bolsa_controll');
        $this->content = new stdClass;

        $this->content->text = $renderer->render($baremacontroll);;
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