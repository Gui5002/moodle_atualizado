<?php

namespace block_eva_form_barema\output;

defined('MOODLE_INTERNAL') || die();

//require_once ('classes/output/criarbarema.php');
use moodle_url;
use renderable;
use renderer_base;
use templatable;

class AvaliadorController implements renderable, templatable {

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


    public function export_for_template(renderer_base $output) {
        global $DB, $USER;

        $baremaid = $_GET['baremaid'];

        $data = new \stdClass();

//        if(!empty($this->config->title)){$data->title = $this->config->title;}
//        if(!empty($this->config->subtitle)){$data->subtitle = $this->config->subtitle;}
//        if(!empty($this->config->items)){$data->items = $this->config->items;}

        // $id_avaliador = $DB->get_records('eva_barema_avaliador', array('avaliador_tb_user_id'=>3127));
        // foreach($id_avaliador as $ava){
        //     var_dump($ava->qtd_alunos == 0);die();
        //     if($ava->qtd_alunos = 0){
        //         $DB->update_record('eva_barema_avaliador', array('id'=>$ava->id, 'status'=>1));
        //     }
        // }

        $usuarios = $DB->get_records('eva_barema_avaliadores', array());


            

        $i=0;
        $ct=1;
        $avaliadores = [];
        foreach ($usuarios as $user) {
            $contador = false;
            if (isset($user->avaliador_id)) {
                //===Zerar status dos avaliadores do antigo barema de pos quando qtd_lunos = 0 e qtd_avaliados = 0
                //Obs esse foreatch pode ser removido com em algum determinado tempo
                $id_avaliador = $DB->get_records('eva_barema_avaliador', array('avaliador_tb_user_id'=>$user->avaliador_id));

                foreach($id_avaliador as $ava){
                    if($ava->qtd_alunos == 0){
                        $DB->update_record('eva_barema_avaliador', array('id'=>$ava->id, 'status'=>1));
                    }
                }

                $sql = "SELECT id, fullname, email FROM vw_autocomplete_user where id = '{$user->avaliador_id}'";
                $avaliador = $DB->get_record_sql($sql);

                $avaliadores[$i]['id'] = $user->id;
                $avaliadores[$i]['id_user'] = $avaliador->id;
//                $avaliadores[$i]['posicao'] = 'Titular';
                $avaliadores[$i]['nome'] = ucwords(strtoupper($avaliador->fullname));
                $avaliadores[$i]['email'] = strtolower($avaliador->email);

                //=====Qtd Anteprojeto - conta quando ainda nao teve avaliação e quando a atribuição tiver ativo = 1=======
//                $qtd_avaliacao = $DB->get_records('eva_barema_avaliador', array('avaliador_tb_user_id'=>$avaliador->id, 'status'=>0, 'flag'=>0, 'ativo'>0));

                $sql = "SELECT * FROM mdl_eva_barema_avaliador WHERE avaliador_tb_user_id ='{$avaliador->id}' AND status = 0 AND flag = 0 AND ativo > 0";
                $qtd_avaliacao = $DB->get_records_sql($sql);

                $avaliadores[$i]['qtd_anteprojeto'] = count($qtd_avaliacao);
//                $avaliadores[$i]['coluna_status'] = 'status_titular';
//                $avaliadores[$i]['coluna_user_id'] = 'titular_user_id';
//                $avaliadores[$i]['eixo'] = ($user->eixo == 'j' ? 'JURIDICO' : 'GESTÃO');
                if ($user->status == 1){
                    $avaliadores[$i]['icon'] = 'online';
                    $avaliadores[$i]['title'] = 'Ativo';
                    $avaliadores[$i]['status'] = 'suspend';
                    $avaliadores[$i]['icon_eye'] = '';
                }else{
                    $avaliadores[$i]['icon'] = 'busy';
                    $avaliadores[$i]['title'] = 'Inativo';
                    $avaliadores[$i]['status'] = 'unsupend';
                    $avaliadores[$i]['icon_eye'] = '-slash';
                }
            }
//            else{
//                $contador = true;
//            }
//
//            if (isset($t_s->suplente_user_id)) {
//                if ($contador){
//                    $avaliadores[$i] = $this->get_avaliadores_suplente($i, $ct, $t_s);
//                }else{
//                    $i++;
//                    $avaliadores[$i] = $this->get_avaliadores_suplente($i, $ct, $t_s);
//                }
//            }
            $i++;
//              $ct++;
        }

        $data->dadosuser = $avaliadores;

        return $data;

    }
}