<?php
// This simple script displays all the users with pictures on one page.
// By default it is not linked anywhere on the site.  If you want to
// make it available you should link it in yourself from somewhere.
// Remember also to comment or delete the lines restricting access
// to administrators only (see below)

require('../../../config.php');
global $PAGE, $OUTPUT, $USER;
//var_dump($_GET['id']);die();
$id      = required_param('id', PARAM_INT); // Course Module ID

if (!isloggedin()) {
    require_login();
}

$exist = $DB->record_exists('eva_barema_permissao', array('user_id'=>$USER->id, 'posgraduacao'=>1));
if (!$exist) {
    print_error('nopermissiontoshow');
}

$PAGE->set_url('/blocks/eva_form_barema/atribuicao/create.php?id='.$id);

$syscontext = context_system::instance();
//require_capability('moodle/site:config', $syscontext);

$title = "EVAGU: Barema Pós Graduação - Atribuição";
$PAGE->set_pagelayout('admin');

$PAGE->set_context($syscontext);
$PAGE->navbar->add($title);
$PAGE->set_title($title);
$PAGE->set_heading($title);
echo $OUTPUT->header();
echo $OUTPUT->footer();

