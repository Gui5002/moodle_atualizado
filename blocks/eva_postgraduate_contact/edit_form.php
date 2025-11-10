<?php
defined('MOODLE_INTERNAL') || die();

class block_eva_postgraduate_contact_edit_form extends block_edit_form {

    public function specific_definition($mform) {
        global $CFG, $PAGE;
        // Inicialização de variáveis necessárias.
        $maxbytes = $CFG->maxbytes ?? 10485760; // Define o tamanho máximo de arquivos como 10 MB ou o padrão do Moodle.
        $attributes = []; // Inicializa o array de atributos como vazio.
        $ccnFontList = include($CFG->dirroot . '/theme/evagu/ccn/font_handler/ccn_font_select.php');
        if (!empty($this->block->config) && is_object($this->block->config)) {
            $ccnStorage = $this->block->config;
        } else {
            $ccnStorage = new stdClass();
            $ccnStorage->items = 3;
        }
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));
        // File manager
        $mform->addElement('filemanager', 'config_image', get_string('config_image', 'theme_evagu'), null, [
            'subdirs' => 0,
            'maxbytes' => $maxbytes,
            'areamaxbytes' => 10485760,
            'maxfiles' => 1,
            'accepted_types' => ['.png', '.jpg', '.gif'],
        ]);
        // Style selection
        $radioarray = [];
        $radioarray[] = $mform->createElement('radio', 'config_style', '', 'Image Left', 0, $attributes);
        $radioarray[] = $mform->createElement('radio', 'config_style', '', 'Image Right', 1, $attributes);
        $mform->addGroup($radioarray, 'config_style', 'Style', [' '], false);
        // Items configuration
        $ccnItemsRange = array_combine(range(0, 12), range(0, 12)); // Gera array de 0 a 12.
        $ccnItemsMax = 12;
        $mform->addElement('select', 'config_items', get_string('config_items', 'theme_evagu'), $ccnItemsRange, ['class' => 'ccnCommLcRef_change']);
        $mform->setDefault('config_items', 3);
        for ($i = 1; $i <= $ccnItemsMax; $i++) {
            $mform->addElement('header', 'config_ccn_item' . $i, get_string('config_item', 'theme_evagu') . $i);
            $mform->addElement('text', 'config_title_' . $i, get_string('config_title', 'theme_evagu', $i));
            $mform->setDefault('config_title_' . $i, 'Our Email');
            $mform->setType('config_title_' . $i, PARAM_TEXT);
            $mform->addElement('textarea', 'config_subtitle_' . $i, get_string('config_body', 'theme_evagu', $i));
            $mform->setDefault('config_subtitle_' . $i, 'info@evagu.com');
            $mform->setType('config_subtitle_' . $i, PARAM_RAW);
            $select = $mform->addElement('select', 'config_icon_' . $i, get_string('config_icon_class', 'theme_evagu'), $ccnFontList, ['class' => 'ccn_icon_class']);
            $select->setSelected('flaticon-email');
        }
        // Recaptcha options
        $options = [
            '0' => 'Display reCAPTCHA to guests',
            '1' => 'Display reCAPTCHA to all users',
            '2' => 'Do not display reCAPTCHA',
        ];
        $select = $mform->addElement('select', 'config_recaptcha', get_string('config_recaptcha', 'theme_evagu'), $options);
        $select->setSelected('0');

        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/edit.php');
    }
    public function set_data($defaults) {
        $field = 'image';
        $conffield = 'config_image';
        $draftitemid = file_get_submitted_draft_itemid($conffield);
        file_prepare_draft_area($draftitemid, $this->block->context->id, 'block_eva_postgraduate_contact', 'content', 0, ['subdirs' => false]);
        $defaults->$conffield['itemid'] = $draftitemid;
        $this->block->config->$field = $draftitemid;

        parent::set_data($defaults);

        if ($data = parent::get_data()) {
            file_save_draft_area_files($data->config_image, $this->block->context->id, 'block_eva_postgraduate_contact', 'content', 0, ['subdirs' => true]);
        }
        if (!empty($this->block->config) && is_object($this->block->config)) {
            $text = $this->block->config->body ?? '';
            $draftid_editor = file_get_submitted_draft_itemid('config_body');

            $defaults->config_body['text'] = file_prepare_draft_area(
                $draftid_editor,
                $this->block->context->id,
                'block_eva_postgraduate_contact',
                'content',
                0,
                ['subdirs' => true],
                $text
            );
            $defaults->config_body['itemid'] = $draftid_editor;
            $defaults->config_body['format'] = $this->block->config->format ?? FORMAT_HTML;
        }
    }
}
