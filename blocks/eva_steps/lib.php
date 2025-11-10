<?php
function block_eva_steps_pluginfile($course, $birecord_or_cm, $context, $filearea, $args, $forcedownload, array $options = array())
{
    global $DB, $CFG, $USER;
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
        } else if ($parentcontext->contextlevel === CONTEXT_USER && $parentcontext->instanceid != $USER->id) {
            send_file_not_found();
        }
    }
    if ($filearea !== 'items') {
        send_file_not_found();
    }
    $fs = get_file_storage();
    if (count($args) != 2) {
        send_file_not_found();
    }
    if (!$file = $fs->get_file($context->id, 'block_eva_steps', 'items', $args[0], '/', $args[1]) or $file->is_directory()) {
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
    send_stored_file($file, null, 0, $forcedownload, $options);
}
