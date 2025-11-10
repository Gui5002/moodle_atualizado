<?php

class block_eva_gallery_edit_form extends block_edit_form
{
    protected function specific_definition($mform)
    {
        global $CFG;


        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));


        $mform->addElement('text', 'config_title', get_string('config_title', 'theme_evagu'));
        $mform->setDefault('config_title', 'Nome da galeria do evento');
        $mform->setType('config_title', PARAM_RAW);


        $mform->addElement('text', 'config_subtitle', get_string('config_subtitle', 'theme_evagu'));
        $mform->setDefault('config_subtitle', 'Breve texto falando sobre a galeria slider para editor.');
        $mform->setType('config_subtitle', PARAM_RAW);

        // Image
        $mform->addElement(
            'filemanager',
            'config_image',
            get_string('config_image', 'block_eva_gallery'),
            null,
            array(
                'subdirs' => 0, 'maxbytes' => $maxbytes, 'areamaxbytes' => 10485760, 'maxfiles' => null,
                'accepted_types' => array('.png', '.jpg', '.gif')
            )
        );

        $radioarray = array();
        $radioarray[] = $mform->createElement('radio', 'config_columns', '', '1', 0, $attributes);
        $radioarray[] = $mform->createElement('radio', 'config_columns', '', '2', 1, $attributes);
        $radioarray[] = $mform->createElement('radio', 'config_columns', '', '3', 2, $attributes);
        $radioarray[] = $mform->createElement('radio', 'config_columns', '', '4', 3, $attributes);
        $radioarray[] = $mform->createElement('radio', 'config_columns', '', '6', 4, $attributes);
        $mform->addGroup($radioarray, 'config_columns', get_string('config_columns', 'theme_evagu'), array(' '), false);

        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/edit.php');
    }

    function set_data($defaults)
    {
        $field = 'image';
        $conffield = 'config_image';
        $draftitemid = file_get_submitted_draft_itemid($conffield);
        file_prepare_draft_area($draftitemid, $this->block->context->id, 'block_eva_gallery', 'content', 0, array('subdirs'=>false));
        $defaults->$conffield['itemid'] = $draftitemid;
        $this->block->config->$field = $draftitemid;
        // if (empty($entry->id)) {
        //     $entry = new stdClass;
        //     $entry->id = null;
        // }
        // $draftitemid = file_get_submitted_draft_itemid('config_image');
        // file_prepare_draft_area(
        //     $draftitemid,
        //     $this->block->context->id,
        //     'block_eva_gallery',
        //     'content',
        //     0,
        //     array('subdirs' => true)
        // );
        // $entry->attachments = $draftitemid;
        parent::set_data($defaults);
        if ($data = parent::get_data()) {
            file_save_draft_area_files(
                $data->config_image,
                $this->block->context->id,
                'block_eva_gallery',
                'content',
                0,
                array('subdirs' => true)
            );
        }
        // END CCN Image Processing



        if (!empty($this->block->config) && is_object($this->block->config)) {
            $text = $this->block->config->bio;
            $draftid_editor = file_get_submitted_draft_itemid('config_bio');
            if (empty($text)) {
                $currenttext = '';
            } else {
                $currenttext = $text;
            }
            $defaults->config_bio['text'] = file_prepare_draft_area($draftid_editor, $this->block->context->id, 'block_eva_gallery', 'content', 0, array('subdirs' => true), $currenttext);
            $defaults->config_bio['itemid'] = $draftid_editor;
            $defaults->config_bio['format'] = $this->block->config->format;
        } else {
            $text = '';
        }
    }
}