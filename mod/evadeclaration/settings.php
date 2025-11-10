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

/**
 * Provides some custom settings for the declaration module
 *
 * @package    mod
 * @subpackage evadeclaration
 * @copyright  R016
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    require_once("$CFG->dirroot/mod/evadeclaration/lib.php");

    // General settings.
    $settings->add(new admin_setting_configtext(
        'evadeclaration/width',
        get_string('defaultwidth', 'evadeclaration'),
        get_string('size_help', 'evadeclaration'),
        210,
        PARAM_INT
    ));
    $settings->add(new admin_setting_configtext(
        'evadeclaration/height',
        get_string('defaultheight', 'evadeclaration'),
        get_string('size_help', 'evadeclaration'),
        297,
        PARAM_INT
    ));

    $settings->add(new admin_setting_configtext(
        'evadeclaration/declarationtextx',
        get_string('defaultdeclarationtextx', 'evadeclaration'),
        get_string('textposition_help', 'evadeclaration'),
        10,
        PARAM_INT
    ));
    $settings->add(new admin_setting_configtext(
        'evadeclaration/declarationtexty',
        get_string('defaultdeclarationtexty', 'evadeclaration'),
        get_string('textposition_help', 'evadeclaration'),
        90,
        PARAM_INT
    ));

    $settings->add(new admin_setting_configselect(
        'evadeclaration/decldate',
        get_string('printdate', 'evadeclaration'),
        get_string('printdate_help', 'evadeclaration'),
        -2,
        evadeclaration_get_date_options()
    ));


    $settings->add(new admin_setting_configtext(
        'evadeclaration/decllifetime',
        get_string('decllifetime', 'evadeclaration'),
        get_string('decllifetime_help', 'evadeclaration'),
        60,
        PARAM_INT
    ));

    // QR CODE.
    $settings->add(new admin_setting_configcheckbox(
        'evadeclaration/printqrcode',
        get_string('printqrcode', 'evadeclaration'),
        get_string('printqrcode_help', 'evadeclaration'),
        1
    ));
    $settings->add(new admin_setting_configtext(
        'evadeclaration/codex',
        get_string('defaultcodex', 'evadeclaration'),
        get_string('qrcodeposition_help', 'evadeclaration'),
        15,
        PARAM_INT
    ));
    $settings->add(new admin_setting_configtext(
        'evadeclaration/codey',
        get_string('defaultcodey', 'evadeclaration'),
        get_string('qrcodeposition_help', 'evadeclaration'),
        230,
        PARAM_INT
    ));
    $settings->add(new admin_setting_configcheckbox(
        'evadeclaration/qrcodefirstpage',
        get_string('qrcodefirstpage', 'evadeclaration'),
        get_string('qrcodefirstpage_help', 'evadeclaration'),
        0
    ));

    // declaration back page.
    $settings->add(new admin_setting_configcheckbox(
        'evadeclaration/enablesecondpage',
        get_string('enablesecondpage', 'evadeclaration'),
        get_string('enablesecondpage_help', 'evadeclaration'),
        0
    ));

    // Pagination.
    $settings->add(new admin_setting_configtext(
        'evadeclaration/perpage',
        get_string('defaultperpage', 'evadeclaration'),
        get_string('defaultperpage_help', 'evadeclaration'),
        100,
        PARAM_INT
    ));
}
