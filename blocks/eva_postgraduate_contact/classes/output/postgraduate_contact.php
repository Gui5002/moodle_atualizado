<?php

namespace block_eva_postgraduate_contact\output;

defined('MOODLE_INTERNAL') || die();

use core_table\local\filter\string_filter;
use moodle_url;
use renderable;
use renderer_base;
use templatable;

class postgraduate_contact implements renderable, templatable {

    var $config;
    var $context;

    public function __construct($config, $context) {
        $this->config = $config;
        $this->context = $context;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return stdClass
     */

    public function export_for_template(renderer_base $output) {
        global $USER, $OUTPUT, $PAGE, $CFG;

        $data = new \stdClass();
        if(!empty($this->config->title)){$data->title = $this->config->title;}
        if(!empty($this->config->subtitle)){$data->subtitle = $this->config->subtitle;}
        if(!empty($this->config->style)){$data->style = $this->config->style;} else {$data->style = 0;}


        $fs = get_file_storage();
        $files = $fs->get_area_files($this->context->id, 'block_eva_postgraduate_contact', 'content');
        $this->config->image = $CFG->wwwroot . '/theme/evagu/images/about/8.jpg';
        foreach ($files as $file) {
            $filename = $file->get_filename();
            if ($filename <> '.') {
                $url = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), null, $file->get_filepath(), $filename);
                $this->config->image =  $url;
            }
        }

        for ($i = 1; $i <= $this->config->items; $i++) {
            $title = 'title_'.$i;
            $subtitle = 'subtitle_'.$i;
            $icon = 'icon_'.$i;

            $show = $i == 1 ? 'show' : '';

            $grades[$i-1]['id'] = $i;
            $grades[$i-1]['card_icon'] = $this->config->$icon;
            $grades[$i-1]['card_title'] = $this->config->$title;
            $grades[$i-1]['card_subtitle'] = $this->config->$subtitle;
        }
        //var_dump($this->config->recaptcha == 0);die();
        $hidden = 'hidden';
        if($this->config->recaptcha == 0) {
            if (!isloggedin() || isguestuser()) {
                $captcha = $this->getrecaptcha();
            }
        } else if($this->config->recaptcha == 1) {
            $captcha = $this->getrecaptcha();
        }


        $data->cards = $grades;
        $data->captcha = $captcha;
        $data->hiden = $hidden;
        $data->img = $this->config->image;

        return $data;
    }

    function getrecaptcha() {
        global $CFG;
        // Is Moodle reCAPTCHA configured?
        if (!empty($CFG->recaptchaprivatekey) && !empty($CFG->recaptchapublickey)) {
            // Yes? Generate reCAPTCHA.
            if (file_exists($CFG->libdir . '/recaptchalib_v2.php')) {
                // For reCAPTCHA 2.0.
                require_once($CFG->libdir . '/recaptchalib_v2.php');
                return '<div class="ccn_recaptcha_container">'. recaptcha_get_challenge_html(RECAPTCHA_API_URL, $CFG->recaptchapublickey).'</div>';
            } else {
                // For reCAPTCHA 1.0.
                require_once($CFG->libdir . '/recaptchalib.php');
                return recaptcha_get_html($CFG->recaptchapublickey, null, $this->ishttps());
            }
        } else { // If debugging is set to DEVELOPER...
            // Show indicator that {reCAPTCHA} tag is not required.
            return '<a class="mt40 mb20 btn btn-danger" href="'.$CFG->wwwroot.'/admin/settings.php?section=manageauths"><i class="fa fa-warning"></i> Configure reCAPTCHA keys</a>';
        }
        // Logged-in as non-guest user (reCAPTCHA is not required) or Moodle reCAPTCHA not configured.
        // Don't generate reCAPTCHA.
        return '';
    }

}