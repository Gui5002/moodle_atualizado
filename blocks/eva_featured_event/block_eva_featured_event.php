<?php
global $CFG;
require_once($CFG->dirroot . '/theme/evagu/ccn/block_handler/ccn_block_handler.php');
class block_eva_featured_event extends block_base {
    public function init() {
        $this->title = get_string('eva_featured_event', 'block_eva_featured_event');
    }
    public function specialization() {
        global $CFG, $DB;
        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/specialization.php');
    }
    public function get_content() {
        global $CFG, $DB;
        require_once($CFG->libdir . '/filelib.php');
        if ($this->content !== null) {
            return $this->content;
        }
        $this->content = new stdClass;
        // Verifique cada propriedade antes de acessá-la
        if (isset($this->config->title)) {
            $this->content->title = $this->config->title;
        }
        if (isset($this->config->body)) {
            $this->content->body = $this->config->body;
        }
        if (isset($this->config->date)) {
            $this->content->date = $this->config->date;
        }
        if (isset($this->config->end_date)) {
            $this->content->end_date = $this->config->end_date;
        }
        if (isset($this->config->time)) {
            $this->content->time = $this->config->time;
        }
        if (isset($this->config->location)) {
            $this->content->location = $this->config->location;
        }
        if (isset($this->config->button_text)) {
            $this->content->button_text = $this->config->button_text;
        }
        if (isset($this->config->button_link)) {
            $this->content->button_link = $this->config->button_link;
        }
        if (isset($this->config->config_category)) {
            $this->content->config_category = $this->config->config_category;
        }
        $fs = get_file_storage();
        $files = $fs->get_area_files($this->context->id, 'block_eva_featured_event', 'content');
        $this->content->image = '';
        foreach ($files as $file) {
            $filename = $file->get_filename();
            if ($filename <> '.') {
                $url = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), null, $file->get_filepath(), $filename);
                $this->content->image .= $url;
            }
        }
        $this->content->text = '
        <div class="row event_lists p0">
          <div class="col-xl-5 pr15-xl pr0 mb35">
            <div class="blog_grid_post event_lists">
              <div class="thumb" style="background-image: url(' . $this->content->image . ');background-size:cover;">
                <div class="post_date"><h2>' . userdate($this->content->date, '%d') . '</h2> <span>' . userdate($this->content->date, '%B') . '</span></div>
              </div>
            </div>
          </div>
          <div class="col-xl-7 pl15-xl pl0 mb35">
            <div class="blog_grid_post style2 event_lists">
              <div class="details">';
        if (!empty($this->content->title)) {
            $this->content->text .= '<h3 data-ccn="title">' . format_text($this->content->title, FORMAT_HTML, array('filter' => true)) . '</h3>';
        }
        if (!empty($this->content->body)) {
            $this->content->text .= '<p data-ccn="body">' . format_text($this->content->body, FORMAT_HTML, array('filter' => true)) . '</p>';
        }
        $this->content->text .= '
                <ul class="mb0 no-list-style">';
        if (!empty($this->content->date) || !empty($this->content->end_date)) {
            $this->content->text .= '<li class="ccn-block-featured-event-detail">';
            if (!empty($this->content->date)) {
                $this->content->text .= '<span class="flaticon-appointment"></span>' . get_string('config_date', 'theme_evagu') . ': ' . userdate($this->content->date, get_string('strftimedatefullshort', 'langconfig'));
            }
            if (!empty($this->content->end_date)) {
                $this->content->text .= '<span class="flaticon-appointment ' . (!empty($this->content->date) ? 'ml30' : '') . '"></span>' . get_string('end_date', 'theme_evagu') . ': ' . userdate($this->content->end_date, get_string('strftimedatefullshort', 'langconfig'));
            }
            $this->content->text .= '</li>';
        }
        if (!empty($this->content->time)) {
            $this->content->text .= '<li class="ccn-block-featured-event-detail"><span class="flaticon-clock"></span><span data-ccn="time">' . format_text($this->content->time, FORMAT_HTML, array('filter' => true)) . '</span></li>';
        }
        if (!empty($this->content->location)) {
            $this->content->text .= '<li class="ccn-block-featured-event-detail"><span class="flaticon-placeholder"></span><span data-ccn="location">' . format_text($this->content->location, FORMAT_HTML, array('filter' => true)) . '</span></li>';
        }
        if (!empty($this->config->category) && $this->config->category !== "0") {
            $category = $DB->get_record('course_categories', array('id' => $this->config->category));
            $this->content->text .= '<li class="ccn-block-featured-event-detail"><span class="fa fa-tags"></span><span data-ccn="location">' . get_string('category_text', 'block_eva_featured_event') . ": " . format_text($category->name, FORMAT_HTML, array('filter' => true)) . '</span></li>';
        }
        $this->content->text .= '
                </ul>';
        if (!empty($this->content->button_text)) {
            $this->content->text .= '<a data-ccn="button_text" href="' . $this->content->button_link . '" class="btn dbxshad btn-md btn-thm2 rounded mt30">' . format_text($this->content->button_text, FORMAT_HTML, array('filter' => true)) . '</a>';
        }
        $this->content->text .= '
              </div>
            </div>
          </div>
        </div>';
        return $this->content;
    }
    public function instance_allow_multiple() {
        return true;
    }
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
