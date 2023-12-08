<?php

require('../../config.php');
global $DB, $PAGE, $USER, $CFG;

//$hash_atrib      = required_param('gerenciar', PARAM_TEXT); // Course Module ID
$idadmin      = optional_param('admin', 0,  PARAM_INT); // Course Module ID
$idgerenciar      = optional_param('gerenciar', 0, PARAM_TEXT); // Course Module ID
$idespelho      = optional_param('espelho', 0, PARAM_INT); // Course Module ID

if (!isloggedin()) {
    require_login();
}

$userid = md5('userid='.$USER->id);
//var_dump($userid);die();
//
if (($idadmin <= 0 && $idgerenciar <= "0")) {
    print_error('invalidaccessparameter');
}elseif ($idadmin == $USER->id) {
    $exist = $DB->record_exists('eva_barema_permissao', array('user_id'=>$idadmin, 'bolsa'=>1));
    if ($exist)
        $url = '/blocks/eva_barema_bolsa/avaliacao.php?admin='.$idadmin;
    else
        print_error('nopermissiontoshow');

}elseif ($idgerenciar == $userid) {
    if ($idespelho > 0){
        $url = '/blocks/eva_barema_bolsa/avaliacao.php?gerenciar='.$idgerenciar.'&espelho='.$idespelho;
    }else{
        $url = '/blocks/eva_barema_bolsa/avaliacao.php?gerenciar='.$idgerenciar;
    }
}else {
    print_error('nopermissiontoshow');
}


//$PAGE->set_url('/blocks/eva_barema_bolsa/avaliacao.php?admin='.$idadmin);
$PAGE->set_url($url);

$syscontext = context_system::instance();
//require_capability('moodle/site:config', $syscontext);

$title = "EVAGU: Avaliacao para Bolsa";
$PAGE->set_pagelayout('admin');

$PAGE->set_context($syscontext);
$PAGE->navbar->add($title);
$PAGE->set_title($title);
$PAGE->set_heading($title);
echo $OUTPUT->header();
echo $OUTPUT->footer();

