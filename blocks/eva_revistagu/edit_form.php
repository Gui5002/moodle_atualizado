<?php

defined('MOODLE_INTERNAL') || die();

class block_eva_revistagu_edit_form extends block_edit_form {

    public function specific_definition($mform) {
        global $CFG, $PAGE;

        $ccnFontList = include($CFG->dirroot . '/theme/evagu/ccn/font_handler/ccn_font_select.php');

        if (!empty($this->block->config) && is_object($this->block->config)) {
            $data = $this->block->config;
        } else {
            $data = new stdClass();
        }

        // language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));

        // Title
        $mform->addElement('text', 'config_title', get_string('config_title', 'block_eva_revistagu'));
        $mform->setDefault('config_title', 'Revista da AGU');
        $mform->setType('config_title', PARAM_RAW);

        //Editor
        $mform->addElement('editor', 'config_editor', get_string('config_editor', 'block_eva_revistagu'));
        $mform->setType('config_editor', PARAM_RAW);

        //icon 1
        $select = $mform->addElement('select', 'config_icon1', get_string('config_icon1', 'block_eva_revistagu'), $ccnFontList, array('class' => 'ccn_icon_class'));
        $select->setSelected('flaticon-student-3');

        // Icon title 1
        $mform->addElement('text', 'config_icon_title1', get_string('config_icon_title1', 'block_eva_revistagu'));
        $mform->setDefault('config_icon_title1', 'REVISTA DA AGU');
        $mform->setType('config_icon_title1', PARAM_RAW);

        //Icon 1 URL
        $mform->addElement('url', 'config_slide_url1', get_string('config_slide_url1', 'block_eva_revistagu'), array('size' => '60'), array('usefilepicker' => true));
        $mform->setDefault('config_slide_url1', 'https://seer.agu.gov.br/index.php/AGU');
        $mform->setType('config_slide_url1', PARAM_URL);

        //icon 2
        $select = $mform->addElement('select', 'config_icon2', get_string('config_icon2', 'block_eva_revistagu'), $ccnFontList, array('class' => 'ccn_icon_class'));
        $select->setSelected('flaticon-student-2');

        // Icon title 2
        $mform->addElement('text', 'config_icon_title2', get_string('config_icon_title2', 'block_eva_revistagu'));
        $mform->setDefault('config_icon_title2', 'PUBLICAÇÕES TEMÁTICAS');
        $mform->setType('config_icon_title2', PARAM_RAW);

        //Icon 2 URL
        $mform->addElement('url', 'config_slide_url2', get_string('config_slide_url2', 'block_eva_revistagu'), array('size' => '60'), array('usefilepicker' => true));
        $mform->setDefault('config_slide_url2', 'https://www.gov.br/agu/pt-br/composicao/escola-da-agu-1/avaliacao-editorial/publicacoes-tematicas');
        $mform->setType('config_slide_url2', PARAM_URL);

        //icon 3
        $select = $mform->addElement('select', 'config_icon3', get_string('config_icon3', 'block_eva_revistagu'), $ccnFontList, array('class' => 'ccn_icon_class'));
        $select->setSelected('flaticon-student-1');

        // Icon title 3
        $mform->addElement('text', 'config_icon_title3', get_string('config_icon_title3', 'block_eva_revistagu'));
        $mform->setDefault('config_icon_title3', 'LIVROS ELETRÔNICOS');
        $mform->setType('config_icon_title3', PARAM_RAW);

        //Icon 3 URL
        $mform->addElement('url', 'config_slide_url3', get_string('config_slide_url3', 'block_eva_revistagu'), array('size' => '60'), array('usefilepicker' => true));
        $mform->setDefault('config_slide_url3', 'https://www.gov.br/agu/pt-br/composicao/escola-da-agu-1/avaliacao-editorial/livros-eletronicos');
        $mform->setType('config_slide_url3', PARAM_URL);

        $mform->addElement('filemanager', 'config_image', get_string('config_image', 'theme_evagu'), null,
            array('subdirs' => 0, 'maxbytes' => $maxbytes, 'areamaxbytes' => 10485760, 'maxfiles' => 1,
                'accepted_types' => array('.png', '.jpg', '.gif') ));

        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/edit.php');
    }

    function set_data($defaults)
    {

        //var_dump($defaults);die();
        // Begin CCN Image Processing
        if (empty($entry->id)) {
            $entry = new stdClass;
            $entry->id = null;
            $entry->definition = '';
            $entry->format = FORMAT_HTML;
        }

        $draftid_editor = file_get_submitted_draft_itemid('config_editor');
        $currenttext = file_prepare_draft_area($draftid_editor, $this->block->context->id, 'block_eva_revistagu', 'entry',
                                            $entry->id, array('subdirs'=>true), $entry->definition);
        $entry->entry = array('text'=>$currenttext, 'format'=>$entry->format, 'itemid'=>$draftid_editor);

        // $draftitemid = file_get_submitted_draft_itemid('config_image');
        // file_prepare_draft_area($draftitemid, $this->block->context->id, 'block_eva_revistagu', 'content', 0, array('subdirs' => true));
        // $entry->attachments = $draftitemid;
        $field = 'image';
        $conffield = 'config_image';
        $draftitemid = file_get_submitted_draft_itemid($conffield);
        file_prepare_draft_area($draftitemid, $this->block->context->id, 'block_eva_revistagu', 'content', 0, array('subdirs'=>false));
        $defaults->$conffield['itemid'] = $draftitemid;
        $this->block->config->$field = $draftitemid;

        parent::set_data($defaults);
        if ($data = parent::get_data()) {
            file_save_draft_area_files($data->config_image, $this->block->context->id, 'block_eva_revistagu', 'content', 0,
                array('subdirs' => true));

            $messagetext = $data->entry['text'];
            $messageformat  = $data->entry['format'];
            $messagetext = file_save_draft_area_files($draftid_editor, $context->id, 'block_eva_revistagu', 'entry',
                                          $entry->id, array('subdirs'=>true), $messagetext);
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
            $defaults->config_body['text'] = file_prepare_draft_area($draftid_editor, $this->block->context->id, 'block_eva_revistagu', 'content', 0, array('subdirs'=>true), $currenttext);
            $defaults->config_body['itemid'] = $draftid_editor;
            $defaults->config_body['format'] = $this->block->config->format;
        } else {
            $text = '';
        }


    }
}