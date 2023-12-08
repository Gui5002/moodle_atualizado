<?php

class block_eva_list_study_room_edit_form extends block_edit_form
{
    protected function specific_definition($mform)
    {
        global $CFG;
        $ccnFontList = include($CFG->dirroot . '/theme/evagu/ccn/font_handler/ccn_font_select.php');
        require_once($CFG->dirroot . '/theme/evagu/ccn/event_handler/ccn_event_handler.php');
        include_once($CFG->dirroot . '/course/lib.php');
        require_once($CFG->dirroot . '/course/renderer.php');

        $ccnEventHandler = new ccnEventHandler();
        $ccnEventList = $ccnEventHandler->ccnEventList();

        $searchareas = \core_search\manager::get_search_areas_list(true);
        $areanames = array();
        foreach ($ccnEventList as $k => $ccnEvent) {
            $areanames[$k] = $ccnEvent->name;
        }

        if (!empty($this->block->config) && is_object($this->block->config)) {
            $data = $this->block->config;
        } else {
            $data = new stdClass();
            $data->items = 6;
        }

        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));

        // Title
        $mform->addElement('text', 'config_title', get_string('config_title', 'theme_evagu'));
        $mform->setDefault('config_title', 'Salas de estudo em andamento');
        $mform->setType('config_title', PARAM_RAW);

        // Subtitle
        $mform->addElement('text', 'config_subtitle', get_string('config_subtitle', 'theme_evagu'));
        $mform->setDefault('config_subtitle', 'Breve descritivo falando da salas de estudo da EVA.');
        $mform->setType('config_subtitle', PARAM_RAW);

        // Button Text
        $mform->addElement('text', 'config_button_text', get_string('config_button_text', 'theme_evagu'));
        $mform->setDefault('config_button_text', 'Ver todas');
        $mform->setType('config_button_text', PARAM_RAW);

        $items_range = array(
            1 => '1',
            2 => '2',
            3 => '3',
            4 => '4',
            5 => '5',
            6 => '6',
        );

        $items_max = 6;

        $mform->addElement('select', 'config_items', get_string('config_items', 'theme_evagu'), $items_range);
        $mform->setDefault('config_items', 6);
        $mform->addRule('config_items', get_string('required'), 'required', null, 'client', false, false);

        for ($i = 1; $i <= $items_max; $i++) {
            $mform->addElement('header', 'config_ccn_item' . $i,  get_string('config_item', 'theme_evagu') . ' ' . $i);

            $mform->addElement('text', 'config_color' . $i, get_string('config_color', 'theme_evagu', $i), array('class' => 'ccn_spectrum_class'));
            $mform->setDefault('config_color' . $i, '#23ad96');
            $mform->setType('config_color' . $i, PARAM_TEXT);
            $mform->addRule('config_color' . $i, get_string('required'), 'required', null, 'client', false, false);

            $mform->addElement('text', 'config_color_hover' . $i, get_string('config_color', 'theme_evagu') . get_string('on_hover', 'theme_evagu'), array('class' => 'ccn_spectrum_class'));
            $mform->setDefault('config_color_hover' . $i, '#3f3b64');
            $mform->setType('config_color_hover' . $i, PARAM_TEXT);
            $mform->addRule('config_color_hover' . $i, get_string('required'), 'required', null, 'client', false, false);

            $options = array(
                'multiple' => false,
                'noselectionstring' => get_string('select_from_dropdown', 'theme_evagu'),
            );
            $mform->addElement('autocomplete', 'config_event' . $i, get_string('event', 'theme_evagu'), $areanames, $options);
            $mform->addRule('config_event' . $i, get_string('required'), 'required', null, 'client', false, false);
        }

        $mform->addElement('header', 'config_ccn_colors', get_string('block_styles', 'theme_evagu'));

        $mform->addElement('text', 'config_color_bg', get_string('config_color_bg', 'theme_evagu'), array('class' => 'ccn_spectrum_class'));
        $mform->setDefault('config_color_bg', '#f8f8f8');
        $mform->setType('config_color_bg', PARAM_TEXT);

        $mform->addElement('text', 'config_color_title', get_string('config_color_title', 'theme_evagu'), array('class' => 'ccn_spectrum_class'));
        $mform->setDefault('config_color_title', '#0a0a0a');
        $mform->setType('config_color_title', PARAM_TEXT);

        $mform->addElement('text', 'config_color_subtitle', get_string('config_color_subtitle', 'theme_evagu'), array('class' => 'ccn_spectrum_class'));
        $mform->setDefault('config_color_subtitle', 'rgb(111, 112, 116)');
        $mform->setType('config_color_subtitle', PARAM_TEXT);

        $mform->addElement('text', 'config_color_btn', get_string('config_color_btn', 'theme_evagu'), array('class' => 'ccn_spectrum_class'));
        $mform->setDefault('config_color_btn', '#555555');
        $mform->setType('config_color_btn', PARAM_TEXT);

        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/edit.php');
    }
}
