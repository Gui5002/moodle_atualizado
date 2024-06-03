<?php
require_once('../../../config.php');
require_once 'tabela_reports.php';

defined('MOODLE_INTERNAL') || die();


global $CFG, $DB, $USER;

$idReport = isset($_REQUEST['id'])    ? (integer)$_REQUEST['id'] : 0;



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
            $row['progresso'] = $row['progresso'];
            $row['atv'] = $row['completas']." de ".$row['total'];

            if(!empty($row['tcategoria'])){
                $row['categoria'] = $row['tcategoria'];
                $row['subcategoria'] = $row['scategoria'].'/'.$row['pcategoria'];
            } elseif (!empty($row['scategoria'])){
                $row['categoria'] = $row['scategoria'];
                $row['subcategoria'] = $row['pcategoria'];
            } elseif ($row['scategoria'] == null){
                $row['categoria'] = $row['pcategoria'];
                $row['subcategoria'] = $row['pcategoria'];
            }

            $valores = explode(" ", $row['carga_horaria']);
            $row['carga'] = $valores[0].":".$valores[2].":00";

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
    $relatorio3 = new tabela_reports();
    $rs = $relatorio3->get_curso_usuario();
    $array = array();

    if ($rs) {
        $resultSet = json_decode(json_encode($rs, JSON_UNESCAPED_UNICODE), true);

        foreach ($resultSet as $row) {
            $array = array();

            $progresso = $row['progresso'];

            $row['matricula'] = date('d/m/Y', strtotime($row['data_matricula']));

            if ($progresso == 0) {
                $row['status'] = "N/I";
            } elseif ($progresso == 100) {
                $row['status'] = "CONCLUÍDO";
            } else {
                $row['status'] = "N/C";
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
    //================================RELATORIO 4 - USUARIOS GERAL- ========================================
} else if($idReport == 4){

    $relatorio4 = new tabela_reports();
    $rs = $relatorio4->get_consolidado_cursos();

    $array = array();
    $dataAtual = new DateTime();

    if($rs){
        $resultSet = json_decode(json_encode($rs,JSON_UNESCAPED_UNICODE),true);

        foreach($resultSet as $row){

            $row['criacao'] = date('d/m/Y', strtotime($row['criacao']));

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

} else if($idReport == 5){

    $relatorio5 = new tabela_reports();
	$rs = $relatorio5->get_consolidado_eva();

    $array = array();
    $cargaMinutoss = 0;
    $cargaHoras = 0;
    $cargaTotal = 0;
    $dataAtual = new DateTime();

    if($rs){
        $resultSet = json_decode(json_encode($rs,JSON_UNESCAPED_UNICODE),true);

        foreach($resultSet as $row){

            $valores = explode(" ", $row['carga_horaria']);
            $row['horas'] = $valores[0];
            $row['minutos'] = $valores[2];

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

} else if($idReport == 0){

	$relatorio2 = new tabela_reports();
	$rs = $relatorio2->get_conclusao();
	$arr = array();

	if ($rs) {
		$resultSet = json_decode(json_encode($rs, JSON_UNESCAPED_UNICODE), true);
		$array['progress'] = '';
		foreach ($resultSet as $row) {
			$array = array();

			$row['data'] = date('d/m/Y', strtotime($row['data_final']));
			$row['progresso'] = $row['progresso'];

			if(!empty($row['scategoria'])){
				$row['categoria'] = $row['scategoria'].'/'.$row['pcategoria'];
			} else{
				$row['categoria'] = $row['pcategoria'];
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


} else if ($_REQUEST['categoria']) {
	$categoria = $_REQUEST['categoria'];
	$query = new tabela_reports();
	$rs = $query->get_sub_categorias();

	if($rs){

		$resultSet = json_decode(json_encode($rs,JSON_UNESCAPED_UNICODE),true);

		foreach($resultSet as $row){

			foreach($row as $k => $v){
				$array[$k] = $v;
			}

			$arr[] = $array;
		}

		echo json_encode($arr,JSON_UNESCAPED_UNICODE);
	}else{
		$arr = '';
		echo json_encode(['error' => 'Erro na consulta'], JSON_UNESCAPED_UNICODE);
}


}

