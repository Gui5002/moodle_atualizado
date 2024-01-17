<?php

require('../../config.php');
global $DB, $PAGE, $USER, $CFG;

$barema_id      = required_param('barema_id',PARAM_INT); // Course Module ID
$avaliador_id   = required_param('avaliador_id',PARAM_INT); // Course Module ID
$curso_id        = required_param('curso_id',PARAM_INT); // Course Module ID
$quiz_id        = required_param('quiz_id',PARAM_INT); // Course Module ID


if (!isloggedin()) {
    require_login();
}
if (!$quiz_id){
    print_error('invalidaccessparameter');
}
if ($USER->id != $avaliador_id) {
    print_error('invalidaccessparameter');
}
if (!$br = $DB->get_record('eva_barema', array('id'=>$barema_id))) {
    print_error('invalidaccessparameter');
}else{
    if (!$access = $DB->get_record('eva_barema_avaliador', array('tb_barema_id'=>$barema_id, 'avaliador_tb_user_id'=>$avaliador_id))) {
        print_error('invalidaccessparameter');
    }
}

$PAGE->set_url('/blocks/eva_form_barema/barema_avaliacao.php?barema_id='.$barema_id.'&avaliador_id='.$avaliador_id.'&curso_id='.$curso_id.'&quiz_id='.$quiz_id);

$syscontext = context_system::instance();

$title = "EVAGU: Barema";
$PAGE->set_pagelayout('admin');

$PAGE->set_context($syscontext);
$PAGE->navbar->add($title);
$PAGE->set_title($title);
$PAGE->set_heading($title);
echo $OUTPUT->header();
echo $OUTPUT->footer();

