<?php
class block_eva_video_edit_form extends block_edit_form
{
    protected function specific_definition($mform)
    {
        global $CFG;
        $ccnFontList = include $CFG->dirroot . '/theme/evagu/ccn/font_handler/ccn_font_select.php';
        if (!empty($this->block->config) && is_object($this->block->config)) {
            $data = $this->block->config;
        } else {
            $data = new stdClass();
            $data->slidesnumber = 1;
        }
        // Fields for editing HTML block title and contents.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));
        // Title
        $mform->addElement('text', 'config_title', get_string('config_title', 'block_eva_video'));
        $mform->setDefault('config_title', 'Como acessar a plataforma EVA AGU?');
        $mform->setType('config_title', PARAM_RAW);
        // Subtitle
        $mform->addElement('text', 'config_subtitle', get_string('config_subtitle', 'block_eva_video'));
        $mform->setDefault('config_subtitle', 'Veja o video explicativo para acesso plataforma');
        $mform->setType('config_subtitle', PARAM_RAW);
        // URL video
        $mform->addElement('text', 'config_video_url', get_string('config_video', 'theme_evagu'));
        $mform->setDefault('config_video_url', 'https://www.youtube.com/watch?v=zZN7rfwg2E0');
        $mform->setType('config_video_url', PARAM_RAW);
        // Image
        $mform->addElement(
            'filemanager', 'config_image', get_string('config_image', 'theme_evagu'), null,
            array('subdirs' => 0, 'maxbytes' => $maxbytes, 'maxfiles' => 1,
                'accepted_types' => array('.png', '.jpg', '.gif'),
            ));
// Style
        $mform->addElement('header', 'config_ccn_colors', get_string('block_styles', 'theme_evagu'));
        $mform->addElement('text', 'config_color_bfbg', get_string('config_color_bg', 'theme_evagu'), array('class' => 'ccn_spectrum_class'));
        $mform->setDefault('config_color_bfbg', '#f9f9f9');
        $mform->setType('config_color_bfbg', PARAM_TEXT);
        $mform->addElement('text', 'config_color_title', get_string('config_color_title', 'theme_evagu'), array('class' => 'ccn_spectrum_class'));
        $mform->setDefault('config_color_title', '#0067da');
        $mform->setType('config_color_title', PARAM_TEXT);
        $mform->addElement('text', 'config_color_subtitle', get_string('config_color_subtitle', 'theme_evagu'), array('class' => 'ccn_spectrum_class'));
        $mform->setDefault('config_color_subtitle', '#222222');
        $mform->setType('config_color_subtitle', PARAM_TEXT);
        $mform->addElement('text', 'config_color_overlay', get_string('config_color_overlay', 'theme_evagu'), array('class' => 'ccn_spectrum_class'));
        $mform->setDefault('config_color_overlay', 'rgb(34, 34, 34, .4)');
        $mform->setType('config_color_overlay', PARAM_TEXT);
        include $CFG->dirroot . '/theme/evagu/ccn/block_handler/edit.php';
    }
    public function set_data($defaults)
    {
        $field = 'image';
        $conffield = 'config_image';
        $draftitemid = file_get_submitted_draft_itemid($conffield);
        file_prepare_draft_area($draftitemid, $this->block->context->id, 'block_eva_video', 'content', 0, array('subdirs' => false));
        $defaults->$conffield['itemid'] = $draftitemid;
        $this->block->config->$field = $draftitemid;
        //$entry->attachments = $draftitemid;
        parent::set_data($defaults);
        if ($data = parent::get_data()) {
            file_save_draft_area_files($data->config_image, $this->block->context->id, 'block_eva_video', 'content', 0, array('subdirs' => true));
        }
        // END CCN Image Processing
    }
}
