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

class block_eva_video_pesquisa_edit_form extends block_edit_form {
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
        $mform->addElement('text', 'config_title', get_string('config_title', 'block_eva_video_pesquisa'));
        $mform->setDefault('config_title', 'Atualidades');
        $mform->setType('config_title', PARAM_RAW);

        // Subtitle
        $mform->addElement('text', 'config_subtitle', get_string('config_subtitle', 'block_eva_video_pesquisa'));
        $mform->setDefault('config_subtitle', 'Breve descritivo sobre essa página 3 linhas explicando o objetivo e destinação da educação continuada no EVA e como é administrada texto texto texto texto texto texto texto.');
        $mform->setType('config_subtitle', PARAM_RAW);

        // Slide category
        $mform->addElement('text', 'config_slide_category', get_string('config_slide_category', 'block_eva_video_pesquisa'));
        $mform->setDefault('config_slide_category', 'Defesa da probidade e recuperação de ativos');
        $mform->setType('config_slide_category', PARAM_RAW);

        $slidesrange = range(0, 20);
        $mform->addElement('select', 'config_slidesnumber', get_string('slides_number', 'block_eva_featured_posts'), $slidesrange);
        $mform->setDefault('config_slidesnumber', $data->slidesnumber);

        for($i = 1; $i <= $data->slidesnumber; $i++) {
            $mform->addElement('header', 'config_header' . $i , 'Slide ' . $i);

            $mform->addElement('text', 'config_slide_title' . $i, get_string('config_slide_title', 'block_eva_video_pesquisa', $i));
            $mform->setDefault('config_slide_title' .$i , 'Categoria do curso');
            $mform->setType('config_slide_title' . $i, PARAM_TEXT);

            $mform->addElement('text', 'config_slide_subtitle' . $i, get_string('config_slide_subtitle', 'block_eva_video_pesquisa', $i));
            $mform->setDefault('config_slide_subtitle' .$i , 'Nome do curso');
            $mform->setType('config_slide_subtitle' . $i, PARAM_TEXT);

            $mform->addElement('date_selector', 'config_slide_date' . $i, get_string('config_slide_date', 'block_eva_featured_posts', $i));

            $mform->addElement('url', 'config_slide_url' . $i, get_string('config_slide_url', 'block_eva_video_pesquisa', $i), array('size' => '60'), array('usefilepicker' => true));
            $mform->setDefault('config_slide_url' .$i , '');
            $mform->setType('config_slide_url' . $i, PARAM_URL);

            $filemanageroptions = array('maxbytes'      => $CFG->maxbytes,
                                        'subdirs'       => 0,
                                        'maxfiles'      => 1,
                                        'accepted_types' => array('.jpg', '.png', '.gif'));

            $f = $mform->addElement('filemanager', 'config_file_slide' . $i, get_string('config_file_slide', 'block_eva_video_pesquisa', $i), null, $filemanageroptions);
        }

        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/edit.php');

    }

    function set_data($defaults) {
        if (!empty($this->block->config) && is_object($this->block->config)) {

            for($i = 1; $i <= $this->block->config->slidesnumber; $i++) {
                $field = 'file_slide' . $i;
                $conffield = 'config_file_slide' . $i;
                $draftitemid = file_get_submitted_draft_itemid($conffield);
                file_prepare_draft_area($draftitemid, $this->block->context->id, 'block_eva_video_pesquisa', 'slides', $i, array('subdirs'=>false));
                $defaults->$conffield['itemid'] = $draftitemid;
                $this->block->config->$field = $draftitemid;
//        var_dump($defaults);die();
            }
        }

        parent::set_data($defaults);
    }
}
