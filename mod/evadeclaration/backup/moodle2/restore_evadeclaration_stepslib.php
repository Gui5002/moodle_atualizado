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
defined('MOODLE_INTERNAL') || die;
/**
 * Define all the restore steps that will be used by the restore_evadeclaration_activity_task
 */

/**
 * Structure step to restore one evadeclaration activity
 */
class restore_evadeclaration_activity_structure_step extends restore_activity_structure_step {

    protected function define_structure() {

        $paths = array();
        $userinfo = $this->get_setting_value('userinfo');

        $paths[] = new restore_path_element('evadeclaration', '/activity/evadeclaration');

        if ($userinfo) {
            $paths[] = new restore_path_element('evadeclaration_issue', '/activity/evadeclaration/issues/issue');
        }

        // Return the paths wrapped into standard activity structure.
        return $this->prepare_activity_structure($paths);
    }

    protected function process_evadeclaration($olddata) {
        global $DB;

        $olddata = (object)$olddata;
        $data = new stdClass();
        $data = $olddata;

        $data->course = $this->get_courseid();
        $data->timemodified = $this->apply_date_offset($olddata->timemodified);

        if (isset($olddata->outcame) && !empty($olddata->outcame)) {
            $data->outcame = $this->get_mappingid('outcome', $olddata->outcame);
        }

        // Verifing if decldate it's from a module.
        if (isset($olddata->decldate) && $olddata->decldate > 0) {
            // Try to get new module id, but could be not set.
            $decldate = $this->get_mappingid('course_module', $olddata->decldate);
            if (!$decldate) {
                // Add this ugly hack to mark not sucefully, try in after_restorke in TASK lib
                // as sugested in http://docs.moodle.org/dev/Restore_2.0_for_developers.
                $decldate = -1000 * $olddata->decldate;
            }

            $data->decldate = $decldate;
        }

        // Verifing if declgrade it's from a module.
        if (isset($olddata->declgrade) && $olddata->declgrade > 0) {
            // An odd error, i think,  it's don't set correct declGRADE if it's equals declDATE.
            if ($olddata->decldate == $olddata->declgrade) {
                $declgrade = $data->decldate;
            } else {
                $declgrade = $this->get_mappingid('course_module', $olddata->declgrade);
                if (!$declgrade) {
                    // Try to get new module id, but could be not set.
                    // Add this ugly hack to mark not sucefully, try in after_restorke in TASK lib
                    // as sugested in http://docs.moodle.org/dev/Restore_2.0_for_developers.
                    $declgrade = -1000 * $olddata->declgrade;
                }
            }
            $data->declgrade = $declgrade;
        }

        // Insert the evadeclaration record.
        $newitemid = $DB->insert_record('evadeclaration', $data);
        // Immediately after inserting "activity" record, call this.
        $this->apply_activity_instance($newitemid);
    }

    protected function process_evadeclaration_issue($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;

        $data->pathnamehash = $oldid;
        $data->declarationid = $this->get_new_parentid('evadeclaration');
        $data->userid = $this->get_mappingid('user', $data->userid);
        if (!isset($data->timecreated)) {
            $data->timecreated = time();
        }

        $newitemid = $DB->insert_record('evadeclaration_issues', $data);
        $this->set_mapping('evadeclaration_issue', $oldid, $newitemid);

    }

    protected function after_execute() {
        // Add evadeclaration related files, no need to match by itemname (just internally handled context).
        $this->add_related_files('mod_evadeclaration', 'intro', null);
        $this->add_related_files('mod_evadeclaration', 'image', null);
        $this->add_related_files('mod_evadeclaration', 'issues', null);
    }

}
