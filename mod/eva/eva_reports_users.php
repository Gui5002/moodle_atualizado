<?php

require_once (__DIR__ . '/../../config.php');
require_once ($CFG->dirroot . '/blocks/eva_reports_users/classes/output/renderer.php');
require_once ($CFG->dirroot . '/blocks/eva_reports_users/classes/output/reports_users.php');

$reportid = optional_param('id', 2, PARAM_INT);
$allowed = [2, 3];
if (!in_array($reportid, $allowed, true)) {
    $reportid = 2;
}

$PAGE->set_url('/mod/eva/eva_reports_users.php', ['id' => $reportid]);
$id = optional_param('id', null, PARAM_INT);
require_login();

$syscontext = context_system::instance();
require_capability('mod/eva:view', $syscontext);

$renderable = new \block_eva_reports_users\output\reports_users();

$subtitle = '';
if ($reportid === 2) {
    $subtitle = ' - Resultados por Curso';
} else if ($reportid === 3) {
    $subtitle = ' - Conclusão de Cursos';
}
$title = 'Relatório' . $subtitle;

$PAGE->set_pagelayout('admin');
$PAGE->set_context($syscontext);
$PAGE->navbar->add($title);
$PAGE->set_title($title);
$PAGE->set_heading($title);

$PAGE->requires->css(new \moodle_url($CFG->wwwroot . '/blocks/eva_reports_users/css/style.css'));
$PAGE->requires->css(new \moodle_url($CFG->wwwroot . '/theme/evagu/style/bootstrap-datepicker.css'));
$PAGE->requires->css(new \moodle_url(
    'https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.11.5/af-2.3.7/b-2.2.2/b-colvis-2.2.2/b-html5-2.2.2/b-print-2.2.2/cr-1.5.5/date-1.1.2/fc-4.0.2/fh-3.2.2/kt-2.6.4/r-2.2.9/rg-1.1.4/rr-1.2.8/sc-2.0.5/sb-1.3.2/sp-2.0.0/sl-1.3.4/sr-1.1.0/datatables.min.css'
));
$PAGE->requires->css(new \moodle_url('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'));
$PAGE->requires->js(new \moodle_url('https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js'), true);
$PAGE->requires->js(new \moodle_url('https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js'), true);
$PAGE->requires->js(new \moodle_url(
    'https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.25/af-2.3.7/b-1.7.1/b-colvis-1.7.1/b-html5-1.7.1/b-print-1.7.1/cr-1.5.4/date-1.1.0/fc-3.3.3/fh-3.1.9/kt-2.6.2/r-2.2.9/rg-1.1.3/rr-1.2.8/sc-2.0.4/sb-1.1.0/sp-1.3.0/sl-1.3.3/datatables.min.js'
), true);

$PAGE->requires->js(new \moodle_url('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'), true);
$PAGE->requires->js(new \moodle_url('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js'), true);
$PAGE->requires->js(new \moodle_url($CFG->wwwroot . '/theme/evagu/javascript/bootstrap-datepicker.js'), true);
$PAGE->requires->js(new \moodle_url($CFG->wwwroot . '/blocks/eva_reports_users/locales/bootstrap-datepicker.pt-BR.min.js'), true);
$PAGE->requires->js(new \moodle_url('https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js'), true);

require_once ($CFG->dirroot . '/blocks/eva_reports_users/js/funcoesJs.php');
$PAGE->set_heading($title);

$output = $PAGE->get_renderer('block_eva_reports_users');

echo $OUTPUT->header();
echo $output->render($renderable);
echo $OUTPUT->footer();
