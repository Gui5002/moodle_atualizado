<?php
/**
 * @author Celio Pereira Batalha
 * email: celio.batalha@gmail.com
 */

require('../../config.php');
global $DB, $PAGE, $USER, $CFG;

$hash_atrib      = required_param('atrib', PARAM_TEXT); // Course Module ID

if (!isloggedin()) {
    require_login();
}


$sql = "SELECT * FROM mdl_eva_bolsa_atribuicao WHERE url_atrib = '{$hash_atrib}'";
//$access = $DB->get_record_sql($sql);
if (!$access = $DB->get_record_sql($sql)) {
    print_error('invalidaccessparameter');
}
if ($access->avaliador_tb_user_id != $USER->id) {
    print_error('invaliduser');
}


//$PAGE->set_url('/blocks/eva_barema_bolsa/avaliacao_bolsa.php?barema_id=1');


$PAGE->set_url('/blocks/eva_barema_bolsa/avaliacao_bolsa.php?atrib='.$hash_atrib);

$syscontext = context_system::instance();

$title = "EVAGU: Avaliacao para Bolsa";
$PAGE->set_pagelayout('admin');

$PAGE->set_context($syscontext);
$PAGE->navbar->add($title);
$PAGE->set_title($title);
$PAGE->set_heading($title);
echo $OUTPUT->header();
echo $OUTPUT->footer();

