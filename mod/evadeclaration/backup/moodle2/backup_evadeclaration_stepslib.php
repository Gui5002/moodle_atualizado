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
 * @package moodlecore
 * @subpackage backup-moodle2
 * @copyright 2024 onwards Renata Neves (Xangay) {@link https://webdevsolutions.com.br/}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Define all the backup steps that will be used by the backup_declaration_activity_task
 */

/**
 * Define the complete declaration structure for backup, with file and id annotations
 */

defined('MOODLE_INTERNAL') || die;
require_once("$CFG->dirroot/mod/evadeclaration/locallib.php");

class backup_evadeclaration_activity_structure_step extends backup_activity_structure_step
{

    protected function define_structure()
    {
        global $CFG;

        // To know if we are including userinfo.
        $userinfo = $this->get_setting_value('userinfo');

        // Define each element separated.
        $declaration = new backup_nested_element('evadeclaration', array('id'), array(
            'name', 'intro', 'introformat', 'timemodified', 'width', 'height', 'declarationimage',
            'declarationtext', 'declarationtextformat', 'declarationtextx', 'declarationtexty',
            'coursename', 'coursehours', 'outcome', 'decldate', 'decldatefmt', 'declgrade',
            'gradefmt', 'emailfrom', 'emailothers', 'emailteachers', 'reportdecl', 'delivery',
            'requiredtime', 'printqrcode', 'qrcodefirstpage', 'codex', 'codey', 'enablesecondpage',
            'secondpagex', 'secondpagey', 'secondpagetext', 'secondpagetextformat', 'secondimage', 'timestartdatefmt'
        ));

        $issues = new backup_nested_element('issues');

        $issue = new backup_nested_element(
            'issue',
            array('id'),
            array('userid', 'declarationname', 'timecreated', 'code', 'timedeleted')
        );

        // Build the tree.
        $declaration->add_child($issues);
        $issues->add_child($issue);

        // Define sources.
        $declaration->set_source_table('evadeclaration', array('id' => backup::VAR_ACTIVITYID));

        // All the rest of elements only happen if we are including user info.
        if ($userinfo) {
            $issue->set_source_table('evadeclaration_issues', array('declarationid' => backup::VAR_PARENTID));
        }

        // Annotate the user id's where required.
        $declaration->annotate_ids('outcome', 'outcome');
        $declaration->annotate_ids('decldate', 'decldate');
        $declaration->annotate_ids('declgrade', 'declgrade');
        $issue->annotate_ids('user', 'userid');

        // Define file annotations.
        $declaration->annotate_files(
            evadeclaration::DECLARATION_COMPONENT_NAME,
            evadeclaration::DECLARATION_IMAGE_FILE_AREA,
            null
        );
        $issue->annotate_files(
            evadeclaration::DECLARATION_COMPONENT_NAME,
            evadeclaration::DECLARATION_ISSUES_FILE_AREA,
            'id'
        );

        // Return the root element (declaration), wrapped into standard activity structure.
        return $this->prepare_activity_structure($declaration);
    }
}
