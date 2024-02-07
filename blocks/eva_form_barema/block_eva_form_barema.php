<?php

require_once($CFG->dirroot. '/theme/evagu/ccn/block_handler/ccn_block_handler.php');

// require_once('classes/output/criar_pos_atribuicao.php');
require_once('classes/output/AtribuicaoController.php');
require_once('classes/output/AvaliadorController.php');
require_once('classes/output/ModeloController.php');
require_once('classes/output/customize_conf.php');
require_once('classes/output/avaliacao_config_view.php');
require_once('classes/output/avaliacao_config_pdf.php');
require_once('classes/output/avaliador_config_view.php');
require_once('classes/output/barema_config_lista.php');
require_once('classes/output/relatorio_barema_config_pdf.php');
require_once('classes/output/relatorio_barema_config_xls.php');
require_once('classes/output/gerenciar_alunos.php');
require_once('classes/output/admin_alunos.php');
require_once('classes/output/admin_curso.php');
require_once('classes/output/alunos.php');
require_once('classes/privacy/pos_contact.php');
//require_once($CFG->dirroot . '/local/contact/classes/local_contact.php');
require_once ('models/criar_barema.php');
require_once ('models/prazos.php');
//require('../../vendor/autoload.php');
use Dompdf\Dompdf;

//require_once ('models/jquery_cad_avaliacao.php');

class block_eva_form_barema extends block_base {

    function init() {
        $this->title = get_string('pluginname', 'block_eva_form_barema');

    }

    /**
     * The block is usable in all pages
     */
    function applicable_formats() {
        $ccnBlockHandler = new ccnBlockHandler();
        return $ccnBlockHandler->ccnGetBlockApplicability(array('all'));
    }

    /**
     * The block can be used repeatedly in a page.
     */
    function instance_allow_multiple() {
        return true;
    }

    public function specialization() {
        global $CFG, $DB;
        include($CFG->dirroot.'/theme/evagu/ccn/block_handler/specialization.php');

    }

    /**
     * Allow the user to configure a block instance
     * @return bool Returns true
     */
    function instance_allow_config() {
        return true;
    }

    function get_content() {
        global $CFG, $DB, $PAGE, $USER;

       if ($this->content !== null) {
            return $this->content;
        }

        $id = $_GET['id'];
        $gerenciar_alunos = $_GET['avaliador'];
        $qt_alunos = $_GET['qt_aluno_por_avaliador'];


        $arraypath = explode('/', $PAGE->docspath);

        $fildsbarema = [];
        foreach ($_POST as $key => $value) {
            $fildsbarema[$key] = $value;
        }

        $fildsinputs = [];
        $modelo = new \stdClass();
        foreach ($_POST as $key => $value) {
            $fildsinputs[$key] = $value;
            $modelo->$key = $value;
        }

        if ($arraypath[2] === 'modelos') {
            if ( $_GET['modelo'] == "novo"){
                if ($modelo->submitbutton == "Cadastrar") {
                    criar_modelo_pos($modelo);
                }
            }
        }


        //=========Aqui evita que novas instancia do block "eva_form_barema" faça alteraçao na table "mdl_eva_barema"================
        if ($arraypath[2] === 'atribuicao') {
//            $configdata = $DB->get_record('eva_barema', array('id'=>1));
//            if (!hash_equals($configdata->hash_barema, $this->instance->configdata)) {
//                //=========Chama a função de inserção de Modelo Barema==================
//                block_instance_barema($this->instance);
//            }

//            $sql = "TRUNCATE TABLE 'carrinho'";
//            $DB->



            $novobarema = new \block_eva_form_barema\output\AtribuicaoController($CFG->wwwroot . '/blocks/eva_form_barema/atribuicao/create.php?id='.$id, null, 'post');
            $returnurlbarema = new moodle_url('/blocks/eva_form_barema/atribuicao/create.php?id='.$id);
            cadastrar_atribuicao($novobarema, $returnurlbarema, $fildsbarema);
        }


        if ($arraypath[2] === 'barema_avaliacao') {

//            $id_existes = $DB->get_records('eva_barema_avaliacao', array('aluno_tb_user_id'=>25));
//            foreach ($id_existes as $idexiste) {
//                $tb_avaliador = $DB->get_record('eva_barema_avaliador', array('id' => $idexiste->tb_avaliador_id));
//            var_dump($idexiste->status);die();
//            }

//            $returnurl = new moodle_url($CFG->wwwroot . '/blocks/eva_form_barema/barema_avaliacao.php?barema_id='.$_GET['barema_id'].'&avaliador_id='.$_GET['avaliador_id'].'&curso_id='.$_GET['curso_id'].'&quiz_id='.$_GET['quiz_id'].'&aluno_id='.$_GET['aluno_id']);
            $returnurl = new moodle_url($CFG->wwwroot . '/blocks/eva_form_barema/gerencia.php?qt_aluno_por_avaliador='.$_GET['tb_id_avaliador']);


            if ($barema_id = $_GET['barema_id']) {
                $barema = $DB->get_record('eva_barema', array('id'=>$barema_id));
                if(!hash_equals($barema->hash_barema, $this->instance->configdata)) {
                    $DB->update_record('block_instances', array('id'=>$this->instance->id, 'configdata' =>$barema->hash_barema));
                    redirect($returnurl);
                }
            }

            controller_barema_avaliacao($returnurl, $fildsbarema);
        }



        $customize_config = new \block_eva_form_barema\output\customize_conf($this->config, $this->context);

        $avaliador_lista = new \block_eva_form_barema\output\AvaliadorController($this->config, $this->context);

        $avaliacao_config_view = new \block_eva_form_barema\output\avaliacao_config_view($this->config, $this->context);

        $avaliador_config_view = new \block_eva_form_barema\output\avaliador_config_view($this->config, $this->context);

        $novo_modelo = new \block_eva_form_barema\output\ModeloController($this->config, $this->context);

        $form_avaliador = new \block_eva_form_barema\output\form_barema($this->config, $this->context);

        $renderer = $this->page->get_renderer('block_eva_form_barema');

        $this->content          = new stdClass;

        if ($arraypath[2] === 'atribuicao') {
            $this->content->text = $renderer->render($novobarema);
        }else if ($arraypath[2] === 'modelos') {
            $this->content->text = $renderer->render($novo_modelo);
        }else if ($arraypath[2] === 'barema_avaliacao') {
            $this->content->text  = $renderer->render($form_avaliador);
        }else if ($arraypath[2] === 'gerencia') {
            if ($_GET['qt_aluno_por_avaliador']){
                $alunos = new \block_eva_form_barema\output\alunos($this->config, $this->context);
                $this->content->text  = $renderer->render($alunos);
            }elseif ($_GET['avaliador']){
                $avaliacao = new \block_eva_form_barema\output\gerenciar_alunos($this->config, $this->context);
                $this->content->text  = $renderer->render($avaliacao);
            }elseif ($_GET['admin'] == 'alunos'){
                $avaliacao = new \block_eva_form_barema\output\admin_alunos($this->config, $this->context);
                $this->content->text  = $renderer->render($avaliacao);
            }elseif ($_GET['admin'] == 'curso'){
                $avaliacao = new \block_eva_form_barema\output\admin_curso($this->config, $this->context);
                $this->content->text  = $renderer->render($avaliacao);
            }elseif ( $_GET['admin'] == 'avaliacao_pendente'){
                $returnurl = new moodle_url('/blocks/eva_form_barema/gerencia.php?admin=curso');
                emails_pendente_avaliacao_pos($returnurl, $fildsinputs);
            }
        }else if ($arraypath[2] === 'barema_lista') {

            $barema_config_lista = new \block_eva_form_barema\output\barema_config_lista($this->config, $this->context);
            $this->content->text = $renderer->render($barema_config_lista);

            if ( $_GET['attemp'] == 'view'){
                require_once '../../dompdf/autoload.inc.php';
                
                $avaliacao_config_pdf = new \block_eva_form_barema\output\avaliacao_config_pdf($this->config, $this->context);
                $view =  $renderer->render($avaliacao_config_pdf);

                $dompdf = new Dompdf(["enable_remote", true]);
                $dompdf->loadHtml($view);
                $dompdf->setPaper("A4");
                $dompdf->render();
                $dompdf->stream("espelho.pdf", ["Attachment"=>false]);
                exit();
            }

            if ( $_GET['relatorio'] == 'pdf'){
                require_once '../../dompdf/autoload.inc.php';
                $relatorio_barema_config_pdf = new \block_eva_form_barema\output\relatorio_barema_config_pdf($this->config, $this->context);

                $relatorio =  $renderer->render($relatorio_barema_config_pdf);


                $dompdf = new Dompdf(["enable_remote", true]);
                $dompdf->loadHtml($relatorio);
                $dompdf->setPaper("A4", "landscape");
                $dompdf->render();
                $dompdf->stream("relatorio.pdf", ["Attachment"=>false]);
                exit();
            }
            if ( $_GET['relatorio'] == 'xls'){

                $relatorio_barema_config_xls = new \block_eva_form_barema\output\relatorio_barema_config_xls($this->config, $this->context);
                $relatorio =  $renderer->render($relatorio_barema_config_xls);
                $arquivo = 'lista_avaliacao.xls';

                header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                header("Last-Modified: " . gmdate("D,d M YH:t:s") . " GMT");
                header("Cache-Control: no-cache, must-revalidate");
                header("Pragma: no-cache");
                header("Content-type: application/x-msexcel");
                header("Content-Disposition: attachment; filename=\"{$arquivo}\"");
                header("Content-Description: PHP Generated Data");
                echo $relatorio;
                exit();
            }

        }else if ($arraypath[2] === 'avaliadores'){
            $this->content->text = $renderer->render($avaliador_lista);
        }

//        $this->content->text = $renderer->render($avaliador_config_view);

        $this->content->footer = '';
        return $this->content;
    }

    public function html_attributes() {
        global $CFG;
        $attributes = parent::html_attributes();
        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/attributes.php');
        return $attributes;
    }
}