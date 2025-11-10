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
class alunos implements renderable, templatable
{

    var $config;
    var $context;

    public function __construct($config, $context)
    {
        $this->config = $config;
        $this->context = $context;
    }

    private function filter_busca( ) {
        global $USER, $PAGE, $CFG, $FULLME, $DB;

        if (!$DB->record_exists('eva_barema_permissao', array('user_id'=>$USER->id, 'posgraduacao'=>1))) {
            if (!$avaliadorid = $DB->get_field('eva_barema_avaliador', 'avaliador_tb_user_id', array('avaliador_tb_user_id'=>$USER->id))) {
                $alunoid = $USER->id;
            }
        }


        //BUSCA
        $barema = filter_input(INPUT_GET, 'barema', FILTER_SANITIZE_STRING);
        $curso = filter_input(INPUT_GET, 'curso', FILTER_SANITIZE_STRING);
        $atividade = filter_input(INPUT_GET, 'ativiadade', FILTER_SANITIZE_STRING);
        $avaliador = ($avaliadorid == $USER->id) ? fullname($USER) : filter_input(INPUT_GET, 'avaliador', FILTER_SANITIZE_STRING);
        $aluno = $alunoid ? fullname($USER) : filter_input(INPUT_GET, 'aluno', FILTER_SANITIZE_STRING);


        //FILTRO DE STATUS
        $status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_NUMBER_INT);
        $status = in_array($status,[0,1]) ? $status : '';

        $condicoes = [
//            strlen($barema) ? ' barema LIKE "%'.str_replace(' ','%',$barema).'%"':null,
            strlen($curso) ? ' curso LIKE "%'.str_replace(' ','%',$curso).'%"':null,
            strlen($atividade) ? ' atividade LIKE "%'.str_replace(' ','%',$atividade).'%"':null,
            strlen($avaliador) ? ' avaliador LIKE "%'.str_replace(' ','%',$avaliador).'%"':null,
            strlen($aluno) ? ' aluno LIKE "%'.str_replace(' ','%',$aluno).'%"':null,
            strlen($status) ? ' status = '.$status : null
        ];
        //REMOVE POSICOES VAZIAS
        $condicoes = array_filter($condicoes);


        //CLÁUSULA WHERE
        $where = implode(' AND ', $condicoes);

        return $where;
    }

    private function pagination_relatorio (){
        
        //INPÚT GET
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $per_page = (isset($_GET['per_page']) && ($_GET['per_page'] <= 50)) ? (int)$_GET['per_page'] : 5;
        
        //POSITIONING
        $start = ($page > 1) ? ($page * $per_page) - $per_page : 0;
        
        $caderno['page'] = $page;
        $caderno['perPage'] = $per_page;
        $caderno['start'] = $start;
        $caderno = (object) $caderno;
        

        return  $caderno;
    }

    public function ordenaAlunos ($arr){
        array_multisort(array_map(function ($elemento){
            return $elemento['alunos'];
        }, $arr), SORT_ASC, $arr);

        return $arr;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return stdClass
     */

    public function export_for_template(renderer_base $output)
    {
        global $DB, $USER, $PAGE;
        $data = new \stdClass();


//        $nome_avaliador = $USER->firstname.' '.$USER->lastname;
//        $userid = $USER->id;
//
//        $cursoalunos = explode('&',base64_decode($_GET['alunos']));
//        $cursoid = explode('=',$cursoalunos[0]);


$idtbavaliador = $_GET['qt_aluno_por_avaliador'];


$cursoid = $DB->get_record('eva_barema_avaliador', array('id' => $idtbavaliador), 'id, avaliador_tb_user_id, tb_curso_id, tb_atividade_id, url_avaliacao, data_atribuicao');

$voltar = $cursoid->avaliador_tb_user_id;

$cursoname = $DB->get_record('course', array('id' => $cursoid->tb_curso_id), 'fullname');

$dadosAlunosCursos = $DB->get_records('eva_barema_alunos', array('tb_avaliador_id' => $idtbavaliador));


$prazo = new \block_eva_form_barema\models\prazos();
$tempo = $prazo->quantosDiasFaltam($cursoid->data_atribuicao, 15);

//      data 2023/08/03 => 1691031600

$i = 0;
foreach ($dadosAlunosCursos as $cha=>$ac) {
    $nome = $DB->get_record_sql("SELECT fullname FROM vw_autocomplete_user WHERE id={$ac->alunos_id} ORDER BY fullname ASC");
    
    if ($ac->datafinish) {
        $diasresp = $prazo->diasRespostas($cursoid->data_atribuicao, $ac->datafinish);
        $prazo = ($diasresp > $ac->prazo) ? 'fora': 'dentro';
        if ($prazo == 'dentro') {
            $DB->update_record('eva_barema_alunos', array('id'=>$ac->id, 'prazo'=>null));
        }else{
            $DB->update_record('eva_barema_alunos', array('id'=>$ac->id, 'prazo'=>0));
        }
    }
            
            
            $alunosCursos[$i]['id'] = $ac->alunos_id;
            $alunosCursos[$i]['alunos'] = $nome->fullname;
            if ($ac->status == 1 && $ac->prazo == null) {
                $alunosCursos[$i]['textColor'] = '';
                $alunosCursos[$i]['acao_link'] = '<i class="flaticon-download-1 " style="font-weight: 600;font-size: 15px;"></i>';
                $alunosCursos[$i]['href_link'] =  $cursoid->url_avaliacao . '&aluno_id=' . $ac->alunos_id.'&tb_id_avaliador='.$idtbavaliador;
            }else{
                if ($ac->status == 1 && $ac->prazo == 0){
                    $alunosCursos[$i]['textColor'] = 'fw600';
                    $alunosCursos[$i]['acao_link'] = '<i class="icon fa ccn-flaticon-info text-info fa-fw" style="font-weight: 600;font-size: 15px;"
                    data-toggle="tooltip" data-placement="right"
                    data-custom-class="custom-tooltip"
                    data-title="Aluno respondeu fora do prazo... Esperando atorização do Admin."  
                    ></i>';
                    $alunosCursos[$i]['href_link'] = '';
                }else if ($ac->status == 1 && $ac->prazo < 0) {
                    $alunopendente[$i]['textColor'] = 'fw600';
                    $alunosCursos[$i]['acao_link'] = '<i class="flaticon-download-1 " style="font-weight: 600;font-size: 15px;"></i>';
                    $alunosCursos[$i]['href_link'] =  $cursoid->url_avaliacao . '&aluno_id=' . $ac->alunos_id.'&tb_id_avaliador='.$idtbavaliador;
                }
                if ($ac->status == 0 && $ac->prazo > 0){
                    $alunosCursos[$i]['textColor'] = 'text-danger fw600';
                    $alunosCursos[$i]['acao_link'] = '<i class="icon fa ccn-flaticon-info text-danger fa-fw" style="font-weight: 600;font-size: 15px;"
                    data-toggle="tooltip" data-placement="right"
                    data-custom-class="custom-tooltip"
                    data-title="Aluno ainda nao fez a Atividade."  
                    ></i>';
                    $alunosCursos[$i]['href_link'] = '';
                }
            }
            //            $alunosCursos[$i]['acao_link'] = ($ac->status == 1) ? '<i class="flaticon-download-1 " style="font-weight: 600;font-size: 15px;"></i>' : '<i class="icon fa ccn-flaticon-info text-danger fa-fw" title="Informação da Substituição" style="font-weight: 600;font-size: 15px;"></i>';
//            $alunosCursos[$i]['href_link'] = $cursoid->url_avaliacao . '&aluno_id=' . $ac->alunos_id.'&tb_id_avaliador='.$idtbavaliador;

$i++;
}

$alunoOdenados =  $this->ordenaAlunos($alunosCursos);

$nomeavaliador = $DB->get_record_sql("SELECT fullname FROM vw_autocomplete_user WHERE id={$cursoid->avaliador_tb_user_id}");

$data->listalunos = $alunoOdenados;
$data->cursoalunos = $cursoname->fullname;
$data->nomeavaliador = $nomeavaliador->fullname;

//====================================================lista de avaliados===============================================

$clausulaWhere = $this->filter_busca();

$where = $clausulaWhere ? 'WHERE'.$clausulaWhere.'ORDER BY data DESC' : '';

$caderno = $this->pagination_relatorio();

//buscando os dados no banco
$sql = "SELECT SQL_CALC_FOUND_ROWS `id`, barema, curso, atividade, avaliador, aluno, `data` 
                FROM vw_relatorio_barema {$where} LIMIT {$caderno->start}, {$caderno->perPage}";

//        var_dump($sql);die();
$relatorios = $DB->get_records_sql($sql);

        $sqltotal = "SELECT FOUND_ROWS() as total";
        $resultado_pg = $DB->get_record_sql($sqltotal)->total;

        $quantidade_pg = ceil($resultado_pg / $caderno->perPage);

        $sepera_path = explode('/', $PAGE->url->get_path());
        $selectfilter = [];
        $path_especifico = $sepera_path[3].'?qt_aluno_por_avaliador='.$idtbavaliador;
        foreach ($_GET as $key => $value) {

            if ($key === 'curso') {
                $selects .= '&curso='.$value;
            }else if ($key === 'atividade') {
                $selects .= '&atividade='.$value;
            }else if ($key === 'aluno') {
                $selects .= '&aluno='.$value;
            }
            $selectfilter[$key] = $value;
        }

        $max_links = 2;
        $primeiro   = $selects ? $path_especifico.$selects.'&page='. 1 .'&per_page='.$caderno->perPage : $path_especifico. '&page='. 1 .'&per_page='.$caderno->perPage;
        $i=0;
        for ($pag_ant = $caderno->page - $max_links; $pag_ant <= $caderno->page - 1; $pag_ant++) {
            if ($pag_ant >= 1) {
                $link  = $selects ? $path_especifico.$selects.'&page='.$pag_ant.'&per_page='.$caderno->perPage : $path_especifico.'&page='. $pag_ant .'&per_page='.$caderno->perPage;
                $link_pdf  = $selects ? $selects.'&page='.$pag_ant.'&per_page='.$caderno->perPage : '&page='. $pag_ant .'&per_page='.$caderno->perPage;
                $link_ant[$i]['href_ant'] = $link;
                $link_ant[$i]['pg_ant'] = $pag_ant;
                $i++;
            }
        }
        $link  = $selects ? $path_especifico.$selects.'&page='.$pag_ant.'&per_page='.$caderno->perPage : $path_especifico.'&page='. $pag_ant .'&per_page='.$caderno->perPage;
        $link_pdf  = $selects ? $selects.'&page='.$pag_ant.'&per_page='.$caderno->perPage : '&page='. $pag_ant .'&per_page='.$caderno->perPage;
        $link_atual[0]['select'] = 'active';
        $link_atual[0]['href_atual'] = $link;
        $link_atual[0]['pg_atual'] = $caderno->page;

        $i=0;
        for ($pag_dep = $caderno->page + 1; $pag_dep <= $caderno->page + $max_links; $pag_dep++) {
            if ($pag_dep <= $quantidade_pg) {
                $link  = $selects ? $path_especifico.$selects.'&page='.$pag_dep.'&per_page='.$caderno->perPage : $path_especifico.'&page='. $pag_dep .'&per_page='.$caderno->perPage;
                $link_pdf  = $selects ? $selects.'&page='.$pag_dep.'&per_page='.$caderno->perPage : '&page='. $pag_dep .'&per_page='.$caderno->perPage;
                $link_dep[$i]['href_dep'] = $link;
                $link_dep[$i]['pg_dep'] = $pag_dep;
            }
            $i++;
        }

        if (($caderno->page + 3) <= $quantidade_pg) {
            $ultima_pg = $selects ? $path_especifico.$selects.'&page='.$quantidade_pg.'&per_page='.$caderno->perPage : $path_especifico.'&page='. $quantidade_pg .'&per_page='.$caderno->perPage;
            $link_ultimo[0]['href_ultimo'] = $ultima_pg;
            $link_ultimo[0]['pontos'] = '...';
            $link_ultimo[0]['ultima_pg'] = $quantidade_pg;
        }
        $ultima     = $selects ? $path_especifico.$selects.'&page='.$quantidade_pg.'&per_page='.$caderno->perPage : $path_especifico.'&page='. $quantidade_pg .'&per_page='.$caderno->perPage;

        if (!$DB->get_field('eva_barema_permissao', 'user_id', array('user_id'=>$USER->id))) {
            if (!$DB->get_field('eva_barema_avaliador', 'avaliador_tb_user_id', array('avaliador_tb_user_id'=>$USER->id))) {
                $hidden = 'hidden';
            }
        }

        //Linstando os dados
        $count = count($relatorios);
        if ($count > 0) {
            $i = 0;

            foreach ($relatorios as $key=>$relatorio) {

                $data_atual = date('d/m/Y');
                $datatime = ($relatorio->data == null)?"--":date('d/m/Y', strtotime($relatorio->data));
                if ($datatime == $data_atual) {
                    $array[$i]['fwdata'] = 'fw-hoje';
                } else{
                    $array[$i]['fwdata'] = 'fw-old';
                }
                $array[$i]['id'] = $relatorio->id;
                $array[$i]['barema'] = $relatorio->barema;
                $array[$i]['curso'] = $relatorio->curso;
                $array[$i]['atividade'] = $relatorio->atividade;
                $array[$i]['avaliador'] = ucwords(strtolower($relatorio->avaliador));
                $array[$i]['aluno'] = ucwords(strtolower($relatorio->aluno));
                $array[$i]['data_avaliacao'] = $datatime;
                $i++;
            }
        }


        $barema     = $_GET['barema'];
        $curso      = $_GET['curso'];
        $atividade  = $_GET['atividade'];
        $avaliador  = $_GET['avaliador'];
        $aluno      = $_GET['aluno'];

        $data->baremas = $this->filtro_select_barema($barema);
        $data->cursos = $this->filtro_select_curso($curso);
        $data->atividades = $this->filtro_select_atividade($atividade);
        $data->avaliadores = $this->filtro_select_avaliador($avaliador);
        $data->alunos = $this->filtro_select_aluno($where, $aluno);

        $data->id_tbavaliador = $idtbavaliador;
        $data->voltar = $voltar;

        $data->dadosAvaliacao = $array;
        $data->primeiro = $primeiro;
        $data->ultima = $ultima;
        $data->links_ant = $link_ant;
        $data->links_atual = $link_atual;
        $data->links_dep = $link_dep;
        $data->links_ultimo = $link_ultimo;

        $data->filters = $link_pdf;
        $data->page = $caderno->page;
        $data->results = $caderno->perPage;
        $data->registros = $resultado_pg;
        $data->hidden = $hidden;

        return $data;

    }

    //funcoes para construção dos filtros de paginação
    public function filtro_select_barema ($barema){
        global $DB;
        $sql = "SELECT barema FROM vw_relatorio_barema GROUP BY barema";
        $dados = $DB->get_records_sql($sql);
        $i=0;
        foreach ($dados as $key=>$dado) {
            $array[$i]['valor'] = $key;
            $array[$i]['selected'] = ($key == $barema) ? 'selected' : '';
            $array[$i]['selecao'] = $dado->barema;
            $i++;
        }
        return $array;
    }
    public function filtro_select_curso ($curso){
        global $DB;
        $sql = "SELECT curso FROM vw_relatorio_barema GROUP BY curso";
        $dados = $DB->get_records_sql($sql);
        $i=0;
        foreach ($dados as $key=>$dado) {
            $array[$i]['valor'] = $key;
            $array[$i]['selected'] = ($key == $curso) ? 'selected' : '';
            $array[$i]['selecao'] = $dado->curso;
            $i++;
        }
        return $array;
    }
    public function filtro_select_atividade ($atividade){
        global $DB;
        $sql = "SELECT atividade FROM vw_relatorio_barema GROUP BY atividade";
        $dados = $DB->get_records_sql($sql);
        $i=0;
        foreach ($dados as $key=>$dado) {
            $array[$i]['valor'] = $key;
            $array[$i]['selected'] = ($key == $atividade) ? 'selected' : '';
            $array[$i]['selecao'] = $dado->atividade;
            $i++;
        }
        return $array;
    }

    public function filtro_select_avaliador ($avaliador_user){
        global $DB;
        $sql = "SELECT avaliador FROM vw_relatorio_barema GROUP BY avaliador ORDER BY avaliador ASC";
        $dados = $DB->get_records_sql($sql);
        $i=0;
        foreach ($dados as $key=>$dado) {
            $array[$i]['valor'] = $key;
            $array[$i]['selected'] = ($key == $avaliador_user) ? 'selected' : '';
            $array[$i]['selecao'] = $dado->avaliador;
            $i++;
        }
        return $array;
    }
    public function filtro_select_aluno ($condicao, $aluno_user){
        global $DB;
        $sql = "SELECT aluno, data FROM vw_relatorio_barema {$condicao} ";
//        var_dump($sql);die();
        $dados = $DB->get_records_sql($sql);
        $i=0;
        foreach ($dados as $key=>$dado) {
            $array[$i]['valor'] = $key;
            $array[$i]['selected'] = ($key == $aluno_user) ? 'selected' : '';
            $array[$i]['selecao'] = $dado->aluno;
            $i++;
        }
        return $array;
    }
}
