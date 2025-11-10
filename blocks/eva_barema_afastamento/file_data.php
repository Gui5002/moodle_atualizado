<?php
require_once ('../../config.php');
require_once ('lib.php');

global $CFG, $DB, $PAGE, $USER;

$arquivo = $_FILES['arquivo'];

if ($arquivo['size'] > 35000000){
    $mensagem['msg'] = "error";
    $mensagem['mensagem'] = "Arquivo muito grande!! Max: 35MB";
    echo json_encode($mensagem);
    die();
}

$dadosfile['documentos'] = "doc_afastamentos_pdf/";
$dadosdb['nomeArquivo'] = $arquivo['name'];
$dadosfile['novoNomeArquivo'] = uniqid();
$dadosfile['extensao'] = strtolower(pathinfo($dadosdb['nomeArquivo'],PATHINFO_EXTENSION));

$dadosdb['pathfile'] = $dadosfile['documentos'] . $dadosfile['novoNomeArquivo'] . "." . $dadosfile['extensao'];

if ($dadosfile['extensao'] != "pdf") {
    $mensagem['msg'] = "error";
    $mensagem['mensagem'] = "Tipo de arquivo não aceito!";
    echo json_encode($mensagem);
    die();
}
    $file_salvo = move_uploaded_file($arquivo["tmp_name"], $dadosdb['pathfile']);

    if ($file_salvo) {
        $dadosdb['msg'] = "salvo";
        echo json_encode($dadosdb);
        die();
    }else{
        $mensagem['msg'] = "error";
        $mensagem['mensagem'] = "Arquivo falho";
        echo json_encode($mensagem);
        die();
    }