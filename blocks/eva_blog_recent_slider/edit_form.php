<?php

class block_eva_blog_recent_slider_edit_form extends block_edit_form {
    protected function specific_definition($mform) {

        global $CFG;

        // Fields for editing HTML block title and contents.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        // Title
        $mform->addElement('text', 'config_title', get_string('config_title', 'theme_evagu'));
        $mform->setDefault('config_title', 'Noticia');
        $mform->setType('config_title', PARAM_RAW);

        // Subtitle
        $mform->addElement('text', 'config_subtitle', get_string('config_subtitle', 'theme_evagu'));
        $mform->setDefault('config_subtitle', 'texto para o edito de noticias.');
        $mform->setType('config_subtitle', PARAM_RAW);

        $mform->addElement('header', 'config_ccn_colors', get_string('block_styles', 'theme_evagu'));

        $mform->addElement('text', 'config_color_bg', get_string('config_color_bg', 'theme_evagu'), array('class'=>'ccn_spectrum_class'));
        $mform->setDefault('config_color_bg', '#fff');
        $mform->setType('config_color_bg', PARAM_TEXT);

        $mform->addElement('text', 'config_color_title', get_string('config_color_title', 'theme_evagu'), array('class'=>'ccn_spectrum_class'));
        $mform->setDefault('config_color_title', '#0a0a0a');
        $mform->setType('config_color_title', PARAM_TEXT);

        $mform->addElement('text', 'config_color_subtitle', get_string('config_color_subtitle', 'theme_evagu'), array('class'=>'ccn_spectrum_class'));
        $mform->setDefault('config_color_subtitle', '#6f7074');
        $mform->setType('config_color_subtitle', PARAM_TEXT);

        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/edit.php');

    }
}
