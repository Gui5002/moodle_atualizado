<?php

class block_eva_course_rating_edit_form extends block_edit_form
{
    protected function specific_definition($mform)
    {
        global $CFG;

        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));

        // Title
        $mform->addElement('text', 'config_title', get_string('config_title', 'theme_evagu'));
        $mform->setDefault('config_title', 'Student feedback');
        $mform->setType('config_title', PARAM_RAW);

        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/edit.php');
    }

}
