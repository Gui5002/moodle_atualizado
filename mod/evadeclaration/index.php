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
 * This page lists all the instances of declaration in a particular course
 *
 * @package    mod
 * @subpackage evadeclaration
 * @copyright  Renata Costa Neves <xangay@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once('lib.php');

$id = required_param('id', PARAM_INT);// Course Module ID.

// Ensure that the course specified is valid.
$course = $DB->get_record('course', array('id' => $id));
if (!$course) {
    print_error('Course ID is incorrect');
}

// Requires a login.
require_course_login($course);

// Declare variables.
$currentsection = "";
$printsection = "";
$timenow = time();

// Strings used multiple times.
$strdeclarations = get_string('modulenameplural', 'evadeclaration');
$strissued  = get_string('issued', 'evadeclaration');
$strname  = get_string("name");
$strsectionname = get_string('sectionname', 'format_'.$course->format);

// Print the header.
$PAGE->set_pagelayout('incourse');
$PAGE->set_url('/mod/evadeclaration/index.php', array('id' => $course->id));
$PAGE->navbar->add($strdeclarations);
$PAGE->set_title($strdeclarations);
$PAGE->set_heading($course->fullname);

// Get the declarations, if there are none display a notice.
$declarations = get_all_instances_in_course('evadeclaration', $course);
if (!$declarations) {
    echo $OUTPUT->header();
    notice(get_string('nodeclarationsissued', 'evadeclaration'), "$CFG->wwwroot/course/view.php?id=$course->id");
    echo $OUTPUT->footer();
    exit();
}

$usesections = course_format_uses_sections($course->format);
if ($usesections) {
    $modinfo = get_fast_modinfo($course->id);
    $sections = $modinfo->get_section_info_all();
}

$table = new html_table();

if ($usesections) {
    $table->head  = array ($strsectionname, $strname, $strissued);
} else {
    $table->head  = array ($strname, $strissued);
}

foreach ($declarations as $declaration) {
    if (!$declaration->visible) {
        // Show dimmed if the mod is hidden.
        $link = html_writer::tag('a', $declaration->name, array('class' => 'dimmed',
            'href' => $CFG->wwwroot . '/mod/evadeclaration/view.php?id=' . $declaration->coursemodule));
    } else {
        // Show normal if the mod is visible.
        $link = html_writer::tag('a', $declaration->name, array('href' => $CFG->wwwroot .
          '/mod/evadeclaration/view.php?id=' . $declaration->coursemodule));
    }
    if ($declaration->section !== $currentsection) {
        if ($declaration->section) {
            $printsection = $declaration->section;
        }
        if ($currentsection !== "") {
            $table->data[] = 'hr';
        }
        $currentsection = $declaration->section;
    }
    // Get the latest declaration issue.
    if ($declrecord = $DB->get_record('evadeclaration_issues', array('userid' => $USER->id,
        'declarationid' => $declaration->id))) {
        $issued = userdate($declrecord->timecreated);
    } else {
        $issued = get_string('notreceived', 'evadeclaration');
    }
    if (($course->format == 'weeks') || ($course->format == 'topics')) {
        $table->data[] = array ($declaration->section, $link, $issued);
    } else {
        $table->data[] = array ($link, $issued);
    }
}

echo $OUTPUT->header();
echo '<br />';
echo html_writer::table($table);
echo $OUTPUT->footer();