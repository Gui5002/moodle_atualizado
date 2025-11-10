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
function block_eva_about_2_pluginfile($course, $birecord_or_cm, $context, $filearea, $args, $forcedownload, array $options = array())
{
    global $DB, $CFG;
    if ($context->contextlevel != CONTEXT_BLOCK) {
        send_file_not_found();
    }
    if ($context->get_course_context(false)) {
        require_course_login($course);
    } else if ($CFG->forcelogin) {
        require_login();
    } else {
        $parentcontext = $context->get_parent_context();
        if ($parentcontext->contextlevel === CONTEXT_COURSECAT) {
            $category = $DB->get_record('course_categories', array('id' => $parentcontext->instanceid), '*', MUST_EXIST);
            if (!$category->visible) {
                require_capability('moodle/category:viewhiddencategories', $parentcontext);
            }
        }
    }
    if ($filearea !== 'content') {
        send_file_not_found();
    }
    $fs = get_file_storage();
    $filename = array_pop($args);
    $filepath = $args ? '/' . implode('/', $args) . '/' : '/';
    if (!$file = $fs->get_file($context->id, 'block_eva_about_2', 'content', 0, $filepath, $filename) or $file->is_directory()) {
        send_file_not_found();
    }
    if ($parentcontext = context::instance_by_id($birecord_or_cm->parentcontextid, IGNORE_MISSING)) {
        if ($parentcontext->contextlevel == CONTEXT_USER) {
            $forcedownload = true;
        }
    } else {
        $forcedownload = true;
    }
    \core\session\manager::write_close();
    send_stored_file($file, 60 * 60, 0, $forcedownload, $options);
}
