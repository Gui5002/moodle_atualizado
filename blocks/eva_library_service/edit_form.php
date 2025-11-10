<?php

defined('MOODLE_INTERNAL') || die();

class block_eva_library_service_edit_form extends block_edit_form {

    public function specific_definition($mform) {
        global $CFG, $PAGE;

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
        $mform->setDefault('config_title', 'Titulo do Bloco');
        $mform->setType('config_title', PARAM_RAW);

        $items_range = array(
            1 => '1',
            2 => '2',
            3 => '3',
            4 => '4',
            5 => '5',
            6 => '6',
        );

        $mform->addElement('select', 'config_items', get_string('config_items', 'theme_evagu'), $items_range);
        $mform->setDefault('config_items', 4);

        for ($i = 1; $i <= count($items_range); $i++) {
            $mform->addElement('header', 'config_ccn_item' . $i , 'Tabs ' . $i);

            $mform->addElement('text', 'config_tabs_title' . $i, get_string('config_title', 'theme_evagu'));
            $mform->setDefault('config_tabs_title' .$i , 'Nome Tabs' . $i);
            $mform->setType('config_tabs_title' . $i, PARAM_TEXT);

            $filemanageroptions = array('maxbytes'      => $CFG->maxbytes,
                'subdirs'       => 0,
                'maxfiles'      => 1,
                'accepted_types' => array('.jpg', '.png', '.gif'));
            $f = $mform->addElement('filemanager', 'config_tabs_image' . $i, get_string('config_image', 'theme_evagu', $i), null, $filemanageroptions);

            $mform->addElement('editor', 'config_body'. $i, get_string('config_body', 'theme_evagu', $i));
            $mform->setType('config_body'.$i, PARAM_RAW);


            $mform->addElement('text', 'config_bt_title1' . $i, get_string('config_title', 'theme_evagu', $i));
            $mform->setType('config_bt_title1' . $i, PARAM_TEXT);

            $mform->addElement('text', 'config_bt_link1' . $i, get_string('config_link', 'theme_evagu', $i));
            $mform->setDefault('config_bt_link1' . $i, '#');
            $mform->setType('config_bt_link1' . $i, PARAM_TEXT);

            $mform->addElement('text', 'config_tooltip1' . $i, get_string('config_tooltip', 'theme_evagu', $i));
            $mform->setType('config_tooltip1' . $i, PARAM_TEXT);


            $mform->addElement('text', 'config_bt_title2'.$i, get_string('config_title', 'theme_evagu', $i));
//            $mform->setDefault('config_bt_title2'.$i, 'Create Account');
            $mform->setType('config_bt_title2'.$i, PARAM_TEXT);

            $mform->addElement('text', 'config_bt_link2'.$i, get_string('config_link', 'theme_evagu', $i));
            $mform->setDefault('config_bt_link2'.$i, '#');
            $mform->setType('config_bt_link2'.$i, PARAM_TEXT);

            $mform->addElement('text', 'config_tooltip2' . $i, get_string('config_tooltip', 'theme_evagu', $i));
            $mform->setType('config_tooltip2' . $i, PARAM_TEXT);


            $mform->addElement('text', 'config_bt_title3' . $i, get_string('config_title', 'theme_evagu', $i));
//            $mform->setDefault('config_bt_title3' . $i, 'Create Account');
            $mform->setType('config_bt_title3' . $i, PARAM_TEXT);

            $mform->addElement('text', 'config_bt_link3' . $i, get_string('config_link', 'theme_evagu', $i));
            $mform->setDefault('config_bt_link3' . $i, '#');
            $mform->setType('config_bt_link3' . $i, PARAM_TEXT);

            $mform->addElement('text', 'config_tooltip3' . $i, get_string('config_tooltip', 'theme_evagu', $i));
            $mform->setType('config_tooltip3' . $i, PARAM_TEXT);


            $mform->addElement('text', 'config_bt_title4' . $i, get_string('config_title', 'theme_evagu', $i));
            $mform->setType('config_bt_title4' . $i, PARAM_TEXT);

            $mform->addElement('text', 'config_bt_link4' . $i, get_string('config_link', 'theme_evagu', $i));
            $mform->setDefault('config_bt_link4' . $i, '#');
            $mform->setType('config_bt_link4' . $i, PARAM_TEXT);

            $mform->addElement('text', 'config_tooltip4' . $i, get_string('config_tooltip', 'theme_evagu', $i));
            $mform->setType('config_tooltip4' . $i, PARAM_TEXT);


            $mform->addElement('text', 'config_bt_title5' . $i, get_string('config_title', 'theme_evagu', $i));
//            $mform->setDefault('config_bt_title5' . $i, 'Create Account');
            $mform->setType('config_bt_title5' . $i, PARAM_TEXT);

            $mform->addElement('text', 'config_bt_link5' . $i, get_string('config_link', 'theme_evagu', $i));
            $mform->setDefault('config_bt_link5' . $i, '#');
            $mform->setType('config_bt_link5' . $i, PARAM_TEXT);

            $mform->addElement('text', 'config_tooltip5' . $i, get_string('config_tooltip', 'theme_evagu', $i));
            $mform->setType('config_tooltip5' . $i, PARAM_TEXT);

            $options = array(
                '_self' => 'Self (open in same window)',
                '_blank' => 'Blank (open in new window)',
                '_parent' => 'Parent (open in parent frame)',
                '_top' => 'Top (open in full body of the window)',
            );
            $select = $mform->addElement('select', 'config_link_target'.$i, get_string('config_button_target', 'theme_evagu'), $options);
            $select->setSelected('_self');
        }

        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/edit.php');

    }

    function set_data($defaults)
    {
        if (!empty($this->block->config) && is_object($this->block->config)) {

            for($i = 1; $i <= $this->block->config->items; $i++) {
                $field = 'tabs_image' . $i;
                $conffield = 'config_tabs_image'.$i;
                $draftitemid = file_get_submitted_draft_itemid($conffield);
                file_prepare_draft_area($draftitemid, $this->block->context->id, 'block_eva_library_service', 'content', $i, array('subdirs' => true));
                $defaults->$conffield['itemid'] = $draftitemid;
                $this->block->config->$field = $draftitemid;
                if ($data = parent::get_data()) {
                    file_save_draft_area_files($data->$conffield, $this->block->context->id, 'block_eva_library_service', 'content', $i, array('subdirs' => true));
                }
            }
            // END CCN tabs_image Processing

            $text = $this->block->config->body;
            $draftid_editor = file_get_submitted_draft_itemid('config_body');
            if (empty($text)) {
                $currenttext = '';
            } else {
                $currenttext = $text;
            }
            $defaults->config_body['text'] = file_prepare_draft_area($draftid_editor, $this->block->context->id, 'block_eva_library_service', 'content', 0, array('subdirs'=>true), $currenttext);
            $defaults->config_body['itemid'] = $draftid_editor;
            $defaults->config_body['format'] = $this->block->config->format;
        } else {
            $text = '';
        }
        parent::set_data($defaults);




    }
}