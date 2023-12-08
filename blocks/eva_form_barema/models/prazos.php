<?php

namespace block_eva_form_barema\models;

defined('MOODLE_INTERNAL') || die();
class prazos {

    public function quantosDiasFaltam ($dataAtribuicao, $prazo){
//        ========PRAMETRO => data_atribuicao, prazo ==================

        $tempo_atual = strtotime(date("Y-m-d")); // Data Atual
        $tempo = new \DateTime($dataAtribuicao); // Data Atribuido
        $tempo_evento = strtotime(date($tempo->format('Y-m-d'))); // Data Atribuido


        $tempo->add(new \DateInterval('P'.$prazo.'D'));
        $data_mais_qt_dia = strtotime(date($tempo->format('Y-m-d'))); // Data Atribuido

        $diferenca = $data_mais_qt_dia - $tempo_atual;
        $dias = intval($diferenca / 86400);

        return $dias;
    }

    public function diasRespostas ($dataAtribuicao, $dataResposta) {


        $tempo_atribuicao = strtotime(date($dataAtribuicao)); // Data Atribuicao
        $tempo_final = intval($dataResposta); // Data Atual

        $diferenca = $tempo_final - $tempo_atribuicao;
        $diasResposta = intval($diferenca / 86400);

        return $diasResposta;
    }

}

?>