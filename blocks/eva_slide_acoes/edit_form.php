<?php
class block_eva_slide_acoes_edit_form extends block_edit_form {
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
        // Inclui o arquivo de edição específico (se houver) do block_handler
        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/edit.php');
    }
    function set_data($defaults) {
        parent::set_data($defaults);
    }
}
