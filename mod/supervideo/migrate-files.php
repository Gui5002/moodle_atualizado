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
 * Migrate vídeos from mod_videotime
 *
 * @package   mod_supervideo
 * @copyright 2024 Eduardo kraus (http://eduardokraus.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once('../../config.php');
(new \core\task\file_trash_cleanup_task())->execute();

require_login();
$context = context_system::instance();
require_capability('moodle/site:config', $context);


$files = $DB->get_records_sql("SELECT * FROM {files} WHERE component != 'user' AND (mimetype LIKE 'video%' OR mimetype LIKE 'audio%')");
foreach ($files as $file) {

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

    echo '<pre>';
    print_r($result);
    echo '</pre>';
}
