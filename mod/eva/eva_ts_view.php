<?php
global $CFG,$PAGE;

require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/blocks/eva_ts_view/classes/output/renderer.php');
require_once($CFG->dirroot . '/blocks/eva_ts_view/classes/output/ts_view.php');

$PAGE->set_url('/mod/eva/eva_ts_view.php');

require_login();

$syscontext = context_system::instance();
require_capability('mod/eva:view', $syscontext);

$renderable = new \block_eva_ts_view\output\ts_view();

$title = "Visualizar sugestões de capacitação";
$PAGE->set_pagelayout('admin');
$PAGE->set_context($syscontext);
$PAGE->navbar->add($title);
$PAGE->set_title($title);

$PAGE->requires->css(new \moodle_url($CFG->wwwroot . '/blocks/eva_ts_view/css/style.css'));
$PAGE->requires->css(new \moodle_url('https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.25/af-2.3.7/b-1.7.1/b-colvis-1.7.1/b-html5-1.7.1/b-print-1.7.1/cr-1.5.4/date-1.1.0/fc-3.3.3/fh-3.1.9/kt-2.6.2/r-2.2.9/rg-1.1.3/rr-1.2.8/sc-2.0.4/sb-1.1.0/sp-1.3.0/sl-1.3.3/datatables.min.css'));
$PAGE->requires->js(new \moodle_url('https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js'), true);
$PAGE->requires->js(new \moodle_url('https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js'), true);
$PAGE->requires->js(new \moodle_url('https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.25/af-2.3.7/b-1.7.1/b-colvis-1.7.1/b-html5-1.7.1/b-print-1.7.1/cr-1.5.4/date-1.1.0/fc-3.3.3/fh-3.1.9/kt-2.6.2/r-2.2.9/rg-1.1.3/rr-1.2.8/sc-2.0.4/sb-1.1.0/sp-1.3.0/sl-1.3.3/datatables.min.js'), true);
require_once($CFG->dirroot . '/blocks/eva_ts_view/js/funcoesJs.php');

$PAGE->set_heading($title);

$output = $PAGE->get_renderer('block_eva_ts_view');

echo $OUTPUT->header();
echo $output->render($renderable);
echo $OUTPUT->footer();
