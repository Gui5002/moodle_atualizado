<?php
require_once('../../../config.php');

defined('MOODLE_INTERNAL') || die();

global $CFG, $DB, $USER;

$idReport = isset($_REQUEST['id']) ? (integer)$_REQUEST['id'] : 0;
$filtro   = "";
$x        = 0;

if($idReport == 5){
    $filterUsers5 = isset($_REQUEST['filterUsers5']) ? $_REQUEST['filterUsers5'] : "";
        
    if(!empty($filterUsers5) and !is_null($filterUsers5)){
        $rs = $DB->get_records_sql("SELECT c.id,  upper(trim(c.fullname)) as fullname  
        FROM mdl_course c 
        JOIN mdl_enrol en ON en.courseid = c.id 
        JOIN mdl_user_enrolments ue ON ue.enrolid = en.id 
        WHERE ue.userid = ".$filterUsers5."");
        $array = array();

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
            echo json_encode($arr,JSON_UNESCAPED_UNICODE);
        }
    }
}else if($idReport == 6){
    $filterUsers6 = isset($_REQUEST['filterUsers6']) ? $_REQUEST['filterUsers6'] : "";
        
    if(!empty($filterUsers6) and !is_null($filterUsers6)){
        $rs = $DB->get_records_sql("SELECT c.id, upper(trim(c.fullname)) as fullname  
        FROM mdl_course c 
        JOIN mdl_enrol en ON en.courseid = c.id 
        JOIN mdl_user_enrolments ue ON ue.enrolid = en.id 
        WHERE ue.userid = ".$filterUsers6."");
        $array = array();

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
            echo json_encode($arr,JSON_UNESCAPED_UNICODE);
        }
    }
}
?>