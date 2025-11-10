<?php

class block_eva_reports_edit_form extends block_edit_form {
    protected function specific_definition($mform) {
        global $CFG;

        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));

        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/edit.php');
    }

    // function set_data($defaults) {
    //     $field = 'image';
    //     $conffield = 'config_image';
    //     $draftitemid = file_get_submitted_draft_itemid($conffield);
    //     file_prepare_draft_area($draftitemid, $this->block->context->id, 'block_eva_abouteva', 'content', 0, array('subdirs'=>false));
    //     $defaults->$conffield['itemid'] = $draftitemid;
    //     $this->block->config->$field = $draftitemid;
    //     parent::set_data($defaults);
        
    //     if ($data = parent::get_data()) {
    //         file_save_draft_area_files($data->config_image, $this->block->context->id, 'block_eva_abouteva', 'content', 0,
    //             array('subdirs' => true));
    //     }
    // }
}
