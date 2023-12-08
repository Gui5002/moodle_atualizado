<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

class block_eva_publicidade_edit_form extends block_edit_form {
    protected function specific_definition($mform) {
        global $CFG;

        if (!empty($this->block->config) && is_object($this->block->config)) {
            $data = $this->block->config;
        } else {
            $data = new stdClass();
            $data->slidesnumber = 4;
        }

        $searchareas = \core_search\manager::get_search_areas_list(true);
        $areanames = array();
        foreach ($searchareas as $areaid => $searcharea) {
            $areanames[$areaid] = $searcharea->get_visible_name();
        }

        $bloglisting = new blog_listing();

        $entries = $bloglisting->get_entries();
        $entrieslist = array();

        foreach ($entries as $entryid => $entry) {
          $entrieslist[$entry->id] = $entry->subject;
        }

        // Fields for editing HTML block title and contents.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        // Title
        $mform->addElement('text', 'config_title', get_string('config_title', 'block_eva_publicidade'));
        $mform->setDefault('config_title', 'CICLO DE ATUALIZAÇÃO EM');
        $mform->setType('config_title', PARAM_RAW);

        // Title 2
        $mform->addElement('text', 'config_title2', get_string('config_title2', 'block_eva_publicidade'));
        $mform->setDefault('config_title2', 'PROCESSO DE PÓS-GRADUAÇÃO NOME');
        $mform->setType('config_title2', PARAM_RAW);

        // Subtitle
        $mform->addElement('text', 'config_subtitle', get_string('config_subtitle', 'block_eva_publicidade'));
        $mform->setDefault('config_subtitle', 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore aecat cupidatat non proident');
        $mform->setType('config_subtitle', PARAM_RAW);

        // Aula ID
        $mform->addElement('text', 'config_aula_id', get_string('config_aula_id', 'block_eva_publicidade'));
        $mform->setDefault('config_aula_id', 'AULA 29 TITULO 04');
        $mform->setType('config_aula_id', PARAM_RAW);

        // Palestrantes
        $mform->addElement('text', 'config_palestrante', get_string('config_palestrante', 'block_eva_publicidade'));
        $mform->setDefault('config_palestrante', 'PALESTRANTES');
        $mform->setType('config_palestrante', PARAM_RAW);

        // Data
        $mform->addElement('date_selector', 'config_date_select', get_string('config_date_select','block_eva_publicidade'));
        
        // Range de tempo
        $mform->addElement('text', 'config_hour_range', get_string('config_hour_range', 'block_eva_publicidade'));
        $mform->setDefault('config_hour_range', '09h às 12h');
        $mform->setType('config_hour_range', PARAM_RAW);

        // Público 1
        $mform->addElement('text', 'config_target_audience1', get_string('config_target_audience1', 'block_eva_publicidade'));
        $mform->setDefault('config_target_audience1', 'Público 01');
        $mform->setType('config_target_audience1', PARAM_RAW);

        // Público 2
        $mform->addElement('text', 'config_target_audience2', get_string('config_target_audience2', 'block_eva_publicidade'));
        $mform->setDefault('config_target_audience2', 'Público 02');
        $mform->setType('config_target_audience2', PARAM_RAW);

        // Público 3
        $mform->addElement('text', 'config_target_audience3', get_string('config_target_audience3', 'block_eva_publicidade'));
        $mform->setDefault('config_target_audience3', 'Público 03');
        $mform->setType('config_target_audience3', PARAM_RAW);

        // Público 4
        $mform->addElement('text', 'config_target_audience4', get_string('config_target_audience4', 'block_eva_publicidade'));
        $mform->setDefault('config_target_audience4', 'Público 04');
        $mform->setType('config_target_audience4', PARAM_RAW);

        // Transmissão linha 1
        $mform->addElement('text', 'config_transmissao_line1', get_string('config_transmissao_line1', 'block_eva_publicidade'));
        $mform->setDefault('config_transmissao_line1', 'Transmissão Microsoft Teams');
        $mform->setType('config_transmissao_line1', PARAM_RAW);

        // Transmissão link 1
        $mform->addElement('text', 'config_transmissao_link1', get_string('config_transmissao_link1', 'block_eva_publicidade'));
        $mform->setDefault('config_transmissao_link1', '#');
        $mform->setType('config_transmissao_link1', PARAM_RAW);

        // Transmissão linha 2
        $mform->addElement('text', 'config_transmissao_line2', get_string('config_transmissao_line2', 'block_eva_publicidade'));
        $mform->setDefault('config_transmissao_line2', 'Acesso Intranet AGU');
        $mform->setType('config_transmissao_line2', PARAM_RAW);

        // Transmissão link 2
        $mform->addElement('text', 'config_transmissao_link2', get_string('config_transmissao_link2', 'block_eva_publicidade'));
        $mform->setDefault('config_transmissao_link2', '#');
        $mform->setType('config_transmissao_link2', PARAM_RAW);

        // Transmissão linha 3
        $mform->addElement('text', 'config_transmissao_line3', get_string('config_transmissao_line3', 'block_eva_publicidade'));
        $mform->setDefault('config_transmissao_line3', 'www.evaagu.gov.br/eventoXXXX');
        $mform->setType('config_transmissao_line3', PARAM_RAW);

        // Transmissão link 3
        $mform->addElement('text', 'config_transmissao_link3', get_string('config_transmissao_link3', 'block_eva_publicidade'));
        $mform->setDefault('config_transmissao_link3', '#');
        $mform->setType('config_transmissao_link3', PARAM_RAW);

        $filemanageroptions = array('maxbytes'      => $CFG->maxbytes,
                                        'subdirs'       => 0,
                                        'maxfiles'      => 1,
                                        'accepted_types' => array('.jpg', '.png', '.gif'));

        $f = $mform->addElement('filemanager', 'config_file_slide_transmition', get_string('config_transmissao_image', 'block_eva_publicidade'), null, $filemanageroptions);

        // Facebook
        $mform->addElement('text', 'config_facebook_url', get_string('config_facebook_url', 'block_eva_publicidade'));
        $mform->setDefault('config_facebook_url', '#');
        $mform->setType('config_facebook_url', PARAM_RAW);

        // Tweeter
        $mform->addElement('text', 'config_tweeter_url', get_string('config_tweeter_url', 'block_eva_publicidade'));
        $mform->setDefault('config_tweeter_url', '#');
        $mform->setType('config_tweeter_url', PARAM_RAW);

        // Instagram
        $mform->addElement('text', 'config_instagram_url', get_string('config_instagram_url', 'block_eva_publicidade'));
        $mform->setDefault('config_instagram_url', '#');
        $mform->setType('config_instagram_url', PARAM_RAW);

        // Youtube
        $mform->addElement('text', 'config_youtube_url', get_string('config_youtube_url', 'block_eva_publicidade'));
        $mform->setDefault('config_youtube_url', '#');
        $mform->setType('config_youtube_url', PARAM_RAW);

        // Range de palestrantes
        $slidesrange = range(0, 8);
        $mform->addElement('select', 'config_slidesnumber', get_string('config_slidesnumber', 'block_eva_publicidade'), $slidesrange);
        $mform->setDefault('config_slidesnumber', $data->slidesnumber);

        // Range de realização
        $slidesrangeRealizacao = range(0, 2);
        $mform->addElement('select', 'config_slidesnumber_realizacao', get_string('config_slidesnumber_realizacao', 'block_eva_publicidade'), $slidesrangeRealizacao);
        $mform->setDefault('config_slidesnumber_realizacao', $data->slidesnumber_realizacao);

        // Range de apoio
        $slidesrangeApoio = range(0, 8);
        $mform->addElement('select', 'config_slidesnumber_apoio', get_string('config_slidesnumber_apoio', 'block_eva_publicidade'), $slidesrangeApoio);
        $mform->setDefault('config_slidesnumber_apoio', $data->slidesnumber_apoio);

        // Loop de palestrantes
        for($i = 1; $i <= $data->slidesnumber; $i++) {
            $mform->addElement('header', 'config_header' . $i , 'Slide ' . $i);

            $mform->addElement('text', 'config_palestrante_nome' . $i, get_string('config_palestrante_nome', 'block_eva_publicidade', $i));
            $mform->setDefault('config_palestrante_nome' .$i , 'FULANO DA SILVA');
            $mform->setType('config_palestrante_nome' . $i, PARAM_TEXT);

            $mform->addElement('text', 'config_palestrante_profissao' . $i, get_string('config_palestrante_profissao', 'block_eva_publicidade', $i));
            $mform->setDefault('config_palestrante_profissao' .$i , 'Creative Leader');
            $mform->setType('config_palestrante_profissao' . $i, PARAM_TEXT);

            $mform->addElement('text', 'config_palestrante_texto' . $i, get_string('config_palestrante_texto', 'block_eva_publicidade', $i));
            $mform->setDefault('config_palestrante_texto' .$i , 'Adipiscing elit, sed do eiusmod tempor incididunt ut labor');
            $mform->setType('config_palestrante_texto' . $i, PARAM_TEXT);

            $filemanageroptions = array('maxbytes'      => $CFG->maxbytes,
                                        'subdirs'       => 0,
                                        'maxfiles'      => 1,
                                        'accepted_types' => array('.jpg', '.png', '.gif'));

            $f = $mform->addElement('filemanager', 'config_file_slide' . $i, get_string('config_file_slide', 'block_eva_publicidade', $i), null, $filemanageroptions);
        }

        // Loop de realização
        for($i = 1; $i <= $data->slidesnumber_realizacao; $i++) {
            $mform->addElement('header', 'config_header_realizacao' . $i , 'Realizacao ' . $i);

            $filemanageroptions = array('maxbytes'      => $CFG->maxbytes,
                                        'subdirs'       => 0,
                                        'maxfiles'      => 1,
                                        'accepted_types' => array('.jpg', '.png', '.gif'));

            $f = $mform->addElement('filemanager', 'config_file_slide_realizacao' . $i, get_string('config_file_slide_realizacao', 'block_eva_publicidade', $i), null, $filemanageroptions);
        }

        // Loop de apoio
        for($i = 1; $i <= $data->slidesnumber_apoio; $i++) {
            $mform->addElement('header', 'config_header_apoio' . $i , 'Apoio ' . $i);

            $filemanageroptions = array('maxbytes'      => $CFG->maxbytes,
                                        'subdirs'       => 0,
                                        'maxfiles'      => 1,
                                        'accepted_types' => array('.jpg', '.png', '.gif'));

            $f = $mform->addElement('filemanager', 'config_file_slide_apoio' . $i, get_string('config_file_slide_apoio', 'block_eva_publicidade', $i), null, $filemanageroptions);
        }

        // Color header
        $mform->addElement('header', 'config_ccn_colors', get_string('block_styles', 'block_eva_publicidade'));
        
        // First color
        $mform->addElement('text', 'config_first_color', get_string('config_first_color', 'block_eva_publicidade'), array('class' => 'ccn_spectrum_class'));
        $mform->setDefault('config_first_color', '#ffffff');
        $mform->setType('config_first_color', PARAM_TEXT);

        // Second color
        $mform->addElement('text', 'config_second_color', get_string('config_second_color', 'block_eva_publicidade'), array('class' => 'ccn_spectrum_class'));
        $mform->setDefault('config_second_color', '#478ac9');
        $mform->setType('config_second_color', PARAM_TEXT);

        // Third color
        $mform->addElement('text', 'config_third_color', get_string('config_third_color', 'block_eva_publicidade'), array('class' => 'ccn_spectrum_class'));
        $mform->setDefault('config_third_color', '#c3d0ea');
        $mform->setType('config_third_color', PARAM_TEXT);

        // BG color
        $mform->addElement('text', 'config_bg_color', get_string('config_bg_color', 'block_eva_publicidade'), array('class' => 'ccn_spectrum_class'));
        $mform->setDefault('config_bg_color', '#2c3847');
        $mform->setType('config_bg_color', PARAM_TEXT);
        
        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/edit.php');
    }

    function set_data($defaults) {
        if (!empty($this->block->config) && is_object($this->block->config)) {
            $field = 'file_slide_transmition';
            $conffield = 'config_file_slide_transmition';
            $draftitemid = file_get_submitted_draft_itemid($conffield);
            file_prepare_draft_area($draftitemid, $this->block->context->id, 'block_eva_publicidade', 'slide_transmition', 1, array('subdirs'=>false));
            $defaults->$conffield['itemid'] = $draftitemid;
            $this->block->config->$field = $draftitemid;
            
            if($data = parent::get_data()){
                file_save_draft_area_files($data->$conffield,  $this->block->context->id,  'block_eva_publicidade',  'slide_transmition',  1, array('subdirs' => false));
            }

            for($i = 1; $i <= $this->block->config->slidesnumber; $i++) {
                $field = 'file_slide' . $i;
                $conffield = 'config_file_slide' . $i;
                $draftitemid = file_get_submitted_draft_itemid($conffield);
                file_prepare_draft_area($draftitemid, $this->block->context->id, 'block_eva_publicidade', 'slides', $i, array('subdirs'=>false));
                $defaults->$conffield['itemid'] = $draftitemid;
                $this->block->config->$field = $draftitemid;
                
                if($data = parent::get_data()){
                    file_save_draft_area_files($data->$conffield,  $this->block->context->id,  'block_eva_publicidade',  'slides',  $i, array('subdirs' => false));
                }
            }

            for($i = 1; $i <= $this->block->config->slidesnumber_realizacao; $i++) {
                $field = 'file_slide_realizacao' . $i;
                $conffield = 'config_file_slide_realizacao' . $i;
                $draftitemid = file_get_submitted_draft_itemid($conffield);
                file_prepare_draft_area($draftitemid, $this->block->context->id, 'block_eva_publicidade', 'slides_realizacao', $i, array('subdirs'=>false));
                $defaults->$conffield['itemid'] = $draftitemid;
                $this->block->config->$field = $draftitemid;
                
                if($data = parent::get_data()){
                    file_save_draft_area_files($data->$conffield,  $this->block->context->id,  'block_eva_publicidade',  'slides_realizacao',  $i, array('subdirs' => false));
                }
            }

            for($i = 1; $i <= $this->block->config->slidesnumber_apoio; $i++) {
                $field = 'file_slide_apoio' . $i;
                $conffield = 'config_file_slide_apoio' . $i;
                $draftitemid = file_get_submitted_draft_itemid($conffield);
                file_prepare_draft_area($draftitemid, $this->block->context->id, 'block_eva_publicidade', 'slides_apoio', $i, array('subdirs'=>false));
                $defaults->$conffield['itemid'] = $draftitemid;
                $this->block->config->$field = $draftitemid;
                
                if($data = parent::get_data()){
                    file_save_draft_area_files($data->$conffield,  $this->block->context->id,  'block_eva_publicidade',  'slides_apoio',  $i, array('subdirs' => false));
                }
            }
        }

        parent::set_data($defaults);
    }
}
