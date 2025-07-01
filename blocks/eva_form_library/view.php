<?php

require('../../config.php');
global $DB, $USER, $PAGE, $OUTPUT;

$PAGE->set_url('/blocks/eva_form_library/view.php');

$syscontext = context_system::instance();
//require_capability('moodle/site:config', $syscontext);

$title = "EVAGU: Solicitação de Livros";
$PAGE->set_pagelayout('');

$PAGE->set_context($syscontext);
$PAGE->navbar->add($title);
$PAGE->set_title($title);
$PAGE->set_heading($title);
echo $OUTPUT->header();
echo $OUTPUT->footer();


