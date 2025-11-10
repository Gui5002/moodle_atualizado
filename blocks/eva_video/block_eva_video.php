<?php
global $CFG;
require_once $CFG->dirroot . '/theme/evagu/ccn/block_handler/ccn_block_handler.php';
require_once $CFG->dirroot . '/theme/evagu/ccn/general_handler/ccnLazy.php';
class block_eva_video extends block_base
{
  public function init()
  {
    $this->title = get_string('pluginname', 'block_eva_video');
  }
  public function specialization()
  {
    global $CFG;
    include $CFG->dirroot . '/theme/evagu/ccn/block_handler/specialization.php';
    if (empty($this->config)) {
      $this->config = new stdClass();
      $this->config->title = 'Guia de acesso à EVA';
      $this->config->subtitle = 'Video explicativo do passo a passo de como acessar ou se cadastrar.';
      $this->config->slidesnumber = '1';
      $this->config->video_url = 'https://www.youtube.com/watch?v=zZN7rfwg2E0';
      $this->config->color_bfbg = '#f9f9f9';
      $this->config->color_title = '#000';
      $this->config->color_subtitle = '#b2abab';
      $this->config->color_overlay = 'rgba(34, 34, 34, 0)';
    }
  }
  public function get_content()
  {
    global $CFG, $DB;
    require_once $CFG->libdir . '/filelib.php';
    if ($this->content !== null) {
      return $this->content;
    }
    $this->content = new stdClass();
    $this->content->title = !empty($this->config->title) ? $this->config->title : '';
    $this->content->subtitle = !empty($this->config->subtitle) ? $this->config->subtitle : '';
    $this->content->video_url = !empty($this->config->video_url) ? $this->config->video_url : '';
    $this->content->color_bfbg = !empty($this->config->color_bfbg) ? $this->config->color_bfbg : '#f9f9f9';
    $this->content->color_title = !empty($this->config->color_title) ? $this->config->color_title : '#0067da';
    $this->content->color_subtitle = !empty($this->config->color_subtitle) ? $this->config->color_subtitle : '#222222';
    $this->content->color_overlay = !empty($this->config->color_overlay) ? $this->config->color_overlay : 'rgb(34, 34, 34, .4)';
    $this->content->image = $CFG->wwwroot . '/theme/evagu/images/ccnBgMd.png';
    $ccnLazy = new ccnLazy();
    if (!empty($this->config) && is_object($this->config)) {
      $data = $this->config;
      $data->slidesnumber = is_numeric($data->slidesnumber) ? (int)$data->slidesnumber : 1;
    } else {
      $data = new stdClass();
      $data->slidesnumber = '1';
    }
    $fs = get_file_storage();
    $files = $fs->get_area_files($this->context->id, 'block_eva_video', 'content');
    foreach ($files as $file) {
      $filename = $file->get_filename();
      if ($filename != '.') {
        $url = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), null, $file->get_filepath(), $filename);
        $this->content->image = $url;
      }
    }
    $this->content->text = '
        <section
          class="about-us-home13 pb20 pt0"
          data-ccn-c="color_bfbg"
          data-ccn-co="ccnBfBg"
          data-ccn-cv="' . $this->content->color_bfbg . '">
          <div class="container">
            <div class="row">
              <div class="col-lg-10 offset-lg-1 text-center">
                <h1 style="color: ' . $this->content->color_title . '; margin-top: 40px;">' . $this->content->title . '</h1>
                <p style="color: ' . $this->content->color_subtitle . '; margin-bottom: 20px;">' . $this->content->subtitle . '</p>
                <div class="gallery_item home13">
                  <img class="img-fluid img-circle-rounded" alt="" data-ccn="image" data-ccn-img="content" ' . $ccnLazy->ccnLazyImage($this->content->image) . '>
                  <div
                    class="gallery_overlay"
                    data-ccn-c="color_overlay"
                    data-ccn-co="ccnBg"
                    data-ccn-cv="' . $this->content->color_overlay . '">
                    <a class="popup-img popup-youtube home_post_overlay_icon bgc-theme8" href="' . $this->content->video_url . '">
                      <div class="video_popup_btn"><span class="flaticon-play-button-1"></span></div>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>';
    return $this->content;
  }
  public function instance_allow_multiple()
  {
    return true;
  }
  public function has_config()
  {
    return true;
  }
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
?>