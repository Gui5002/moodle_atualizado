<?php
require_once ('../../../config.php');
global $CFG, $DB, $USER;

$slcOrgan = isset($_REQUEST['slcOrgan']) ? $_REQUEST['slcOrgan'] : '';
$slcNome = isset($_REQUEST['slcNome']) ? $_REQUEST['slcNome'] : '';
$slcEixo = isset($_REQUEST['slcEixo']) ? $_REQUEST['slcEixo'] : '';
$slcPubAlvo = isset($_REQUEST['slcPubAlvo']) ? $_REQUEST['slcPubAlvo'] : '';
$slcStatus = isset($_REQUEST['slcStatus']) ? $_REQUEST['slcStatus'] : '';
$slcModalidade = isset($_REQUEST['slcModalidade']) ? $_REQUEST['slcModalidade'] : '';
$slcTema = isset($_REQUEST['slcTema']) ? $_REQUEST['slcTema'] : '';
$inpDateIni = isset($_REQUEST['inpDateIni']) ? $_REQUEST['inpDateIni'] : '';
$inpDateFim = isset($_REQUEST['inpDateFim']) ? $_REQUEST['inpDateFim'] : '';
$filtro = '';
$x = 0;

if ($slcOrgan !== '') {
    $filtro .= ' and ts.id_superior_organ = ' . $slcOrgan;
}

if ($slcNome !== '') {
    $filtro .= ' and mu.id = ' . $slcNome;
}

if ($slcEixo !== '') {
    $filtro .= " and ts.slc_priority_area_legal like '%" . $slcEixo . "%'";
}

if ($slcPubAlvo !== '') {
    $filtro .= " and ts.ds_target_audience like '%" . $slcPubAlvo . "%'";
}

if ($slcStatus !== '') {
    $filtro .= ' and ts.st_suggestion = ' . $slcStatus;
}

if ($slcModalidade !== '') {
    $filtro .= " and ts.slc_modality like '%" . $slcModalidade . "%'";
}

if ($slcTema !== '') {
    $filtro .= " and ts.ds_theme like '%" . $slcTema . "%'";
}

if ($inpDateIni !== '') {
    $filtro .= " and ts.dt_suggestion >= '" . $inpDateIni . "'";
}

if ($inpDateFim !== '') {
    $filtro .= " and ts.dt_suggestion <= '" . $inpDateFim . " 23:59:59'";
}

$sql = "select
            ts.id,
            concat(mu.firstname,' ',mu.lastname) as no_user,
            case
                when ts.id_slc_cargo = 1 then 'Membro das Carreiras Jurídicas da AGU'
                when ts.id_slc_cargo = 2 then 'Servidor administrativo em exercicio da AGU'
            end as slc_cargo,
            so.no_organ,
            ts.ds_theme,
            ts.slc_priority_area_legal,
            ts.slc_technical_legal,
            case
                when ts.id_slc_comp_ass = 0 then 'Não se aplica'
                when ts.id_slc_comp_ass = 1 then 'Gestão do desenvolvimento de pessoas'
                when ts.id_slc_comp_ass = 2 then 'Gestão da qualidade'
                when ts.id_slc_comp_ass = 3 then 'Liderança eficaz'
                when ts.id_slc_comp_ass = 4 then 'Gerenciamento de recursos'
                when ts.id_slc_comp_ass = 5 then 'Planejamento'
                when ts.id_slc_comp_ass = 6 then 'Relacionamento com dirigentes'
                when ts.id_slc_comp_ass = 7 then 'Resolução de problemas'
            end as slc_comp_ass,
            ts.ds_development_need,
            ts.ds_target_audience,
            ts.nu_participants,
            case
                when ts.id_slc_cargo = 1 then 'Apenas para este órgão ou unidade'
                when ts.id_slc_cargo = 2 then 'Atende a todos'
            end as ds_transversality,
            ts.ds_workload,
            ts.slc_modality,
            case
                when ts.id_slc_realizacao = 1 then 'a) realização interna, sem ônus, pela própria ESAGU'
                when ts.id_slc_realizacao = 2 then 'b) realização de parceria, sem ônus, com outra instituição'
                when ts.id_slc_realizacao = 3 then 'c) contração de instrutor servidor público federal'
                when ts.id_slc_realizacao = 4 then 'd) contratação de instrutor não servidor público federal'
                when ts.id_slc_realizacao = 5 then 'e) aquisição de vaga individual em evento ou curso de outra instituição.'
                when ts.id_slc_realizacao = 6 then 'f) contratação de turma fechada de outra instituição para a AGU (in company)'
            end as slc_realizacao,
            ts.no_institution_instructor as no_instructor,
            ts.nu_estimated_value,
            case
                when ts.st_suggestion = 0 then 'Cancelado'
                when ts.st_suggestion = 1 then 'Pendente'
                when ts.st_suggestion = 2 then 'Aprovado'
            end as st_suggestion,
            case
                when ts.slc_se_necessary = 0 then 'Não selecionado'
                when ts.slc_se_necessary = 1 then 'Sim'
            end as slc_se_necessary,
            DATE_FORMAT(ts.dt_suggestion, \"%d/%l/%Y %H:%i:%s\") AS dt_suggestion
        from mdl_eva_training_suggestion ts
        left join mdl_user mu on mu.id = ts.id_user
        left join mdl_eva_superior_organ so on so.id = ts.id_superior_organ
        where
            1=1 " . $filtro;
$rs = $DB->get_records_sql($sql);

$array = array();

$eixo = '';
$eixotecn = '';
$eixoT = '';
$modalidade = '';
$modal = '';

$eixojuri = '';
$opcoesEixo = [
    0 => 'Não aplicável',
    1 => 'Meios adequados de resolução de conflitos na administração pública',
    2 => 'Representação de agentes públicos pela AGU',
    3 => 'Proteção da probidade e combate à corrupção',
    4 => 'Direito digital, sigilo de dados e comunicação e direito à informação',
    5 => 'Recuperação de ativos',
    6 => 'Direito Administrativo Sancionador',
    7 => 'Proteção de políticas públicas',
    8 => 'Licitações, contratos, c onvênios',
    9 => 'Direito da saúde e judicialização da saúde',
    10 => 'Recursos e Sistema de Precedentes no Processo Civil',
    11 => 'Direito previdenciário e judicialização previdenciária',
    12 => 'Controle de constitucionalidade e processo constitucional',
    13 => 'Direito internacional',
    14 => 'Processo Tributário',
    15 => 'Direito Ambiental',
    16 => 'Defesa da Democracia',
    17 => 'Direito Regulatório',
    18 => 'Crimes Contra a Administração Pública e Assistência da Acusação',
    19 => 'Liderança, Competências Comportamentais, Comunicação e Gestão de Pessoas',
    20 => 'Transformação Digital, Inteligência Artificial e Law Design',
    21 => 'Governança, Gestão Pública, Gestão Estratégica e Auditoria Interna',
    22 => 'Tecnologia da Informação e Análise de dados',
    23 => 'Ética, Cidadania, Integridade e Transparência',
    24 => 'Educação e Gestão Corporativa',
    25 => 'Plano de logística sustentável e compras públicas',
    26 => 'Gestão orçamentária',
    27 => 'Diversidade e Gestão Inclusiva',
    28 => 'Sustentabilidade Ambiental'
];

if ($rs) {
    $resultSet = json_decode(json_encode($rs, JSON_UNESCAPED_UNICODE), true);

    foreach ($resultSet as $row) {
        $id = $row['id'];

        if (!empty($row['slc_priority_area_legal'])) {
            $eixojuri = '';
            $ej = explode(',', $row['slc_priority_area_legal']);

            foreach ($ej as $valor) {
                $chave = (int) trim($valor);
                if (array_key_exists($chave, $opcoesEixo)) {
                    $eixo = $opcoesEixo[$chave];
                    $eixojuri .= '<option value="' . htmlspecialchars($chave) . '">' . mb_strtoupper(htmlspecialchars($eixo)) . '</option>';
                }
            }
        }

        if ($row['slc_technical_legal'] !== '') {
            $et = explode(',', $row['slc_technical_legal']);

            for ($x = 0; $x < count($et); $x++) {
                $eixotecn = '';
                if ($et[$x] == 0) {
                    $eixoT = 'Não aplicável';
                } else if ($et[$x] == 38) {
                    $eixoT = 'Gestão de competências';
                } else if ($et[$x] == 39) {
                    $eixoT = 'Educação Corporativa';
                } else if ($et[$x] == 40) {
                    $eixoT = 'Designer industrial';
                } else if ($et[$x] == 41) {
                    $eixoT = 'Produção e edição de vídeo';
                }

                if ($x < 1) {
                    $eixotecn = $eixoT;
                } else {
                    $eixotecn .= '<br/><br/>' . $eixoT;
                }
            }
        }

        if ($row['slc_modality'] !== '') {
            $mo = explode(',', $row['slc_modality']);

            for ($z = 0; $z < count($mo); $z++) {
                if ($mo[$z] == 1) {
                    $modal = 'Presencial';
                } else if ($mo[$z] == 2) {
                    $modal = 'Transmissão interna ao vivo (teams)';
                } else if ($mo[$z] == 3) {
                    $modal = 'Sala de estudo virtual (moodle)';
                } else if ($mo[$z] == 4) {
                    $modal = 'Ciclo permanente de ações de treinamento a distância';
                }

                if ($z < 1) {
                    $modalidade = $modal;
                } else {
                    $modalidade .= '<br/><br/>' . $modal;
                }
            }
        }

        foreach ($row as $k => $v) {
            if ($k == 'slc_priority_area_legal') {
                continue;
            }

            if ($k == 'slc_technical_legal') {
                continue;
            }

            if ($k == 'slc_modality') {
                continue;
            }

            $btn = '';
            $btn .= '
            <button type="button" class="btn btn-primary" onclick="modalShow(' . $id . ');">
                Decisão
            </button>
            ';

            $array['slc_priority_area_legal'] = $eixojuri;
            $array['slc_technical_legal'] = $eixotecn;
            $array['slc_modality'] = $modalidade;
            $array[$k] = $v;
            $array['acoes'] = $btn;
        }

        $arr[] = $array;
    }

    echo json_encode($arr, JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode($arr, JSON_UNESCAPED_UNICODE);
}
