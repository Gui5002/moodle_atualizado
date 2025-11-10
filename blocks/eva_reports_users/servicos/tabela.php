<?php
require_once('../../../config.php');
require_once 'tabela_reports_users.php';

defined('MOODLE_INTERNAL') || die();

global $CFG, $DB, $USER;

$idReport = isset($_REQUEST['id'])    ? (integer)$_REQUEST['id'] : 0;



//===============================RELATORIO 1 - CURSOS E CATEGORIAS - ========================================

if($idReport == 1){

    $relatorio1 = new tabela_reports_users();
    $rs = $relatorio1->get_cursos_user();

    $array = array();

    if($rs){
        $resultSet = json_decode(json_encode($rs,JSON_UNESCAPED_UNICODE),true);


		foreach($resultSet as $row){

			if($row['progresso'] == 100 && $row['media_nota'] == null) {
				$row['mencao'] = 'CONCLUÍDO';
			} elseif ($row['progresso'] == 0 && $row['media_nota'] == null){
				$row['mencao'] = 'NÃO INICIADO';
			} elseif ($row['progresso'] < 100 && $row['media_nota'] == null) {
				$row['mencao'] = 'NÃO CONCLUÍDO';
			} elseif ($row['progresso'] >= 70 && $row['media_nota'] != null) {
				$row['mencao'] = 'CONCLUÍDO';
			} elseif ($row['progresso'] == 0 && $row['media_nota'] != null) {
				$row['mencao'] = 'NÃO INICIADO';
			} else {
				$row['mencao'] = 'NÃO CONCLUÍDO';
			}



			if ($row['media_nota'] == null){
				$row['nota'] = 'S/N';
			} else {
				$row['nota'] = $row['media_nota'];
			}

			if(!empty($row['codigo_ev'])){
				$declaracao = "<a href='". $CFG->wwwroot ."/mod/evadeclaration/wmsendfile.php?code=".$row['codigo_ev']."'><i title='Declaração' class='glyphicon ccn-flaticon-document fa-2x icon'></i></a>";
			} else {
				$declaracao = "<i title='1ª emissão no curso.' class='glyphicon ccn-flaticon-document fa-2x icon disabled'></i>";
			}

			if(!empty($row['codigo_sc'])){
				$certificado = "<a href='". $CFG->wwwroot ."/mod/simplecertificate/wmsendfile.php?code=" . $row['codigo_sc'] . "'><i title=' Emitir o Certificado' class='glyphicon flaticon-medal-1 fa-2x icon'></i></a>";
			} else {
				$certificado = "<i title='1ª emissão no curso.' class='glyphicon flaticon-medal-1 fa-2x icon disabled'></i>";
			}

			$row["opcoes"] = $declaracao.$certificado;



			$valores = explode(" ", $row['carga_horaria']);
			$row['carga'] = $valores[0].":".$valores[2].":00";

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

}

