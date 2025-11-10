<?php
require_once('../../../config.php');

defined('MOODLE_INTERNAL') || die();

global $CFG, $DB, $USER;

$idReport = isset($_REQUEST['id']) ? $_REQUEST['id'] : "";
$comando = isset($_REQUEST['comando']) ? $_REQUEST['comando'] : "";
$usasub = isset($_REQUEST['usasub']) ? $_REQUEST['usasub'] : "";
$filtro   = "";

if($idReport !== ""){
    if($idReport == "1"){
        if($comando == "1"){
            $category = isset($_REQUEST['category']) ? $_REQUEST['category'] : "";
            $filtro .= ' and tab.categoryname like \'' . $category . '\' ';

            $sql = 'select 
                        tab.subcategoryid,
                        tab.subcategoryname
                    from 
                        (
                        select
                            vcs.categoryname,
                            vcs.subcategoryid,
                            vcs.subcategoryname
                        from 
                            vw_category_subcategory vcs 
                        where 
                            vcs.subcategoryid is not null
                        union
                        select
                            vcs.categoryname,
                            vcs.sub_subcategoryid as subcategoryid,
                            vcs.sub_subcategoryname as subcategoryname
                        from 
                            vw_category_subcategory vcs 
                        where 
                            vcs.subcategoryid is not null
                        ) tab
                    where 
                        tab.subcategoryid is not null
                        '.$filtro.'
                    order by 
                        tab.subcategoryname asc';
                    
            $rs = $DB->get_records_sql($sql);
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
                $arr = array("id" => 0,"subcategory" => "");
                echo json_encode($arr,JSON_UNESCAPED_UNICODE);
            }
        }else if($comando == "2"){
            $category = isset($_REQUEST['category']) ? $_REQUEST['category'] : "";

            if($category !== ""){
                if($usasub !== ""){
                    $filtro .= ' and vcs.subcategoryname like \'' . $category . '\' or vcs.sub_subcategoryname like \''.$category.'\' ';
                }else{
                    $filtro .= ' and vcs.categoryname like \'' . $category . '\' ';
                }
            }else{
                $filtro = " and 1=1 ";
            }

            $sql = 'select
                        mc.id,
                        mc.fullname as course
                    from
                        {course} mc
                    left join
                        vw_course_category vcs on 
                        vcs.id = mc.id 
                    where
                        mc.visible = 1
                        '.$filtro.'
                    order by
                        mc.fullname asc';
            $rs = $DB->get_records_sql($sql);
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
                $arr = array("id" => 0,"course" => "");
                echo json_encode($arr,JSON_UNESCAPED_UNICODE);
            }
        }else if($comando == "3"){
            $category = isset($_REQUEST['category']) ? $_REQUEST['category'] : "";

            if($category !== ""){
                if($usasub !== ""){
                    $filtro .= ' and vcs.subcategoryname like \'' . $category . '\' or vcs.sub_subcategoryname like \''.$category.'\' ';
                }else{
                    $filtro .= ' and vcs.categoryname like \'' . $category . '\' ';
                }
            }else{
                $filtro = " and 1=1 ";
            }

            $sql = 'select
                        tab.status_curso
                    from
                        (
                        select
                            case 
                                when mc.enddate > UNIX_TIMESTAMP(now()) then \'ENCERRADO\'
                                when mc.enddate <= UNIX_TIMESTAMP(now()) then \'EM ANDAMENTO\'
                                when mc.startdate = 0 then \'EM ANDAMENTO\'
                                when mc.enddate = 0 then \'EM ANDAMENTO\'
                                when mc.visible = 0 then \'CANCELADO\' 
                            end as status_curso
                        from
                            {course} mc
                        left join vw_course_category vcs on
                            vcs.id = mc.id
                        where
                            mc.visible = 1
                            '.$filtro.'
                        ) tab
                    group by 
                        tab.status_curso
                    order by
                        tab.status_curso asc';
            $rs = $DB->get_records_sql($sql);
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
                $arr = array("status_curso" => "");
                echo json_encode($arr,JSON_UNESCAPED_UNICODE);
            }
        }
    }
}