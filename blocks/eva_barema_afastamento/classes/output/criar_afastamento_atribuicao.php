<?php

namespace block_eva_barema_afastamento\output;
defined('MOODLE_INTERNAL') || die();

//use block_rss_client\output\item;
use DateTime;
use moodleform;
use moodle_url;
use renderer_base;
use renderable;
use templatable;

require_once($CFG->libdir.'/formslib.php');

class criar_afastamento_atribuicao extends moodleform implements renderable, templatable
{

    private function juridico_gestao(){
        global $DB;

        $ante_eixo = $DB->get_record('eva_afastamento_jur_ges', array());

        return $ante_eixo->eixo;

    }

    private function get_anteprojeto()
    {
        global $DB;
        $eixo = $DB->get_record('eva_afastamento_jur_ges', array());
        $anteprojetos = $DB->get_records('eva_afastamento_anteprojeto', array('distribuicao'=>$eixo->eixo));


        foreach ($anteprojetos as $key=>$anteprojeto) {
            //====Aqui verifica se o anteprojeto ja foi atribuido a um avaliador - se true, e o ativo = 1 retirado da selecao, se o ativo=0 retorna pra seleção=====
            //====O ativo estando 0 é porque todos os dados da atribuição esta invalido=====
            $existe = $DB->record_exists('eva_afastamento_atribuicao', array('tb_anteprojeto_id'=>$anteprojeto->id, 'ativo'=>1));
            if (!$existe){
                $antepj[$key] = $anteprojeto->anteprojeto;
            }
        }
        return $antepj;
    }

//    private function get_anteprojeto() {
//
//        global $DB;
//        $anteprojetos = $DB->get_records('eva_afastamento_anteprojeto', array());
////        var_dump($anteprojetos);die();
//
//        $i=0;
//        foreach ($anteprojetos as $key=>$anteprojeto) {
////            if ($modelo->nome_modelo && $modelo->ativo){
//                $option[$i]['text'] = $anteprojeto->anteprojeto;
//                $option[$i]['attr']['value'] = $key;
//                $i++;
////            }
//        }
//        $default_ant[0]['text'] = 'SELECIONE ANTEPROJETO';
//        $default_ant[0]['attr']['value'] = "";
//        $anteprojeto = array_merge($default_ant, $option);
//
//        return $anteprojeto;
//    }
    private function get_modelo_barema() {

        global $DB;
        $modelos = $DB->get_records('eva_afastamento_modelo', array());
        $i=0;
        foreach ($modelos as $key=>$modelo) {
            if ($modelo->nome_modelo){
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

    private function get_avaliadores() {

        global $DB;
//        $modelos = $DB->get_records('eva_avaliadores', array());

        $sql = "SELECT fullname, email FROM vw_autocomplete_user vu 
                inner join mdl_eva_avaliadores ea  ON ea.user_id = vu.id";

        $avaliadores = $DB->get_records_sql($sql);

//        var_dump($avaliadores);die();
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

    /**
     * @inheritDoc
     */
    protected function definition()
    {
        // TODO: Implement definition() method.
        global $CFG, $USER, $PAGE;

        $mform = $this->_form; // Don't forget the underscore!

        $mform->addElement('static', 'baremainfo', '', 'Formulário de Atribuição para o Projeto de afastamento');

        $barema = $this->get_modelo_barema();

        $anteprojeto = $this->get_anteprojeto();

        $radio = $this->juridico_gestao();
        $checked_eixo = $radio;
        $radioarray=array();
        $radioarray[] = $mform->createElement('radio', 'eixo', '', 'Jurídico', 'j');
        $radioarray[] = $mform->createElement('radio', 'eixo', '', 'Gestão', 'g');
        $mform->addGroup($radioarray, 'radioar', 'Escolha a distribuição', array(' '), false);
        $mform->setDefault('eixo', $checked_eixo);
        $mform->addRule('radioar', 'Escolha uma distribuição', 'required', null, 'client');

        $select_barema = $mform->addElement('select', 'tb_afastamento_modelo_id', 'Modelo de afastamento');
        $select_barema->_options = $barema;
        $mform->setType('tb_afastamento_modelo_id', PARAM_NOTAGS);
        $mform->addRule('tb_afastamento_modelo_id', 'O campo modelo está vazio', 'required', null, 'client');



        if ($radio == 'j'){
            $mform->addElement('autocomplete', 'tb_anteprojeto_id', 'Anteprojeto Juridico', $anteprojeto,[
                'multiple' => true,
            ]);
        }
        if ($radio == 'g'){
            $mform->addElement('autocomplete', 'tb_anteprojeto_id', 'Anteprojeto Gestão', $anteprojeto,[
                'multiple' => true,
            ]);
        }

//        var_dump($teste);die();
//
//        $select_anteprojeto = $mform->addElement('select', 'tb_anteprojeto_id', 'Anteprojeto');
//        $select_anteprojeto->_options = $anteprojeto;
//        $mform->setType('tb_anteprojeto_id', PARAM_NOTAGS);
//        $mform->addRule('tb_anteprojeto_id', 'O campo anteprojeto está vazio', 'required', null, 'client');

        $mform->addElement('select', 'avaliador_1', 'Avaliador 1');
        $mform->setType('avaliador_1', PARAM_NOTAGS);
        $mform->addRule('avaliador_1', 'Selecione o avalidor', 'required', null, 'client');

        $mform->addElement('select', 'avaliador_2', 'Avaliador 2');
        $mform->setType('avaliador_2', PARAM_NOTAGS);
        $mform->addRule('avaliador_2', 'Selecione o avalidor', 'required', null, 'client');

        $mform->addElement('select', 'avaliador_3', 'Avaliador 3');
        $mform->setType('avaliador_3', PARAM_NOTAGS);
        $mform->addRule('avaliador_3', 'Selecione o avalidor', 'required', null, 'client');

        $mform->addElement('date_time_selector', 'startdate_ava', 'Data de início da avaliacão:');
//        $mform->addHelpButton('startdate_ava', 'startdate');
        $date = (new DateTime())->setTimestamp(usergetmidnight(time()));
        $date->modify('+1 day');
        $mform->setDefault('startdate', $date->getTimestamp());

//        $options = range(0, 10);
//        $mform->addElement('select', 'qt_dias_ava', 'Quantidade de dias para Avaliação:', $options);

        $mform->addElement('text', 'qt_dias_ava', 'Quantidade de dias para Avaliação:', 'maxlength="2"');
        $mform->setType('qt_dias_ava', PARAM_TEXT);
        $mform->addRule('qt_dias_ava', 'Quantidade de dias para avaliação', 'required', null, 'client');


//       $teste = $mform->addElement('autocomplete', 'avaliador_tb_user_id', get_string('selecaoavaliadorname', 'block_eva_form_barema'),'', [
//            'multiple' => true,
//            'ajax' => 'tool_lp/form-user-selector',
//        ]);


        $labelbutton = 'Cadastrar';

        $this->add_action_buttons(true, $labelbutton);
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

        $create_id = $DB->get_record('eva_afastamento_modelo', array('id'=>1));

        $data = new \stdClass();

        ob_start();
        $this->display();
        $formafastamento = ob_get_contents();
        ob_end_clean();

        $data->idbarema = $create_id->numero_barema;
        $data->formafastamento = $formafastamento;

        $dadoschecks = $DB->get_records('eva_articulacao_prioritaria', array());

        $i=0;
        $j=0;
        foreach ($dadoschecks as $dadoscheck) {
            if ($dadoscheck->eixo == 'juridico') {
                $juridico[$i]['id'] = $dadoscheck->id;
                $juridico[$i]['numero'] = $dadoscheck->numero;
                $juridico[$i]['articulacaojuridica'] = $dadoscheck->articulacao;
                $i++;
            }
            if ($dadoscheck->eixo == 'tecnico') {
                $tecnico[$j]['id'] = $dadoscheck->id;
                $tecnico[$j]['numero'] = $dadoscheck->numero;
                $tecnico[$j]['articulacaotecnica'] = $dadoscheck->articulacao;
                $j++;
            }
        }

        $permissao = $DB->record_exists('eva_barema_permissao', array('user_id'=>$USER->id, 'afastamento'=>1));
        if (!$permissao) {
            $data->idmsgpermission = "id_msg_permissionBtn";
            $data->admavaliacao = "#";
        }else {
            $data->admavaliacao = "/blocks/eva_barema_afastamento/avaliacao.php?admin=".$USER->id;
        }

        $data->chekejuridico = $juridico;
        $data->chekegestao = $tecnico;


        return $data;

    }

}