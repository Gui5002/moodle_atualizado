<?php

class block_eva_abouteva_edit_form extends block_edit_form {
    protected function specific_definition($mform) {
        global $CFG;

        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));

        // Title
        $mform->addElement('text', 'config_title', get_string('config_title', 'block_eva_abouteva'));
        $mform->setDefault('config_title', 'SOBRE A EVA');
        $mform->setType('config_title', PARAM_RAW);

        // Body
        // $text = "A Escola Virtual da AGU -  é um ambiente virtual desenvolvido pela Escola da Advocacia Geral da União para disponibilizar conteúdos educacionais diversos nas matérias de interesse da instituição. de maneira flexível e permanentemente acessível com vistas a propiciar aprendizagem organizacional constante e dinâmica.
        // Busca-se ampliar o alcance das capacitações para o âmbito nacional, democratizando dessa forma o acesso às oportunidades de capacitação na Advocacia Geral da União.
        // com economicidade e eficiência";
        // $mform->addElement('text', 'config_body', get_string('config_body', 'block_eva_abouteva'));
        // $mform->setDefault('config_body', $text);
        // $mform->setType('config_body', PARAM_RAW);

        $mform->addElement('editor', 'config_about_html', get_string('config_about_html', 'block_eva_abouteva'));
        $mform->setDefault('config_about_html', $text);
        $mform->setType('config_about_html', PARAM_RAW);

        // Image
        $mform->addElement('filemanager', 'config_image', get_string('config_image', 'block_eva_abouteva'), null,
                array('subdirs' => 0, 'maxbytes' => $maxbytes, 'areamaxbytes' => 10485760, 'maxfiles' => 1,
                'accepted_types' => array('.png', '.jpg', '.gif', '.jpeg') ));

        //Section color
        $mform->addElement('text', 'config_color_background', get_string('config_color_background', 'block_eva_abouteva'), array('class' => 'ccn_spectrum_class'));
        $mform->setDefault('config_color_background' . $i, 'rgb(229, 229, 229)');
        $mform->setType('config_color_background' . $i, PARAM_TEXT);

        //Textarea background color
        $mform->addElement('text', 'config_color_text_background', get_string('config_color_text_background', 'block_eva_abouteva'), array('class' => 'ccn_spectrum_class'));
        $mform->setDefault('config_color_text_background' . $i, 'rgb(89, 97, 125)');
        $mform->setType('config_color_text_background' . $i, PARAM_TEXT);

        //Title color
        $mform->addElement('text', 'config_title_color', get_string('config_title_color', 'block_eva_abouteva'), array('class' => 'ccn_spectrum_class'));
        $mform->setDefault('config_title_color', 'rgb(255, 255, 255)');
        $mform->setType('config_title_color', PARAM_TEXT);

        //Text color
        $mform->addElement('text', 'config_text_color', get_string('config_text_color', 'block_eva_abouteva'), array('class' => 'ccn_spectrum_class'));
        $mform->setDefault('config_text_color', 'rgb(255, 255, 255)');
        $mform->setType('config_text_color', PARAM_TEXT);

        //Back retangle color
        $mform->addElement('text', 'config_color_retangle', get_string('config_color_retangle', 'block_eva_abouteva'), array('class' => 'ccn_spectrum_class'));
        $mform->setDefault('config_color_retangle' . $i, 'rgb(185, 193, 204)');
        $mform->setType('config_color_retangle' . $i, PARAM_TEXT);

        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/edit.php');
    }

    function set_data($defaults) {
        $field = 'image';
        $conffield = 'config_image';
        $draftitemid = file_get_submitted_draft_itemid($conffield);
        file_prepare_draft_area($draftitemid, $this->block->context->id, 'block_eva_abouteva', 'content', 0, array('subdirs'=>false));
        $defaults->$conffield['itemid'] = $draftitemid;
        $this->block->config->$field = $draftitemid;
        parent::set_data($defaults);
        
        if ($data = parent::get_data()) {
            file_save_draft_area_files($data->config_image, $this->block->context->id, 'block_eva_abouteva', 'content', 0,
                array('subdirs' => true));
        }

        $text = $this->block->config->about_html;
        $conffield = 'config_about_html';
        $draftid_editor = file_get_submitted_draft_itemid($conffield);
        
        if (empty($text)) {
            $currenttext = '';
        } else {
            $currenttext = $text;
        }
        
        $defaults->$conffield['text'] = file_prepare_draft_area($draftid_editor, $this->block->context->id, 'block_eva_abouteva', 'content', 0, array('subdirs'=>false), $currenttext);
        $defaults->$conffield['itemid'] = $draftid_editor;
        $defaults->$conffield['format'] = $this->block->config->format;
    }
}
