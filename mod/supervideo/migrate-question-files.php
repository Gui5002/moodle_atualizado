<?php
/**
 * User: Eduardo Kraus
 * Date: 19/01/2024
 * Time: 15:00
 */


require_once('../../config.php');

session_write_close();


$questions = $DB->get_records_sql("SELECT * FROM {question} WHERE (questiontext LIKE '%.mp4%' OR questiontext LIKE '%.mp3%')");

foreach ($questions as $question) {

    echo "<a href='https://trainingrichardsedu.aulaemvideo.com.br/MOODLE_401/question/bank/previewquestion/preview.php?id={$question->id}&restartversion=0&courseid=1' target=aa>{$question->name}</a>";
    echo '<div style="background:#ffff0035; margin:20px 2px 2px;">Antes:' . "\n";
    echo htmlentities($question->questiontext);
    echo '</div>';

    preg_match_all('/<(video|audio).*?src="(.*?)">.*?<\/(video|audio)>/s', $question->questiontext, $videos);
    foreach ($videos[0] as $key => $video) {
        $tagVideo = $videos[0][$key];
        $caminhoVideo = $videos[2][$key];

        $caminhoVideo = urldecode($caminhoVideo);
        preg_match('/@@PLUGINFILE@@\/(.*\.mp[3-4])/', $caminhoVideo, $file);
        if (isset($file[1])) {
            $aa = getFile($file[1]);

            $replace = '<a href="' . $aa->data->url . '">' . $aa->data->url . '</a>';

            $question->questiontext = str_replace($tagVideo, $replace, $question->questiontext);


            echo '<div style="background:#55ff0035; margin:2px;">Depois:' . "\n";
            echo htmlentities($question->questiontext);
            echo '</div>';

            $DB->update_record("question", $question);
        }
    }

    echo '<hr>';
    // die();
}

function getFile($filename) {
    global $DB, $CFG;
    $sql = "SELECT * FROM {files} WHERE filename LIKE '{$filename}' AND component = 'question' LIMIT 1";
    $file = $DB->get_record_sql($sql);

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