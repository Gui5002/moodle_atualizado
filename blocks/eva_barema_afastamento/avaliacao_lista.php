<?php
// This simple script displays all the users with pictures on one page.
// By default it is not linked anywhere on the site.  If you want to
// make it available you should link it in yourself from somewhere.
// Remember also to comment or delete the lines restricting access
// to administrators only (see below)

require('../../config.php');
global $PAGE, $OUTPUT;

if (!isloggedin()) {
    require_login();
}
$PAGE->set_url('/blocks/eva_barema_afastamento/avaliacao_lista.php');
$syscontext = context_system::instance();

$title = "EVAGU: Barema para Afastamento de Estudo";
$PAGE->set_pagelayout('admin');

$PAGE->set_context($syscontext);
$PAGE->navbar->add($title);
$PAGE->set_title($title);
$PAGE->set_heading($title);
echo $OUTPUT->header();
echo $OUTPUT->footer();

