<?php

namespace block_eva_barema_bolsa\output;

defined('MOODLE_INTERNAL') || die();

//require_once ('classes/output/criarbarema.php');
use moodle_url;
use renderable;
use renderer_base;
use templatable;
use function PHPUnit\Framework\isNull;

class avaliador_lista implements renderable, templatable {

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

    // public function get_avaliadores_suplente($i, $ct, $t_s){
    //     global $DB;

    //     $suplente_sql = "SELECT id, fullname, email FROM vw_autocomplete_user where id = '{$t_s->suplente_user_id}'";
    //     $ava_suplente = $DB->get_record_sql($suplente_sql);

    //     $avaliadores[$i]['id'] = $t_s->id;
    //     $avaliadores[$i]['id_user'] = $ava_suplente->id;
    //     $avaliadores[$i]['posicao'] = 'Suplente';
    //     $avaliadores[$i]['nome'] = $ct.'.1 - '.ucwords(strtoupper($ava_suplente->fullname));
    //     $avaliadores[$i]['email'] = strtolower($ava_suplente->email);

    //     //=====Qtd Anteprojeto - conta quando ainda nao teve avaliação e quando a atribuição tiver ativo = 1=======
    //     $qtd_avaliacao = $DB->get_records('eva_bolsa_atribuicao', array('avaliador_tb_user_id'=>$ava_suplente->id, 'status'=>0, 'ativo'=>1));

    //     $avaliadores[$i]['qtd_anteprojeto'] = count($qtd_avaliacao);
    //     $avaliadores[$i]['coluna_status'] = 'status_suplente';
    //     $avaliadores[$i]['coluna_user_id'] = 'suplente_user_id';
    //     $avaliadores[$i]['eixo'] = ($t_s->eixo == 'j' ? 'juridico' : 'gestao');
    //     if ($t_s->status_suplente == 1){
    //         $avaliadores[$i]['icon'] = 'online';
    //         $avaliadores[$i]['title'] = 'Ativo';
    //         $avaliadores[$i]['status'] = 'suspend';
    //         $avaliadores[$i]['icon_eye'] = '';
    //     }else{
    //         $avaliadores[$i]['icon'] = 'busy';
    //         $avaliadores[$i]['title'] = 'Inativo';
    //         $avaliadores[$i]['status'] = 'unsupend';
    //         $avaliadores[$i]['icon_eye'] = '-slash';
    //     }

    //     return $avaliadores[$i];
    // }

    public function export_for_template(renderer_base $output) {
        global $DB, $USER;

        $baremaid = $_GET['baremaid'];

        $data = new \stdClass();

//        if(!empty($this->config->title)){$data->title = $this->config->title;}
//        if(!empty($this->config->subtitle)){$data->subtitle = $this->config->subtitle;}
//        if(!empty($this->config->items)){$data->items = $this->config->items;}

        // $users = "SELECT * FROM vw_autocomplete_user WHERE id < 100";
        // $names = $DB->get_records_sql($users);


        // $u=0;
        // foreach ($names as $key=>$name) {
        //     $option[$u]['id'] = $name->id;
        //     $option[$u]['nome'] = $name->fullname;
        //     $u++;
        // }

        $usuarios = $DB->get_records('eva_bolsa_avaliadores', array());
        $i=0;
        $ct=1;
        $avaliadores = [];
        foreach ($usuarios as $ava) {
            // $contador = false;
            if (isset($ava->user_id)) {

                $titular_sql = "SELECT id, fullname, email FROM vw_autocomplete_user where id = '{$ava->user_id}'";
                $vw_user = $DB->get_record_sql($titular_sql);

                $avaliadores[$i]['id'] = $ava->id;
                $avaliadores[$i]['id_user'] = $vw_user->id;
                // $avaliadores[$i]['posicao'] = ($ava->eixo == "j") ? "Jurídico" : "Gestão";
                $avaliadores[$i]['nome'] = ucwords(strtoupper($vw_user->fullname));
                $avaliadores[$i]['email'] = strtolower($vw_user->email);

                //=====Qtd Anteprojeto - conta quando ainda nao teve avaliação e quando a atribuição tiver ativo = 1=======
                $qtd_avaliacao = $DB->get_records('eva_bolsa_atribuicao', array('avaliador_tb_user_id'=>$vw_user->id, 'status'=>0, 'ativo'=>1));

                $avaliadores[$i]['qtd_anteprojeto'] = count($qtd_avaliacao);
                // $avaliadores[$i]['coluna_status'] = 'status';
                // $avaliadores[$i]['coluna_user_id'] = 'user_id';
                $avaliadores[$i]['hide_pencil'] = (count($qtd_avaliacao ) > 0)?'hidden':'';
                $avaliadores[$i]['eixo'] = ($ava->eixo == 'j' ? 'Jurídico' : 'Gestão');
                if ($ava->status == 1){
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
            
            // else{
            //     $contador = true;
            // }

            // if (isset($t_s->suplente_user_id)) {
            //     if ($contador){
            //         $avaliadores[$i] = $this->get_avaliadores_suplente($i, $ct, $t_s);
            //     }else{
            //         $i++;
            //         $avaliadores[$i] = $this->get_avaliadores_suplente($i, $ct, $t_s);
            //     }
            // }
            $i++; 
            // $ct++;
        }

        $data->dadosuser = $avaliadores;
        // $data->modalSelect = $option;

        return $data;

    }
}