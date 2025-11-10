<?php
require_once('../../../config.php');
global $CFG, $PAGE, $DB, $USER;

$search = isset($_REQUEST['q']) ? $_REQUEST['q'] : "";
$filtro = '';
$text   = '';

if($search == "" || empty($search)){
    $filtro = '1=1';
}else{
    $filtro = "no_organ like '%".$search."%'";
}

$rs = $DB->get_records_sql('SELECT * FROM {eva_superior_organ} WHERE st_status = 1 AND ' . $filtro);

if($rs){
    $d = json_decode(json_encode($rs,JSON_UNESCAPED_UNICODE),true);
    
    $text .= '<div id="checkOptions" name="checkOptions">';
    foreach($d as $row){
        $text .= '<div class="form-check ml-1">';
            $text .= '<input class="form-check-input" type="checkbox" id="slcOrgan" name="slcOrgan[]" value="'. $row['id'] .'">';
            $text .= '<label class="form-check-label" for="defaultCheck1">';
                $text .= '<span style="font-size: 1em;">'. $row['no_organ'] .'</span>';
            $text .= '</label>';
        $text .= '</div>';
    }
    $text .= '</div>';

    echo $text;
}else{
    $text .= '<div id="checkOptions" name="checkOptions">Nenhum registro encontrado</div>';
    echo $text;
}