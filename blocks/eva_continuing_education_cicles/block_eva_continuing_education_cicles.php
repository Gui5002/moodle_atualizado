<?php
global $CFG;
require_once $CFG->dirroot . '/theme/evagu/ccn/block_handler/ccn_block_handler.php';
require_once $CFG->dirroot . '/theme/evagu/ccn/general_handler/ccnLazy.php';
class block_eva_continuing_education_cicles extends block_base
{
    public function init()
    {
        $this->title = get_string('pluginname', 'block_eva_continuing_education_cicles');
    }
    public function specialization()
    {
        global $CFG, $DB;
        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/specialization.php');
        if (empty($this->config)) {
            $this->config = new \stdClass();
        }
    }
    public function get_content()
    {
        global $CFG, $DB, $PAGE;
        require_once($CFG->libdir . '/filelib.php');
        $PAGE->requires->css(new moodle_url($CFG->wwwroot . '/blocks/eva_continuing_education_cicles/style.css'));
        if ($this->content !== null) {
            return $this->content;
        }
        $ccnLazy = new ccnLazy();
        if (!empty($this->config) && is_object($this->config)) {
            $data = $this->config;
        } else {
            $data = new stdClass();
        }
        $fs = get_file_storage();
        $files = $fs->get_area_files($this->context->id, 'block_eva_continuing_education_cicles', 'content');
        foreach ($files as $file) {
            $filename = $file->get_filename();
            if ($filename <> '.') {
                $url = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), null, $file->get_filepath(), $filename);
                $this->content->image =  $url;
            }
        }
        $text = ' ';
        $text .= '
        <div class="container-fluid ">
            <div class="container">
                <div class="row">
<div class="col-lg-12">
             <div class="container">
            <div class="row">
            <div class="col-lg-10 offset-lg-1 text-center">
            <div class="main-title text-center">
            <h1 class="mt0">' . $data->title . '</h1>
            <p>' . $data->editor . '</p>
            </div>
            </div>
            </div>';
        $text .= '</div>
        </div>
                </div>
            </div>
        </div>
         <div class="row">&nbsp;</div>
        <div class="container-fluid ">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <style>';
        $loop = 5;
        $body_color_hover = 'body_color_hover';
        $text_color_hover = 'text_color_hover';
        for ($i = 1; $i < $loop; $i++) {
            $bch = $body_color_hover . $i;
            $tch = $text_color_hover . $i;
            $text .= '.icon_hvr_img_box.ccn-box[data-ccn-c=color' . $i . ']:hover {
                                background-color: ' . $data->$bch . ' !important;
                            }
                            .icon_hvr_img_box.ccn-box[data-ccn-c=color' . $i . ']:hover h5 {
                                color: ' . $data->$tch . ' !important;
                            }
                            .icon_hvr_img_box.ccn-box[data-ccn-c=color' . $i . ']:hover p {
                                color: ' . $data->$tch . ' !important;
                            }
                            .icon_hvr_img_box.ccn-box[data-ccn-c=color' . $i . ']:hover .icon h2 a {
                                color: ' . $data->$tch . ' !important;
                            }';
        }
        $text .= '</style>
                        <section class="u-clearfix u-white u-section-1 u-font-raleway sectionPadding" id="carousel_0af9">
                            <div class="u-clearfix u-sheet u-sheet-1">
                                <img class="imagePosition u-image u-image-contain u-image-default u-image-1" alt="" data-image-width="700" data-image-height="700" data-ccn="image" data-ccn-img="content" ' . $ccnLazy->ccnLazyImage($this->content->image) . '>
                                <section id="our-courses" class="sectionPadding cardSection our-courses pt90 pt650-992" data-ccn-c="color_bg" data-ccn-co="bg">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="row rowPosition">';
        $text .= '
                                                    <div class="icon_hvr_img_box ccn-box sbbg1 mr-2 mt-2 ml-2 mb-2" data-ccn-c="color1" data-ccn-co="bg" style="background-color: ' . $data->body_color1 . '; max-width: 270px !important; min-width: 270px !important; max-height: 270px !important; min-height: 270px !important;">
                                                        <div class="overlay">
                                                            <div class="ccn_icon_2 icon">
                                                                <h2 class="u-align-center u-text u-text-3">
                                                                    <a data-ccn="icon1" data-ccn-c="color_icon1" data-ccn-co="content" style="color: ' . $data->text_color1 . ';" class="u-active-none u-border-none u-btn u-button-link u-button-style u-hover-none u-none u-text-palette-1-base u-btn-1" href="' . $data->slide_url1 . '">
                                                                        ' . $data->card_title1 . '
                                                                    </a>
                                                                </h2>
                                                            </div>
                                                            <div>
                                                                <p class="u-p u-text u-text-grey-50 u-text-4" data-ccn="title1" data-ccn-c="color_title1" data-ccn-co="content" style="color: ' . $data->text_color1 . '; text-align: center !important; margin-top: -20px !important; line-height: 1.5rem; font-size: 16px;">
                                                                    ' . $data->card_subtitle1 . '
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    ';
        $text .= '
                                                    <div class="icon_hvr_img_box ccn-box sbbg1 mr-2 mt-2 ml-2 mb-2" data-ccn-c="color2" data-ccn-co="bg" style="background-color: ' . $data->body_color2 . '; max-width: 270px !important; min-width: 270px !important; max-height: 270px !important; min-height: 270px !important;">
                                                        <div class="overlay" style="padding: 20px 20px 20px !important;margin-top: 20px;">
                                                            <div class="ccn_icon_2 icon">
                                                                <h3 class="u-align-center u-text u-text-3">
                                                                    <a data-ccn="icon3" data-ccn-c="color_icon2" data-ccn-co="content" style="color: ' . $data->text_color2 . ';" class="u-active-none u-border-none u-btn u-button-link u-button-style u-hover-none u-none u-text-palette-1-base u-btn-1" href="' . $data->slide_url2 . '">
                                                                        ' . $data->card_title2 . '
                                                                    </a>
                                                                </h3>
                                                            </div>
                                                            <div>
                                                                <p class="u-p u-text u-text-grey-50 u-text-4" data-ccn="title2" data-ccn-c="color_title2" data-ccn-co="content" style="color: ' . $data->text_color2 . '; text-align: center !important;  margin-top: -20px !important; line-height: 1.5rem; font-size: 16px;">
                                                                    ' . $data->card_subtitle2 . '
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    ';
        $text .= '</div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <section id="our-courses" class="sectionPadding cardSection our-courses pt90 pt650-992" data-ccn-c="color_bg" data-ccn-co="bg">
                                    <div class="container">
                                        <div class="row ">
                                            <div class="col-lg-12">
                                                <div class="row rowPosition">';
        $text .= '
                                                    <div class="icon_hvr_img_box ccn-box sbbg1 mr-2 mt-2 ml-2 mb-2" data-ccn-c="color3" data-ccn-co="bg" style="background-color: ' . $data->body_color3 . '; max-width: 270px !important; min-width: 270px !important; max-height: 270px !important; min-height: 270px !important;">
                                                        <div class="overlay" style="padding: 20px 20px 20px !important;margin-top: 20px;">
                                                            <div class="ccn_icon_2 icon">
                                                                <h3 class="u-align-center u-text u-text-3">
                                                                    <a data-ccn="icon3" data-ccn-c="color_icon3" data-ccn-co="content" style="color: ' . $data->text_color3 . ';" class="u-active-none u-border-none u-btn u-button-link u-button-style u-hover-none u-none u-text-palette-1-base u-btn-1" href="' . $data->slide_url3 . '">
                                                                        ' . $data->card_title3 . '
                                                                    </a>
                                                                </h3>
                                                            </div>
                                                            <div>
                                                                <p class="u-p u-text u-text-grey-50 u-text-4" data-ccn="title3" data-ccn-c="color_title3" data-ccn-co="content" style="color: ' . $data->text_color3 . '; text-align: center !important;  margin-top: -20px !important; line-height: 1.5rem; font-size: 16px;">
                                                                    ' . $data->card_subtitle3 . '
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    ';
        $text .= '
                                                    <div class="icon_hvr_img_box ccn-box sbbg1 mr-2 mt-2 ml-2 mb-2" data-ccn-c="color4" data-ccn-co="bg" style="background-color: ' . $data->body_color4 . '; max-width: 270px !important; min-width: 270px !important; max-height: 270px !important; min-height: 270px !important;">
                                                        <div class="overlay" style="padding: 20px 20px 20px !important;margin-top: 20px;">
                                                            <div class="ccn_icon_2 icon">
                                                                <h3 class="u-align-center u-text u-text-3">
                                                                    <a data-ccn="icon4" data-ccn-c="color_icon4" data-ccn-co="content" style="color: ' . $data->text_color4 . ';" class="u-active-none u-border-none u-btn u-button-link u-button-style u-hover-none u-none u-text-palette-1-base u-btn-1" href="' . $data->slide_url4 . '">
                                                                        ' . $data->card_title4 . '
                                                                    </a>
                                                                </h3>
                                                            </div>
                                                            <div>
                                                                <p class="u-p u-text u-text-grey-50 u-text-4" data-ccn="title4" data-ccn-c="color_title4" data-ccn-co="content" style="color: ' . $data->text_color4 . '; text-align: center !important;  margin-top: -20px !important; line-height: 1.5rem; font-size: 16px;">
                                                                    ' . $data->card_subtitle4 . '
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    ';
        $text .= '</div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
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
    public function instance_allow_multiple()
    {
        return true;
    }
    /**
     * Enables global configuration of the block in settings.php.
     *
     * @return bool True if the global configuration is enabled.
     */
    public function has_config()
    {
        return true;
    }
    /**
     * Sets the applicable formats for the block.
     *
     * @return string[] Array of pages and permissions.
     */
    public function applicable_formats()
    {
        $ccnBlockHandler = new ccnBlockHandler();
        return $ccnBlockHandler->ccnGetBlockApplicability(array('all'));
    }
    public function html_attributes()
    {
        global $CFG;
        $attributes = parent::html_attributes();
        include $CFG->dirroot . '/theme/evagu/ccn/block_handler/attributes.php';
        return $attributes;
    }
}
