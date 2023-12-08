<?php

defined('MOODLE_INTERNAL') || die();

class block_eva_library_central_edit_form extends block_edit_form {

    public function specific_definition($mform) {
        global $CFG, $PAGE;

        $ccnFontList = include($CFG->dirroot . '/theme/evagu/ccn/font_handler/ccn_font_select.php');

        if (!empty($this->block->config) && is_object($this->block->config)) {
            $data = $this->block->config;
        } else {
            $data = new stdClass();
            $data->items = 4;
        }

        // language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));

        // Title
        $mform->addElement('text', 'config_title', get_string('config_title', 'theme_evagu'));
        //$mform->setDefault('config_title', 'What We Do');
        $mform->setType('config_title', PARAM_RAW);

        // Body
        $editoroptions = array('maxfiles' => EDITOR_UNLIMITED_FILES, 'noclean'=>true, 'context'=>$this->block->context);
        $mform->addElement('editor', 'config_body', get_string('config_body', 'theme_evagu'), null, $editoroptions);
        $mform->addRule('config_body', null, 'required', null, 'client');
        $mform->setType('config_body', PARAM_RAW); // XSS is prevented when printing the block contents and serving files

    include($CFG->dirroot . '/theme/evagu/ccn/block_handler/edit.php');


    }

    function set_data($defaults)
    {

        //var_dump($defaults);die();
        // Begin CCN Image Processing
        if (empty($entry->id)) {
            $entry = new stdClass;
            $entry->id = null;
        }
        $draftitemid = file_get_submitted_draft_itemid('config_image');
        file_prepare_draft_area($draftitemid, $this->block->context->id, 'block_eva_library_central', 'content', 0, array('subdirs' => true));
        $entry->attachments = $draftitemid;
        parent::set_data($defaults);
        if ($data = parent::get_data()) {
            file_save_draft_area_files($data->config_image, $this->block->context->id, 'block_eva_library_central', 'content', 0,
                array('subdirs' => true));
        }
        // END CCN Image Processing

        if (!empty($this->block->config) && is_object($this->block->config)) {
            $text = $this->block->config->body;
            $draftid_editor = file_get_submitted_draft_itemid('config_body');
            if (empty($text)) {
                $currenttext = '';
            } else {
                $currenttext = $text;
            }
            $defaults->config_body['text'] = file_prepare_draft_area($draftid_editor, $this->block->context->id, 'block_eva_library_central', 'content', 0, array('subdirs'=>true), $currenttext);
            $defaults->config_body['itemid'] = $draftid_editor;
            $defaults->config_body['format'] = $this->block->config->format;
        } else {
            $text = '';
        }


    }
}