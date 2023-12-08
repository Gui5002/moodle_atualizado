<?php
require_once($CFG->dirroot . '/theme/evagu/ccn/block_handler/ccn_block_handler.php');

class block_eva_banner_slider extends block_base
{
  function init()
  {
    $this->title = get_string('pluginname', 'block_eva_banner_slider');
  }
  function applicable_formats()
  {
    $ccnBlockHandler = new ccnBlockHandler();
    return $ccnBlockHandler->ccnGetBlockApplicability(array('all'));
  }
  function specialization()
  {
    global $CFG, $DB;
    include($CFG->dirroot . '/theme/evagu/ccn/block_handler/specialization.php');
    if (empty($this->config)) {
      $this->config = new \stdClass();
      $this->config->slidesnumber = '3';
      $this->config->slide_title1 = 'Recursos e informações da sala de estudos1';
      $this->config->slide_subtitle1 = 'A tecnologia está trazendo uma enorme evolução.';
      $this->config->slide_btn_text1 = 'Pronto para começar?';
      $this->config->slide_btn_url1 = '#';
      $this->config->slide_title2 = 'Recursos e informações da sala de estudos2';
      $this->config->slide_subtitle2 = 'A tecnologia está trazendo uma enorme evolução.';
      $this->config->slide_btn_text2 = 'Pronto para começar?';
      $this->config->slide_btn_url2 = '#';
      $this->config->slide_title3 = 'Recursos e informações da sala de estudos3';
      $this->config->slide_subtitle3 = 'A tecnologia está trazendo uma enorme evolução.';
      $this->config->slide_btn_text3 = 'Pronto para começar?';
      $this->config->slide_btn_url3 = '#';
      $this->config->prev_1 = '';
      $this->config->prev_2 = '';
      $this->config->next_1 = '';
      $this->config->next_2 = '';
      $this->config->arrow_style = 0;
      include($CFG->dirroot . '/theme/evagu/ccn/block_handler/specialization/specialization_ccn_carousel.php');
    }
  }
  function instance_allow_multiple()
  {
    return true;
  }

  function get_content()
  {
    global $CFG, $PAGE;
    require_once($CFG->libdir . '/filelib.php');

    if ($this->content !== NULL) {
      return $this->content;
    }

    if (!empty($this->config) && is_object($this->config)) {
      $data = $this->config;
      $data->slidesnumber = is_numeric($data->slidesnumber) ? (int)$data->slidesnumber : 3;
      if ($data->style == 1) {
        $slidersize = 'slide slide-one home6';
      } else {
        $slidersize = 'slide slide-one sh2';
      }
    } else {
      $data = new \stdClass();
      $data->slidesnumber = '3';
    }

    $this->content = new \stdClass();
    if (!empty($this->config->prev_1)) {
      $this->content->prev_1 = $this->config->prev_1;
    } else {
      $this->content->prev_1 = '';
    }
    if (!empty($this->config->prev_2)) {
      $this->content->prev_2 = $this->config->prev_2;
    } else {
      $this->content->prev_2 = '';
    }
    if (!empty($this->config->next_1)) {
      $this->content->next_1 = $this->config->next_1;
    } else {
      $this->content->next_1 = '';
    }
    if (!empty($this->config->next_2)) {
      $this->content->next_2 = $this->config->next_2;
    } else {
      $this->content->next_2 = '';
    }
    if (!empty($this->config->prev)) {
      $this->content->prev = $this->config->prev;
    } else {
      $this->content->prev = '';
    }
    if (!empty($this->config->next)) {
      $this->content->next = $this->config->next;
    } else {
      $this->content->next = '';
    }
    if (!empty($this->config->arrow_style)) {
      $this->content->arrow_style = $this->config->arrow_style;
    } else {
      $this->content->arrow_style = '0';
    }
    include($CFG->dirroot . '/theme/evagu/ccn/block_handler/config/config_ccn_carousel.php');

    $text = '';
    $bannerstyle = '';
    if ($data->slidesnumber > 1) {
      $bannerstyle .= 'banner-style-one--multiple';
    } else {
      $bannerstyle .= 'banner-style-one--single';
    }

    $PAGE->requires->css(new moodle_url($CFG->wwwroot . '/blocks/eva_banner_slider/style.css'));

    if ($data->slidesnumber > 0) {
      $text = '
    <div class="home2-slider">
    	<div class="container-fluid p0">
    		<div class="row" style="width: 100%; margin-left: 0px; margin-right: 0px;">
    			<div class="col-lg-12">
    				<div class="main-banner-wrapper">
    				  <div class="banner-style-one owl-theme owl-carousel ' . $bannerstyle . '" ' . $ccnConfigDataCarousel . '>';
      $fs = get_file_storage();
      for ($i = 1; $i <= $data->slidesnumber; $i++) {
        $sliderimage = 'file_slide' . $i;
        $slide_title = 'slide_title' . $i;
        $slide_subtitle = 'slide_subtitle' . $i;
        $slide_btn_url = 'slide_btn_url' . $i;
        $slide_btn_text = 'slide_btn_text' . $i;
        $slide_btn_target = 'slide_btn_target' . $i;
        if (!empty($data->$slide_btn_target)) {
          $slide_btn_target = $data->$slide_btn_target;
        } else {
          $slide_btn_target = '';
        }
        $files = $fs->get_area_files($this->context->id, 'block_eva_banner_slider', 'slides', $i, 'sortorder DESC, id ASC', false, 0, 0, 1);
        if (!empty($data->$sliderimage) && count($files) >= 1) {
          $mainfile = reset($files);
          $mainfile = $mainfile->get_filename();
          $data->$sliderimage = moodle_url::make_file_url("$CFG->wwwroot/pluginfile.php", "/{$this->context->id}/block_eva_banner_slider/slides/" . $i . '/' . $mainfile);
        } else {
          $data->$sliderimage = $CFG->wwwroot . '/theme/evagu/images/home/1.jpg';
        }
        $text .= '
                <div class="' . $slidersize . '" data-ccn="file_slide' . $i . '" data-ccn-img="bg-img-url" style="background-image: url(' . $data->$sliderimage . ');width: auto !important;">
                 <div class="container">
                     <div class="row">
                         <div class="col-lg-12 text-center">';
        if (!empty($data->$slide_title)) {
          $text .= '<h3 class="banner-title2">' . $data->$slide_title . '</h3>';
        }
        if (!empty($data->$slide_subtitle)) {
          $text .= '<p class="banner-subtitle2">' . $data->$slide_subtitle . '</p>';
        }
        if (!empty($data->$slide_btn_url) && !empty($data->$slide_btn_text)) {
          $text .= '<div class="btn-block">
                                      <a target="' . $slide_btn_target . '" href="'
            . $data->$slide_btn_url . '" class="banner-btn2">'
            . $data->$slide_btn_text . '</a>
                                    </div>';
        }
        $text .= '</div>
                     </div>
                 </div>
             </div>';
      }
      $text .= '
            </div>';
      if ($this->content->arrow_style != '2' && $data->slidesnumber > 1) {
        $text .= '
            <div class="carousel-btn-block banner-carousel-btn">
              <span class="carousel-btn left-btn">
                <i class=""></i> ';
        if ($this->content->arrow_style != '1') {
          $text .= ' <span class="left">' . $this->content->prev_1 . ' <br> ' . $this->content->prev_2 . '</span>';
        } else {
          $text .= ' <span class="left">' . $this->content->prev . '</span>';
        }
        $text .= '
              </span>
                  <span class="carousel-btn right-btn">';
        if ($this->content->arrow_style != '1') {
          $text .= '<span class="right">' . $this->content->next_1 . ' <br> ' . $this->content->next_2 . '</span> <i class=""></i>';
        } else {
          $text .= '<span class="right">' . $this->content->next . '</span> <i class=""></i>';
        }
        $text .= '
                  </span>
              </div><!-- /.carousel-btn-block banner-carousel-btn -->';
      }
      $text .= '
					</div><!-- /.main-banner-wrapper -->
				</div>
			</div>
		</div>
	</div>';
    }

    $this->content = new stdClass;
    $this->content->footer = '';
    $this->content->text = $text;

    return $this->content;
  }
  function instance_config_save($data, $nolongerused = false)
  {
    global $CFG;

    $filemanageroptions = array(
      'maxbytes'      => $CFG->maxbytes,
      'subdirs'       => 0,
      'maxfiles'      => 1,
      'accepted_types' => array('.jpg', '.png', '.gif')
    );

    for ($i = 1; $i <= $data->slidesnumber; $i++) {
      $field = 'file_slide' . $i;
      if (!isset($data->$field)) {
        continue;
      }

      file_save_draft_area_files($data->$field, $this->context->id, 'block_eva_banner_slider', 'slides', $i, $filemanageroptions);
    }

    parent::instance_config_save($data, $nolongerused);
  }

  /**
   * When a block instance is deleted.
   */
  function instance_delete()
  {
    global $DB;
    $fs = get_file_storage();
    $fs->delete_area_files($this->context->id, 'block_eva_banner_slider');
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
      $data->slidesnumber = is_numeric($data->slidesnumber) ? (int)$data->slidesnumber : 0;
    } else {
      $data = new stdClass();
      $data->slidesnumber = 0;
    }

    $filemanageroptions = array(
      'maxbytes'      => $CFG->maxbytes,
      'subdirs'       => 0,
      'maxfiles'      => 1,
      'accepted_types' => array('.jpg', '.png', '.gif')
    );

    for ($i = 1; $i <= $data->slidesnumber; $i++) {
      $field = 'file_slide' . $i;
      if (!isset($data->$field)) {
        continue;
      }
        if (!$fs->is_area_empty($fromcontext->id, 'block_eva_banner_slider', 'slides', $i, false)) {
        $draftitemid = 0;
        file_prepare_draft_area($draftitemid, $fromcontext->id, 'block_eva_banner_slider', 'slides', $i, $filemanageroptions);
        file_save_draft_area_files($draftitemid, $this->context->id, 'block_eva_banner_slider', 'slides', $i, $filemanageroptions);
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
    include($CFG->dirroot . '/theme/evagu/ccn/block_handler/attributes.php');
    return $attributes;
  }
}
