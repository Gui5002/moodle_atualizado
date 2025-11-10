<?php
class block_eva_abouteva_edit_form extends block_edit_form
{
    protected function specific_definition($mform)
    {
        global $CFG;
        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));
        // Title
        $mform->addElement('text', 'config_title', get_string('config_title', 'block_eva_abouteva'));
        $mform->setDefault('config_title', 'SOBRE A EVA');
        $mform->setType('config_title', PARAM_RAW);
        // Body
        $mform->addElement('editor', 'config_about_html', get_string('config_about_html', 'block_eva_abouteva'));
        $mform->setType('config_about_html', PARAM_RAW);
        // Image
        $maxbytes = 10485760;  // 10 MB
        $mform->addElement('filemanager', 'config_image', get_string('config_image', 'block_eva_abouteva'), null, [
            'subdirs' => 0,
            'maxbytes' => $maxbytes,
            'areamaxbytes' => $maxbytes,
            'maxfiles' => 1,
            'accepted_types' => ['.png', '.jpg', '.gif', '.jpeg']
        ]);
        // Section color
        $mform->addElement('text', 'config_color_background', get_string('config_color_background', 'block_eva_abouteva'), ['class' => 'ccn_spectrum_class']);
        $mform->setDefault('config_color_background', 'rgb(229, 229, 229)');
        $mform->setType('config_color_background', PARAM_TEXT);
        // Textarea background color
        $mform->addElement('text', 'config_color_text_background', get_string('config_color_text_background', 'block_eva_abouteva'), ['class' => 'ccn_spectrum_class']);
        $mform->setDefault('config_color_text_background', 'rgb(89, 97, 125)');
        $mform->setType('config_color_text_background', PARAM_TEXT);
        // Title color
        $mform->addElement('text', 'config_title_color', get_string('config_title_color', 'block_eva_abouteva'), ['class' => 'ccn_spectrum_class']);
        $mform->setDefault('config_title_color', 'rgb(255, 255, 255)');
        $mform->setType('config_title_color', PARAM_TEXT);
        // Text color
        $mform->addElement('text', 'config_text_color', get_string('config_text_color', 'block_eva_abouteva'), ['class' => 'ccn_spectrum_class']);
        $mform->setDefault('config_text_color', 'rgb(255, 255, 255)');
        $mform->setType('config_text_color', PARAM_TEXT);
        // Back rectangle color
        $mform->addElement('text', 'config_color_retangle', get_string('config_color_retangle', 'block_eva_abouteva'), ['class' => 'ccn_spectrum_class']);
        $mform->setDefault('config_color_retangle', 'rgb(185, 193, 204)');
        $mform->setType('config_color_retangle', PARAM_TEXT);
        include ($CFG->dirroot . '/theme/evagu/ccn/block_handler/edit.php');
    }

    function set_data($defaults)
    {
        $field = 'image';
        $conffield = 'config_image';
        $draftitemid = file_get_submitted_draft_itemid($conffield);
        // Prepare the draft area for image files
        file_prepare_draft_area($draftitemid, $this->block->context->id, 'block_eva_abouteva', 'content', 0, ['subdirs' => false]);
        $defaults->$conffield['itemid'] = $draftitemid;
        $this->block->config->$field = $draftitemid;
        parent::set_data($defaults);
        // Save draft area files if data exists
        if ($data = parent::get_data()) {
            file_save_draft_area_files($data->config_image, $this->block->context->id, 'block_eva_abouteva', 'content', 0, ['subdirs' => true]);
        }
        // Prepare the editor content
        $text = $this->block->config->about_html ?? '';
        $conffield = 'config_about_html';
        $draftid_editor = file_get_submitted_draft_itemid($conffield);
        $defaults->$conffield['text'] = file_prepare_draft_area(
            $draftid_editor,
            $this->block->context->id,
            'block_eva_abouteva',
            'content',
            0,
            ['subdirs' => false],
            $text
        );
        $defaults->$conffield['itemid'] = $draftid_editor;
        $defaults->$conffield['format'] = $this->block->config->format ?? FORMAT_HTML;
    }
}