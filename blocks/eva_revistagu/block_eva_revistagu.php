<?php
global $CFG;
require_once($CFG->dirroot. '/theme/evagu/ccn/block_handler/ccn_block_handler.php');
class block_eva_revistagu extends block_base {

    public function init() {
        $this->title = get_string('pluginname', 'block_eva_revistagu');
    }
    function has_config() {
        return true;
    }
    function instance_allow_multiple() {
        return true;
    }
    function applicable_formats() {
        $ccnBlockHandler = new ccnBlockHandler();
        return $ccnBlockHandler->ccnGetBlockApplicability(array('all'));
    }
    function specialization() {
        global $CFG, $DB;

        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/specialization.php');
       
        if (empty($this->config)) {
            $this->config = new \stdClass();
       }
   }

    public function get_content(){
        global $CFG, $PAGE, $DB, $USER;

        require_once($CFG->libdir . '/filelib.php');
        $PAGE->requires->css(new moodle_url($CFG->wwwroot . '/blocks/eva_revistagu/style.css'));
        
        if ($this->content !== NULL) {
            return $this->content;
        }

        if (!empty($this->config) && is_object($this->config)) {
            $data = $this->config;
        } else {
            $data = new stdClass();
        }
        
        if (!empty($data->image)) {
            $fs = get_file_storage();
            $files = $fs->get_area_files($this->context->id, 'block_eva_revistagu', 'content');
            
            foreach ($files as $file) {
                $filename = $file->get_filename();
                if ($filename <> '.') {
                    $url = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), null, $file->get_filepath(), $filename);
                }
            }
        }
        
        $text = '';
        $text .= '
        <div class="container-fluid">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <section class="u-align-center u-clearfix u-section-1" id="carousel_e006" style="padding-bottom: 0px !important;">
                            <div class="u-clearfix u-sheet u-sheet-1">
                                <div class="u-clearfix u-expanded-width u-layout-wrap u-layout-wrap-1">
                                    <div class="u-gutter-0 u-layout">
                                        <div class="row">
                                            <div class="col-sm-12 col-xs-12 col-lg-6 col-xl-6 col-md-6">
                                                <div class="u-layout-col">
                                                    <div class="u-size-40">
                                                        <div class="row">
                                                            <div class="u-container-style u-layout-cell u-size-60 u-layout-cell-1">
                                                                <div class="u-container-layout u-container-layout-1">
                                                                    <h2 class="u-align-left u-custom-font u-text u-text-palette-2-base u-text-1";">
                                                                        '.$data->title.'
                                                                    </h2>
                                                                    <p class="u-align-justify u-text u-text-2";"> 
                                                                        '.$data->editor['text'].'.<br>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-xs-12 col-lg-6 col-xl-6 col-md-6" style="position: relative">
                                                <div class="u-layout-col">
                                                    <div class="u-size-40">
                                                        <div class="row">
                                                            <div class="u-container-style u-layout-cell u-size-60 u-layout-cell-1">
                                                                <div class="u-container-layout u-container-layout-1" style="padding: 0px !important;">
                                                                    <div class="u-size-28-lg u-size-28-xl u-size-29-sm u-size-29-xs u-size-60-md">
                                                                        <div class="u-layout-col postitionBox">
                                                                            <div class="u-align-center u-container-style u-image u-layout-cell u-size-60 u-image-1">
                                                                                <img style="box-shadow: 0px 5px 20px 0 rgb(0 0 0 / 40%);" src="'.$url.'">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section> 
                        <section>
                            <div class="row">
                                <div class="col-sm-12 col-xs-12 col-lg-6 col-xl-6 col-md-6">
                                    <div class="row">
                                        <div class="col-sm-12 col-xs-12 col-lg-4 col-xl-4 col-md-4 u-align-center">
                                            <button class="btnBola" onclick="window.location.href = \''.$data->slide_url1.'\'">
                                                <i class="btnIcon '.$data->icon1.'"></i>
                                            </button>
                                            <p class="btnText u-align-center">'.$data->icon_title1.'</p>
                                        </div>
                                        <div class="col-sm-12 col-xs-12 col-lg-4 col-xl-4 col-md-4 u-align-center">
                                            <button class="btnBola" onclick="window.location.href = \''.$data->slide_url2.'\'">
                                                <i class="btnIcon '.$data->icon2.'"></i>
                                            </button>
                                            <p class="btnText u-align-center">'.$data->icon_title2.'</p>
                                        </div>
                                        <div class="col-sm-12 col-xs-12 col-lg-4 col-xl-4 col-md-4 u-align-center">
                                            <button class="btnBola" onclick="window.location.href = \''.$data->slide_url3.'\'">
                                                <i class="btnIcon '.$data->icon3.'"></i>
                                            </button>
                                            <p class="btnText u-align-center">'.$data->icon_title3.'</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
        ';

        $this->content = new stdClass;
        $this->content->footer = '';
        $this->content->text = $text;

        return $this->content;
    }    
    public function html_attributes() {
        global $CFG;
        $attributes = parent::html_attributes();
        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/attributes.php');
        return $attributes;
    }
}