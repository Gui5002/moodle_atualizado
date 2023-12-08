<?php

namespace block_eva_training_suggestion\output;

defined('MOODLE_INTERNAL') || die();

use moodle_url;
use renderable;
use renderer_base;
use templatable;

class training_suggestion implements renderable, templatable {
    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return stdClass
     */

    public function export_for_template(renderer_base $output) {
        global $CFG,$PAGE,$DB;

        require_once($CFG->libdir . '/filelib.php');

        $data = new \stdClass();

        $ts_id = optional_param('id_ts', 0, PARAM_INT);
        $page_id = optional_param('id', 0, PARAM_INT);

        $rs = $DB->get_records('eva_superior_organ', ['st_status' => 1]);

        if($rs){
            $data->resultSet = $rs;
        }

        $sql = 'SELECT 
                id, 
                id_user, 
                id_superior_organ, 
                dt_suggestion,
                id_slc_cargo,
                st_suggestion, 
                ds_theme, 
                slc_priority_area_legal, 
                slc_technical_legal, 
                id_slc_comp_ass,
                ds_development_need, 
                ds_target_audience, 
                nu_participants, 
                ds_transversality, 
                ds_workload, 
                slc_modality, 
                no_institution_instructor, 
                nu_estimated_value,
                slc_se_necessary
            FROM 
                {eva_training_suggestion} 
            WHERE 
                st_suggestion = 1 
                AND id = ' . $ts_id;


        $rsAll = $DB->get_records_sql($sql);


        if($rsAll){
            $data->rsAll = $rsAll;
        }

        $data->title    = 'Levantamento de Necessidades de Capacitação';
        $data->subtitle = 'Dados do treinamento';
        $data->heading  = 'A Escola Superior da Advocacia-Geral da União (ESAGU) desenvolveu o Levantamento de Necessidades de Capacitação (LNC) para identificar as demandas de formação prioritárias dos membros e dos servidores técnicos administrativos da Advocacia-Geral da União, tendo como objetivo primordial o alinhamento das ações de desenvolvimento promovidas pela ESAGU com as diferentes áreas de atividade da instituição.';

        $data->ts = ((!empty($ts_id)) ? $data->ts = $ts_id : $data->ts = 0);
        $data->pageid = ((!empty($page_id)) ? $data->pageid = $page_id : $data->pageid = 0);

        foreach($rs as $row => $val){
            if($rsAll[$ts_id]->id_superior_organ == $val->id){
                $data->select1_op .= '<option value="'. $val->id .'" selected>'. $val->no_organ .'</option>';
            }else{
                $data->select1_op .= '<option value="'. $val->id .'">'. $val->no_organ .'</option>';
            }
        }

//      ================ Cargo : ============================
        $select_cargos = [
            '1' => 'Membro das Carreiras Jurídicas da AGU',
            '2' => 'Servidor administrativo em exercicio da AGU',
        ];
        $i = 0;
        foreach ($select_cargos as $key=>$select_cargo){
            $op_cargo[$i]['valor'] = $key;
            $op_cargo[$i]['cargo'] = $select_cargo;
            $op_cargo[$i]['selected_cargo'] = (($rsAll[$ts_id]->id_slc_cargo == "1") ? 'selected' : '');
            $i++;
        }
        $data->for_op = $op_cargo;

        $data->ds_theme = $rsAll[$ts_id]->ds_theme;

        $slc_priority_area_legal = explode(",",$rsAll[$ts_id]->slc_priority_area_legal);
//
//        for ($i = 0; $i <= 37; $i++) {
//            $data->checked1 = ((in_array($i, $slc_priority_area_legal)) ? "checked" : "");
//        }
//
//        $slc_technical_legal = explode(",",$rsAll[$ts_id]->slc_technical_legal);
//
//        for ($i = 0; $i <= 19; $i++) {
//            $data->checked2 = ((in_array($i, $slc_technical_legal)) ? "checked" : "");
//        }
//      ========================  Competência associada : ================================

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
        $i = 0;
        foreach ($competencias as $key=>$competencia){
            $op_compet[$i]['valor'] = $key;
            $op_compet[$i]['comp_asc'] = $competencia;
            $op_compet[$i]['selected_comp'] = (($rsAll[$ts_id]->id_slc_comp_ass == "1") ? 'selected' : '');
            $i++;
        }
        $data->for_comp = $op_compet;
*/
        $data->ds_development_need = $rsAll[$ts_id]->ds_development_need;
        $data->ds_target_audience = $rsAll[$ts_id]->ds_target_audience;
//        $data->nu_participants = $rsAll[$ts_id]->nu_participants;

//      ====================  Transversalidade : ======================================
//        $data->ds_transversality .= '<option value="1" '. (($rsAll[$ts_id]->ds_transversality == "1") ? "selected" : "") .'>Apenas para este órgão / unidade</option>';
//        $data->ds_transversality .= '<option value="2" '. (($rsAll[$ts_id]->ds_transversality == "2") ? "selected" : "") .'>Atende a todos</option>';

        $data->ds_workload .= $rsAll[$ts_id]->ds_workload;

//      =====================  Modalidade : ========================================
        $slc_modality = explode(",",$rsAll[$ts_id]->slc_modality);

        $data->checked3 = ((in_array("1",$slc_modality)) ? "checked" : "");
        $data->checked3 = ((in_array("2",$slc_modality)) ? "checked" : "");
        $data->checked3 = ((in_array("3",$slc_modality)) ? "checked" : "");
        $data->checked3 = ((in_array("4",$slc_modality)) ? "checked" : "");

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
        $i = 0;
        foreach ($realizacoes as $key=>$realizacoe){
            $op_realizacao[$i]['valor'] = $key;
            $op_realizacao[$i]['realizacoes'] = $realizacoe;
            $op_realizacao[$i]['selected_realizacao'] = (($rsAll[$ts_id]->id_slc_realizacao == "1") ? 'selected' : '');
            $i++;
        }
        $data->op_realizacao = $op_realizacao;
*/

        $data->no_institution_instructor = $rsAll[$ts_id]->no_institution_instructor;
        $data->nu_estimated_value = $rsAll[$ts_id]->nu_estimated_value;

        $slc_se_necessary = explode(",",$rsAll[$ts_id]->slc_se_necessary);
        $data->checked4 = ((in_array("1",$slc_se_necessary)) ? "checked" : "");

        $data->wwwroot = $CFG->wwwroot;




        return $data;
    }

}