<?php
// This file is part of Ranking block for Moodle - http://moodle.org/
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

/**
 * Atto HTML editor
 *
 * @package    atto_reciteditor
 * @copyright  2019 RECIT
 * @license    {@link http://www.gnu.org/licenses/gpl-3.0.html} GNU GPL v3 or later
 */

// This line protects the file from being accessed by a URL directly.
defined('MOODLE_INTERNAL') || die();
require_once(dirname(__FILE__).'/classes/admin_setting_configtext_iconclass.php');

// This is used for performance, we don't need to know about these settings on every page in Moodle, only when
// we are looking at the admin settings pages.
if ($ADMIN->fulltree) {



    $name = 'atto_reciteditor/enableshowcase';
    $title = get_string('enableshowcase', 'atto_reciteditor');
    $description = get_string('enableshowcasedesc', 'atto_reciteditor');
    $setting = new admin_setting_configcheckbox($name, $title, $description, 1);
    $settings->add($setting);
   
    $name = 'atto_reciteditor/showcase_url';
    $title = get_string('showcase_url', 'atto_reciteditor');
    $description = get_string('showcase_urldesc', 'atto_reciteditor');
    $default = 'https://sn-recit-formation-a-distance.github.io/html-bootstrap-editor-showcase/index.html';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $settings->add($setting);
   
    $name = 'atto_reciteditor/stylesheet_to_add';
    $title = get_string('stylesheet_to_add', 'atto_reciteditor');
    $description = get_string('stylesheet_to_adddesc', 'atto_reciteditor');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $settings->add($setting);
   
    $name = 'atto_reciteditor/pixabaykey';
    $title = get_string('pixabaykey', 'atto_reciteditor');
    $description = get_string('pixabaykeydesc', 'atto_reciteditor');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $settings->add($setting);
   
    $name = 'atto_reciteditor/iconclass';
    $title = get_string('iconclass', 'atto_reciteditor');
    $description = get_string('iconclassdesc', 'atto_reciteditor');
    $default = 'Fontawesome 4.7=.fa-,Fontawesome 5=.fas-';
    $setting = new \atto_reciteditor\admin_setting_configtext_iconclass($name, $title, $description, $default);
    $settings->add($setting);

}