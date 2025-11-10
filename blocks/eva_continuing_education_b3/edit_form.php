<?php

defined('MOODLE_INTERNAL') || die();

class block_eva_continuing_education_b3_edit_form extends block_edit_form {

    public function specific_definition($mform) {
        global $CFG, $PAGE;

        //icons list
        $ccnFontList = include($CFG->dirroot . '/theme/evagu/ccn/font_handler/ccn_font_select.php');

        // language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));

        // Title
        $mform->addElement('text', 'config_title', get_string('config_title', 'block_eva_continuing_education_b3'));
        $mform->setType('config_title', PARAM_RAW);

        // Subtitle
        $mform->addElement('text', 'config_subtitle', get_string('config_subtitle', 'block_eva_continuing_education_b3'));
        $mform->setType('config_subtitle', PARAM_RAW);

        // Text
        $mform->addElement('text', 'config_text', get_string('config_text', 'block_eva_continuing_education_b3'));
        $mform->setType('config_text', PARAM_RAW);

        //====== Itens range ======//
        $items_range = array(
            1 => '1',
            2 => '2',
            3 => '3',
            4 => '4',
            5 => '5',
            6 => '6',
            7 => '7',
            8 => '8',
            9 => '9',
            10 => '10',
            11 => '11',
            12 => '12',
            13 => '13',
            14 => '14',
            15 => '15',
            16 => '16',
            17 => '17',
            18 => '18',
            19 => '19',
            20 => '20',
        );

        $items_max = 20;

        $mform->addElement('select', 'config_items', get_string('config_items', 'block_eva_continuing_education_b3'), $items_range);
        $mform->setDefault('config_items', 20);
        //====== Itens range ======//

        //Background color
        $mform->addElement('text', 'config_color', get_string('config_color_bg', 'block_eva_continuing_education_b3'), array('class' => 'ccn_spectrum_class'));
        $mform->setDefault('config_color', 'rgb(245, 245, 246)');
        $mform->setType('config_color', PARAM_TEXT);

        //Title color
        $mform->addElement('text', 'config_title_color', get_string('config_title_color', 'block_eva_continuing_education_b3'), array('class' => 'ccn_spectrum_class'));
        $mform->setDefault('config_title_color', 'rgb(0, 0, 0)');
        $mform->setType('config_title_color', PARAM_TEXT);

        //Subtitle color
        $mform->addElement('text', 'config_subtitle_color', get_string('config_subtitle_color', 'block_eva_continuing_education_b3'), array('class' => 'ccn_spectrum_class'));
        $mform->setDefault('config_subtitle_color', 'rgb(157, 155, 154)');
        $mform->setType('config_subtitle_color', PARAM_TEXT);

        //Text color
        $mform->addElement('text', 'config_text_color', get_string('config_text_color', 'block_eva_continuing_education_b3'), array('class' => 'ccn_spectrum_class'));
        $mform->setDefault('config_text_color', 'rgb(0, 0, 0)');
        $mform->setType('config_text_color', PARAM_TEXT);

        //Cards text color
        $mform->addElement('text', 'config_cards_text_color', get_string('config_cards_text_color', 'block_eva_continuing_education_b3'), array('class' => 'ccn_spectrum_class'));
        $mform->setDefault('config_cards_text_color', 'rgb(0, 0, 0)');
        $mform->setType('config_cards_text_color', PARAM_TEXT);

        //Cards color
        $mform->addElement('text', 'config_cards_color', get_string('config_cards_color', 'block_eva_continuing_education_b3'), array('class' => 'ccn_spectrum_class'));
        $mform->setDefault('config_cards_color', 'rgb(167, 183, 199)');
        $mform->setType('config_cards_color', PARAM_TEXT);

        //Cards color hover
        $mform->addElement('text', 'config_cards_color_hover', get_string('config_cards_color_hover', 'block_eva_continuing_education_b3'), array('class' => 'ccn_spectrum_class'));
        $mform->setDefault('config_cards_color_hover', 'rgb(63, 158, 214)');
        $mform->setType('config_cards_color_hover', PARAM_TEXT);

        for ($i = 1; $i <= $items_max; $i++) {
            $mform->addElement('header', 'config_ccn_item' . $i, get_string('config_item', 'theme_evagu') . $i);

            $mform->addElement('text', 'config_item_title' . $i, get_string('config_item_title', 'block_eva_continuing_education_b3', $i));
            // $mform->setDefault('config_item_title' . $i, 'Título do item');
            $mform->setType('config_item_title' . $i, PARAM_TEXT);

            $mform->addElement('text', 'config_item_subtitle' . $i, get_string('config_item_subtitle', 'block_eva_continuing_education_b3', $i));
            // $mform->setDefault('config_item_subtitle' . $i, 'Turmas por Perfil');
            $mform->setType('config_item_subtitle' . $i, PARAM_TEXT);

            $mform->addElement('text', 'config_item_body' . $i, get_string('config_item_body', 'block_eva_continuing_education_b3', $i));
            // $mform->setDefault('config_item_body' . $i, 'Objetivo da Capacitação : Texto com breve descritivo do que vai aprender nessa capacitação Texto Texto Texto Texto até 4 linhas.');
            $mform->setType('config_item_body' . $i, PARAM_TEXT);

            $mform->addElement('text', 'config_item_link' . $i, get_string('config_item_link', 'block_eva_continuing_education_b3', $i));
            // $mform->setDefault('config_item_body' . $i, 'Objetivo da Capacitação : Texto com breve descritivo do que vai aprender nessa capacitação Texto Texto Texto Texto até 4 linhas.');
            $mform->setType('config_item_link' . $i, PARAM_TEXT);

            $select = $mform->addElement('select', 'config_item_icon' . $i, get_string('config_item_icon', 'block_eva_continuing_education_b3'), $ccnFontList, array('class' => 'ccn_icon_class'));
            $select->setSelected('flaticon-student-3');
        }

        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/edit.php');
    }
}