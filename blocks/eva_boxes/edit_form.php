<?php
class block_eva_boxes_edit_form extends block_edit_form
{
    protected function specific_definition($mform)
    {
        global $CFG;
        $ccnFontList = include ($CFG->dirroot . '/theme/evagu/ccn/font_handler/ccn_font_select.php');
        if (!empty($this->block->config) && is_object($this->block->config)) {
            $data = $this->block->config;
        } else {
            $data = new stdClass();
            $data->items = 4;
        }
        // language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));
        // Title
        $mform->addElement('text', 'config_title', get_string('config_title', 'theme_evagu'));
        $mform->setDefault('config_title', 'Serviços ESAGU');
        $mform->setType('config_title', PARAM_RAW);
        // Subtitle
        $mform->addElement('text', 'config_subtitle', get_string('config_subtitle', 'theme_evagu'));
        $mform->setDefault('config_subtitle', 'Acesso rapido as ações, eventos e cursos AGU.');
        $mform->setType('config_subtitle', PARAM_RAW);
        $items_range = array(
            1 => '1',
            2 => '2',
            3 => '3',
            4 => '4',
            5 => '5',
            6 => '6',
            7 => '7',
            8 => '8',
        );
        $items_max = 8;
        $mform->addElement('select', 'config_items', get_string('config_items', 'theme_evagu'), $items_range);
        $mform->setDefault('config_items', 4);
        for ($i = 1; $i <= $items_max; $i++) {
            $mform->addElement('header', 'config_ccn_item' . $i, get_string('config_item', 'theme_evagu') . $i);
            $mform->addElement('text', 'config_title' . $i, get_string('config_title', 'theme_evagu', $i));
            $mform->setDefault('config_title' . $i, 'Create Account');
            $mform->setType('config_title' . $i, PARAM_TEXT);
            $mform->addElement('text', 'config_body' . $i, get_string('config_body', 'theme_evagu', $i));
            $mform->setDefault('config_body' . $i, 'Breve Texto complementando o titulo.');
            $mform->setType('config_body' . $i, PARAM_TEXT);
            $mform->addElement('text', 'config_link' . $i, get_string('config_link', 'theme_evagu', $i));
            $mform->setDefault('config_link' . $i, '#');
            $mform->setType('config_link' . $i, PARAM_TEXT);
            $options = array(
                '_self' => 'Self (open in same window)',
                '_blank' => 'Blank (open in new window)',
                '_parent' => 'Parent (open in parent frame)',
                '_top' => 'Top (open in full body of the window)',
            );
            $select = $mform->addElement('select', 'config_link_target' . $i, get_string('config_button_target', 'theme_evagu'), $options);
            $select->setSelected('_self');
            $select = $mform->addElement('select', 'config_icon' . $i, get_string('config_icon_class', 'theme_evagu'), $ccnFontList, array('class' => 'ccn_icon_class'));
            $select->setSelected('flaticon-student-3');
            $mform->addElement('text', 'config_color' . $i, get_string('config_color_bg', 'theme_evagu', $i), array('class' => 'ccn_spectrum_class'));
            $mform->setDefault('config_color' . $i, 'rgb(245, 245, 246)');
            $mform->setType('config_color' . $i, PARAM_TEXT);
            $mform->addElement('text', 'config_color_hover' . $i, get_string('config_color_bg', 'theme_evagu') . get_string('on_hover', 'theme_evagu'), array('class' => 'ccn_spectrum_class'));
            $mform->setDefault('config_color_hover' . $i, 'rgb(62, 68, 72)');
            $mform->setType('config_color_hover' . $i, PARAM_TEXT);
            $mform->addElement('text', 'config_color_icon' . $i, get_string('config_color_icon', 'theme_evagu', $i), array('class' => 'ccn_spectrum_class'));
            $mform->setDefault('config_color_icon' . $i, 'rgb(0 0 0');
            $mform->setType('config_color_icon' . $i, PARAM_TEXT);
            $mform->addElement('text', 'config_color_icon_hover' . $i, get_string('config_color_icon', 'theme_evagu') . get_string('on_hover', 'theme_evagu'), array('class' => 'ccn_spectrum_class'));
            $mform->setDefault('config_color_icon_hover' . $i, 'rgb(245, 245, 246)');
            $mform->setType('config_color_icon_hover' . $i, PARAM_TEXT);
            $mform->addElement('text', 'config_color_title' . $i, get_string('config_color_title', 'theme_evagu', $i), array('class' => 'ccn_spectrum_class'));
            $mform->setDefault('config_color_title' . $i, 'rgb(62, 68, 72)');
            $mform->setType('config_color_title' . $i, PARAM_TEXT);
            $mform->addElement('text', 'config_color_title_hover' . $i, get_string('config_color_title', 'theme_evagu') . get_string('on_hover', 'theme_evagu'), array('class' => 'ccn_spectrum_class'));
            $mform->setDefault('config_color_title_hover' . $i, 'rgb(255,255,255)');
            $mform->setType('config_color_title_hover' . $i, PARAM_TEXT);
            $mform->addElement('text', 'config_color_body' . $i, get_string('config_color_body', 'theme_evagu', $i), array('class' => 'ccn_spectrum_class'));
            $mform->setDefault('config_color_body' . $i, 'rgb(62, 68, 72)');
            $mform->setType('config_color_body' . $i, PARAM_TEXT);
            $mform->addElement('text', 'config_color_body_hover' . $i, get_string('config_color_body', 'theme_evagu') . get_string('on_hover', 'theme_evagu'), array('class' => 'ccn_spectrum_class'));
            $mform->setDefault('config_color_body_hover' . $i, 'rgb(255,255,255)');
            $mform->setType('config_color_body_hover' . $i, PARAM_TEXT);
        }
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
        include ($CFG->dirroot . '/theme/evagu/ccn/block_handler/edit.php');
    }
}
