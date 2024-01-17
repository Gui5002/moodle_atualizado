<?php

function barema_avaliador_create($avaliadores, $baremaCurso){
    global $DB, $CFG;

    $old_barema = $DB->get_record('eva_barema', array('id'=>$baremaCurso->tb_barema_id));
    $arrayavaliador = array();
    foreach ($avaliadores as $dadosava) {
        $url_barema = $CFG->wwwroot . '/blocks/eva_form_barema/barema_avaliacao.php?barema_id='.$baremaCurso->tb_barema_id.'&avaliador_id='.$dadosava.'&curso_id='.$baremaCurso->tb_curso_id.'&quiz_id='.$baremaCurso->tb_atividade_id;
        $arrayavaliador[] = array(
            'tb_barema_id'            => $baremaCurso->tb_barema_id,
            'avaliador_tb_user_id'    => $dadosava,
            'tb_categoria_id'         => $baremaCurso->tb_categoria_id,
            'tb_subcategoria_id'      => $baremaCurso->tb_subcategoria_id,
            'tb_curso_id'             => $baremaCurso->tb_curso_id,
            'tb_atividade_id'         => $baremaCurso->tb_atividade_id,
            'barema_modelo'           => $old_barema->hash_barema,
            'url_avaliacao'           => $url_barema,
            'resposta_padrao'         => $baremaCurso->resposta_padrao,
        );


        set_envio_email_avaliadores($dadosava, $baremaCurso, $url_barema);
    }
    $DB->insert_records('eva_barema_avaliador', $arrayavaliador);


    $sqljoin = "SELECT id FROM mdl_eva_barema_resposta_padrao WHERE tb_quiz_id = '{$baremaCurso->tb_atividade_id}'";
    $existe = $DB->record_exists_sql($sqljoin);
    $arrayresposta = array(
        'tb_quiz_id'    => $baremaCurso->tb_atividade_id,
        'resposta'    => $baremaCurso->resposta_padrao,
    );

    if (!$existe) {
        $DB->insert_record('eva_barema_resposta_padrao', $arrayresposta);
    }else{
        $id = $DB->get_field_sql($sqljoin);
        $DB->update_record('eva_barema_resposta_padrao', array('id'=>$id, 'resposta'=>$baremaCurso->resposta_padrao));
    }

    $avaliador = 1;
    return $avaliador;
}

function set_envio_email_avaliadores($idavaliadores, $baremaCurso,  $url_barema) {
    global $DB, $USER;
//    $solicitacao->cc = '';
    $name = '';


    $sql = "SELECT fullname, email FROM vw_autocomplete_user WHERE id ='{$idavaliadores}'";
    $avaliador = $DB->get_record_sql($sql);

    $barema = $DB->get_record('eva_barema', array('id'=>$baremaCurso->tb_barema_id));
    $curso = $DB->get_field('course', 'fullname', array('id'=>$baremaCurso->tb_curso_id));
    $atividade = $DB->get_field('quiz', 'name', array('id'=>$baremaCurso->tb_atividade_id));

    $contact = new pos_contact();
//        unset($_POST['id_user'], $_POST['created'], $_POST['mform_isexpanded_id_createuserandpass'], $_POST['submitbutton'], $_POST['Cc']);
    unset($_POST);

    $_POST['barema'] = $barema->nome_modelo;
    $_POST['avaliador'] = $avaliador->fullname;
    $_POST['curso'] = $curso;
    $_POST['atividade'] = $atividade;
    $_POST['message'] = 'Click no link abaixo para Iniciar o Barema de avaliações dos alunos';
    $_POST['link'] = $url_barema;

    $envio = $contact->sendmessage($avaliador->email, $name, null , null);
    $mensagem[] = $envio;
//        if () {
//            // Share a gratitude and Say Thank You! Your user will love to know their message was sent.
//            $message = '<h5 class="text-center">' . get_string('msgsolicitacao', 'block_eva_form_library') . '</h5>';
//        } else {
//            // Oh no! What are the chances. Looks like we failed to meet user expectations (message not sent).
//            $message = '<h5 class="text-center">'.get_string('errorsendingtitle', 'local_contact').'</h5>';
//        }
}

function barema_avaliacao_create($data) {
    global $DB;
    $avaliacao = (object) $data;

//    var_dump($avaliacao);die();
    $tb_avaliador = $DB->get_record('eva_barema_avaliador', array('tb_barema_id'=>$avaliacao->tb_barema_id, 'avaliador_tb_user_id'=>$avaliacao->avaliador_tb_user_id));
    $avaliacao->tb_avaliador_id = $tb_avaliador->id;
    $avaliacao->data_avaliacao = date('Y-m-d h:i:sa');
    $avaliacao->status = 1;

    if ($avaliacao->aluno_tb_user_id){
        $sql = "SELECT id FROM mdl_eva_barema_avaliacao WHERE tb_avaliador_id = '{$tb_avaliador->id}' AND aluno_tb_user_id = '{$avaliacao->aluno_tb_user_id}'";
        $existe = $DB->record_exists_sql($sql);
        if (!$existe) {
            $dados = $DB->insert_record('eva_barema_avaliacao', $avaliacao);
            return $dados;
        }else{
            $msg['error'] = 'Aluno já foi avaliado!';
            return $msg;
        }
    }else{
        $msg['error'] = 'Aluno não selecionado!';
        return $msg;
    }
}


function block_instance_barema($instance) {

    global $CFG, $DB, $USER;
    $instbarema  = new stdClass;
    $data = date("Y-m-d h:i:sa");

    $instbarema->numero_barema = $instance->id;
    $instbarema->hash_barema = $instance->configdata;

    if (!$configdata = $DB->get_record('eva_barema', array('id'=>1))) {

        //===========Insere um Modelo Barema em "BRANCO" ==========
        $DB->insert_record('eva_barema', $instbarema);
    }else if(!hash_equals($configdata->hash_barema, $instance->configdata)) {
        $config = unserialize_object(base64_decode($instance->configdata));
        $instbarema->nome_modelo = $config->title;
        $instbarema->created_at = $data;
        $instbarema->userid_created_at = $USER->id;
        //============Inserção do Novo Modelo Barema criando uma nova linha na tabela "eva_barema"======

        if ($DB->insert_record('eva_barema', $instbarema)) {
            //===========Após cadastrar o novo modelo na tabela "eva_barema" o formulario edit_form é zerado ===================
            $DB->update_record('block_instances', array('id'=>$configdata->numero_barema, 'configdata'=>$configdata->hash_barema));
        }
    }
}

function models_barema($novobarema, $returnurlbarema, $fildsbarema) {
    global $DB, $PAGE;


    $solicitacao = '';
    if($novobarema->is_cancelled()) {

        redirect($returnurlbarema);

    }else if ($solicitacao = $novobarema->get_data()) {


        $solicitacao->tb_subcategoria_id    = $fildsbarema['tb_subcategoria_id'];
        $solicitacao->tb_curso_id           = $fildsbarema['tb_curso_id'];
        $solicitacao->tb_atividade_id       = $fildsbarema['tb_atividade_id'];

        $data = barema_avaliador_create($solicitacao->avaliador_tb_user_id, $solicitacao);

        if ($data){
            $message = '<h5 class="text-center">' . get_string('msgnovobarema', 'block_eva_form_barema') . '</h5>';
        }
        if (isset($message)) {
//            if (!$PAGE->url->compare($returnurlbarema, URL_MATCH_BASE)) {
//            }
            redirect($returnurlbarema, $message);
            // We are already on the purge caches page, add the notification.
//            \core\notification::add($message, \core\output\notification::NOTIFY_INFO);
        }
    }
}

function controller_barema_avaliacao($returnurl, $fildsbarema) {
    global $DB, $PAGE;

    if ($_POST['cancelbutton']) {
        redirect($returnurl);

    }else if ($_POST['submitbutton']){

        $data = barema_avaliacao_create($fildsbarema);

        if ($data){
            if (!$data['error']) {
                $message = '<h5 class="text-center">' . get_string('msgformbarema', 'block_eva_form_barema') . '</h5>';
                $info = \core\output\notification::NOTIFY_INFO;
            }else{
                $message = '<h5 class="text-center"><span class="font-weight-bold">Falhou! </span> ' . $data['error'] . '</h5>';
                $info = \core\output\notification::NOTIFY_WARNING;
            }
            // Share a gratitude and Say Thank You! Your user will love to know their message was sent.
        }
        if (isset($message)) {
            \core\notification::add($message, $info);
            redirect($returnurl);
            // We are already on the purge caches page, add the notification.
            return false;
        }
    }
    return false;
}
