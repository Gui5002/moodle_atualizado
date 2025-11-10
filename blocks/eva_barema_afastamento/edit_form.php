<?php

defined('MOODLE_INTERNAL') || die();

class block_eva_barema_afastamento_edit_form extends block_edit_form {

    private function exite_modelo_barema() {
        global $DB, $PAGE, $CFG, $USER;

        $array = explode('/', $PAGE->docspath);
        $baremadata = $DB->get_record('eva_afastamento_modelo', array('id'=>1));
        $config = $DB->get_record('block_instances', array('id'=>$baremadata->numero_barema));
        if ($array[2] === 'create' && $config->configdata) {
            return true;
        }else{
            return false;
        }
    }

    public function specific_definition($mform) {
        global $CFG, $PAGE, $DB;



        if ($this->exite_modelo_barema()){
            $mform->addElement('header', 'configheader', 'Configuração do Modelo de Afastamento');

            // Title
            $mform->addElement('text', 'config_title', 'Título do Modelo');
            $mform->setType('config_title', PARAM_RAW);
            $mform->addRule('config_title', 'O campo titulo barema está vazio', 'required', null, 'client');

            // Subtitle
            $mform->addElement('text', 'config_subtitle', 'Descrição do Modelo');
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
                9 => '9',
                10 => '10',
            );

            $mform->addElement('select', 'config_items', 'Itens', $items_range);
            $mform->setDefault('config_items', 1);

            $mform->addElement('static', 'quantidadeItemInfo', '', 'Os itens definem a quantidade de critérios a serem criados nesse modelo de Afastamento.');

            for($i = 1; $i <= count($items_range); $i++) {
                $mform->addElement('header', 'config_ccn_item' . $i , get_string('config_item', 'theme_evagu') . $i);

                $mform->addElement('text', 'config_notamaxima' . $i, get_string('config_notamaxima', 'block_eva_barema_afastamento'));
                //$mform->setDefault('config_title' .$i , 'Accordion' . $i);
                $mform->setType('config_notamaxima' . $i, PARAM_TEXT);
                $mform->addElement('static', 'maxnotainfo', '', get_string('infonotamax', 'block_eva_barema_afastamento'));

                $mform->addElement('text', 'config_criterio' . $i, get_string('config_criterio', 'block_eva_barema_afastamento'));
                //$mform->setDefault('config_title' .$i , 'Accordion' . $i);
                $mform->setType('config_criterio' . $i, PARAM_TEXT);
                $mform->addElement('static', 'criterioinfo', '', get_string('infocriterio', 'block_eva_barema_afastamento'));


                //            ======================Campos para o Radio button=============================

                $mform->addElement('static', 'radiobuttoninfo', '', get_string('inforadiobutton', 'block_eva_barema_afastamento'));

                $mform->addElement('text', 'config_labelbaixa' . $i, get_string('label_faixa', 'block_eva_barema_afastamento'));
                $mform->setType('config_labelbaixa' . $i, PARAM_TEXT);

                $mform->addElement('text', 'config_valorbaixa' . $i, get_string('value_faixa', 'block_eva_barema_afastamento'));
                $mform->setType('config_valorbaixa' . $i, PARAM_TEXT);

                $mform->addElement('text', 'config_labelmedia1' . $i, get_string('label_faixa', 'block_eva_barema_afastamento'));
                $mform->setType('config_labelmedia1' . $i, PARAM_TEXT);

                $mform->addElement('text', 'config_valormedia1' . $i, get_string('value_faixa', 'block_eva_barema_afastamento'));
                $mform->setType('config_valormedia1' . $i, PARAM_TEXT);

                $mform->addElement('text', 'config_labelmedia2' . $i, get_string('label_faixa', 'block_eva_barema_afastamento'));
                $mform->setType('config_labelmedia2' . $i, PARAM_TEXT);

                $mform->addElement('text', 'config_valormedia2' . $i, get_string('value_faixa', 'block_eva_barema_afastamento'));
                $mform->setType('config_valormedia2' . $i, PARAM_TEXT);

                $mform->addElement('text', 'config_labelmedia3' . $i, get_string('label_faixa', 'block_eva_barema_afastamento'));
                $mform->setType('config_labelmedia3' . $i, PARAM_TEXT);

                $mform->addElement('text', 'config_valormedia3' . $i, get_string('value_faixa', 'block_eva_barema_afastamento'));
                $mform->setType('config_valormedia3' . $i, PARAM_TEXT);

                $mform->addElement('text', 'config_labelalta' . $i, get_string('label_faixa', 'block_eva_barema_afastamento'));
                $mform->setType('config_labelalta' . $i, PARAM_TEXT);

                $mform->addElement('text', 'config_valoralta' . $i, get_string('value_faixa', 'block_eva_barema_afastamento'));
                $mform->setType('config_valoralta' . $i, PARAM_TEXT);
            }
        }



        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/edit.php');

    }

    function set_data($defaults)
    {
        $field = 'image';
        $conffield = 'config_image';
        $draftitemid = file_get_submitted_draft_itemid($conffield);
        file_prepare_draft_area($draftitemid, $this->block->context->id, 'block_eva_barema_afastamento', 'content', 0, array('subdirs'=>false));
        $defaults->$conffield['itemid'] = $draftitemid;
        $this->block->config->$field = $draftitemid;
        //var_dump($defaults);die();
        // Begin CCN Image Processing
        // if (empty($entry->id)) {
        //     $entry = new stdClass;
        //     $entry->id = null;
        // }
        // $draftitemid = file_get_submitted_draft_itemid('config_image');
        // file_prepare_draft_area($draftitemid, $this->block->context->id, 'block_eva_barema_afastamento', 'content', 0, array('subdirs' => true));
        // $entry->attachments = $draftitemid;
        parent::set_data($defaults);
        if ($data = parent::get_data()) {
            file_save_draft_area_files($data->config_image, $this->block->context->id, 'block_eva_barema_afastamento', 'content', 0,
                array('subdirs' => true));
        }
        // END CCN Image Processing

        if (!empty($this->block->config) && is_object($this->block->config)) {
            $text = $this->block->config->body;
            $draftid_editor = file_get_submitted_draft_itemid('config_body');
            if (empty($text)) {
                $currenttext = '';
            } else {
                $currenttext = $text;
            }
            $defaults->config_body['text'] = file_prepare_draft_area($draftid_editor, $this->block->context->id, 'block_eva_barema_afastamento', 'content', 0, array('subdirs'=>true), $currenttext);
            $defaults->config_body['itemid'] = $draftid_editor;
            $defaults->config_body['format'] = $this->block->config->format;
        } else {
            $text = '';
        }


    }



}
