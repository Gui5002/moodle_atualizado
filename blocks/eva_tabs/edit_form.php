<?php
class block_eva_tabs_edit_form extends block_edit_form {
    protected function specific_definition($mform) {
        global $CFG;
        if (!empty($this->block->config) && is_object($this->block->config)) {
            $data = $this->block->config;
        } else {
            $data = new stdClass();
            $data->slidesnumber = 3;
        }
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));
        $mform->addElement('text', 'config_title', get_string('config_title', 'theme_evagu'));
        $mform->setDefault('config_title', 'Tabs');
        $mform->setType('config_title', PARAM_RAW);
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
        );
        $ccnItemsMax = 10;
        $mform->addElement('select', 'config_slidesnumber', get_string('config_items', 'theme_evagu'), $ccnItemsRange);
        $mform->setDefault('config_slidesnumber', 3);
        for($i = 1; $i <= $ccnItemsMax; $i++) {
            $mform->addElement('header', 'config_ccn_item' . $i , get_string('item', 'theme_evagu') . $i);
            $mform->addElement('text', 'config_title' . $i, get_string('config_title', 'theme_evagu'));
            $mform->setDefault('config_title' .$i , 'Tab' . $i);
            $mform->setType('config_title' . $i, PARAM_TEXT);
            $mform->addElement('editor', 'config_text'.$i, get_string('config_body', 'theme_evagu'));
            $mform->setType('config_text'.$i, PARAM_RAW);
        }
        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/edit.php');
    }
    function set_data($defaults) {
        if (!empty($this->block->config) && is_object($this->block->config)) {
            for($i = 1; $i <= $this->block->config->slidesnumber; $i++) {
                $field = 'file_slide' . $i;
                $conffield = 'config_file_slide' . $i;
                $draftitemid = file_get_submitted_draft_itemid($conffield);
                file_prepare_draft_area($draftitemid, $this->block->context->id, 'block_eva_tabs', 'slides', $i, array('subdirs'=>false));
                $defaults->$conffield['itemid'] = $draftitemid;
                $this->block->config->$field = $draftitemid;
                    $text = $this->block->config->faq_html . $i;
                    $conffield = 'config_body' . $i;
                    $draftid_editor = file_get_submitted_draft_itemid($conffield);
                    if (empty($text)) {
                        $currenttext = '';
                    } else {
                        $currenttext = $text;
                    }
                    $defaults->$conffield['text'] = file_prepare_draft_area($draftid_editor, $this->block->context->id, 'block_eva_tabs', 'content', $i, array('subdirs'=>false), $currenttext);
                    $defaults->$conffield['itemid'] = $draftid_editor;
                    $defaults->$conffield['format'] = $this->block->config->format . $i ;
            }
        }
        parent::set_data($defaults);
    }
}
