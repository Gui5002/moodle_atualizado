<?php
require_once('../../config.php');
global $CFG, $DB, $USER;

// $userid = isset($_REQUEST['userid']) ? $_REQUEST['userid'] : 0;
$pageid = isset($_REQUEST['pageid']) ? $_REQUEST['pageid'] : 0;
$ts = isset($_REQUEST['ts']) ? $_REQUEST['ts'] : 0;
$eva_slc_cargo = isset($_REQUEST['eva_slc_cargo']) ? $_REQUEST['eva_slc_cargo'] : "";
$eva_slc_1 = isset($_REQUEST['eva_slc_1']) ? $_REQUEST['eva_slc_1'] : "";
//$eva_slc_2 = isset($_REQUEST['eva_slc_2']) ? $_REQUEST['eva_slc_2'] : 0;
$eva_txtarea_1 = isset($_REQUEST['eva_txtarea_1']) ? $_REQUEST['eva_txtarea_1'] : "";
$eva_txtarea_2 = isset($_REQUEST['eva_txtarea_2']) ? $_REQUEST['eva_txtarea_2'] : "";
//$slc_priority_area_legal = isset($_REQUEST['slc_priority_area_legal']) ? $_REQUEST['slc_priority_area_legal'] : "";
$slc_technical_legal = isset($_REQUEST['slc_technical_legal']) ? $_REQUEST['slc_technical_legal'] : "";
//$eva_slc_comp = isset($_REQUEST['eva_slc_comp']) ? $_REQUEST['eva_slc_comp'] : "";
$slc_modality = isset($_REQUEST['slc_modality']) ? $_REQUEST['slc_modality'] : "";
//$eva_slc_realizacao = isset($_REQUEST['eva_slc_realizacao']) ? $_REQUEST['eva_slc_realizacao'] : "";
$eva_input_1 = isset($_REQUEST['eva_input_1']) ? $_REQUEST['eva_input_1'] : "";
//$eva_input_2 = isset($_REQUEST['eva_input_2']) ? $_REQUEST['eva_input_2'] : "";
//$eva_input_3 = isset($_REQUEST['eva_input_3']) ? $_REQUEST['eva_input_3'] : "";
$eva_input_4 = isset($_REQUEST['eva_input_4']) ? $_REQUEST['eva_input_4'] : "";
//$eva_input_5 = isset($_REQUEST['eva_input_5']) ? $_REQUEST['eva_input_5'] : "";
$slc_se_necessary = isset($_REQUEST['slc_previsao']) ? $_REQUEST['slc_previsao'] : "";
$userid = $USER->id;
$error = 0;
$msg = "";
$mail = 0;

if($eva_slc_cargo == ""){
    $error = $error + 1;
    $msg .= "- Selecione o cargo.<br/>";
}

if($eva_slc_1 == ""){
    $error = $error + 1;
    $msg .= "- Selecione o órgão de direção superior.<br/>";
}

if($eva_txtarea_1 == ""){
    $error = $error + 1;
    $msg .= "- A descrição do tema não pode ficar em branco.<br/>";
}

//if($slc_priority_area_legal == ""){
//    $error = $error + 1;
//    $msg .= "- Selecione ao menos uma área prioritária do eixo jurídico.<br/>";
//}

if($eva_txtarea_2 == ""){
    $error = $error + 1;
    $msg .= "- A descrição da necessidade de desenvolvimento a ser atendida não pode ficar em branco.<br/>";
}

if($eva_input_1 == ""){
    $error = $error + 1;
    $msg .= "- O campo público-alvo é de preenchimento obrigatório.<br/>";
}

//if($eva_input_2 == ""){
//    $error = $error + 1;
//    $msg .= "- O campo número de participantes é de preenchimento obrigatório.<br/>";
//}

//if($eva_slc_2 == ""){
//    $error = $error + 1;
//    $msg .= "- Selecione a transversalidade.<br/>";
//}

//if($eva_input_3 == ""){
//    $error = $error + 1;
//    $msg .= "- Defina a carga horária da capacitação.<br/>";
//}

if($error > 0){
    $mail = 0;
    $arr = ["erros" => $error,"msg" => $msg,"email" => $mail];

    echo json_encode($arr,JSON_UNESCAPED_UNICODE);
}else{
    $table = 'eva_training_suggestion';

    if($ts == 0){
        //insert
        $objData = new \stdClass();

        // if($USER->id !== $userid){
        //     $mail = 0;
        //     $error++;
        //     $msg = "- Usuário passado não corresponde ao usuário logado. <br>Tente novamente.";
        //     $arr = ["erros" => 1,"msg" => $msg,"email" => $mail];

        //     echo json_encode($arr,JSON_UNESCAPED_UNICODE);
        // }else{
//        $slc_priority_area_legal = implode(",",$slc_priority_area_legal);
        $slc_technical_legal = implode(",",$slc_technical_legal);
        $slc_modality = implode(",",$slc_modality);
        $slc_se_necessary = implode(",",$slc_se_necessary);

        $objData->id_user = $userid;

        $confere = $DB->get_record('eva_superior_organ', ["id" => $eva_slc_1], $fields='*', IGNORE_MISSING);

        if($confere){
            $objData->id_superior_organ = $eva_slc_1;
        }else{
            $slc1 = $DB->insert_record('eva_superior_organ', ["no_organ" => $eva_slc_1], $returnid=true);
            $organ = $slc1;
            $objData->id_superior_organ = $organ;
        }

        $objData->id_slc_cargo = $eva_slc_cargo;
        $objData->ds_theme = $eva_txtarea_1;
//        $objData->slc_priority_area_legal = $slc_priority_area_legal;
        $objData->slc_technical_legal = $slc_technical_legal;
//        $objData->id_slc_comp_ass = $eva_slc_comp;
        $objData->ds_development_need = $eva_txtarea_2;
        $objData->ds_target_audience = $eva_input_1;
//        $objData->nu_participants = $eva_input_2;
//        $objData->ds_transversality = $eva_slc_2;
//        $objData->ds_workload = $eva_input_3;
        $objData->slc_modality = $slc_modality;
//        $objData->id_slc_realizacao = $eva_slc_realizacao;
        $objData->no_institution_instructor = $eva_input_4;
//        $objData->nu_estimated_value = $eva_input_5;
        $objData->slc_se_necessary = $slc_se_necessary;

        $insert = $DB->insert_record($table, $objData, $returnid=true);
        $ts = $insert;

        if($insert){
            $mail = 1;
            $msg = "Sua demanda foi registrada e será considerada para fins de levantamento ";
            $msg .= "de necessidades de capacitação. <br>A Escola da AGU agradece sua contribuição!";
            $arr = ["erros" => 0,"msg" => $msg,"email" => $mail,"id_ts" => $insert,"organ" => $objData->id_superior_organ,"userid" => $userid];

            echo json_encode($arr,JSON_UNESCAPED_UNICODE);
        }else{
            $mail = 0;
            $error++;
            $msg = "Não foi possível incluir o registro no sistema. <br>Tente novamente!";
            $arr = ["erros" => 1,"msg" => $msg,"email" => $mail];

            echo json_encode($arr,JSON_UNESCAPED_UNICODE);
        }
        // }
    }else{
        //update
        $objData = new \stdClass();
        $verifica = $DB->record_exists($table, ["id" => $ts]);

//        $slc_priority_area_legal = implode(",",$slc_priority_area_legal);
        $slc_technical_legal = implode(",",$slc_technical_legal);
        $slc_modality = implode(",",$slc_modality);
        $slc_se_necessary = implode(",",$slc_se_necessary);

        $objData->id = $ts;
        $objData->id_user = $userid;

        $confere = $DB->get_record('eva_superior_organ', ["id" => $eva_slc_1], $fields='*', IGNORE_MISSING);

        if($confere){
            $objData->id_superior_organ = $eva_slc_1;
        }else{
            $slc1 = $DB->insert_record('eva_superior_organ', ["no_organ" => $eva_slc_1], $returnid=true);
            $organ = $slc1;
            $objData->id_superior_organ = $organ;
        }

        $objData->id_slc_cargo = $eva_slc_cargo;
        $objData->ds_theme = $eva_txtarea_1;
//        $objData->slc_priority_area_legal = $slc_priority_area_legal;
        $objData->slc_technical_legal = $slc_technical_legal;
//        $objData->id_slc_comp_ass = $eva_slc_comp;
        $objData->ds_development_need = $eva_txtarea_2;
        $objData->ds_target_audience = $eva_input_1;
//        $objData->nu_participants = $eva_input_2;
//        $objData->ds_transversality = $eva_slc_2;
//        $objData->ds_workload = $eva_input_3;
        $objData->slc_modality = $slc_modality;
//        $objData->id_slc_realizacao = $eva_slc_realizacao;
        $objData->no_institution_instructor = $eva_input_4;
//        $objData->nu_estimated_value = $eva_input_5;
        $objData->slc_se_necessary = $slc_se_necessary;

        if($verifica){
            $mail = 1;
            $update = $DB->update_record($table, $objData);

            if($update){
                $msg = "Sua demanda foi atualizada e será considerada para fins de levantamento ";
                $msg .= "de necessidades de capacitação. <br>A Escola da AGU agradece sua contribuição!";
                $arr = ["erros" => 0,"msg" => $msg,"email" => $mail,"id_ts" => $ts,"organ" => $objData->id_superior_organ,"userid" => $userid];

                echo json_encode($arr,JSON_UNESCAPED_UNICODE);
            }else{
                $mail = 0;
                $error++;
                $msg = "Não foi possível atualizar o registro no sistema. <br>Tente novamente!";
                $arr = ["erros" => 1,"msg" => $msg,"email" => $mail];

                echo json_encode($arr,JSON_UNESCAPED_UNICODE);
            }
        }else{
            $mail = 0;
            $error++;
            $msg = "Registro não foi encontrado. <br>Não será possível a atualização!";
            $arr = ["erros" => 1,"msg" => $msg,"email" => $mail];

            echo json_encode($arr,JSON_UNESCAPED_UNICODE);
        }
    }
}