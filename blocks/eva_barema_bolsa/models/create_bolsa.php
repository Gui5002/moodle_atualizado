<?php

//=================Funçao do formulario de atribuição para o banco de dados=======================
function bolsa_avaliador_create($avaliadores, $anteprojetos, $bolsa){
    global $DB, $CFG;
    $old_barema = $DB->get_record('eva_bolsa_modelo', array('id'=>$bolsa->tb_bolsa_modelo_id));
    $arrayavaliador = array();

    foreach ($anteprojetos as $anteprojeto) {
        foreach ($avaliadores as $dadosava) {
            $url_atribuicao = base64_encode('barema_id='.$bolsa->tb_bolsa_modelo_id.'&avaliador_id='.$dadosava.'&anteprojeto_id='.$anteprojeto);
            $url_avaliacao = '/blocks/eva_barema_bolsa/avaliacao_bolsa.php?atrib=';
            $arrayavaliador[] = array(
                'tb_bolsa_modelo_id'     => $bolsa->tb_bolsa_modelo_id,
                'tb_anteprojeto_id'      => $anteprojeto,
                'avaliador_tb_user_id'   => $dadosava,
                'barema_modelo'          => $old_barema->hash_barema,
                'url_avaliacao'          => $url_avaliacao,
                'url_atrib'              => $url_atribuicao,
                'data_ini_avaliacao'     => $bolsa->data_ini_avaliacao,
                'qt_dia_avaliacao'       => $bolsa->qt_dia_avaliacao
            );

        $urlavaliacao = $CFG->wwwroot.'/blocks/eva_barema_bolsa/avaliacao.php?gerenciar='. md5('userid='.$dadosava);
//        $link_avaliador = '<a href="'.$urlavaliacao.'">Fazer Avaliação</a>';
        enviar_email_avaliadores($dadosava, $anteprojeto, $urlavaliacao);
        }
    }

    $DB->insert_records('eva_bolsa_atribuicao', $arrayavaliador);

    $avaliador = 1;
    return $avaliador;

}

function enviar_email_avaliadores($idavaliadores, $anteprojeto,  $url_barema) {
    global $DB, $USER;
    $name = '';


    $sql = "SELECT fullname, email FROM vw_autocomplete_user WHERE id ='{$idavaliadores}'";
    $avaliador = $DB->get_record_sql($sql);

    $nome_anteprojeto = $DB->get_record('eva_bolsa_anteprojeto', array('id'=>$anteprojeto));

    $contact_bolsa = new bolsa_contact();
    unset($_POST);

    $_POST['anteprojeto'] = $nome_anteprojeto->anteprojeto;
    $_POST['avaliador'] = $avaliador->fullname."\n";
    $_POST['mensagem'] = "\n\n".'Prezado Avaliador,'."\n\n".'Você tem uma nova avaliação referente ao edital de concessão de bolsas de estudo.'."\n".'Clique no link abaixo e visualize as avaliações pendentes.'."\n";
    $_POST['link'] = $url_barema;

    $from_email_nome['email'] = "eagu.bolsasdeestudo@agu.gov.br";
    $from_email_nome['nome'] = "ESAGU - Bolsas de Estudo";
    $from_email_nome['subtitle'] = "Avaliador, você tem uma nova avaliação.";

    $envio = $contact_bolsa->sendmessage($avaliador->email, $name, null , null, $from_email_nome);
    $mensagem[] = $envio;
//        if () {
//            // Share a gratitude and Say Thank You! Your user will love to know their message was sent.
//            $message = '<h5 class="text-center">' . get_string('msgsolicitacao', 'block_eva_form_library') . '</h5>';
//        } else {
//            // Oh no! What are the chances. Looks like we failed to meet user expectations (message not sent).
//            $message = '<h5 class="text-center">'.get_string('errorsendingtitle', 'bolsa_contact').'</h5>';
//        }
}

function block_instance_bolsa($instance) {

    global $CFG, $DB, $USER;
    $instbarema  = new stdClass;
    $data = date("Y-m-d h:i:sa");

    $instbarema->numero_barema = $instance->id;
    $instbarema->hash_barema = $instance->configdata;

    if (!$configdata = $DB->get_record('eva_bolsa_modelo', array('id'=>1))) {

        //===========Insere um Modelo Barema em "BRANCO" ==========
        $DB->insert_record('eva_bolsa_modelo', $instbarema);
    }else if(!hash_equals($configdata->hash_barema, $instance->configdata)) {
        $config = unserialize_object(base64_decode($instance->configdata));
        $instbarema->nome_modelo = $config->title;
        $instbarema->created_at = $data;
        $instbarema->userid_created_at = $USER->id;
        //============Inserção do Novo Modelo Barema criando uma nova linha na tabela "eva_bolsa_modelo"======

        if ($DB->insert_record('eva_bolsa_modelo', $instbarema)) {
            //===========Após cadastrar o novo modelo na tabela "eva_bolsa_modelo" o formulario edit_form é zerado ===================
            $DB->update_record('block_instances', array('id'=>$configdata->numero_barema, 'configdata'=>$configdata->hash_barema));
        }
    }
}

function criar_modelo_bolsa($data) {
    global $CFG, $DB, $USER;
    $hash_data =  base64_encode(serialize($data));

    $data_modelo['hash_barema'] = $hash_data;
    $data_modelo['nome_modelo'] = $data->title;
    $data_modelo['created_at'] = date("Y-m-d h:i:sa");
    $data_modelo['userid_created_at'] = $USER->id;

    $DB->insert_record('eva_bolsa_modelo', $data_modelo);

}


function form_atribuicao($form_atrib, $returnurl, $fildsinputs) {
    global $DB, $PAGE;


    if($form_atrib->is_cancelled()) {

        redirect($returnurl);

    }else if ($solicitacao = $form_atrib->get_data()) {

        $avaliadores[] =  $fildsinputs['avaliador_1'];
        $avaliadores[] =  $fildsinputs['avaliador_2'];
        $avaliadores[] =  $fildsinputs['avaliador_3'];

        $year = $fildsinputs['startdate_ava']['year'];
        $month = (strlen($fildsinputs['startdate_ava']['month']) == 1) ? '0'.$fildsinputs['startdate_ava']['month'] : $fildsinputs['startdate_ava']['month'];
        $day = (strlen($fildsinputs['startdate_ava']['day']) == 1) ? '0'.$fildsinputs['startdate_ava']['day'] : $fildsinputs['startdate_ava']['day'];

        $solicitacao->tb_bolsa_modelo_id    = $fildsinputs['tb_bolsa_modelo_id'];
        $solicitacao->tb_anteprojeto_id     = $fildsinputs['tb_anteprojeto_id'];
        $solicitacao->avaliador_tb_user_id  = $avaliadores;
        $solicitacao->data_ini_avaliacao        = $year.'-'.$month.'-'.$day;
        $solicitacao->qt_dia_avaliacao      = $fildsinputs['qt_dias_ava'];

        $data = bolsa_avaliador_create($solicitacao->avaliador_tb_user_id, $solicitacao->tb_anteprojeto_id,  $solicitacao);

        if ($data){
            $message = '<h5 class="text-center">' . get_string('create_msg', 'block_eva_barema_bolsa') . '</h5>';
        }
        if (isset($message)) {
//            if (!$PAGE->url->compare($returnurlbarema, URL_MATCH_BASE)) {
//            }
            redirect($returnurl, $message);
            // We are already on the purge caches page, add the notification.
//            \core\notification::add($message, \core\output\notification::NOTIFY_INFO);
        }
    }
}

function emails_pendente_avaliacao($returnurl, $fildsinputs) {
    global $DB, $CFG, $PAGE;


    if($fildsinputs['btn_email_pendente'] == "Enviar Email") {
        $id_atrib = $fildsinputs['id_atribuicao'];

        $dados = $DB->get_records('eva_bolsa_atribuicao', array('id'=>$id_atrib));

        foreach ($dados as $dado){
            $numero = $DB->get_field('eva_bolsa_anteprojeto', 'anteprojeto', array('id'=>$dado->tb_anteprojeto_id));
            $sql = "SELECT fullname, email FROM vw_autocomplete_user WHERE id = '{$dado->avaliador_tb_user_id}'";
            $user_name = $DB->get_record_sql($sql);
            $urlavaliacao = $CFG->wwwroot.'/blocks/eva_barema_bolsa/avaliacao.php?gerenciar='. md5('userid='.$dado->avaliador_tb_user_id);
        }

        $contact_bolsa = new bolsa_contact();
        unset($_POST);

        $text1 = ($fildsinputs['tempo_restante'] > 1) ? 'Faltam' : 'Falta';
        $text2 = ($fildsinputs['tempo_restante'] > 1) ? 'dias' : 'dia';

        $email = $user_name->email;
        $_POST['anteprojeto'] = $numero;
        $_POST['avaliador'] = $user_name->fullname."\n";
        $_POST['mensagem'] = "\n\n".'Prezado Avaliador,'."\n\n".$text1.' '.$fildsinputs['tempo_restante'].' '.$text2.' para realizar a avaliação pendente relativa ao edital de concessão de bolsas de estudo.'."\n".'Clique no link abaixo e visualize as avaliações pendentes.'."\n";
        $_POST['link'] = $urlavaliacao;

        $from_email_nome['email'] = "eagu.bolsasdeestudo@agu.gov.br";
        $from_email_nome['nome'] = "ESAGU - Bolsas de Estudo";
        $from_email_nome['subtitle'] = "Avaliador, você tem uma nova avaliação.";

        $envio = $contact_bolsa->sendmessage($email, $name, null , null, $from_email_nome);

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

function bolsa_avaliacao($returnurl, $fildsbarema) {
    global $DB, $PAGE;

    if ($_POST['cancelbutton']) {
        redirect($returnurl);

    }else if ($_POST['submitbutton']){

        $data = bolsa_avaliacao_create($fildsbarema);

        if ($data){
            if (!$data['error']) {
                $message = '<h5 class="text-center">' . get_string('msg_avaliacao_bolsa', 'block_eva_barema_bolsa') . '</h5>';
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

function bolsa_avaliacao_create($data) {
    global $DB;
    $avaliacao = (object) $data;

    $url_atrib = $_GET['atrib'];

    $sql = "SELECT * FROM mdl_eva_bolsa_atribuicao WHERE url_atrib = '{$url_atrib}' AND status = 0 AND ativo = 1 ";
    $dados_atribuicao = $DB->get_record_sql($sql);


    $avaliacao->tb_atribuicao_id = $dados_atribuicao->id;
    $avaliacao->data_avaliacao = date('Y-m-d h:i:sa');
    $avaliacao->status = 1;

    if ($dados_atribuicao){

        $dados = $DB->insert_record('eva_bolsa_avaliacao', $avaliacao, 'id');
        $DB->update_record('eva_bolsa_atribuicao', array('id'=>$dados_atribuicao->id, 'status'=>$avaliacao->status));
        $anteprojeto = $DB->get_field('eva_bolsa_anteprojeto', 'anteprojeto', array('id'=>$dados_atribuicao->tb_anteprojeto_id));
        $result_avaliacao = $DB->get_record('eva_bolsa_avaliacao', array('id'=>$dados));


//        =============== Condicao para operacões e resultado da nota final ===============================
        $sql = "SELECT anteprojeto FROM mdl_eva_bolsa_result_final WHERE anteprojeto = '{$anteprojeto}'";
        $exist = $DB->record_exists_sql($sql);
        $resultado = new stdClass();
        $avaliador = $result_avaliacao->nt_avaliador;
        if ($exist){
            $cont = $DB->get_record('eva_bolsa_result_final', array('anteprojeto'=>$anteprojeto));
            switch ($cont->cont){
                case 1: //existe um avaliador_1
                    $DB->update_record('eva_bolsa_result_final', array('id'=>$cont->id, 'avaliador_2'=>$avaliador, 'cont'=>2));
                break;
                case 2: //existe um avaliador_2
                    $DB->update_record('eva_bolsa_result_final', array('id'=>$cont->id, 'avaliador_3'=>$avaliador, 'cont'=>3));
                break;
            }
            if ($cont->cont == 2) {
                $sql = "SELECT avaliador_1, avaliador_2, avaliador_3, nt_capes FROM mdl_eva_bolsa_result_final WHERE anteprojeto = '{$anteprojeto}' ";
                $notafinal = $DB->get_record_sql($sql);
                $divisao = ($notafinal->avaliador_1 + $notafinal->avaliador_2 + $notafinal->avaliador_3)/3;
                $nota = $divisao + $notafinal->nt_capes;
                $nota = number_format($nota, 2, '.', '');
                $DB->update_record('eva_bolsa_result_final', array('id'=>$cont->id, 'nt_final'=>$nota));

            }
        }else{
            $resultado->anteprojeto = $anteprojeto;
            $resultado->avaliador_1 = $avaliador;
            $resultado->nt_capes = $DB->get_record('eva_bolsa_anteprojeto', array('id'=>$dados_atribuicao->tb_anteprojeto_id))->notacapes ;
            $resultado->cont = 1;
            $DB->insert_record('eva_bolsa_result_final', $resultado);

        }

        return $dados;
    }else{
        $msg['error'] = 'Esse Anteprojeto ja foi avaliado!';
        return $msg;
    }
}



