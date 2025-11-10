<?php
require_once('../../../config.php');
global $CFG, $DB, $USER;

$decisao = isset($_REQUEST['decisao']) ? $_REQUEST['decisao'] : "";
$id      = isset($_REQUEST['id'])      ? $_REQUEST['id']      : "";
$table   = 'eva_training_suggestion';

if($decisao !== ""){
    $objData = new \stdClass();
    $objData->id = $id;
    $objData->st_suggestion = $decisao;
    $update = $DB->update_record($table, $objData);

    if($update){
        echo "ok";
    }
}else{
    echo "Nada ok";
}