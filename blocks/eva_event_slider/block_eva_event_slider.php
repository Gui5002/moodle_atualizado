<?php
global $CFG;
require_once($CFG->dirroot . '/theme/evagu/ccn/block_handler/ccn_block_handler.php');

class block_eva_event_slider extends block_base
{

    function init()
    {
        $this->title = get_string('pluginname', 'block_eva_event_slider');
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

        $ccn_title = !empty($this->config->title) ? $this->config->title : '';
        $ccn_subtitle = !empty($this->config->subtitle) ? $this->config->subtitle : '';

        if (!empty($this->config) && is_object($this->config)) {
            $data = $this->config;
            $data->slidesnumber = is_numeric($data->slidesnumber) ? (int)$data->slidesnumber : 0;
            $slidersize = ($data->style == 1) ? 'slide slide-one home6' : 'slide slide-one sh2';
        } else {
            $data = new stdClass();
            $data->slidesnumber = 0;
        }

        $text = '';
        $textDiv = '';

        if ($data->slidesnumber > 0) {
            $text = '<section class="our-blog">
<div class="container">
<div class="row">
<div class="col-lg-6 offset-lg-3">
<div class="main-title text-center">';
            if (!empty($ccn_title)) {
                $text .= '<h1 class="mt0">' . format_text($ccn_title, FORMAT_HTML, array('filter' => true)) . '</h1>';
            }
            if (!empty($ccn_subtitle)) {
                $text .= '<p>' . format_text($ccn_subtitle, FORMAT_HTML, array('filter' => true)) . '</p>';
            }
            $text .= '</div></div></div><div class="row"><div class="col-lg-12"><div class="blog_post_slider_home2">';

            $fs = get_file_storage();
            for ($i = 1; $i <= $data->slidesnumber; $i++) {
                $sliderimage = 'file_slide' . $i;
                $slide_title = 'slide_title' . $i;
                $slide_subtitle = 'slide_subtitle' . $i;
                $slide_btn_link = 'slide_btn_link' . $i;
                $slide_btn_text = 'slide_btn_text' . $i;
                $slide_time = 'slide_time' . $i;
                $slide_location = 'slide_location' . $i;
                $slide_date = 'slide_date' . $i;
                $slide_url_field = 'slide_url' . $i;

                $slide_url = !empty($data->$slide_url_field) ? $data->$slide_url_field : '';

                if (!empty($data->$sliderimage)) {
                    $files = $fs->get_area_files($this->context->id, 'block_eva_event_slider', 'slides', $i, 'sortorder DESC, id ASC', false, 0, 0, 1);
                    if (count($files) >= 1) {
                        $mainfile = reset($files)->get_filename();
                    } else {
                        continue;
                    }

                    $text .= '<div class="item">';
                    if ($slide_url) {
                        $text .= '<a href="' . $slide_url . '">';
                    }

                    $text .= '<div class="blog_post_home2">
<div class="bph2_header">
<img class="img-fluid" src="' . moodle_url::make_file_url("$CFG->wwwroot/pluginfile.php", "/{$this->context->id}/block_eva_event_slider/slides/" . $i . '/' . $mainfile) . '" alt="">';

                    if (!empty($data->$sliderimage)) {
                        $text .= '<div class="bph2_date_meta">
<span class="year">' . userdate($data->$slide_date, '%d') . ' <br> ' . userdate($data->$slide_date, '%B') . '</span>
</div>';
                    }

                    $text .= '</div><div class="details"><div class="post_meta"><ul>';
                    if (!empty($data->$slide_time)) {
                        $text .= '<li class="list-inline-item"><span><i class="flaticon-calendar"></i> ' . format_text($data->$slide_time, FORMAT_HTML, array('filter' => true)) . '</span></li>';
                    }
                    if (!empty($data->$slide_location)) {
                        $text .= '<li class="list-inline-item"><span><i class="flaticon-placeholder"></i> ' . format_text($data->$slide_location, FORMAT_HTML, array('filter' => true)) . '</span></li>';
                    }

                    $text .= '</ul></div>';

                    if (!empty($data->$slide_title)) {
                        $text .= '<h4>' . format_text($data->$slide_title, FORMAT_HTML, array('filter' => true)) . '</h4>';
                    }

                    $text .= '</div></div>';

                    if ($slide_url) {
                        $text .= '</a>';
                    }

                    $text .= '</div>';
                }
            }

            $text .= '</div></div></div></div>';

            // Footer com botão
            if (!empty($data->footer_text)) {
                $textDiv = '
<div class="row">
<div class="col-lg-6 offset-lg-3">
<div class="courses_all_btn">
<a class="btn btn-transparent" data-ccn="button_text" href="' . format_text($data->button_link, FORMAT_HTML, array('filter' => true)) . '">
' . $data->button_text . '
<span class="flaticon-right-arrow pl10"></span>
</a>
</div>
</div>
</div>';
                $text .= '<div class="row"><div class="col-lg-12"><div class="read_more_home text-center">' . $textDiv . '</div></div></div>';
            }

            $text .= '</section>';
        }

        $text .= '<script>
$(document).ready(function(){
let footerHtml = \'' . addslashes($textDiv) . '\';
if ($(".block_eva_event_slider .owl-stage-outer").length > 0) {
$(".block_eva_event_slider .owl-stage-outer").append(footerHtml);
} else {
$(".block_eva_event_slider").append(footerHtml);
}
$(".block_eva_event_slider .details h4 a").css("color","#fff");
});
</script>';

        $this->content = new stdClass;
        $this->content->footer = '';
        $this->content->text = $text;
        return $this->content;
    }

    function instance_config_save($data, $nolongerused = false)
    {
        global $CFG;
        $filemanageroptions = array(
            'maxbytes' => $CFG->maxbytes,
            'subdirs' => 0,
            'maxfiles' => 1,
            'accepted_types' => array('.jpg', '.png', '.jpeg')
        );
        for ($i = 1; $i <= $data->slidesnumber; $i++) {
            $field = 'file_slide' . $i;
            if (!isset($data->$field)) {
                continue;
            }
            file_save_draft_area_files($data->$field, $this->context->id, 'block_eva_event_slider', 'slides', $i, $filemanageroptions);
        }
        parent::instance_config_save($data, $nolongerused);
    }

    function instance_delete()
    {
        $fs = get_file_storage();
        $fs->delete_area_files($this->context->id, 'block_eva_event_slider');
        return true;
    }

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
            'maxbytes' => $CFG->maxbytes,
            'subdirs' => 0,
            'maxfiles' => 1,
            'accepted_types' => array('.jpg', '.png', '.gif')
        );

        for ($i = 1; $i <= $data->slidesnumber; $i++) {
            $field = 'file_slide' . $i;
            if (!isset($data->$field)) {
                continue;
            }
            if (!$fs->is_area_empty($fromcontext->id, 'block_eva_event_slider', 'slides', $i, false)) {
                $draftitemid = 0;
                file_prepare_draft_area($draftitemid, $fromcontext->id, 'block_eva_event_slider', 'slides', $i, $filemanageroptions);
                file_save_draft_area_files($draftitemid, $this->context->id, 'block_eva_event_slider', 'slides', $i, $filemanageroptions);
            }
        }
        return true;
    }

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