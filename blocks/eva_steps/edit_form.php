<?php
class block_eva_steps_edit_form extends block_edit_form
{
    protected function specific_definition($mform)
    {
        global $CFG;
        $ccnFontList = include ($CFG->dirroot . '/theme/evagu/ccn/font_handler/ccn_font_select.php');
        if (!empty($this->block->config) && is_object($this->block->config)) {
            $data = $this->block->config;
        } else {
            $data = new stdClass();
            $data->slidesnumber = 3;
        }
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));
        $mform->addElement('text', 'config_title', get_string('config_title', 'theme_evagu'));
        $mform->setDefault('config_title', 'See How It Works');
        $mform->setType('config_title', PARAM_TEXT);
        $mform->addElement('text', 'config_subtitle', get_string('config_subtitle', 'theme_evagu'));
        $mform->setDefault('config_subtitle', 'Os serviços de uma escola superior tem uma variedade de atividades.');
        $mform->setType('config_subtitle', PARAM_TEXT);
        $slidesrange = array(
            3 => '3',
            4 => '4',
            5 => '5',
            6 => '6',
        );
        $slidesmax = 6;
        $mform->addElement('select', 'config_slidesnumber', get_string('config_items', 'theme_evagu'), $slidesrange);
        $mform->setDefault('config_slidesnumber', 3);
        for ($i = 1; $i <= $slidesmax; $i++) {
            $mform->addElement('header', 'config_ccn_item' . $i, get_string('config_item', 'theme_evagu') . $i);
            $mform->addElement('text', 'config_title' . $i, get_string('config_title', 'theme_evagu', $i));
            $mform->setDefault('config_title' . $i, 'Choose What To Do');
            $mform->setType('config_title' . $i, PARAM_TEXT);
            $mform->addElement('textarea', 'config_body' . $i, get_string('config_body', 'theme_evagu', $i));
            $mform->setDefault('config_body' . $i, 'Adicionar pequeno texto com breve chamada para o serviço prestado');
            $mform->setType('config_body' . $i, PARAM_TEXT);
            $mform->addElement('text', 'config_link' . $i, get_string('config_link', 'theme_evagu', $i));
            $mform->setDefault('config_link' . $i, '#');
            $mform->setType('config_link' . $i, PARAM_TEXT);
            $filemanageroptions = array('maxbytes' => $CFG->maxbytes,
                'subdirs' => 0,
                'maxfiles' => 1,
                'accepted_types' => array('.jpg', '.png', '.gif'));
            $f = $mform->addElement('filemanager', 'config_image' . $i, get_string('config_image', 'theme_evagu', $i), null, $filemanageroptions);
        }
        include ($CFG->dirroot . '/theme/evagu/ccn/block_handler/edit.php');
    }

    function set_data($defaults)
    {
        if (!empty($this->block->config) && is_object($this->block->config)) {
            for ($i = 1; $i <= $this->block->config->slidesnumber; $i++) {
                $field = 'image' . $i;
                $conffield = 'config_image' . $i;
                $draftitemid = file_get_submitted_draft_itemid($conffield);
                file_prepare_draft_area($draftitemid, $this->block->context->id, 'block_eva_steps', 'items', $i, array('subdirs' => false));
                $defaults->$conffield['itemid'] = $draftitemid;
                $this->block->config->$field = $draftitemid;
            }
        }
        parent::set_data($defaults);
    }
}
