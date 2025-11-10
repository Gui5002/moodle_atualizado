<?php
class block_eva_blog_recent_edit_form extends block_edit_form {
    protected function specific_definition($mform) {
        global $CFG;
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));
        // Title
        $mform->addElement('text', 'config_title', get_string('config_title', 'block_eva_blog_recent'));
        $mform->setDefault('config_title', 'Últimas notícias e eventos ESAGU');
        $mform->setType('config_title', PARAM_RAW);
        // Subtitle
        $mform->addElement('text', 'config_subtitle', get_string('config_subtitle', 'block_eva_blog_recent'));
        $mform->setDefault('config_subtitle', 'Texto complementar do titulo de forma reduzida modelo.');
        $mform->setType('config_subtitle', PARAM_RAW);
        // botão
        $mform->addElement('text', 'config_button_text', get_string('config_button_text', 'theme_evagu'));
        $mform->setDefault('config_button_text', 'Veja mais');
        $mform->setType('config_button_text', PARAM_RAW);
        $mform->addElement('text', 'config_button_link', get_string('config_button_link', 'theme_evagu'));
        $mform->setDefault('config_button_link', '/blog/index.php');
        $mform->setType('config_button_link', PARAM_RAW);
        include ($CFG->dirroot . '/theme/evagu/ccn/block_handler/edit.php');
    }
}
