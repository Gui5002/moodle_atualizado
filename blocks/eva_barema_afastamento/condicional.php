<?php
global $CFG, $DB, $PAGE, $USER;
/**
 * @author Celio Pereira Batalha
 * email: celio.batalha@gmail.com
 */
require_once('classes/privacy/afastamento_contact.php');
require_once ('../../config.php');
require_once ('lib.php');


$acao = ($_POST['acao'] ? $_POST['acao'] : $_GET['acao']);

switch ($acao) {
    //==== é identificado pelo name do input de envio do Modal anteprojeto======

    case 'btn_anteprojeto':

        $anteprojeto  = $_GET;

        $existe = $DB->record_exists('eva_afastamento_anteprojeto', array("anteprojeto"=>$anteprojeto['anteprojeto']));

        if (!$existe) {
            $result = $DB->insert_record('eva_afastamento_anteprojeto', $anteprojeto);

            if ($result) {
                $msg['message'] = 'success';
            } else {
                $msg['message'] = 'error';
            }
        }else{
            $msg['message'] = 'fail';
        }
        echo json_encode($msg);
    break;

    case 'editformafastamento':
        $barema = $DB->get_record('eva_afastamento_modelo', array('id'=>1));
        if ($barema->id) {
            $bi =  $DB->update_record('block_instances', array('id'=>$barema->numero_barema, 'configdata'=>$barema->hash_barema));
        }
        echo json_encode($bi);
    break;

    case 'escolhaeixo':

        $updateeixo = $_GET['eixo'];
        $inserteixo = $_GET;


        $dados = $DB->get_record('eva_afastamento_jur_ges', array('id'=>1));
        if ($dados->id){
            $return =  $DB->update_record_raw('eva_afastamento_jur_ges', array('id'=>$dados->id, 'eixo'=>$updateeixo));
        }else{
            $return = $DB->insert_record('eva_afastamento_jur_ges', $inserteixo);
        }
        if ($return == true){
            $msg = 'success';
        }

        echo json_encode($msg);
    break;


    case 'selecionaravaliadores':
        $numero = $_GET;
        $anteprojeto = $DB->get_record('eva_afastamento_anteprojeto', array("id" => $numero['id']));

        if ($anteprojeto->distribuicao == 'j'){
            $avaliadores_user = $DB->get_records('eva_afastamento_avaliadores', array("eixo" => $anteprojeto->distribuicao));
            $i=0;
            $ct=1;
            //======Titular e Suplente===========
            foreach ($avaliadores_user as $key=>$user) {
                $sql = "SELECT id, fullname, email FROM vw_autocomplete_user where id = '{$user->avaliador_id}'";
                $avaliador = $DB->get_record_sql($sql);
//                $suplente_sql = "SELECT id, fullname, email FROM vw_autocomplete_user where id = '{$t_s->suplente_user_id}'";
//                $ava_suplente = $DB->get_record_sql($suplente_sql);
                $avaliadores[$i]['id'] = $avaliador->id;
                $avaliadores[$i]['nome'] = $avaliador->fullname;
                if ($user->status == 0){
                    $avaliadores[$i]['afastado'] =  'disabled';
                }

                $i++;
//                $avaliadores[$i]['id'] = $ava_suplente->id;
//                $avaliadores[$i]['nome'] = $ct.'.1 - '.$ava_suplente->fullname;
//                $avaliadores[$i]['afastado'] = ($user->status_suplente == 0) ? 'disabled' : '';
//                $i++;
//                $ct++;
            }

        }else{
            $avaliadores_user = $DB->get_records('eva_afastamento_avaliadores', array());
            $i=0;
            $ct=1;
            foreach ($avaliadores_user as $key=>$user) {
                $sql = "SELECT id, fullname, email FROM vw_autocomplete_user where id = '{$user->avaliador_id}'";
                $avaliador = $DB->get_record_sql($sql);
//                $suplente_sql = "SELECT id, fullname, email FROM vw_autocomplete_user where id = '{$t_s->suplente_user_id}'";
//                $ava_suplente = $DB->get_record_sql($suplente_sql);
                $avaliadores[$i]['id'] = $avaliador->id;
                $avaliadores[$i]['nome'] = $avaliador->fullname;
                if ($user->status == 0){
                    $avaliadores[$i]['afastado'] =  'disabled';
                }
                $i++;
//                $avaliadores[$i]['id'] = $ava_suplente->id;
//                $avaliadores[$i]['nome'] = $ct.'.1 - '.$ava_suplente->fullname;
//                $avaliadores[$i]['afastado'] = ($t_s->status_suplente == 0) ? 'disabled' : '';
//                $i++;
//                $ct++;
            }
        }
    echo json_encode($avaliadores);
    break;
    case 'buscausuarios':
//        $names = get_users_confirmed();
        $users = "SELECT * FROM vw_autocomplete_user";
        $names = $DB->get_records_sql($users);


        $i=0;
        foreach ($names as $key=>$name) {
            $option[$i]['id'] = $name->id;
            $option[$i]['nome'] = $name->fullname;
            $i++;
        }
        $option['length'] = $i;
        echo json_encode($option);

    break;

    case 'adicionarvaliador':
        $avaliadores =  $_GET;
        $existe = $DB->record_exists('eva_afastamento_avaliadores', array('avaliador_id'=>$avaliadores['avaliador_id']));
        if (!$existe){
            $avaliador = $DB->insert_record('eva_afastamento_avaliadores', $avaliadores);
        }
        echo json_encode($existe);
    break;

    case 'editavaliadores':

        $id = $_GET['id'];
        $coluna_user_id = $_GET['coluna_user_id'];
        $user_id = $_GET['user_id'];
        $eixo = $_GET['eixo'];

        $retorno = $DB->update_record_raw('eva_afastamento_avaliadores', array('id'=>$id, $coluna_user_id=>$user_id, 'eixo'=>$eixo));

        if ($retorno){
            $message['msg'] = "Avaliador alterado com sucesso!";
        }else{
            $message['msg'] = "Algo deu errado...!";
        }
        echo json_encode($message);

    break;

    case 'mudarstatus':
        $avaliador = "";
//        $message = "";
        $avaliador_id = $_GET['id'];
//        $coluna_status = $_GET['coluna_status'];
//        $coluna_user_id = $_GET['coluna_user_id'];
        if ($_GET['status'] == 'suspend') {
            $avaliador['status'] = 0;
        }else{
            $avaliador['status'] = 1;
        }

        $sql = "SELECT * FROM mdl_eva_afastamento_atribuicao WHERE avaliador_tb_user_id ='{$avaliador_id}' AND status = 0 AND flag = 0 AND ativo > 0";
        $qtd_avaliacao = $DB->get_records_sql($sql);
//        $qtd_avaliacao = $DB->get_records('eva_afastamento_atribuicao', array('avaliador_tb_user_id'=>$avaliador_id, 'status'=>0, 'ativo'=>1));
        $cont = count($qtd_avaliacao);

        if ($cont === 0){
            $id_table_avaliador = $DB->get_record('eva_afastamento_avaliadores', array('avaliador_id'=>$avaliador_id), 'id');
            $resposta = $DB->update_record('eva_afastamento_avaliadores', array('id'=>$id_table_avaliador->id, 'status'=>$avaliador['status']));
            if ($resposta){
                if ($avaliador['status'] == 1) {
//                    $message['msg'] = "Avaliador ativado com sucesso!";
                    $message['status'] = '1';
                }else{
//                    $message['msg'] = "Avaliador desativado com sucesso!";
                    $message['status'] = '0';
                }
            }else{
                $message['msg'] = false;
            }
        }else{
            $message['cont'] = true;
        }

        echo json_encode($message);
    break;

    case 'emailpendente':
        $id_atrib = $_GET['id'];
        $dados = $DB->get_records('eva_afastamento_atribuicao', array('id'=>$id_atrib));

        foreach ($dados as $dado){
            $numero = $DB->get_field('eva_afastamento_anteprojeto', 'anteprojeto', array('id'=>$dado->tb_anteprojeto_id));
            $sql = "SELECT fullname, email FROM vw_autocomplete_user WHERE id = '{$dado->avaliador_tb_user_id}'";
            $user_name = $DB->get_record_sql($sql);
            $urlavaliacao = $CFG->wwwroot.'/blocks/eva_barema_afastamento/avaliacao.php?gerenciar='. md5('userid='.$dado->avaliador_tb_user_id);
        }

        $email['idatribuicao'] = $id_atrib;
        $email['anteprojeto'] = $numero;
        $email['avaliador'] = $user_name->fullname;
        $email['email'] = $user_name->email;
        $email['url_link'] = $urlavaliacao;

        echo json_encode($email);
    break;

    case 'preparandoSubstituicao':
        $id_atrib = $_GET['id'];
        $dados = $DB->get_records('eva_afastamento_atribuicao', array('id'=>$id_atrib));

        foreach ($dados as $dado){
            $numero = $DB->get_field('eva_afastamento_anteprojeto', 'anteprojeto', array('id'=>$dado->tb_anteprojeto_id));
            $destribuicao = $DB->get_field('eva_afastamento_anteprojeto', 'distribuicao', array('id'=>$dado->tb_anteprojeto_id));
            $qtdia = $dado->qt_dia_avaliacao;
            $sql = "SELECT fullname, email FROM vw_autocomplete_user WHERE id = '{$dado->avaliador_tb_user_id}'";
            $user_name = $DB->get_record_sql($sql);
            $urlavaliacao = $CFG->wwwroot.'/blocks/eva_afastamento_bolsa/avaliacao.php?gerenciar='. md5('userid='.$dado->avaliador_tb_user_id);
        }


        $atual['anteprojeto'] = $numero;
        $atual['destribuicao'] = $destribuicao;
        $atual['avaliador'] = $user_name->fullname;
        $atual['qt_dia'] = $qtdia;
        $atual['idatribuicao'] = $id_atrib;
        $atual['email'] = $user_name->email;
        $atual['url_link'] = $urlavaliacao;

        echo json_encode($atual);
    break;

    case 'verificastatus':
        $id_user = $_GET['id'];
        $atribuicao_id = $_GET['idatribuicao'];
//        $idanteprojeto = $DB->get_field('eva_afastamento_atribuicao', 'tb_anteprojeto_id', array('id'=>$atribuicao_id, 'status'=>0));
        $anteprojetos = $DB->get_records('eva_afastamento_atribuicao', array('id'=>$atribuicao_id, 'status'=>0));
        $compareanteprojetos = $DB->get_records('eva_afastamento_atribuicao', array('avaliador_tb_user_id'=>$id_user, 'status'=>0, 'flag'=>0));


        $anteprojetoExiste = false;
        foreach ($anteprojetos as $data){
            $eixoateprojeto = $DB->get_field('eva_afastamento_anteprojeto', 'distribuicao', array('id'=>$data->tb_anteprojeto_id));

            foreach ($compareanteprojetos as $compare){
                if (($compare->tb_anteprojeto_id == $data->tb_anteprojeto_id)){
                    $anteprojetoExiste = true;
                }
            }
            $eixoavaliador = $DB->get_field('eva_afastamento_avaliadores', 'eixo', array('avaliador_id'=>$id_user));

        }



        if ($anteprojetoExiste){
            $status['status'] = 'restric';
        }else{
            if ($eixoateprojeto == 'g'){
                $status['status'] = true;
            }else if ($eixoateprojeto == 'j' && $eixoavaliador == 'j'){
                $status['status'] = true;
            }else if ($eixoateprojeto == 'j' && $eixoavaliador == 'g'){
                $status['status'] = false;
            }
        }
            echo json_encode($status);
    break;

    case 'substituicao':
        $data = date("Y-m-d");
        $idatribuicao = $_GET['id_atribuicao'];
        $idsubstituto = $_GET['id_substituto'];
        $qt_dia = $_GET['qt_dia'];

        $dados = $DB->get_records('eva_afastamento_atribuicao', array('id'=>$idatribuicao));

        foreach ($dados as $dado){
            $url_atribuicao = base64_encode('barema_id='.$dado->tb_afastamento_modelo_id.'&avaliador_id='.$idsubstituto.'&anteprojeto_id='.$dado->tb_anteprojeto_id);
            $url_avaliacao = '/blocks/eva_barema_afastamento/avaliacao_afastamento.php?atrib=';
            $id_substituido = $dado->avaliador_tb_user_id;
            $data_substituido = $dado->data_ini_avaliacao;
            $qt_dia_substituido = $dado->qt_dia_avaliacao;
            $arrayavaliador[] = array(
                'tb_afastamento_modelo_id'     => $dado->tb_afastamento_modelo_id,
                'tb_anteprojeto_id'      => $dado->tb_anteprojeto_id,
                'avaliador_tb_user_id'   => $idsubstituto,
                'barema_modelo'          => $dado->barema_modelo,
                'url_avaliacao'          => $url_avaliacao,
                'url_atrib'              => $url_atribuicao,
                'data_ini_avaliacao'     => $data,
                'qt_dia_avaliacao'       => $qt_dia,
                'ativo'                  => 2
            );
        }

        $urlavaliacao = $CFG->wwwroot.'/blocks/eva_barema_afastamento/avaliacao.php?gerenciar='. md5('userid='.$idsubstituto);

//        enviar_email_para_substituicao($dadosava, $anteprojeto, $urlavaliacao);

        $DB->insert_records('eva_afastamento_atribuicao', $arrayavaliador);
        $sql = 'SELECT * FROM mdl_eva_afastamento_atribuicao ORDER BY id DESC limit 1';
        $dadosubs = $DB->get_records_sql($sql);
        foreach ($dadosubs as $subs){
            $tabelasubstituicao[] = array(
                'tb_atribuicao_id'=>$subs->id,
                'avaliador_substituido'=>$id_substituido,
                'qt_dia_substituido'=> $qt_dia_substituido,
                'data_substituido'=> $data_substituido,
                'usuarioid_operador'=>$USER->id
            );
        }

        $DB->insert_records('eva_afastamento_substituicao', $tabelasubstituicao);

        $result_update = $DB->update_record_raw('eva_afastamento_atribuicao', array('id'=>$idatribuicao, 'flag'=>1));

        echo json_encode($result_update);

    break;

    case 'emailparasubstituido':
        $id_tb_atribuicao = $_GET['id_tb_atribuicao'];
        $dados = $DB->get_records('eva_afastamento_atribuicao', array('id'=>$id_tb_atribuicao, 'flag'=>1));

        $mensagem = "\n\n".'Prezado Avaliador,'."\n\n".'Devido a sua situação voce foi substituído dessa avaliação.'."\n".'Clique no link abaixo e visualize as avaliações pendentes.'."\n";
        foreach ($dados as $dado){
            $urlavaliacao = $CFG->wwwroot.'/blocks/eva_barema_afastamento/avaliacao.php?gerenciar='. md5('userid='.$dado->avaliador_tb_user_id);
            $avaliador_id = $dado->avaliador_tb_user_id;
            $anteprojeto_id = $dado->tb_anteprojeto_id;
        }

        $retorno_email =  enviar_email_para_substituicao($avaliador_id, $anteprojeto_id, $urlavaliacao, $mensagem);

    echo json_encode($retorno_email);
    break;

    case 'emailparasubstituto':
        $id_tb_atribuicao = $_GET['id_tb_atribuicao'];
        $substitutoid = $_GET['idsubstituto'];
        $dados = $DB->get_records('eva_afastamento_atribuicao', array('id'=>$id_tb_atribuicao, 'flag'=>1));

        $mensagem = "\n\n".'Prezado Avaliador,'."\n\n".'Você tem uma nova avaliação referente ao edital de afastamentos de estudo.'."\n".'Clique no link abaixo e visualize as avaliações pendentes.'."\n";
        foreach ($dados as $dado){
            $urlavaliacao = $CFG->wwwroot.'/blocks/eva_barema_afastamento/avaliacao.php?gerenciar='. md5('userid='.$dado->avaliador_tb_user_id);
            $avaliador_id = $substitutoid;
            $anteprojeto_id = $dado->tb_anteprojeto_id;
        }

        $retorno_email =  enviar_email_para_substituicao($avaliador_id, $anteprojeto_id, $urlavaliacao, $mensagem);

        echo json_encode($retorno_email);
    break;

    case 'infoSubstituicao':
        $id_atribuicao = $_GET['id_atribuicao'];

        $dadossubstituto = $DB->get_records('eva_afastamento_atribuicao', array('id'=>$id_atribuicao));
        $dadossubstituido = $DB->get_records('eva_afastamento_substituicao', array('tb_atribuicao_id'=>$id_atribuicao));
        foreach ($dadossubstituto as $substituto){
            $fullname = pegando_nome_completo($substituto->avaliador_tb_user_id);
            $info['avaliador_substituto'] = $fullname;
            $info['qt_dia_substituto'] = $substituto->qt_dia_avaliacao;
            $info['data_substituto'] = $substituto->data_ini_avaliacao;
        }
        foreach ($dadossubstituido as $substituido){
            $fullname = pegando_nome_completo($substituido->avaliador_substituido);
            $info['avaliador_substituido'] = $fullname;
            $info['qt_dia_substituido'] = $substituido->qt_dia_substituido;
            $info['data_substituido'] = $substituido->data_substituido;
        }



        echo json_encode($info);
    break;
}

function enviar_email_para_substituicao($idavaliador, $anteprojeto, $url_barema, $mensagem){
    global $DB, $USER;
    $name = '';

    $sql = "SELECT fullname, email FROM vw_autocomplete_user WHERE id ='{$idavaliador}'";
    $avaliador = $DB->get_record_sql($sql);

    $nome_anteprojeto = $DB->get_record('eva_afastamento_anteprojeto', array('id'=>$anteprojeto));

    $contact_afastamento = new afastamento_contact();
//    $contact_afastamento = new local_contact();
    unset($_POST);

    $_POST['anteprojeto'] = $nome_anteprojeto->anteprojeto;
    $_POST['avaliador'] = $avaliador->fullname."\n";
    $_POST['mensagem'] = $mensagem;
    $_POST['link'] = $url_barema;


    $from_email_nome['email'] = "eagu.bolsasdeestudo@agu.gov.br";
    $from_email_nome['nome'] = "ESAGU - afastamentos de Estudo";
    $from_email_nome['subtitle'] = "Avaliador, você tem uma nova avaliação.";

    $envio = $contact_afastamento->sendmessage($avaliador->email, $name, null , null, $from_email_nome);

    return $envio;
}

function pegando_nome_completo($idnome){
    global $DB;
    $sql = "SELECT fullname, email FROM vw_autocomplete_user WHERE id = '{$idnome}'";
    $user_name = $DB->get_record_sql($sql);

    return $user_name->fullname;
}