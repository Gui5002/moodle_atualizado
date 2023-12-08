<?php

require('../../config.php');
global $DB, $PAGE, $USER, $CFG;

$avaliadorid      = optional_param('avaliador', 0,  PARAM_INT); // Course Module ID
$alunos      = optional_param('qt_aluno_por_avaliador', "", PARAM_TEXT); // Course Module ID
$admin      = optional_param('admin', "", PARAM_TEXT); // Course Module ID
$avaliacao      = optional_param('avaliacao', "", PARAM_TEXT); // Course Module ID

if (!isloggedin()) {
    require_login();
}
$existe = $DB->record_exists('eva_barema_permissao', array('user_id'=>$USER->id, 'posgraduacao'=>1));

$confirmausuario = $DB->get_field('eva_barema_avaliador', 'avaliador_tb_user_id', array('id'=>$alunos));


if (($avaliadorid != $USER->id) && !($confirmausuario == $USER->id) && !($admin == 'avaliadores' || $admin == 'curso' || $admin == 'alunos' || $admin == 'avaliacao_pendente')){
    print_error('nopermissiontoshow');
}elseif ($alunos) {
    $url = '/blocks/eva_form_barema/gerencia.php?qt_aluno_por_avaliador=' . $alunos;
    $titulo = "Gerenciamento das Avaliações dos Alunos";
}elseif ($avaliadorid){
    $url = '/blocks/eva_form_barema/gerencia.php?avaliador='.$avaliadorid;
    $titulo = "Portal do Avaliador";
}elseif ($admin && $existe){
    $url = '/blocks/eva_form_barema/gerencia.php?admin='.$admin;
    $titulo = "Area Administrativa das Avalições";
}else{
    print_error('nopermissiontoshow');
}

$PAGE->set_url($url);

$syscontext = context_system::instance();
//require_capability('moodle/site:config', $syscontext);

$title = "EVAGU: ". $titulo;
$PAGE->set_pagelayout('admin');

$PAGE->set_context($syscontext);
$PAGE->navbar->add($title);
$PAGE->set_title($title);
$PAGE->set_heading($title);
echo $OUTPUT->header();
echo $OUTPUT->footer();

