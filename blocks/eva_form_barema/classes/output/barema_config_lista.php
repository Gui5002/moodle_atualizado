<?php

namespace block_eva_form_barema\output;

defined('MOODLE_INTERNAL') || die();

//require_once ('classes/output/criarbarema.php');
use core\plugininfo\filter;
use mod_forum\local\exporters\group;
use moodle_url;
use renderable;
use renderer_base;
use templatable;

class barema_config_lista implements renderable, templatable {

    var $config;
    var $context;

    public function __construct($config, $context) {
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
        $avaliador = $avaliadorid ? fullname($USER) : filter_input(INPUT_GET, 'avaliador', FILTER_SANITIZE_STRING);
        $aluno = $alunoid ? fullname($USER) : filter_input(INPUT_GET, 'aluno', FILTER_SANITIZE_STRING);




        //FILTRO DE STATUS
        $status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_NUMBER_INT);
        $status = in_array($status,[0,1]) ? $status : '';

        $condicoes = [
            strlen($barema) ? ' barema LIKE "%'.str_replace(' ','%',$barema).'%"':null,
            strlen($curso) ? ' curso LIKE "%'.str_replace(' ','%',$curso).'%"':null,
            strlen($atividade) ? ' curso LIKE "%'.str_replace(' ','%',$atividade).'%"':null,
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

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return stdClass
     */


    public function export_for_template(renderer_base $output) {

        global $DB, $USER;

        $data = new \stdClass();

        if(!empty($this->config->title)){$data->title = $this->config->title;}
        if(!empty($this->config->subtitle)){$data->subtitle = $this->config->subtitle;}
        if(!empty($this->config->items)){$data->items = $this->config->items;}


        $clausulaWhere = $this->filter_busca();
        $where = $clausulaWhere ? 'WHERE'.$clausulaWhere : '';

        $caderno = $this->pagination_relatorio();

//        var_dump($caderno);die();
        //buscando os dados no banco
        $sql = "SELECT SQL_CALC_FOUND_ROWS `id`, barema, curso, atividade, avaliador, aluno, `data` 
                FROM vw_relatorio_barema {$where} LIMIT {$caderno->start}, {$caderno->perPage}";

        $relatorios = $DB->get_records_sql($sql);

        $sqltotal = "SELECT FOUND_ROWS() as total";
        $resultado_pg = $DB->get_record_sql($sqltotal)->total;

        $quantidade_pg = ceil($resultado_pg / $caderno->perPage);

        $selectfilter = [];
        foreach ($_GET as $key => $value) {
            if ($key === 'barema'){
                $selects = '?barema='.$value;
            }elseif ($key === 'curso') {
                $selects .= '&curso='.$value;
            }elseif ($key === 'atividade') {
                $selects .= '&atividade='.$value;
            }elseif ($key === 'avaliador') {
                $selects .= '&avaliador='.$value;
            }elseif ($key === 'aluno') {
                $selects .= '&aluno='.$value;
            }
            $selectfilter[$key] = $value;
        }

        $selectfilter = (object) $selectfilter;

        //contruindo os links da paginação

        $max_links = 2;
        $primeiro   = $selects ? $selects.'&page='. 1 .'&per_page='.$caderno->perPage : '?page='. 1 .'&per_page='.$caderno->perPage;
        $i=0;
        for ($pag_ant = $caderno->page - $max_links; $pag_ant <= $caderno->page - 1; $pag_ant++) {
            if ($pag_ant >= 1) {
                $link  = $selects ? $selects.'&page='.$pag_ant.'&per_page='.$caderno->perPage : '?page='. $pag_ant .'&per_page='.$caderno->perPage;
                $link_ant[$i]['href_ant'] = $link;
                $link_ant[$i]['pg_ant'] = $pag_ant;
                $i++;
            }
        }
        $link  = $selects ? $selects.'&page='.$pag_ant.'&per_page='.$caderno->perPage : '?page='. $pag_ant .'&per_page='.$caderno->perPage;
        $link_atual[0]['select'] = 'active';
        $link_atual[0]['href_atual'] = $link;
        $link_atual[0]['pg_atual'] = $caderno->page;

        $i=0;
        for ($pag_dep = $caderno->page + 1; $pag_dep <= $caderno->page + $max_links; $pag_dep++) {
            if ($pag_dep <= $quantidade_pg) {
                $link  = $selects ? $selects.'&page='.$pag_dep.'&per_page='.$caderno->perPage : '?page='. $pag_dep .'&per_page='.$caderno->perPage;
                $link_dep[$i]['href_dep'] = $link;
                $link_dep[$i]['pg_dep'] = $pag_dep;
            }
            $i++;
        }
        if (($caderno->page + 3) <= $quantidade_pg) {
            $ultima_pg = $selects ? $selects.'&page='.$quantidade_pg.'&per_page='.$caderno->perPage : '?page='. $quantidade_pg .'&per_page='.$caderno->perPage;
            $link_ultimo[0]['href_ultimo'] = $ultima_pg;
            $link_ultimo[0]['pontos'] = '...';
            $link_ultimo[0]['ultima_pg'] = $quantidade_pg;
        }
        $ultima     = $selects ? $selects.'&page='.$quantidade_pg.'&per_page='.$caderno->perPage : '?page='. $quantidade_pg .'&per_page='.$caderno->perPage;

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

                $array[$i]['id'] = $relatorio->id;
                $array[$i]['barema'] = $relatorio->barema;
                $array[$i]['curso'] = $relatorio->curso;
                $array[$i]['atividade'] = $relatorio->atividade;
                $array[$i]['avaliador'] = ucwords(strtolower($relatorio->avaliador));
                $array[$i]['aluno'] = ucwords(strtolower($relatorio->aluno));
                $array[$i]['data_avaliacao'] = date('d/m/Y', strtotime($relatorio->data)) ;
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
        $data->alunos = $this->filtro_select_aluno($aluno);

        $data->dadosAvaliacao = $array;
        $data->primeiro = $primeiro;
        $data->ultima = $ultima;
        $data->links_ant = $link_ant;
        $data->links_atual = $link_atual;
        $data->links_dep = $link_dep;
        $data->links_ultimo = $link_ultimo;

        $data->filters = $link;
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
    public function filtro_select_aluno ($aluno_user){
        global $DB;
        $sql = "SELECT aluno FROM vw_relatorio_barema GROUP BY aluno";
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