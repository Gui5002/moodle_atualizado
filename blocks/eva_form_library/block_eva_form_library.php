<?php


require_once($CFG->dirroot. '/theme/evagu/ccn/block_handler/ccn_block_handler.php');

require_once ('classes/output/formlib.php');
require_once ('lib/librarylib.php');
require_once ('classes/privacy/library_contact.php');
//require_once($CFG->dirroot . '/local/contact/classes/local_contact.php');

class block_eva_form_library extends block_base {
    function init() {
        $this->title = get_string('pluginname', 'block_eva_form_library');
    }

    /**
     * The block is usable in all pages
     */
    function applicable_formats() {
         $ccnBlockHandler = new ccnBlockHandler();
         return $ccnBlockHandler->ccnGetBlockApplicability(array('all'));
//        return array('mod' => true);
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

    function instance_allow_config() {
        return true;
    }

    function get_content() {
        global $URL, $PAGE, $COURSE;
//
        if ($this->content !== null) {
            return $this->content;
        }


        $id = $_GET['id'];
//        $url = new moodle_url('/blocks/eva_form_library/view.php', array('id' => $id));
//        redirect($url);

//        ======================================================================================

        $arraypath = explode('/', $PAGE->docspath);

        $fildsbarema = [];
        foreach ($_POST as $key => $value) {
            $fildsbarema[$key] = $value;
        }


        $fildsinputs = [];
        $modelo = new \stdClass();
        foreach ($_POST as $key => $value) {
            $fildsinputs[$key] = $value;
            $modelo->$key = $value;
        }


        if ($arraypath[2] === 'view') {
            if ($modelo->submitbutton == "Enviar") {
                $return = new moodle_url('/blocks/eva_form_library/view.php');
                solicitacao_create_library($modelo, $return);
            }else if($modelo->cancel == "Cancelar"){
                $return = new moodle_url('/blocks/eva_form_library/view.php');
                redirect($return);
            }
        }

        $avaliacao = new \block_eva_form_library\output\formlib($this->config, $this->context);
        $renderer = $this->page->get_renderer('block_eva_form_library');
        $this->content          = new stdClass;
        $this->content->text = $renderer->render($avaliacao);

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