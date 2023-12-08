<?php

class block_eva_abouteva_info_edit_form extends block_edit_form {
    protected function specific_definition($mform) {
        global $CFG;

        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));

        // Texto 1
        $mform->addElement('text', 'config_text1', get_string('config_text1', 'block_eva_abouteva_info'));
        $mform->setDefault('config_text1', 'CAPACITAÇÃO E EVENTOS ONLINE');
        $mform->setType('config_text1', PARAM_RAW);

        // Texto 2
        $mform->addElement('text', 'config_text2', get_string('config_text2', 'block_eva_abouteva_info'));
        $mform->setDefault('config_text2', 'Tudo no conforto de sua residência!');
        $mform->setType('config_text2', PARAM_RAW);

        // Texto 3
        $text = "Amplo material de capacitação para membros da Advocacia-Geral da União, Procuradores dos Estados e Procuradores dos Municípios";
        $mform->addElement('text', 'config_text3', get_string('config_text3', 'block_eva_abouteva_info'));
        $mform->setDefault('config_text3', $text);
        $mform->setType('config_text3', PARAM_RAW);

        // Btn
        $mform->addElement('text', 'config_btn_text', get_string('config_btn_text', 'block_eva_abouteva_info'));
        $mform->setDefault('config_btn_text', 'VEJA MAIS');
        $mform->setType('config_btn_text', PARAM_RAW);

        // Btn URL
        $mform->addElement('text', 'config_btn_url', get_string('config_btn_url', 'block_eva_abouteva_info'));
        $mform->setDefault('config_btn_url', '#');
        $mform->setType('config_btn_url', PARAM_RAW);

        // Image
        $mform->addElement('filemanager', 'config_image', get_string('config_image', 'block_eva_abouteva_info'), null,
                array('subdirs' => 0, 'maxbytes' => $maxbytes, 'areamaxbytes' => 10485760, 'maxfiles' => 1,
                'accepted_types' => array('.png', '.jpg', '.gif', '.jpeg') ));

        //Textarea background color
        $mform->addElement('text', 'config_color_text_background', get_string('config_color_text_background', 'block_eva_abouteva_info'), array('class' => 'ccn_spectrum_class'));
        $mform->setDefault('config_color_text_background' . $i, 'rgb(203, 223, 230)');
        $mform->setType('config_color_text_background' . $i, PARAM_TEXT);

        //Back retangle color
        $mform->addElement('text', 'config_color_retangle', get_string('config_color_retangle', 'block_eva_abouteva_info'), array('class' => 'ccn_spectrum_class'));
        $mform->setDefault('config_color_retangle' . $i, 'rgb(68, 80, 99)');
        $mform->setType('config_color_retangle' . $i, PARAM_TEXT);

        //Title and text color
        $mform->addElement('text', 'config_all_text_color', get_string('config_all_text_color', 'block_eva_abouteva_info'), array('class' => 'ccn_spectrum_class'));
        $mform->setDefault('config_all_text_color', 'rgb(0, 0, 0)');
        $mform->setType('config_all_text_color', PARAM_TEXT);

        //Button color
        $mform->addElement('text', 'config_button_color', get_string('config_button_color', 'block_eva_abouteva_info'), array('class' => 'ccn_spectrum_class'));
        $mform->setDefault('config_button_color', 'rgb(89, 127, 140)');
        $mform->setType('config_button_color', PARAM_TEXT);

        //Button color hover
        $mform->addElement('text', 'config_button_color_hover', get_string('config_button_color_hover', 'block_eva_abouteva_info'), array('class' => 'ccn_spectrum_class'));
        $mform->setDefault('config_button_color_hover', 'rgb(49, 118, 141)');
        $mform->setType('config_button_color_hover', PARAM_TEXT);

        //Button text color
        $mform->addElement('text', 'config_button_text_color', get_string('config_button_text_color', 'block_eva_abouteva_info'), array('class' => 'ccn_spectrum_class'));
        $mform->setDefault('config_button_text_color', 'rgb(255, 255, 255)');
        $mform->setType('config_button_text_color', PARAM_TEXT);

        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/edit.php');
    }

    function set_data($defaults) {
        $field = 'image';
        $conffield = 'config_image';
        $draftitemid = file_get_submitted_draft_itemid($conffield);
        file_prepare_draft_area($draftitemid, $this->block->context->id, 'block_eva_abouteva_info', 'content', 0, array('subdirs'=>false));
        $defaults->$conffield['itemid'] = $draftitemid;
        $this->block->config->$field = $draftitemid;
        parent::set_data($defaults);
        
        if ($data = parent::get_data()) {
            file_save_draft_area_files($data->config_image, $this->block->context->id, 'block_eva_abouteva_info', 'content', 0,
                array('subdirs' => true));
        }
    }
}
