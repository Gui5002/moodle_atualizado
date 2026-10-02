<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
namespace theme_evagu\output;

defined('MOODLE_INTERNAL') || die;

use action_link;
use action_menu;
use action_menu_filler;
use action_menu_link_secondary;
use block_contents;
use block_move_target;
use ccnUserHandler;
use coding_exception;
use context_course;
use context_header;
use context_system;
use core_text;
use custom_menu;
use custom_menu_item;
use html_writer;
use moodle_page;
use moodle_url;
use navigation_node;
use pix_icon;
use renderer_base;
use stdClass;

require_once ($CFG->dirroot . '/course/format/lib.php');
require_once ($CFG->dirroot . '/theme/evagu/ccn/mdl_handler/ccn_mdl_handler.php');

class core_renderer extends \core_renderer {
    /**
     * Return the image URL, if any.
     *
     * Note that maximum sizes are not applied to the image.
     *
     * @param int $maxwidth The maximum width, or null when the maximum width does not matter.
     * @param int $maxheight The maximum height, or null when the maximum height does not matter.
     * @return moodle_url|false
     */
    public function get_theme_image_headerlogo1($maxwidth = null, $maxheight = 100) {
        global $CFG;
        if (!empty($this->page->theme->settings->headerlogo1)) {
            $url = $this->page->theme->setting_file_url('headerlogo1', 'headerlogo1');
            // Get a URL suitable for moodle_url.
            $relativebaseurl = preg_replace('|^https?://|i', '//', $CFG->wwwroot);
            $url = str_replace($relativebaseurl, '', $url);
            return new moodle_url($url);
            return parent::get_theme_image_headerlogo1($maxwidth, $maxheight);
        }
    }

    public function get_theme_image_headerlogo2($maxwidth = null, $maxheight = 100) {
        global $CFG;
        if (!empty($this->page->theme->settings->headerlogo2)) {
            $url = $this->page->theme->setting_file_url('headerlogo2', 'headerlogo2');
            // Get a URL suitable for moodle_url.
            $relativebaseurl = preg_replace('|^https?://|i', '//', $CFG->wwwroot);
            $url = str_replace($relativebaseurl, '', $url);
            return new moodle_url($url);
            return parent::get_theme_image_headerlogo2($maxwidth, $maxheight);
        }
    }

    public function get_theme_image_headerlogo3($maxwidth = null, $maxheight = 100)
    {
        global $CFG;
        if (!empty($this->page->theme->settings->headerlogo3)) {
            $url = $this->page->theme->setting_file_url('headerlogo3', 'headerlogo3');
            // Get a URL suitable for moodle_url.
            $relativebaseurl = preg_replace('|^https?://|i', '//', $CFG->wwwroot);
            $url = str_replace($relativebaseurl, '', $url);
            return new moodle_url($url);
            return parent::get_theme_image_headerlogo3($maxwidth, $maxheight);
        }
    }

    public function get_theme_image_headerlogo4($maxwidth = null, $maxheight = 100) {
        global $CFG;
        if (!empty($this->page->theme->settings->headerlogo4)) {
            $url = $this->page->theme->setting_file_url('headerlogo4', 'headerlogo3');
            // Get a URL suitable for moodle_url.
            $relativebaseurl = preg_replace('|^https?://|i', '//', $CFG->wwwroot);
            $url = str_replace($relativebaseurl, '', $url);
            return new moodle_url($url);
            return parent::get_theme_image_headerlogo4($maxwidth, $maxheight);
        }
    }

    public function get_theme_image_headerlogo_mobile($maxwidth = null, $maxheight = 100) {
        global $CFG;
        if (!empty($this->page->theme->settings->headerlogo_mobile)) {
            $url = $this->page->theme->setting_file_url('headerlogo_mobile', 'headerlogo_mobile');
            // Get a URL suitable for moodle_url.
            $relativebaseurl = preg_replace('|^https?://|i', '//', $CFG->wwwroot);
            $url = str_replace($relativebaseurl, '', $url);
            return new moodle_url($url);
            return parent::get_theme_image_headerlogo_mobile($maxwidth, $maxheight);
        }
    }

    public function get_theme_image_footerlogo1($maxwidth = null, $maxheight = 100) {
        global $CFG;
        if (!empty($this->page->theme->settings->footerlogo1)) {
            $url = $this->page->theme->setting_file_url('footerlogo1', 'footerlogo1');
            // Get a URL suitable for moodle_url.
            $relativebaseurl = preg_replace('|^https?://|i', '//', $CFG->wwwroot);
            $url = str_replace($relativebaseurl, '', $url);
            return new moodle_url($url);
            return parent::get_theme_image_footerlogo1($maxwidth, $maxheight);
        }
    }

    public function get_theme_image_heading_bg($maxwidth = null, $maxheight = 100) {
        global $CFG;
        if (!empty($this->page->theme->settings->heading_bg)) {
            $url = $this->page->theme->setting_file_url('heading_bg', 'heading_bg');
            // Get a URL suitable for moodle_url.
            $relativebaseurl = preg_replace('|^https?://|i', '//', $CFG->wwwroot);
            $url = str_replace($relativebaseurl, '', $url);
            return new moodle_url($url);
            return parent::get_theme_image_heading_bg($maxwidth, $maxheight);
        }
    }

    public function get_theme_image_login_bg($maxwidth = null, $maxheight = 100) {
        global $CFG;
        if (!empty($this->page->theme->settings->login_bg)) {
            $url = $this->page->theme->setting_file_url('login_bg', 'login_bg');
            // Get a URL suitable for moodle_url.
            $relativebaseurl = preg_replace('|^https?://|i', '//', $CFG->wwwroot);
            $url = str_replace($relativebaseurl, '', $url);
            return new moodle_url($url);
            return parent::get_theme_image_login_bg($maxwidth, $maxheight);
        }
    }

    public function get_theme_image_favicon($maxwidth = null, $maxheight = 100) {
        global $CFG;
        if (!empty($this->page->theme->settings->favicon)) {
            $url = $this->page->theme->setting_file_url('favicon', 'favicon');
            // Get a URL suitable for moodle_url.
            $relativebaseurl = preg_replace('|^https?://|i', '//', $CFG->wwwroot);
            $url = str_replace($relativebaseurl, '', $url);
            return new moodle_url($url);
            return parent::get_theme_image_favicon($maxwidth, $maxheight);
        }
    }

    public function get_theme_image_preloader_image($maxwidth = null, $maxheight = 100) {
        global $CFG;
        if (!empty($this->page->theme->settings->preloader_image)) {
            $url = $this->page->theme->setting_file_url('preloader_image', 'preloader_image');
            // Get a URL suitable for moodle_url.
            $relativebaseurl = preg_replace('|^https?://|i', '//', $CFG->wwwroot);
            $url = str_replace($relativebaseurl, '', $url);
            return new moodle_url($url);
            return parent::get_theme_image_preloader_image($maxwidth, $maxheight);
        }
    }

    public function ccn_render_lang_menu() {
        global $CFG;
        $langs = get_string_manager()->get_list_of_translations();
        $strlang = get_string('language');
        $currentlang = current_language();
        $haslangmenu = $this->lang_menu() != '';
        if (isset($langs[$currentlang])) {
            $currentlang = $langs[$currentlang];
        } else {
            $currentlang = $strlang;
        }
        $langArr = [];
        foreach ($langs as $langtype => $langname) {
            $langArr[] = [
                'name' => $langname,
                'url' => new moodle_url($this->page->url, array('lang' => $langtype)),
                'code' => $langtype,
                'icon' => $CFG->wwwroot . '/theme/evagu/pix/lang/' . strtoupper(str_replace('_', '-', $langtype)) . '.svg',
            ];
        }
        $current_icon = '';
        foreach ($langArr as $k => $lang) {
            if ($lang['name'] == $currentlang)
                $current_icon = $langArr[$k]['icon'];
        }

        $context = [
            'has_lang_menu' => $haslangmenu,
            'current_lang' => $currentlang,
            'current_icon' => $current_icon,
            'strlang' => $strlang,
            'langs' => $langArr,
        ];
        return $this->render_from_template('theme_evagu/ccn_lang_menu', $context);
    }

    /**
     * @param custom_menu $menu
     * @return string
     */
    protected function render_custom_menu(custom_menu $menu) {
        global $CFG;
        $langs = get_string_manager()->get_list_of_translations();
        $haslangmenu = $this->lang_menu() != '';
        if (!$menu->has_children() && !$haslangmenu) {
            return '';
        }
        if ($haslangmenu && isset($this->page->theme->settings->language_menu) && $this->page->theme->settings->language_menu === '1') {
            $strlang = get_string('language');
            $currentlang = current_language();
            if (isset($langs[$currentlang])) {
                $currentlang = $langs[$currentlang];
            } else {
                $currentlang = $strlang;
            }
            $this->language = $menu->add($currentlang, new moodle_url('#'), $strlang, 10000);
            foreach ($langs as $langtype => $langname) {
                $this->language->add($langname, new moodle_url($this->page->url, array('lang' => $langtype)), $langname);
            }
        }
        $content = '';
        foreach ($menu->get_children() as $item) {
            $content .= $this->render_custom_menu_item($item);
        }
        return $content;
    }

    /**
     * Renders a custom menu node as part of a submenu
     *
     * The custom menu this method produces makes use of the YUI3 menunav widget
     * and requires very specific html elements and classes.
     *
     * @see core:renderer::render_custom_menu()
     *
     * @staticvar int $submenucount
     * @param custom_menu_item $menunode
     * @return string
     */
    protected function render_custom_menu_item(custom_menu_item $menunode) {
        // Required to ensure we get unique trackable id's
        static $submenucount = 0;
        if ($menunode->has_children()) {
            // If the child has menus render it as a sub menu
            $submenucount++;
            $content = html_writer::start_tag('li');
            if ($menunode->get_url() !== null) {
                $url = $menunode->get_url();
            } else {
                $url = '#cm_submenu_' . $submenucount;
            }
            $content .= html_writer::link($url, strip_tags(format_text($menunode->get_text(), FORMAT_HTML)), array('class' => 'ccn-menu-item', 'title' => $menunode->get_title()));
            // $content .= html_writer::link($url, $menunode->get_text(), array('class'=>'ccn-menu-item', 'title'=>$menunode->get_title()));
            // $content .= html_writer::start_tag('div', array('id'=>'cm_submenu_'.$submenucount, 'class'=>'yui3-menu custom_menu_submenu'));
            // $content .= html_writer::start_tag('div', array('class'=>'yui3-menu-content'));
            $content .= html_writer::start_tag('ul');
            foreach ($menunode->get_children() as $menunode) {
                $content .= $this->render_custom_menu_item($menunode);
            }
            $content .= html_writer::end_tag('ul');
            // $content .= html_writer::end_tag('div');
            // $content .= html_writer::end_tag('div');
            $content .= html_writer::end_tag('li');
        } else {
            $content = '';
            if (preg_match('/^#+$/', $menunode->get_text())) {
                // This is a divider.
                $content = html_writer::start_tag('li', array('class' => ''));
            } else {
                $content = html_writer::start_tag(
                    'li',
                    array(
                        'class' => ''
                    )
                );
                if ($menunode->get_url() !== null) {
                    $url = $menunode->get_url();
                } else {
                    $url = '#';
                }
                $content .=
                    html_writer::link($url, strip_tags(format_text($menunode->get_text(), FORMAT_HTML)), array('class' => 'ccn-menu-item', 'title' => $menunode->get_title()));
            }
            $content .= html_writer::end_tag('li');
        }
        // Return the sub menu
        $ccnUserHandler = new ccnUserHandler();
        $ccnCurrentUserIsGuestOrAnon = $ccnUserHandler->ccnCurrentUserIsGuestOrAnon();
        if (
            !empty($this->page->theme->settings->header_main_menu) &&
            $this->page->theme->settings->header_main_menu == '1' &&
            $ccnCurrentUserIsGuestOrAnon == true
        ) {
            return null;
        }
        return $content;
    }

    /**
     * The standard tags (meta tags, links to stylesheets and JavaScript, etc.)
     * @return string HTML fragment.
     */
    public function standard_head_html()
    {
        global $CFG, $SESSION, $SITE, $PAGE;
        foreach ($this->page->blocks->get_regions() as $region) {
            $this->page->blocks->ensure_content_created($region, $this);
        }
        $output = '';
        $pluginswithfunction = get_plugins_with_function('before_standard_html_head', 'lib.php');
        foreach ($pluginswithfunction as $plugins) {
            foreach ($plugins as $function) {
                $output .= $function();
            }
        }
        if (isset($CFG->urlrewriteclass) && !isset($CFG->upgraderunning)) {
            $class = $CFG->urlrewriteclass;
            $output .= $class::html_head_setup();
        }
        $output .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />' . "\n";

        if (!empty($this->page->theme->settings->meta_keywords)) {
            $output .= '<meta name="keywords" content="' . $this->page->theme->settings->meta_keywords . '" />' . "\n";
        } else {
            $output .= '<meta name="keywords" content="moodle, ' . $this->page->title . '" />' . "\n";
        }
        $output .= $this->metarefreshtag;
        // Check if a periodic refresh delay has been set and make sure we arn't
        // already meta refreshing
        if ($this->metarefreshtag == '' && $this->page->periodicrefreshdelay !== null) {
            $output .= '<meta http-equiv="refresh" content="' . $this->page->periodicrefreshdelay . ';url=' . $this->page->url->out() . '" />';
        }
        $this->page->requires->js_init_call('M.util.help_popups.setup');
        $focus = $this->page->focuscontrol;
        if (!empty($focus)) {
            if (preg_match("#forms\['([a-zA-Z0-9]+)'\].elements\['([a-zA-Z0-9]+)'\]#", $focus, $matches)) {
                $this->page->requires->js_function_call('old_onload_focus', array($matches[1], $matches[2]));
            } else if (strpos($focus, '.') !== false) {
                // Old style of focus, bad way to do it
                debugging('This code is using the old style focus event, Please update this code to focus on an element id or the moodleform focus method.', DEBUG_DEVELOPER);
                $this->page->requires->js_function_call('old_onload_focus', explode('.', $focus, 2));
            } else {
                $this->page->requires->js_function_call('focuscontrol', array($focus));
            }
        }
        $urls = $this->page->theme->css_urls($this->page);
        foreach ($urls as $url) {
            $this->page->requires->css_theme($url);
        }
        if ($jsurl = $this->page->theme->javascript_url(true)) {
            $this->page->requires->js($jsurl, true);
        }
        if ($jsurl = $this->page->theme->javascript_url(false)) {
            $this->page->requires->js($jsurl);
        }
        $output .= $this->page->requires->get_head_code($this->page, $this);
        foreach ($this->page->alternateversions as $type => $alt) {
            $output .= html_writer::empty_tag('link', array('rel' => 'alternate',
                'type' => $type, 'title' => $alt->title, 'href' => $alt->url));
        }
        $allowindexing = isset($CFG->allowindexing) ? $CFG->allowindexing : 0;
        $loginpages = array('login-index', 'login-signup');
        if ($allowindexing == 2 || ($allowindexing == 0 && in_array($this->page->pagetype, $loginpages))) {
            if (!isset($CFG->additionalhtmlhead)) {
                $CFG->additionalhtmlhead = '';
            }
            $CFG->additionalhtmlhead .= '<meta name="robots" content="noindex" />';
        }
        if (!empty($CFG->additionalhtmlhead)) {
            $output .= "\n" . $CFG->additionalhtmlhead;
        }
        if ($PAGE->pagelayout == 'frontpage') {
            $summary = s(strip_tags(format_text($SITE->summary, FORMAT_HTML)));
            if (!empty($this->page->theme->settings->meta_description)) {
                $output .= '<meta name="description" content="' . $this->page->theme->settings->meta_description . '" />' . "\n";
            } elseif (!empty($summary)) {
                $output .= "<meta name=\"description\" content=\"$summary\" />\n";
            }
        }
        if (!empty($this->page->theme->settings->meta_abstract)) {
            $output .= '<meta name="abstract" content="' . $this->page->theme->settings->meta_abstract . '" />' . "\n";
        }
        return $output;
    }

    /**
     * The standard tags (typically performance information and validation links,
     * @return string HTML fragment.
     */
    public function standard_footer_html()
    {
        global $CFG, $SCRIPT;
        $output = '';
        if (during_initial_install()) {
            return $output;
        }
        $pluginswithfunction = get_plugins_with_function('standard_footer_html', 'lib.php');
        foreach ($pluginswithfunction as $plugins) {
            foreach ($plugins as $function) {
                $output .= $function();
            }
        }
        $output .= $this->unique_performance_info_token;
        if ($this->page->devicetypeinuse == 'legacy') {
            // The legacy theme is in use print the notification
            $output .= html_writer::tag('div', get_string('legacythemeinuse'), array('class' => 'legacythemeinuse'));
        }
        $output .= $this->theme_switch_links();
        if (!empty($CFG->debugpageinfo)) {
            $output .= '<div class="performanceinfo pageinfo">' . get_string('pageinfodebugsummary', 'core_admin',
                $this->page->debug_summary()) . '</div>';
        }
        if (debugging(null, DEBUG_DEVELOPER) and has_capability('moodle/site:config', context_system::instance())) {  // Only in developer mode
            if (function_exists('profiling_is_running') && profiling_is_running()) {
                $txt = get_string('profiledscript', 'admin');
                $title = get_string('profiledscriptview', 'admin');
                $url = $CFG->wwwroot . '/admin/tool/profiling/index.php?script=' . urlencode($SCRIPT);
                $link = '<a title="' . $title . '" href="' . $url . '">' . $txt . '</a>';
                $output .= '<div class="profilingfooter">' . $link . '</div>';
            }
            $purgeurl = new moodle_url('/admin/purgecaches.php', array('confirm' => 1,
                'sesskey' => sesskey(), 'returnurl' => $this->page->url->out_as_local_url(false)));
            $output .= '<li class="list-inline-item"><div class="purgecaches">'
                . html_writer::link($purgeurl, get_string('purgecaches', 'admin')) . '</div></li>';
        }
        if (!empty($CFG->debugvalidators)) {
            $output .= '<div class="validators"><ul class="list-unstyled ml-1">
      <li><a href="http://validator.w3.org/check?verbose=1&amp;ss=1&amp;uri=' . urlencode(qualified_me()) . '">Validate HTML</a></li>
      <li><a href="http://www.contentquality.com/mynewtester/cynthia.exe?rptmode=-1&amp;url1=' . urlencode(qualified_me()) . '">Section 508 Check</a></li>
      <li><a href="http://www.contentquality.com/mynewtester/cynthia.exe?rptmode=0&amp;warnp2n3e=1&amp;url1=' . urlencode(qualified_me()) . '">WCAG 1 (2,3) Check</a></li>
    </ul></div>';
        }
        return $output;
    }

    /**
     * Returns standard main content placeholder.
     * Designed to be called in theme layout.php files.
     * @return string HTML fragment.
     */
    public function main_content()
    {
        return $this->unique_main_content_token;
    }

    public function firstview_fakeblocks(): bool {
        global $SESSION;
        $firstview = false;
        if ($this->page->cm) {
            if (!$this->page->blocks->region_has_fakeblocks('side-pre')) {
                return false;
            }
            if (!property_exists($SESSION, 'firstview_fakeblocks')) {
                $SESSION->firstview_fakeblocks = [];
            }
            if (array_key_exists($this->page->cm->id, $SESSION->firstview_fakeblocks)) {
                $firstview = false;
            } else {
                $SESSION->firstview_fakeblocks[$this->page->cm->id] = true;
                $firstview = true;
                if (count($SESSION->firstview_fakeblocks) > 100) {
                    array_shift($SESSION->firstview_fakeblocks);
                }
            }
        }
        return $firstview;
    }

    public function user_menu($user = null, $withlinks = null)
    {
        global $USER, $CFG;
        require_once ($CFG->dirroot . '/user/lib.php');

        if (is_null($user)) {
            $user = $USER;
        }
        if (is_null($withlinks)) {
            $withlinks = empty($this->page->layout_options['nologinlinks']);
        }
        $usermenuclasses = 'usermenu';
        if (!$withlinks) {
            $usermenuclasses .= ' withoutlinks';
        }

        $returnstr = '';

        // If during initial install, return the empty return string.
        if (during_initial_install()) {
            return $returnstr;
        }
        $loginpage = $this->is_login_page();
        $loginurl = get_login_url();
        if (!isloggedin()) {
            $returnstr = get_string('loggedinnot', 'moodle');
            if (!$loginpage) {
                $returnstr .= " (<a href=\"$loginurl\">" . get_string('login') . '</a>)';
            }
            return html_writer::div(
                html_writer::span(
                    $returnstr,
                    'login'
                ),
                $usermenuclasses
            );
        }
        if (isguestuser()) {
            $returnstr = get_string('loggedinasguest');
            if (!$loginpage && $withlinks) {
                $returnstr .= " (<a href=\"$loginurl\">" . get_string('login') . '</a>)';
            }
            return html_writer::div(
                html_writer::span(
                    $returnstr,
                    'login'
                ),
                $usermenuclasses
            );
        }
        $opts = user_get_user_navigation_info($user, $this->page);

        $avatarclasses = 'avatars';
        $avatarcontents = html_writer::span($opts->metadata['useravatar'], 'avatar current');
        $usertextcontents = $opts->metadata['userfullname'];
        // Other user.
        if (!empty($opts->metadata['asotheruser'])) {
            $avatarcontents .= html_writer::span(
                $opts->metadata['realuseravatar'],
                'avatar realuser'
            );
            $usertextcontents = $opts->metadata['realuserfullname'];
            $usertextcontents .= html_writer::tag(
                'span',
                get_string(
                    'loggedinas',
                    'moodle',
                    html_writer::span(
                        $opts->metadata['userfullname'],
                        'value'
                    )
                ),
                array('class' => 'meta viewingas')
            );
        }
        if (!empty($opts->metadata['asotherrole'])) {
            $role = core_text::strtolower(preg_replace('#[ ]+#', '-', trim($opts->metadata['rolename'])));
            $usertextcontents .= html_writer::span(
                $opts->metadata['rolename'],
                'meta role role-' . $role
            );
        }
        if (!empty($opts->metadata['userloginfail'])) {
            $usertextcontents .= html_writer::span(
                $opts->metadata['userloginfail'],
                'meta loginfailures'
            );
        }
        if (!empty($opts->metadata['asmnetuser'])) {
            $mnet = strtolower(preg_replace('#[ ]+#', '-', trim($opts->metadata['mnetidprovidername'])));
            $usertextcontents .= html_writer::span(
                $opts->metadata['mnetidprovidername'],
                'meta mnet mnet-' . $mnet
            );
        }
        $returnstr .= html_writer::span(
            html_writer::span($usertextcontents, 'usertext mr-1')
                . html_writer::span($avatarcontents, $avatarclasses),
            'userbutton'
        );
        $divider = new action_menu_filler();
        $divider->primary = false;
        $am = new action_menu();
        $am->set_menu_trigger(
            $returnstr
        );
        $am->set_action_label(get_string('usermenu'));
        $am->set_alignment(action_menu::TR, action_menu::BR);
        $am->set_nowrap_on_items();
        $ccn_nav_items = '';
        if ($withlinks) {
            $navitemcount = count($opts->navitems);
            $idx = 0;
            foreach ($opts->navitems as $key => $value) {
                $ccnMenuItemIcon = '';

                if (strpos($value->url, '/my')) {
                    $ccnMenuItemIcon = '<i class="icon fa fa-fw flaticon-puzzle-1"></i>';
                }
                if (strpos($value->url, '/profile.php')) {
                    $ccnMenuItemIcon = '<i class="icon fa fa-fw flaticon-student"></i>';
                }
                if (strpos($value->url, '/grade')) {
                    $ccnMenuItemIcon = '<i class="icon fa fa-fw flaticon-rating"></i>';
                }
                if (strpos($value->url, '/message')) {
                    $ccnMenuItemIcon = '<i class="icon fa fa-fw flaticon-speech-bubble"></i>';
                }
                if (strpos($value->url, '/preferences.php')) {
                    $ccnMenuItemIcon = '<i class="icon fa fa-fw flaticon-settings"></i>';
                }
                if (strpos($value->url, '/logout.php')) {
                    $ccnMenuItemIcon = '<i class="icon fa fa-fw flaticon-logout"></i>';
                }
                if (strpos($value->url, '/switchrole.php')) {
                    $ccnMenuItemIcon = '<i class="icon fa fa-fw flaticon-add-contact"></i>';
                }
                if (strpos($value->url, '/calendar')) {
                    $ccnMenuItemIcon = '<i class="icon fa fa-fw flaticon-calendar"></i>';
                }
                if (strpos($value->url, '/files.php')) {
                    $ccnMenuItemIcon = '<i class="icon fa fa-fw ccn-flaticon-document"></i>';
                }
                if (strpos($value->url, '/reportbuilder/index.php')) {
                    $ccnMenuItemIcon = '<i class="icon fa fa-fw flaticon-checklist"></i>';
                }
                if (strpos($value->url, '/blocks/eva_catalogo/view.php')) {
                    $ccnMenuItemIcon = '<i class="icon fa fa-fw ccn-flaticon-document"></i>';
                }
                if (strpos($value->url, '/mod/eva/eva_reports_users.php?id=1')) {
                    $ccnMenuItemIcon = '<i class="icon fa fa-fw flaticon-checklist"></i>';
                }

                if (isset($value->imgsrc) && !empty($value->imgsrc)) {
                    $ccnMenuItemIcon = '<img class="iconsmall" src="' . $value->imgsrc . '" alt=""/>';
                }
                switch ($value->itemtype) {
                    case 'divider':
                        $am->add($divider);
                        break;
                    case 'invalid':
                        break;
                    case 'link':
                        $pix = null;
                        if (isset($value->pix) && !empty($value->pix)) {
                            $pix = new pix_icon($value->pix, '', null, array('class' => 'iconsmall'));
                        } else if (isset($value->imgsrc) && !empty($value->imgsrc)) {
                            $value->title = html_writer::img(
                                $value->imgsrc,
                                $value->title,
                                array('class' => 'iconsmall')
                            ) . $value->title;
                        }
                        $al = new action_menu_link_secondary(
                            $value->url,
                            $pix,
                            $value->title,
                            array('class' => 'icon')
                        );
                        if (!empty($value->titleidentifier)) {
                            $al->attributes['data-title'] = $value->titleidentifier;
                        }
                        $am->add($al);
                        $ccn_nav_items .= '<a class="dropdown-item" href="' . $value->url . '"> ' . $ccnMenuItemIcon . $value->title . '</a>';
                        break;
                }
                $idx++;
            }
        }
        return $ccn_nav_items;
    }

    /**
     * Prints a nice side block with an optional header.
     * @param block_contents $bc HTML for the content
     * @param string $region the region the block is appearing in.
     * @return string the HTML to be output.
     */
    public function block(block_contents $bc, $region)
    {
        global $PAGE;

        $bc = clone ($bc);  // Avoid messing up the object passed in.
        if (empty($bc->blockinstanceid) || !strip_tags($bc->title)) {
            $bc->collapsible = block_contents::NOT_HIDEABLE;
        }
        $ccnBlockInventory = array(
            'eva_about_1',
            'eva_about_2',
            'eva_about_3',
            'eva_about_4',
            'eva_about_databases',
            'eva_abouteva',
            'eva_abouteva_cards',
            'eva_abouteva_info',
            'eva_accordion',
            'eva_accordion2',
            'eva_banner',
            'eva_banner_slider',
            'eva_blog_list',
            'eva_blog_recent',
            'eva_blog_recent_list',
            'eva_blog_recent_slider',
            'eva_boxes',
            'eva_cards_database',
            'eva_cards_post_internship',
            'eva_catalogo',
            'eva_cen_geral',
            'eva_cf_rating',
            'eva_contact_form',
            'eva_continuing_education_b1',
            'eva_continuing_education_b2',
            'eva_continuing_education_b3',
            'eva_continuing_education_cicles',
            'eva_continuing_education_news',
            'eva_course_details',
            'eva_course_features',
            'eva_course_grid',
            'eva_course_grid_2',
            'eva_course_grid_3',
            'eva_course_info',
            'eva_course_instructor',
            'eva_course_intro',
            'eva_course_list',
            'eva_course_overview',
            'eva_course_rating',
            'eva_courses_slider',
            'eva_custom_html',
            'eva_event_body',
            'eva_event_contact',
            'eva_event_details',
            'eva_events_categories_list',
            'eva_event_slider',
            'eva_faqs',
            'eva_featured_event',
            'eva_featured_posts',
            'eva_featured_teacher',
            'eva_featured_video',
            'eva_featuredcourses',
            'eva_form_library',
            'eva_gallery',
            'eva_gallery_slider',
            'eva_globalsearch_n',
            'eva_globalsearch_sb',
            'eva_library_central',
            'eva_library_information',
            'eva_library_service',
            'eva_list_study_room',
            'eva_more_courses',
            'eva_my_courses',
            'eva_mynews',
            'eva_myorders',
            'eva_myviews',
            'eva_numbers',
            'eva_partners',
            'eva_pills',
            'eva_postgraduate_about',
            'eva_postgraduate_contact',
            'eva_postgraduate_services',
            'eva_publicidade',
            'eva_reports',
            'eva_reports_users',
            'eva_reports_users_controll',
            'eva_revistagu',
            'eva_services',
            'eva_services_dark',
            'eva_slide_acoes',
            'eva_slider_8',
            'eva_steps',
            'eva_subscribe',
            'eva_tablets',
            'eva_tabs',
            'eva_traning_suggestion',
            'eva_ts_view',
            'eva_tstmnls_3',
            'eva_users',
            'eva_users_slider',
            'eva_users_slider_round',
            'eva_video',
            'eva_video_pesquisa',
        );
        // for blocks ControlledByOverrides in /templates
        $ccnPseudoBlockInventory = array(
            'myoverview',
            'recentlyaccessedcourses',
            'tags',
        );
        $ccnBreakoutBlockInvetory = array(
            'eva_myviews',
            'eva_mynews',
        );
        $ccn_lc_vbCollection = array(
            'eva_about_1',
            'eva_about_2',
            'eva_about_3',
            'eva_about_4',
            'eva_about_databases',
            'eva_abouteva',
            'eva_abouteva_cards',
            'eva_abouteva_info',
            'eva_accordion',
            'eva_accordion2',
            'eva_banner',
            'eva_banner_slider',
            'eva_blog_list',
            'eva_blog_recent',
            'eva_blog_recent_list',
            'eva_blog_recent_slider',
            'eva_boxes',
            'eva_cards_database',
            'eva_cards_post_internship',
            'eva_catalogo',
            'eva_cen_geral',
            'eva_cf_rating',
            'eva_contact_form',
            'eva_continuing_education_b1',
            'eva_continuing_education_b2',
            'eva_continuing_education_b3',
            'eva_continuing_education_cicles',
            'eva_continuing_education_news',
            'eva_course_details',
            'eva_course_features',
            'eva_course_grid',
            'eva_course_grid_2',
            'eva_course_grid_3',
            'eva_course_info',
            'eva_course_instructor',
            'eva_course_intro',
            'eva_course_list',
            'eva_course_overview',
            'eva_course_rating',
            'eva_courses_slider',
            'eva_custom_html',
            'eva_event_body',
            'eva_event_contact',
            'eva_event_details',
            'eva_events_categories_list',
            'eva_event_slider',
            'eva_faqs',
            'eva_featured_event',
            'eva_featured_posts',
            'eva_featured_teacher',
            'eva_featured_video',
            'eva_featuredcourses',
            'eva_form_library',
            'eva_gallery',
            'eva_gallery_slider',
            'eva_globalsearch_n',
            'eva_globalsearch_sb',
            'eva_library_central',
            'eva_library_information',
            'eva_library_service',
            'eva_list_study_room',
            'eva_more_courses',
            'eva_my_courses',
            'eva_mynews',
            'eva_myorders',
            'eva_myviews',
            'eva_numbers',
            'eva_partners',
            'eva_pills',
            'eva_postgraduate_about',
            'eva_postgraduate_contact',
            'eva_postgraduate_services',
            'eva_publicidade',
            'eva_revistagu',
            'eva_services',
            'eva_services_dark',
            'eva_slide_acoes',
            'eva_slider_8',
            'eva_steps',
            'eva_subscribe',
            'eva_tablets',
            'eva_tabs',
            'eva_traning_suggestion',
            'eva_ts_view',
            'eva_tstmnls_3',
            'eva_users',
            'eva_users_slider',
            'eva_users_slider_round',
            'eva_video',
            'eva_video_pesquisa',
        );
        // ccnBreak
        $id = !empty($bc->attributes['id']) ? $bc->attributes['id'] : uniqid('block-');
        $context = new stdClass();
        $context->skipid = $bc->skipid;
        $context->blockinstanceid = $bc->blockinstanceid;
        $context->dockable = $bc->dockable;
        $context->id = $id;
        $context->hidden = $bc->collapsible == block_contents::HIDDEN;
        $context->skiptitle = strip_tags($bc->title);
        $context->showskiplink = !empty($context->skiptitle);
        $context->arialabel = $bc->arialabel;
        $context->ariarole = !empty($bc->attributes['role']) ? $bc->attributes['role'] : 'complementary';
        $context->class = $bc->attributes['class'];
        $context->type = $bc->attributes['data-block'];
        if (array_key_exists('ccn_style', $bc->attributes)) {
            $context->ccn_style = $bc->attributes['ccn_style'];
        }
        if (in_array($context->type, $ccn_lc_vbCollection)) {
            $ccnActionUrl = $this->page->url->out(false, array('sesskey' => sesskey(), 'bui_editid' => $context->blockinstanceid, 'eva_live_customizer' => '1'));
            $context->ccn_lc_vb = $ccnActionUrl;
        }
        $ccnControlBlockAppearance = array_merge($ccnBlockInventory, $ccnPseudoBlockInventory);
        if (in_array($context->type, $ccnControlBlockAppearance) && !in_array($context->type, $ccnBreakoutBlockInvetory)) {
            $context->ccn_block = true;
        } else {
            $context->ccn_block = false;
        }
        $context->title = $bc->title;
        $context->content = $bc->content;
        $context->annotation = $bc->annotation;
        $context->footer = $bc->footer;
        $context->hascontrols = !empty($bc->controls);
        if ($context->hascontrols) {
            $context->controls = $this->block_controls($bc->controls, $id);
        }
        $context->ccn_context_course = false;
        if ($PAGE->pagelayout && ($PAGE->pagelayout == 'course' || $PAGE->pagelayout == 'incourse' || $PAGE->pagelayout == 'coursecategory')) {
            $context->ccn_context_course = true;
        } elseif ($PAGE->pagelayout && $PAGE->pagelayout == 'mydashboard' && $this->page->theme->settings->dashboard_layout == '1') {
            return $this->render_from_template('theme_evagu/ccn_block_dashboard_front', $context);
        } elseif ($PAGE->pagelayout && ($PAGE->pagelayout == 'mydashboard' || $PAGE->pagelayout == 'admin')) {
            return $this->render_from_template('theme_evagu/ccn_block_dashboard_dash', $context);
        }
        return $this->render_from_template('core/block', $context);
    }

    /**
     * Outputs a heading
     * @param string $text The text of the heading
     * @param int $level The level of importance of the heading. Defaulting to 2
     * @param string $classes A space-separated list of CSS classes. Defaulting to null
     * @param string $id An optional ID
     * @return string the HTML to output.
     */
    public function heading($text, $level = 2, $classes = null, $id = null)
    {
        $level = (int) $level;
        if ($level < 1 or $level > 6) {
            throw new coding_exception('Heading level must be an integer between 1 and 6.');
        }
        return html_writer::tag('h' . $level, $text, array('id' => $id, 'class' => renderer_base::prepare_classes($classes) . ' ccnMdlHeading'));
    }

    /**
     * Renders the header bar.
     * @param context_header $contextheader Header bar object.
     * @return string HTML for the header bar.
     */
    protected function render_context_header(context_header $contextheader)
    {
        $ccnMdlHandler = new \ccnMdlHandler();
        $ccnMdlVersion = $ccnMdlHandler->ccnGetCoreVersion();
        $ccnMdlVersion = (int) $ccnMdlVersion;
        if ($ccnMdlVersion >= 400) {
            // Generate the heading first and before everything else as we might have to do an early return.
            if (strpos($this->page->pagetype, 'mod') === false) {
                $heading = NULL;
            } else {
                if (!isset($contextheader->heading)) {
                    $heading = $this->heading($this->page->heading, $contextheader->headinglevel, 'h2');
                } else {
                    $heading = $this->heading($contextheader->heading, $contextheader->headinglevel, 'h2');
                }
            }
            $html = html_writer::start_div('page-context-header');
            // Image data.
            if (isset($contextheader->imagedata)) {
                // Header specific image.
                $html .= html_writer::div($contextheader->imagedata, 'page-header-image mr-2');
            }
            // Headings.
            if (isset($contextheader->prefix)) {
                $prefix = html_writer::div($contextheader->prefix, 'text-muted text-uppercase small line-height-3');
                $heading = $prefix . $heading;
            }
            $html .= html_writer::tag('div', $heading, array('class' => 'page-header-headings'));
            // Buttons.
            if (isset($contextheader->additionalbuttons)) {
                $html .= html_writer::start_div('btn-group header-button-group');
                foreach ($contextheader->additionalbuttons as $button) {
                    if (!isset($button->page)) {
                        if ($button['buttontype'] === 'togglecontact') {
                            \core_message\helper::togglecontact_requirejs();
                        }
                        if ($button['buttontype'] === 'message') {
                            \core_message\helper::messageuser_requirejs();
                        }
                        $image = $this->pix_icon($button['formattedimage'], $button['title'], 'moodle', array(
                            'class' => 'iconsmall',
                            'role' => 'presentation'
                        ));
                        $image .= html_writer::span($button['title'], 'header-button-title');
                    } else {
                        $image = html_writer::empty_tag('img', array(
                            'src' => $button['formattedimage'],
                            'role' => 'presentation'
                        ));
                    }
                    $html .= html_writer::link($button['url'], html_writer::tag('span', $image), $button['linkattributes']);
                }
                $html .= html_writer::end_div();
            }
            $html .= html_writer::end_div();
            return $html;
        } else {
            $html = '';
            if (!isset($contextheader->heading)) {
                $heading = NULL;
            } else {
                $heading = NULL;
            }
            $showheader = empty($this->page->layout_options['nocontextheader']);
            if (!$showheader) {
                return html_writer::div($heading, 'sr-only');
            }
            if ($heading !== NULL || isset($contextheader->additionalbuttons) || isset($contextheader->imagedata)) {
                $html = html_writer::start_div('page-context-header');
            }
            // Image data.
            if (isset($contextheader->imagedata)) {
                // Header specific image.
                $html .= html_writer::div($contextheader->imagedata, 'page-header-image');
            }
            // Headings.
            if ($heading !== NULL) {
                $html .= html_writer::tag('div', $heading, array('class' => 'page-header-headings'));
            }
            // Buttons.
            if (isset($contextheader->additionalbuttons)) {
                $html .= html_writer::start_div('header-button-group');
                foreach ($contextheader->additionalbuttons as $button) {
                    if (!isset($button->page)) {
                        if ($button['buttontype'] === 'togglecontact') {
                            \core_message\helper::togglecontact_requirejs();
                        }
                        if ($button['buttontype'] === 'message') {
                            \core_message\helper::messageuser_requirejs();
                        }
                        $image = $this->pix_icon($button['formattedimage'], $button['title'], 'moodle', array(
                            'class' => 'iconsmall',
                            'role' => 'presentation'
                        ));
                        $image .= html_writer::span($button['title'], 'header-button-title');
                    } else {
                        $image = html_writer::empty_tag('img', array(
                            'src' => $button['formattedimage'],
                            'role' => 'presentation'
                        ));
                    }
                    $html .= html_writer::link($button['url'], html_writer::tag('span', $image), $button['linkattributes']);
                }
                $html .= html_writer::end_div();
            }
            if ($heading !== NULL || isset($contextheader->additionalbuttons) || isset($contextheader->imagedata)) {
                $html .= html_writer::end_div();
            }
            return $html;
        }
    }

    public function context_header($headerinfo = null, $headinglevel = 1) {
        $ccnMdlHandler = new \ccnMdlHandler();
        $ccnMdlVersion = $ccnMdlHandler->ccnGetCoreVersion();
        $ccnMdlVersion = (int) $ccnMdlVersion;
        if ($ccnMdlVersion >= 400) {
            global $DB, $USER, $CFG, $SITE;
            require_once ($CFG->dirroot . '/user/lib.php');
            $context = $this->page->context;
            $heading = null;
            $imagedata = null;
            $subheader = null;
            $userbuttons = null;
            if (isset($headerinfo['heading'])) {
                $heading = $headerinfo['heading'];
            } else {
                $heading = $this->page->heading;
            }
            if ((isset($headerinfo['user']) || $context->contextlevel == CONTEXT_USER) && $this->page->pagetype !== 'my-index') {
                if (isset($headerinfo['user'])) {
                    $user = $headerinfo['user'];
                } else {
                    $user = $DB->get_record('user', array('id' => $context->instanceid));
                }
                if (isset($headerinfo['usercontext'])) {
                    $context = $headerinfo['usercontext'];
                }
                $course = ($this->page->context->contextlevel == CONTEXT_COURSE) ? $this->page->course : null;
                if (user_can_view_profile($user, $course)) {
                    // Use the user's full name if the heading isn't set.
                    if (empty($heading)) {
                        $heading = fullname($user);
                    }
                    $imagedata = $this->user_picture($user, array('size' => 100));
                    // Check to see if we should be displaying a message button.
                    if (!empty($CFG->messaging) && has_capability('moodle/site:sendmessage', $context)) {
                        $userbuttons = array(
                            'messages' => array(
                                'buttontype' => 'message',
                                'title' => get_string('message', 'message'),
                                'url' => new moodle_url('/message/index.php', array('id' => $user->id)),
                                'image' => 'message',
                                'linkattributes' => \core_message\helper::messageuser_link_params($user->id),
                                'page' => $this->page
                            )
                        );
                        if ($USER->id != $user->id) {
                            $iscontact = \core_message\api::is_contact($USER->id, $user->id);
                            $contacttitle = $iscontact ? 'removefromyourcontacts' : 'addtoyourcontacts';
                            $contacturlaction = $iscontact ? 'removecontact' : 'addcontact';
                            $contactimage = $iscontact ? 'removecontact' : 'addcontact';
                            $userbuttons['togglecontact'] = array(
                                'buttontype' => 'togglecontact',
                                'title' => get_string($contacttitle, 'message'),
                                'url' => new moodle_url('/message/index.php', array(
                                    'user1' => $USER->id,
                                    'user2' => $user->id,
                                    $contacturlaction => $user->id,
                                    'sesskey' => sesskey()
                                )),
                                'image' => $contactimage,
                                'linkattributes' => \core_message\helper::togglecontact_link_params($user, $iscontact),
                                'page' => $this->page
                            );
                        }
                        $this->page->requires->string_for_js('changesmadereallygoaway', 'moodle');
                    }
                } else {
                    $heading = null;
                }
            }
            $prefix = null;
            if ($context->contextlevel == CONTEXT_MODULE) {
                if ($this->page->course->format === 'singleactivity') {
                    $heading = $this->page->course->fullname;
                } else {
                    $heading = $this->page->cm->get_formatted_name();
                    $imagedata = $this->pix_icon('monologo', '', $this->page->activityname, ['class' => 'activityicon']);
                    $purposeclass = plugin_supports('mod', $this->page->activityname, FEATURE_MOD_PURPOSE);
                    $purposeclass .= ' activityiconcontainer';
                    $purposeclass .= ' modicon_' . $this->page->activityname;
                    $imagedata = html_writer::tag('div', $imagedata, ['class' => $purposeclass]);
                    $prefix = get_string('modulename', $this->page->activityname);
                }
            }
            $contextheader = new \context_header($heading, $headinglevel, $imagedata, $userbuttons, $prefix);
            return $this->render_context_header($contextheader);
        } else {
            global $DB, $USER, $CFG, $SITE, $PAGE;
            require_once ($CFG->dirroot . '/user/lib.php');
            $context = $this->page->context;
            $heading = null;
            $imagedata = null;
            $subheader = null;
            $userbuttons = null;
            // Make sure to use the heading if it has been set.
            if (isset($headerinfo['heading'])) {
                $heading = $headerinfo['heading'];
            } else {
                $heading = $this->page->heading;
            }
            if (isset($headerinfo['user']) || $context->contextlevel == CONTEXT_USER) {
                if (isset($headerinfo['user'])) {
                    $user = $headerinfo['user'];
                } else {
                    $user = $DB->get_record('user', array('id' => $context->instanceid));
                }
                if (isset($headerinfo['usercontext'])) {
                    $context = $headerinfo['usercontext'];
                }
                $course = ($this->page->context->contextlevel == CONTEXT_COURSE) ? $this->page->course : null;
                if (user_can_view_profile($user, $course)) {
                    // Use the user's full name if the heading isn't set.
                    if (empty($heading)) {
                        $heading = fullname($user);
                    }
                    $imagedata = $this->user_picture($user, array('size' => 100));
                    // Check to see if we should be displaying a message button.
                    if (!empty($CFG->messaging) && has_capability('moodle/site:sendmessage', $context)) {
                        $userbuttons = array(
                            'messages' => array(
                                'buttontype' => 'message',
                                'title' => get_string('message', 'message'),
                                'url' => new moodle_url('/message/index.php', array('id' => $user->id)),
                                'image' => 'message',
                                'linkattributes' => \core_message\helper::messageuser_link_params($user->id),
                                'page' => $this->page
                            )
                        );
                        if ($USER->id != $user->id) {
                            $iscontact = \core_message\api::is_contact($USER->id, $user->id);
                            $contacttitle = $iscontact ? 'removefromyourcontacts' : 'addtoyourcontacts';
                            $contacturlaction = $iscontact ? 'removecontact' : 'addcontact';
                            $contactimage = $iscontact ? 'removecontact' : 'addcontact';
                            $userbuttons['togglecontact'] = array(
                                'buttontype' => 'togglecontact',
                                'title' => get_string($contacttitle, 'message'),
                                'url' => new moodle_url('/message/index.php', array(
                                    'user1' => $USER->id,
                                    'user2' => $user->id,
                                    $contacturlaction => $user->id,
                                    'sesskey' => sesskey()
                                )),
                                'image' => $contactimage,
                                'linkattributes' => \core_message\helper::togglecontact_link_params($user, $iscontact),
                                'page' => $this->page
                            );
                        }
                        $this->page->requires->string_for_js('changesmadereallygoaway', 'moodle');
                    }
                } else {
                    $heading = null;
                }
            }
            if ($this->should_display_main_logo($headinglevel)) {
                $sitename = format_string($SITE->fullname, true, ['context' => context_course::instance(SITEID)]);
                // Logo.
                $html = html_writer::div(
                    html_writer::empty_tag('img', [
                        'src' => $this->get_logo_url(null, 150),
                        'alt' => get_string('logoof', '', $sitename),
                        'class' => 'img-fluid'
                    ]),
                    'logo'
                );
                // Heading.
                if (!isset($heading)) {
                    $html .= $this->heading($this->page->heading, $headinglevel, 'sr-only');
                } else {
                    $html .= $this->heading($heading, $headinglevel, 'sr-only');
                }
                return $html;
            }
            $contextheader = new context_header($heading, $headinglevel, $imagedata, $userbuttons);
            if ($PAGE->pagetype === 'user-profile') {
                $contextheader = new context_header('', $headinglevel, null, $userbuttons);
            }
            return $this->render_context_header($contextheader);
        }
    }

    /**
     * Render the login signup form into a nice template for the theme.
     * @param mform $form
     * @return string
     */
    public function custom_menu_flat()
    {
        global $CFG;
        $custommenuitems = '';
        if (empty($custommenuitems) && !empty($CFG->custommenuitems)) {
            $custommenuitems = $CFG->custommenuitems;
        }
        $custommenu = new custom_menu($custommenuitems, current_language());
        $langs = get_string_manager()->get_list_of_translations();
        $haslangmenu = $this->lang_menu() != '';
        if ($haslangmenu) {
            $strlang = get_string('language');
            $currentlang = current_language();
            if (isset($langs[$currentlang])) {
                $currentlang = $langs[$currentlang];
            } else {
                $currentlang = $strlang;
            }
            $this->language = $custommenu->add($currentlang, new moodle_url('#'), $strlang, 10000);
            foreach ($langs as $langtype => $langname) {
                $this->language->add($langname, new moodle_url($this->page->url, array('lang' => $langtype)), $langname);
            }
        }
        return $custommenu->export_for_template($this);
    }

    public function full_header() {
        $ccnMdlHandler = new \ccnMdlHandler();
        $ccnMdlVersion = $ccnMdlHandler->ccnGetCoreVersion();
        $ccnMdlVersion = (int) $ccnMdlVersion;
        if ($ccnMdlVersion >= 400) {
            $pagetype = $this->page->pagetype;
            $homepage = get_home_page();
            $homepagetype = null;
            // Add a special case since /my/courses is a part of the /my subsystem.
            if ($homepage == HOMEPAGE_MY || $homepage == HOMEPAGE_MYCOURSES) {
                $homepagetype = 'my-index';
            } else if ($homepage == HOMEPAGE_SITE) {
                $homepagetype = 'site-index';
            }
            if ($this->page->include_region_main_settings_in_header_actions() &&
                    !$this->page->blocks->is_block_present('settings')) {
                $this->page->add_header_action(html_writer::div(
                    $this->region_main_settings_menu(),
                    'd-print-none',
                    ['id' => 'region-main-settings-menu']
                ));
            }
            $header = new stdClass();
            $header->settingsmenu = $this->context_header_settings_menu();
            $header->contextheader = $this->context_header();
            $header->hasnavbar = empty($this->page->layout_options['nonavbar']);
            $header->navbar = $this->navbar();
            $header->pageheadingbutton = $this->page_heading_button();
            $header->courseheader = $this->course_header();
            $header->headeractions = $this->page->get_header_actions();
            if (!empty($pagetype) && !empty($homepagetype) && $pagetype == $homepagetype) {
                $header->welcomemessage = \core_user::welcome_message();
            }
            return $this->render_from_template('theme_evagu/ccn_mdl_400/full_header', $header);
        } else {

    /*
     * Compatibilidade Moodle 3.5.
     *
     * region_main_settings_menu() e
     * include_region_main_settings_in_header_actions()
     * podem não existir nesta versão.
     */
    if (
        method_exists($this->page, 'include_region_main_settings_in_header_actions') &&
        method_exists($this, 'region_main_settings_menu') &&
        $this->page->include_region_main_settings_in_header_actions() &&
        !$this->page->blocks->is_block_present('settings')
    ) {
        $this->page->add_header_action(
            html_writer::div(
                $this->region_main_settings_menu(),
                'd-print-none',
                array('id' => 'region-main-settings-menu')
            )
        );
    }

    $header = new stdClass();
            $header->settingsmenu = $this->context_header_settings_menu();
            $header->contextheader = $this->context_header();
            $header->hasnavbar = empty($this->page->layout_options['nonavbar']);
            $header->navbar = $this->navbar();
            $header->pageheadingbutton = $this->page_heading_button();
            $header->courseheader = $this->course_header();
            $header->headeractions = $this->page->get_header_actions();
            return $this->render_from_template('core/full_header', $header);
        }
    }

    public function should_display_main_logo($headinglevel = 1)
    {
        $logo = $this->get_logo_url();

        if ($headinglevel == 1 && !empty($logo)) {
            if ($this->page->pagelayout == 'frontpage' || $this->page->pagelayout == 'login') {
                return true;
            }
        }
        return false;
    }
}
