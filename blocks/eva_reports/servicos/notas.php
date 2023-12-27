<?php
require_once('../../../config.php');

defined('MOODLE_INTERNAL') || die();

global $CFG, $PAGE, $DB, $USER;

$userid = isset($_REQUEST['id']) ? $_REQUEST['id'] : "";
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

$txt .= '<div style="background: rgba(0, 0, 0, 0.8);" class="modal fade" id="mymodal" tabindex="-1" role="dialog" aria-labelledby="mymodalTitle" aria-hidden="true">';
$txt .= '<div class="modal-dialog modal-dialog-centered" role="document">';
$txt .= '<div class="modal-content">';
$txt .= '<div class="modal-header">';
$txt .= '<h5 class="modal-title" id="exampleModalLongTitle">Notas</h5>';
$txt .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
$txt .= '<span aria-hidden="true">&times;</span>';
$txt .= '</button>';
$txt .= '</div>';
$txt .= '<div class="modal-body">';
if($rs){
    $d = json_decode(json_encode($rs,JSON_UNESCAPED_UNICODE),true);
    foreach($d as $key){
        $nome = $key['nome'];
        $curso = $key['curso'];
    }
    $txt .= '<div class="row">';
        $txt .= '<div class="col-md-12">';
            $txt .= '<span>Nome: '.$nome.'</span>';
        $txt .= '</div>';
    $txt .= '</div>';
    $txt .= '<div class="row">';
        $txt .= '<div class="col-md-12">';
            $txt .= '<span>Curso: '.$curso.'</span>';
        $txt .= '</div>';
    $txt .= '</div>';
}
$txt .= '<div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-5 table-responsive">
                    <table cellspacing="0" style="font-size: 1em; width: 100%;" id="tab_sug" name="tab_sug" class="table table-hover table-bordered">
                        <thead style="background: #185287; color: #fff;">
                            <tr>
                                <th style="width: 9%">Exame</th>
                                <th style="width: 9%">Escala nota</th>
                                <th style="width: 9%">Média</th>
                                <th style="width: 9%">Nota</th>
                                <th style="width: 9%">Porcentagem de acerto</th>
                            </tr>
                        </thead>
                        <tbody>';

if($rs){
    $d = json_decode(json_encode($rs,JSON_UNESCAPED_UNICODE),true);

    foreach($d as $row){
        $txt .=  '<tr>';
        $txt .=  '<td>'.$row['examname'].'</td>';
        $txt .=  '<td>'.$row['grademax'].'</td>';
        $txt .=  '<td>'.$row['pointstopass'].'</td>';
        $txt .=  '<td>'.$row['pointsobtained'].'</td>';
        $txt .=  '<td>'.$row['finalgradepercent'].'</td>';
        $txt .=  '</tr>';
    }    
}else{
    $txt .=  '<tr style="text-align: center;">';
    $txt .=  '<td colspan="5">Nenhum registro encontrato</td>';
    $txt .=  '</tr>';
}

$txt .= '
                        </tbody>
                    </table>
                </div>
            </div>';
$txt .= '</div>';
$txt .= '</div>';
$txt .= '</div>';
$txt .= '</div>';
echo $txt;
?>