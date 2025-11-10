<?php
class block_eva_event_slider_edit_form extends block_edit_form {
    protected function specific_definition($mform) {
        global $CFG;
        // Se já houver configuração, usa os valores; caso contrário, define um objeto padrão.
        if (!empty($this->block->config) && is_object($this->block->config)) {
            $data = $this->block->config;
        } else {
            $data = new stdClass();
            $data->slidesnumber = 0;
        }
        // Cabeçalho do formulário de configuração do bloco
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));
        // Título do bloco
        $mform->addElement('text', 'config_title', get_string('config_title', 'theme_evagu'));
        $mform->setDefault('config_title', 'Próximos Eventos');
        $mform->setType('config_title', PARAM_TEXT);
        // Subtítulo do bloco
        $mform->addElement('text', 'config_subtitle', get_string('config_subtitle', 'theme_evagu'));
        $mform->setDefault('config_subtitle', 'Modalidades online e presenciais da Escola Superior da AGU');
        $mform->setType('config_subtitle', PARAM_TEXT);
        /*
         * Campo para definir o texto que, se preenchido, fará com que o botão seja exibido.
         * Esse campo será armazenado como "footer_text" na configuração do bloco.
         */
        $mform->addElement('text', 'config_footer_text', get_string('config_footer_text', 'theme_evagu'));
        $mform->setDefault('config_footer_text', ''); // Deixe em branco se não quiser exibir o botão.
        $mform->setType('config_footer_text', PARAM_RAW);
        // Texto do botão (exibido quando o footer_text estiver preenchido)
        $mform->addElement('text', 'config_button_text', get_string('config_button_text', 'theme_evagu'));
        $mform->setDefault('config_button_text', 'Ver todas as ações');
        $mform->setType('config_button_text', PARAM_RAW);
        // Link para o botão
        $mform->addElement('text', 'config_button_link', get_string('config_button_link', 'theme_evagu'));
        $mform->setDefault('config_button_link', '/blog/index.php');
        $mform->setType('config_button_link', PARAM_RAW);
        // Escolha do estilo do slider (Standard ou Fullsize)
        $radioarray = array();
        $radioarray[] = $mform->createElement('radio', 'config_style', '', 'Standard', 0);
        $radioarray[] = $mform->createElement('radio', 'config_style', '', 'Fullsize', 1);
        $mform->addGroup($radioarray, 'config_style', 'Slider Size', array(' '), false);
        // Número de slides
        $slidesrange = range(0, 12);
        $mform->addElement('select', 'config_slidesnumber', get_string('config_items', 'theme_evagu'), $slidesrange);
        $mform->setDefault('config_slidesnumber', $data->slidesnumber);
        // Para cada slide, define os campos necessários
        for ($i = 1; $i <= $data->slidesnumber; $i++) {
            $mform->addElement('header', 'config_header' . $i, 'Slide ' . $i);
            // Título do slide
            $mform->addElement('text', 'config_slide_title' . $i, get_string('config_title', 'theme_evagu', $i));
            $mform->setDefault('config_slide_title' . $i, 'Titulo para o Editor do evento');
            $mform->setType('config_slide_title' . $i, PARAM_TEXT);
            // Data do slide
            $mform->addElement('date_selector', 'config_slide_date' . $i, get_string('config_date', 'theme_evagu', $i));
            // Local do evento
            $mform->addElement('text', 'config_slide_location' . $i, get_string('config_location', 'theme_evagu', $i));
            $mform->setDefault('config_slide_location' . $i, 'Brasília, Distrito Federal');
            $mform->setType('config_slide_location' . $i, PARAM_TEXT);
            // Horário do evento
            $mform->addElement('text', 'config_slide_time' . $i, get_string('config_time', 'theme_evagu', $i));
            $mform->setDefault('config_slide_time' . $i, '8:00 am - 15:00 pm');
            $mform->setType('config_slide_time' . $i, PARAM_TEXT);
            // Link do slide
            $mform->addElement('text', 'config_slide_url' . $i, get_string('config_link', 'theme_evagu', $i));
            $mform->setDefault('config_slide_url' . $i, '#');
            $mform->setType('config_slide_url' . $i, PARAM_TEXT);
            // Imagem do slide (usando filemanager)
            $filemanageroptions = array(
                'maxbytes'       => $CFG->maxbytes,
                'subdirs'        => 0,
                'maxfiles'       => 1,
                'accepted_types' => array('.jpg', '.png', '.jpeg')
            );
            $mform->addElement('filemanager', 'config_file_slide' . $i, get_string('config_image', 'theme_evagu', $i), null, $filemanageroptions);
        }
        // Inclui o arquivo de edição específico (se houver) do block_handler
        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/edit.php');
    }
    function set_data($defaults) {
        if (!empty($this->block->config) && is_object($this->block->config)) {
            for ($i = 1; $i <= $this->block->config->slidesnumber; $i++) {
                $field = 'file_slide' . $i;
                $conffield = 'config_file_slide' . $i;
                $draftitemid = file_get_submitted_draft_itemid($conffield);
                file_prepare_draft_area($draftitemid, $this->block->context->id, 'block_eva_event_slider', 'slides', $i, array('subdirs' => false));
                $defaults->$conffield['itemid'] = $draftitemid;
                $this->block->config->$field = $draftitemid;
            }
        }
        parent::set_data($defaults);
    }
}
