<?php
require_once($CFG->dirroot . '/theme/evagu/ccn/block_handler/ccn_block_handler.php');
class block_eva_pills extends block_base
{
  /**
   * Start block instance.
   */
  function init()
  {
    $this->title = get_string('pluginname', 'block_eva_pills');
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
      $this->config->slidesnumber = '4';
      $this->config->slide_title1 = 'Program';
      $this->config->slide_title2 = 'Affordability';
      $this->config->slide_title3 = 'Certification';
      $this->config->slide_title4 = 'Academic Calendar';
      $this->config->slide_subtitle1 = 'Lorem Ipsum is simply of the printing and industry.';
      $this->config->slide_subtitle2 = 'Lorem Ipsum is simply of the printing and industry.';
      $this->config->slide_subtitle3 = 'Lorem Ipsum is simply of the printing and industry.';
      $this->config->slide_subtitle4 = 'Lorem Ipsum is simply of the printing and industry.';
      $this->config->slide_btn_text1 = 'Learn more';
      $this->config->slide_btn_text2 = 'Learn more';
      $this->config->slide_btn_text3 = 'Learn more';
      $this->config->slide_btn_text4 = 'Learn more';
      $this->config->slide_btn_link1 = '#';
      $this->config->slide_btn_link2 = '#';
      $this->config->slide_btn_link3 = '#';
      $this->config->slide_btn_link4 = '#';
      $this->config->file_slide1 = $CFG->wwwroot . '/theme/evagu/images/service/1.jpg';
      $this->config->file_slide2 = $CFG->wwwroot . '/theme/evagu/images/service/2.jpg';
      $this->config->file_slide3 = $CFG->wwwroot . '/theme/evagu/images/service/3.jpg';
      $this->config->file_slide4 = $CFG->wwwroot . '/theme/evagu/images/service/4.jpg';
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
      $data->slidesnumber = is_numeric($data->slidesnumber) ? (int)$data->slidesnumber : 4;
    } else {
      $data = new \stdClass();
      $data->slidesnumber = '4';
    }
    $text = '';
    if ($data->slidesnumber > 0) {
      $text = '			<section class="home6_about pt90 bgc-f9">
		<div class="container">
			<div class="row">';
      $fs = get_file_storage();
      for ($i = 1; $i <= $data->slidesnumber; $i++) {
        $sliderimage = 'file_slide' . $i;
        $slide_title = 'slide_title' . $i;
        $slide_subtitle = 'slide_subtitle' . $i;
        $slide_btn_link = 'slide_btn_link' . $i;
        $slide_btn_text = 'slide_btn_text' . $i;
        $button_target = 'button_target' . $i;
        if (!empty($data->$button_target)) {
          $button_target = $data->$button_target;
        } else {
          $button_target = '';
        }
        $files = $fs->get_area_files($this->context->id, 'block_eva_pills', 'slides', $i, 'sortorder DESC, id ASC', false, 0, 0, 1);
        if (!empty($data->$sliderimage) && count($files) >= 1) {
          $mainfile = reset($files);
          $mainfile = $mainfile->get_filename();
          $data->$sliderimage = moodle_url::make_file_url("$CFG->wwwroot/pluginfile.php", "/{$this->context->id}/block_eva_pills/slides/" . $i . '/' . $mainfile);
        } else {
          $data->$sliderimage = $CFG->wwwroot . '/theme/evagu/images/service/1.jpg';
        }
        $text .= '
<div class="col-sm-6 col-lg-6 col-xl-3">
  <div class="hvr_img_box_container">
<div class="hvr_img_box imgs" style="background-image: url(' . $data->$sliderimage . ');"></div>
<div class="overlay">
  <div class="details">
<h5 data-ccn="slide_title' . $i . '">' . format_text($data->$slide_title, FORMAT_HTML, array('filter' => true)) . '</h5>
<p data-ccn="slide_subtitle' . $i . '">' . format_text($data->$slide_subtitle, FORMAT_HTML, array('filter' => true)) . '</p>
<a target="' . $button_target . '" data-ccn="slide_btn_text' . $i . '" href="' . format_text($data->$slide_btn_link, FORMAT_HTML, array('filter' => true)) . '">' . format_text($data->$slide_btn_text, FORMAT_HTML, array('filter' => true)) . ' <span class="span flaticon-right-arrow-1"></span></a>
  </div>
</div>
  </div>
</div>';
      }
      $text .= '
			</div>
		</div>
	</section>';
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
      'maxbytes'  => $CFG->maxbytes,
      'subdirs'   => 0,
      'maxfiles'  => 1,
      'accepted_types' => array('.jpg', '.png', '.gif')
    );
    for ($i = 1; $i <= $data->slidesnumber; $i++) {
      $field = 'file_slide' . $i;
      if (!isset($data->$field)) {
        continue;
      }
      file_save_draft_area_files($data->$field, $this->context->id, 'block_eva_pills', 'slides', $i, $filemanageroptions);
    }
    parent::instance_config_save($data, $nolongerused);
  }
  function instance_delete()
  {
    global $DB;
    $fs = get_file_storage();
    $fs->delete_area_files($this->context->id, 'block_eva_pills');
    return true;
  }
  /**
   * @param int
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
      'maxbytes'  => $CFG->maxbytes,
      'subdirs'   => 0,
      'maxfiles'  => 1,
      'accepted_types' => array('.jpg', '.png', '.gif')
    );
    for ($i = 1; $i <= $data->slidesnumber; $i++) {
      $field = 'file_slide' . $i;
      if (!isset($data->$field)) {
        continue;
      }
      if (!$fs->is_area_empty($fromcontext->id, 'block_eva_pills', 'slides', $i, false)) {
        $draftitemid = 0;
        file_prepare_draft_area($draftitemid, $fromcontext->id, 'block_eva_pills', 'slides', $i, $filemanageroptions);
        file_save_draft_area_files($draftitemid, $this->context->id, 'block_eva_pills', 'slides', $i, $filemanageroptions);
      }
    }
    return true;
  }
  /**
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
