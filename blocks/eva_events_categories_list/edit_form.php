<?php

class block_eva_events_categories_list_edit_form extends block_edit_form
{
    protected function specific_definition($mform)
    {
      global $CFG;
        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));

        // Title
        $mform->addElement('text', 'config_title', get_string('config_title', 'theme_evagu'));
        $mform->setDefault('config_title', 'Filtro de categorias');
        $mform->setType('config_title', PARAM_RAW);

        $options = array(
            '0' => 'Nunca',
            '1' => 'Ao visualizar a categoria pai',
            '2' => 'Sempre',
        );
        $select = $mform->addElement('select', 'config_child_categories', get_string('config_child_categories', 'theme_evagu'), $options);
        $select->setSelected('0');


        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/edit.php');

    }

}
