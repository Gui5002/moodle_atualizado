<?php

defined('MOODLE_INTERNAL') || die();

class block_eva_postgraduate_about_edit_form extends block_edit_form {

    public function specific_definition($mform) {
        global $CFG, $PAGE;
        $maxbytes = $CFG->maxbytes ?? 10485760; // Use o valor padrão do Moodle ou defina 10MB.
        $attributes = array();
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));
        // Title
        $mform->addElement('text', 'config_title', get_string('config_title', 'block_eva_postgraduate_about'));
        $mform->setDefault('config_title', 'PÓS-GRADUAÇÃO');
        $mform->setType('config_title', PARAM_RAW);
        // Subtitle
        $mform->addElement('text', 'config_subtitle', get_string('config_subtitle', 'block_eva_postgraduate_about'));
        $mform->setDefault('config_subtitle', 'Texto modelo por padrão inserir resumo Acesso rapido as ações, eventos e cursos AGU.');
        $mform->setType('config_subtitle', PARAM_RAW);
        $mform->addElement('filemanager', 'config_image', get_string('config_image', 'theme_evagu'), null,
            array(
                'subdirs' => 0,
                'maxbytes' => $maxbytes,
                'areamaxbytes' => 10485760,
                'maxfiles' => 1,
                'accepted_types' => array('.png', '.jpg', '.gif'),
            )
        );

        // Style selection
        $radioarray = array();
        $radioarray[] = $mform->createElement('radio', 'config_style', '', 'Image Left', 0, $attributes);
        $radioarray[] = $mform->createElement('radio', 'config_style', '', 'Image Right', 1, $attributes);
        $mform->addGroup($radioarray, 'config_style', 'Style', array(' '), false);

        // Slides range
        $slidesrange = array_combine(range(1, 10), range(1, 10));
        $mform->addElement('select', 'config_slidesnumber', get_string('config_items', 'theme_evagu'), $slidesrange);
        $mform->setDefault('config_slidesnumber', 10);

        // Configuração dos itens
        for ($i = 1; $i <= count($slidesrange); $i++) {
            $mform->addElement('header', 'config_ccn_item' . $i, get_string('config_item', 'theme_evagu') . $i);

            $mform->addElement('text', 'config_title' . $i, get_string('config_title', 'theme_evagu'));
            $mform->setType('config_title' . $i, PARAM_TEXT);

            $mform->addElement('editor', 'config_text' . $i, get_string('config_body', 'theme_evagu'));
            $mform->setType('config_text' . $i, PARAM_RAW);
        }

        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/edit.php');
    }

    public function set_data($defaults) {
        $field = 'image';
        $conffield = 'config_image';
        $draftitemid = file_get_submitted_draft_itemid($conffield);
        file_prepare_draft_area($draftitemid, $this->block->context->id, 'block_eva_postgraduate_about', 'content', 0, array('subdirs' => false));
        $defaults->$conffield['itemid'] = $draftitemid;
        $this->block->config->$field = $draftitemid;

        parent::set_data($defaults);

        // Processamento de texto e formato.
        if (!empty($this->block->config) && is_object($this->block->config)) {
            $text = $this->block->config->body ?? '';
            $draftid_editor = file_get_submitted_draft_itemid('config_body');
            $defaults->config_body['text'] = file_prepare_draft_area(
                $draftid_editor,
                $this->block->context->id,
                'block_eva_postgraduate_about',
                'content',
                0,
                array('subdirs' => true),
                $text
            );
            $defaults->config_body['itemid'] = $draftid_editor;
            $defaults->config_body['format'] = $this->block->config->format ?? FORMAT_HTML;
        }
    }
}
