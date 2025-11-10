<?php
// This simple script displays all the users with pictures on one page.
// By default it is not linked anywhere on the site.  If you want to
// make it available you should link it in yourself from somewhere.
// Remember also to comment or delete the lines restricting access
// to administrators only (see below)

require('../../config.php');
global $DB, $USER, $PAGE, $OUTPUT;
//var_dump($_GET['id']);die();
$char      = required_param('modelo', PARAM_TEXT); // Course Module ID

if (!isloggedin()) {
    require_login();
}
//$exist = $DB->record_exists('eva_barema_permissao', array('user_id'=>$USER->id, 'bolsa'=>1));
if ( $char != "novo") {
    print_error('invalidaccessparameter');
}

$PAGE->set_url('/blocks/eva_barema_bolsa/criar_modelo.php?modelo='. $char);

$syscontext = context_system::instance();
//require_capability('moodle/site:config', $syscontext);

$title = "EVAGU: Novo Modelo de Bolsa";
$PAGE->set_pagelayout('admin');

$PAGE->set_context($syscontext);
$PAGE->navbar->add($title);
$PAGE->set_title($title);
$PAGE->set_heading($title);
echo $OUTPUT->header();
echo $OUTPUT->footer();

