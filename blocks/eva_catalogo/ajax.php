<?php
require('../../config.php');
require_login();
require_sesskey();

$context = context_system::instance();
require_capability('moodle/course:view', $context);

header('Content-Type: text/html; charset=utf-8');

// 🔎 Filtros
$nome     = optional_param('nome', '', PARAM_TEXT);
$carga    = optional_param_array('ch', [], PARAM_INT);
// (Outros filtros podem ser incluídos aqui futuramente)

$joins = "
    JOIN {customfield_data} cfd ON cfd.instanceid = c.id
    JOIN {customfield_field} cff ON cff.id = cfd.fieldid AND cff.shortname = 'catalogo'
    JOIN {context} ctx ON ctx.instanceid = c.id AND ctx.contextlevel = :ctxcourse
    JOIN {course_categories} cc ON cc.id = c.category
    JOIN {enrol} e ON e.courseid = c.id
    JOIN {user_enrolments} ue ON ue.enrolid = e.id
    JOIN {eva_course_workload} w ON w.courseid = c.id
";

$where = "c.id != 1 AND c.visible = 1 AND cfd.value = '1'";
$params = ['ctxcourse' => CONTEXT_COURSE];

if (!empty($nome)) {
    $where .= " AND c.fullname LIKE :nome";
    $params['nome'] = "%$nome%";
}

if (!empty($carga)) {
    $faixas = [];
    foreach ($carga as $faixa) {
        switch ($faixa) {
            case 1: $faixas[] = "ROUND(SUM(w.tempoemmin)/COUNT(DISTINCT ue.userid)) <= 1200"; break;
            case 2: $faixas[] = "(ROUND(SUM(w.tempoemmin)/COUNT(DISTINCT ue.userid)) BETWEEN 1201 AND 2400)"; break;
            case 3: $faixas[] = "(ROUND(SUM(w.tempoemmin)/COUNT(DISTINCT ue.userid)) BETWEEN 2401 AND 3600)"; break;
            case 4: $faixas[] = "ROUND(SUM(w.tempoemmin)/COUNT(DISTINCT ue.userid)) > 3600"; break;
        }
    }
    if (!empty($faixas)) {
        $where .= " AND (" . implode(" OR ", $faixas) . ")";
    }
}

$sql = "
    SELECT c.id, c.fullname, c.summary, c.summaryformat,
           ctx.id AS contextid,
           cc.name AS category,
           ROUND(SUM(w.tempoemmin)/COUNT(DISTINCT ue.userid)) AS total_minutos,
           CONCAT(
               FLOOR(SUM(w.tempoemmin)/COUNT(DISTINCT ue.userid)/60), 'h ',
               LPAD(MOD(SUM(w.tempoemmin)/COUNT(DISTINCT ue.userid), 60), 2, '0'), ' min'
           ) AS carga_formatada
    FROM {course} c
    $joins
    WHERE $where
    GROUP BY c.id, c.fullname, c.summary, c.summaryformat, ctx.id, cc.name
    ORDER BY c.fullname ASC
";

$cursos = $DB->get_records_sql($sql, $params);
$fs = get_file_storage();

foreach ($cursos as $curso) {
    $img = $CFG->wwwroot . '/blocks/eva_catalogo/pix/default_course.png';

    $files = $fs->get_area_files($curso->contextid, 'course', 'summary', 0, 'itemid, filepath, filename', false);
    foreach ($files as $file) {
        $img = moodle_url::make_pluginfile_url(
            $file->get_contextid(), $file->get_component(), $file->get_filearea(),
            $file->get_itemid(), $file->get_filepath(), $file->get_filename()
        )->out();
        break;
    }

    echo "<div class='eva-card'>";
    echo "<img src='$img' alt='" . s($curso->fullname) . "' class='eva-img' />";
    echo "<div class='eva-content'>";
    echo "<h3>" . format_string($curso->fullname) . "</h3>";
    echo "<p><strong>Área:</strong> " . format_string($curso->category) . "</p>";
    echo "<p><strong>Carga horária:</strong> " . format_string($curso->carga_formatada) . "</p>";
    echo "<div class='eva-botao'>";
    echo "<a href='$CFG->wwwroot/course/view.php?id=$curso->id' class='btn btn-sm btn-secondary'>" . get_string('seemore', 'block_eva_catalogo') . "</a>";
    echo "</div></div></div>";
}
