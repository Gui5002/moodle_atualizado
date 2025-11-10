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
 * Verify an issued declaration by code
 *
 * @package mod
 * @subpackage evadeclaration
 * @copyright 2014 © Renata C Neves2024
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once('verify_form.php');
require_once('lib.php');

$code = optional_param('code', null, PARAM_ALPHANUMEXT); // Issed Code.

$context = context_system::instance();
$PAGE->set_url('/mod/evadeclaration/verify.php', array('code' => $code));
$PAGE->set_context($context);
$PAGE->set_title(get_string('declarationverification', 'evadeclaration'));
$PAGE->set_heading(get_string('declarationverification', 'evadeclaration'));
$PAGE->set_pagelayout('base');
echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('declarationverification', 'evadeclaration'));

$verifyform = new verify_form();

if (!$verifyform->get_data()) {
    if ($code) {
        $verifyform->set_data(array('code' => $code));
    }

    $verifyform->display();

} else {
    $issueddecl = get_issued_decl($code);

    $user = $DB->get_record('user', array('id' => $issueddecl->userid));
    if ($user) {
        $username = fullname($user);
    } else {
        $username = get_string('notavailable');
    }

    $strto = get_string('awardedto', 'evadeclaration');
    $strdate = get_string('issueddate', 'evadeclaration');
    $strcode = get_string('code', 'evadeclaration');

    $table = new html_table();
    $table->width = "95%";
    $table->tablealign = "center";
    $table->head = array(get_string('course'), $strto, $strdate, $strcode);
    $table->align = array("left", "left", "center", "center");
    $coursename = get_course_name($issueddecl);
    $table->data[] = array($coursename, $username,
            userdate($issueddecl->timecreated) . evadeclaration_print_issue_declaration_file($issueddecl), $issueddecl->code);
    echo html_writer::table($table);

    // Add to log.
    $event = \mod_evadeclaration\event\declaration_verified::create(array(
            'objectid' => $issueddecl->id,
            'context' => $context,
            'relateduserid' => $issueddecl->userid,
            'other' => array( 'issueddeclcode' => $issueddecl->code)
        )
    );
    $event->trigger();


}

echo $OUTPUT->footer();

function get_issued_decl($code = null) {
    global $DB;

    $issueddecl = $DB->get_record("evadeclaration_issues", array('code' => $code));
    if (!$issueddecl) {
        print_error('invalidcode', 'evadeclaration');
    }
    return $issueddecl;
}

/**
 * Try to get course name, or return 'course not found!'
 *
 * @param issueddecl Issued declaration object
 */
function get_course_name($issueddecl) {
    global $DB;

    if ($issueddecl->coursename) {
        return $issueddecl->coursename;
    }

    $cm = get_coursemodule_from_instance('evadeclaration', $issueddecl->declarationid);
    if ($cm) {
        $course = $DB->get_record('coruse', array('id' => $cm->course));
        if ($course) {
            return $course->fullname;
        }
    }

    return get_string('coursenotfound', 'evadeclaration');
}





