<?php

class block_eva_course_details_edit_form extends block_edit_form
{
    protected function specific_definition($mform)
    {
      global $CFG;
      $ccnFontList = include ($CFG->dirroot . '/theme/evagu/ccn/font_handler/ccn_font_select.php');

      if (!empty($this->block->config) && is_object($this->block->config)) {
          $data = $this->block->config;
      } else {
          $data = new stdClass();
          $data->items = 0;
      }
        $courseid = optional_param('id', null, PARAM_INT);

      $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));

      $mform->addElement('hidden', 'courseid');
        $mform->setType('courseid', PARAM_INT);
        $mform->setDefault('courseid', $courseid);

        $mform->addElement('text', 'config_workload', get_string('config_workload', 'block_eva_course_details'));
        $mform->addHelpButton('config_workload', 'config_workload', 'block_eva_course_details');
        $mform->addRule('config_workload', 'HHH:MM use esse formato ou HH:MM', 'regex', '/^[0-9]{2,3}:[0-5][0-9]$/', 'client');
        $mform->setType('config_workload', PARAM_TEXT);

        $select = $mform->addElement('select', 'config_item_icon', get_string('config_icon_class', 'theme_evagu'), $ccnFontList, array('class'=>'ccn_icon_class'));
        $select->setSelected('flaticon-account');
      $itemsrange = range(0, 12);
      $mform->addElement('select', 'config_items', get_string('config_items', 'block_eva_course_details'), $itemsrange);
      $mform->setDefault('config_items', $data->items);

        for($i = 1; $i <= $data->items; $i++) {

          $mform->addElement('header', 'config_header' . $i , 'Item ' . $i);

          $mform->addElement('text', 'config_item_title' . $i, get_string('config_item_title', 'block_eva_course_details', $i));
          $mform->setDefault('config_item_title' .$i , '11 hours on-demand video');
          $mform->setType('config_item_title' . $i, PARAM_TEXT);
          $select = $mform->addElement('select', 'config_item_icon' . $i, get_string('config_icon_class', 'theme_evagu'), $ccnFontList, array('class'=>'ccn_icon_class'));
          $select->setSelected('flaticon-account');

       }

     include($CFG->dirroot . '/theme/evagu/ccn/block_handler/edit.php'); }
    function validation($data, $files) {
      global $CFG,$DB,$USER;

      $errors = array();

      if($data['courseid'] == 0){
          $errors['err_id'] = 'ID do curso está errado';
      }

      if($USER->id == 0){
          $errors['err_userid'] = 'ID do usuário está errado';
      }

      if($data['config_workload'] == ""){
          $errors['err_workload'] = 'Nenhum dado de Carga horária foi passado';
      }

      $exp = explode(':',$data['config_workload']);
      $hr = $exp[0].' hrs ';
      $min = $exp[1].' min';
      $carga = $hr.$min;
      $hr_int = intval($exp[0]);
      $hr_int = $hr_int * 60;
      $min_int = intval($exp[1]);
      $carga_min = $hr_int + $min_int;

      if(count($errors) > 0){
          echo 'Erros encontrados:';
          foreach($errors as $erro){
              echo '<br>-'.$erro;
          }
          die;
      }else{
          $sql = 'SELECT id FROM {eva_course_workload} WHERE courseid = ?';
          $verify = $DB->get_record_sql($sql, array($data['courseid']));

          $currenttime = time();

          if($verify == false){
              $insertdata = (object)[
                  'courseid' => $data['courseid'],
                  'workload' => $carga,
                  'usermodified' => $USER->id,
                  'timecreated' => $currenttime,
                  'tempoemmin' => $carga_min
              ];

              $i = $DB->insert_record('eva_course_workload', $insertdata);

              if($i){
                  return true;
              }else{
                  $errors['err_insert'] = 'Não foi possível inserir os dados';

                  echo 'Erros encontrados:';
                  foreach($errors as $erro){
                      echo '<br>-'.$erro;
                  }
                  die;
              }
          }else{
              $id = json_decode(json_encode($verify,JSON_UNESCAPED_UNICODE),true);
              $id = $id['id'];

              $updatedata = (object)[
                  'id' => $id,
                  'courseid' => $data['courseid'],
                  'workload' => $carga,
                  'usermodified' => $USER->id,
                  'timemodified' => $currenttime,
                  'tempoemmin' => $carga_min
              ];

              $u = $DB->update_record('eva_course_workload', $updatedata);

              if($u){
                  return true;
              }else{
                  $errors['err_update'] = 'Não foi possível atualizar os dados';

                  echo 'Erros encontrados:';
                  foreach($errors as $erro){
                      echo '<br>-'.$erro;
                  }
                  die;
              }
          }
      }
  }
}
