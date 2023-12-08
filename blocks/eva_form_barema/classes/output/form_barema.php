<?php

namespace block_eva_form_barema\output;

defined('MOODLE_INTERNAL') || die();

//require_once ('classes/output/criarbarema.php');
use moodle_url;
use renderable;
use renderer_base;
use templatable;

class form_barema implements renderable, templatable {

    var $config;
    var $context;

    public function __construct($config, $context) {
        $this->config = $config;
        $this->context = $context;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return stdClass
     */

    public function situacaodoaluno(){

        global $DB, $USER;

        $idcurse = trim($_GET['curso_id']);
        $idquiz = trim($_GET['quiz_id']);
        $idestudante = trim($_GET['aluno_id']);

        $sql = "SELECT id from vw_autocomplete_user where id = '{$idestudante}'";
        $existealuno =  $DB->record_exists_sql($sql);


        if ($existealuno) {
            //============== verifica se aluno ja foi avaliado ====================================

            $id_existes = $DB->get_records('eva_barema_avaliacao', array('aluno_tb_user_id'=>$idestudante));
            $podeavaliar = true;

            if ($id_existes) {

                foreach ($id_existes as $idexiste) {

                    $tb_avaliador = $DB->get_record('eva_barema_avaliador', array('id'=>$idexiste->tb_avaliador_id));
                    //======== Verificar se o avaliador logado e a atividade autal é o mesmo que avaliou o aluno e se o status esta true====
                    //===O aluno so pode ser avaliador uma vez nesse curso e atividade !!!==========

                    $repetir_avaliacao = $DB->get_record('eva_barema_avaliacao', array('tb_avaliador_id'=>$tb_avaliador->id, 'aluno_tb_user_id'=>$idexiste->aluno_tb_user_id));
                    if ($tb_avaliador->tb_curso_id == $idcurse && $tb_avaliador->tb_atividade_id == $idquiz && ($repetir_avaliacao->flag == 1)) {
                        //=== esse aluno pode ser avaliado denovo caso a flag for = 1 =======
                        $podeavaliar = true;

                    }else if ($tb_avaliador->tb_curso_id == $idcurse && $tb_avaliador->tb_atividade_id == $idquiz) {
                        //===esse aluna ja foi avaliado com esses parametros de courso e de quiz ===
                        $podeavaliar = false;
                    }
                }

                if ($podeavaliar) {

                    $sql = "SELECT qz.id, qz.quiz, qz.userid, qu.questionsummary, qu.rightanswer, qu.responsesummary FROM mdl_quiz_attempts qz 
                        INNER JOIN mdl_question_attempts qu ON qz.uniqueid = qu.questionusageid
                        WHERE qz.quiz = '{$idquiz}' AND qz.userid = '{$idestudante}'";
                    $dados = $DB->get_records_sql($sql);
                    $reposta = $DB->get_field('eva_barema_resposta_padrao', 'resposta', array('tb_quiz_id'=>$idquiz));
                    foreach ($dados as $key=>$dado){
                        $resposta["question"] = $dado->questionsummary;
                        $resposta["responsecorreta"] = $reposta;
                        $resposta["response"] = $dado->responsesummary;
                    }
                    return $resposta;
                }else{
                    $resposta["status"] = $idexiste->status;
                    return $resposta;
                }

            }else {
                $sql = "SELECT qz.id, qz.quiz, qz.userid, qu.questionsummary, qu.rightanswer, qu.responsesummary FROM mdl_quiz_attempts qz 
                            INNER JOIN mdl_question_attempts qu ON qz.uniqueid = qu.questionusageid
                            WHERE qz.quiz = '{$idquiz}' AND qz.userid = '{$idestudante}'";
                $dados = $DB->get_records_sql($sql);
                $reposta = $DB->get_field('eva_barema_resposta_padrao', 'resposta', array('tb_quiz_id'=>$idquiz));
                foreach ($dados as $key=>$dado){
                    $resposta["question"] = $dado->questionsummary;
                    $resposta["responsecorreta"] = $reposta;
                    $resposta["response"] = $dado->responsesummary;
                }
                return $resposta;
            }
        }else{
            $resposta["error"] = true;
            return $resposta;
        }

    }


    public function export_for_template(renderer_base $output) {
        global $DB, $USER;


        $barema_id = trim($_GET['barema_id']);
        $aluno_id = trim($_GET['aluno_id']);
        $curso_id = trim($_GET['curso_id']);
        $quiz_id = trim($_GET['quiz_id']);


        $data = new \stdClass();

        if(!empty($this->config->title)){$data->title = $this->config->title;}
        if(!empty($this->config->subtitle)){$data->subtitle = $this->config->subtitle;}
        if(!empty($this->config->items)){$data->items = $this->config->items;}

        $respostas = $this->situacaodoaluno();

        if ($respostas['question']){
            $data->resposta_question = '<div class="alert alert-secondary" role="alert">'.$respostas['question'].'</div>';
            $data->bx_scrol = "bx_scroll";
            $data->resp_correta = '<div class="alert fz14 ml-6" role="alert" style="background-color: rgba(229,229,229,0.61)"><b>Resposta Ideal:</b></br><p>'.$respostas['responsecorreta'].'</p> </div>';
            $data->resp_resposta = '<div class="alert alert-success" role="alert">'.$respostas['response'].'</div>';
        }else if ($respostas['status']){
            $data->resp_correta = '<div class="alert alert-warning" role="alert"><strong>Esse Aluno já foi avaliado!</strong></div>';
         }


        $sql = "SELECT fullname, email from vw_autocomplete_user where id = '{$aluno_id}'";
        $aluno =  $DB->get_record_sql($sql);
        $curso = $DB->get_record('course', array('id'=>$curso_id), 'fullname');


        if ($data->items > 0) {
            for ($i = 1; $i <= $data->items; $i++) {

                $criterio = 'criterio'.$i;
                $nota_maxima = 'notamaxima'.$i;
                $label_baixa = 'labelbaixa'.$i;
                $valor_baixa = 'valorbaixa'.$i;
                $label_media1 = 'labelmedia1'.$i;
                $valor_media1 = 'valormedia1'.$i;
                $label_media2 = 'labelmedia2'.$i;
                $valor_media2 = 'valormedia2'.$i;
                $label_media3 = 'labelmedia3'.$i;
                $valor_media3 = 'valormedia3'.$i;
                $label_alta = 'labelalta'.$i;
                $valor_alta = 'valoralta'.$i;

                if ($this->config->$label_baixa &&  $this->config->$valor_baixa)
                {$barema[$i-1]['hidden_baixa'] = '';}else{$barema[$i-1]['hidden_baixa'] = 'hidden';}
                if ($this->config->$label_media1 &&  $this->config->$valor_media1)
                {$barema[$i-1]['hidden_media1'] = '';}else{$barema[$i-1]['hidden_media1'] = 'hidden';}
                if ($this->config->$label_media2 &&  $this->config->$valor_media2)
                {$barema[$i-1]['hidden_media2'] = '';}else{$barema[$i-1]['hidden_media2'] = 'hidden';}
                if ($this->config->$label_media3 &&  $this->config->$valor_media3)
                {$barema[$i-1]['hidden_media3'] = '';}else{$barema[$i-1]['hidden_media3'] = 'hidden';}
                if ($this->config->$label_alta &&  $this->config->$valor_alta)
                {$barema[$i-1]['hidden_alta'] = '';}else{$barema[$i-1]['hidden_alta'] = 'hidden';}

                $barema[$i-1]['id'] = $i;
                $barema[$i-1]['criterio'] = $this->config->$criterio;
                $barema[$i-1]['nota_maxima'] = $this->config->$nota_maxima;
                $barema[$i-1]['label_baixa'] = $this->config->$label_baixa;
                $barema[$i-1]['valor_baixa'] = $this->config->$valor_baixa;
                $barema[$i-1]['label_media1'] = $this->config->$label_media1;
                $barema[$i-1]['valor_media1'] = $this->config->$valor_media1;
                $barema[$i-1]['label_media2'] = $this->config->$label_media2;
                $barema[$i-1]['valor_media2'] = $this->config->$valor_media2;
                $barema[$i-1]['label_media3'] = $this->config->$label_media3;
                $barema[$i-1]['valor_media3'] = $this->config->$valor_media3;
                $barema[$i-1]['label_alta'] = $this->config->$label_alta;
                $barema[$i-1]['valor_alta'] = $this->config->$valor_alta;
            }

        }

        $dez_porcento = $DB->get_record('eva_barema_alunos', array('tb_avaliador_id'=>$_GET['tb_id_avaliador'], 'alunos_id'=>$_GET['aluno_id']), 'prazo');
        if ($dez_porcento->prazo < 0){
            $data->display = "";
            $data->percent = $dez_porcento->prazo;
        }else{
            $data->display = "d-none";
            $data->percent = "";
        }

        $data->id_user = $USER->id;
        $data->id_barema = $_GET['barema_id'];
        $data->id_avaliador = $_GET['avaliador_id'];
        $data->id_curso = $_GET['curso_id'];
        $data->id_quiz = $_GET['quiz_id'];
        $data->id_aluno = $_GET['aluno_id'];
        $data->id_tb_avaliador = $_GET['tb_id_avaliador'];
        $data->alunos = $aluno->fullname;
        $data->email = $aluno->email;
        $data->curso = $curso->fullname;
        $data->barema = $barema;

        return $data;

    }
}