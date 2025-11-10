<?php

namespace block_eva_form_barema\output;

use renderable;
use renderer_base;
use templatable;

defined('MOODLE_INTERNAL') || die();

class relatorio_barema_config_pdf implements renderable, templatable {

    var $config;
    var $context;

    public function __construct($config, $context) {
        $this->config = $config;
        $this->context = $context;
    }

    private function filter_busca( ) {
        global $USER, $PAGE, $CFG, $FULLME, $DB;


        if (!$DB->get_field('eva_barema_permissao', 'user_id', array('user_id'=>$USER->id, 'posgraduacao'=>1))) {
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
        $per_page = isset($_GET['per_page']) && $_GET['per_page'] <= 50 ? (int)$_GET['per_page'] : 5;

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
        $sql_filtro = "SELECT SQL_CALC_FOUND_ROWS id, barema, curso, atividade, avaliador, aluno, data, nt_avaliador
                FROM vw_relatorio_barema {$where}";
        $sql_relatorio = "SELECT id, barema, curso, atividade, avaliador, aluno, data, nt_avaliador FROM vw_relatorio_barema";


        $sql = $where ? $sql_filtro : $sql_relatorio;

        $relatorios = $DB->get_records_sql($sql);
        $sqltotal = "SELECT FOUND_ROWS() as total";
        $total = $DB->get_record_sql($sqltotal);
        $pages = ceil($total->total / $caderno->perPage);


        $selectfilter = [];
        foreach ($_GET as $key => $value) {
            if ($key === 'barema'){$selects = '?barema='.$value;
            }elseif ($key === 'curso') {$selects .= '&curso='.$value;
            }elseif ($key === 'atividade') {$selects .= '&atividade='.$value;
            }elseif ($key === 'avaliador') {$selects .= '&avaliador='.$value;
            }elseif ($key === 'aluno') {$selects .= '&aluno='.$value;
            }
            $selectfilter[$key] = $value;
        }



        //contruindo os links da paginação
        $i =0;
        for ($x = 1; $x <= $pages; $x++) {
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
                $array[$i]['data_avaliacao'] = date('d/m/Y H:i:d', strtotime($relatorio->data));
                $array[$i]['nota'] = $relatorio->nt_avaliador;
                $i++;
            }
        }
        if (!$DB->get_field('eva_barema_permissao', 'user_id', array('user_id'=>$USER->id))) {
            if (!$DB->get_field('eva_barema_avaliador', 'avaliador_tb_user_id', array('avaliador_tb_user_id'=>$USER->id))) {
                $hidden = 'none';
            }
        }

        $data->dadosAvaliacao = $array;
        $data->links = $link;
        $data->page = $caderno->page;
        $data->results = $caderno->perPage;
        $data->registros = $total->total;
        $data->none = $hidden;

        return $data;
    }

}