<?php

defined('MOODLE_INTERNAL') || die();

class block_eva_postgraduate_contact_edit_form extends block_edit_form {

    public function specific_definition($mform) {
        global $CFG, $PAGE;

        $ccnFontList = include($CFG->dirroot . '/theme/evagu/ccn/font_handler/ccn_font_select.php');
        if (!empty($this->block->config) && is_object($this->block->config)) {
            $ccnStorage = $this->block->config;
        } else {
            $ccnStorage = new stdClass();
            $ccnStorage->items = 3;
        }

        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        $mform->addElement('filemanager', 'config_image', get_string('config_image', 'theme_evagu'), null,
            array('subdirs' => 0, 'maxbytes' => $maxbytes, 'areamaxbytes' => 10485760, 'maxfiles' => 1,
                'accepted_types' => array('.png', '.jpg', '.gif') ));

        $radioarray=array();
        $radioarray[] = $mform->createElement('radio', 'config_style', '', 'Image Left', 0, $attributes);
        $radioarray[] = $mform->createElement('radio', 'config_style', '', 'Image Right', 1, $attributes);
        $mform->addGroup($radioarray, 'config_style', 'Style', array(' '), false);


        $ccnItemsRange = array(
            0 => '0',
            1 => '1',
            2 => '2',
            3 => '3',
            4 => '4',
            5 => '5',
            6 => '6',
            7 => '7',
            8 => '8',
            9 => '9',
            10 => '10',
            11 => '11',
            12 => '12',
        );

        $ccnItemsMax = 12;

        $mform->addElement('select', 'config_items', get_string('config_items', 'theme_evagu'), $ccnItemsRange, array('class'=>'ccnCommLcRef_change'));
        $mform->setDefault('config_items', 3);

        for($i = 1; $i <= $ccnItemsMax; $i++) {
            $mform->addElement('header', 'config_ccn_item'.$i , get_string('config_item', 'theme_evagu') . $i);

            $mform->addElement('text', 'config_title_'.$i, get_string('config_title', 'theme_evagu', $i));
            $mform->setDefault('config_title_'.$i, 'Our Email');
            $mform->setType('config_title_'.$i, PARAM_TEXT);

            $mform->addElement('textarea', 'config_subtitle_'.$i, get_string('config_body', 'theme_evagu', $i));
            $mform->setDefault('config_subtitle_'.$i , 'info@evagu.com');
            $mform->setType('config_subtitle_'.$i, PARAM_RAW);

            $select = $mform->addElement('select', 'config_icon_'.$i, get_string('config_icon_class', 'theme_evagu'), $ccnFontList, array('class'=>'ccn_icon_class'));
            $select->setSelected('flaticon-email');

        }

        $options = array(
            '0' => 'Display reCAPTCHA to guests',
            '1' => 'Display reCAPTCHA to all users',
            '2' => 'Do not display reCAPTCHA',
        );
        $select = $mform->addElement('select', 'config_recaptcha', get_string('config_recaptcha', 'theme_evagu'), $options);
        $select->setSelected('0');


        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/edit.php');

    }

    function set_data($defaults)
    {
        $field = 'image';
        $conffield = 'config_image';
        $draftitemid = file_get_submitted_draft_itemid($conffield);
        file_prepare_draft_area($draftitemid, $this->block->context->id, 'block_eva_postgraduate_contact', 'content', 0, array('subdirs'=>false));
        $defaults->$conffield['itemid'] = $draftitemid;
        $this->block->config->$field = $draftitemid;
        //var_dump($defaults);die();
        // Begin CCN Image Processing
        // if (empty($entry->id)) {
        //     $entry = new stdClass;
        //     $entry->id = null;
        // }
        // $draftitemid = file_get_submitted_draft_itemid('config_image');
        // file_prepare_draft_area($draftitemid, $this->block->context->id, 'block_eva_postgraduate_contact', 'content', 0, array('subdirs' => true));
        // $entry->attachments = $draftitemid;
        parent::set_data($defaults);
        if ($data = parent::get_data()) {
            file_save_draft_area_files($data->config_image, $this->block->context->id, 'block_eva_postgraduate_contact', 'content', 0,
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
            $defaults->config_body['text'] = file_prepare_draft_area($draftid_editor, $this->block->context->id, 'block_eva_postgraduate_contact', 'content', 0, array('subdirs'=>true), $currenttext);
            $defaults->config_body['itemid'] = $draftid_editor;
            $defaults->config_body['format'] = $this->block->config->format;
        } else {
            $text = '';
        }


    }



}
