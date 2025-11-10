<?php
require('../../config.php');

require_login();

$context = context_system::instance();
require_capability('moodle/course:view', $context);

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/blocks/eva_catalogo/view.php'));
$PAGE->set_title(get_string('pluginname', 'block_eva_catalogo'));
$PAGE->set_heading(get_string('pluginname', 'block_eva_catalogo'));
$PAGE->set_pagelayout('standard');

$PAGE->requires->css(new moodle_url('/blocks/eva_catalogo/styles.css'));
$PAGE->requires->js(new moodle_url('/blocks/eva_catalogo/js/catalogo-dinamico.js'));

$renderer = $PAGE->get_renderer('block_eva_catalogo');

echo $OUTPUT->header();
echo $renderer->render_catalogo_page();
echo $OUTPUT->footer();
