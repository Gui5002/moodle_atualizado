<?php
require_once('../../../config.php');

defined('MOODLE_INTERNAL') || die();

global $CFG, $PAGE, $DB, $USER;

$userid = isset($_REQUEST['userid']) ? $_REQUEST['userid'] : "";
$courseid = isset($_REQUEST['courseid']) ? $_REQUEST['courseid'] : "";
$txt   = '';

$sql = 'select
            row_number() over(partition by upper(concat(trim(mu.firstname), \' \', trim(mu.lastname))) order by vueg.examname asc) as id,
            upper(concat(trim(mu.firstname),\' \',trim(mu.lastname))) as nome,
            mc.fullname as curso, 
            vueg.examname,
            truncate(vueg.grademax,2) as grademax,
            truncate(vueg.pointstopass,2) as pointstopass,
            truncate(vueg.pointsobtained,2) as pointsobtained,
            concat(convert(vueg.finalgradepercent,char),\'%\') as finalgradepercent
        from
            vw_users_exams_grades vueg
        left join
            mdl_user mu on mu.id = vueg.userid 
        left join
            mdl_course mc on mc.id = vueg.courseid 
        where 
            vueg.userid = '.$userid.' 
            and vueg.courseid = '.$courseid.'';
$rs = $DB->get_records_sql($sql);

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

?>