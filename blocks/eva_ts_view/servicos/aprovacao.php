<?php
require_once('../../../config.php');
global $CFG, $PAGE, $DB, $USER;

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : "";
$txt   = '';

$sql = 'select
            ts.id,
            concat(mu.firstname,\' \',mu.lastname) as no_user,
            so.no_organ,
            ts.nu_participants,
            ts.ds_transversality,
            ts.ds_workload,
            ts.no_institution_instructor,
            ts.ds_transversality,
            ts.dt_suggestion,
            ts.ds_theme,
            ts.slc_priority_area_legal,
            ts.slc_technical_legal,
            ts.ds_development_need,
            ts.ds_target_audience,
            ts.slc_modality,
            ts.nu_estimated_value,
            case
                when ts.st_suggestion = 0 then \'Cancelado\'
                when ts.st_suggestion = 1 then \'Pendente\'
                when ts.st_suggestion = 2 then \'Aprovado\'
            end as st_suggestion 
        from
            mdl_eva_training_suggestion ts
        left join 
            mdl_user mu on mu.id = ts.id_user 
        left join 
            mdl_eva_superior_organ so on so.id = ts.id_superior_organ
        where
            ts.id = ' . $id;

$rs = $DB->get_records_sql($sql);

$array = array();
$eixojuri = "";
$eixo = "";
$eixotecn = "";
$eixoT = "";
$modalidade = "";
$modal = "";

if($rs){
    $d = json_decode(json_encode($rs,JSON_UNESCAPED_UNICODE),true);
    foreach($d as $row){
        $arr = $row;
    }
    
    $data = str_replace("-", "/", $arr['dt_suggestion']);
    $dataSugg = date('d/m/Y h:i:s', strtotime($data));

    $txt .= '<input type="hidden" id="idAprovacao" name="idAprovacao" value="'. $id .'">';
    $txt .= '<div style="background: rgba(0,0,0,0.6);" class="modal fade" id="mymodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">';
        $txt .= '<div class="modal-dialog" role="document" style="width: 50% !important;">';
            $txt .= '<div class="modal-content">';
                $txt .= '<div class="modal-header">';
                    $txt .= '<h5 class="modal-title" id="exampleModalLabel">Aprovar / Cancelar sugestão de capacitação</h5>';
                    $txt .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                    $txt .= '<span aria-hidden="true">&times;</span>';
                    $txt .= '</button>';
                $txt .= '</div>';
                $txt .= '<div class="modal-body" style="width: 100%;">';
                    $txt .= '
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <span style="font-weight: 600; font-size: 1.5em;">Solicitante</span><br>
                            <span style="font-size: 1.3em;">'. (($arr['no_user'] !== "") ? $arr['no_user'] : "-") .'</span>
                            <br/><br/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <span style="font-weight: 600; font-size: 1.5em;">Data da solicitação</span><br>
                            <span style="font-size: 1.3em;">'. (($dataSugg !== "") ? $dataSugg : "-") .'</span>
                            <br/><br/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <span style="font-weight: 600; font-size: 1.5em;">Órgão de direção superior</span><br>
                            <span style="font-size: 1.3em;">'. (($arr['no_organ'] !== "") ? $arr['no_organ'] : "-") .'</span>
                            <br/><br/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <span style="font-weight: 600; font-size: 1.5em;">Tema do treinamento</span><br>
                            <span style="font-size: 1.3em;">'. (($arr['ds_theme'] !== "") ? $arr['ds_theme'] : "-") .'</span>
                            <br/><br/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <span style="font-weight: 600; font-size: 1.5em;">Articulação com área prioritária para treinamento da AGU - eixo jurídico</span><br>';
                            if($arr['slc_priority_area_legal'] !== ""){
                                $ej = explode(",", $row['slc_priority_area_legal']);
                    
                                for($i=0;$i<count($ej);$i++){
                                    if($ej[$i] == 0){
                                        $eixo = "- Não aplicável";
                                    }else if($ej[$i] == 1){
                                        $eixo = "- Combate à corrupção e recuperação de ativos";
                                    }else if($ej[$i] == 2){
                                        $eixo = "- Judicialização da saúde pública";
                                    }else if($ej[$i] == 3){
                                        $eixo = "- Mecanismos para resolver controvérsias e disputas em organizações internacionais";
                                    }

                                    if($i < 1){
                                        $eixojuri = $eixo;
                                    }else{
                                        $eixojuri = "<br>" . $eixo;
                                    }
                                }

                                $txt .= '<span style="font-size: 1.3em;">'. (($eixojuri !== "") ? $eixojuri : "-") .'</span>';
                            }        
                    $txt .= '<br/><br/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <span style="font-weight: 600; font-size: 1.5em;">Articulação com área prioritária para capacitação da AGU - eixo técnico-jurídico de gestão</span><br>';
                            if($row['slc_technical_legal'] !== ""){
                                $et = explode(",", $row['slc_technical_legal']);
                    
                                for($x=0;$x<count($et);$x++){
                                    if($et[$x] == 0){
                                        $eixoT = "- Não aplicável";
                                    }else if($et[$x] == 38){
                                        $eixoT = "- Gestão de competências";
                                    }else if($et[$x] == 39){
                                        $eixoT = "- Educação Corporativa";
                                    }else if($et[$x] == 40){
                                        $eixoT = "- Designer industrial";
                                    }else if($et[$x] == 41){
                                        $eixoT = "- Produção e edição de vídeo";
                                    }
                    
                                    if($x < 1){
                                        $eixotecn = $eixoT;
                                    }else{
                                        $eixotecn = "<br/>" . $eixoT;
                                    }
                                }

                                $txt .= '<span style="font-size: 1.3em;">'. (($eixotecn !== "") ? $eixotecn : "-") .'</span>';
                            }
                    $txt .= '<br/><br/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <span style="font-weight: 600; font-size: 1.5em;">Necessidade de desenvolvimento a ser atendida com a capacitação solicitada</span><br>
                            <span style="font-size: 1.3em;">'. (($arr['ds_development_need'] !== "") ? $arr['ds_development_need'] : "-") .'</span>
                            <br/><br/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <span style="font-weight: 600; font-size: 1.5em;">Público-alvo</span><br>
                            <span style="font-size: 1.3em;">'. (($arr['ds_target_audience'] !== "") ? $arr['ds_target_audience'] : "-") .'</span>
                            <br/><br/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <span style="font-weight: 600; font-size: 1.5em;">Número de participantes</span><br>
                            <span style="font-size: 1.3em;">'. (($arr['nu_participants'] !== "") ? $arr['nu_participants'] : "-") .'</span>
                            <br/><br/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <span style="font-weight: 600; font-size: 1.5em;">Solicitante</span><br>';
                            if($arr['ds_transversality'] !== ""){
                                if($arr['ds_transversality'] == 1){
                                    $txt .= '<span style="font-size: 1.3em;">Apenas para este órgão / unidade</span>';
                                }else{
                                    $txt .= '<span style="font-size: 1.3em;">Atende a todos</span>';
                                }
                            }
                    $txt .= '<br/><br/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <span style="font-weight: 600; font-size: 1.5em;">Carga horária</span><br>
                            <span style="font-size: 1.3em;">'. (($arr['ds_workload'] !== "") ? $arr['ds_workload'] : "-") .'</span>
                            <br/><br/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <span style="font-weight: 600; font-size: 1.5em;">Modalidade</span><br>';
                            if($row['slc_modality'] !== ""){
                                $mo = explode(",", $row['slc_modality']);
                                for($z=0;$z<count($mo);$z++){
                                    if($mo[$z] == 1){
                                        $modal = "- Presencial";
                                    }else if($mo[$z] == 2){
                                        $modal = "- Transmissão interna ao vivo (teams)";
                                    }else if($mo[$z] == 3){
                                        $modal = "- Sala de estudo virtual (moodle)";
                                    }else if($mo[$z] == 4){
                                        $modal = "- Ciclo permanente de ações de treinamento a distância";
                                    }
                    
                                    if($z < 1){
                                        $modalidade = $modal;
                                    }else{
                                        $modalidade = "<br/>" . $modal;
                                    }

                                    $txt .= '<span style="font-size: 1.3em;">'. (($modalidade !== "") ? $modalidade : "-") .'</span>';
                                }
                            }
                    $txt .= '<br/><br/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <span style="font-weight: 600; font-size: 1.5em;">Instituição / Instrutor</span><br>
                            <span style="font-size: 1.3em;">'. (($arr['no_institution_instructor'] !== "") ? $arr['no_institution_instructor'] : "-") .'</span>
                            <br/><br/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <span style="font-weight: 600; font-size: 1.5em;">Valor estimado</span><br>
                            <span style="font-size: 1.3em;">'. (($arr['nu_estimated_value'] !== "") ? "R$ ".$arr['nu_estimated_value'] : "-") .'</span>
                            <br/><br/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12" style="text-align: center;">
                            <span style="font-size: 1.1em;">Deseja APROVAR ou CANCELAR essa sugestão de capacitação?</span>
                            <br>
                            <select id="slcAprovar" name="slcAprovar">
                                <option value="2">Aprovar</option>
                                <option value="0">Cancelar</option>
                            </select>
                            <br><br>
                            <input class="btn btn-primary" id="btnAprovar" name="btnAprovar" value="Enviar decisão">
                            <br><br>
                        </div>
                    </div>
                    ';
                $txt .= '</div>';
            $txt .= '</div>';
        $txt .= '</div>';
    $txt .= '</div>';
    $txt .= '
    <script>
        $("#btnAprovar").on(\'click\',function(){
            let decisao = $("#slcAprovar").val();
            let idAprov = $("#idAprovacao").val();

            if(confirm("Deseja mesmo enviar esta decisão?")){
                $.ajax({
                    url: \''.$CFG->wwwroot.'/blocks/eva_ts_view/servicos/cadastrarDecisao.php\',
                    type: \'GET\',
                    dataType: \'text\',
                    data: {
                        decisao : decisao,
                        id: idAprov
                    },
                    success: function(response){
                        if(response == "ok"){
                            alert("Decisão registrada com sucesso!");
                            
                            $("#mymodal").modal("hide");
                            $("#mymodal").on("hidden.bs.modal", function () {
                                location.reload();
                            });
                        }
                    }
                });
            }
        });
    </script>
    ';

    echo $txt;
}