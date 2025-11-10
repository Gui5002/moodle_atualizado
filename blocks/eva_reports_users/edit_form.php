<?php

class block_eva_reports_users_edit_form extends block_edit_form {
    protected function specific_definition($mform) {
        global $CFG;

        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));

        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/edit.php');
    }
}
