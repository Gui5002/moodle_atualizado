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
class admin_alunos implements renderable, templatable {
    var $config;
    var $context;
    public function __construct($config, $context) {
        $this->config = $config;
        $this->context = $context;
    }
    public function ordenaAvaliadores ($arr){
        array_multisort(array_map(function ($elemento){
            $elemento['notas'];
            return $elemento;
        }, $arr), SORT_ASC, $arr);
        return $arr;
    }
    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        global $DB, $USER, $PAGE;
        $prazo = new \block_eva_form_barema\models\prazos();
//        $nome_usuario = $USER->firstname.' '.$USER->lastname;
        $id_tbavaliador = $_GET['qt_aluno_por_curso'];
        $tb_id_avaliador = $DB->get_record('eva_barema_avaliador', array('id'=>$id_tbavaliador));
        $dadosAlunosCursos = $DB->get_records('eva_barema_alunos', array('tb_avaliador_id' => $id_tbavaliador));
        foreach ($dadosAlunosCursos as $cha=>$ac) {
            if ($ac->datafinish) {
                $diasresp = $prazo->diasRespostas($tb_id_avaliador->data_atribuicao, $ac->datafinish);
                $prazo = ($diasresp > $ac->prazo) ? 'fora': 'dentro';
                if ($prazo == 'dentro') {
                    $DB->update_record('eva_barema_alunos', array('id'=>$ac->id, 'prazo'=>null, 'datafinish'=>null));
                }else{
                    $DB->update_record('eva_barema_alunos', array('id'=>$ac->id, 'prazo'=>0));
                }
            }
        }
        $alunosavaliados = $DB->get_records_sql("SELECT aluno_tb_user_id as alunos_id FROM mdl_eva_barema_avaliacao WHERE  tb_avaliador_id = '$id_tbavaliador'");
        $alunopendentes = $DB->get_records('eva_barema_alunos', array('tb_avaliador_id'=>$id_tbavaliador), 'status ASC', 'id, alunos_id, status, prazo');
//        var_dump($alunopendentes);die();
        $totalalunos = array_merge($alunopendentes, $alunosavaliados);
        $avaliador_selecionado = $DB->get_record_sql("SELECT fullname FROM vw_autocomplete_user WHERE id = '$tb_id_avaliador->avaliador_tb_user_id'");
        $curso = $DB->get_record('course', array('id'=>$tb_id_avaliador->tb_curso_id), 'fullname');
        $barema = $DB->get_record('eva_barema', array('id'=>$tb_id_avaliador->tb_barema_id), 'nome_modelo');
        $atividade = $DB->get_record('quiz', array('id'=>$tb_id_avaliador->tb_atividade_id), 'name');
        $i=0;
        foreach ($totalalunos as $alunos){
            $nome = $DB->get_record_sql("SELECT fullname, suap FROM vw_autocomplete_user WHERE id = '$alunos->alunos_id'");
            $tb_avaliacao = $DB->get_record('eva_barema_avaliacao', array('tb_avaliador_id'=>$id_tbavaliador, 'aluno_tb_user_id'=>$alunos->alunos_id), 'nt_avaliador, data_avaliacao, flag');
            $dataavaliacao = ($tb_avaliacao->data_avaliacao != NULL) ? date('d/m/Y', strtotime($tb_avaliacao->data_avaliacao )) : "--";
            if ($tb_avaliacao->nt_avaliador != null){
                if ($tb_avaliacao->flag == 1 ){
                    $alunopendente[$i]['textColor'] = 'fw600';
                    $alunopendente[$i]['acao_link'] = '<i class="icon fa ccn-flaticon-info text-success fa-fw" style="font-weight: 600;font-size: 15px;"
                                                        data-toggle="tooltip" data-placement="right"
                                                        data-custom-class="custom-tooltip"
                                                        data-title="Respondeu fora do prazo com justificaiva."
                                                      ></i>';
                }else if ($tb_avaliacao->flag == 2) {
                    $alunopendente[$i]['textColor'] = 'fw600';
                    $alunopendente[$i]['acao_link'] = '<i class="icon fa ccn-flaticon-info text-info fa-fw" style="font-weight: 600;font-size: 15px;"
                                                        data-toggle="tooltip" data-placement="right"
                                                        data-custom-class="custom-tooltip"
                                                        data-title="Respondeu fora do prazo teve desconto de 10% da nota total."
                                                      ></i>';
                }
                $status = "status_verde";
                $data = $tb_avaliacao->nt_avaliador;
                $alunopendente[$i]['display'] = "d-none";
            }else{
                $status = "status_red";
                $data = "Pendente";
                $alunopendente[$i]['display'] = "d-none";
            }
            if ($alunos->status == 1 && $alunos->prazo == null){
                $alunopendente[$i]['textColor'] = 'fw500';
                $alunopendente[$i]['acao_link'] = '';
                $alunopendente[$i]['display'] = "d-none";
            }else{
                if ($alunos->status == 1 && $alunos->prazo == 0){
                    $alunopendente[$i]['textColor'] = 'fw600';
                    $alunopendente[$i]['acao_link'] = '<i class="icon fa ccn-flaticon-info text-info fa-fw" style="font-weight: 600;font-size: 15px;"
                                                        data-toggle="tooltip" data-placement="right"
                                                        data-custom-class="custom-tooltip"
                                                        data-title="Respondeu fora do prazo... Esperando atorização do Admin."
                                                      ></i>';
                    $alunopendente[$i]['display'] = "";
                }else if ($alunos->status == 1 && $alunos->prazo < 0) { //Resposta do administrador
                    $alunopendente[$i]['textColor'] = 'fw600';
                    $alunopendente[$i]['acao_link'] = '<i class="icon fa ccn-flaticon-info text-info fa-fw" style="font-weight: 600;font-size: 15px;"
                                                        data-toggle="tooltip" data-placement="right"
                                                        data-custom-class="custom-tooltip"
                                                        data-title="Desconto de 10% da nota total."
                                                      ></i>';
                    $alunopendente[$i]['display'] = "d-none";
                }else if ($alunos->status == 1 && $alunos->prazo > 0) { //Resposta do administrador
                    $alunopendente[$i]['textColor'] = 'fw600';
                    $alunopendente[$i]['acao_link'] = '<i class="icon fa ccn-flaticon-info text-success fa-fw" style="font-weight: 600;font-size: 15px;"
                                                        data-toggle="tooltip" data-placement="right"
                                                        data-custom-class="custom-tooltip"
                                                        data-title="Respondeu fora do prazo com justificaiva."
                                                      ></i>';
                    $alunopendente[$i]['display'] = "d-none";
                }
                if ($alunos->status == "0" && $alunos->prazo > 0){
                    $alunopendente[$i]['textColor'] = 'text-danger fw600';
                    $alunopendente[$i]['acao_link'] = '<i class="icon fa ccn-flaticon-info text-danger fa-fw" style="font-weight: 600;font-size: 15px;"
                                                        data-toggle="tooltip" data-placement="right"
                                                        data-custom-class="custom-tooltip"
                                                        data-title="Não fez Atividade."
                                                      ></i>';
                    $data = "???";
                    $alunopendente[$i]['display'] = "d-none";
                }
            }
            $alunopendente[$i]['id'] = $alunos->id;
            $alunopendente[$i]['alunoid'] = $alunos->alunos_id;
            $alunopendente[$i]['nome'] = $nome->fullname;
//            $alunopendente[$i]['barema'] = $barema->nome_modelo;
//            $alunopendente[$i]['curso'] = $curso->fullname;
           $alunopendente[$i]['suap'] = $nome->suap;
            $alunopendente[$i]['data'] = $dataavaliacao;
            $alunopendente[$i]['nota'] = $data;
            $alunopendente[$i]['status'] = $status;
            $i++;
        }
//        $ordenavaliadores = $this->ordenaAvaliadores($alunopendente);
        $data = new \stdClass();
        $data->idatribuicao = $id_tbavaliador;
        $data->avaliador_selecionado = $avaliador_selecionado->fullname;
        $data->listagrupoalunos = $alunopendente;
        $data->barema = $barema->nome_modelo;
        $data->curso = $curso->fullname;
        $data->atividade = $atividade->name;
//        $data->listagrupoalunos = $ordenavaliadores;
        return $data;
    }
}
