<?php
//require_once ('../../config.php');
//require_once ('lib.php');

    global $DB;

//avaliador_tb_user_id=37&tb_barema_id=3&aluno=Celio%20Pereira%20Batalha%20Filho&aluno_tb_user_id=37&nt_faixa_1=20&nt_faixa_2=15&nt_faixa_3=15&nt_avaliador=50

//    $fildsbarema = [];
//    foreach ($_GET as $key => $value) {
//        $fildsbarema[$key] = $value;
//    }
//    $avaliacao = (object) $fildsbarema;
//
////    $avaliacao->tb_barema_id = $_POST['tb_barema_id'];
////    $avaliacao->avaliador_tb_user_id = $_POST['avaliador_tb_user_id'];
//
//
//    $tb_avaliador = $DB->get_record('eva_barema_avaliador', array('tb_barema_id'=>$avaliacao->tb_barema_id, 'avaliador_tb_user_id'=>$avaliacao->avaliador_tb_user_id));
//    $avaliacao->tb_avaliador_id = $tb_avaliador->id;
//    $avaliacao->data_avaliacao = date('Y-m-d h:i:sa');
//    $avaliacao->status = 1;
//
//    $dados = $DB->insert_record('eva_barema_avaliacao', $avaliacao);

    $retorno = "error";
    echo json_encode($retorno);

?>