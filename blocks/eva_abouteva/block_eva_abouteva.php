<?php
global $CFG;
require_once($CFG->dirroot. '/theme/evagu/ccn/block_handler/ccn_block_handler.php');
require_once($CFG->dirroot. '/theme/evagu/ccn/general_handler/ccnLazy.php');

class block_eva_abouteva extends block_base
{
    public function init(){
        $this->title = get_string('eva_abouteva', 'block_eva_abouteva');
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
        $PAGE->requires->css(new moodle_url($CFG->wwwroot . '/blocks/eva_abouteva/style.css'));
        
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
        $files = $fs->get_area_files($this->context->id, 'block_eva_abouteva', 'content');

        foreach ($files as $file) {
            $filename = $file->get_filename();
            if ($filename <> '.') {
                $url = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), null, $file->get_filepath(), $filename);
                $this->content->image =  $url;
            }
        }

        $text = '';
        $text .= '
        <div class="container-fluid" style="background: '.$data->color_background.'">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <section style="padding-bottom: 0px;" class="u-section-1" id="carousel_c4f5">
                            <div class="u-valign-middle-md u-valign-middle-sm u-valign-middle-xs">
                                <div style="background-image: url('.$CFG->wwwroot.'/blocks/eva_abouteva/img/texture.png);" class="u-container-style u-expanded-width-sm u-expanded-width-xs u-group u-image u-image-tiles u-image-1"></div>
                                <div class="u-palette-5-base u-shape-1" style="background: '.$data->color_retangle.'"></div>
                                <img class="u-image u-image-2" data-ccn="image" data-ccn-img="content" '.$ccnLazy->ccnLazyImage($this->content->image).'>
                                <div class="u-container-style u-custom-color-3 u-group u-group-2" style="background: '.$data->color_text_background.'">
                                    <div class="u-container-layout-2">
                                        <div class="row">
                                            <div class="col-md-12 u-align-center">
                                                <h2 class="u-subtitle" style="color: '.$data->title_color.'">'.$data->title.'</h2>
                                            </div>
                                        </div>
                                        <p class="u-align-justify u-text-2" style="color: '.$data->text_color.'">
                                            '.$data->about_html['text'].'
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
        ';
        $text .= '
        <script>
        $(document).ready(function(){
           $("#ccn-main-region").addClass("removeCcnMainStyle");
        });
        </script>
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
