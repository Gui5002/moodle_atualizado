<?php
namespace block_eva_library_service\output;
defined('MOODLE_INTERNAL') || die();
use moodle_url;
use renderable;
use renderer_base;
use templatable;
class library_service implements renderable, templatable {
    /** @var mixed */
    public $content = null;
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
        global $USER, $OUTPUT, $PAGE, $CFG, $DB;
        require_once($CFG->libdir . '/filelib.php');
        if ($this->content !== null) {
            return $this->content;
        }
        $data = new \stdClass();
        if(!empty($this->config->title)){$data->title = $this->config->title;} else {$data->title = '';}
//        if(!empty($this->config->bt_title11)){$data->title = $this->config->title;} else {$data->title = '';}
        if (!empty($this->config) && is_object($this->config)) {
            $data->items = is_numeric($this->config->items) ? (int)$this->config->items : 4;
        } else {
            $data->items = 4;
        }
//        var_dump($this->config->bt_title11);die();
        $fs = get_file_storage();
        for ($i = 1; $i <= $data->items; $i++) {
            $tabs_image = 'tabs_image'.$i;
            if (!empty($this->config->$tabs_image)) {
                $files = $fs->get_area_files($this->context->id, 'block_eva_library_service', 'content', $i);
                foreach ($files as $file) {
                    $filename = $file->get_filename();
                    if ($filename <> '.') {
                        $url = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), $file->get_itemid(), $file->get_filepath(), $filename);
                        $navs[$i-1]['image'] = $url;
                    }
                }
            }
            $bt_title1 = 'bt_title1'.$i;
            $bt_title2 = 'bt_title2'.$i;
            $bt_title3 = 'bt_title3'.$i;
            $bt_title4 = 'bt_title4'.$i;
            $bt_title5 = 'bt_title5'.$i;
            $bt_link1 = 'bt_link1'.$i;
            $bt_link2 = 'bt_link2'.$i;
            $bt_link3 = 'bt_link3'.$i;
            $bt_link4 = 'bt_link4'.$i;
            $bt_link5 = 'bt_link5'.$i;
            $tooltip1 = 'tooltip1'.$i;
            $tooltip2 = 'tooltip2'.$i;
            $tooltip3 = 'tooltip3'.$i;
            $tooltip4 = 'tooltip4'.$i;
            $tooltip5 = 'tooltip5'.$i;
            $tt_title1 = '<div class="toltip">'.$this->config->$tooltip1.'</div>';
            $tt_title2 = '<div class="toltip">'.$this->config->$tooltip2.'</div>';
            $tt_title3 = '<div class="toltip">'.$this->config->$tooltip3.'</div>';
            $tt_title4 = '<div class="toltip">'.$this->config->$tooltip4.'</div>';
            $tt_title5 = '<div class="toltip">'.$this->config->$tooltip5.'</div>';
            $body = 'body'.$i;
            $tabs_title = 'tabs_title'.$i;
            $show = $i == 1 ? 'show' : '';
            $active = $i == 1 ? 'active' : '';
            $navs[$i-1]['id'] = $i;
            $navs[$i-1]['info_tabs'] = $this->config->$tabs_title;
            $navs[$i-1]['info_body'] = $this->config->$body['text'];
            $navs[$i-1]['info_show'] = $show;
            $navs[$i-1]['info_active'] = $active;
            $navs[$i-1]['link_target'] = isset($this->config->link_target) ? $this->config->link_target : '_self';
            $navs[$i-1]['bt1'] = $this->config->$bt_title1;
            $navs[$i-1]['hiden1'] = ($this->config->$bt_title1 == "") ? 'hidden' : '';
            $navs[$i-1]['link1'] = $this->config->$bt_link1;
            $navs[$i-1]['tooltip1'] = $this->config->$tooltip1?$tt_title1:'';
            $navs[$i-1]['tt1'] = $this->config->$tooltip1 ? 'tooltip' : '';
            $navs[$i-1]['bt2'] = $this->config->$bt_title2;
            $navs[$i-1]['hiden2'] = ($this->config->$bt_title2 == "") ? 'hidden' : '';
            $navs[$i-1]['link2'] = $this->config->$bt_link2;
            $navs[$i-1]['tooltip2'] = $this->config->$tooltip2?$tt_title2:'';
            $navs[$i-1]['tt2'] = $this->config->$tooltip2 ? 'tooltip' : '';
            $navs[$i-1]['bt3'] = $this->config->$bt_title3;
            $navs[$i-1]['hiden3'] = ($this->config->$bt_title3 == "") ? 'hidden' : '';
            $navs[$i-1]['link3'] = $this->config->$bt_link3;
            $navs[$i-1]['tooltip3'] = $this->config->$tooltip3?$tt_title3:'';
            $navs[$i-1]['tt3'] = $this->config->$tooltip3 ? 'tooltip' : '';
            $navs[$i-1]['bt4'] = $this->config->$bt_title4;
            $navs[$i-1]['hiden4'] = ($this->config->$bt_title4 == "") ? 'hidden' : '';
            $navs[$i-1]['tooltip4'] = $this->config->$tooltip4?$tt_title4:'';
            $navs[$i-1]['tt4'] = $this->config->$tooltip4 ? 'tooltip' : '';
            $navs[$i-1]['bt5'] = $this->config->$bt_title5;
            $navs[$i-1]['hiden5'] = ($this->config->$bt_title5 == "") ? 'hidden' : '';
            $navs[$i-1]['link5'] = $this->config->$bt_link5;
            $navs[$i-1]['tooltip5'] = $this->config->$tooltip5?$tt_title5:'';
            $navs[$i-1]['tt5'] = $this->config->$tooltip5 ? 'tooltip' : '';
        }
        $hidden = 'hidden';
        if(isset($this->config->recaptcha) && $this->config->recaptcha == 0) {
            if (!isloggedin() || isguestuser()) {
                $captcha = $this->getrecaptcha();
            }
        } else if(isset($this->config->recaptcha) && $this->config->recaptcha == 1) {
            $captcha = $this->getrecaptcha();
        }
        $dadolocals = $DB->get_records('eva_biblioteca_local', array(), 'id ASC');
        $i = 0;
        foreach ($dadolocals as $key=>$dadolocal) {
            $local[$i]['e_mail'] = $dadolocal->email;
            $local[$i]['destino'] = $dadolocal->local;
            $i++;
        }
        $user = trim($USER->firstname) . ' ' . trim($USER->lastname);
        $user = $user == " "? trim($user) : $user;
        $name = trim($CFG->supportname);
        $email = trim($CFG->supportemail);
        $data->info = $navs;
        $data->name = $user ? $user : $name;
        $data->email = $USER->email ? $USER->email : $email;
        $data->options = $local;
//        $data->captcha = $captcha;
        $data->hiden = $hidden;
//        var_dump($data);die();
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
