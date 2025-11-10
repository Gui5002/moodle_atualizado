<?php

require('../../config.php');
require_once ('classes/output/avaliacao_config_view.php');
    global $DB, $PAGE, $USER, $CFG, $COURSE, $OUTPUT;


    $espaco = htmlentities($_GET);
    $espaco = str_replace('_',' ',$espaco);
    echo html_entity_decode($espaco) ;

    $PAGE->set_url('/blocks/eva_form_barema/avaliacao_view.php?attemp=view');
    $syscontext = context_system::instance();
//var_dump($syscontext);die();

//    require_capability('moodle/site:config', $syscontext);

//    $title = "EVAGU: Barema";
//    $PAGE->set_pagelayout('admin');

//    $PAGE->set_context($syscontext);
//    $PAGE->navbar->add($title);
//    $PAGE->set_title($title);
//    $PAGE->set_heading($title);
//    echo $OUTPUT->header();
