<?php

class block_eva_continuing_education_cicles_edit_form extends block_edit_form {
    protected function specific_definition($mform) {
        global $CFG;
        $ccnFontList = include($CFG->dirroot . '/theme/evagu/ccn/font_handler/ccn_font_select.php');

        if (!empty($this->block->config) && is_object($this->block->config)) {
            $data = $this->block->config;
        } else {
            $data = new stdClass();
            $data->slidesnumber = 4;
        }


        // Fields for editing HTML block title and contents.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        //Title
        $mform->addElement('text', 'config_title', get_string('config_title', 'block_eva_continuing_education_cicles'));
        $mform->setDefault('config_title', 'Ciclos de Formação Continuada');
        $mform->setType('config_title', PARAM_TEXT);

        //Editor
        $mform->addElement('text', 'config_editor', get_string('config_editor', 'block_eva_continuing_education_cicles'));
        $mform->setDefault('config_editor', 'Amostra de Título Amostra de Título Amostra de Título Amostra de Título Amostra de Título <br> Amostra de Título Amostra de Título Amostra de Título Amostra de Título Amostra de Título');
        $mform->setType('config_editor', PARAM_TEXT);

        // Image
        $mform->addElement('filemanager', 'config_image', get_string('config_image', 'block_eva_continuing_education_cicles'), null,
                array('subdirs' => 0, 'maxbytes' => $CFG->maxbytes, 'maxfiles' => 1,
                'accepted_types' => array('.png', '.jpg', '.gif', '.jpeg') ));

        //Card 1-------------
            //Header
            $mform->addElement('header', 'config_ccn_item1', get_string('config_item', 'theme_evagu') . 1);
            
            //Title
            $mform->addElement('text', 'config_card_title1', get_string('config_card_title1', 'block_eva_continuing_education_cicles'));
            $mform->setDefault('config_card_title1', 'PGU');
            $mform->setType('config_card_title1', PARAM_TEXT);

            //Subtitle
            $mform->addElement('text', 'config_card_subtitle1', get_string('config_card_subtitle1', 'block_eva_continuing_education_cicles'));
            $mform->setDefault('config_card_subtitle1', 'Sample text. Lorem ipsum dolor sit amet, consectetur adipiscing elit nullam nunc');
            $mform->setType('config_card_subtitle1', PARAM_TEXT);

            //URL
            $mform->addElement('text', 'config_slide_url1', get_string('config_slide_url1', 'block_eva_continuing_education_cicles'), array('size' => '60'), array('usefilepicker' => true));
            $mform->setDefault('config_slide_url1', 'https://www.gov.br/cgu/pt-br');
            $mform->setType('config_slide_url1', PARAM_URL);

            //Text color
            $mform->addElement('text', 'config_text_color1', get_string('config_text_color1', 'block_eva_continuing_education_cicles'), array('class' => 'ccn_spectrum_class'));
            $mform->setDefault('config_text_color1', 'rgb(0, 0, 0)');
            $mform->setType('config_text_color1', PARAM_TEXT);

            //Text color hover
            $mform->addElement('text', 'config_text_color_hover1', get_string('config_text_color_hover1', 'block_eva_continuing_education_cicles') . get_string('on_hover', 'theme_evagu'), array('class' => 'ccn_spectrum_class'));
            $mform->setDefault('config_text_color_hover1', 'rgb(255, 255, 255)');
            $mform->setType('config_text_color_hover1', PARAM_TEXT);

            //Card color
            $mform->addElement('text', 'config_body_color1', get_string('config_body_color1', 'block_eva_continuing_education_cicles'), array('class' => 'ccn_spectrum_class'));
            $mform->setDefault('config_body_color1', 'rgb(255, 255, 255)');
            $mform->setType('config_body_color1', PARAM_TEXT);

            //Card color hover
            $mform->addElement('text', 'config_body_color_hover1', get_string('config_body_color_hover1', 'block_eva_continuing_education_cicles') . get_string('on_hover', 'theme_evagu'), array('class' => 'ccn_spectrum_class'));
            $mform->setDefault('config_body_color_hover1', 'rgb(142, 157, 167)');
            $mform->setType('config_body_color_hover1', PARAM_TEXT);
        //-------------------

        //Card 2-------------
            //Header
            $mform->addElement('header', 'config_ccn_item2', get_string('config_item', 'theme_evagu') . 2);
            
            //Title
            $mform->addElement('text', 'config_card_title2', get_string('config_card_title2', 'block_eva_continuing_education_cicles'));
            $mform->setDefault('config_card_title2', 'CGU');
            $mform->setType('config_card_title2', PARAM_TEXT);

            //Subtitle
            $mform->addElement('text', 'config_card_subtitle2', get_string('config_card_subtitle2', 'block_eva_continuing_education_cicles'));
            $mform->setDefault('config_card_subtitle2', 'Sample text. Lorem ipsum dolor sit amet, consectetur adipiscing elit nullam nunc');
            $mform->setType('config_card_subtitle2', PARAM_TEXT);

            //URL
            $mform->addElement('text', 'config_slide_url2', get_string('config_slide_url2', 'block_eva_continuing_education_cicles'), array('size' => '60'), array('usefilepicker' => true));
            $mform->setDefault('config_slide_url2', 'https://www.gov.br/cgu/pt-br');
            $mform->setType('config_slide_url2', PARAM_URL);

            //Text color
            $mform->addElement('text', 'config_text_color2', get_string('config_text_color2', 'block_eva_continuing_education_cicles'), array('class' => 'ccn_spectrum_class'));
            $mform->setDefault('config_text_color2', 'rgb(0, 0, 0)');
            $mform->setType('config_text_color2', PARAM_TEXT);

            //Text color hover
            $mform->addElement('text', 'config_text_color_hover2', get_string('config_text_color_hover2', 'block_eva_continuing_education_cicles') . get_string('on_hover', 'theme_evagu'), array('class' => 'ccn_spectrum_class'));
            $mform->setDefault('config_text_color_hover2', 'rgb(255, 255, 255)');
            $mform->setType('config_text_color_hover2', PARAM_TEXT);

            //Card color
            $mform->addElement('text', 'config_body_color2', get_string('config_body_color2', 'block_eva_continuing_education_cicles'), array('class' => 'ccn_spectrum_class'));
            $mform->setDefault('config_body_color2', 'rgb(255, 255, 255)');
            $mform->setType('config_body_color2', PARAM_TEXT);

            //Card color hover
            $mform->addElement('text', 'config_body_color_hover2', get_string('config_body_color_hover2', 'block_eva_continuing_education_cicles') . get_string('on_hover', 'theme_evagu'), array('class' => 'ccn_spectrum_class'));
            $mform->setDefault('config_body_color_hover2', 'rgb(142, 157, 167)');
            $mform->setType('config_body_color_hover2', PARAM_TEXT);
        //-------------------

        //Card 3-------------
            //Header
            $mform->addElement('header', 'config_ccn_item3', get_string('config_item', 'theme_evagu') . 3);
            
            //Title
            $mform->addElement('text', 'config_card_title3', get_string('config_card_title3', 'block_eva_continuing_education_cicles'));
            $mform->setDefault('config_card_title3', 'CGAU');
            $mform->setType('config_card_title3', PARAM_TEXT);

            //Subtitle
            $mform->addElement('text', 'config_card_subtitle3', get_string('config_card_subtitle3', 'block_eva_continuing_education_cicles'));
            $mform->setDefault('config_card_subtitle3', 'Sample text. Lorem ipsum dolor sit amet, consectetur adipiscing elit nullam nunc');
            $mform->setType('config_card_subtitle3', PARAM_TEXT);

            //URL
            $mform->addElement('text', 'config_slide_url3', get_string('config_slide_url3', 'block_eva_continuing_education_cicles'), array('size' => '60'), array('usefilepicker' => true));
            $mform->setDefault('config_slide_url3', 'https://www.gov.br/cgu/pt-br');
            $mform->setType('config_slide_url3', PARAM_URL);

            //Text color
            $mform->addElement('text', 'config_text_color3', get_string('config_text_color3', 'block_eva_continuing_education_cicles'), array('class' => 'ccn_spectrum_class'));
            $mform->setDefault('config_text_color3', 'rgb(0, 0, 0)');
            $mform->setType('config_text_color3', PARAM_TEXT);

            //Text color hover
            $mform->addElement('text', 'config_text_color_hover3', get_string('config_text_color_hover3', 'block_eva_continuing_education_cicles') . get_string('on_hover', 'theme_evagu'), array('class' => 'ccn_spectrum_class'));
            $mform->setDefault('config_text_color_hover3', 'rgb(255, 255, 255)');
            $mform->setType('config_text_color_hover3', PARAM_TEXT);

            //Card color
            $mform->addElement('text', 'config_body_color3', get_string('config_body_color3', 'block_eva_continuing_education_cicles'), array('class' => 'ccn_spectrum_class'));
            $mform->setDefault('config_body_color3', 'rgb(255, 255, 255)');
            $mform->setType('config_body_color3', PARAM_TEXT);

            //Card color hover
            $mform->addElement('text', 'config_body_color_hover3', get_string('config_body_color_hover3', 'block_eva_continuing_education_cicles') . get_string('on_hover', 'theme_evagu'), array('class' => 'ccn_spectrum_class'));
            $mform->setDefault('config_body_color_hover3', 'rgb(142, 157, 167)');
            $mform->setType('config_body_color_hover3', PARAM_TEXT);
        //-------------------

        //Card 4-------------
            //Header
            $mform->addElement('header', 'config_ccn_item4', get_string('config_item', 'theme_evagu') . 4);
            
            //Title
            $mform->addElement('text', 'config_card_title4', get_string('config_card_title3', 'block_eva_continuing_education_cicles'));
            $mform->setDefault('config_card_title4', 'PGF');
            $mform->setType('config_card_title4', PARAM_TEXT);

            //Subtitle
            $mform->addElement('text', 'config_card_subtitle4', get_string('config_card_subtitle4', 'block_eva_continuing_education_cicles'));
            $mform->setDefault('config_card_subtitle4', 'Sample text. Lorem ipsum dolor sit amet, consectetur adipiscing elit nullam nunc');
            $mform->setType('config_card_subtitle4', PARAM_TEXT);

            //URL
            $mform->addElement('text', 'config_slide_url4', get_string('config_slide_url4', 'block_eva_continuing_education_cicles'), array('size' => '60'), array('usefilepicker' => true));
            $mform->setDefault('config_slide_url4', 'https://www.gov.br/cgu/pt-br');
            $mform->setType('config_slide_url4', PARAM_URL);

            //Text color
            $mform->addElement('text', 'config_text_color4', get_string('config_text_color4', 'block_eva_continuing_education_cicles'), array('class' => 'ccn_spectrum_class'));
            $mform->setDefault('config_text_color4', 'rgb(0, 0, 0)');
            $mform->setType('config_text_color4', PARAM_TEXT);

            //Text color hover
            $mform->addElement('text', 'config_text_color_hover4', get_string('config_text_color_hover4', 'block_eva_continuing_education_cicles') . get_string('on_hover', 'theme_evagu'), array('class' => 'ccn_spectrum_class'));
            $mform->setDefault('config_text_color_hover4', 'rgb(255, 255, 255)');
            $mform->setType('config_text_color_hover4', PARAM_TEXT);

            //Card color
            $mform->addElement('text', 'config_body_color4', get_string('config_body_color4', 'block_eva_continuing_education_cicles'), array('class' => 'ccn_spectrum_class'));
            $mform->setDefault('config_body_color4', 'rgb(255, 255, 255)');
            $mform->setType('config_body_color4', PARAM_TEXT);

            //Card color hover
            $mform->addElement('text', 'config_body_color_hover4', get_string('config_body_color_hover4', 'block_eva_continuing_education_cicles') . get_string('on_hover', 'theme_evagu'), array('class' => 'ccn_spectrum_class'));
            $mform->setDefault('config_body_color_hover4', 'rgb(142, 157, 167)');
            $mform->setType('config_body_color_hover4', PARAM_TEXT);
        //-------------------

        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/edit.php');
    }

    function set_data($defaults) {
        //========== Begin CCN Image Processing ==========
        if (empty($entry->id)) {
            $entry = new stdClass;
            $entry->id = null;
            $entry->format = FORMAT_HTML;
        }

        $draftid_editor = file_get_submitted_draft_itemid('config_editor');
        $currenttext = file_prepare_draft_area($draftid_editor, $this->block->context->id, 'block_eva_continuing_education_cicles', 'entry', 0, array('subdirs'=>true), '');
        $defaults->config_editor = array('text'=>$currenttext, 'format'=>$entry->format, 'itemid'=>$draftid_editor);
        
        // $draftitemid = file_get_submitted_draft_itemid('config_image');
        // file_prepare_draft_area($draftitemid, $this->block->context->id, 'block_eva_continuing_education_cicles', 'content', 0, array('subdirs' => true));
        // $entry->attachments = $draftitemid;

        $field = 'image';
        $conffield = 'config_image';
        $draftitemid = file_get_submitted_draft_itemid($conffield);
        file_prepare_draft_area($draftitemid, $this->block->context->id, 'block_eva_continuing_education_cicles', 'content', 0, array('subdirs'=>false));
        $defaults->$conffield['itemid'] = $draftitemid;
        $this->block->config->$field = $draftitemid;
        
        parent::set_data($defaults);
        
        if ($data = parent::get_data()) {
            $messagetext = $data->config_editor['text'];
            $messageformat  = $data->config_editor['format'];
            
            file_save_draft_area_files($data->config_editor, $this->block->context->id, 'block_eva_continuing_education_cicles', 'entry', 0, array('subdirs'=>true), $messagetext);
            file_save_draft_area_files($data->config_image, $this->block->context->id, 'block_eva_continuing_education_cicles', 'content', 0, array('subdirs' => true));
        }
        //========== END CCN Image Processing ==========
    }
}
