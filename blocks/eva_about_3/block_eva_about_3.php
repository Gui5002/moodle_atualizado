<?php
global $CFG;
require_once($CFG->dirroot. '/theme/evagu/ccn/block_handler/ccn_block_handler.php');
require_once($CFG->dirroot. '/theme/evagu/ccn/general_handler/ccnLazy.php');

class block_eva_about_3 extends block_base
{
    // Declare first
    public function init() {
        $this->title = get_string('eva_about_3', 'block_eva_about_3');
    }

    // Declare second
    public function specialization()
    {
      global $CFG, $DB;
      include($CFG->dirroot . '/theme/evagu/ccn/block_handler/specialization.php');

      if (empty($this->config)) {
        $this->config->title = 'Acervo digital';
        $this->config->subtitle = 'Texto falando sobre o Bloco EVA pequeno texto com duas linhas apenas.';
        // $this->config->image = $CFG->wwwroot . '/theme/evagu/images/about/8.jpg';
        $this->config->body['text'] = '<p class="color-black22 mt20">Ninguém, por si mesmo, o prazer porque é prazer rejeita, não gosta ou evita, mas quando, em conseqüência das grandes tristezas daqueles que em razão do prazer segui-lo sem perceber., Não era, e ninguém causou dor sozinho, que dolor sit amet, consequat quis velit, sed quia non numquam eius modi times</p>';
        $this->config->video_url = 'https://www.youtube.com/watch?v=zGSATGQ6Q8g&t=10s';
        $this->config->style = 0;
      }
    }
    public function get_content(){
        global $CFG, $DB;
        require_once($CFG->libdir . '/filelib.php');
        if ($this->content !== null) {
            return $this->content;
        }
        $this->content =  new stdClass;
        if(!empty($this->config->title)){$this->content->title = $this->config->title;} else {$this->content->title = "";}
        if(!empty($this->config->subtitle)){$this->content->subtitle = $this->config->subtitle;} else {$this->content->subtitle = "";}
        if(!empty($this->config->title)){$this->content->title = $this->config->title;} else {$this->content->title = '';}
        if(!empty($this->config->body)){$this->content->body = $this->config->body['text'];} else {$this->content->body = ''; }
        if(!empty($this->config->video_url)){$this->content->video_url = $this->config->video_url;} else {$this->content->video_url = '';}
        if(!empty($this->config->style)){$this->content->style = $this->config->style;} else {$this->content->style = 0;}
        if(!empty($this->config->button_color)){$this->content->button_color = $this->config->button_color;} else {$this->content->button_color = 'rgb(1, 16, 31);';}
        if(!empty($this->config->button_color_hover)){$this->content->button_color_hover = $this->config->button_color_hover;} else {$this->content->button_color_hover = 'rgb(255, 255, 255);';}
        if(!empty($this->config->button_text_color)){$this->content->button_text_color = $this->config->button_text_color;} else {$this->content->button_text_color = 'rgb(255, 255, 255);';}
        if(!empty($this->config->button_text_color_hover)){$this->content->button_text_color_hover = $this->config->button_text_color_hover;} else {$this->content->button_text_color_hover = 'rgb(0, 0, 0);';}
        if(!empty($this->config->button_url)){$this->content->button_url = $this->config->button_url;} else {$this->content->button_url = '';}
        if(!empty($this->config->button_text)){$this->content->button_text = $this->config->button_text;} else {$this->content->button_text = 'Mais vídeos';}
        $this->config->image = $CFG->wwwroot . '/theme/evagu/images/about/8.jpg';
          if($this->content->style == 1) {
          $class = '';
        } else {
          $class = 'ccn-row-reverse';
        }
        $fs = get_file_storage();
        $files = $fs->get_area_files($this->context->id, 'block_eva_about_3', 'content');
     
      foreach ($files as $file) {
      $filename = $file->get_filename();
      if ($filename <> '.') {
      $url = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), null, $file->get_filepath(), $filename);
      $this->content->image =  $url;
          }
        }

        $this->content->text = '
        <div class="container mt70">
        <div class="row">
          <div class="col-lg-6 offset-lg-3">
            <div class="main-title text-center">
              <h3 class="mt0">'.$this->content->title.'</h3>
              <p>'.format_text($this->content->subtitle, FORMAT_HTML, array('filter' => true)).'</p>
            </div>
          </div>
        </div>
      			<div class="row">
            </div>

          <div class="row '.$class.'">';
              $this->content->text .='<div class="col-lg-6">';
        			$this->content->text .='
        			<div class="about_content">
        				';
                // $this->content->text .='<h3 data-ccn="title">'.format_text($this->content->title, FORMAT_HTML, array('filter' => true)).'</h3>';
        				$this->content->text .='
                <div data-ccn="body">'.format_text($this->content->body, FORMAT_HTML, array('filter' => true, 'noclean' => true)).'
                  <style>
                  .new_btn_about3 {
                      background-color: '.$this->content->button_color.' !important;
                      border-color: '.$this->content->button_color.' !important;
                      color: '.$this->content->button_text_color.' !important;
                      border-radius: 0 !important;
                      -webkit-transition: all 0.3s ease !important;
                      -o-transition: all 0.3s ease !important;
                      transition: all 0.3s ease !important;
                  }

                  .new_btn_about3:hover {
                      background-color: '.$this->content->button_color_hover.' !important;
                      border-color: '.$this->content->button_color.' !important;
                      color: '.$this->content->button_text_color_hover.' !important;
                      border-radius: 0 !important;
                      -webkit-transition: all 0.3s ease !important;
                      -o-transition: all 0.3s ease !important;
                      transition: all 0.3s ease !important;
                  }
                  </style>
                  <p class="color-black22 mt20">
                    <a class="btn dbxshad btn-lg new_btn_about3 rounded" href="'.$this->content->button_url.'">
                      '.$this->content->button_text.'
                    </a>
                  </p>
                </div>
        			</div>
        	</div>';

        $ccnLazy = new ccnLazy();
        if($this->config->image){
        $this->content->text .='
        <div class="col-lg-6">
        <div class=".gallery_item .gallery_overlay">
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
        </div>';
      }
      
      $this->content->text .='
        </div>
        </div>';
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
