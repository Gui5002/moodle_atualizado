<?php
class block_eva_about_3_edit_form extends block_edit_form
{
    protected function specific_definition($mform)
    {
        global $CFG;
        // Definir $maxbytes como um valor padrão (exemplo: 10485760 bytes = 10 MB).
        $maxbytes = 10485760;  // Valor padrão para tamanho máximo de arquivo.
        $attributes = [];  // Inicializado para evitar "undefined variable".
        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));
        // Title
        $mform->addElement('text', 'config_title', get_string('config_title', 'block_eva_about_3'));
        $mform->setDefault('config_title', 'Nossas Ações');
        $mform->setType('config_title', PARAM_RAW);
        // Subtitle
        $mform->addElement('text', 'config_subtitle', get_string('config_subtitle', 'block_eva_about_3'));
        $mform->setDefault('config_subtitle', 'Texto complementar do titulo de forma reduzida modelo.');
        $mform->setType('config_subtitle', PARAM_RAW);
        // Body
        $editoroptions = ['maxfiles' => EDITOR_UNLIMITED_FILES, 'noclean' => true, 'context' => $this->block->context];
        $mform->addElement('editor', 'config_body', get_string('config_body', 'theme_evagu'), null, $editoroptions);
        $mform->addRule('config_body', null, 'required', null, 'client');
        $mform->setType('config_body', PARAM_RAW);  // XSS prevenido ao imprimir o conteúdo do bloco e servir arquivos.
        // Video
        $mform->addElement('text', 'config_video_url', get_string('config_video', 'theme_evagu'));
        $mform->setDefault('config_video_url', 'https://www.youtube.com/watch?v=fBRee7p7S_A');
        $mform->setType('config_video_url', PARAM_RAW);
        // Image
        $mform->addElement('filemanager', 'config_image', get_string('config_image', 'theme_evagu'), null,
            array('subdirs' => 0, 'maxbytes' => $maxbytes, 'areamaxbytes' => 10485760, 'maxfiles' => 1,
                'accepted_types' => array('.png', '.jpg', '.gif')));
        // Button color
        $mform->addElement('text', 'config_button_color', get_string('config_button_color', 'block_eva_about_3'), array('class' => 'ccn_spectrum_class'));
        $mform->setDefault('config_button_color', 'rgb(1, 16, 31)');
        $mform->setType('config_button_color', PARAM_TEXT);
        // Button color hover
        $mform->addElement('text', 'config_button_color_hover', get_string('config_button_color_hover', 'block_eva_about_3'), array('class' => 'ccn_spectrum_class'));
        $mform->setDefault('config_button_color_hover', 'rgb(255, 255, 255)');
        $mform->setType('config_button_color_hover', PARAM_TEXT);
        // Button text color
        $mform->addElement('text', 'config_button_text_color', get_string('config_button_text_color', 'block_eva_about_3'), array('class' => 'ccn_spectrum_class'));
        $mform->setDefault('config_button_text_color', 'rgb(255, 255, 255)');
        $mform->setType('config_button_text_color', PARAM_TEXT);
        // Button text color hover
        $mform->addElement('text', 'config_button_text_color_hover', get_string('config_button_text_color_hover', 'block_eva_about_3'), array('class' => 'ccn_spectrum_class'));
        $mform->setDefault('config_button_text_color_hover', 'rgb(0, 0, 0)');
        $mform->setType('config_button_text_color_hover', PARAM_TEXT);
        // Button URL
        $mform->addElement('text', 'config_button_url', get_string('config_button_url', 'block_eva_about_3'));
        $mform->setDefault('config_button_url', 'https://www.youtube.com/c/EscoladaAGU/playlists');
        $mform->setType('config_button_url', PARAM_RAW);
        // Button Text
        $mform->addElement('text', 'config_button_text', get_string('config_button_text', 'block_eva_about_3'));
        $mform->setDefault('config_button_text', 'Mais vídeos');
        $mform->setType('config_button_text', PARAM_RAW);
        $radioarray = array();
        $radioarray[] = $mform->createElement('radio', 'config_style', '', 'Image Left', 0, $attributes);
        $radioarray[] = $mform->createElement('radio', 'config_style', '', 'Image Right', 1, $attributes);
        $mform->addGroup($radioarray, 'config_style', 'Style', array(' '), false);
        include ($CFG->dirroot . '/theme/evagu/ccn/block_handler/edit.php');
    }

    function set_data($defaults)
    {
        $field = 'image';
        $conffield = 'config_image';
        $draftitemid = file_get_submitted_draft_itemid($conffield);
        file_prepare_draft_area($draftitemid, $this->block->context->id, 'block_eva_about_3', 'content', 0, array('subdirs' => false));
        $defaults->$conffield['itemid'] = $draftitemid;
        $this->block->config->$field = $draftitemid;
        parent::set_data($defaults);
        if ($data = parent::get_data()) {
            file_save_draft_area_files($data->config_image, $this->block->context->id, 'block_eva_about_3', 'content', 0,
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
            $defaults->config_body['text'] = file_prepare_draft_area($draftid_editor, $this->block->context->id, 'block_eva_about_3', 'content', 0, array('subdirs' => true), $currenttext);
            $defaults->config_body['itemid'] = $draftid_editor;
            $defaults->config_body['format'] = $this->block->config->format;
        } else {
            $text = '';
        }
    }
}
