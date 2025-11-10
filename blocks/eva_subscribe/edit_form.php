<?php
class block_eva_subscribe_edit_form extends block_edit_form
{
    protected function specific_definition($mform)
    {
        global $CFG;
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));
        $mform->addElement('text', 'config_title', get_string('config_title', 'theme_evagu'));
        $mform->setDefault('config_title', 'Get Newsletter');
        $mform->setType('config_title', PARAM_RAW);
        $mform->addElement('text', 'config_subtitle', get_string('config_subtitle', 'theme_evagu'));
        $mform->setDefault('config_subtitle', 'Your download should start automatically, if not Click here. Do you want our newsletter?');
        $mform->setType('config_subtitle', PARAM_RAW);
        $mform->addElement('text', 'config_button_text', get_string('config_button_text', 'theme_evagu'));
        $mform->setDefault('config_button_text', 'Get it Now');
        $mform->setType('config_button_text', PARAM_RAW);
        $mform->addElement('header', 'config_ccn_colors', get_string('block_styles', 'theme_evagu'));
        $mform->addElement('text', 'config_color_bg', get_string('config_color_bg', 'theme_evagu'), array('class' => 'ccn_spectrum_class'));
        $mform->setDefault('config_color_bg', '#f9fafc');
        $mform->setType('config_color_bg', PARAM_TEXT);
        $mform->addElement('text', 'config_color_title', get_string('config_color_title', 'theme_evagu'), array('class' => 'ccn_spectrum_class'));
        $mform->setDefault('config_color_title', '#0a0a0a');
        $mform->setType('config_color_title', PARAM_TEXT);
        $mform->addElement('text', 'config_color_subtitle', get_string('config_color_subtitle', 'theme_evagu'), array('class' => 'ccn_spectrum_class'));
        $mform->setDefault('config_color_subtitle', '#6f7074');
        $mform->setType('config_color_subtitle', PARAM_TEXT);
        $mform->addElement('text', 'config_color_btn', get_string('config_color_btn', 'theme_evagu'), array('class' => 'ccn_spectrum_class'));
        $mform->setDefault('config_color_btn', '#035D9B');
        $mform->setType('config_color_btn', PARAM_TEXT);
        $mform->addElement('text', 'config_color_btn_hover', get_string('config_color_btn', 'theme_evagu') . get_string('on_hover', 'theme_evagu'), array('class' => 'ccn_spectrum_class'));
        $mform->setDefault('config_color_btn_hover', '#035D9B');
        $mform->setType('config_color_btn_hover', PARAM_TEXT);
        $options = array(
            '0' => 'Yes',
            '1' => 'No',
        );
        $select = $mform->addElement('select', 'config_box_shadow', get_string('config_box_shadow', 'theme_evagu'), $options, array('class' => 'ccnCommLcRef_change'));
        $select->setSelected('0');
        include ($CFG->dirroot . '/theme/evagu/ccn/block_handler/edit.php');
    }

    function set_data($defaults)
    {
        if (empty($entry->id)) {
            $entry = new stdClass;
            $entry->id = null;
        }
        $draftitemid = file_get_submitted_draft_itemid('config_image');
        file_prepare_draft_area($draftitemid, $this->block->context->id, 'block_eva_subscribe', 'content', 0,
            array('subdirs' => true));
        $entry->attachments = $draftitemid;
        parent::set_data($defaults);
        if ($data = parent::get_data()) {
            file_save_draft_area_files($data->config_image, $this->block->context->id, 'block_eva_subscribe', 'content', 0,
                array('subdirs' => true));
        }
    }
}
