<?php
/**
 * @author Celio Pereira Batalha
 * email: celio.batalha@gmail.com
 */

require_once ('../../config.php');
require_once ('lib.php');

global $CFG, $DB, $PAGE, $USER;

$acao = ($_POST['acao'] ? $_POST['acao'] : $_GET['acao']);

switch ($acao) {
    //==== é identificado pelo name do input de envio do Modal anteprojeto======

    case 'btn_anteprojeto':

        $anteprojeto  = $_GET;

        $existe = $DB->record_exists('eva_bolsa_anteprojeto', array("anteprojeto"=>$anteprojeto['anteprojeto']));

        if (!$existe) {
            $result = $DB->insert_record('eva_bolsa_anteprojeto', $anteprojeto);
            $ant_files = $DB->get_records('eva_file_anteprojeto', array());

            if ($result) {
                $msg['message'] = 'success';
                $i=0;
                foreach ($ant_files as $f){
                    $msg[$i]['file_nome'] = $f->nome;
                    $msg[$i]['file_path'] = $f->path;
                    $i++;
                }
                $msg['length'] = $i;
            } else {
                $msg['message'] = 'error';
            }
        }else{
            $msg['message'] = 'fail';
        }
        echo json_encode($msg);
    break;

    case 'editformbolsa':
        $barema = $DB->get_record('eva_bolsa_modelo', array('id'=>1));
        if ($barema->id) {
            $bi =  $DB->update_record('block_instances', array('id'=>$barema->numero_barema, 'configdata'=>$barema->hash_barema));
        }
    echo json_encode($bi);
    break;

    default:

    case 'escolhaeixo':

        $updateeixo = $_GET['eixo'];
        $inserteixo = $_GET;


        $dados = $DB->get_record('eva_bolsa_jur_ges', array('id'=>1));
        if ($dados->id){
            $return =  $DB->update_record_raw('eva_bolsa_jur_ges', array('id'=>$dados->id, 'eixo'=>$updateeixo));
        }else{
            $return = $DB->insert_record('eva_bolsa_jur_ges', $inserteixo);
        }
        if ($return == true){
            $msg = 'success';
        }

        echo json_encode($msg);
    break;
    case 'selecionaravaliadores':
        $numero = $_GET;
        $anteprojeto = $DB->get_record('eva_bolsa_anteprojeto', array("id" => $numero['id']));

        if ($anteprojeto->distribuicao == 'j'){
            $titularsuplente = $DB->get_records('eva_bolsa_avaliadores', array("eixo" => $anteprojeto->distribuicao, "status" => 1));
            $i=0;
            // $ct=1;
            //======Titular e Suplente===========
            foreach ($titularsuplente as $key=>$t_s) {
                $titular_sql = "SELECT id, fullname, email FROM vw_autocomplete_user where id = '{$t_s->user_id}'";
                $ava_titulares = $DB->get_record_sql($titular_sql);
                // $suplente_sql = "SELECT id, fullname, email FROM vw_autocomplete_user where id = '{$t_s->suplente_user_id}'";
                // $ava_suplente = $DB->get_record_sql($suplente_sql);
                $avaliadores[$i]['id'] = $ava_titulares->id;
                $avaliadores[$i]['nome'] = $ava_titulares->fullname;
                // if ($t_s->status == 0){
                //     $avaliadores[$i]['afastado'] =  'disabled';
                // }

                $i++;
                // $avaliadores[$i]['id'] = $ava_suplente->id;
                // $avaliadores[$i]['nome'] = $ct.'.1 - '.$ava_suplente->fullname;
                // $avaliadores[$i]['afastado'] = ($t_s->status_suplente == 0) ? 'disabled' : '';
                // $i++; 
                // $ct++;
            }

        }else{
            $titularsuplente = $DB->get_records('eva_bolsa_avaliadores', array('status' => 1));
            $i=0;
            $ct=1;
            foreach ($titularsuplente as $key=>$t_s) {
                $titular_sql = "SELECT id, fullname, email FROM vw_autocomplete_user where id = '{$t_s->user_id}'";
                $ava_titulares = $DB->get_record_sql($titular_sql);
                // $suplente_sql = "SELECT id, fullname, email FROM vw_autocomplete_user where id = '{$t_s->suplente_user_id}'";
                // $ava_suplente = $DB->get_record_sql($suplente_sql);
                $avaliadores[$i]['id'] = $ava_titulares->id;
                $avaliadores[$i]['nome'] = $ava_titulares->fullname;
                // if ($t_s->status_titular == 0){
                //     $avaliadores[$i]['afastado'] =  'disabled';
                // }
                $i++;
                // $avaliadores[$i]['id'] = $ava_suplente->id;
                // $avaliadores[$i]['nome'] = $ct.'.1 - '.$ava_suplente->fullname;
                // $avaliadores[$i]['afastado'] = ($t_s->status_suplente == 0) ? 'disabled' : '';
                // $i++; $ct++;
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
        $avaliador = $DB->insert_record('eva_bolsa_avaliadores', $avaliadores);
        echo json_encode($avaliadores);

    break;

    case 'editavaliadores':

        $id = $_GET['id'];
        // $coluna_user_id = $_GET['coluna_user_id'];
        $user_id = $_GET['user_id'];
        $eixo = $_GET['eixo'];

        $retorno = $DB->update_record_raw('eva_bolsa_avaliadores', array('id'=>$id, 'user_id'=>$user_id, 'eixo'=>$eixo));

        if ($retorno){
            $message['msg'] = "Avaliador alterado com sucesso!";
        }else{
            $message['msg'] = "Algo deu errado...!";
        }
        echo json_encode($message);

    break;

    case 'mudarstatus':
        $avaliador = "";
        $avaliador_id = $_GET['id'];
        // $coluna_status = $_GET['coluna_status'];
        // $coluna_user_id = $_GET['user_id'];
        if ($_GET['status'] == 'suspend') {
            $avaliador['status'] = 0;
        }else{
            $avaliador['status'] = 1;
        }
        $id_table_avaliador = $DB->get_record('eva_bolsa_avaliadores', array('user_id'=>$avaliador_id), 'id');
        $resposta = $DB->update_record_raw('eva_bolsa_avaliadores', array('id'=>$id_table_avaliador->id, 'status'=>$avaliador['status']));
        if ($resposta){
            if ($avaliador['status'] == 1) {
                $message['msg'] = "Avaliador ativado com sucesso!";
            }else{
                $message['msg'] = "Avaliador desativado com sucesso!";
            }
        }else{
            $message['msg'] = "Algo deu errado...!";
        }

        echo json_encode($message);
    break;

    case 'emailpendente':
        $id_atrib = $_GET['id'];
        $dados = $DB->get_records('eva_bolsa_atribuicao', array('id'=>$id_atrib));

        foreach ($dados as $dado){
            $numero = $DB->get_field('eva_bolsa_anteprojeto', 'anteprojeto', array('id'=>$dado->tb_anteprojeto_id));
            $sql = "SELECT fullname, email FROM vw_autocomplete_user WHERE id = '{$dado->avaliador_tb_user_id}'";
            $user_name = $DB->get_record_sql($sql);
            $urlavaliacao = $CFG->wwwroot.'/blocks/eva_barema_bolsa/avaliacao.php?gerenciar='. md5('userid='.$dado->avaliador_tb_user_id);
        }

        $email['idatribuicao'] = $id_atrib;
        $email['anteprojeto'] = $numero;
        $email['avaliador'] = $user_name->fullname;
        $email['email'] = $user_name->email;
        $email['url_link'] = $urlavaliacao;

        echo json_encode($email);
    break;

}