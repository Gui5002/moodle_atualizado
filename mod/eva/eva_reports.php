<?php
  // This simple script displays all the users with pictures on one page.
  // By default it is not linked anywhere on the site.  If you want to
  // make it available you should link it in yourself from somewhere.
  // Remember also to comment or delete the lines restricting access
  // to administrators only (see below)


require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/blocks/eva_reports/classes/output/renderer.php');
require_once($CFG->dirroot . '/blocks/eva_reports/classes/output/reports.php');

$PAGE->set_url('/mod/eva/eva_reports.php');
$id = optional_param('id', null, PARAM_INT);

require_login();

/// Remove the following three lines if you want everyone to access it
$syscontext = context_system::instance();
require_capability('mod/eva:view', $syscontext);

$renderable = new \block_eva_reports\output\reports();

$subtitle = "";
if($id == 1){
    $subtitle = " - Cursos e categorias";
}else if($id == 2){
    $subtitle = " - Resultados por curso";
}else if($id == 3){
    $subtitle = " - Usuários geral";
}else if($id == 4){
    $subtitle = " - Consolidado por curso";
}else if($id == 5){
    $subtitle = " - Consolidado EVA";
}else if($id == 0 && $id == true){
    $subtitle = " - Conclusão";
}

$title = "Relatórios";
$title = $title . $subtitle;
$PAGE->set_pagelayout('admin');
$PAGE->set_context($syscontext);
$PAGE->navbar->add($title);
$PAGE->set_title($title);

$PAGE->requires->css(new \moodle_url($CFG->wwwroot . '/blocks/eva_reports/css/style.css'));
$PAGE->requires->css(new \moodle_url($CFG->wwwroot . '/theme/evagu/style/bootstrap-datepicker.css'));
$PAGE->requires->css(new \moodle_url('https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.11.5/af-2.3.7/b-2.2.2/b-colvis-2.2.2/b-html5-2.2.2/b-print-2.2.2/cr-1.5.5/date-1.1.2/fc-4.0.2/fh-3.2.2/kt-2.6.4/r-2.2.9/rg-1.1.4/rr-1.2.8/sc-2.0.5/sb-1.3.2/sp-2.0.0/sl-1.3.4/sr-1.1.0/datatables.min.css'));
$PAGE->requires->css(new \moodle_url('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'));
$PAGE->requires->js(new \moodle_url('https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js'), true);
$PAGE->requires->js(new \moodle_url('https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/vfs_fonts.js'), true);
$PAGE->requires->js(new \moodle_url('https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.25/af-2.3.7/b-1.7.1/b-colvis-1.7.1/b-html5-1.7.1/b-print-1.7.1/cr-1.5.4/date-1.1.0/fc-3.3.3/fh-3.1.9/kt-2.6.2/r-2.2.9/rg-1.1.3/rr-1.2.8/sc-2.0.4/sb-1.1.0/sp-1.3.0/sl-1.3.3/datatables.min.js'), true);
$PAGE->requires->js(new \moodle_url('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'), true);
$PAGE->requires->js(new \moodle_url('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js'),true);
$PAGE->requires->js(new \moodle_url($CFG->wwwroot . '/theme/evagu/javascript/bootstrap-datepicker.js'),true);
$PAGE->requires->js(new \moodle_url($CFG->wwwroot . '/blocks/eva_reports/locales/bootstrap-datepicker.pt-BR.min.js'),true);
$PAGE->requires->js(new \moodle_url('https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js'),true);
require_once($CFG->dirroot . '/blocks/eva_reports/js/funcoesJs.php');

$PAGE->set_heading($title);

$output = $PAGE->get_renderer('block_eva_reports');

echo $OUTPUT->header();
echo $output->render($renderable);
echo $OUTPUT->footer();
