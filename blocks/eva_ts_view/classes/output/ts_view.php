<?php

namespace block_eva_ts_view\output;

defined('MOODLE_INTERNAL') || die();

use moodle_url;
use renderable;
use renderer_base;
use templatable;

class ts_view implements renderable, templatable {
    /**
     * Export this data so it can be used as the context for a mustache template.
     * @param \renderer_base $output
     * @return stdClass
     */

    public function export_for_template(renderer_base $output) {
        global $CFG,$PAGE,$DB;

        require_once($CFG->libdir . '/filelib.php');

        $data = new \stdClass();
        $text = '';

        $rs = $DB->get_records_sql('select
                                        distinct
                                        meso.id,
                                        upper(meso.no_organ) as no_organ
                                    from
                                        mdl_eva_superior_organ meso
                                    join
                                        mdl_eva_training_suggestion mets on mets.id_superior_organ = meso.id
                                    where
                                        meso.st_status = 1
                                    order by
                                        meso.no_organ asc');
        if($rs){
            $d = json_decode(json_encode($rs,JSON_UNESCAPED_UNICODE),true);

            foreach($d as $row){
                $data->rs .= '<div class="form-check ml-1">';
                $data->rs .= '<input class="form-check-input" type="checkbox" id="slcOrgan" name="slcOrgan[]" value="'. $row['id'] .'">';
                $data->rs .= '<label class="form-check-label" for="defaultCheck1">';
                $data->rs .= '<span>'. $row['no_organ'] .'</span>';
                $data->rs .= '</label>';
                $data->rs .= '</div>';
            }
        }

        $sql_user = "select
                        distinct
                        mets.id_user,
                        upper(concat(trim(mu.firstname),' ',trim(mu.lastname))) as name
                    from
                        mdl_eva_training_suggestion mets
                    join
                        mdl_user mu on mu.id = mets.id_user
                    order by
                        upper(concat(trim(mu.firstname),' ',trim(mu.lastname))) asc";
        $user = $DB->get_records_sql($sql_user);
        $u = json_decode(json_encode($user,JSON_UNESCAPED_UNICODE),true);

        foreach($u as $row){
            $data->user .= '<option value="'. $row['id'] .'">'. $row['name'] .'</option>';
        }

        $sql_RSALL = "select
        distinct
        upper(trim(mets.ds_target_audience)) as ds_target_audience
    from
        mdl_eva_training_suggestion mets
    order by
        upper(trim(mets.ds_target_audience)) asc";
        $rsAll = $DB->get_records_sql($sql_RSALL);
        $pa = json_decode(json_encode($rsAll,JSON_UNESCAPED_UNICODE),true);

        foreach ($pa as $row){
            $data->rsAll .= '<option value="'. $row['ds_target_audience'] .'">'. $row['ds_target_audience'] .'</option>';
        }

        $sql_RSALL2 = "select
        distinct
        upper(trim(mets.ds_theme)) as ds_theme
    from
        mdl_eva_training_suggestion mets
    order by
        upper(trim(mets.ds_theme)) asc";
        $rsAll2 = $DB->get_records_sql($sql_RSALL2);
        $t = json_decode(json_encode($rsAll2,JSON_UNESCAPED_UNICODE),true);

        foreach ($t as $row){
            $data->theme .= '<option value="'. $row['ds_theme'] .'">'. $row['ds_theme'] .'</option>';
        }

        $sql = 'select
            ts.id,
            concat(mu.firstname,\' \',mu.lastname) as no_user,
            case
                when ts.id_slc_cargo = 1 then \'Membro das Carreiras Jurídicas da AGU\'
                when ts.id_slc_cargo = 2 then \'Servidor administrativo em exercicio da AGU\'
            end as slc_cargo,
            so.no_organ,
            ts.ds_theme,
            ts.slc_priority_area_legal,
            ts.slc_technical_legal,
            case
                when ts.id_slc_comp_ass = 0 then \'Não se aplica\'
                when ts.id_slc_comp_ass = 1 then \'Gestão do desenvolvimento de pessoas\'
                when ts.id_slc_comp_ass = 2 then \'Gestão da qualidade\'
                when ts.id_slc_comp_ass = 3 then \'Liderança eficaz\'
                when ts.id_slc_comp_ass = 4 then \'Gerenciamento de recursos\'
                when ts.id_slc_comp_ass = 5 then \'Planejamento\'
                when ts.id_slc_comp_ass = 6 then \'Relacionamento com dirigentes\'
                when ts.id_slc_comp_ass = 7 then \'Resolução de problemas\'
            end as slc_comp_ass,
            ts.ds_development_need,
            ts.ds_target_audience,
            ts.nu_participants,
            case
                when ts.id_slc_cargo = 1 then \'Apenas para este órgão ou unidade\'
                when ts.id_slc_cargo = 2 then \'Atende a todos\'
            end as ds_transversality,
            ts.slc_modality,
            case
                when ts.id_slc_realizacao = 1 then \'a) realização interna, sem ônus, pela própria ESAGU\'
                when ts.id_slc_realizacao = 2 then \'b) realização de parceria, sem ônus, com outra instituição\'
                when ts.id_slc_realizacao = 3 then \'c) contração de instrutor servidor público federal\'
                when ts.id_slc_realizacao = 4 then \'d) contratação de instrutor não servidor público federal\'
                when ts.id_slc_realizacao = 5 then \'e) aquisição de vaga individual em evento ou curso de outra instituição.\'
                when ts.id_slc_realizacao = 6 then \'f) contratação de turma fechada de outra instituição para a AGU (in company)\'
            end as slc_realizacao,
            ts.no_institution_instructor as no_instructor,
            ts.nu_estimated_value,
            case
                when ts.st_suggestion = 0 then \'Cancelado\'
                when ts.st_suggestion = 1 then \'Pendente\'
                when ts.st_suggestion = 2 then \'Aprovado\'
            end as st_suggestion,
            case
                when ts.slc_se_necessary = 0 then \'Não selecionado\'
                when ts.slc_se_necessary = 1 then \'Sim\'
            end as slc_se_necessary,
            DATE_FORMAT(ts.dt_suggestion, "%d/%l/%Y %H:%i:%s") AS dt_suggestion
        from
            mdl_eva_training_suggestion ts
        left join
            mdl_user mu on mu.id = ts.id_user
        left join
            mdl_eva_superior_organ so on so.id = ts.id_superior_organ
        where
            1=1 ';
        $rs = $DB->get_records_sql($sql);

        if($rs){
            $resultSet = json_decode(json_encode($rs,JSON_UNESCAPED_UNICODE),true);
            $eixojuri = "";
            $eixo = "";
            $status = "";
            $modal = "";
            $modalidade = "";
            $x = 0;

            $opcoesEixo = [
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


            foreach($resultSet as $row){
                if (!empty($row['slc_priority_area_legal'])) {

                    $ej = explode(",", $row['slc_priority_area_legal']);

                    foreach ($ej as $valor) {
                        $chave = (int) trim($valor);
                        if (array_key_exists($chave, $opcoesEixo)) {
                            $eixo = $opcoesEixo[$chave];
                            $eixojuri .= '<option value="' . htmlspecialchars($chave) . '">' . mb_strtoupper(htmlspecialchars($eixo)) . '</option>';
                        }
                    }
                }

                if($row['cod_st_suggestion'] !== ""){
                    if($x < 0){
                        $status = $row['cod_st_suggestion'];
                    }else{
                        $status .= '|'.$row['cod_st_suggestion'];
                    }
                    $x++;
                }

                if($row['slc_modality'] !== ""){
                    $mo = explode(",", $row['slc_modality']);

                    for($z=0;$z<count($mo);$z++){
                        if($mo[$z] == 1){
                            $modal = "Presencial";
                        }else if($mo[$z] == 2){
                            $modal = "Transmissão interna ao vivo (teams)";
                        }else if($mo[$z] == 3){
                            $modal = "Sala de estudo virtual (moodle)";
                        }else if($mo[$z] == 4){
                            $modal = "Ciclo permanente de ações de treinamento a distância";
                        }

                        if($z < 1){
                            $modalidade = '<option value="'.$mo[$z].'">'.mb_strtoupper($modal).'</option>';
                        }else{
                            $modalidade .= '<option value="'.$mo[$z].'">'.mb_strtoupper($modal).'</option>';
                        }
                    }
                }
            }

            $st = explode("|", $status);
            $st = array_filter($st);
            $st = array_unique($st);
            $arST = "";
            $statusF = "";
            $y = 0;

            foreach($st as $s){
                if($s == "0"){
                    $arST = "CANCELADO";
                }else if($s == "1"){
                    $arST = "PENDENTE";
                }else if($s == "2"){
                    $arST = "APROVADO";
                }

                if($y < 0){
                    $statusF = '<option value="'.$s.'">'.$arST.'</option>';
                }else{
                    $statusF .= '<option value="'.$s.'">'.$arST.'</option>';
                }
                $y++;
            }

            $data->eixo = $eixojuri;
            $data->status = $statusF;
            $data->modalidade = $modalidade;
        }

        $data->wwwroot = $CFG->wwwroot;

        return $data;
    }

}
