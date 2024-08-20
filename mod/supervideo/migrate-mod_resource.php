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
 * Migrate vídeos from mod_resource
 *
 * @package   mod_supervideo
 * @copyright 2024 Eduardo kraus (http://eduardokraus.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once('../../config.php');

(new \core\task\file_trash_cleanup_task())->execute();
(new \core\task\file_temp_cleanup_task())->execute();

require_once "{$CFG->dirroot}/course/lib.php";
$course_modules = $DB->get_records('course_modules', ['deletioninprogress' => 1]);
foreach ($course_modules as $course_module) {
    course_delete_module($course_module->id, false);
}


require_login();
$context = context_system::instance();
require_capability('moodle/site:config', $context);

$moduleresource = $DB->get_record('modules', ['name' => 'resource']);
if (!$moduleresource) {
    die("Você não tem o MOD_RESOURCE instalado");
}
$modulesupervideo = $DB->get_record('modules', ['name' => 'supervideo']);


$resources = $DB->get_records_sql("SELECT * FROM {resource}");
foreach ($resources as $resource) {
    $coursemodules = $DB->get_record("course_modules", [
        'module' => $moduleresource->id,
        'instance' => $resource->id,
        'deletioninprogress' => 0
    ]);
    if ($coursemodules) {
        if ($coursemodules->deletioninprogress) {

            echo "<a href='https://trainingrichardsedu.aulaemvideo.com.br/MOODLE_401/mod/resource/view.php?id={$coursemodules->id}' target=aa>$resource->name </a><br>";

            continue;
        }
        echo "Bora <br>";
        echo "-> Course => {$coursemodules->course} <br>";
        echo "-> CourseModule => {$coursemodules->id} <br>";

        print_r($coursemodules);

        $context = context_module::instance($coursemodules->id);
        $files = $DB->get_records_sql("SELECT * FROM {files} WHERE contextid = {$context->id} AND (mimetype LIKE 'video%' OR mimetype LIKE 'audio%')");
        if ($files) {
            foreach ($files as $file) {
                echo "-> FILES_ID => {$file->id} <br>";
                $file = sendFile($file);
            }

            echo '<pre>';
            print_r($file);
            echo '</pre>';

            die();;

            $supervideo = (object)[
                'course' => $resource->course,
                'name' => $resource->name,
                'intro' => $resource->intro,
                'introformat' => $resource->introformat,
                'videourl' => $videourl,
                'playersize' => 1,
                'showcontrols' => 1,
                'autoplay' => 0,
                'grade_approval' => 0,
                'completionpercent' => 0,
                'timemodified' => $resource->timemodified,
            ];
            $supervideo->id = $DB->insert_record("supervideo", $supervideo);


            $coursemodules->module = $modulesupervideo->id;
            $coursemodules->instance = $supervideo->id;
            $DB->update_record('course_modules', $coursemodules);

           // die();
        }
    }
}

purge_caches();

\context_helper::cleanup_instances();
echo ' \context_helper::cleanup_instances()<br>';
\context_helper::build_all_paths(true);
echo ' \context_helper::build_all_paths(true)<br>';


function sendFile($file) {
    global $CFG;

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://app.ottflix.com.br/api/v2/Envio');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);

    $l1 = $file->contenthash[0] . $file->contenthash[1];
    $l2 = $file->contenthash[2] . $file->contenthash[3];

    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        'file' => curl_file_create("{$CFG->dataroot}/filedir/{$l1}/{$l2}/{$file->contenthash}", $file->mimetype, $file->filename),
        //'filename' => $file->filename
    ]);

    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: multipart/form-data',
        'authorization: HMAC-SHA2048-Ni04NmFhYTU3NmE0OTBlZjJkMjZmNGQ3MjNlZDlkNzA0NzEyZDc2ZjMw',
    ]);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);

    return json_decode($result);
}
