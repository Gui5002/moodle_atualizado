<?php

namespace block_eva_barema_afastamento\output;

defined('MOODLE_INTERNAL') || die();

//require_once ('classes/output/criarbarema.php');
use core\plugininfo\filter;
use mod_forum\local\exporters\group;
use moodle_url;
use renderable;
use renderer_base;
use templatable;

class avaliacao_lista_pdf implements renderable, templatable {

    var $config;
    var $context;

    public function __construct($config, $context) {
        $this->config = $config;
        $this->context = $context;
    }

    private function filter_busca( ) {
        global $USER, $PAGE, $CFG, $FULLME, $DB;


//        if (!$DB->get_field('eva_barema_permissao', 'user_id', array('user_id'=>$USER->id))) {
//            if (!$avaliadorid = $DB->get_field('eva_barema_avaliador', 'avaliador_tb_user_id', array('avaliador_tb_user_id'=>$USER->id))) {
//                $alunoid = $USER->id;
//            }
//        }

        //BUSCA
        $anteprojeto = filter_input(INPUT_GET, 'anteprojeto', FILTER_SANITIZE_STRING);
//        $curso = filter_input(INPUT_GET, 'nota', FILTER_SANITIZE_STRING);
//        $atividade = filter_input(INPUT_GET, 'ativiadade', FILTER_SANITIZE_STRING);
//        $avaliador = $avaliadorid ? fullname($USER) : filter_input(INPUT_GET, 'avaliador', FILTER_SANITIZE_STRING);
//        $aluno = $alunoid ? fullname($USER) : filter_input(INPUT_GET, 'aluno', FILTER_SANITIZE_STRING);


        //FILTRO DE STATUS
        $status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_NUMBER_INT);
        $status = in_array($status,[0,1]) ? $status : '';

        $condicoes = [
            strlen($anteprojeto) ? ' anteprojeto LIKE "%'.str_replace(' ','%',$anteprojeto).'%"':null,
//            strlen($curso) ? ' curso LIKE "%'.str_replace(' ','%',$curso).'%"':null,
//            strlen($atividade) ? ' curso LIKE "%'.str_replace(' ','%',$atividade).'%"':null,
//            strlen($avaliador) ? ' avaliador LIKE "%'.str_replace(' ','%',$avaliador).'%"':null,
//            strlen($aluno) ? ' aluno LIKE "%'.str_replace(' ','%',$aluno).'%"':null,
//            strlen($status) ? ' status = '.$status : null
        ];
        //REMOVE POSICOES VAZIAS
        $condicoes = array_filter($condicoes);

        //CLÁUSULA WHERE
        $where = implode(' AND ', $condicoes);


        return $where;
    }

    private function pagination_relatorio (){
//        var_dump($_GET);die();

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



        //buscando os dados no banco
//        $sql = "SELECT SQL_CALC_FOUND_ROWS `id`, anteprojeto, `data_avaliacao` FROM vw_relatorio_afastamento {$where} LIMIT {$caderno->start}, {$caderno->perPage}";
        $sql_filtro = "SELECT SQL_CALC_FOUND_ROWS `id`, anteprojeto, avaliador_1, avaliador_2, avaliador_3, nt_capes, nt_final FROM mdl_eva_afastamento_result_final {$where}";
        $sql_relatorio = "SELECT id, anteprojeto, avaliador_1, avaliador_2, avaliador_3, nt_capes, nt_final FROM mdl_eva_afastamento_result_final";

        $sql = $where ? $sql_filtro : $sql_relatorio;

        $relatorios = $DB->get_records_sql($sql);


        $sqltotal = "SELECT FOUND_ROWS() as total";
        $resultado_pg = $DB->get_record_sql($sqltotal)->total;

        $quantidade_pg = ceil($resultado_pg / $caderno->perPage);

        $selectfilter = [];
        foreach ($_GET as $key => $value) {
            if ($key === 'anteprojeto'){
                $selects = '?anteprojeto='.$value;
            }
            $selectfilter[$key] = $value;
        }

        $selectfilter = (object) $selectfilter;

//        //contruindo os links da paginação
        $i =0;
        for ($x = 1; $x <= $quantidade_pg; $x++) {
            $link = $selects ? $selects.'&page='.$x.'&per_page='.$caderno->perPage : '?page='.$x.'&per_page='.$caderno->perPage;
            $href[$i]['link'] = $link;
            $href[$i]['pages'] = $x;
            if ($caderno->page === $x){
                if ($x == 1) {
                    $voltar = $selects ? $selects.'&page='.($x).'&per_page='.$caderno->perPage : '?page='.($x).'&per_page='.$caderno->perPage;
                    $selected = 'active';
                    $href[$i]['select'] = $selected;
                    $proximo = $selects ? $selects.'&page='.($x + 1).'&per_page='.$caderno->perPage : '?page='.($x + 1).'&per_page='.$caderno->perPage;
                }else{
                    $voltar = $selects ? $selects.'&page='.($x - 1).'&per_page='.$caderno->perPage : '?page='.($x - 1).'&per_page='.$caderno->perPage;
                    $selected = 'active';
                    $href[$i]['select'] = $selected;
                    $proximo = $selects ? $selects.'&page='.($x + 1).'&per_page='.$caderno->perPage : '?page='.($x + 1).'&per_page='.$caderno->perPage;
                }
            }
            $i++;
        }

//        if (!$DB->get_field('eva_barema_permissao', 'user_id', array('user_id'=>$USER->id))) {
//            if (!$DB->get_field('eva_barema_avaliador', 'avaliador_tb_user_id', array('avaliador_tb_user_id'=>$USER->id))) {
//                $hidden = 'hidden';
//            }
//        }

        //Linstando os dados


        $count = count($relatorios);
        if ($count > 0) {
            $i = 0;

            foreach ($relatorios as $key=>$relatorio) {

                $array[$i]['id'] = $relatorio->id;
                $array[$i]['anteprojeto'] = $relatorio->anteprojeto;
                $array[$i]['avaliador_1'] = $relatorio->avaliador_1;
                $array[$i]['avaliador_2'] = $relatorio->avaliador_2;
                $array[$i]['avaliador_3'] = $relatorio->avaliador_3;
                $array[$i]['nota_capes'] = $relatorio->nt_capes;
                $array[$i]['nota_final'] = $relatorio->nt_final;
                $i++;
            }
        }

        $data->dadosAvaliacao = $array;
        $data->results = $caderno->perPage;
        $data->registros = $resultado_pg;
//        $data->hidden = $hidden;


        return $data;
    }

}