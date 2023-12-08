<?php

defined('MOODLE_INTERNAL') || die();

class block_eva_continuing_education_b2_edit_form extends block_edit_form {

    public function specific_definition($mform) {
        global $CFG, $PAGE;

        // language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));

        // Title
        $mform->addElement('text', 'config_title', get_string('config_title', 'theme_evagu'));
        $mform->setType('config_title', PARAM_RAW);

        // Blockquote
        $mform->addElement('text', 'config_blockquote', get_string('config_blockquote', 'block_eva_continuing_education_b2'));
        $mform->setType('config_blockquote', PARAM_RAW);

        // Text
        $mform->addElement('text', 'config_text', get_string('config_text', 'theme_evagu'));
        $mform->setType('config_text', PARAM_RAW);

        // Button text
        $mform->addElement('text', 'config_button', get_string('config_button', 'theme_evagu'));
        $mform->setType('config_button', PARAM_RAW);

        // URL
        $mform->addElement('text', 'config_url', get_string('config_url', 'block_eva_continuing_education_b2'));
        $mform->setType('config_url', PARAM_RAW);

        // Box color
        $mform->addElement('text', 'config_color', get_string('config_color_bg', 'theme_evagu'), array('class' => 'ccn_spectrum_class'));
        $mform->setDefault('config_color', 'rgb(239, 242, 252)');
        $mform->setType('config_color', PARAM_TEXT);

        // Title color
        $mform->addElement('text', 'config_title_color', get_string('config_title_color', 'block_eva_continuing_education_b2'), array('class' => 'ccn_spectrum_class'));
        $mform->setDefault('config_title_color', 'rgb(111, 128, 158)');
        $mform->setType('config_title_color', PARAM_TEXT);

        // Button color
        $mform->addElement('text', 'config_button_color', get_string('config_button_color', 'block_eva_continuing_education_b2'), array('class' => 'ccn_spectrum_class'));
        $mform->setDefault('config_button_color', 'rgb(111, 128, 158)');
        $mform->setType('config_button_color', PARAM_TEXT);

        // Button color hover
        $mform->addElement('text', 'config_button_color_hover', get_string('config_button_color_hover', 'block_eva_continuing_education_b2'), array('class' => 'ccn_spectrum_class'));
        $mform->setDefault('config_button_color_hover', 'rgb(164, 164, 165)');
        $mform->setType('config_button_color_hover', PARAM_TEXT);

        // Button text color
        $mform->addElement('text', 'config_button_text_color', get_string('config_button_text_color', 'block_eva_continuing_education_b2'), array('class' => 'ccn_spectrum_class'));
        $mform->setDefault('config_button_text_color', 'rgb(255, 255, 255)');
        $mform->setType('config_button_text_color', PARAM_TEXT);

        $mform->addElement('filemanager', 'config_image', get_string('config_image', 'theme_evagu'), null,
            array('subdirs' => 0, 'maxbytes' => $maxbytes, 'areamaxbytes' => 10485760, 'maxfiles' => 1,
                'accepted_types' => array('.png', '.jpg', '.jpeg', '.gif') ));


        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/edit.php');
    }

    function set_data($defaults)
    {
        $field = 'image';
        $conffield = 'config_image';
        $draftitemid = file_get_submitted_draft_itemid($conffield);
        file_prepare_draft_area($draftitemid, $this->block->context->id, 'block_eva_continuing_education_b2', 'content', 0, array('subdirs'=>false));
        $defaults->$conffield['itemid'] = $draftitemid;
        $this->block->config->$field = $draftitemid;
        // Begin CCN Image Processing
        // if (empty($entry->id)) {
        //     $entry = new stdClass;
        //     $entry->id = null;
        // }

        // $draftitemid = file_get_submitted_draft_itemid('config_image');
        // file_prepare_draft_area($draftitemid, $this->block->context->id, 'block_eva_continuing_education_b2', 'content', 0, array('subdirs' => true));
        // $entry->attachments = $draftitemid;
        parent::set_data($defaults);
        
        if ($data = parent::get_data()) {
            file_save_draft_area_files($data->config_image, $this->block->context->id, 'block_eva_continuing_education_b2', 'content', 0,
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
            $defaults->config_body['text'] = file_prepare_draft_area($draftid_editor, $this->block->context->id, 'block_eva_continuing_education_b2', 'content', 0, array('subdirs'=>true), $currenttext);
            $defaults->config_body['itemid'] = $draftid_editor;
            $defaults->config_body['format'] = $this->block->config->format;
        } else {
            $text = '';
        }
    }
}