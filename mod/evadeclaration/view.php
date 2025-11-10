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
 * Simple declaration activity module
 *
 * @package    mod
 * @subpackage evadeclaration
 * @copyright  Renata C Neves 2024 <xangay@hotmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once("$CFG->dirroot/mod/evadeclaration/lib.php");
require_once("$CFG->libdir/pdflib.php");
require_once("$CFG->dirroot/mod/evadeclaration/locallib.php");
$id = required_param('id', PARAM_INT); // Course Module ID.
$action = optional_param('action', '', PARAM_ALPHA);
$tab = optional_param('tab', evadeclaration::DEFAULT_VIEW, PARAM_INT);
$type = optional_param('type', '', PARAM_ALPHA);
$page = optional_param('page', 0, PARAM_INT);
$perpage = optional_param('perpage', get_config('evadeclaration', 'perpage'), PARAM_INT);
$orderby = optional_param('orderby', 'username', PARAM_RAW);
$issuelist = optional_param('issuelist', null, PARAM_ALPHA);
$selectedusers = optional_param_array('selectedusers', null, PARAM_INT);
$cm = get_coursemodule_from_id( 'evadeclaration', $id);
if (!$cm) {
    print_error('Course Module ID was incorrect');
}
$course = $DB->get_record('course', array('id' => $cm->course));
if (!$course) {
    print_error('course is misconfigured');
}
$declaration = $DB->get_record('evadeclaration', array('id' => $cm->instance));
if (!$declaration) {
    print_error('course module is incorrect');
}
$context = context_module::instance ($cm->id);
$url = new moodle_url('/mod/evadeclaration/view.php', array (
        'id' => $cm->id,
        'tab' => $tab,
        'page' => $page,
        'perpage' => $perpage,
));
if ($type) {
    $url->param('type', $type);
}
if ($orderby) {
    $url->param ('orderby', $orderby);
}
if ($action) {
    $url->param ('action', $action);
}
if ($issuelist) {
    $url->param ('issuelist', $issuelist);
}
// Initialize $PAGE, compute blocks.
$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_cm($cm);
require_login( $course->id, false, $cm);
require_capability('mod/evadeclaration:view', $context);
$canmanage = has_capability('mod/evadeclaration:manage', $context);
// Log update.
$evadeclaration = new evadeclaration($context, $cm, $course);
$evadeclaration->set_instance($declaration);
$completion = new completion_info($course);
$completion->set_module_viewed($cm);
$PAGE->set_title(format_string($declaration->name));
$PAGE->set_heading(format_string($course->fullname));
switch ($tab) {
    case $evadeclaration::ISSUED_DECLARACOES_VIEW :
        // Verify if user can access this page
        // avoid the access by adding tab=1 in post/get.
        if ($canmanage) {
            $evadeclaration->view_issued_declarations($url, $selectedusers);
        } else {
            print_error('nopermissiontoviewpage');
        }
    break;
    case $evadeclaration::BULK_ISSUE_DECLARACOES_VIEW :
        // Verify if user can access this page
        // avoid the access by adding tab=1 in post/get.
        if ($canmanage) {
            $evadeclaration->view_bulk_declarations($url, $selectedusers);
        } else {
            print_error('nopermissiontoviewpage');
        }
    break;
    default :
        $evadeclaration->view_default($url, $canmanage);
    break;
}
