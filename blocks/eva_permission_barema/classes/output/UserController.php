<?php

namespace block_eva_permission_barema\output;


defined('MOODLE_INTERNAL') || die();

class UserController {

   var $dados;
   var $inputs;
   var $returnurl;

   function __construct($dados, $inputs, $returnurl)
   {
      $this->dados = $dados;
      $this->inputs = $inputs;
      $this->returnurl = $returnurl;
   }

   public function adicionar(){
      global $DB, $CFG, $USER;
      $usuarios = $DB->get_records_sql("SELECT id, fullname FROM vw_autocomplete_user WHERE id={$this->dados['user_id']}");
      foreach($usuarios as $user){
         $adicionar = array(
            'user_id' => $user->id,
            'name' => $user->fullname,
            'created_at' => date('Y-m-d H:m:s')
         );
      }
      $existe = $DB->record_exists('eva_barema_permissao', array('user_id'=>$this->dados['user_id']));
      if (!$existe) {
         $return = $DB->insert_record('eva_barema_permissao', $adicionar);
         if($return){
            $update = $DB->get_record('eva_barema_permissao', array('id'=>$return));
            $string = "$update->created_at - usuario $USER->id adicionou o usuario $update->user_id ;";
            $DB->update_record('eva_barema_permissao', array('id'=>$return, 'logs'=>$string));
         }
   
         $msg['message'] = '<h3 class="text-center" style="color: #0f5132">' . 'Usuario adicionado com  '.'<b>Sucesso!</b>' . '</h3>';
         $msg['info'] = \core\output\notification::NOTIFY_SUCCESS;
      }else{
         $msg['message'] = '<h3 class="text-center" style="color: #664d03">' . 'Usuario já  '.'<b>existe!</b>' . '</h3>';
         $msg['info'] = \core\output\notification::NOTIFY_WARNING;
      }

      if (isset( $msg['message'])) {
         \core\notification::add( $msg['message'],  $msg['info']);
         redirect( $this->returnurl);
         // We are already on the purge caches page, add the notification.
         return false;
     }
   }

   public function update() {
      global $DB, $CFG, $USER;

      
      if($this->inputs) {
         $msg = $this->update_check();
      };
      
      if($this->dados){
         $msg = $this->update_status();
      }
      
      
      if (isset( $msg['message'])) {
         \core\notification::add( $msg['message'],  $msg['info']);
         redirect( $this->returnurl);
         // We are already on the purge caches page, add the notification.
         return false;
     }

   }

   public function delete () {
      global $DB, $CFG, $USER;

      $dados = $DB->delete_records('eva_barema_permissao', array('id'=>$this->dados['id']));

      if ($dados) {
         $message = '<h3 class="text-center" style="color: #664d03">' . 'Usuario foi '.'<b>Removido!</b>' . '</h3>';
         $info = \core\output\notification::NOTIFY_WARNING;
      }else{
         $message = '<h3 class="text-center" style="color: #842029">' . 'Algo deu '.'<b>Errado!</b>' . '</h3>';
         $info = \core\output\notification::NOTIFY_ERROR;
      }

      if (isset($message)) {
         \core\notification::add($message, $info);
         redirect( $this->returnurl);
         // We are already on the purge caches page, add the notification.
         return false;
     }

   }

   public function update_check(){
      global $DB, $CFG, $USER;
      $now = date('Y-m-d H:m:s');
      foreach($this->inputs as $key=>$input){
         $dado = $DB->get_record('eva_barema_permissao', array('id'=>$key));
         $string = null;
         
         $posgraduacao = ($input['posgraduacao'] == "on") ? 1 : 0;
         if(($dado->posgraduacao != $posgraduacao) && $posgraduacao == 1){
            $string = "$now - Usuario $USER->id deu permissao de Posgraduacao para usuario $dado->user_id;";
         }else if(($dado->posgraduacao != $posgraduacao) && $posgraduacao == 0){
            $string = "$now - Usuario $USER->id Tirou a permissao de Posgraduacao do usuario $dado->user_id;";
         }

         $bolsa = ($input['bolsa'] == "on") ? 1 : 0;
         if(($dado->bolsa != $bolsa) && $bolsa == 1){
            $string .= "$now - Usuario $USER->id deu permissao de Bolsa para usuario $dado->user_id;";
         }else if(($dado->bolsa != $bolsa) && $bolsa == 0){
            $string .= "$now - Usuario $USER->id Tirou a permissao de Bolsa do usuario $dado->user_id;";
         }

         $afastamento = ($input['afastamento'] == "on") ? 1 : 0;
         if(($dado->afastamento != $afastamento) && $afastamento == 1){
            $string .= "$now - Usuario $USER->id deu permissao de Afastamento para usuario $dado->user_id;";
         }else if(($dado->afastamento != $afastamento) && $afastamento == 0){
            $string .= "$now - Usuario $USER->id Tirou a permissao de Afastamento do usuario $dado->user_id;";
         }

         $admin = ($input['admin'] == "on") ? 1 : 0;
         if(($dado->admin != $admin) && $admin == 1){
            $string .= "$now - Usuario $USER->id deu permissao de Admin para usuario $dado->user_id;";
         }else if(($dado->admin != $admin) && $admin == 0){
            $string .= "$now - Usuario $USER->id Tirou a permissao de Admin do usuario $dado->user_id;";
         }

         if($string){
            $string = "$dado->logs " . $string;
         }else{
            $string = $dado->logs;
         }
         $DB->update_record('eva_barema_permissao', array('id'=>$key, 'posgraduacao'=>$posgraduacao, 'bolsa'=>$bolsa, 'afastamento'=>$afastamento, 'admin'=>$admin, 'logs'=>$string));
      }

      $msg['message'] = '<h3 class="text-center" style="color: #0f5132">' . 'Alterações realizadas com  '.'<b>Sucesso!</b>' . '</h3>';
      $msg['info'] = \core\output\notification::NOTIFY_SUCCESS;

      return $msg;

   }

   public function update_status(){
      global $DB, $CFG, $USER;
      $now = date('Y-m-d H:m:s');
      $dado = $DB->get_record('eva_barema_permissao', array('id'=>$this->dados['id']));
      $status = ($this->dados['status'] == 0) ? 1 : 0;
      
      if(($dado->status != $status) && $status == 1){
         $string = "$now - Usuario $USER->id Ativou o usuario $dado->user_id;";
      }else if(($dado->status != $status) && $status == 0){
         $string = "$now - Usuario $USER->id Desativou o usuario $dado->user_id;";
      }
      if($string){
         $string = "$dado->logs " . $string;
      }else{
         $string = $dado->logs;
      }

      $dados = $DB->update_record('eva_barema_permissao', array('id'=>$this->dados['id'], 'posgraduacao'=>0, 'bolsa'=>0, 'afastamento'=>0, 'admin'=>0, 'logs'=>$string, 'status'=>$status));

      if ($status) {
         $msg['message'] = '<h3 class="text-center" style="color: #0f5132">' . 'Usuario foi '.'<b>Ativado!</b>' . '</h3>';
         $msg['info'] = \core\output\notification::NOTIFY_SUCCESS;
      }else{
         $msg['message'] = '<h3 class="text-center" style="color: #664d03">' . 'Usuario está '.'<b>Inativo!</b>' . '</h3>';
         $msg['info'] = \core\output\notification::NOTIFY_WARNING;
      }
      return $msg;

   }

}