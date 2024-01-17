<?php

function atribuicao_avaliador_create($avaliadores, $baremaCurso){
    global $DB, $CFG, $USER;

//    $distribuicao = $DB->get_records('eva_barema_distribuicao', array('usuario_id'=>$USER->id));
//    $idusers =  $DB->get_records('quiz_attempts', array('quiz'=>$baremaCurso->tb_atividade_id, 'state'=>'finished'), '', 'id, userid');
    $idusers =  $DB->get_records('quiz_attempts', array('quiz'=>$baremaCurso->tb_atividade_id), '', 'id, userid, state');

    $old_barema = $DB->get_record('eva_barema', array('id'=>$baremaCurso->tb_barema_id));
    $arrayavaliador = array();
    $arrayalunos = array();

    foreach ($avaliadores as $dadosava) {
        $qt_aluno = $DB->get_field('eva_barema_distribuicao', 'qt_alunos', array('avaliador_id'=>$dadosava));
        $url_barema = '/blocks/eva_form_barema/barema_avaliacao.php?barema_id='.$baremaCurso->tb_barema_id.'&avaliador_id='.$dadosava.'&curso_id='.$baremaCurso->tb_curso_id.'&quiz_id='.$baremaCurso->tb_atividade_id;
        $arrayavaliador = array(
            'tb_barema_id'            => $baremaCurso->tb_barema_id,
            'avaliador_tb_user_id'    => $dadosava,
            'tb_categoria_id'         => $baremaCurso->tb_categoria_id,
            'tb_subcategoria_id'      => $baremaCurso->tb_subcategoria_id,
            'tb_curso_id'             => $baremaCurso->tb_curso_id,
            'tb_atividade_id'         => $baremaCurso->tb_atividade_id,
            'qtd_alunos'              => $qt_aluno,
            'barema_modelo'           => $old_barema->hash_barema,
            'url_avaliacao'           => $url_barema,
            'data_atribuicao'         => date('Y-m-d')
        );

        $DB->insert_record('eva_barema_avaliador', $arrayavaliador);

//        $id_avaliador = $DB->get_field_sql("SELECT id FROM mdl_eva_barema_avaliador WHERE avaliador_tb_user_id = {$dadosava} ORDER BY id DESC");

        $cont = $DB->get_record_sql("SELECT id, avaliador_tb_user_id FROM mdl_eva_barema_avaliador ORDER BY id DESC");
        $qt = $qt_aluno;
        foreach ($idusers as $key=>$al){
            if ($qt > 0){
                $arrayalunos[] = array(
                    'quiz_att_id'       => $al->id,
                    'tb_avaliador_id'   => $cont->id,
                    'avaliador_id'      => $cont->avaliador_tb_user_id,
                    'alunos_id'         => $al->userid,
                    'status'            => ($al->state == "finished") ? 1 : 0 ,
                    'prazo'            => ($al->state == "inprogress") ? $baremaCurso->prazo : null ,
                );
                $qt--;
                unset($idusers[$key]);
            }
        }

        $link_avalaidor = $CFG->wwwroot.'/blocks/eva_form_barema/gerencia.php?avaliador='.$dadosava;
        set_envio_email_avaliadores($dadosava, $baremaCurso, $link_avalaidor);
    }

    $DB->insert_records('eva_barema_alunos', $arrayalunos);


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

function set_envio_email_avaliadores($idavaliadores, $baremaCurso,  $link_avaliador) {
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
    $_POST['link'] = $link_avaliador;

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


function emails_pendente_avaliacao_pos($returnurl, $fildsinputs) {

    global $DB, $CFG, $PAGE;
    $name = '';

    if($fildsinputs['btn_email_pendente'] == "Enviar Email") {
        $id_atrib = $fildsinputs['id_tbavaliador'];

        $dados = $DB->get_records('eva_barema_avaliador', array('id'=>$id_atrib));

        foreach ($dados as $dado){
            $nome_curso = $DB->get_field('course', 'fullname', array('id'=>$dado->tb_curso_id));
            $nome_quiz = $DB->get_field('quiz', 'name', array('id'=>$dado->tb_atividade_id));
            $sql = "SELECT fullname, email FROM vw_autocomplete_user WHERE id = '{$dado->avaliador_tb_user_id}'";
            $user_name = $DB->get_record_sql($sql);
            $urlavaliacao = $CFG->wwwroot.'/blocks/eva_form_barema/gerencia.php?avaliador='.$dado->avaliador_tb_user_id;
//            $qt_alunos = $dado->qtd_alunos - $dado->qtd_avaliados;
        }

        $contact_pendente = new pos_contact();
        unset($_POST);

        $text1 = ($fildsinputs['qtd_alunos'] > 1) ? 'Existem' : 'Existe';
//        $text2 = ($fildsinputs['qtd_alunos'] > 1) ? 'dias' : 'dia';

        $email = $user_name->email;
        $_POST['curso'] = $nome_curso;
        $_POST['atividade'] = $nome_quiz;
        $_POST['avaliador'] = $user_name->fullname."\n";
        $_POST['mensagem'] = "\n\n".'Prezado Avaliador,'."\n\n".$text1.' '.$fildsinputs['qtd_alunos'].' alunos pendentes da Pós graduação para fazer avaliação.'."\n".'Clique no link abaixo e visualize as avaliações pendentes.'."\n";
        $_POST['link'] = $urlavaliacao;

//        $from_email_nome['email'] = "eagu.bolsasdeestudo@agu.gov.br";
//        $from_email_nome['nome'] = "ESAGU - afastamentos de Estudo";
//        $from_email_nome['subtitle'] = "Avaliador, você tem uma nova avaliação.";

        $envio = $contact_pendente->sendmessage($email, $name, null , null);

        if ($envio){
            $message = '<h5 class="text-center">' .'Email enviado com sucesso'. '</h5>';
            \core\notification::add($message, \core\output\notification::NOTIFY_INFO);
            redirect($returnurl);
        }else{
            $message = '<h5 class="text-center">' .'Falha ao enviar email'. '</h5>';
            \core\notification::add($message, \core\output\notification::NOTIFY_WARNING);
            redirect($returnurl);
        }
    }
}

function barema_avaliacao_create($data) {
    global $DB;
    $avaliacao = (object) $data;


    $tb_avaliador = $DB->get_record('eva_barema_avaliador', array('id'=>$avaliacao->tb_avaliador_id));
    $avaliacao->data_avaliacao = date('Y-m-d h:i:sa');
    $avaliacao->status = 1;

    if ($avaliacao->percent > 0){
        $avaliacao->flag = 1;
    }else if ($avaliacao->percent < 0){
        $avaliacao->flag = 2;
    }

    if ($avaliacao->aluno_tb_user_id){

        $existe = $DB->record_exists('eva_barema_avaliacao', array('tb_avaliador_id'=>$avaliacao->tb_avaliador_id, 'aluno_tb_user_id'=>$avaliacao->aluno_tb_user_id));
        if (!$existe) {

            $dados = $DB->insert_record('eva_barema_avaliacao', $avaliacao);
            $qt_avaliados = $tb_avaliador->qtd_avaliados + 1;
            if ($qt_avaliados < $tb_avaliador->qtd_alunos){
                $DB->update_record('eva_barema_avaliador', array('id'=>$tb_avaliador->id, 'qtd_avaliados'=>$qt_avaliados));
                $return = $DB->delete_records('eva_barema_alunos', array('tb_avaliador_id'=>$avaliacao->tb_avaliador_id, 'alunos_id'=>$avaliacao->aluno_tb_user_id));
            }elseif ($qt_avaliados == $tb_avaliador->qtd_alunos){
                $DB->update_record('eva_barema_avaliador', array('id'=>$tb_avaliador->id, 'qtd_avaliados'=>$qt_avaliados, 'status'=>1));
            }
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


//function block_instance_barema($instance) {
//
//    global $CFG, $DB, $USER;
//    $instbarema  = new stdClass;
//    $data = date("Y-m-d h:i:sa");
//
//    $instbarema->numero_barema = $instance->id;
//    $instbarema->hash_barema = $instance->configdata;
//
//    if (!$configdata = $DB->get_record('eva_barema', array('id'=>1))) {
//
//        //===========Insere um Modelo Barema em "BRANCO" ==========
//        $DB->insert_record('eva_barema', $instbarema);
//    }else if(!hash_equals($configdata->hash_barema, $instance->configdata)) {
//        $config = unserialize_object(base64_decode($instance->configdata));
//        $instbarema->nome_modelo = $config->title;
//        $instbarema->created_at = $data;
//        $instbarema->userid_created_at = $USER->id;
//        //============Inserção do Novo Modelo Barema criando uma nova linha na tabela "eva_barema"======
//
//        if ($DB->insert_record('eva_barema', $instbarema)) {
//            //===========Após cadastrar o novo modelo na tabela "eva_barema" o formulario edit_form é zerado ===================
//            $DB->update_record('block_instances', array('id'=>$configdata->numero_barema, 'configdata'=>$configdata->hash_barema));
//        }
//    }
//}

function criar_modelo_pos($data) {
    global $CFG, $DB, $USER;
    $hash_data =  base64_encode(serialize($data));

    $data_modelo['hash_barema'] = $hash_data;
    $data_modelo['nome_modelo'] = $data->title;
    $data_modelo['created_at'] = date("Y-m-d h:i:sa");
    $data_modelo['userid_created_at'] = $USER->id;

    $DB->insert_record('eva_barema', $data_modelo);

}

function cadastrar_atribuicao($novobarema, $returnurlbarema, $fildsbarema) {
    global $DB, $PAGE;



    $solicitacao = '';
    if($novobarema->is_cancelled()) {

        redirect($returnurlbarema);

    }else if ($solicitacao = $novobarema->get_data()) {


        $solicitacao->tb_subcategoria_id    = $fildsbarema['tb_subcategoria_id'];
        $solicitacao->tb_curso_id           = $fildsbarema['tb_curso_id'];
        $solicitacao->tb_atividade_id       = $fildsbarema['tb_atividade_id'];

        $data = atribuicao_avaliador_create($solicitacao->avaliador_tb_user_id, $solicitacao);

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
