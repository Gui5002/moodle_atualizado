<?php
global $CFG;
require_once($CFG->dirroot. '/theme/evagu/ccn/block_handler/ccn_block_handler.php');
require_once($CFG->dirroot. '/theme/evagu/ccn/general_handler/ccnLazy.php');

class block_eva_abouteva_info extends block_base
{
    public function init(){
        $this->title = get_string('eva_abouteva_info', 'block_eva_abouteva_info');
    }

    public function specialization() {
        global $CFG, $DB;
        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/specialization.php');

        if (empty($this->config)) {
            $this->config = new \stdClass();
        }
    }

    public function get_content() {
        global $CFG, $DB, $PAGE;
        require_once($CFG->libdir . '/filelib.php');
        $PAGE->requires->css(new moodle_url($CFG->wwwroot . '/blocks/eva_abouteva_info/style_info.css'));
        
        if ($this->content !== null) {
            return $this->content;
        }
        
        if (!empty($this->config) && is_object($this->config)) {
            $data = $this->config;
        } else {
            $data = new stdClass();
        }

        $ccnLazy = new ccnLazy();

        $fs = get_file_storage();
        $files = $fs->get_area_files($this->context->id, 'block_eva_abouteva_info', 'content');

        foreach ($files as $file) {
            $filename = $file->get_filename();
            if ($filename <> '.') {
                $url = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), null, $file->get_filepath(), $filename);
                $this->content->image =  $url;
            }
        }

        $text = '';
        $text .= '
        <div class="container-fluid">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <section class="u-section-1-info" id="carousel_c4f5-info">
                            <div class="u-valign-middle-md-info u-valign-middle-sm-info u-valign-middle-xs-info">
                                <div class="u-palette-5-base-info u-shape-1-info" style="background: '.$data->color_retangle.'"></div>
                                <img class="u-image-info u-image-2-info" data-ccn="image-info" data-ccn-img="content-info" '.$ccnLazy->ccnLazyImage($this->content->image).'>
                                <div class="u-container-style-info u-custom-color-3-info u-group-info u-group-2-info" style="background: '.$data->color_text_background.'">
                                    <div class="u-container-layout-2-info">
                                        <div class="row" style="margin-top: 20px; margin-bottom: 20px;">
                                            <div class="col-md-12">
                                                <span class="text1-info" style="color: '.$data->all_text_color.';">'.$data->text1.'</span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <span class="u-align-center-info text2-info" style="color: '.$data->all_text_color.';">'.$data->text2.'</span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p class="text3-info" style="color: '.$data->all_text_color.';">
                                                '.$data->text3.'
                                                </p>
                                            </div>
                                        </div>
                                        <div class="row">&nbsp;</div>
                                        <div class="row">&nbsp;</div>
                                        <div class="row">
                                            <style>
                                                .u-btn-1-info:hover{
                                                    background: '.$data->button_color_hover.' !important;
                                                }
                                            </style>
                                            <div class="col-md-12">
                                                <a href="'.$data->btn_url.'" class="u-btn-1-info"  style="color: '.$data->button_text_color.'; background: '.$data->button_color.';">
                                                    '.$data->btn_text.'
                                                </a>
                                            </div>
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

    /**
     * Allow multiple instances in a single course?
     *
     * @return bool True if multiple instances are allowed, false otherwise.
     */
    public function instance_allow_multiple() {
        return true;
    }

    /**
     * Enables global configuration of the block in settings.php.
     *
     * @return bool True if the global configuration is enabled.
     */
    function has_config() {
        return true;
    }

    function applicable_formats() {
        $ccnBlockHandler = new ccnBlockHandler();
        return $ccnBlockHandler->ccnGetBlockApplicability(array('all'));
    }

    public function html_attributes() {
        global $CFG;
        $attributes = parent::html_attributes();
        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/attributes.php');
        return $attributes;
    }
}
