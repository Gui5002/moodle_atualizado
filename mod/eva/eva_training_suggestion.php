<?php
global $CFG,$PAGE;

require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/blocks/eva_training_suggestion/classes/output/renderer.php');
require_once($CFG->dirroot . '/blocks/eva_training_suggestion/classes/output/training_suggestion.php');

$PAGE->set_url('/mod/eva/eva_training_suggestion.php');

require_login();

/// Remove the following three lines if you want everyone to access it
$syscontext = context_system::instance();
require_capability('mod/eva:view', $syscontext);

$renderable = new \block_eva_training_suggestion\output\training_suggestion();

$title = "Cadastrar sugestão de capacitação";
$PAGE->set_pagelayout('admin');
$PAGE->set_context($syscontext);
$PAGE->navbar->add($title);
$PAGE->set_title($title);

$PAGE->requires->css(new \moodle_url($CFG->wwwroot . '/blocks/eva_training_suggestion/css/styles.css'));
require_once($CFG->dirroot . '/blocks/eva_training_suggestion/functions.php');

$PAGE->set_heading($title);

$output = $PAGE->get_renderer('block_eva_training_suggestion');

echo $OUTPUT->header();
echo $output->render($renderable);
echo $OUTPUT->footer();
