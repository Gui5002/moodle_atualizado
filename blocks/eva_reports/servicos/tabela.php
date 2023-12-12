<?php
require_once('../../../config.php');
require_once 'tabela_reports.php';

defined('MOODLE_INTERNAL') || die();


global $CFG, $DB, $USER;

$idReport = isset($_REQUEST['id'])    ? (integer)$_REQUEST['id'] : 0;
$filtro   = "";
$filtro2  = "";
$x        = 0;


//===============================RELATORIO 1 - CURSOS E CATEGORIAS - ========================================

if($idReport == 1){

    $relatorio1 = new tabela_reports();
    $rs = $relatorio1->get_curso_categoria();

    $array = array();
    $dataAtual = new DateTime();

    if($rs){
        $resultSet = json_decode(json_encode($rs,JSON_UNESCAPED_UNICODE),true);

        foreach($resultSet as $row){

            $row['inicio'] = date('d/m/Y', strtotime($row['inicio']));
            $row['fim'] = date('d/m/Y', strtotime($row['fim']));

            if(!empty($row['tcategoria'])){
                $row['categoria'] = $row['tcategoria'];
                $row['subcategoria'] = $row['scategoria'].'/'.$row['pcategoria'];
            } elseif (!empty($row['scategoria'])){
                $row['categoria'] = $row['scategoria'];
                $row['subcategoria'] = $row['pcategoria'];
            } else {
                $row['categoria'] = $row['pcategoria'];
                $row['subcategoria'] = $row['pcategoria'];
            }

            foreach($row as $k => $v){
                $array[$k] = $v;
            }

            $arr[] = $array;
        }

        echo json_encode($arr,JSON_UNESCAPED_UNICODE);
    }else{
        $arr = '';
        echo json_encode($arr,JSON_UNESCAPED_UNICODE);
    }


//============================RELATORIO 2 - RESULTADOS POR CURSOS - ========================================

}else if($idReport == 2){

    $relatorio2 = new tabela_reports();
    $rs = $relatorio2->get_resultado_por_curso();
    $arr = array();

    if ($rs) {
        $resultSet = json_decode(json_encode($rs, JSON_UNESCAPED_UNICODE), true);
        $array['progress'] = '';
        foreach ($resultSet as $row) {
            $array = array();

            $row['matricula'] = date('d/m/Y', strtotime($row['data_matricula']));
            $row['progresso'] = $row['progresso'].'%';
            $row['atv'] = $row['completas']." de ".$row['total'];

            foreach ($row as $k => $v) {
                $array[$k] = $v;
            }

            $arr[] = $array;
        }

        echo json_encode($arr, JSON_UNESCAPED_UNICODE);
    } else {
        $arr = array(); // Inicializa o array vazio
        echo json_encode($arr, JSON_UNESCAPED_UNICODE);
    }


//================================RELATORIO 3 - USUARIOS GERAL- ========================================

}else if($idReport == 3) {
    $relatorio4 = new tabela_reports();
    $rs = $relatorio4->get_curso_usuario();
    $array = array();

    if ($rs) {
        $resultSet = json_decode(json_encode($rs, JSON_UNESCAPED_UNICODE), true);

        foreach ($resultSet as $row) {
            $array = array();

            $progresso = $row['progresso'];

            $row['matricula'] = date('d/m/Y', strtotime($row['data_matricula']));

            if ($progresso == 0) {
                $row['status'] = "NÃO INICIADO";
            } elseif ($progresso == 100) {
                $row['status'] = "CONCLUÍDO!";
            } else {
                $row['status'] = "NÃO CONCLUÍDO";
            }


            foreach ($row as $k => $v) {
                $array[$k] = $v;
            }

            $arr[] = $array;
        }

        echo json_encode($arr, JSON_UNESCAPED_UNICODE);
    } else {
        $arr = array(); // Inicializa o array vazio
        echo json_encode($arr, JSON_UNESCAPED_UNICODE);
    }
}

