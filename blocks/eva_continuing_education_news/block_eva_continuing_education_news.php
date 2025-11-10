<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
require_once($CFG->dirroot. '/theme/evagu/ccn/block_handler/ccn_block_handler.php');
require_once($CFG->dirroot. '/theme/evagu/ccn/blog_handler/ccn_blog_handler.php');

class block_eva_continuing_education_news extends block_base {

    /**
     * Start block instance.
     */
    function init() {
        $this->title = get_string('pluginname', 'block_eva_continuing_education_news');
    }

    /**
     * The block is usable in all pages
     */
     function applicable_formats() {
       $ccnBlockHandler = new ccnBlockHandler();
       return $ccnBlockHandler->ccnGetBlockApplicability(array('all'));
     }


    /**
     * Customize the block title dynamically.
     */

    function specialization() {
        global $CFG, $DB;

        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/specialization.php');

        if (empty($this->config)) {
            $this->config = new \stdClass();
            $this->config->title = 'Atualidades';
       }
   }

    /**
     * The block can be used repeatedly in a page.
     */
    function instance_allow_multiple() {
        return true;
    }

    /**
     * Build the block content.
     */
    function get_content() {
        global $CFG, $PAGE;

        require_once($CFG->libdir . '/filelib.php');

        $PAGE->requires->css(new moodle_url($CFG->wwwroot . '/blocks/eva_continuing_education_news/style.css'));

        if ($this->content !== NULL) {
            return $this->content;
        }

        if (!empty($this->config) && is_object($this->config)) {
            $data = $this->config;
            $data->slidesnumber = is_numeric($data->slidesnumber) ? (int)$data->slidesnumber : 0;
        } else {
            $data = new stdClass();
            $data->slidesnumber = 0;
        }

        $text = '';


        $ccnBlogHandler = new ccnBlogHandler();

        foreach($this->config->posts as $post){
            $ccnGetPostDetails = $ccnBlogHandler->ccnGetPostDetails($post);
            print_object($ccnGetPostDetails);
            $text .= '
            <div class="item">
                <a href="'.format_text($data->$slide_url, FORMAT_HTML, array('filter' => true)).'">
                    <div class="blog_post">
                        <div style="height: 300px; max-height: 200px">
                            <img class="img-fluid w100" style="height: 200px" src="' . moodle_url::make_file_url("$CFG->wwwroot/pluginfile.php", "/{$this->context->id}/block_eva_continuing_education_news/slides/" . $i . '/' . $mainfile) . '" alt="">';

            $text .='
                        </div>
                        <div>
                            <h4 style="font-size: 30px; color: #000; text-shadow: 1px 1px 7px #aeaeae; margin: 10px 0px 0px 0px ;">'.format_text($data->$slide_title, FORMAT_HTML, array('filter' => true)).'</h4>
                            <h4 style="color: #000 ;margin: 0px auto ;">'.format_text($data->$slide_subtitle, FORMAT_HTML, array('filter' => true)).'</h4>';
                            if($PAGE->theme->settings->blog_post_date != 1){
                                $text .='<h4 style="color: #000 ;margin: 0px auto ;">'.userdate($data->$slide_date, '%d %B', 0).'</h4>';
                            }
                        $text .= '</div>
                    </div>
                </a>
            </div>';
        }

        if ($data->slidesnumber > 0) {
            $text = '

		        <div class="container">
                    <div class="row">
                        <div>
                            <div class="main-title text-center" style="margin-bottom: 0px ;">
                                <h1 class="mt0">'.$data->title.'</h1>
                                <p>'.$data->subtitle.'</p>
                            </div>
                        </div>
                    </div>
                </div>

            <div class="row">&nbsp;</div>
            <section class="blog_post_container" style="background-color: #f2f4f8;padding-top: 50px ;padding-bottom: 20px ;">
		        <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <h2 style="color: #000 ; font-size: 20px;">'.$data->slide_category.'</h2>
                            <br>
                        </div>
                    </div>';

            $text .='<div class="row">
				        <div class="col-lg-12">
					        <div class="feature_post_slider">';

            $fs = get_file_storage();
            for ($i = 1; $i <= $data->slidesnumber; $i++) {
                $sliderimage = 'file_slide' . $i;
                $slide_title = 'slide_title' . $i;
                $slide_subtitle = 'slide_subtitle' . $i;
                $slide_date = 'slide_date' . $i;
                $slide_url = 'slide_url' . $i;

                if (!empty($data->$sliderimage)) {
                    $files = $fs->get_area_files($this->context->id, 'block_eva_continuing_education_news', 'slides', $i, 'sortorder DESC, id ASC', false, 0, 0, 1);

                    if (count($files) >= 1) {
                        $mainfile = reset($files);
                        $mainfile = $mainfile->get_filename();
                    } else {
                        continue;
                    }

                    $href     = $data->$slide_url;
                    $src      = moodle_url::make_file_url("$CFG->wwwroot/pluginfile.php", "/{$this->context->id}/block_eva_continuing_education_news/slides/" . $i . '/' . $mainfile);
                    $userdate = userdate($data->$slide_date, '%d %B', 0);
                    $h5       = format_text($data->$slide_subtitle, FORMAT_HTML, array('filter' => true));
                    $h4       = format_text($data->$slide_title, FORMAT_HTML, array('filter' => true));

                    $text .= '
                    <div class="item">
                        <div class="gallery_overlay" data-ccn-c="color_overlay" data-ccn-co="ccnBg" data-ccn-cv="">
                            <a href="'.$href.'" class="popup-img popup-youtube home_post_overlay_icon bgc-theme8">
                                <div class="video_popup_btn"><span class="flaticon-play-button-1"></span></div>
                            </a>
                            <div class="blog_post">
                                <div style="height: 300px; max-height: 200px">
                                    <img class="img-fluid w100" style="height: 200px" src="'.$src.'" alt="">
                                </div>
                                <div style="margin-top: 5px;">
                                    <h4 style="font-weight: 600;text-shadow: none;">'.$h4.'</h4>
                                    <h4 style="color: #7c8b94 ;margin: 0px auto ;text-shadow: none;">'.$h5.'</h4>';
                                    if($PAGE->theme->settings->blog_post_date != 1){
                                        $text .='<h4 style="color: #7c8b94 ;margin: 0px auto ;text-shadow: none;">'.userdate($data->$slide_date, '%d/%m/%Y', 0).'</h4>';
                                    }
                                $text .= '
                                </div>
                            </div>
                        </div>
                    </div>';
                }
            }

            $text .= '
                            </div>
          				</div>
          			</div>
          		</div>
          	</section>';
        }
        $text .= '
        <script>
        $(document).ready(function(){
           $("#ccn-main-region").addClass("removeCcnMainStyle");
           $("#ccn-main").remove();
        });
        </script>
        ';

        $this->content = new stdClass;
        $this->content->footer = '';
        $this->content->text = $text;

        return $this->content;

    }


    /**
     * Serialize and store config data
     */
    function instance_config_save($data, $nolongerused = false) {
        global $CFG;

        $filemanageroptions = array('maxbytes'      => $CFG->maxbytes,
                                    'subdirs'       => 0,
                                    'maxfiles'      => 1,
                                    'accepted_types' => array('.jpg', '.png', '.gif'));

        for($i = 1; $i <= $data->slidesnumber; $i++) {
            $field = 'file_slide' . $i;
            if (!isset($data->$field)) {
                continue;
            }

            file_save_draft_area_files($data->$field, $this->context->id, 'block_eva_continuing_education_news', 'slides', $i, $filemanageroptions);
        }

        parent::instance_config_save($data, $nolongerused);
    }

    /**
     * When a block instance is deleted.
     */
    function instance_delete() {
        global $DB;
        $fs = get_file_storage();
        $fs->delete_area_files($this->context->id, 'block_eva_continuing_education_news');
        return true;
    }

    /**
     * Copy any block-specific data when copying to a new block instance.
     * @param int $fromid the id number of the block instance to copy from
     * @return boolean
     */
    public function instance_copy($fromid) {
        global $CFG;

        $fromcontext = context_block::instance($fromid);
        $fs = get_file_storage();

        if (!empty($this->config) && is_object($this->config)) {
            $data = $this->config;
            $data->slidesnumber = is_numeric($data->slidesnumber) ? (int)$data->slidesnumber : 0;
        } else {
            $data = new stdClass();
            $data->slidesnumber = 0;
        }

        $filemanageroptions = array('maxbytes'      => $CFG->maxbytes,
                                    'subdirs'       => 0,
                                    'maxfiles'      => 1,
                                    'accepted_types' => array('.jpg', '.png', '.gif'));

        for($i = 1; $i <= $data->slidesnumber; $i++) {
            $field = 'file_slide' . $i;
            if (!isset($data->$field)) {
                continue;
            }

            // This extra check if file area is empty adds one query if it is not empty but saves several if it is.
            if (!$fs->is_area_empty($fromcontext->id, 'block_eva_continuing_education_news', 'slides', $i, false)) {
                $draftitemid = 0;
                file_prepare_draft_area($draftitemid, $fromcontext->id, 'block_eva_continuing_education_news', 'slides', $i, $filemanageroptions);
                file_save_draft_area_files($draftitemid, $this->context->id, 'block_eva_continuing_education_news', 'slides', $i, $filemanageroptions);
            }
        }

        return true;
    }

    /**
     * The block should only be dockable when the title of the block is not empty
     * and when parent allows docking.
     *
     * @return bool
     */
    public function instance_can_be_docked() {
        return (!empty($this->config->title) && parent::instance_can_be_docked());
    }
    public function html_attributes() {
      global $CFG;
      $attributes = parent::html_attributes();
      include($CFG->dirroot . '/theme/evagu/ccn/block_handler/attributes.php');
      return $attributes;
    }

}
