<?php

namespace block_eva_form_barema\output;
defined('MOODLE_INTERNAL') || die();

//use block_rss_client\output\item;
use moodleform;
use moodle_url;
use renderer_base;
use renderable;
use templatable;

require_once($CFG->libdir.'/formslib.php');

class AtribuicaoController extends moodleform implements renderable, templatable {

    private function get_barema() {
        global $DB;
        $dadosbarema = $DB->get_record('eva_barema_avaliacao', array(), '*');
        return $dadosbarema;
    }

    private function get_users()
    {
        global $DB, $OUTPUT, $PAGE;

        $usernames = [];

        $barema = $DB->get_record('eva_barema_avaliacao', array(), '*');
        $selectuser = $DB->get_fieldset_select('eva_barema_avaliador', 'avaliador_userid', 'baremaid = :baremaid', ["baremaid"=>$barema->id]);

        if (empty($selectuser)) return [];
        $ids = $selectuser;
//
        list($uids, $params) = $DB->get_in_or_equal($ids);
        $rs = $DB->get_recordset_select('user', 'id ' . $uids, $params, '', 'id,firstname,lastname,email');

        foreach ($rs as $record) {
            $usernames[$record->id] = fullname($record) . ' ' . $record->email;
        }
        $rs->close();
        return $usernames;
    }

    private function get_avaliadores() {

        global $DB;
        $sql = "SELECT vu.id, vu.fullname, vu.email 
                FROM vw_autocomplete_user vu 
                inner join mdl_eva_barema_avaliadores eba  ON eba.avaliador_id = vu.id
                where eba.status = 1";

        $avaliadores = $DB->get_records_sql($sql);

        foreach ($avaliadores as $key=>$ava) {
            //====Aqui verifica se o anteprojeto ja foi atribuido a um avaliador - se true, e o ativo = 1 retirado da selecao, se o ativo=0 retorna pra seleção=====
            //====O ativo estando 0 é porque todos os dados da atribuição esta invalido=====

//            $existe = $DB->record_exists('eva_afastamento_atribuicao', array('tb_anteprojeto_id'=>$anteprojeto->id, 'ativo'=>1));
//            if (!$existe){
//            }
                $avaliador[$key] = $ava->fullname;
        }

        return $avaliador;
    }

    private function distribuicao_de_alunos (){
        global $DB;
        $distribuicoes = $DB->get_records('eva_barema_distribuicao', array());

        return $distribuicoes;
    }

    private function get_categoria () {
        global $DB;

        $coursecategories = $DB->get_records('course_categories', array("parent"=>0), 'id ASC');
        $i=0;
        foreach ($coursecategories as $key=>$categoria) {
            $option[$i]['text'] = $categoria->name;
            $option[$i]['attr']['value'] = $key;
            $i++;
        }
        $default_cargo[0]['text'] = 'SELECIONE UMA CATEGORIA';
        $default_cargo[0]['attr']['value'] = "";
        $categorias = array_merge($default_cargo, $option);

        return $categorias;
    }

    private function get_modelo_barema() {

        global $DB;
        $modelos = $DB->get_records('eva_barema', array());
        $i=0;
        foreach ($modelos as $key=>$modelo) {
            if ($modelo->nome_modelo && $modelo->ativo){
                $option[$i]['text'] = $modelo->nome_modelo;
                $option[$i]['attr']['value'] = $key;
                $i++;
            }
        }
        $default_cargo[0]['text'] = 'SELECIONE UM MODELO';
        $default_cargo[0]['attr']['value'] = "";
        $barema = array_merge($default_cargo, $option);

        return $barema;
    }

    public function definition()
    {
        global $CFG, $USER, $PAGE;

        $mform = $this->_form; // Don't forget the underscore!

//        $mform->addElement('header', 'createuserandpass', get_string('avaliadorrandpass', 'block_eva_form_barema'), '');

        $dadosbarema = $this->get_barema();

        $barema = $this->get_modelo_barema();
        $categorias = $this->get_categoria();

        $mform->addElement('static', 'baremainfo', '', get_string('infobarema', 'block_eva_form_barema'));

        $select_barema = $mform->addElement('select', 'tb_barema_id', get_string('namebarema', 'block_eva_form_barema'));
        $select_barema->_options = $barema;
        $mform->setType('tb_barema_id', PARAM_NOTAGS);
        $mform->addRule('tb_barema_id', get_string('missingbarema', 'block_eva_form_barema'), 'required', null, 'client');
//        $mform->setForceLtr('barema');

        $usernames = $this->get_avaliadores();

        $mform->addElement('autocomplete', 'avaliador_tb_user_id', get_string('selecaoavaliadorname', 'block_eva_form_barema'), $usernames, [
            'multiple' => true,
//            'ajax' => 'tool_lp/form-user-selector',
        ]);
        $mform->addRule('avaliador_tb_user_id', 'Está faltando avaliadores.', 'required', null, 'client');


//        $mform->addElement('header', 'dadoscursorandpass', get_string('dadoscursorandpass', 'block_eva_form_barema'), '');

        $mform->addElement('static', 'cursoinfo', '', get_string('infocurso', 'block_eva_form_barema'));

        $select_categoria = $mform->addElement('select', 'tb_categoria_id', get_string('dadoscategoria', 'block_eva_form_barema'));
        $select_categoria->_options = $categorias;
        $mform->setType('tb_categoria_id', PARAM_NOTAGS);
        $mform->addRule('tb_categoria_id', get_string('missingcategoria', 'block_eva_form_barema'), 'required', null, 'client');

        $mform->addElement('select', 'tb_subcategoria_id', get_string('dadossubcategoria', 'block_eva_form_barema'));
        $mform->setType('tb_subcategoria_id', PARAM_NOTAGS);

        $mform->addElement('select', 'tb_curso_id', get_string('dadoscurso', 'block_eva_form_barema'));
        $mform->setType('tb_curso_id', PARAM_NOTAGS);
        $mform->addRule('tb_curso_id', get_string('missingcurso', 'block_eva_form_barema'), 'required', null, 'client');

        $mform->addElement('select', 'tb_atividade_id', get_string('dadosativadade', 'block_eva_form_barema'));
        $mform->setType('tb_atividade_id', PARAM_NOTAGS);
        $mform->addRule('tb_atividade_id', get_string('missingatividade', 'block_eva_form_barema'), 'required', null, 'client');

        $mform->addElement('textarea', 'resposta_padrao', get_string("respostapadrao", "block_eva_form_barema"), 'wrap="virtual" cols="10" rows="5"');
        $mform->setType('resposta_padrao', PARAM_NOTAGS);                   //Set type of element
//        $mform->addRule('message', get_string('missingsolicitetext', 'block_eva_form_library'), 'required', null, 'client');
        $mform->setForceLtr('resposta_padrao');

//        $labelbutton = get_string('submit_barema', 'block_eva_form_barema');
//        $this->add_action_buttons(true, $labelbutton);

//        $mform->addElement('button', 'dist', 'Distribuir');
//        $mform->addElement('button', 'dist', 'Distribuir');
//        var_dump($bt);die();
        $mform->addElement('static', 'prazoinfo', '', 'OS Alunos terão (x)dias de prazo para o obter 100% da nota da avaliação, após esse prazo serão descontado 10% da sua nota total.');
        $mform->addElement('text', 'prazo', 'Prazo de dias', 'value="15" style="width:25%" placeholder="Qtd de dias?"');
        $mform->setType('prazo', PARAM_NOTAGS);
        $mform->addRule('prazo', 'Quantidade de dias é Obrigatorio', 'required', null, 'client');

        $mform->addElement('static', 'dist', 'Distribuição', 'Avaliadores');


        $tags = [
            $mform->createElement('button', 'dist', 'Distribuir', 'style="background-color: #0a3f70;"'),
            $mform->createElement('submit', 'submitbutton', 'Cadastrar', 'hidden'), //'class="d-none"
            $mform->createElement('button', 'cancel', 'Cancelar')
        ];
        $mform->addGroup($tags, '', '', '', '');

    }

    /**
     * Validate user supplied data on the signup form.
     *
     * @param array $data array of ("fieldname"=>value) of submitted data
     * @param array $files array of uploaded files "element_name"=>tmp_file_path
     * @return array of "element_name"=>"error_description" if there are errors,
     *         or an empty array if everything is OK (true allowed for backwards compatibility too).
     */
//    public function validation($data, $files) {
//
//        $errors = parent::validation($data, $files);
//        $errors += library_validate_data($data, $files);
//        return $errors;
//    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return stdClass
     */

    public function export_for_template(renderer_base $output)
    {
        global $CFG, $DB, $USER;

        $novo_barema_id = $DB->get_record('eva_barema', array('id'=>1));

        $data = new \stdClass();

        ob_start();
        $this->display();
        $formbarema = ob_get_contents();
        ob_end_clean();

        $data->idbarema = $novo_barema_id->numero_barema;
        $data->formbarema = $formbarema;
        $data->admavaliacao = '/blocks/eva_form_barema/gerencia.php?admin=curso';

        return $data;
    }

}