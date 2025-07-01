<?php

class block_eva_course_overview_edit_form extends block_edit_form
{
    protected function specific_definition($mform)
    {
        global $CFG;

        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));

        $mform->addElement('text', 'config_title', get_string('config_title', 'theme_evagu'));
        $mform->setDefault('config_title', 'Overview');
        $mform->setType('config_title', PARAM_RAW);

        $mform->addElement('editor', 'config_description', get_string('config_body', 'theme_evagu'));
        $mform->setType('config_description', PARAM_RAW);

        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/edit.php');
    }

}
