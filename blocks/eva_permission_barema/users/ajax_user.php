<?php
global $CFG, $DB, $PAGE, $USER;
/**
 * @author Celio Pereira Batalha
 * email: celio.batalha@gmail.com
 */
require_once ('../../../config.php');


$acao = ($_POST['acao'] ? $_POST['acao'] : $_GET['acao']);

switch ($acao) {
   case 'historicos':
      $id = $_GET['id'];

      $historicos = $DB->get_record('eva_barema_permissao', array('id'=>$id));

      $explod = explode(';', $historicos->logs);

      for ($i=0; $i <= count($explod); $i++) {
         if($explod[$i]){
            $logs[]['log'] = $explod[$i];
         }
      }
      echo json_encode($logs);
   break;
   default;
   echo 'Nao encontrou';
}