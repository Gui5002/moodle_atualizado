<?php
global $CFG;
require_once ($CFG->dirroot . '/theme/evagu/ccn/block_handler/ccn_block_handler.php');
require_once ($CFG->dirroot . '/theme/evagu/ccn/general_handler/ccnLazy.php');

class block_eva_about_4 extends block_base
{
    // Declare first
    public function init()
    {
        $this->title = get_string('eva_about_4', 'block_eva_about_4');
    }

    // Declare second
    public function specialization()
    {
        global $CFG, $DB;
        include ($CFG->dirroot . '/theme/evagu/ccn/block_handler/specialization.php');

        if (empty($this->config)) {
            $this->config->title = 'Salas de estudo EVA';
            $this->config->image = $CFG->wwwroot . '/theme/evagu/images/about/8.jpg';
            $this->config->body['text'] = '<p class="color-black22 mt20">Texto teste para troca omnis iste natus error sit voluptatem accusantium laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis,et quasi architecto beatae vitae dicta sunt explicabo.</p><p class="mt15">Texto teste para troca iste natus error sit voluptatem accusantium laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis,et quasi architecto beatae vitae dicta sunt explicabo.</p><p class="mt20">Nemo enim ipsam,voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia,consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.,Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, adipisci velit, sed quia non numquam eius modi tempora</p>';
            $this->config->video_url = 'https://www.youtube.com/watch?v=HFr3TkPSBBE';
            $this->config->style = 0;
        }
    }

    public function get_content()
    {
        global $CFG, $DB;
        require_once ($CFG->libdir . '/filelib.php');
        if ($this->content !== null) {
            return $this->content;
        }
        $this->content = new stdClass;
        if (!empty($this->config->title)) {
            $this->content->title = $this->config->title;
        } else {
            $this->content->title = '';
        }
        if (!empty($this->config->body)) {
            $this->content->body = $this->config->body['text'];
        } else {
            $this->content->body = '';
        }
        if (!empty($this->config->video_url)) {
            $this->content->video_url = $this->config->video_url;
        } else {
            $this->content->video_url = '';
        }
        if (!empty($this->config->style)) {
            $this->content->style = $this->config->style;
        } else {
            $this->content->style = 0;
        }

        if ($this->content->style == 1) {
            $class = '';
        } else {
            $class = 'ccn-row-reverse';
        }
        $fs = get_file_storage();
        $files = $fs->get_area_files($this->context->id, 'block_eva_about_4', 'content');
        $this->config->image = $CFG->wwwroot . '/theme/evagu/images/about/8.jpg';
        foreach ($files as $file) {
            $filename = $file->get_filename();
            if ($filename <> '.') {
                $url = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), null, $file->get_filepath(), $filename);
                $this->content->image = $url;
            }
        }

        $this->content->text = '
        <div class="container mt80">
        <div class="row ' . $class . '">';

        $this->content->text .= '<div class="col-lg-6">';

        $this->content->text .= '
        <div class="about_content">
          <h2 data-ccn="title" style="font-size: 1.5em; font-weight: 800;">' . format_text($this->content->title, FORMAT_HTML, array('filter' => true)) . '</h2>
          <div data-ccn="body">' . format_text($this->content->body, FORMAT_HTML, array('filter' => true, 'noclean' => true)) . '</div>
        </div>
      </div>';

        $ccnLazy = new ccnLazy();
        if ($this->config->image) {
            $this->content->text .= "
        <div class=\"col-lg-6\">
        <div class=\".gallery_item .gallery_overlay\">
      \t\t\t\t\t\t<img class=\"img-fluid img-circle-rounded\" alt=\"\" data-ccn=\"image\" data-ccn-img=\"content\" " . $ccnLazy->ccnLazyImage($this->content->image) . ">
             \t\t<div
                    class=\"gallery_overlay\"
                    data-ccn-c=\"color_overlay\"
                    data-ccn-co=\"ccnBg\"
                    data-ccn-cv=\"" . $this->content->color_overlay . "\">
      \t\t\t\t\t\t\t<a class=\"popup-img popup-youtube home_post_overlay_icon bgc-theme8\" href=\"" . $this->content->video_url . "\">
      \t\t\t\t\t\t\t\t<div class=\"video_popup_btn\"><span class=\"flaticon-play-button-1\"></span></div>
      \t\t\t\t\t\t\t</a>
      \t\t\t\t\t\t</div>
      \t\t\t\t\t</div>
        </div>";
        }

        $this->content->text .= '
        </div>
        </div>';
        $this->content->text .= '
    <script>
        $(document).ready(function(){
           $("#ccn-main-region").addClass("removeCcnMainStyle");
           $("#ccn-main").remove();
        });
    </script>
    ';
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
    function has_config()
    {
        return true;
    }

    function applicable_formats()
    {
        $ccnBlockHandler = new ccnBlockHandler();
        return $ccnBlockHandler->ccnGetBlockApplicability(array('all'));
    }

    public function html_attributes()
    {
        global $CFG;
        $attributes = parent::html_attributes();
        include ($CFG->dirroot . '/theme/evagu/ccn/block_handler/attributes.php');
        return $attributes;
    }
}
