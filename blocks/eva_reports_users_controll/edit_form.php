<?php

class block_eva_reports_users_controll_edit_form extends block_edit_form {
    protected function specific_definition($mform) {
        global $CFG;

        if (!empty($this->block->config) && is_object($this->block->config)) {
            $data = $this->block->config;
        } else {
            $data = new stdClass();
        }

        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/edit.php');
    }
}
