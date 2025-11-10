<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.


namespace block_eva_form_barema\output;

defined('MOODLE_INTERNAL') || die();

use renderable;
use renderer_base;
use templatable;

/**
 *  file description here.
 *
 * @package
 * @copyright  2023 celio <>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


class admin_avaliadores implements renderable, templatable {

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
        global $DB, $USER, $PAGE;

        $nome_avaliador = $USER->firstname.' '.$USER->lastname;



//        $userid = $_GET['admin'];
//
//        $dadosAtribuicoes = $DB->get_records('eva_barema_avaliador', array());
//
//        $i=0;
//        foreach ($dadosAtribuicoes as $atrib){
//
//
//            $curso = $DB->get_record('course', array('id'=>$atrib->tb_curso_id), 'fullname');
//            $url = explode('?' ,$atrib->url_avaliacao);
//            $url_tb_avaliador_id = $_SERVER['SCRIPT_NAME']."?qt_aluno_por_curso=".$atrib->id;
//
//            $urlcurso = explode('&' ,$atrib->url_avaliacao);
//            $cursoatividade = $_SERVER['SCRIPT_NAME']."?qt_aluno_por_curso=".base64_encode($urlcurso[2]."&".$urlcurso[3]);
//
//            $nome = $DB->get_record_sql("SELECT fullname FROM vw_autocomplete_user WHERE id={$atrib->avaliador_tb_user_id}");
//
//            $data = ($atrib->data_atribuicao == null)?"--":date('d/m/Y', strtotime($atrib->data_atribuicao));
//
//            $listaalunos[$i]['nomeavaliador'] = $nome->fullname;
//            $listaalunos[$i]['acessoaosalunos'] = $curso->fullname;
//            $listaalunos[$i]['dataatribuicao'] = $data ;
//            $listaalunos[$i]['qtd_avaliados'] = $atrib->qtd_avaliados;
//            $listaalunos[$i]['color'] = ($atrib->qtd_avaliados > 0)? "status_blue" : "status_red" ;
//            $listaalunos[$i]['total'] = $atrib->qtd_alunos;
//            $listaalunos[$i]['linkavaliacao'] = $cursoatividade;
//            $listaalunos[$i]['linkalunos'] = $url_tb_avaliador_id;
//            $i++;
//        }

        $data = new \stdClass();



//        $data->avaliador = $nome_avaliador;
//        $data->listagrupoalunos = $listaalunos;


        return $data;

    }
}
