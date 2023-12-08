<?php

defined('MOODLE_INTERNAL') || die();

class block_eva_about_databases_edit_form extends block_edit_form {

    public function specific_definition($mform) {
        global $CFG, $PAGE;

        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        // Title
        $mform->addElement('text', 'config_main_title', get_string('config_title', 'block_eva_about_databases'));
        $mform->setDefault('config_main_title', 'PÓS-GRADUAÇÃO');
        $mform->setType('config_main_title', PARAM_RAW);

        // Subtitle
//        $mform->addElement('text', 'config_subtitle', get_string('config_subtitle', 'block_eva_about_databases'));
//        $mform->setDefault('config_subtitle', 'Cum doctus civibus efficiantur in imperdiet deterruisCum doctus civibus efficiantur in imperdiet deterruisset.');
//        $mform->setType('config_subtitle', PARAM_RAW);

        $mform->addElement('filemanager', 'config_image', get_string('config_image', 'theme_evagu'), null,
            array('subdirs' => 0, 'maxbytes' => $maxbytes, 'areamaxbytes' => 10485760, 'maxfiles' => 1,
                'accepted_types' => array('.png', '.jpg', '.gif') ));

//        $radioarray=array();
//        $radioarray[] = $mform->createElement('radio', 'config_style', '', 'Image Left', 0, $attributes);
//        $radioarray[] = $mform->createElement('radio', 'config_style', '', 'Image Right', 1, $attributes);
//        $mform->addGroup($radioarray, 'config_style', 'Style', array(' '), false);


        $mform->addElement('header', 'config_ccn_item' . $i , get_string('config_item', 'theme_evagu') . $i);

        $mform->addElement('text', 'config_title' . $i, get_string('config_title', 'theme_evagu'));
        //$mform->setDefault('config_title' .$i , 'Accordion' . $i);
        $mform->setType('config_title' . $i, PARAM_TEXT);

        $mform->addElement('editor', 'config_text'.$i, get_string('config_body', 'theme_evagu'));
        $mform->setType('config_text'.$i, PARAM_RAW);





        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/edit.php');

    }

    function set_data($defaults)
    {
        $field = 'image';
        $conffield = 'config_image';
        $draftitemid = file_get_submitted_draft_itemid($conffield);
        file_prepare_draft_area($draftitemid, $this->block->context->id, 'block_eva_about_databases', 'content', 0, array('subdirs'=>false));
        $defaults->$conffield['itemid'] = $draftitemid;
        $this->block->config->$field = $draftitemid;
        //var_dump($defaults);die();
        // Begin CCN Image Processing
        // if (empty($entry->id)) {
        //     $entry = new stdClass;
        //     $entry->id = null;
        // }
        // $draftitemid = file_get_submitted_draft_itemid('config_image');
        // file_prepare_draft_area($draftitemid, $this->block->context->id, 'block_eva_about_databases', 'content', 0, array('subdirs' => true));
        // $entry->attachments = $draftitemid;
        parent::set_data($defaults);
        if ($data = parent::get_data()) {
            file_save_draft_area_files($data->config_image, $this->block->context->id, 'block_eva_about_databases', 'content', 0,
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
            $defaults->config_body['text'] = file_prepare_draft_area($draftid_editor, $this->block->context->id, 'block_eva_about_databases', 'content', 0, array('subdirs'=>true), $currenttext);
            $defaults->config_body['itemid'] = $draftid_editor;
            $defaults->config_body['format'] = $this->block->config->format;
        } else {
            $text = '';
        }


    }



}
