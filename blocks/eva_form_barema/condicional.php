<?php
global $CFG, $DB, $PAGE, $USER;
/**
 * @author Celio Pereira Batalha
 * email: celio.batalha@gmail.com
 */
require_once('classes/privacy/pos_contact.php');
require_once ('../../config.php');
require_once ('lib.php');


$acao = ($_POST['acao'] ? $_POST['acao'] : $_GET['acao']);

switch ($acao) {
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
//
    case 'adicionarvaliador':
        $avaliadores =  $_GET;
        $existe = $DB->record_exists('eva_barema_avaliadores', array('avaliador_id'=>$avaliadores['avaliador_id']));
        if (!$existe){
            $avaliador = $DB->insert_record('eva_barema_avaliadores', $avaliadores);
        }
        echo json_encode($existe);
    break;
//
    case 'editavaliadores':

        $id = $_GET['id'];
        $coluna_user_id = $_GET['coluna_user_id'];
        $user_id = $_GET['user_id'];
        $eixo = $_GET['eixo'];

        $retorno = $DB->update_record_raw('eva_barema_avaliadores', array('id'=>$id, $coluna_user_id=>$user_id));

        if ($retorno){
            $message['msg'] = "Avaliador alterado com sucesso!";
        }else{
            $message['msg'] = "Algo deu errado...!";
        }
        echo json_encode($message);

    break;
//
    case 'mudarstatus':
        $avaliador = "";
        $avaliador_id = $_GET['id'];
        if ($_GET['status'] == 'suspend') {
            $avaliador['status'] = 0;
        }else{
            $avaliador['status'] = 1;
        }

        $sql = "SELECT * FROM mdl_eva_barema_avaliador WHERE avaliador_tb_user_id ='{$avaliador_id}' AND status = 0 AND flag = 0 AND ativo > 0";
        $qtd_avaliacao = $DB->get_records_sql($sql);
        $cont = count($qtd_avaliacao);

        if ($cont === 0){
            $id_table_avaliador = $DB->get_record('eva_barema_avaliadores', array('avaliador_id'=>$avaliador_id), 'id');
            $resposta = $DB->update_record('eva_barema_avaliadores', array('id'=>$id_table_avaliador->id, 'status'=>$avaliador['status']));
            if ($resposta){
                if ($avaliador['status'] == 1) {
                    $message['status'] = '1';
                }else{
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
//
    case 'emailpendente':
        $tb_avaliador_id = $_GET['id'];
        $dados = $DB->get_records('eva_barema_avaliador', array('id'=>$tb_avaliador_id));
//
        foreach ($dados as $dado){
            $nome_curso = $DB->get_field('course', 'fullname', array('id'=>$dado->tb_curso_id));
            $nome_quiz = $DB->get_field('quiz', 'name', array('id'=>$dado->tb_atividade_id));
            $sql = "SELECT fullname, email FROM vw_autocomplete_user WHERE id = '{$dado->avaliador_tb_user_id}'";
            $user_name = $DB->get_record_sql($sql);
            $urlavaliacao = $CFG->wwwroot.'/blocks/eva_form_barema/gerencia.php?avaliador='.$dado->avaliador_tb_user_id;
            $qt_alunos = $dado->qtd_alunos - $dado->qtd_avaliados;
        }
//
        $email['id_tb_avaliador'] = $tb_avaliador_id;
        $email['avaliador'] = $user_name->fullname;
        $email['curso'] = $nome_curso;
        $email['quiz'] = $nome_quiz;
        $email['email'] = $user_name->email;
        $email['qt_alunos'] = $qt_alunos;
        $email['url_link'] = $urlavaliacao;

        echo json_encode($email);
    break;
//
    case 'preparandoSubstituicao':
        $id_atrib = $_GET['id'];
        $dados = $DB->get_records('eva_barema_avaliador', array('id'=>$id_atrib));

        foreach ($dados as $dado){
            $sql = "SELECT fullname, email FROM vw_autocomplete_user WHERE id = '{$dado->avaliador_tb_user_id}'";
            $user_name = $DB->get_record_sql($sql);
            $nome_curso = $DB->get_field('course', 'fullname', array('id'=>$dado->tb_curso_id));

            $atual['avaliador'] = $user_name->fullname;
            $atual['curso'] = $nome_curso;
            $atual['total_alunos'] = $dado->qtd_alunos;
            $atual['qtd_avaliados'] = $dado->qtd_avaliados;
            $atual['url_link'] = $CFG->wwwroot.'/blocks/eva_afastamento_bolsa/avaliacao.php?gerenciar='. md5('userid='.$dado->avaliador_tb_user_id);
        }

        echo json_encode($atual);
    break;
//
//    case 'verificastatus':
//        $id_user = $_GET['id'];
//        $atribuicao_id = $_GET['idatribuicao'];
////        $idanteprojeto = $DB->get_field('eva_afastamento_atribuicao', 'tb_anteprojeto_id', array('id'=>$atribuicao_id, 'status'=>0));
//        $anteprojetos = $DB->get_records('eva_afastamento_atribuicao', array('id'=>$atribuicao_id, 'status'=>0));
//        $compareanteprojetos = $DB->get_records('eva_afastamento_atribuicao', array('avaliador_tb_user_id'=>$id_user, 'status'=>0, 'flag'=>0));
//
//
//        $anteprojetoExiste = false;
//        foreach ($anteprojetos as $data){
//            $eixoateprojeto = $DB->get_field('eva_afastamento_anteprojeto', 'distribuicao', array('id'=>$data->tb_anteprojeto_id));
//
//            foreach ($compareanteprojetos as $compare){
//                if (($compare->tb_anteprojeto_id == $data->tb_anteprojeto_id)){
//                    $anteprojetoExiste = true;
//                }
//            }
//            $eixoavaliador = $DB->get_field('eva_afastamento_avaliadores', 'eixo', array('avaliador_id'=>$id_user));
//
//        }
//
//
//
//        if ($anteprojetoExiste){
//            $status['status'] = 'restric';
//        }else{
//            if ($eixoateprojeto == 'g'){
//                $status['status'] = true;
//            }else if ($eixoateprojeto == 'j' && $eixoavaliador == 'j'){
//                $status['status'] = true;
//            }else if ($eixoateprojeto == 'j' && $eixoavaliador == 'g'){
//                $status['status'] = false;
//            }
//        }
//            echo json_encode($status);
//    break;
//
    case 'substituicao':
        $data = date("Y-m-d");
        $ava_id = $_GET['id'];
        $idatribuicao = $_GET['idatribuicao'];

        $dadosAtribuicao = $DB->get_records('eva_barema_avaliador', array('id'=>$idatribuicao));

        foreach ($dadosAtribuicao as $atrib) {
            $subst_total_alunos = ($atrib->qtd_alunos - $atrib->qtd_avaliados);


            $array_substituto[] = array(
                'tb_barema_id' => $atrib->tb_barema_id,
                'avaliador_tb_user_id' => $ava_id,
                'tb_categoria_id' => $atrib->tb_categoria_id,
                'tb_subcategoria_id' => $atrib->tb_subcategoria_id,
                'tb_curso_id' => $atrib->tb_curso_id,
                'tb_atividade_id' => $atrib->tb_atividade_id,
//                'qtd_avaliados' => $atrib->qtd_avaliados,
                'qtd_alunos' => $subst_total_alunos,
                'barema_modelo' => $atrib->barema_modelo,
                'url_avaliacao' => '/blocks/eva_form_barema/barema_avaliacao.php?barema_id='.$atrib->tb_barema_id.'&avaliador_id='.$ava_id.'&curso_id='.$atrib->tb_curso_id.'&quiz_id='.$atrib->tb_atividade_id,
                'data_atribuicao' => date('Y-m-d h:i:sa')
            );

            $qtd_alunos_substituido = $atrib->qtd_avaliados;

        }

        $DB->insert_records('eva_barema_avaliador', $array_substituto);

        if ($qtd_alunos_substituido == 0){
            $retorno = $DB->delete_records('eva_barema_avaliador', array('id'=>$idatribuicao));
        }else{
            $retorno = $DB->update_record('eva_barema_avaliador', array('id'=>$idatribuicao, 'qtd_alunos'=>$qtd_alunos_substituido, 'status'=>1));
        }

        $dadosListaAlunos = $DB->get_records('eva_barema_alunos', array('tb_avaliador_id'=>$idatribuicao));
        $id_tbavaliador = $DB->get_record_sql("SELECT id, avaliador_tb_user_id FROM mdl_eva_barema_avaliador ORDER BY id DESC ");
        foreach ($dadosListaAlunos as $listAlunos){
            $DB->update_record('eva_barema_alunos', array('id'=>$listAlunos->id, 'tb_avaliador_id'=>$id_tbavaliador->id, 'avaliador_id'=>$id_tbavaliador->avaliador_tb_user_id));
        }

        $arrayRetorno['status'] = $retorno;
        $arrayRetorno['tbavaliadorid'] = $id_tbavaliador->id;

        echo json_encode($arrayRetorno);
    break;

//
    case 'emailparasubstituido':
        $tb_avaliador_id = $_GET['tb_avaliador_id'];
        $dados = $DB->get_records('eva_barema_avaliador', array('id'=>$tb_avaliador_id));

        // $mensagem = "\n\n".'Prezado Avaliador,'."\n\n".'Devido a sua situação voce foi substituído dessa avaliação.'."\n".'Clique no link abaixo e visualize as avaliações pendentes.'."\n";
        $mensagem = "\n\n".'Prezado Avaliador,'."\n\n".'o senhor foi selecionado para corrigir as Avaliações de Aprendizagem que estavam a cargo de outro professor. '."\n".'Clique no link abaixo para iniciar o Barema e a correção das Avaliações de Aprendizagem.'."\n";
        foreach ($dados as $dado){
            $urlavaliacao = $CFG->wwwroot.'/blocks/eva_form_barema/gerencia.php?avaliador='. $dado->avaliador_tb_user_id;
            $avaliador_id = $dado->avaliador_tb_user_id;
            $baremaCurso = $dado;
        }

        $retorno_email =  enviar_email_para_substituicao($avaliador_id, $baremaCurso, $urlavaliacao, $mensagem);

    echo json_encode($retorno_email);
    break;

    case 'comautorizacao':

        $id_atribuicao = $_GET['idatribuicao'];
        $id_aluno = $_GET['idaluno'];

        $idtbalunos = $DB->get_record('eva_barema_alunos', array('tb_avaliador_id'=>$id_atribuicao, 'alunos_id'=>$id_aluno), 'id');
        $update = $DB->update_record('eva_barema_alunos', array('id'=>$idtbalunos->id, 'prazo'=>1, 'datafinish'=>null));
//
//
    echo json_encode($update);
    break;
    case 'semautorizacao':

        $id_atribuicao = $_GET['idatribuicao'];
        $id_aluno = $_GET['idaluno'];

        $idtbalunos = $DB->get_record('eva_barema_alunos', array('tb_avaliador_id'=>$id_atribuicao, 'alunos_id'=>$id_aluno), 'id');
        $update = $DB->update_record('eva_barema_alunos', array('id'=>$idtbalunos->id, 'prazo'=>-10, 'datafinish'=>null));
//
//
        echo json_encode($update);
        break;
//
//    case 'emailparasubstituto':
//        $id_tb_atribuicao = $_GET['id_tb_atribuicao'];
//        $substitutoid = $_GET['idsubstituto'];
//        $dados = $DB->get_records('eva_afastamento_atribuicao', array('id'=>$id_tb_atribuicao, 'flag'=>1));
//
//        $mensagem = "\n\n".'Prezado Avaliador,'."\n\n".'Você tem uma nova avaliação referente ao edital de afastamentos de estudo.'."\n".'Clique no link abaixo e visualize as avaliações pendentes.'."\n";
//        foreach ($dados as $dado){
//            $urlavaliacao = $CFG->wwwroot.'/blocks/eva_form_barema/avaliacao.php?gerenciar='. md5('userid='.$dado->avaliador_tb_user_id);
//            $avaliador_id = $substitutoid;
//            $anteprojeto_id = $dado->tb_anteprojeto_id;
//        }
//
//        $retorno_email =  enviar_email_para_substituicao($avaliador_id, $anteprojeto_id, $urlavaliacao, $mensagem);
//
//        echo json_encode($retorno_email);
//    break;
//
//    case 'infoSubstituicao':
//        $id_atribuicao = $_GET['id_atribuicao'];
//
//        $dadossubstituto = $DB->get_records('eva_afastamento_atribuicao', array('id'=>$id_atribuicao));
//        $dadossubstituido = $DB->get_records('eva_afastamento_substituicao', array('tb_atribuicao_id'=>$id_atribuicao));
//        foreach ($dadossubstituto as $substituto){
//            $fullname = pegando_nome_completo($substituto->avaliador_tb_user_id);
//            $info['avaliador_substituto'] = $fullname;
//            $info['qt_dia_substituto'] = $substituto->qt_dia_avaliacao;
//            $info['data_substituto'] = $substituto->data_ini_avaliacao;
//        }
//        foreach ($dadossubstituido as $substituido){
//            $fullname = pegando_nome_completo($substituido->avaliador_substituido);
//            $info['avaliador_substituido'] = $fullname;
//            $info['qt_dia_substituido'] = $substituido->qt_dia_substituido;
//            $info['data_substituido'] = $substituido->data_substituido;
//        }
//
//
//
//        echo json_encode($info);
//    break;
}

function enviar_email_para_substituicao($idavaliador, $baremaCurso, $link_avalaidor){
    global $DB, $USER;
    $name = '';

    $sql = "SELECT fullname, email FROM vw_autocomplete_user WHERE id ='{$idavaliador}'";
    $avaliador = $DB->get_record_sql($sql);

    $curso = $DB->get_field('course', 'fullname', array('id'=>$baremaCurso->tb_curso_id));
    $atividade = $DB->get_field('quiz', 'name', array('id'=>$baremaCurso->tb_atividade_id));


    $contact_afastamento = new pos_contact();
    unset($_POST);


    $_POST['avaliador'] = $avaliador->fullname;
    $_POST['curso'] = $curso;
    $_POST['atividade'] = $atividade;
    $_POST['message'] = 'Click no link abaixo para Iniciar o Barema de avaliações dos alunos';
    $_POST['link'] = $link_avalaidor;


//    $from_email_nome['email'] = "eagu.bolsasdeestudo@agu.gov.br";
//    $from_email_nome['nome'] = "EAGU - Barema de Pos-Graduação";
//    $from_email_nome['subtitle'] = "Avaliador, você tem uma nova avaliação.";


    $envio = $contact_afastamento->sendmessage($avaliador->email, $name, null , null);

    return $envio;
//    return $_POST;
}

function pegando_nome_completo($idnome){
    global $DB;
    $sql = "SELECT fullname, email FROM vw_autocomplete_user WHERE id = '{$idnome}'";
    $user_name = $DB->get_record_sql($sql);

    return $user_name->fullname;
}