<?php
global $CFG;
require_once $CFG->dirroot . '/theme/evagu/ccn/block_handler/ccn_block_handler.php';

class block_eva_tstmnls_3 extends block_base
{
    public function init()
    {
        $this->title = get_string('pluginname', 'block_eva_tstmnls_3');
    }

    public function applicable_formats()
    {
        $ccnBlockHandler = new ccnBlockHandler();
        return $ccnBlockHandler->ccnGetBlockApplicability(array('all'));
    }

    public function specialization()
    {
        global $CFG, $DB;
        include $CFG->dirroot . '/theme/evagu/ccn/block_handler/specialization.php';
        if (empty($this->config)) {
            $this->config->title = 'What People Say';
            $this->config->subtitle = 'Texto complementar do titulo de forma reduzida modelo.';
            $this->config->style = '0';
            $this->config->autoplay = 'true';
            $this->config->speed = '2000';
            $this->config->loop = 'true';
            $this->config->slidesnumber = '5';
            $this->config->slide_title1 = 'Renata Neves';
            $this->config->slide_subtitle1 = 'Advogada';
            $this->config->slide_text1 = 'Customization is very easy with this theme. Clean and quality design and full support for any kind of request! Great theme!';
            $this->config->file_slide1 = $CFG->wwwroot . '/theme/evagu/images/ccnBgSm.png';
            $this->config->slide_title2 = 'Renata Neves';
            $this->config->slide_subtitle2 = 'Promotora';
            $this->config->slide_text2 = 'Customization is very easy with this theme. Clean and quality design and full support for any kind of request! Great theme!';
            $this->config->file_slide2 = $CFG->wwwroot . '/theme/evagu/images/ccnBgSm.png';
            $this->config->slide_title3 = 'Renata Neves';
            $this->config->slide_subtitle3 = 'Juiz';
            $this->config->slide_text3 = 'Customization is very easy with this theme. Clean and quality design and full support for any kind of request! Great theme!';
            $this->config->file_slide3 = $CFG->wwwroot . '/theme/evagu/images/ccnBgSm.png';
            $this->config->slide_title4 = 'Renata Neves';
            $this->config->slide_subtitle4 = 'Estudante Direito';
            $this->config->slide_text4 = 'Customization is very easy with this theme. Clean and quality design and full support for any kind of request! Great theme!';
            $this->config->file_slide4 = $CFG->wwwroot . '/theme/evagu/images/ccnBgSm.png';
            $this->config->slide_title5 = 'Renata Neves';
            $this->config->slide_subtitle5 = 'Desenbargadora';
            $this->config->slide_text5 = 'Customization is very easy with this theme. Clean and quality design and full support for any kind of request! Great theme!';
            $this->config->file_slide5 = $CFG->wwwroot . '/theme/evagu/images/ccnBgSm.png';
            $this->config->color_bg = '#fff8ef';
            $this->config->color_title = '#0a0a0a';
            $this->config->color_subtitle = '#6f7074';
        }
    }

    public function instance_allow_multiple()
    {
        return true;
    }

    public function get_content()
    {
        global $CFG, $PAGE;
        require_once $CFG->libdir . '/filelib.php';
        if ($this->content !== null) {
            return $this->content;
        }
        $this->content = new stdClass;
        if (!empty($this->config->title)) {
            $this->content->title = $this->config->title;
        } else {
            $this->content->subtitle = 'What People Say';
        }
        if (!empty($this->config->subtitle)) {
            $this->content->subtitle = $this->config->subtitle;
        } else {
            $this->content->subtitle = 'Texto complementar do titulo de forma reduzida modelo.';
        }
        if (!empty($this->config->autoplay)) {
            $this->content->autoplay = $this->config->autoplay;
        } else {
            $this->content->autoplay = 'false';
        }
        if (!empty($this->config->loop)) {
            $this->content->loop = $this->config->loop;
        } else {
            $this->content->loop = 'true';
        }
        if (!empty($this->config->speed)) {
            $this->content->speed = $this->config->speed;
        } else {
            $this->content->speed = '2000';
        }
        if (!empty($this->config->color_bg)) {
            $this->content->color_bg = $this->config->color_bg;
        } else {
            $this->content->color_bg = '#fff8ef';
        }
        if (!empty($this->config->color_title)) {
            $this->content->color_title = $this->config->color_title;
        } else {
            $this->content->color_title = '#0a0a0a';
        }
        if (!empty($this->config->color_subtitle)) {
            $this->content->color_subtitle = $this->config->color_subtitle;
        } else {
            $this->content->color_subtitle = '#6f7074';
        }
        if (!empty($this->config) && is_object($this->config)) {
            $data = $this->config;
            $data->slidesnumber = is_numeric($data->slidesnumber) ? (int) $data->slidesnumber : 5;
        } else {
            $data = new stdClass();
            $data->slidesnumber = '5';
        }
        $text = '';
        if ($data->slidesnumber > 0) {
            $text = "\t<section
                        class=\"bgc-floral-white pb80 pt90\"
                        data-ccn-c=\"color_bg\"
                        data-ccn-co=\"bg\"
                        data-ccn-cv=\"" . $this->content->color_bg . "\"
                      >
\t\t                    <div class=\"container-fluid\">
                    \t\t\t<div class=\"main-title mb70\">
                    \t\t\t\t<div class=\"row\">
                    \t\t\t\t\t<div class=col-lg-10 offset-lg-1 text-center\">
                                        <div class=\"main-title text-center\">
                    \t\t\t\t\t\t<h1
                                  class=\"mt0\"
                                  data-ccn=\"title\"
                                  data-ccn-c=\"color_title\"
                                  data-ccn-cv=\"" . $this->content->color_title . '"
                                >' . format_text($this->content->title, FORMAT_HTML, array('filter' => true)) . "</h1>
                    \t\t\t\t\t\t<p
                                  data-ccn=\"subtitle\"
                                  data-ccn-c=\"color_subtitle\"
                                  data-ccn-cv=\"" . $this->content->color_subtitle . '">' . format_text($this->content->subtitle, FORMAT_HTML, array('filter' => true)) . "</p>
                    \t\t\t\t\t</div>
                    \t\t\t\t</div>
                    \t\t\t</div>
                      \t\t\t<div class=\"row\">
                      \t\t\t\t<div class=\"col-lg-12\">
\t\t\t\t\t                       <div class=\"testimonial_slider_home2 home12 testimonial_slider_home2-" . $this->instance->id . '"">';
            $fs = get_file_storage();
            for ($i = 1; $i <= $data->slidesnumber; $i++) {
                $sliderimage = 'file_slide' . $i;
                $slide_title = 'slide_title' . $i;
                $slide_subtitle = 'slide_subtitle' . $i;
                $slide_text = 'slide_text' . $i;
                $files = $fs->get_area_files($this->context->id, 'block_eva_tstmnls_3', 'slides', $i, 'sortorder DESC, id ASC', false, 0, 0, 1);
                if (!empty($data->$sliderimage) && count($files) >= 1) {
                    $mainfile = reset($files);
                    $mainfile = $mainfile->get_filename();
                    $data->$sliderimage = moodle_url::make_file_url("$CFG->wwwroot/pluginfile.php", "/{$this->context->id}/block_eva_tstmnls_3/slides/" . $i . '/' . $mainfile);
                } else {
                    $data->$sliderimage = $CFG->wwwroot . '/theme/evagu/images/ccnBgSm.png';
                }
                $text .= "
                    <div class=\"item\">
        \t\t\t\t\t\t\t<div class=\"testimonial_item home2 style2\">
        \t\t\t\t\t\t\t\t<div class=\"details bgc-white\">
        \t\t\t\t\t\t\t\t\t<div class=\"icon\"><span class=\"fa fa-quote-left\"></span></div>
        \t\t\t\t\t\t\t\t\t<p data-ccn=\"slide_text" . $i . '">' . format_text($data->$slide_text, FORMAT_HTML, array('filter' => true)) . "</p>
        \t\t\t\t\t\t\t\t</div>
        \t\t\t\t\t\t\t\t<div class=\"thumb\">
        \t\t\t\t\t\t\t\t\t<img class=\"img-fluid rounded-circle\" src=\"" . $data->$sliderimage . "\" alt=\"\">
        \t\t\t\t\t\t\t\t\t<div data-ccn=\"slide_title" . $i . '" class="title">' . format_text($data->$slide_title, FORMAT_HTML, array('filter' => true)) . "</div>
        \t\t\t\t\t\t\t\t\t<div data-ccn=\"slide_subtitle" . $i . '" class="subtitle">' . format_text($data->$slide_subtitle, FORMAT_HTML, array('filter' => true)) . "</div>
        \t\t\t\t\t\t\t\t</div>
        \t\t\t\t\t\t\t</div>
        \t\t\t\t\t\t</div>";
            }
        }
        $text .= "
            </div>
  \t\t\t\t</div>
  \t\t\t</div>
  \t\t</div>
  \t</section>
  <script type=\"text/javascript\">
  (function(\$){
      if(\$(\".testimonial_slider_home2-" . $this->instance->id . '").length){
          $(".testimonial_slider_home2-' . $this->instance->id . '").owlCarousel({
              center:true,
              loop:' . $this->content->loop . ',
              margin:15,
              dots:true,
              nav:false,
              rtl:false,
              autoplayHoverPause:false,
              autoplay: true,
              singleItem: true,
              smartSpeed: ' . $this->content->speed . ',
              navText: [
                \'<i class="flaticon-left-arrow"></i>\',
                \'<i class="flaticon-right-arrow-1"></i>\'
              ],
              responsive: {
                  0: {
                      items: 1,
                      center: false
                  },
                  480:{
                      items:1,
                      center: false
                  },
                  520:{
                      items:1,
                      center: false
                  },
                  600: {
                      items: 1,
                      center: false
                  },
                  768: {
                      items: 2
                  },
                  992: {
                      items: 2
                  },
                  1200: {
                      items: 3
                  },
                  1366: {
                      items: 3
                  },
                  1400: {
                      items: 3
                  }
              }
          })
      }
  }(jQuery));
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
    public function instance_config_save($data, $nolongerused = false)
    {
        global $CFG;
        $filemanageroptions = array('maxbytes' => $CFG->maxbytes,
            'subdirs' => 0,
            'maxfiles' => 1,
            'accepted_types' => array('.jpg', '.png', '.gif'));
        for ($i = 1; $i <= $data->slidesnumber; $i++) {
            $field = 'file_slide' . $i;
            if (!isset($data->$field)) {
                continue;
            }
            file_save_draft_area_files($data->$field, $this->context->id, 'block_eva_tstmnls_3', 'slides', $i, $filemanageroptions);
        }
        parent::instance_config_save($data, $nolongerused);
    }

    /**
     * When a block instance is deleted.
     */
    public function instance_delete()
    {
        global $DB;
        $fs = get_file_storage();
        $fs->delete_area_files($this->context->id, 'block_eva_tstmnls_3');
        return true;
    }

    /**
     * Copy any block-specific data when copying to a new block instance.
     * @param int $fromid the id number of the block instance to copy from
     * @return boolean
     */
    public function instance_copy($fromid)
    {
        global $CFG;
        $fromcontext = context_block::instance($fromid);
        $fs = get_file_storage();
        if (!empty($this->config) && is_object($this->config)) {
            $data = $this->config;
            $data->slidesnumber = is_numeric($data->slidesnumber) ? (int) $data->slidesnumber : 0;
        } else {
            $data = new stdClass();
            $data->slidesnumber = 0;
        }
        $filemanageroptions = array('maxbytes' => $CFG->maxbytes,
            'subdirs' => 0,
            'maxfiles' => 1,
            'accepted_types' => array('.jpg', '.png', '.gif'));
        for ($i = 1; $i <= $data->slidesnumber; $i++) {
            $field = 'file_slide' . $i;
            if (!isset($data->$field)) {
                continue;
            }
            // This extra check if file area is empty adds one query if it is not empty but saves several if it is.
            if (!$fs->is_area_empty($fromcontext->id, 'block_eva_tstmnls_3', 'slides', $i, false)) {
                $draftitemid = 0;
                file_prepare_draft_area($draftitemid, $fromcontext->id, 'block_eva_tstmnls_3', 'slides', $i, $filemanageroptions);
                file_save_draft_area_files($draftitemid, $this->context->id, 'block_eva_tstmnls_3', 'slides', $i, $filemanageroptions);
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
    public function instance_can_be_docked()
    {
        return (!empty($this->config->title) && parent::instance_can_be_docked());
    }

    public function html_attributes()
    {
        global $CFG;
        $attributes = parent::html_attributes();
        include ($CFG->dirroot . '/theme/evagu/ccn/block_handler/attributes.php');
        return $attributes;
    }
}
