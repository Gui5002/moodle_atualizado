<?php
// This simple script displays all the users with pictures on one page.
// By default it is not linked anywhere on the site.  If you want to
// make it available you should link it in yourself from somewhere.
// Remember also to comment or delete the lines restricting access
// to administrators only (see below)

    require('../../config.php');
    global $DB, $PAGE, $USER, $CFG;

    $baremaid      = optional_param('baremaid', 0, PARAM_INT); // Course Module ID
//    $novo       = optional_param('novo', 0, PARAM_INT);  // Page instance ID
//
//    if ($baremaid) {
//        if (!$br = $DB->get_record('eva_barema_avaliacao', array('id'=>$baremaid))) {
//            print_error('invalidcoursemodule');
//        }
//
//    }
//    $returnurl = optional_param('returnurl', '/blocks/eva_form_barema/avaliadores.php?baremaid='. $db->id, PARAM_LOCALURL);
//    $returnurl = new moodle_url($returnurl);

    $PAGE->set_url('/blocks/eva_form_barema/avaliadores.php?baremaid='. $baremaid);
    $syscontext = context_system::instance();
    require_capability('moodle/site:config', $syscontext);

    $title = "EVAGU: Barema";
    $PAGE->set_pagelayout('admin');

    $PAGE->set_context($syscontext);
    $PAGE->navbar->add($title);
    $PAGE->set_title($title);
    $PAGE->set_heading($title);
    echo $OUTPUT->header();
    echo $OUTPUT->footer();
