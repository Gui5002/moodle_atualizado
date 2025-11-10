<?php

class block_eva_training_suggestion_edit_form extends block_edit_form {
    protected function specific_definition($mform) {
        global $CFG;

        if (!empty($this->block->config) && is_object($this->block->config)) {
            $data = $this->block->config;
        } else {
            $data = new stdClass();
        }


        // Fields for editing HTML block title and contents.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        // URL
        $mform->addElement('text', 'config_url', get_string('config_url', 'block_eva_training_suggestion'));
        $mform->setDefault('config_url', '#');
        $mform->setType('config_url', PARAM_RAW);

        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/edit.php');

    }
}
