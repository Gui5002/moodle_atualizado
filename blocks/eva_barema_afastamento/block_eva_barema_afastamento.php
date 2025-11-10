<?php
/**
 * @author Celio Pereira Batalha
 * email: celio.batalha@gmail.com
 */

require_once($CFG->dirroot. '/theme/evagu/ccn/block_handler/ccn_block_handler.php');

require_once('classes/output/criar_afastamento_atribuicao.php');
require_once('classes/output/criar_modelo_afastamento.php');
require_once('classes/output/avaliacao_afastamento_page.php');
require_once('classes/output/avaliador_lista.php');
require_once('classes/output/avaliacao_lista.php');
require_once('classes/output/avaliacao_lista_pdf.php');
require_once('classes/output/gerenciar_espelho_pdf.php');
require_once('classes/output/admin_avaliacao.php');
require_once('classes/output/gerenciar_avaliacao.php');
require_once('classes/output/anteprojeto_espelho_pdf.php');
require_once('classes/privacy/afastamento_contact.php');
//require_once($CFG->dirroot . '/local/contact/classes/local_contact.php');
require_once ('models/create_afastamento.php');

use Dompdf\Dompdf;

class block_eva_barema_afastamento extends block_base
{
    function init() {
        $this->title = get_string('pluginname', 'block_eva_barema_afastamento');

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
        $url_atrib = $_GET['atrib'];
        $admin_avaliacao = $_GET['admin'];
        $gerenciar_avaliacao = $_GET['gerenciar'];


        $arraypath = explode('/', $PAGE->docspath);
        $arrayparam = $_GET;

        $fildsinputs = [];
        $modelo = new \stdClass();
        foreach ($_POST as $key => $value) {
            $fildsinputs[$key] = $value;
            $modelo->$key = $value;
        }

        if ($arraypath[2] === 'criar_modelo') {
            if ( $_GET['modelo'] == "novo"){
                if ($modelo->submitbutton == "Cadastrar") {
                    criar_modelo_afastamento($modelo);
                }
            }
        }


        //=========Aqui evita que novas instancia do block "eva_barema_afastamento" faça alteraçao na table "mdl_eva_afastamento"================
        if ($arraypath[2] === 'create') {
//            $configdata = $DB->get_record('eva_afastamento_modelo', array('id' => 1));
//            if (!hash_equals($configdata->hash_barema, $this->instance->configdata)) {
//                //=========Chama a função de inserção de Modelo Barema==================
//                block_instance_afastamento($this->instance);
//            }
            $formatrib = new \block_eva_barema_afastamento\output\criar_afastamento_atribuicao($CFG->wwwroot . '/blocks/eva_barema_afastamento/create.php?id=' . $id, null, 'post');
            $returnurl = new moodle_url('/blocks/eva_barema_afastamento/create.php?id=' . $id);
            form_atribuicao_afastamento($formatrib, $returnurl, $fildsinputs);
        }

        if ($arraypath[2] === 'avaliacao_afastamento') {

            $returnurl = new moodle_url($CFG->wwwroot . '/blocks/eva_barema_afastamento/avaliacao_afastamento.php?atrib='.$url_atrib);
//            $returnurl = new moodle_url($CFG->wwwroot . '/blocks/eva_barema_afastamento/avaliacao_afastamento.php?barema_id='.$_GET['barema_id'].'&avaliador_id='.$_GET['avaliador_id']);

            $sql = "SELECT * FROM mdl_eva_afastamento_atribuicao WHERE url_atrib = '{$url_atrib}' ";
            $dados_atribuicao = $DB->get_record_sql($sql);

            $barema = $DB->get_record('eva_afastamento_modelo', array('id'=>$dados_atribuicao->tb_afastamento_modelo_id));
            if(!hash_equals($barema->hash_barema, $this->instance->configdata)) {
                $DB->update_record('block_instances', array('id'=>$this->instance->id, 'configdata' =>$barema->hash_barema));
                redirect($returnurl);
            }
            afastamento_avaliacao($returnurl, $fildsinputs);
        }

        $avaliador_lista = new \block_eva_barema_afastamento\output\avaliador_lista($this->config, $this->context);

        if ($admin_avaliacao) {
            $avaliacao = new \block_eva_barema_afastamento\output\admin_avaliacao($this->config, $this->context);
        }else if ($gerenciar_avaliacao) {
            $avaliacao = new \block_eva_barema_afastamento\output\gerenciar_avaliacao($this->config, $this->context);
        }

        $page_avaliacao = new \block_eva_barema_afastamento\output\avaliacao_afastamento_page($this->config, $this->context);

        $novo_modelo = new \block_eva_barema_afastamento\output\criar_modelo_afastamento($this->config, $this->context);

        $renderer = $this->page->get_renderer('block_eva_barema_afastamento');

        $this->content        = new stdClass;


        if ($arraypath[2] === 'create') {
            $this->content->text  = $renderer->render($formatrib);
        }else if ($arraypath[2] === 'criar_modelo') {
            $this->content->text = $renderer->render($novo_modelo);
        }
        else if ($arraypath[2] === 'avaliacao_afastamento') {
            $this->content->text  = $renderer->render($page_avaliacao);
        }else if ($arraypath[2] === 'avaliadores') {
            $this->content->text  = $renderer->render($avaliador_lista);
        }else if ($arraypath[2] === 'avaliacao') {
            $this->content->text  = $renderer->render($avaliacao);

            if ( $_GET['email'] == 'pendente'){
                $returnurl = new moodle_url('/blocks/eva_barema_afastamento/avaliacao.php?admin='. $USER->id);
                emails_pendente_avaliacao_afastamento($returnurl, $fildsinputs);
            }

            if ( $_GET['espelho']){
                require_once '../../dompdf/autoload.inc.php';
                $gerenciar_espelho = new \block_eva_barema_afastamento\output\gerenciar_espelho_pdf($this->config, $this->context);
                $view = $renderer->render($gerenciar_espelho);

                $dompdf = new Dompdf(["enable_remote", true]);
                $dompdf->loadHtml($view);
                $dompdf->setPaper("A4");
                $dompdf->render();
                $dompdf->stream("espelho.pdf", ["Attachment"=>false]);
                exit();
            }
        }else if ($arraypath[2] === 'avaliacao_lista') {
            $avaliacao_lista = new \block_eva_barema_afastamento\output\avaliacao_lista($this->config, $this->context);
            $this->content->text = $renderer->render($avaliacao_lista);

            if ( $_GET['anteprojeto'] == 'espelho'){
                require_once '../../dompdf/autoload.inc.php';
                $gerenciar_espelho = new \block_eva_barema_afastamento\output\anteprojeto_espelho_pdf($this->config, $this->context);
                $view = $renderer->render($gerenciar_espelho);

                $dompdf = new Dompdf(["enable_remote", true]);
                $dompdf->loadHtml($view);
                $dompdf->setPaper("A4");
                $dompdf->render();
                $dompdf->stream("espelho.pdf", ["Attachment"=>false]);
                exit();
            }

            $avaliacaolista = new \block_eva_barema_afastamento\output\avaliacao_lista_pdf($this->config, $this->context);

            if ( $_GET['avaliacao'] == 'pdf'){
                require_once '../../dompdf/autoload.inc.php';
                $view = $renderer->render($avaliacaolista);

                $dompdf = new Dompdf(["enable_remote", true]);
                $dompdf->loadHtml($view);
                $dompdf->setPaper("A4");
                $dompdf->render();
                $dompdf->stream("espelho.pdf", ["Attachment"=>false]);
                exit();
            }
            if ( $_GET['avaliacao'] == 'xls'){

                $relatorio =  $renderer->render($avaliacaolista);
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

        }

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