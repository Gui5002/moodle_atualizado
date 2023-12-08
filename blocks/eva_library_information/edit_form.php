<?php

defined('MOODLE_INTERNAL') || die();

class block_eva_library_information_edit_form extends block_edit_form {

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

        $ccnItemsRange = array(
            1 => '1',
            2 => '2',
            3 => '3',
            4 => '4',
            5 => '5',
            6 => '6',

        );

        $ccnItemsMax = 6;

        $mform->addElement('select', 'config_items', get_string('config_items', 'theme_evagu'), $ccnItemsRange, array('class'=>'ccnCommLcRef_change'));
        $mform->setDefault('config_items', 2);

        for($i = 1; $i <= $ccnItemsMax; $i++) {
            $mform->addElement('header', 'config_ccn_item'.$i , get_string('config_item', 'theme_evagu') . $i);


            //        // Title
            $mform->addElement('text', 'config_title'.$i, get_string('config_title', 'theme_evagu', $i));
            //$mform->setDefault('config_title', 'What We Do');
            $mform->setType('config_title'.$i, PARAM_RAW);

            // Body
            $mform->addElement('editor', 'config_body'.$i, get_string('config_body', 'theme_evagu', $i));
            $mform->setType('config_body'.$i, PARAM_RAW);


            $mform->addElement('text', 'config_title1'.$i, get_string('config_title', 'theme_evagu', $i));
            $mform->setDefault('config_title1'.$i, 'Information');
            $mform->setType('config_title1'.$i, PARAM_TEXT);

            $mform->addElement('textarea', 'config_subtitle1'.$i, get_string('config_body', 'theme_evagu', $i));
            $mform->setDefault('config_subtitle1'.$i , 'info@evagu.com');
            $mform->setType('config_subtitle1'.$i, PARAM_RAW);

            $mform->addElement('text', 'config_title2'.$i, get_string('config_title', 'theme_evagu', $i));
            $mform->setDefault('config_title2'.$i, 'Information');
            $mform->setType('config_title2'.$i, PARAM_TEXT);

            $mform->addElement('textarea', 'config_subtitle2'.$i, get_string('config_body', 'theme_evagu', $i));
            $mform->setDefault('config_subtitle2'.$i , 'info@evagu.com');
            $mform->setType('config_subtitle2'.$i, PARAM_RAW);

            $mform->addElement('text', 'config_title3'.$i, get_string('config_title', 'theme_evagu', $i));
            $mform->setDefault('config_title3'.$i, 'Information');
            $mform->setType('config_title3'.$i, PARAM_TEXT);

            $mform->addElement('textarea', 'config_subtitle3'.$i, get_string('config_body', 'theme_evagu', $i));
            $mform->setDefault('config_subtitle3'.$i , 'info@evagu.com');
            $mform->setType('config_subtitle3'.$i, PARAM_RAW);

        }

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
        file_prepare_draft_area($draftitemid, $this->block->context->id, 'block_eva_library_information', 'content', 0, array('subdirs' => true));
        $entry->attachments = $draftitemid;
        parent::set_data($defaults);
        if ($data = parent::get_data()) {
            file_save_draft_area_files($data->config_image, $this->block->context->id, 'block_eva_library_information', 'content', 0,
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
            $defaults->config_body['text'] = file_prepare_draft_area($draftid_editor, $this->block->context->id, 'block_eva_library_information', 'content', 0, array('subdirs'=>true), $currenttext);
            $defaults->config_body['itemid'] = $draftid_editor;
            $defaults->config_body['format'] = $this->block->config->format;
        } else {
            $text = '';
        }


    }
}