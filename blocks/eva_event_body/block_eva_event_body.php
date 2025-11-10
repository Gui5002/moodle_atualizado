<?php
global $CFG;
require_once($CFG->dirroot . '/theme/evagu/ccn/block_handler/ccn_block_handler.php');
class block_eva_event_body extends block_base
{
    public function init()
    {
        $this->title = get_string('eva_event_body', 'block_eva_event_body');
    }
    public function specialization()
    {
        global $CFG;
        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/specialization.php');
    }
    public function get_content()
    {
        global $CFG;
        require_once($CFG->libdir . '/filelib.php');
        if ($this->content !== null) {
            return $this->content;
        }
        $this->content = new stdClass();
        $this->prepare_content();
        return $this->content;
    }
    private function prepare_content()
    {
        global $DB;
        $this->content->title = $this->config->title ?? '';
        $this->content->date = $this->config->date ?? '';
        $this->content->image = $this->fetch_image();
        $this->content->text = $this->generate_html_content();
    }
    private function fetch_image()
    {
        $fs = get_file_storage();
        $files = $fs->get_area_files($this->context->id, 'block_eva_event_body', 'content');
        $imageUrls = '';
        foreach ($files as $file) {
            if ($file->get_filename() !== '.') {
                $url = moodle_url::make_pluginfile_url(
                    $file->get_contextid(),
                    $file->get_component(),
                    $file->get_filearea(),
                    null,
                    $file->get_filepath(),
                    $file->get_filename()
                );
                $imageUrls .= $url;
            }
        }
        return $imageUrls;
    }
    private function generate_html_content()
    {
        $formattedTitle = format_text($this->content->title, FORMAT_HTML, ['filter' => true]);
        $dateTime = $this->format_date($this->content->date);
        $content = '<div class="mbp_thumb_post">
                        <div class="details pt0">
                            <h3 class="mb25">' . $formattedTitle . '</h3>
                        </div>';
        if (!empty($this->content->image)) {
            $content .= '<div class="thumb">
                            <img class="img-fluid" style="max-height: 488px;object-fit: fill;" src="' . $this->content->image . '" alt="' . $formattedTitle . '">
                            <div class="post_date">
                                <h2>' . userdate($this->content->date, '%d') . '</h2>
                                <span>' . userdate($this->content->date, '%B') . '</span>
                            </div>
                         </div>';
        }
        $content .= '<div class="event_counter_plugin_container">
                        <div class="event_counter_plugin_content">
                            <ul>
                                <li>' . get_string('days', 'theme_evagu') . '<span id="days"></span></li>
                                <li>' . get_string('hours', 'theme_evagu') . '<span id="hours"></span></li>
                                <li>' . get_string('minutes', 'theme_evagu') . '<span id="minutes"></span></li>
                                <li>' . get_string('seconds', 'theme_evagu') . '<span id="seconds"></span></li>
                            </ul>
                        </div>
                    </div>
                </div>';
        $content .= $this->generate_script($dateTime);
        return $content;
    }
    private function format_date($timestamp)
    {
        $date = new \DateTime(); // Adiciona a barra invertida para usar a classe global
        $date->setTimestamp($timestamp);
        return $date->format('d M Y H:i');
    }
    private function generate_script($date)
    {
        return '<script>
                    document.addEventListener("DOMContentLoaded", function() {
                        (function($) {
                            if ($(".event_counter_plugin_container").length) {
                                const second = 1000,
                                    minute = second * 60,
                                    hour = minute * 60,
                                    day = hour * 24;
                                let countDown = new Date("' . $date . '").getTime(),
                                    x = setInterval(function() {
                                        let now = new Date().getTime(),
                                            distance = countDown - now;
                                        document.getElementById("days").innerText = Math.floor(distance / day);
                                        document.getElementById("hours").innerText = Math.floor((distance % day) / hour);
                                        document.getElementById("minutes").innerText = Math.floor((distance % hour) / minute);
                                        document.getElementById("seconds").innerText = Math.floor((distance % minute) / second);
                                    }, second);
                            }
                        }(jQuery));
                    }, false);
                </script>';
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
        return $ccnBlockHandler->ccnGetBlockApplicability(['all']);
    }
    public function html_attributes()
    {
        global $CFG;
        $attributes = parent::html_attributes();
        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/attributes.php');
        return $attributes;
    }
}
