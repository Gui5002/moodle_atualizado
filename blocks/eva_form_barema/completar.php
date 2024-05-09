<?php
require_once ('../../config.php');
require_once ('lib.php');

global $DB, $USER;

$acao = ($_POST['acao'] ? $_POST['acao'] : $_GET['acao']);

switch ($acao) {
    case 'completar':
        $userid = $_GET['userid'];
        $quizid = $_GET['quizid'];

        $sqljoin = "SELECT * FROM mdl_quiz_attempts WHERE quiz = '{$quizid}' AND state = 'finished'";
        $existe = $DB->record_exists_sql($sqljoin);
//
        $users = [];
        if ($existe){
            $quiz = $DB->get_records_sql($sqljoin);
            $i = 0;
            foreach ($quiz as $key=>$quizes){
                $nome_aluno = $DB->get_record_sql("SELECT fullname from vw_autocomplete_user where id = '{$quizes->userid}' ORDER BY fullname");
                $users[] = $nome_aluno->fullname;
                $i++;
            }
            echo json_encode($users);
        }else{
            echo json_encode($existe);
        }
    break;

    case 'pesquisar':
        $valor = $_GET['valor'];
//        select `mdl_user`.`id` AS `id`,concat(`mdl_user`.`firstname`,' ',`mdl_user`.`lastname`) AS `fullname` from `mdl_user` where ((`mdl_user`.`confirmed` = 1) and (`mdl_user`.`deleted` = 0) and (`mdl_user`.`id` > 1))
        $sql = "SELECT id FROM vw_autocomplete_user vw WHERE fullname = '{$valor}'";
        $data = $DB->get_record_sql($sql);
//        $i = 0;
//        foreach ($data as $key=>$datas) {
//            $datauser['id'] = $datas->id;
//            $i++;
//        }

        echo json_encode($data);
        break;
    case 'validanome':
        $valor = trim($_GET['valor']);

        $existenome = "SELECT id from vw_autocomplete_user where fullname = '{$valor}'";
        $existe = $DB->record_exists_sql($existenome);
        if (!$existe) {
            $erro = get_string('validanome', 'block_eva_form_barema');
            echo $erro;
        }
        break;
    case 'listacategoria':

        $coursecategories = $DB->get_records('course_categories', array("parent"=>0), 'id ASC');

        $i = 0;
        foreach ($coursecategories as $key=>$coursecategory){
            $categoria[$i]['id_cat'] = $coursecategory->id;
            $categoria[$i]['nome_cat'] = $coursecategory->name;
            $i++;
        }

        echo json_encode($categoria);
        break;
    case 'buscarsubcategoria':
        $id = trim($_GET['id_subcategoria']);
        $existecargo = "SELECT id from mdl_course_categories where parent = '{$id}'";
        $existe = $DB->record_exists_sql($existecargo);
        if ($existe) {
            $valor = $DB->get_records('course_categories', array("parent"=>$id), 'id ASC');
            $i = 0;

            foreach ($valor as $key=>$valores){
                $subvalor = $DB->get_records('course_categories', array("parent"=>$valores->id), 'id ASC');
                //===SE EXISTE SUBCATEGORIA, PEGA DADOS PARA FAZER OS OPTGROUP NO SELECT======
                if ($subvalor) {
                    $subcategoria[$i]['id'] = $valores->id;
                    $subcategoria[$i]['optlabel']['nome'] = $valores->name;
                    $x = 0;
                    foreach ($subvalor as $key=>$subvalor){
                        $subcategoria[$i]['options'][$x]['id'] = $subvalor->id;
                        $subcategoria[$i]['options'][$x]['nome'] = $subvalor->name;
                        $x++;
                    }
                } else {
                    $subcategoria[$i]['id'] = $valores->id;
                    $subcategoria[$i]['nome'] = $valores->name;
                }
                $i++;
            }
            $subcategoria['length'] = $i;
            echo json_encode($subcategoria);
        }else{
            echo json_encode($existe);
        }
        break;
    case 'buscarcursos':
        $id = trim($_GET['id_curso']);
        $existecargo = "SELECT id from mdl_course where category = '{$id}'";
        $existe = $DB->record_exists_sql($existecargo);
        if ($existe) {
            $valor = $DB->get_records('course', array("category"=>$id), 'id ASC');
            $i = 0;
            foreach ($valor as $key=>$valores){
                $curso[$i]['id'] = $valores->id;
                $curso[$i]['nome'] = $valores->fullname;
                $i++;
            }
            echo json_encode($curso);
        }else{
            echo json_encode($existe);
        }
        break;

    case 'buscaatividades':

        $idcurse = trim($_GET['id_curso']);
        $idestudante = trim($_GET['id_estudante']);

        $sqljoin = "SELECT q.id, q.name FROM mdl_quiz q WHERE q.course = '{$idcurse}'";

//        $sqljoin = "SELECT q.id, q.name FROM mdl_quiz q
//                    INNER JOIN mdl_quiz_attempts qa 	ON q.id = qa.quiz
//                    WHERE q.course = '{$idcurse}' and qa.userid = '{$idestudante}' and qa.state = 'finished' GROUP BY q.id";

        $existe = $DB->record_exists_sql($sqljoin);
        if ($existe){
            $quiz = $DB->get_records_sql($sqljoin);
            $i = 0;
            foreach ($quiz as $key=>$quizes){
                $atividade[$i]['id'] = $quizes->id;
                $atividade[$i]['nome'] = $quizes->name;
                $i++;
            }
            echo json_encode($atividade);
        }else{
            echo json_encode($existe);

        }
        break;

    case 'buscarestudanteatividade':

        $quiz_id = trim($_GET['quiz_id']);
        $sqljoin = "SELECT id, userid FROM mdl_quiz_attempts WHERE quiz = '{$quiz_id}'";
        $existe = $DB->record_exists_sql($sqljoin);

        if ($existe){
            $quiz = $DB->get_records_sql($sqljoin);
            $i = 0;
            foreach ($quiz as $key=>$quizes){

                $sql = "SELECT id from vw_autocomplete_user where id = '{$quizes->userid}'";
                $nome_aluno = $DB->get_records_sql($sql);
                $atividade[$i]["id"] = $nome_aluno->id;
                $atividade[$i]["aluno"] = $nome_aluno->fullname;
                $i++;
            }
            echo json_encode($atividade);
        }else{
            echo json_encode($existe);

        }
        break;


case 'buscaperguntaresposta':

//    $idbarema = trim($_GET['idbarema']);
//    $idavaliador = trim($_GET['idavaliador']);

    $idcurse = trim($_GET['id_curso']);
    $idquiz = trim($_GET['id_quiz']);
    $idestudante = trim($_GET['idestudante']);
    $nome_naoexiste = trim($_GET['nome_naoexiste']);

    $sql = "SELECT id from vw_autocomplete_user where fullname = '{$nome_naoexiste}'";
    $existealuno =  $DB->get_record_sql($sql);

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
                   //===esse aluna ja foi avaliador com esses parametros de coruso e de quiz ===
                   $podeavaliar = false;
               }
           }
            //$resposta["question"] = $repetir_avaliacao;
            //echo json_encode($resposta);
            //break;
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
                echo json_encode($resposta);
                break;
            }else{
                $resposta["status"] = $idexiste->status;
                echo json_encode($resposta);
                break;
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
            echo json_encode($resposta);
            break;
        }
    }else{
        $resposta["error"] = true;
        echo json_encode($resposta);
    }
    break;


    case 'existebaremaemandamento':

        $idnumero_barema = trim($_GET['idnumerobarema']);

        $sql = "SELECT id, status FROM mdl_eva_barema_avaliacao
                    WHERE numero_barema = 69";
        $existe = $DB->record_exists_sql($sql);

        if ($existe) {
            echo json_encode($existe);
        }else{
            echo json_encode($existe);
        }
        break;

    case 'buscausuarios':
        $names = get_users_confirmed();
        $i=0;
        foreach ($names as $key=>$name) {
            $option[$i]['id'] = $name->id;
            $option[$i]['nome'] = $name->firstname . ' ' . $name->lastname;
            $i++;
        }
        echo json_encode($option);

    break;
    case 'adicionarvaliador':

        $avaliadorid = $_GET['avaliadorid'];
        $baremaid = $_GET['baremaid'];

        $arrayavaliador = array();
        $arrayavaliador[] = array(
            'baremaid' => $baremaid,
            'avaliador_userid' => $avaliadorid,
        );

        $avaliador = $DB->insert_records('eva_barema_avaliador', $arrayavaliador);

        echo json_encode($avaliador);

    break;

    case 'editavaliadores':

        $idavaliador = $_GET['id_avaliador'];
        $iduser = $_GET['id_user'];

        $edit = $DB->update_record('eva_barema_avaliador', array('id'=>$idavaliador, 'avaliador_userid'=>$iduser));
        $idbarema = $DB->get_record('eva_barema_avaliador', array('id'=>$idavaliador));

        echo json_encode($idbarema);
        break;

    case 'editconfigdatabarema':

        $barema = $DB->get_record('eva_barema', array('id'=>1));
        if ($barema->id) {
            $bi =  $DB->update_record('block_instances', array('id'=>$barema->numero_barema, 'configdata'=>$barema->hash_barema));
        }

        echo json_encode($bi);

    break;

    case 'quantosalunos': //==========Pega a quantidade de alnos e faz a distribuição para os avaliadores ====================

        $array_avaliadorid = explode(',', $_GET['avaliadores']);
        $quizid = $_GET['quizid'];
        $courseid = $_GET['courseid'];
//        $quizid = 22;

        $excluiu = $DB->delete_records('eva_barema_distribuicao', array('usuario_id'=>$USER->id));

//        $quiz = $DB->count_records('quiz_attempts', array('quiz'=>$quizid, 'state'=>'finished'), '', 'userid');
        // $quiz = $DB->count_records('quiz_attempts', array('quiz'=>$quizid), '', 'userid');
        $quiz = $DB->get_records('quiz_attempts', array('quiz'=>$quizid), '', 'userid');
        $v1=0;
        $v2=0;
        foreach ($quiz as $alunos) {
            $existes = $DB->record_exists('eva_barema_avaliacao', array('aluno_tb_user_id'=>$alunos->userid));
        // }
        // if(true){
            
            $id_existes = $DB->get_records('eva_barema_avaliacao', array('aluno_tb_user_id'=>$alunos->userid));
            if ($existes) {
                $foiavaliado = false;
                foreach ($id_existes as $idexiste) {
                    $tb_avaliador = $DB->get_record('eva_barema_avaliador', array('id'=>$idexiste->tb_avaliador_id));
                    if (($tb_avaliador->tb_curso_id == $courseid) && ($tb_avaliador->tb_atividade_id == $quizid)) {
                        $foiavaliado = true;
                    }
                }
                if(!$foiavaliado) {
                    $v1++; 
                }
            }else{
                $v2++;
            }
            
            $i++;
        }
        $qt = $v1 + $v2;
        $result = intdiv($qt, count($array_avaliadorid));
        $resto = ($qt % count($array_avaliadorid));

        for ($x=1; $x<=count($array_avaliadorid); $x++){
            $inteiros[]['qt_avaliando'] = $result;
        }
        
        if ($resto > 0){
            foreach ($inteiros as $int){
                if ($resto > 0){
                    $users[]['qt_avaliando'] = $int['qt_avaliando'] + 1;
                }else{
                    $users[]['qt_avaliando'] = $int['qt_avaliando'];
                }
                $resto--;
            }
        }else{
            $users = $inteiros;
        }
        
        foreach ($array_avaliadorid as $key=>$avaliador){
            $nome_aluno = $DB->get_record_sql("SELECT fullname from vw_autocomplete_user where id = '{$avaliador}' ORDER BY fullname");
            $users[$key]['avaliador_id'] = $avaliador;
            $users[$key]['avaliador'] = ucwords(strtolower($nome_aluno->fullname));
            $users[$key]['total'] = $qt;
            $i++;
        }
        
        // echo json_encode($users);
        // break;
        // exit();
        echo json_encode($users);
    break;

    case 'adicionardistribuicao':

        $iduser = explode(',', $_GET['userid']);
        $qtavaliados = explode(',', $_GET['qtavaliados']);
        for ($i=0; $i<count($iduser); $i++){
            $arrays[$i]['iduser'] = $iduser[$i];
        }
        for ($i=0; $i<count($qtavaliados); $i++){
            $arrays[$i]['qtd'] = $qtavaliados[$i];
        }

        foreach ($arrays as $key=>$ar){
            $dadoDistribuicao[] = array(
                'usuario_id' => $USER->id,
                'avaliador_id' => $ar['iduser'],
                'qt_alunos' => $ar['qtd'],
            );

        }

        $excluiu = $DB->delete_records('eva_barema_distribuicao', array('usuario_id'=>$USER->id));
        $DB->insert_records('eva_barema_distribuicao', $dadoDistribuicao);
        $user_existe = $DB->record_exists('eva_barema_distribuicao', array('usuario_id'=>$USER->id));
        if ($user_existe){
            $user_existe = true;
        }else{
            $user_existe = false;
        }

        echo json_encode($user_existe);
    break;

    case 'deletetabeladistribuicao':

        $excluiu = false;

        $user_existe = $DB->record_exists('eva_barema_distribuicao', array('usuario_id'=>$USER->id));
        if ($user_existe){
            $excluiu = $DB->delete_records('eva_barema_distribuicao', array('usuario_id'=>$USER->id));
        }

        echo json_encode($excluiu);
    break;

    case 'buscardistribuicao':

//        $teste = "esse é um teste";

        $distribuicoes = $DB->get_records('eva_barema_distribuicao', array('usuario_id'=>$USER->id));
//
        $ids=1;
        $id=1;
        $busca = array();
        foreach ($distribuicoes as $key=>$dist){
            $avaliador = $DB->get_record_sql("SELECT fullname from vw_autocomplete_user where id = '{$dist->avaliador_id}'");
            $busca[$id]["ava"] = strtoupper($avaliador->fullname); // .' - '. $dist->avaliador_id; // $avaliador . ' - ' . $dist->qt_avaliados;
            $busca[$id]["avaid"] = $dist->qt_alunos; // $avaliador . ' - ' . $dist->qt_avaliados;
            $busca["length"] = $ids++; $id++;
        }

        echo json_encode($busca);

    break;

    default:
        echo 'Nao encontrou';
}



