<?php

class block_eva_featured_video_edit_form extends block_edit_form
{
    protected function specific_definition($mform)
    {
        global $CFG;
        $ccnFontList = include($CFG->dirroot . '/theme/evagu/ccn/font_handler/ccn_font_select.php');

        if (!empty($this->block->config) && is_object($this->block->config)) {
            $data = $this->block->config;
        } else {
            $data = new stdClass();
            $data->slidesnumber = 4;
        }


        // Fields for editing HTML block title and contents.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        $mform->addElement('text', 'config_video_url', get_string('config_video', 'theme_evagu'));
        $mform->setDefault('config_video_url', 'https://www.youtube.com/watch?v=fBRee7p7S_A');
        $mform->setType('config_video_url', PARAM_RAW);

        // Image
        $mform->addElement(
            'filemanager',
            'config_image',
            get_string('config_image', 'theme_evagu'),
            null,
            array(
                'subdirs' => 0, 'maxbytes' => $maxbytes, 'maxfiles' => 1,
                'accepted_types' => array('.png', '.jpg', '.gif')
            )
        );

        $ccnItemsRange = array(
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
        );

        $ccnItemsMax = 12;

        $mform->addElement('select', 'config_slidesnumber', get_string('config_items', 'theme_evagu'), $ccnItemsRange);
        $mform->setDefault('config_slidesnumber', 4);

        for ($i = 1; $i <= $ccnItemsMax; $i++) {

            $mform->addElement('header', 'config_ccn_item' . $i, get_string('config_item', 'theme_evagu') . $i);

            $mform->addElement('text', 'config_title' . $i, get_string('config_title', 'theme_evagu'));
            $mform->setDefault('config_title' . $i, 'Creative Events');
            $mform->setType('config_title' . $i, PARAM_TEXT);

            $mform->addElement('text', 'config_subtitle' . $i, get_string('config_number', 'theme_evagu'));
            $mform->setDefault('config_subtitle' . $i, '#');
            $mform->setType('config_subtitle' . $i, PARAM_TEXT);

            $mform->addElement('text', 'config_subtitle_2_' . $i, get_string('config_number_after', 'theme_evagu'));
            $mform->setDefault('config_subtitle_2_' . $i, '+');
            $mform->setType('config_subtitle_2_' . $i, PARAM_TEXT);
        }


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


        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/edit.php');
    }

    function set_data($defaults)
    {
        $field = 'image';
        $conffield = 'config_image';
        $draftitemid = file_get_submitted_draft_itemid($conffield);
        file_prepare_draft_area($draftitemid, $this->block->context->id, 'block_eva_featured_video', 'content', 0, array('subdirs' => false));
        $defaults->$conffield['itemid'] = $draftitemid;
        $this->block->config->$field = $draftitemid;

        //$entry->attachments = $draftitemid;
        parent::set_data($defaults);

        if ($data = parent::get_data()) {
            file_save_draft_area_files($data->config_image, $this->block->context->id, 'block_eva_featured_video', 'content', 0, array('subdirs' => true));
        }
        // END CCN Image Processing
    }
}