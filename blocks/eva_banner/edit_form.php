<?php

class block_eva_banner_edit_form extends block_edit_form
{
    protected function specific_definition($mform)
    {
      global $CFG;
        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));
        // Title
        $mform->addElement('text', 'config_title', get_string('config_title', 'block_eva_banner'));
        $mform->setDefault('config_title', 'Aprimore suas habilidades com os melhores cursos online');
        $mform->setType('config_title', PARAM_RAW);
        // Subtitle
        $mform->addElement('text', 'config_subtitle', get_string('config_subtitle', 'block_eva_banner'));
        $mform->setDefault('config_subtitle', 'Comece agora!');
        $mform->setType('config_subtitle', PARAM_RAW);
        // Button Text
        $mform->addElement('text', 'config_button_text', get_string('config_button_text', 'block_eva_banner'));
        $mform->setDefault('config_button_text', 'Comece agora!');
        $mform->setType('config_button_text', PARAM_RAW);
        // Button Link
        $mform->addElement('text', 'config_button_link', get_string('config_button_link', 'block_eva_banner'));
        $mform->setDefault('config_button_link', '#');
        $mform->setType('config_button_link', PARAM_RAW);
        // Image
        $mform->addElement('filemanager', 'config_image', get_string('config_image', 'block_eva_banner'), null,
                array('subdirs' => 0, 'maxbytes' => $maxbytes, 'areamaxbytes' => 10485760, 'maxfiles' => 1,
                'accepted_types' => array('.png', '.jpg', '.gif') ));
        // Style
        $radioarray=array();
        $radioarray[] = $mform->createElement('radio', 'config_style', '', 'Light', 0, $attributes);
        $radioarray[] = $mform->createElement('radio', 'config_style', '', 'Dark', 1, $attributes);
        $mform->addGroup($radioarray, 'config_style', 'Style', array(' '), false);
        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/edit.php');
    }
    function set_data($defaults)
    {
        $field = 'image';
        $conffield = 'config_image';
        $draftitemid = file_get_submitted_draft_itemid($conffield);
        file_prepare_draft_area($draftitemid, $this->block->context->id, 'block_eva_banner', 'content', 0, array('subdirs'=>false));
        $defaults->$conffield['itemid'] = $draftitemid;
        $this->block->config->$field = $draftitemid;  
        parent::set_data($defaults);
        if ($data = parent::get_data()) {
            file_save_draft_area_files($data->config_image, $this->block->context->id, 'block_eva_banner', 'content', 0,
                array('subdirs' => true));
        }
        
    }
}
