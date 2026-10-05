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
// This line protects the file from being accessed by a URL directly.
defined('MOODLE_INTERNAL') || die();
$ccnFontList = include ($CFG->dirroot . '/theme/evagu/ccn/font_handler/ccn_font_select.php');
require_once ($CFG->dirroot . '/theme/evagu/ccn/mdl_handler/ccn_mdl_handler.php');
$ccnMdlHandler = new ccnMdlHandler();
$ccnMdlVersion = $ccnMdlHandler->ccnGetCoreVersion();
$ccnMdlVersion = (int) $ccnMdlVersion;
// This is used for performance, we don't need to know about these settings on every page in Moodle, only when
// we are looking at the admin settings pages.
if ($ADMIN->fulltree) {
    // Boost provides a nice setting page which splits settings onto separate tabs. We want to use it here.
    $settings = new theme_boost_admin_settingspage_tabs('themesettingevagu', get_string('configtitle', 'theme_evagu'));
    // CCN General settings
    $page = new admin_settingpage('theme_evagu_general', get_string('general_settings', 'theme_evagu'));
    // Blog style
    $setting = new admin_setting_configselect(
        'theme_evagu/blogstyle',
        get_string('blogstyle', 'theme_evagu'),
        get_string('blogstyle_desc', 'theme_evagu'),
        null,
        array(
            '1' => 'Blog style 1',
            '2' => 'Blog style 2',
        )
    );
    $page->add($setting);
    // Back to Top
    $setting = new admin_setting_configselect(
        'theme_evagu/back_to_top',
        get_string('back_to_top', 'theme_evagu'),
        get_string('back_to_top_desc', 'theme_evagu'),
        null,
        array(
            '0' => 'Visible',
            '1' => 'Hidden'
        )
    );
    $page->add($setting);
    // Back to Top
    $setting = new admin_setting_configselect(
        'theme_evagu/language_menu',
        get_string('language_menu', 'theme_evagu'),
        get_string('language_menu_desc', 'theme_evagu'),
        null,
        array(
            '0' => 'Visible',
            '1' => 'Hidden'
        )
    );
    $page->add($setting);
    // Favicon
    $name = 'theme_evagu/favicon';
    $title = get_string('favicon', 'theme_evagu');
    $description = get_string('favicon_desc', 'theme_evagu');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'favicon');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    $settings->add($page);
    // CCN Logo settings
    $page = new admin_settingpage('theme_evagu_logo', get_string('logo_settings', 'theme_evagu'));
    // Header logos
    $page->add(new admin_setting_heading('theme_evagu/header_logos', get_string('header_logos', 'theme_evagu'), NULL));
    // Logotype
    $setting = new admin_setting_configselect(
        'theme_evagu/logotype',
        get_string('logotype', 'theme_evagu'),
        get_string('logotype_desc', 'theme_evagu'),
        null,
        array(
            '0' => 'Visible',
            '1' => 'Hidden'
        )
    );
    $page->add($setting);
    // Logo image
    $setting = new admin_setting_configselect(
        'theme_evagu/logo_image',
        get_string('logo_image', 'theme_evagu'),
        get_string('logo_image_desc', 'theme_evagu'),
        null,
        array(
            '0' => 'Visible',
            '1' => 'Hidden'
        )
    );
    $page->add($setting);
    // Logo Image Width
    $setting = new admin_setting_configtext('theme_evagu/logo_image_width', get_string('logo_image_width', 'theme_evagu'), get_string('logo_image_width_desc', 'theme_evagu'), '', PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Logo Image Height
    $setting = new admin_setting_configtext('theme_evagu/logo_image_height', get_string('logo_image_height', 'theme_evagu'), get_string('logo_image_height_desc', 'theme_evagu'), '', PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Header logo 1
    $name = 'theme_evagu/headerlogo1';
    $title = get_string('headerlogo1', 'theme_evagu');
    $description = get_string('headerlogo1_desc', 'theme_evagu');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'headerlogo1');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Header logo 2
    $name = 'theme_evagu/headerlogo2';
    $title = get_string('headerlogo2', 'theme_evagu');
    $description = get_string('headerlogo2_desc', 'theme_evagu');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'headerlogo2');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Header logo 3
    $name = 'theme_evagu/headerlogo3';
    $title = get_string('headerlogo3', 'theme_evagu');
    $description = get_string('headerlogo3_desc', 'theme_evagu');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'headerlogo3');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    $name = 'theme_evagu/headerlogo4';
    $title = get_string('headerlogo4', 'theme_evagu');
    $description = get_string('headerlogo4_desc', 'theme_evagu');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'headerlogo4');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Header logo mobile
    $name = 'theme_evagu/headerlogo_mobile';
    $title = get_string('headerlogo_mobile', 'theme_evagu');
    $description = get_string('headerlogo_mobile_desc', 'theme_evagu');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'headerlogo_mobile');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Footer logos
    $page->add(new admin_setting_heading('theme_evagu/footer_logos', get_string('footer_logos', 'theme_evagu'), NULL));
    // Logotype Footer
    $setting = new admin_setting_configselect(
        'theme_evagu/logotype_footer',
        get_string('logotype_footer', 'theme_evagu'),
        get_string('logotype_footer_desc', 'theme_evagu'),
        null,
        array(
            '0' => 'Visible',
            '1' => 'Hidden'
        )
    );
    $page->add($setting);
    // Logo image Footer
    $setting = new admin_setting_configselect(
        'theme_evagu/logo_image_footer',
        get_string('logo_image_footer', 'theme_evagu'),
        get_string('logo_image_footer_desc', 'theme_evagu'),
        null,
        array(
            '0' => 'Visible',
            '1' => 'Hidden'
        )
    );
    $page->add($setting);
    // Logo Image Width footer
    $setting = new admin_setting_configtext('theme_evagu/logo_image_width_footer', get_string('logo_image_width_footer', 'theme_evagu'), get_string('logo_image_width_footer_desc', 'theme_evagu'), '', PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Logo Image Height footer
    $setting = new admin_setting_configtext('theme_evagu/logo_image_height_footer', get_string('logo_image_height_footer', 'theme_evagu'), get_string('logo_image_height_footer_desc', 'theme_evagu'), '', PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Footer logo 1
    $name = 'theme_evagu/footerlogo1';
    $title = get_string('footerlogo1', 'theme_evagu');
    $description = get_string('footerlogo1_desc', 'theme_evagu');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'footerlogo1');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    $settings->add($page);
    // CCN Header settings
    $page = new admin_settingpage('theme_evagu_header', get_string('header_settings', 'theme_evagu'));
    // Library list
    $setting = new admin_setting_configselect(
        'theme_evagu/library_list',
        get_string('library_list', 'theme_evagu'),
        get_string('library_list_desc', 'theme_evagu'),
        null,
        array(
            '0' => 'Hidden',
            '1' => 'Visible'
        )
    );
    $page->add($setting);
    // Search
    $setting = new admin_setting_configselect(
        'theme_evagu/header_search',
        get_string('header_search', 'theme_evagu'),
        get_string('header_search_desc', 'theme_evagu'),
        null,
        array(
            '0' => 'Show icon',
            '1' => 'Show searchbar',
            '2' => 'Hidden'
        )
    );
    $page->add($setting);
    // Login
    $setting = new admin_setting_configselect(
        'theme_evagu/header_login',
        get_string('header_login', 'theme_evagu'),
        get_string('header_login_desc', 'theme_evagu'),
        null,
        array(
            '0' => 'Login popup',
            '1' => 'Login link',
            '2' => 'Hidden'
        )
    );
    $page->add($setting);
    // Menu
    $setting = new admin_setting_configselect(
        'theme_evagu/header_main_menu',
        get_string('header_main_menu', 'theme_evagu'),
        get_string('header_main_menu_desc', 'theme_evagu'),
        '0',
        array(
            '0' => 'Visible to all users',
            '1' => 'Visible only to authenticated users'
        )
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    if ($ccnMdlVersion >= 400) {
        $setting = new admin_setting_configselect(
            'theme_evagu/disable_primary_nav',
            get_string('disable_primary_nav', 'theme_evagu'),
            get_string('disable_primary_nav_desc', 'theme_evagu'),
            '0',
            array(
                '0' => 'Enable primary navigation',
                '1' => 'Disable primary navigation'
            )
        );
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
    }
    // Header type
    $setting = new admin_setting_configselect(
        'theme_evagu/headertype',
        get_string('headertype', 'theme_evagu'),
        get_string('headertype_desc', 'theme_evagu'),
        null,
        array(
            '1' => 'Header 1',
        )
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Header type settings
    // $setting = new admin_setting_configselect('theme_evagu/headertype_settings',
    //     get_string('headertype_settings', 'theme_evagu'),
    //     get_string('headertype_settings_desc', 'theme_evagu'), '1',
    //             array('1' => 'All pages (recommended)'
    //                 ));
    // $setting->set_updatedcallback('theme_reset_all_caches');
    // $page->add($setting);
    // Header email address
    $setting = new admin_setting_configtext('theme_evagu/email_address', get_string('email_address', 'theme_evagu'), get_string('email_address_desc', 'theme_evagu'), 'hello@evagu.com', PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Header phone
    $setting = new admin_setting_configtext('theme_evagu/phone', get_string('phone', 'theme_evagu'), get_string('phone_desc', 'theme_evagu'), '(56) 123 456 789', PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Call to Action Text
    $setting = new admin_setting_configtext('theme_evagu/cta_text', get_string('cta_text', 'theme_evagu'), get_string('cta_text_desc', 'theme_evagu'), 'Become an Instructor', PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Call to Action Link
    $setting = new admin_setting_configtext('theme_evagu/cta_link', get_string('cta_link', 'theme_evagu'), get_string('cta_link_desc', 'theme_evagu'), '#', PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Call to Action icon
    $setting = new admin_setting_configselect(
        'theme_evagu/cta_icon_ccn_icon_class',
        get_string('cta_icon', 'theme_evagu'),
        get_string('cta_icon_desc', 'theme_evagu'),
        'flaticon-megaphone',
        $ccnFontList
    );
    $page->add($setting);
    $settings->add($page);
    // CCN Breadcrumb settings
    $page = new admin_settingpage('breadcrumb_settings', get_string('breadcrumb_settings', 'theme_evagu'));
    // Breadcrumb settings
    $page->add(new admin_setting_heading('theme_evagu/breadcrumb_settings', get_string('breadcrumb_settings', 'theme_evagu'), NULL));
    // Breadcrumb background
    $name = 'theme_evagu/heading_bg';
    $title = get_string('heading_bg', 'theme_evagu');
    $description = get_string('heading_bg_desc', 'theme_evagu');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'heading_bg');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Breadcrumb style
    $setting = new admin_setting_configselect(
        'theme_evagu/breadcrumb_style',
        get_string('breadcrumb_style', 'theme_evagu'),
        get_string('breadcrumb_style_desc', 'theme_evagu'),
        0,
        array(
            '0' => 'Default (large)',
            '1' => 'Medium',
            '2' => 'Small',
            '3' => 'Extra small',
            '4' => 'Hidden'
        )
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Breadcrumb title
    $setting = new admin_setting_configselect(
        'theme_evagu/breadcrumb_title',
        get_string('breadcrumb_title', 'theme_evagu'),
        get_string('breadcrumb_title_desc', 'theme_evagu'),
        0,
        array(
            '0' => 'Visible',
            '1' => 'Hidden',
        )
    );
    $page->add($setting);
    // Breadcrumb trail
    $setting = new admin_setting_configselect(
        'theme_evagu/breadcrumb_trail',
        get_string('breadcrumb_trail', 'theme_evagu'),
        get_string('breadcrumb_trail_desc', 'theme_evagu'),
        0,
        array(
            '0' => 'Visible',
            '1' => 'Hidden',
        )
    );
    $page->add($setting);
    // Breadcrumb clip text
    $setting = new admin_setting_configselect(
        'theme_evagu/breadcrumb_clip',
        get_string('breadcrumb_clip', 'theme_evagu'),
        get_string('breadcrumb_clip_desc', 'theme_evagu'),
        0,
        array(
            '0' => 'Clip long text',
            '1' => 'Clip very long text only',
            '2' => 'Do not clip text',
        )
    );
    $page->add($setting);
    // Breadcrumb clip text
    $setting = new admin_setting_configselect(
        'theme_evagu/breadcrumb_clip',
        get_string('breadcrumb_clip', 'theme_evagu'),
        get_string('breadcrumb_clip_desc', 'theme_evagu'),
        0,
        array(
            '0' => 'Clip long text',
            '1' => 'Clip very long text only',
            '2' => 'Do not clip text',
        )
    );
    $page->add($setting);
    // Breadcrumb capitalization
    $setting = new admin_setting_configselect(
        'theme_evagu/breadcrumb_caps',
        get_string('breadcrumb_caps', 'theme_evagu'),
        get_string('breadcrumb_caps_desc', 'theme_evagu'),
        0,
        array(
            '0' => 'Capitalized',
            '1' => 'Lowercase',
            '2' => 'Uppercase',
            '3' => 'None (inherit from page title)',
        )
    );
    $page->add($setting);
    $settings->add($page);
    // CCN Preloader settings
    $page = new admin_settingpage('preloader_settings', get_string('preloader_settings', 'theme_evagu'));
    // Preloader settings
    $page->add(new admin_setting_heading('theme_evagu/preloader_settings', get_string('preloader_settings', 'theme_evagu'), NULL));
    // Preloader image
    $name = 'theme_evagu/preloader_image';
    $title = get_string('preloader_image', 'theme_evagu');
    $description = get_string('preloader_image_desc', 'theme_evagu');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'preloader_image');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Preloader duration
    $setting = new admin_setting_configselect(
        'theme_evagu/preloader_duration',
        get_string('preloader_duration', 'theme_evagu'),
        get_string('preloader_duration_desc', 'theme_evagu'), 0,
        array(
            '0' => 'Wait for page to fully load (highly recommended)',
            '1' => 'Wait for core elements to load',
            '2' => 'Wait for page to fully load, but no longer than 5 seconds',
            '3' => 'Wait for page to fully load, but no longer than 4 seconds',
            '4' => 'Wait for page to fully load, but no longer than 3 seconds',
            '5' => 'Wait for page to fully load, but no longer than 2 seconds',
            '6' => 'Disable preloader (not recommended)'
        )
    );
    $page->add($setting);
    $settings->add($page);
    // CCN Footer settings
    $page = new admin_settingpage('theme_evagu_footer', get_string('footer_settings', 'theme_evagu'));
    // Footer settings
    $page->add(new admin_setting_heading('theme_evagu/footer_settings', get_string('footer_settings', 'theme_evagu'), NULL));
    // Footer copyright
    $setting = new admin_setting_configtext('theme_evagu/eva_copyright', get_string('eva_copyright', 'theme_evagu'), get_string('eva_copyright_desc', 'theme_evagu'), 'Copyright ©2020 ESAGU Moodle Theme by RCN. All Rights Reserved.', PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Footer style
    $setting = new admin_setting_configselect(
        'theme_evagu/footertype',
        get_string('footertype', 'theme_evagu'),
        get_string('footertype_desc', 'theme_evagu'),
        null,
        array(
            '1' => 'Footer 1',
        )
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Footer column 1
    $page->add(new admin_setting_heading('theme_evagu/footer_col_1', get_string('footer_col_1', 'theme_evagu'), NULL));
    // Footer column title
    $setting = new admin_setting_configtext('theme_evagu/footer_col_1_title', get_string('footer_col_title', 'theme_evagu'), get_string('footer_col_title_desc', 'theme_evagu'), 'Contact', PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Footer column body
    $setting = new admin_setting_configtextarea('theme_evagu/footer_col_1_body', get_string('footer_col_body', 'theme_evagu'), get_string('footer_col_body_desc', 'theme_evagu'), 'Body text for the first column.', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Footer column 2
    $page->add(new admin_setting_heading('theme_evagu/footer_col_2', get_string('footer_col_2', 'theme_evagu'), NULL));
    // Footer column title
    $setting = new admin_setting_configtext('theme_evagu/footer_col_2_title', get_string('footer_col_title', 'theme_evagu'), get_string('footer_col_title_desc', 'theme_evagu'), 'Company', PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Footer column body
    $setting = new admin_setting_configtextarea('theme_evagu/footer_col_2_body', get_string('footer_col_body', 'theme_evagu'), get_string('footer_col_body_desc', 'theme_evagu'), 'Body text for the second column.', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Footer column 3
    $page->add(new admin_setting_heading('theme_evagu/footer_col_3', get_string('footer_col_3', 'theme_evagu'), NULL));
    // Footer column title
    $setting = new admin_setting_configtext('theme_evagu/footer_col_3_title', get_string('footer_col_title', 'theme_evagu'), get_string('footer_col_title_desc', 'theme_evagu'), 'Programs', PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Footer column body
    $setting = new admin_setting_configtextarea('theme_evagu/footer_col_3_body', get_string('footer_col_body', 'theme_evagu'), get_string('footer_col_body_desc', 'theme_evagu'), 'Body text for the third column.', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Footer column 4
    $page->add(new admin_setting_heading('theme_evagu/footer_col_4', get_string('footer_col_4', 'theme_evagu'), NULL));
    // Footer column title
    $setting = new admin_setting_configtext('theme_evagu/footer_col_4_title', get_string('footer_col_title', 'theme_evagu'), get_string('footer_col_title_desc', 'theme_evagu'), 'Support', PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Footer column body
    $setting = new admin_setting_configtextarea('theme_evagu/footer_col_4_body', get_string('footer_col_body', 'theme_evagu'), get_string('footer_col_body_desc', 'theme_evagu'), 'Body text for the fourth column.', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Footer column 5
    $page->add(new admin_setting_heading('theme_evagu/footer_col_5', get_string('footer_col_5', 'theme_evagu'), NULL));
    // Footer column title
    $setting = new admin_setting_configtext('theme_evagu/footer_col_5_title', get_string('footer_col_title', 'theme_evagu'), get_string('footer_col_title_desc', 'theme_evagu'), 'Mobile', PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Footer column body
    $setting = new admin_setting_configtextarea('theme_evagu/footer_col_5_body', get_string('footer_col_body', 'theme_evagu'), get_string('footer_col_body_desc', 'theme_evagu'), 'Body text for the fifth column.', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Footer menu
    $page->add(new admin_setting_heading('theme_evagu/footer_menu_heading', get_string('footer_menu', 'theme_evagu'), NULL));
    // Footer menu
    $setting = new admin_setting_configtextarea('theme_evagu/footer_menu', get_string('footer_menu', 'theme_evagu'), get_string('footer_menu_desc', 'theme_evagu'), 'Body text for the footer menu.', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    $settings->add($page);
    // CCN Course settings
    $page = new admin_settingpage('theme_evagu_course_settings', get_string('course_settings', 'theme_evagu'));
    // General course settings
    $page->add(new admin_setting_heading('theme_evagu/general_course_settings', get_string('general_course_settings', 'theme_evagu'), NULL));
    if (class_exists('NumberFormatter')) {
        // Course price format
        $setting = new admin_setting_configselect(
            'theme_evagu/course_price_format',
            get_string('course_price_format', 'theme_evagu'),
            get_string('course_price_format_desc', 'theme_evagu'),
            '6',
            array(
                '4' => '49R$',
                '5' => '49 R$',
                '6' => 'R$49',
                '7' => 'R$ 49',
                '8' => 'R$49 REAL',
                '9' => 'R$49REAL',
                /* @ccnBreak: these are duplicates of the 0-3 without NumberFormatter */
                '10' => 'REAL 49',
                '11' => 'REAL49',
                '12' => '49REAL',
                '13' => '49 REAL',
            )
        );
        $page->add($setting);
    } else {
        // Course price format
        $setting = new admin_setting_configselect(
            'theme_evagu/course_price_format',
            get_string('course_price_format', 'theme_evagu'),
            get_string('course_price_format_desc', 'theme_evagu'),
            null,
            array(
                '0' => 'R$49',
                '1' => 'R$ 49',
                '2' => '49R$',
                '3' => '49 R$',
                '4' => '49R$',
                '5' => '49 R$',
                '6' => 'R$49',
                '7' => 'R$ 49',
                '8' => 'R$49 REAL',
                '9' => 'R$49REAL',
            )
        );
        $page->add($setting);
    }
    // Course ratings
    $setting = new admin_setting_configselect(
        'theme_evagu/course_ratings',
        get_string('course_ratings', 'theme_evagu'),
        get_string('course_ratings_desc', 'theme_evagu'),
        null,
        array(
            '0' => 'Hide all ratings',
            '1' => 'Show decorative ratings (always 5 stars)',
            '2' => 'Show real ratings (enable the [eva] Course Ratings block on course pages)',
            '3' => 'Hidden',
        )
    );
    $page->add($setting);
    // Modified on courses & course categories
    $setting = new admin_setting_configselect(
        'theme_evagu/coursecat_modified',
        get_string('coursecat_modified', 'theme_evagu'),
        get_string('coursecat_modified_desc', 'theme_evagu'),
        0,
        array(
            '0' => 'Visible',
            '1' => 'Hidden',
        )
    );
    $page->add($setting);
    // Enrolements on Courses & course categories
    $setting = new admin_setting_configselect(
        'theme_evagu/coursecat_enrolments',
        get_string('coursecat_enrolments', 'theme_evagu'),
        get_string('coursecat_enrolments_desc', 'theme_evagu'),
        0,
        array(
            '0' => 'Visible',
            '1' => 'Hidden',
        )
    );
    $page->add($setting);
    // Announcements on Course categories
    $setting = new admin_setting_configselect(
        'theme_evagu/coursecat_announcements',
        get_string('coursecat_announcements', 'theme_evagu'),
        get_string('coursecat_announcements_desc', 'theme_evagu'),
        0,
        array(
            '0' => 'Visible',
            '1' => 'Hidden',
        )
    );
    $page->add($setting);
    // Prices on Course categories
    $setting = new admin_setting_configselect(
        'theme_evagu/coursecat_prices',
        get_string('coursecat_prices', 'theme_evagu'),
        get_string('coursecat_prices_desc', 'theme_evagu'),
        0,
        array(
            '0' => 'Visible',
            '1' => 'Hidden',
        )
    );
    $page->add($setting);
    // Category settings
    $page->add(new admin_setting_heading('theme_evagu/coursecat_settings', get_string('coursecat_settings', 'theme_evagu'), NULL));
    // Course list style
    $setting = new admin_setting_configselect(
        'theme_evagu/courseliststyle',
        get_string('courseliststyle', 'theme_evagu'),
        get_string('courseliststyle_desc', 'theme_evagu'),
        null,
        array(
            '1' => 'Grid',
            '2' => 'List'
        )
    );
    $page->add($setting);
    // Single Course settings
    $page->add(new admin_setting_heading('theme_evagu/course_settings', get_string('single_course_settings', 'theme_evagu'), NULL));
    // Single Course Style
    $setting = new admin_setting_configselect(
        'theme_evagu/course_single_style',
        get_string('course_single_style', 'theme_evagu'),
        get_string('course_single_style_desc', 'theme_evagu'),
        0,
        array(
            '0' => 'Course v1',
            '1' => 'Course v2',
            '2' => 'Course v3',
        )
    );
    $page->add($setting);
    // Course Enrolment Settings
    $setting = new admin_setting_configselect(
        'theme_evagu/course_enrolment_payment',
        get_string('course_enrolment_payment', 'theme_evagu'),
        get_string('course_enrolment_payment_desc', 'theme_evagu'),
        0,
        array(
            '0' => 'All courses require payment',
            '1' => 'Some courses are free',
        )
    );
    $page->add($setting);
    // Single Course Block Settings
    $setting = new admin_setting_configselect(
        'theme_evagu/singlecourse_blocks',
        get_string('singlecourse_blocks', 'theme_evagu'),
        get_string('singlecourse_blocks_desc', 'theme_evagu'),
        0,
        array(
            '0' => 'Show on all pages of the course (Moodle default)',
            '1' => 'Show only on the main course page'
        )
    );
    $page->add($setting);
    // Course Start Date
    $setting = new admin_setting_configselect(
        'theme_evagu/course_start_date',
        get_string('course_start_date', 'theme_evagu'),
        get_string('course_start_date_desc', 'theme_evagu'),
        0,
        array(
            '0' => 'Visible',
            '1' => 'Hidden'
        )
    );
    $page->add($setting);
    // Course Category
    $setting = new admin_setting_configselect(
        'theme_evagu/course_category',
        get_string('course_category', 'theme_evagu'),
        get_string('course_category_desc', 'theme_evagu'),
        0,
        array(
            '0' => 'Visible',
            '1' => 'Hidden'
        )
    );
    $page->add($setting);
    // Enroled access to course content only
    $setting = new admin_setting_configselect(
        'theme_evagu/course_content_enroled_only',
        get_string('course_content_enroled_only', 'theme_evagu'),
        get_string('course_content_enroled_only_desc', 'theme_evagu'),
        0,
        array(
            '0' => 'Display course content to anyone with viewing access to the course',
            '1' => 'Display course content only to enroled users, course creators, managers and administrators',
        )
    );
    $page->add($setting);
    // Topics format settings
    $page->add(new admin_setting_heading('theme_evagu/course_settings_topics_format', get_string('course_settings_topics_format', 'theme_evagu'), NULL));
    if ($ccnMdlVersion < 400) {
        // Collapsible settings
        $setting = new admin_setting_configselect(
            'theme_evagu/topics_format_collapsible',
            get_string('topics_format_collapsible', 'theme_evagu'),
            get_string('topics_format_collapsible_desc', 'theme_evagu'),
            null,
            array(
                '0' => 'All collapsed by default',
                '1' => 'All collapsed, first expanded',
                '2' => 'All expanded by default',
                '3' => 'All expanded and not collapsible',
            )
        );
        $page->add($setting);
    }
    // Activity module settings
    $page->add(new admin_setting_heading('theme_evagu/course_settings_activities', get_string('course_settings_activities', 'theme_evagu'), NULL));
    // Quiz layout
    $setting = new admin_setting_configselect(
        'theme_evagu/quiz_layout',
        get_string('quiz_layout', 'theme_evagu'),
        get_string('quiz_layout_desc', 'theme_evagu'),
        null,
        array(
            '0' => 'Quiz navigation: Top',
            '1' => 'Quiz navigation: Right',
        )
    );
    $page->add($setting);
    $settings->add($page);
    // CCN Social settings
    $page = new admin_settingpage('theme_evagu_social_settings', get_string('social_settings', 'theme_evagu'));
    // New Window
    $setting = new admin_setting_configselect(
        'theme_evagu/social_target',
        get_string('social_target', 'theme_evagu'),
        get_string('social_target_desc', 'theme_evagu'),
        null,
        array(
            '0' => 'Open URLs in the same page',
            '1' => 'Open URLs in a new window'
        )
    );
    $page->add($setting);
    // Facebook URL
    $setting = new admin_setting_configtext('theme_evagu/eva_facebook_url', get_string('eva_facebook_url', 'theme_evagu'), get_string('eva_facebook_url_desc', 'theme_evagu'), '#', PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Twitter URL
    $setting = new admin_setting_configtext('theme_evagu/eva_twitter_url', get_string('eva_twitter_url', 'theme_evagu'), get_string('eva_twitter_url_desc', 'theme_evagu'), '#', PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Instagram URL
    $setting = new admin_setting_configtext('theme_evagu/eva_instagram_url', get_string('eva_instagram_url', 'theme_evagu'), get_string('eva_instagram_url_desc', 'theme_evagu'), '#', PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Pinterest URL
    $setting = new admin_setting_configtext('theme_evagu/eva_pinterest_url', get_string('eva_pinterest_url', 'theme_evagu'), get_string('eva_pinterest_url_desc', 'theme_evagu'), '#', PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Dribbble URL
    $setting = new admin_setting_configtext('theme_evagu/eva_dribbble_url', get_string('eva_dribbble_url', 'theme_evagu'), get_string('eva_dribbble_url_desc', 'theme_evagu'), '#', PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Google URL
    $setting = new admin_setting_configtext('theme_evagu/eva_google_url', get_string('eva_google_url', 'theme_evagu'), get_string('eva_google_url_desc', 'theme_evagu'), '#', PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // YouTube URL
    $setting = new admin_setting_configtext('theme_evagu/eva_youtube_url', get_string('eva_youtube_url', 'theme_evagu'), get_string('eva_youtube_url_desc', 'theme_evagu'), null, PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // VK URL
    $setting = new admin_setting_configtext('theme_evagu/eva_vk_url', get_string('eva_vk_url', 'theme_evagu'), get_string('eva_vk_url_desc', 'theme_evagu'), null, PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // 500px URL
    $setting = new admin_setting_configtext('theme_evagu/eva_500px_url', get_string('eva_500px_url', 'theme_evagu'), get_string('eva_500px_url_desc', 'theme_evagu'), null, PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Behance URL
    $setting = new admin_setting_configtext('theme_evagu/eva_behance_url', get_string('eva_behance_url', 'theme_evagu'), get_string('eva_behance_url_desc', 'theme_evagu'), null, PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Digg URL
    $setting = new admin_setting_configtext('theme_evagu/eva_digg_url', get_string('eva_digg_url', 'theme_evagu'), get_string('eva_digg_url_desc', 'theme_evagu'), null, PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Flickr URL
    $setting = new admin_setting_configtext('theme_evagu/eva_flickr_url', get_string('eva_flickr_url', 'theme_evagu'), get_string('eva_flickr_url_desc', 'theme_evagu'), null, PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Foursquare URL
    $setting = new admin_setting_configtext('theme_evagu/eva_foursquare_url', get_string('eva_foursquare_url', 'theme_evagu'), get_string('eva_foursquare_url_desc', 'theme_evagu'), null, PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // LinkedIn URL
    $setting = new admin_setting_configtext('theme_evagu/eva_linkedin_url', get_string('eva_linkedin_url', 'theme_evagu'), get_string('eva_linkedin_url_desc', 'theme_evagu'), null, PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Medium URL
    $setting = new admin_setting_configtext('theme_evagu/eva_medium_url', get_string('eva_medium_url', 'theme_evagu'), get_string('eva_medium_url_desc', 'theme_evagu'), null, PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Meetup URL
    $setting = new admin_setting_configtext('theme_evagu/eva_meetup_url', get_string('eva_meetup_url', 'theme_evagu'), get_string('eva_meetup_url_desc', 'theme_evagu'), null, PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Snapchat URL
    $setting = new admin_setting_configtext('theme_evagu/eva_snapchat_url', get_string('eva_snapchat_url', 'theme_evagu'), get_string('eva_snapchat_url_desc', 'theme_evagu'), null, PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Tumblr URL
    $setting = new admin_setting_configtext('theme_evagu/eva_tumblr_url', get_string('eva_tumblr_url', 'theme_evagu'), get_string('eva_tumblr_url_desc', 'theme_evagu'), null, PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Vimeo URL
    $setting = new admin_setting_configtext('theme_evagu/eva_vimeo_url', get_string('eva_vimeo_url', 'theme_evagu'), get_string('eva_vimeo_url_desc', 'theme_evagu'), null, PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // WeChat URL
    $setting = new admin_setting_configtext('theme_evagu/eva_wechat_url', get_string('eva_wechat_url', 'theme_evagu'), get_string('eva_wechat_url_desc', 'theme_evagu'), null, PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // WhatsApp URL
    $setting = new admin_setting_configtext('theme_evagu/eva_whatsapp_url', get_string('eva_whatsapp_url', 'theme_evagu'), get_string('eva_whatsapp_url_desc', 'theme_evagu'), null, PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // WordPress URL
    $setting = new admin_setting_configtext('theme_evagu/eva_wordpress_url', get_string('eva_wordpress_url', 'theme_evagu'), get_string('eva_wordpress_url_desc', 'theme_evagu'), null, PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Weibo URL
    $setting = new admin_setting_configtext('theme_evagu/eva_weibo_url', get_string('eva_weibo_url', 'theme_evagu'), get_string('eva_weibo_url_desc', 'theme_evagu'), null, PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Telegram URL
    $setting = new admin_setting_configtext('theme_evagu/eva_telegram_url', get_string('eva_telegram_url', 'theme_evagu'), get_string('eva_telegram_url_desc', 'theme_evagu'), null, PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Moodle URL
    $setting = new admin_setting_configtext('theme_evagu/eva_moodle_url', get_string('eva_moodle_url', 'theme_evagu'), get_string('eva_moodle_url_desc', 'theme_evagu'), null, PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Amazon URL
    $setting = new admin_setting_configtext('theme_evagu/eva_amazon_url', get_string('eva_amazon_url', 'theme_evagu'), get_string('eva_amazon_url_desc', 'theme_evagu'), null, PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Slideshare URL
    $setting = new admin_setting_configtext('theme_evagu/eva_slideshare_url', get_string('eva_slideshare_url', 'theme_evagu'), get_string('eva_slideshare_url_desc', 'theme_evagu'), null, PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // SoundCloud URL
    $setting = new admin_setting_configtext('theme_evagu/eva_soundcloud_url', get_string('eva_soundcloud_url', 'theme_evagu'), get_string('eva_soundcloud_url_desc', 'theme_evagu'), null, PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // LeanPub URL
    $setting = new admin_setting_configtext('theme_evagu/eva_leanpub_url', get_string('eva_leanpub_url', 'theme_evagu'), get_string('eva_leanpub_url_desc', 'theme_evagu'), null, PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Xing URL
    $setting = new admin_setting_configtext('theme_evagu/eva_xing_url', get_string('eva_xing_url', 'theme_evagu'), get_string('eva_xing_url_desc', 'theme_evagu'), null, PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Bitcoin URL
    $setting = new admin_setting_configtext('theme_evagu/eva_bitcoin_url', get_string('eva_bitcoin_url', 'theme_evagu'), get_string('eva_bitcoin_url_desc', 'theme_evagu'), null, PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Twitch URL
    $setting = new admin_setting_configtext('theme_evagu/eva_twitch_url', get_string('eva_twitch_url', 'theme_evagu'), get_string('eva_twitch_url_desc', 'theme_evagu'), null, PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Github URL
    $setting = new admin_setting_configtext('theme_evagu/eva_github_url', get_string('eva_github_url', 'theme_evagu'), get_string('eva_github_url_desc', 'theme_evagu'), null, PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Gitlab URL
    $setting = new admin_setting_configtext('theme_evagu/eva_gitlab_url', get_string('eva_gitlab_url', 'theme_evagu'), get_string('eva_gitlab_url_desc', 'theme_evagu'), null, PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Forumbee URL
    $setting = new admin_setting_configtext('theme_evagu/eva_forumbee_url', get_string('eva_forumbee_url', 'theme_evagu'), get_string('eva_forumbee_url_desc', 'theme_evagu'), null, PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Trello URL
    $setting = new admin_setting_configtext('theme_evagu/eva_trello_url', get_string('eva_trello_url', 'theme_evagu'), get_string('eva_trello_url_desc', 'theme_evagu'), null, PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Weixin URL
    $setting = new admin_setting_configtext('theme_evagu/eva_weixin_url', get_string('eva_weixin_url', 'theme_evagu'), get_string('eva_weixin_url_desc', 'theme_evagu'), null, PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Slack URL
    $setting = new admin_setting_configtext('theme_evagu/eva_slack_url', get_string('eva_slack_url', 'theme_evagu'), get_string('eva_slack_url_desc', 'theme_evagu'), null, PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    $settings->add($page);
    // CCN Color settings
    $page = new admin_settingpage('theme_evagu_color', get_string('color_settings', 'theme_evagu'));
    // Title: Gradients
    $page->add(new admin_setting_heading('theme_evagu/color_settings_gradient', get_string('color_settings_gradient', 'theme_evagu'), NULL));
    // Gradient Start
    $setting = new admin_setting_configcolourpicker('theme_evagu/color_gradient_start', get_string('color_gradient_start', 'theme_evagu'), get_string('color_gradient_start_desc', 'theme_evagu'), '#012950');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Gradient End
    $setting = new admin_setting_configcolourpicker('theme_evagu/color_gradient_end', get_string('color_gradient_end', 'theme_evagu'), get_string('color_gradient_end_desc', 'theme_evagu'), '#0359A5');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Title: Main colors
    $page->add(new admin_setting_heading('theme_evagu/color_settings_main', get_string('color_settings_main', 'theme_evagu'), NULL));
    // Primary Color
    $setting = new admin_setting_configcolourpicker('theme_evagu/color_primary', get_string('color_primary', 'theme_evagu'), get_string('color_primary_desc', 'theme_evagu'), '#035D9B');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Primary Color Alternate
    $setting = new admin_setting_configcolourpicker('theme_evagu/color_primary_alternate', get_string('color_primary_alternate', 'theme_evagu'), get_string('color_primary_alternate_desc', 'theme_evagu'), '#192675');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Secondary Color
    $setting = new admin_setting_configcolourpicker('theme_evagu/color_secondary', get_string('color_secondary', 'theme_evagu'), get_string('color_secondary_desc', 'theme_evagu'), '#012950');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Tertiary Color
    $setting = new admin_setting_configcolourpicker('theme_evagu/color_tertiary', get_string('color_tertiary', 'theme_evagu'), get_string('color_tertiary_desc', 'theme_evagu'), '#6c757d');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Accent Color
    $setting = new admin_setting_configcolourpicker('theme_evagu/color_accent', get_string('color_accent', 'theme_evagu'), get_string('color_accent_desc', 'theme_evagu'), '#e35a9a');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Accent Color 2
    $setting = new admin_setting_configcolourpicker('theme_evagu/color_accent_2', get_string('color_accent_2', 'theme_evagu'), get_string('color_accent_2_desc', 'theme_evagu'), '#c75533');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Accent Color 3
    $setting = new admin_setting_configcolourpicker('theme_evagu/color_accent_3', get_string('color_accent_3', 'theme_evagu'), get_string('color_accent_3_desc', 'theme_evagu'), '#192675');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Accent Color 4
    $setting = new admin_setting_configcolourpicker('theme_evagu/color_accent_4', get_string('color_accent_4', 'theme_evagu'), get_string('color_accent_4_desc', 'theme_evagu'), '#f0d078');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Parallax Color
    $setting = new admin_setting_configcolourpicker('theme_evagu/color_parallax', get_string('color_parallax', 'theme_evagu'), get_string('color_parallax_desc', 'theme_evagu'), '#035D9B');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Title: Header Style 2
    $page->add(new admin_setting_heading('theme_evagu/color_settings_header_style_2', get_string('color_settings_header_style_2', 'theme_evagu'), NULL));
    // Header Style 2: Header Top
    $setting = new admin_setting_configcolourpicker('theme_evagu/color_header_style_2_top', get_string('color_header_color_top', 'theme_evagu'), get_string('color_header_color_top_desc', 'theme_evagu'), '#000');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Header Style 2: Header Bottom
    $setting = new admin_setting_configcolourpicker('theme_evagu/color_header_style_2_bottom', get_string('color_header_color_bottom', 'theme_evagu'), get_string('color_header_color_bottom_desc', 'theme_evagu'), '#141414');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Title: Header Style 3
    $page->add(new admin_setting_heading('theme_evagu/color_settings_header_style_3', get_string('color_settings_header_style_3', 'theme_evagu'), NULL));
    // Header Style 3: Header Top
    $setting = new admin_setting_configcolourpicker('theme_evagu/color_header_style_3_top', get_string('color_header_color_top', 'theme_evagu'), get_string('color_header_color_top_desc', 'theme_evagu'), '#051925');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Title: Header Style 4
    $page->add(new admin_setting_heading('theme_evagu/color_settings_header_style_4', get_string('color_settings_header_style_4', 'theme_evagu'), NULL));
    // Header Style 4: Header Top
    $setting = new admin_setting_configcolourpicker('theme_evagu/color_header_style_4_top', get_string('color_header_color_top', 'theme_evagu'), get_string('color_header_color_top_desc', 'theme_evagu'), '#0359A5');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Title: Header Style 5
    $page->add(new admin_setting_heading('theme_evagu/color_settings_header_style_5', get_string('color_settings_header_style_5', 'theme_evagu'), NULL));
    // Header Style 5
    $setting = new admin_setting_configcolourpicker('theme_evagu/color_header_style_5', get_string('color_header_color', 'theme_evagu'), get_string('color_header_color_desc', 'theme_evagu'), '#ffffff');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Title: Header Style 6
    $page->add(new admin_setting_heading('theme_evagu/color_settings_header_style_6', get_string('color_settings_header_style_6', 'theme_evagu'), NULL));
    // Header Style 6: Header Top
    $setting = new admin_setting_configcolourpicker('theme_evagu/color_header_style_6_top', get_string('color_header_color_top', 'theme_evagu'), get_string('color_header_color_top_desc', 'theme_evagu'), '#0359A5');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Title: Footer Style 1
    $page->add(new admin_setting_heading('theme_evagu/color_settings_footer_style_1', get_string('color_settings_footer_style_1', 'theme_evagu'), NULL));
    // Footer Style 1: Footer Top
    $setting = new admin_setting_configcolourpicker('theme_evagu/color_footer_style_1_top', get_string('color_footer_color_top', 'theme_evagu'), get_string('color_footer_color_top_desc', 'theme_evagu'), '#151515');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Footer Style 1: Footer Bottom
    $setting = new admin_setting_configcolourpicker('theme_evagu/color_footer_style_1_bottom', get_string('color_footer_color_bottom', 'theme_evagu'), get_string('color_footer_color_bottom_desc', 'theme_evagu'), '#0a0a0a');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Title: Footer Style 2
    $page->add(new admin_setting_heading('theme_evagu/color_settings_footer_style_2', get_string('color_settings_footer_style_2', 'theme_evagu'), NULL));
    // Footer Style 2: Footer Top
    $setting = new admin_setting_configcolourpicker('theme_evagu/color_footer_style_2_top', get_string('color_footer_color_top', 'theme_evagu'), get_string('color_footer_color_top_desc', 'theme_evagu'), '#f9fafc');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Footer Style 2: Footer Bottom
    $setting = new admin_setting_configcolourpicker('theme_evagu/color_footer_style_2_bottom', get_string('color_footer_color_bottom', 'theme_evagu'), get_string('color_footer_color_bottom_desc', 'theme_evagu'), '#ebeef4');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Title: Footer Style 3
    $page->add(new admin_setting_heading('theme_evagu/color_settings_footer_style_3', get_string('color_settings_footer_style_3', 'theme_evagu'), NULL));
    // Footer Style 3: Footer Top
    $setting = new admin_setting_configcolourpicker('theme_evagu/color_footer_style_3_top', get_string('color_footer_color_top', 'theme_evagu'), get_string('color_footer_color_top_desc', 'theme_evagu'), '#f9fafc');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Footer Style 3: Footer Middle
    $setting = new admin_setting_configcolourpicker('theme_evagu/color_footer_style_3_middle', get_string('color_footer_color_middle', 'theme_evagu'), get_string('color_footer_color_middle_desc', 'theme_evagu'), '#ffffff');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Footer Style 3: Footer Bottom
    $setting = new admin_setting_configcolourpicker('theme_evagu/color_footer_style_3_bottom', get_string('color_footer_color_bottom', 'theme_evagu'), get_string('color_footer_color_bottom_desc', 'theme_evagu'), '#fafafa');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Title: Footer Style 5
    $page->add(new admin_setting_heading('theme_evagu/color_settings_footer_style_5', get_string('color_settings_footer_style_5', 'theme_evagu'), NULL));
    // Footer Style 5: Footer Top
    $setting = new admin_setting_configcolourpicker('theme_evagu/color_footer_style_5_top', get_string('color_footer_color_top', 'theme_evagu'), get_string('color_footer_color_top_desc', 'theme_evagu'), '#0d2f81');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Footer Style 5: Footer Bottom
    $setting = new admin_setting_configcolourpicker('theme_evagu/color_footer_style_5_bottom', get_string('color_footer_color_bottom', 'theme_evagu'), get_string('color_footer_color_bottom_desc', 'theme_evagu'), '#072670');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Title: Footer Style 6
    $page->add(new admin_setting_heading('theme_evagu/color_settings_footer_style_6', get_string('color_settings_footer_style_6', 'theme_evagu'), NULL));
    // Footer Style 6: Footer All
    $setting = new admin_setting_configcolourpicker('theme_evagu/color_footer_style_6_all', get_string('color_footer_color', 'theme_evagu'), get_string('color_footer_color_desc', 'theme_evagu'), '#3f4449');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Title: Footer Style 7
    $page->add(new admin_setting_heading('theme_evagu/color_settings_footer_style_7', get_string('color_settings_footer_style_7', 'theme_evagu'), NULL));
    // Footer Style 7: Footer Top
    $setting = new admin_setting_configcolourpicker('theme_evagu/color_footer_style_7_top', get_string('color_footer_color_top', 'theme_evagu'), get_string('color_footer_color_top_desc', 'theme_evagu'), '#ffffff');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Footer Style 7: Footer Bottom
    $setting = new admin_setting_configcolourpicker('theme_evagu/color_footer_style_7_bottom', get_string('color_footer_color_bottom', 'theme_evagu'), get_string('color_footer_color_bottom_desc', 'theme_evagu'), '#ffffff');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    $settings->add($page);
    // CCN Dashboard settings
    $page = new admin_settingpage('theme_evagu_dashboard', get_string('dashboard_settings', 'theme_evagu'));
    // Title: Dashboard settings
    $page->add(new admin_setting_heading('theme_evagu/dashboard_settings', get_string('dashboard_settings_long', 'theme_evagu'), NULL));
    // Dashboard header
    $setting = new admin_setting_configselect(
        'theme_evagu/dashboard_header',
        get_string('dashboard_header', 'theme_evagu'),
        get_string('dashboard_header_desc', 'theme_evagu'),
        0,
        array(
            '0' => 'Gradient',
            '1' => 'White'
        )
    );
    $page->add($setting);
    // Sticky header
    $setting = new admin_setting_configselect(
        'theme_evagu/dashboard_sticky_header',
        get_string('dashboard_sticky_header', 'theme_evagu'),
        get_string('dashboard_sticky_header_desc', 'theme_evagu'),
        0,
        array(
            '0' => 'Stick to top',
            '1' => 'Scroll with page'
        )
    );
    $page->add($setting);
    // Sticky left drawer
    $setting = new admin_setting_configselect(
        'theme_evagu/dashboard_sticky_drawer',
        get_string('dashboard_sticky_drawer', 'theme_evagu'),
        get_string('dashboard_sticky_drawer_desc', 'theme_evagu'),
        0,
        array(
            '0' => 'Stick to side',
            '1' => 'Scroll with page'
        )
    );
    $page->add($setting);
    // Dashboard left drawer
    $setting = new admin_setting_configselect(
        'theme_evagu/dashboard_left_drawer',
        get_string('dashboard_left_drawer', 'theme_evagu'),
        get_string('dashboard_left_drawer_desc', 'theme_evagu'),
        0,
        array(
            '0' => 'User menu (default)',
            '3' => 'Moodle drawer navigation',
            '1' => 'Only show blocks from "Sidebar Left" region',
            '2' => 'Disable left drawer'
        )
    );
    $page->add($setting);
    // Dashboard Breadcrumb clip text
    $setting = new admin_setting_configselect(
        'theme_evagu/breadcrumb_clip_dash',
        get_string('breadcrumb_clip', 'theme_evagu'),
        get_string('breadcrumb_clip_desc', 'theme_evagu'),
        0,
        array(
            '0' => 'Clip long text',
            '1' => 'Clip very long text only',
            '2' => 'Do not clip text',
        )
    );
    $page->add($setting);
    // Title: Dashboard tablet 1
    $page->add(new admin_setting_heading('theme_evagu/dashboard_tablet_1', get_string('dashboard_tablet_1', 'theme_evagu'), NULL));
    // Dashboard tablet visibility
    $setting = new admin_setting_configselect(
        'theme_evagu/dashboard_tablet_1_visibility',
        get_string('config_visibility', 'theme_evagu'),
        get_string('config_visibility_desc', 'theme_evagu'),
        0,
        array(
            '0' => 'Show tablet',
            '1' => 'Hidden tablet'
        )
    );
    $page->add($setting);
    // Dashboard tablet title
    $setting = new admin_setting_configtext('theme_evagu/dashboard_tablet_1_title', get_string('config_title', 'theme_evagu'), get_string('config_title_desc', 'theme_evagu'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Dashboard tablet subtitle
    $setting = new admin_setting_configtext('theme_evagu/dashboard_tablet_1_subtitle', get_string('config_subtitle', 'theme_evagu'), get_string('config_subtitle_desc', 'theme_evagu'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Dashboard tablet URL
    $setting = new admin_setting_configtext('theme_evagu/dashboard_tablet_1_url', get_string('config_link', 'theme_evagu'), get_string('config_link_desc', 'theme_evagu'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Dashboard tablet color
    $setting = new admin_setting_configcolourpicker('theme_evagu/dashboard_tablet_1_color', get_string('config_color', 'theme_evagu'), get_string('config_color_desc', 'theme_evagu'), '#035D9B');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Dashboard tablet icon
    $setting = new admin_setting_configselect(
        'theme_evagu/dashboard_tablet_1_ccn_icon_class',
        get_string('config_icon_class', 'theme_evagu'),
        get_string('config_icon_class_desc', 'theme_evagu'),
        'flaticon-speech-bubble',
        $ccnFontList
    );
    $page->add($setting);
    // Title: Dashboard tablet 2
    $page->add(new admin_setting_heading('theme_evagu/dashboard_tablet_2', get_string('dashboard_tablet_2', 'theme_evagu'), NULL));
    // Dashboard tablet visibility
    $setting = new admin_setting_configselect(
        'theme_evagu/dashboard_tablet_2_visibility',
        get_string('config_visibility', 'theme_evagu'),
        get_string('config_visibility_desc', 'theme_evagu'),
        0,
        array(
            '0' => 'Show tablet',
            '1' => 'Hidden tablet'
        )
    );
    $page->add($setting);
    // Dashboard tablet title
    $setting = new admin_setting_configtext('theme_evagu/dashboard_tablet_2_title', get_string('config_title', 'theme_evagu'), get_string('config_title_desc', 'theme_evagu'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Dashboard tablet subtitle
    $setting = new admin_setting_configtext('theme_evagu/dashboard_tablet_2_subtitle', get_string('config_subtitle', 'theme_evagu'), get_string('config_subtitle_desc', 'theme_evagu'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Dashboard tablet URL
    $setting = new admin_setting_configtext('theme_evagu/dashboard_tablet_2_url', get_string('config_link', 'theme_evagu'), get_string('config_link_desc', 'theme_evagu'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Dashboard tablet color
    $setting = new admin_setting_configcolourpicker('theme_evagu/dashboard_tablet_2_color', get_string('config_color', 'theme_evagu'), get_string('config_color_desc', 'theme_evagu'), '#012950');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Dashboard tablet icon
    $setting = new admin_setting_configselect(
        'theme_evagu/dashboard_tablet_2_ccn_icon_class',
        get_string('config_icon_class', 'theme_evagu'),
        get_string('config_icon_class_desc', 'theme_evagu'),
        'flaticon-cap',
        $ccnFontList
    );
    $page->add($setting);
    // Title: Dashboard tablet 3
    $page->add(new admin_setting_heading('theme_evagu/dashboard_tablet_3', get_string('dashboard_tablet_3', 'theme_evagu'), NULL));
    // Dashboard tablet visibility
    $setting = new admin_setting_configselect(
        'theme_evagu/dashboard_tablet_3_visibility',
        get_string('config_visibility', 'theme_evagu'),
        get_string('config_visibility_desc', 'theme_evagu'),
        0,
        array(
            '0' => 'Show tablet',
            '1' => 'Hidden tablet'
        )
    );
    $page->add($setting);
    // Dashboard tablet title
    $setting = new admin_setting_configtext('theme_evagu/dashboard_tablet_3_title', get_string('config_title', 'theme_evagu'), get_string('config_title_desc', 'theme_evagu'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Dashboard tablet subtitle
    $setting = new admin_setting_configtext('theme_evagu/dashboard_tablet_3_subtitle', get_string('config_subtitle', 'theme_evagu'), get_string('config_subtitle_desc', 'theme_evagu'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Dashboard tablet URL
    $setting = new admin_setting_configtext('theme_evagu/dashboard_tablet_3_url', get_string('config_link', 'theme_evagu'), get_string('config_link_desc', 'theme_evagu'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Dashboard tablet color
    $setting = new admin_setting_configcolourpicker('theme_evagu/dashboard_tablet_3_color', get_string('config_color', 'theme_evagu'), get_string('config_color_desc', 'theme_evagu'), '#00a78e');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Dashboard tablet icon
    $setting = new admin_setting_configselect(
        'theme_evagu/dashboard_tablet_3_ccn_icon_class',
        get_string('config_icon_class', 'theme_evagu'),
        get_string('config_icon_class_desc', 'theme_evagu'),
        'flaticon-settings',
        $ccnFontList
    );
    $page->add($setting);
    // Title: Dashboard tablet 4
    $page->add(new admin_setting_heading('theme_evagu/dashboard_tablet_4', get_string('dashboard_tablet_4', 'theme_evagu'), NULL));
    // Dashboard tablet visibility
    $setting = new admin_setting_configselect(
        'theme_evagu/dashboard_tablet_4_visibility',
        get_string('config_visibility', 'theme_evagu'),
        get_string('config_visibility_desc', 'theme_evagu'),
        0,
        array(
            '0' => 'Show tablet',
            '1' => 'Hide tablet'
        )
    );
    $page->add($setting);
    // Dashboard tablet title
    $setting = new admin_setting_configtext('theme_evagu/dashboard_tablet_4_title', get_string('config_title', 'theme_evagu'), get_string('config_title_desc', 'theme_evagu'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Dashboard tablet subtitle
    $setting = new admin_setting_configtext('theme_evagu/dashboard_tablet_4_subtitle', get_string('config_subtitle', 'theme_evagu'), get_string('config_subtitle_desc', 'theme_evagu'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Dashboard tablet URL
    $setting = new admin_setting_configtext('theme_evagu/dashboard_tablet_4_url', get_string('config_link', 'theme_evagu'), get_string('config_link_desc', 'theme_evagu'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Dashboard tablet color
    $setting = new admin_setting_configcolourpicker('theme_evagu/dashboard_tablet_4_color', get_string('config_color', 'theme_evagu'), get_string('config_color_desc', 'theme_evagu'), '#ecd06f');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Dashboard tablet icon
    $setting = new admin_setting_configselect(
        'theme_evagu/dashboard_tablet_4_ccn_icon_class',
        get_string('config_icon_class', 'theme_evagu'),
        get_string('config_icon_class_desc', 'theme_evagu'),
        'flaticon-rating',
        $ccnFontList
    );
    $page->add($setting);
    $settings->add($page);
    // CCN User settings
    $page = new admin_settingpage('theme_evagu_user_settings', get_string('user_settings', 'theme_evagu'));
    // Login pages
    // Login Layout
    $setting = new admin_setting_configselect(
        'theme_evagu/login_layout',
        get_string('login_layout', 'theme_evagu'),
        get_string('login_layout_desc', 'theme_evagu'),
        0,
        array(
            '0' => 'Style 1 (default)',
            '1' => 'Style 2',
        )
    );
    $page->add($setting);
    // Breadcrumb background
    $name = 'theme_evagu/login_bg';
    $title = get_string('login_bg', 'theme_evagu');
    $description = get_string('login_bg_desc', 'theme_evagu');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'login_bg');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Navigation icon
    $page->add(new admin_setting_heading('theme_evagu/navigation_icon', get_string('navigation_icon', 'theme_evagu'), NULL));
    $setting = new admin_setting_configselect(
        'theme_evagu/navigation_icon_visibility',
        get_string('config_visibility', 'theme_evagu'),
        get_string('config_visibility_desc', 'theme_evagu'),
        0,
        array(
            '0' => 'Visible',
            '1' => 'Hidden'
        )
    );
    $page->add($setting);
    $setting = new admin_setting_configselect(
        'theme_evagu/navigation_icon_ccn_icon_class',
        get_string('config_icon_class', 'theme_evagu'),
        get_string('config_icon_class_desc', 'theme_evagu'),
        'flaticon-settings',
        $ccnFontList
    );
    $page->add($setting);
    // Notification icon
    $page->add(new admin_setting_heading('theme_evagu/notification_icon', get_string('notification_icon', 'theme_evagu'), NULL));
    $setting = new admin_setting_configselect(
        'theme_evagu/notification_icon_visibility',
        get_string('config_visibility', 'theme_evagu'),
        get_string('config_visibility_desc', 'theme_evagu'),
        0,
        array(
            '0' => 'Visible',
            '1' => 'Hidden'
        )
    );
    $page->add($setting);
    $setting = new admin_setting_configselect(
        'theme_evagu/notification_icon_ccn_icon_class',
        get_string('config_icon_class', 'theme_evagu'),
        get_string('config_icon_class_desc', 'theme_evagu'),
        'flaticon-alarm',
        $ccnFontList
    );
    $page->add($setting);
    // Messages icon
    $page->add(new admin_setting_heading('theme_evagu/messages_icon', get_string('messages_icon', 'theme_evagu'), NULL));
    $setting = new admin_setting_configselect(
        'theme_evagu/messages_icon_visibility',
        get_string('config_visibility', 'theme_evagu'),
        get_string('config_visibility_desc', 'theme_evagu'),
        0,
        array(
            '0' => 'Visible',
            '1' => 'Hidden'
        )
    );
    $page->add($setting);
    $setting = new admin_setting_configselect(
        'theme_evagu/messages_icon_ccn_icon_class',
        get_string('config_icon_class', 'theme_evagu'),
        get_string('config_icon_class_desc', 'theme_evagu'),
        'flaticon-speech-bubble',
        $ccnFontList
    );
    $page->add($setting);
    // Navigation icon
    $page->add(new admin_setting_heading('theme_evagu/dark_mode_icon', get_string('dark_mode_icon', 'theme_evagu'), NULL));
    $setting = new admin_setting_configselect(
        'theme_evagu/dark_mode_icon_visibility',
        get_string('config_visibility', 'theme_evagu'),
        get_string('config_visibility_desc', 'theme_evagu'),
        0,
        array(
            '0' => 'Visible',
            '1' => 'Hidden'
        )
    );
    $page->add($setting);
    $setting = new admin_setting_configselect(
        'theme_evagu/dark_mode_icon_ccn_icon_class',
        get_string('config_icon_class', 'theme_evagu'),
        get_string('config_icon_class_desc', 'theme_evagu'),
        'ccn-flaticon-hide',
        $ccnFontList
    );
    $page->add($setting);
    // Profile icon
    $page->add(new admin_setting_heading('theme_evagu/profile_icon', get_string('profile_icon', 'theme_evagu'), NULL));
    $setting = new admin_setting_configselect(
        'theme_evagu/profile_icon_username',
        get_string('profile_icon_username', 'theme_evagu'),
        get_string('profile_icon_username_desc', 'theme_evagu'),
        0,
        array(
            '0' => 'Username',
            '1' => 'Full name'
        )
    );
    $page->add($setting);
    // Order receipts
    $page->add(new admin_setting_heading('theme_evagu/order_receipts', get_string('order_receipts', 'theme_evagu'), NULL));
    $setting = new admin_setting_configtext('theme_evagu/order_receipt_address_line_1', get_string('address_line_1', 'theme_evagu'), get_string('address_line_1_desc', 'theme_evagu'), 'Brasília', PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    $setting = new admin_setting_configtext('theme_evagu/order_receipt_address_line_2', get_string('address_line_2', 'theme_evagu'), get_string('address_line_2_desc', 'theme_evagu'), 'Distrito Federal', PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    $setting = new admin_setting_configtext('theme_evagu/order_receipt_address_line_3', get_string('address_line_3', 'theme_evagu'), get_string('address_line_3_desc', 'theme_evagu'), 'Brasil', PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    $setting = new admin_setting_configtext('theme_evagu/order_receipt_zip', get_string('zip_code', 'theme_evagu'), get_string('zip_code_desc', 'theme_evagu'), '71000-00', PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    $setting = new admin_setting_configtext('theme_evagu/order_receipt_phone', get_string('phone', 'theme_evagu'), get_string('phone_desc', 'theme_evagu'), '+55 61 2548-2525', PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    $setting = new admin_setting_configtext('theme_evagu/order_receipt_email', get_string('email_address', 'theme_evagu'), get_string('email_address_desc', 'theme_evagu'), 'eva@agu,gov.br', PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    $settings->add($page);
    // Fonts
    $page = new admin_settingpage('theme_evagu_fonts', get_string('font_settings', 'theme_evagu'));
    // Google Fonts
    $page->add(new admin_setting_heading('theme_evagu/google_fonts', get_string('google_fonts', 'theme_evagu'), NULL));
    // Primary Font
    $setting = new admin_setting_configselect(
        'theme_evagu/primary_font',
        get_string('primary_font', 'theme_evagu'),
        get_string('primary_font_desc', 'theme_evagu'),
        null,
        array(
            '0' => 'Nunito',
            '1' => 'Rawline',
            '2' => 'Lato',
            '3' => 'Maven Pro',
            '4' => 'Montserrat',
            '5' => 'Open Sans',
            '6' => 'Playfair Display',
            '7' => 'Poppins',
            '8' => 'Raleway',
            '9' => 'Roboto',
            '10' => 'Lora',
            '11' => 'Oxygen',
        )
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Secondary Font
    $setting = new admin_setting_configselect(
        'theme_evagu/secondary_font',
        get_string('secondary_font', 'theme_evagu'),
        get_string('secondary_font_desc', 'theme_evagu'),
        5,
        array(
            '0' => 'Nunito',
            '1' => 'Rawline',
            '2' => 'Lato',
            '3' => 'Maven Pro',
            '4' => 'Montserrat',
            '5' => 'Open Sans',
            '6' => 'Playfair Display',
            '7' => 'Poppins',
            '8' => 'Raleway',
            '9' => 'Roboto',
            '10' => 'Lora',
            '11' => 'Oxygen',
        )
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Custom Primary Fonts
    $page->add(new admin_setting_heading('theme_evagu/custom_font_primary', get_string('custom_font_primary', 'theme_evagu'), NULL));
    // Upload font EOT
    $name = 'theme_evagu/upload_font_eot';
    $title = get_string('upload_font_eot', 'theme_evagu');
    $description = get_string('upload_font_eot_desc', 'theme_evagu');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'upload_font_eot', 0, array('maxfiles' => 1, 'accepted_types' => array('.eot')));
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Upload font WOFF2
    $name = 'theme_evagu/upload_font_woff2';
    $title = get_string('upload_font_woff2', 'theme_evagu');
    $description = get_string('upload_font_woff2_desc', 'theme_evagu');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'upload_font_woff2', 0, array('maxfiles' => 1, 'accepted_types' => array('.woff2')));
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Upload font WOFF
    $name = 'theme_evagu/upload_font_woff';
    $title = get_string('upload_font_woff', 'theme_evagu');
    $description = get_string('upload_font_woff_desc', 'theme_evagu');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'upload_font_woff', 0, array('maxfiles' => 1, 'accepted_types' => array('.woff')));
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Upload font TTF
    $name = 'theme_evagu/upload_font_ttf';
    $title = get_string('upload_font_ttf', 'theme_evagu');
    $description = get_string('upload_font_ttf_desc', 'theme_evagu');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'upload_font_ttf', 0, array('maxfiles' => 1, 'accepted_types' => array('.ttf')));
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Upload font SVG
    $name = 'theme_evagu/upload_font_svg';
    $title = get_string('upload_font_svg', 'theme_evagu');
    $description = get_string('upload_font_svg_desc', 'theme_evagu');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'upload_font_svg', 0, array('maxfiles' => 1, 'accepted_types' => array('.svg')));
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Custom Secondary Fonts
    $page->add(new admin_setting_heading('theme_evagu/custom_font_secondary', get_string('custom_font_secondary', 'theme_evagu'), NULL));
    // Upload font EOT
    $name = 'theme_evagu/upload_font_secondary_eot';
    $title = get_string('upload_font_eot', 'theme_evagu');
    $description = get_string('upload_font_eot_desc', 'theme_evagu');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'upload_font_secondary_eot', 0, array('maxfiles' => 1, 'accepted_types' => array('.eot')));
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Upload font WOFF2
    $name = 'theme_evagu/upload_font_secondary_woff2';
    $title = get_string('upload_font_woff2', 'theme_evagu');
    $description = get_string('upload_font_woff2_desc', 'theme_evagu');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'upload_font_secondary_woff2', 0, array('maxfiles' => 1, 'accepted_types' => array('.woff2')));
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Upload font WOFF
    $name = 'theme_evagu/upload_font_secondary_woff';
    $title = get_string('upload_font_woff', 'theme_evagu');
    $description = get_string('upload_font_woff_desc', 'theme_evagu');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'upload_font_secondary_woff', 0, array('maxfiles' => 1, 'accepted_types' => array('.woff')));
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Upload font TTF
    $name = 'theme_evagu/upload_font_secondary_ttf';
    $title = get_string('upload_font_ttf', 'theme_evagu');
    $description = get_string('upload_font_ttf_desc', 'theme_evagu');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'upload_font_secondary_ttf', 0, array('maxfiles' => 1, 'accepted_types' => array('.ttf')));
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Upload font SVG
    $name = 'theme_evagu/upload_font_secondary_svg';
    $title = get_string('upload_font_svg', 'theme_evagu');
    $description = get_string('upload_font_svg_desc', 'theme_evagu');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'upload_font_secondary_svg', 0, array('maxfiles' => 1, 'accepted_types' => array('.svg')));
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    $settings->add($page);
    // CCN Layout settings
    $page = new admin_settingpage('theme_evagu_layout', get_string('layout_settings', 'theme_evagu'));
    // Dashboard Layout
    $setting = new admin_setting_configselect(
        'theme_evagu/dashboard_layout',
        get_string('dashboard_layout', 'theme_evagu'),
        get_string('dashboard_layout_desc', 'theme_evagu'),
        0,
        array(
            '0' => 'evagu Dashboard (default)',
            '1' => 'evagu',
        )
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Course Main Page Layout
    $setting = new admin_setting_configselect(
        'theme_evagu/coursemainpage_layout',
        get_string('coursemainpage_layout', 'theme_evagu'),
        get_string('coursemainpage_layout_desc', 'theme_evagu'),
        0,
        array(
            '0' => 'evagu (default) - recommended',
            '1' => 'evagu Dashboard for enrolled users only',
            '2' => 'evagu Dashboard for all users',
            '3' => 'evagu Focus for enrolled users only',
            '4' => 'evagu Focus for all users',
        )
    );
    $page->add($setting);
    // Inner Course Page Layout
    $setting = new admin_setting_configselect(
        'theme_evagu/incourse_layout',
        get_string('incourse_layout', 'theme_evagu'),
        get_string('incourse_layout_desc', 'theme_evagu'),
        0,
        array(
            '0' => 'evagu (default)',
            '1' => 'evagu Dashboard',
            '2' => 'evagu Focus'
        )
    );
    $page->add($setting);
    // Profile Page Layout
    $setting = new admin_setting_configselect(
        'theme_evagu/user_profile_layout',
        get_string('user_profile_layout', 'theme_evagu'),
        get_string('user_profile_layout_desc', 'theme_evagu'),
        0,
        array(
            '0' => 'evagu (default)',
            '1' => 'evagu Dashboard'
        )
    );
    $page->add($setting);
    // Title: evagu Focus
    $page->add(new admin_setting_heading('theme_evagu/evagu_focus', get_string('evagu_focus', 'theme_evagu'), get_string('evagu_focus_desc', 'theme_evagu')));
    $setting = new admin_setting_configselect(
        'theme_evagu/evagu_focus_sidebar',
        get_string('evagu_focus_sidebar', 'theme_evagu'),
        get_string('evagu_focus_sidebar_desc', 'theme_evagu'),
        '0',
        array(
            '0' => 'Display sidebar',
            '1' => 'Hide sidebar'
        )
    );
    $page->add($setting);
    // Title: Homepage
    $page->add(new admin_setting_heading('theme_evagu/evagu_homepage', get_string('evagu_homepage', 'theme_evagu'), get_string('evagu_homepage_desc', 'theme_evagu')));
    $setting = new admin_setting_configselect(
        'theme_evagu/evagu_homepage_core',
        get_string('evagu_homepage_core', 'theme_evagu'),
        get_string('evagu_homepage_core_desc', 'theme_evagu'),
        '0',
        array(
            '0' => 'Website & Moodle Mobile App',
            '1' => 'Moodle Mobile App'
        )
    );
    $page->add($setting);
    $settings->add($page);
    // CCN Optimization
    $page = new admin_settingpage('theme_evagu_optimization', get_string('optimization_settings', 'theme_evagu'));
    // Lazy Loading
    $setting = new admin_setting_configselect(
        'theme_evagu/lazy_loading',
        get_string('lazy_loading', 'theme_evagu'),
        get_string('lazy_loading_desc', 'theme_evagu'),
        0,
        array(
            '0' => 'Yes (default)',
            '1' => 'No',
        )
    );
    $page->add($setting);
    $settings->add($page);
    // CCN Advanced settings
    $page = new admin_settingpage('theme_evagu_advanced', get_string('advanced_settings', 'theme_evagu'));
    // Google Maps API Key
    $setting = new admin_setting_configtext('theme_evagu/gmaps_key', get_string('gmaps_key', 'theme_evagu'), get_string('gmaps_key_desc', 'theme_evagu'), '', PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Custom CSS
    $setting = new admin_setting_configtextarea('theme_evagu/custom_css', get_string('custom_css', 'theme_evagu'), get_string('custom_css_desc', 'theme_evagu'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Custom CSS Dashboard
    $setting = new admin_setting_configtextarea('theme_evagu/custom_css_dashboard', get_string('custom_css_dashboard', 'theme_evagu'), get_string('custom_css_dashboard_desc', 'theme_evagu'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Custom JavaScript
    $setting = new admin_setting_configtextarea('theme_evagu/custom_js', get_string('custom_js', 'theme_evagu'), get_string('custom_js_desc', 'theme_evagu'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Custom JavaScript Dashboard
    $setting = new admin_setting_configtextarea('theme_evagu/custom_js_dashboard', get_string('custom_js_dashboard', 'theme_evagu'), get_string('custom_js_dashboard_desc', 'theme_evagu'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Blog Post Author
    $setting = new admin_setting_configselect(
        'theme_evagu/blog_post_author',
        get_string('blog_post_author', 'theme_evagu'),
        get_string('blog_post_author_desc', 'theme_evagu'),
        null,
        array(
            '0' => 'Visible',
            '1' => 'Hidden'
        )
    );
    $page->add($setting);
    // Blog Post Date
    $setting = new admin_setting_configselect(
        'theme_evagu/blog_post_date',
        get_string('blog_post_date', 'theme_evagu'),
        get_string('blog_post_date_desc', 'theme_evagu'),
        null,
        array(
            '0' => 'Visible',
            '1' => 'Hidden'
        )
    );
    $page->add($setting);
    // Page Settings button
    $setting = new admin_setting_configselect(
        'theme_evagu/page_settings_controls',
        get_string('page_settings_controls', 'theme_evagu'),
        get_string('page_settings_controls_desc', 'theme_evagu'),
        null,
        array(
            '0' => 'Moodle default (visible based on permissions)',
            '1' => 'Visible only to course creators, managers and administrators'
        )
    );
    $page->add($setting);
    // Google Maps API Key
    $setting = new admin_setting_configtext('theme_evagu/logo_url', get_string('logo_url', 'theme_evagu'), get_string('logo_url_desc', 'theme_evagu'), '', PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Title: Icons
    $page->add(new admin_setting_heading('theme_evagu/icons', get_string('icons', 'theme_evagu'), get_string('icons_desc', 'theme_evagu')));
    $setting = new admin_setting_configcheckbox(
        'theme_evagu/iconset_evagu',
        get_string('iconset_evagu', 'theme_evagu'),
        '',
        1
    );
    $page->add($setting);
    $setting = new admin_setting_configcheckbox(
        'theme_evagu/iconset_eva',
        get_string('iconset_eva', 'theme_evagu'),
        '',
        1
    );
    $page->add($setting);
    $setting = new admin_setting_configcheckbox(
        'theme_evagu/iconset_fontawesome',
        get_string('iconset_fontawesome', 'theme_evagu'),
        '',
        1
    );
    $page->add($setting);
    $setting = new admin_setting_configcheckbox(
        'theme_evagu/iconset_lineawesome',
        get_string('iconset_lineawesome', 'theme_evagu'),
        '',
        1
    );
    $page->add($setting);
    // Title: SEO
    $page->add(new admin_setting_heading('theme_evagu/seo', get_string('seo', 'theme_evagu'), NULL));
    // Meta Description
    $setting = new admin_setting_configtextarea('theme_evagu/meta_description', get_string('meta_description', 'theme_evagu'), get_string('meta_description_desc', 'theme_evagu'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Meta Abstract
    $setting = new admin_setting_configtextarea('theme_evagu/meta_abstract', get_string('meta_abstract', 'theme_evagu'), get_string('meta_abstract_desc', 'theme_evagu'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Meta Keywords
    $setting = new admin_setting_configtext('theme_evagu/meta_keywords', get_string('meta_keywords', 'theme_evagu'), get_string('meta_keywords_desc', 'theme_evagu'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Title: For Developers
    $page->add(new admin_setting_heading('theme_evagu/for_developers', get_string('for_developers', 'theme_evagu'), get_string('for_developers_desc', 'theme_evagu')));
    // Expose blocks to all pages
    $setting = new admin_setting_configselect(
        'theme_evagu/dev_expose_blocks',
        get_string('dev_expose_blocks', 'theme_evagu'),
        get_string('dev_expose_blocks_desc', 'theme_evagu'),
        '0',
        array(
            '0' => 'No (default & highly recommended)',
            '1' => 'Yes (for developer use only)'
        )
    );
    $page->add($setting);
    $settings->add($page);
}
