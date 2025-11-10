<?php
class block_eva_partners_edit_form extends block_edit_form
{
    protected function specific_definition($mform)
    {
        global $CFG;
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));
        $mform->addElement('text', 'config_title', get_string('config_title', 'block_eva_partners'));
        $mform->setDefault('config_title', 'Need To Train Your Team?');
        $mform->setType('config_title', PARAM_RAW);
        $mform->addElement('text', 'config_subtitle', get_string('config_subtitle', 'block_eva_partners'));
        $mform->setDefault('config_subtitle', 'Os serviços de uma escola superior tem uma variedade de atividades.');
        $mform->setType('config_subtitle', PARAM_RAW);
        $mform->addElement(
            'filemanager',
            'config_image',
            get_string('config_image', 'block_eva_partners'),
            null,
            array(
                'subdirs' => 0,
                'maxbytes' => $maxbytes,
                'areamaxbytes' => 10485760,
                'maxfiles' => null,
                'accepted_types' => array('.png', '.jpg', '.gif')
            )
        );
        $mform->addElement('header', 'config_ccn_colors', get_string('block_styles', 'theme_evagu'));
        $mform->addElement('text', 'config_color_bg', get_string('config_color_bg', 'theme_evagu'), array('class' => 'ccn_spectrum_class'));
        $mform->setDefault('config_color_bg', '#fff');
        $mform->setType('config_color_bg', PARAM_TEXT);
        $mform->addElement('text', 'config_color_title', get_string('config_color_title', 'theme_evagu'), array('class' => 'ccn_spectrum_class'));
        $mform->setDefault('config_color_title', '#0a0a0a');
        $mform->setType('config_color_title', PARAM_TEXT);
        $mform->addElement('text', 'config_color_subtitle', get_string('config_color_subtitle', 'theme_evagu'), array('class' => 'ccn_spectrum_class'));
        $mform->setDefault('config_color_subtitle', '#6f7074');
        $mform->setType('config_color_subtitle', PARAM_TEXT);
        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/edit.php');
    }
    function set_data($defaults)
    {
        if (empty($entry->id)) {
            $entry = new stdClass;
            $entry->id = null;
        }
        $draftitemid = file_get_submitted_draft_itemid('config_image');
        file_prepare_draft_area(
            $draftitemid,
            $this->block->context->id,
            'block_eva_partners',
            'content',
            0,
            array('subdirs' => true)
        );
        $entry->attachments = $draftitemid;
        parent::set_data($defaults);
        if ($data = parent::get_data()) {
            file_save_draft_area_files(
                $data->config_image,
                $this->block->context->id,
                'block_eva_partners',
                'content',
                0,
                array('subdirs' => true)
            );
        }
        if (!empty($this->block->config) && is_object($this->block->config)) {
            $text = $this->block->config->bio;
            $draftid_editor = file_get_submitted_draft_itemid('config_bio');
            if (empty($text)) {
                $currenttext = '';
            } else {
                $currenttext = $text;
            }
            $defaults->config_bio['text'] = file_prepare_draft_area($draftid_editor, $this->block->context->id, 'block_eva_partners', 'content', 0, array('subdirs' => true), $currenttext);
            $defaults->config_bio['itemid'] = $draftid_editor;
            $defaults->config_bio['format'] = $this->block->config->format;
        } else {
            $text = '';
        }
    }
}
