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
/** * ${ESAGU} file description here.
* @package    ${THEMANAME}
* @copyright  2024 ESAGU
* @author     xangay® RCN <xangay.gmail.com>
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();
// This is the version of the plugin.
$plugin->version = 202610070001;
// This is the version of Moodle this plugin requires.
$plugin->requires = 2016112900.00;
// This is the component name of the plugin - it always starts with 'theme_'
// for themes and should be the same as the name of the folder.
$plugin->component = 'theme_evagu';
// This is a list of plugins, this plugin depends on (and their versions).
$plugin->dependencies = [
    'theme_boost' => 2016102100 ];
// This is a stable release.
$plugin->maturity = MATURITY_STABLE;
// Compatibility build for Moodle 3.5.
$plugin->release = '3.9.9-moodle35-compat';
$plugin->incompatible = 400;
