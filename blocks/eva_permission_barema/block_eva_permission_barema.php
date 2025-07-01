<?php

require_once($CFG->dirroot. '/theme/evagu/ccn/block_handler/ccn_block_handler.php');

require_once('classes/output/UserController.php');

class block_eva_permission_barema extends block_base {

    /**
     * Start block instance.
     */
    function init() {
        $this->title = get_string('pluginname', 'block_eva_permission_barema');
    }

    /**
     * The block is usable in all pages
     */
    function applicable_formats() {
        $ccnBlockHandler = new ccnBlockHandler();
        return $ccnBlockHandler->ccnGetBlockApplicability(array('all'));
        // return array('all' => true);
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
    /**
     * Allow the user to configure a block instance
     * @return bool Returns true
     */
    function instance_allow_config() {
        return true;
    }


    function get_content() {
        global $CFG, $PAGE, $DB, $USER;

        if ($this->content !== NULL) {
            return $this->content;
        }

        require_once($CFG->libdir . '/filelib.php');

        
        $arraypath = explode('/', $PAGE->docspath);

        $fildspermission = [];
        foreach ($_POST as $key => $value) {
            $fildspermission[$key] = $value;
        }
       
        
        if($arraypath[2] == "users"){
            $returnurlpermission = new moodle_url('/blocks/eva_permission_barema/users');
            $usercontroller = new \block_eva_permission_barema\output\UserController($_GET, $fildspermission, $returnurlpermission);
            if($arraypath[3] === "delete"){            
                $usercontroller->delete();
            }
            if($arraypath[3] === "update"){
                $usercontroller->update();
            }
            if($arraypath[3] === "add"){
                $usercontroller->adicionar();
            }
        }
        

        $permissionbarema = new \block_eva_permission_barema\output\permission_barema($this->config, $this->context);
        
        $renderer = $this->page->get_renderer('block_eva_permission_barema');
        $this->content = new stdClass;

        $this->content->text = $renderer->render($permissionbarema);;
        
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