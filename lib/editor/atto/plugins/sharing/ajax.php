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
 * Generate the Sharing.
 *
 * @package    atto_sharing
 * @copyright  2021 Brain Station 23 Ltd. <brainstation-23.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

define('AJAX_SCRIPT', true);

global $CFG,$PAGE;
require_once(__DIR__ . '/../../../../../config.php');

$contextid = required_param('contextid', PARAM_INT);
$content = required_param('content', PARAM_RAW);
$courseid = required_param('id', PARAM_RAW);

list($context, $course, $cm) = get_context_info_array($contextid);

$PAGE->set_url('/lib/editor/atto/plugins/sharing/ajax.php');
$PAGE->set_context($context);

require_login($course, false, $cm);
require_sesskey();


