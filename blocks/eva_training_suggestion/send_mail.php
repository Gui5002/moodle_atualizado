<?php
require_once('../../config.php');
global $CFG, $DB, $USER;
require_once($CFG->dirroot . '/local/contact/classes/local_contact.php');
require_once ('lib.php');

$userid = isset($_REQUEST['userid']) ? $_REQUEST['userid'] : 0;
$pageid = isset($_REQUEST['pageid']) ? $_REQUEST['pageid'] : 0;
$ts = isset($_REQUEST['ts']) ? $_REQUEST['ts'] : 0;
$eva_slc_cargo = isset($_REQUEST['eva_slc_cargo']) ? $_REQUEST['eva_slc_cargo'] : 0;
$eva_slc_1 = isset($_REQUEST['eva_slc_1']) ? $_REQUEST['eva_slc_1'] : 0;
$eva_slc_2 = isset($_REQUEST['eva_slc_2']) ? $_REQUEST['eva_slc_2'] : 0;
$eva_txtarea_1 = isset($_REQUEST['eva_txtarea_1']) ? $_REQUEST['eva_txtarea_1'] : "";
$eva_txtarea_2 = isset($_REQUEST['eva_txtarea_2']) ? $_REQUEST['eva_txtarea_2'] : "";
$slc_priority_area_legal = isset($_REQUEST['slc_priority_area_legal']) ? $_REQUEST['slc_priority_area_legal'] : "";
$slc_technical_legal = isset($_REQUEST['slc_technical_legal']) ? $_REQUEST['slc_technical_legal'] : "";
//$eva_slc_comp = isset($_REQUEST['eva_slc_comp']) ? $_REQUEST['eva_slc_comp'] : 0;
$slc_modality = isset($_REQUEST['slc_modality']) ? $_REQUEST['slc_modality'] : "";
//$eva_slc_realizacao = isset($_REQUEST['eva_slc_realizacao']) ? $_REQUEST['eva_slc_realizacao'] : 0;
$eva_input_1 = isset($_REQUEST['eva_input_1']) ? $_REQUEST['eva_input_1'] : "";
//$eva_input_2 = isset($_REQUEST['eva_input_2']) ? $_REQUEST['eva_input_2'] : "";
//$eva_input_3 = isset($_REQUEST['eva_input_3']) ? $_REQUEST['eva_input_3'] : "";
$eva_input_4 = isset($_REQUEST['eva_input_4']) ? $_REQUEST['eva_input_4'] : "";
//$eva_input_5 = isset($_REQUEST['eva_input_5']) ? $_REQUEST['eva_input_5'] : "";
$slc_previsao = isset($_REQUEST['slc_previsao']) ? $_REQUEST['slc_previsao'] : "";

date_default_timezone_set("America/Sao_Paulo");
$date = date("d/m/Y h:i:sa");

// Message
$message = '
<html>
<head>
<title>Sugestão de capacitação</title>
</head>
<body style="margin: 2em;">
<div class="row" style="width: 100%;">
    <div style="text-align: center;">
        <h1>Nova solicitação de necessidades de capacitação</h1>
    </div>
</div>
<div class="row" style="width: 100%;">
    <span><b>Data e hora:</b>&nbsp;'.$date.'</span>
</div>
<div class="row" style="width: 100%;">
    <span><b>Solicitante:</b>&nbsp;'.$USER->firstname.' '.$USER->lastname.'</span>
</div>
<div class="row" style="width: 100%;">
    <span><b>Órgão:</b>&nbsp;'.(($USER->institution == "") ? "-" : $USER->institution).'</span>
</div>
<div class="row" style="width: 100%;">
    <span><b>Link da solicitação:</b>&nbsp;<a href="'.$CFG->wwwroot.'/mod/page/view.php?id='.$pageid.'&id_ts='.$ts.'">'.$CFG->wwwroot.'/mod/page/view.php?id='.$pageid.'&id_ts='.$ts.'</a></span>
</div>
<br>
<div class="row" style="width: 100%;background: gray;">
    <h3>Dados da capacitação</h3>
</div>
';

$sql = "select no_organ from {eva_superior_organ} where id = " . $eva_slc_1;
$rs = $DB->get_record_sql($sql);
$message .= '
<div class="row" style="width: 100%">
    <div style="width: 30%;float: left;"><b>Cargo:</b></div>
    <div style="width: 70%;float: right;">&nbsp;'.(($eva_slc_cargo == "1") ? "Membro das Carreiras Jurídicas da AGU" : "Servidor administrativo em exercicio da AGU").'</div>
</div><br><br>
<div class="row" style="width: 100%">
    <div style="width: 30%;float: left;"><b>Orgão de direção superior:</b></div>
    <div style="width: 70%;float: right;">&nbsp;'.$rs->no_organ.'</div>
</div><br><br>
<div class="row" style="width: 100%">
    <div style="width: 30%;float: left;"><b>Tema do treino:</b></div>
    <div style="width: 70%;float: right;">&nbsp;'.$eva_txtarea_1.'</div>
</div><br><br>';


$treinamentos = [
    0  => "Não aplicável",
    1  => "Meios adequados de resolução de conflitos na administração pública",
    2  => "Representação de agentes públicos pela AGU",
    3  => "Proteção da probidade e combate à corrupção",
    4  => "Direito digital, sigilo de dados e comunicação e direito à informação",
    5  => "Recuperação de ativos",
    6  => "Direito Administrativo Sancionador",
    7  => "Proteção de políticas públicas",
    8  => "Licitações, contratos, convênios",
    9  => "Direito da saúde e judicialização da saúde",
    10 => "Recursos e Sistema de Precedentes no Processo Civil",
    11 => "Direito previdenciário e judicialização previdenciária",
    12 => "Controle de constitucionalidade e processo constitucional",
    13 => "Direito internacional",
    14 => "Processo Tributário",
    15 => "Direito Ambiental",
    16 => "Defesa da Democracia",
    17 => "Direito Regulatório",
    18 => "Crimes Contra a Administração Pública e Assistência da Acusação",
    19 => "Liderança, Competências Comportamentais, Comunicação e Gestão de Pessoas",
    20 => "Transformação Digital, Inteligência Artificial e Law Design",
    21 => "Governança, Gestão Pública, Gestão Estratégica e Auditoria Interna",
    22 => "Tecnologia da Informação e Análise de dados",
    23 => "Ética, Cidadania, Integridade e Transparência",
    24 => "Educação e Gestão Corporativa",
    25 => "Plano de logística sustentável e compras públicas",
    26 => "Gestão orçamentária",
    27 => "Diversidade e Gestão Inclusiva",
    28 => "Sustentabilidade Ambiental"
];

$eixo_juridico = "";
$x = 0;
foreach($_REQUEST['slc_priority_area_legal'] as $row){
    if($x < 1){
        foreach ($treinamentos as $key=>$articulacoe) {
            if($row == $key){
                $eixo_juridico = $articulacoe;
            }
        }
    }else{
        foreach ($treinamentos as $key=>$articulacoe) {
            if($row == $key){
                $eixo_juridico .= ", ".$articulacoe;
            }
        }
    }
    $x++;
}
$message .= '
<div class="row" style="width: 100%">
    <div style="width: 30%;float: left;"><b>Articulação  com área prioritária para treinamento da AGU - eixo jurídico:</b></div>
    <div style="width: 70%;float: right;">&nbsp;'.$eixo_juridico.'</div>
</div><br><br><br>';

/*
$capacitacoes = [
        "0" => "Não se aplica",
        "38" => "Gestão por competências",
        "39" => "Educação corporativa",
        "40" => "Design instrucional",
        "41" => "Produção e edição de vídeos",
        "42" => "Design gráfico",
        "43" => "Transformação digital e inteligência artificial",
        "44" => "Inovação e tecnologia",
        "45" => "Ciência da informação e de dados",
        "46" => "Tecnologia da informação aplicada às atividades jurídicas",
        "47" => "Gestão pública e planejamento estratégico",
        "48" => "Liderança e gestão de pessoas",
        "49" => "Gestão de projetos",
        "50" => "Processo decisório",
        "51" => "Gestão orçamentária e financeira",
        "52" => "Governança e infraestrutura de rede",
        "53" => "Plano de logística sustentável",
        "54" => "Licitação e contratos",
        "55" => "Law design"
    ];

$tecnico_juridico = "";

$x = 0;
foreach($_REQUEST['slc_technical_legal'] as $row){
    if($x < 1){
        foreach ($capacitacoes as $key=>$capacitacoe) {
            if($row == $key){
                $tecnico_juridico = $capacitacoe;
            }
        }
    }else{
        foreach ($treinamentos as $key=>$capacitacoe) {
            if($row == $key){
                $tecnico_juridico .= ", ".$capacitacoe;
            }
        }
    }
    $x++;
}

$message .= '
<div class="row" style="width: 100%">
    <div style="width: 30%;float: left;"><b>Articulação  com área prioritária para formação da AGU - eixo de gestão técnico-jurídico:</b></div>
    <div style="width: 70%;float: right;">&nbsp;'.$tecnico_juridico.'</div>
</div><br><br><br>';
*/
$modalidade = "";
$x = 0;
foreach($_REQUEST['slc_modality'] as $row){
    if($x < 1){
        if($row == "1"){
            $modalidade = "Presencial";
        }else if($row == "2"){
            $modalidade = "Online";
        }else if($row == "3"){
            $modalidade = "Hibrido";
        }else if($row == "4"){
            $modalidade = "Transmissão ao vivo interna (teams)";
        }else if($row == "5"){
            $modalidade = "Sala de estudo virtual (moodle)";
        }else if($row == "6"){
            $modalidade = "Ciclo permanente de ações de treinamento a distância";
        }
    }else{
        if($row == "1"){
            $modalidade .= ", Presencial";
        }else if($row == "2"){
            $modalidade = ", Online";
        }else if($row == "3"){
            $modalidade = ", Hibrido";
        }else if($row == "4"){
            $modalidade .= ", Transmissão ao vivo interna (teams)";
        }else if($row == "5"){
            $modalidade .= ", Sala de estudo virtual (moodle)";
        }else if($row == "6"){
            $modalidade .= ", Ciclo permanente de ações de treinamento a distância";
        }
    }
    $x++;
}

$previsao = "";

foreach($_REQUEST['slc_previsao'] as $row){

    if($row == "1"){
        $previsao = "Previsão de necessidade de custeio de passagens e ou diárias para palestrantes ou participantes";
    }
}
/*
$competencias = [
    '0' => 'Não se aplica',
    '1' => 'Gestão do desenvolvimento de pessoas',
    '2' => 'Gestão da qualidade',
    '3' => 'Liderança eficaz',
    '4' => 'Gerenciamento de recursos',
    '5' => 'Planejamento',
    '6' => 'Relacionamento com dirigentes',
    '7' => 'Resolução de problemas',
];
foreach ($competencias as $key=>$competencia){
    if ($eva_slc_comp == $key) {
        $comp_assoc = $competencia;
    }
}

$message .= '

<div class="row" style="width: 100%">
    <div style="width: 30%;float: left;"><b>Previsão:</b></div>
    <div style="width: 70%;float: right;">&nbsp;'.$comp_assoc.'</div>
</div><br><br>';
*/


//      =========== Forma de realização sugerida : =========================
/*
$realizacoes = [
    '1' => 'a) realização interna, sem ônus, pela própria ESAGU',
    '2' => 'b) realização de parceria, sem ônus, com outra instituição',
    '3' => 'c) contração de instrutor servidor público federal',
    '4' => 'd) contratação de instrutor não servidor público federal',
    '5' => 'e) aquisição de vaga individual em evento ou curso de outra instituição.',
    '6' => 'f) contratação de turma fechada de outra instituição para a AGU (in company)',
];
foreach ($realizacoes as $key=>$realizacoe){
    if ($eva_slc_realizacao == $key) {
        $realiz = $realizacoe;
    }
}
*/

/*
    <div class="row" style="width: 100%">
        <div style="width: 30%;float: left;"><b>Realizaçoes :</b></div>
        <div style="width: 70%;float: right;">&nbsp;'.$realiz.'</div>
    </div><br><br>

    <div class="row" style="width: 100%">
        <div style="width: 30%;float: left;"><b>Número de participantes:</b></div>
        <div style="width: 70%;float: right;">&nbsp;'.$eva_input_2.'</div>
    </div><br><br>

    <div class="row" style="width: 100%">
        <div style="width: 30%;float: left;"><b>Tranvesalidade:</b></div>
        <div style="width: 70%;float: right;">&nbsp;'.(($eva_slc_2 == "1") ? "Apenas para este órgão / unidade" : "Atende a todos").'</div>
    </div><br><br>

    <div class="row" style="width: 100%">
        <div style="width: 30%;float: left;"><b>Carga de trabalho:</b></div>
        <div style="width: 70%;float: right;">&nbsp;'.$eva_input_3.'</div>
    </div><br><br>

    <div class="row" style="width: 100%">
        <div style="width: 30%;float: left;"><b>Valor estimado:</b></div>
        <div style="width: 70%;float: right;">R$&nbsp;'.$eva_input_5.'</div>
    </div><br><br>

*/
$message .= '

<div class="row" style="width: 100%">
    <div style="width: 30%;float: left;"><b>Necessidade de desenvolvimento a ser atendida com a capacitação solicitada:</b></div>
    <div style="width: 70%;float: right;">&nbsp;'.$eva_txtarea_2.'</div>
</div><br><br><br>
<div class="row" style="width: 100%">
    <div style="width: 30%;float: left;"><b>Público-alvo:</b></div>
    <div style="width: 70%;float: right;">&nbsp;'.$eva_input_1.'</div>
</div><br><br>

<div class="row" style="width: 100%">
    <div style="width: 30%;float: left;"><b>Modalidade:</b></div>
    <div style="width: 70%;float: right;">&nbsp;'.$modalidade.'</div>
</div><br><br>';

$message .= '
<div class="row" style="width: 100%">
    <div style="width: 30%;float: left;"><b>Instituição/instrutor:</b></div>
    <div style="width: 70%;float: right;">&nbsp;'.$eva_input_4.'</div>
</div><br><br>

<div class="row" style="width: 100%">
    <div style="width: 30%;float: left;"><b>Previsão de diárias e passagens ::</b></div>
    <div style="width: 70%;float: right;">&nbsp;'.$previsao.'</div>
</div><br><br>';

$message .= '
</body>
</html>
';

$contact = new local_contact();
$to = makeemailuser('eva@agu.gov.br', 'EVAGU');
$from = makeemailuser($USER->email, $USER->firstname, $USER->lastname, (int)$USER->id);
$subject = '[eva] Sugestão de capacitação';
$htmlmessage = format_text($message, FORMAT_HTML, array('trusted' => true, 'noclean' => true, 'para' => false));

$status = email_to_user($to, $from, $subject, html_to_text($htmlmessage), $htmlmessage, '', '', true, $from->email, $from->firstname);

if($status){
    echo 'ok';
}else{
    echo 'ferrou';
}


function makeemailuser($email, $name = '', $lastname = '', $id = -99) {
    $emailuser = new stdClass();
    $emailuser->email = trim(filter_var($email, FILTER_SANITIZE_EMAIL));
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailuser->email = '';
    }
    $emailuser->firstname = format_text($name, FORMAT_PLAIN, array('trusted' => false));
    $emailuser->lastname = format_text($lastname, FORMAT_PLAIN, array('trusted' => false));
    $emailuser->maildisplay = true;
    $emailuser->mailformat = 1; // 0 (zero) text-only emails, 1 (one) for HTML emails.
    $emailuser->id = $id;
    $emailuser->firstnamephonetic = '';
    $emailuser->lastnamephonetic = '';
    $emailuser->middlename = '';
    $emailuser->alternatename = '';
    $emailuser->username = '';
    return $emailuser;
}