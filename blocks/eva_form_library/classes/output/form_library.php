<?php
//namespace block_eva_form_library\output;
//defined('MOODLE_INTERNAL') || die();
//
////use block_rss_client\output\item;
//use moodleform;
//use moodle_url;
//use renderer_base;
//use renderable;
//use templatable;
//use function Complex\divideby;
//
//require_once($CFG->libdir.'/formslib.php');
//require_once('lib/librarylib.php');
//
//class form_library extends moodleform implements renderable, templatable {
//
//    public function definition() {
//        global $USER, $CFG, $PAGE, $DB;
//
//
//        if ($_GET['externo'] != "true"){
//            if (!isloggedin()) {
//                require_login();
//            }
//        }
//
//
//        $PAGE->requires->css(new moodle_url($CFG->wwwroot . '/blocks/eva_form_library/form_library.css'));
//
//        $mform = $this->_form; // Don't forget the underscore!
//
//        $mform->addElement('header', 'createuserandpass', get_string('createuserandpass'), '');
//        $data = date("Y-m-d h:i:sa");
//
//        $user = $USER->firstname . ' ' . $USER->lastname;
//
//        $mform->addElement('hidden', 'id_user', $USER->id); // Add elements to your form
//        $mform->addElement('hidden', 'name', $user); // Add elements to your form
//        $mform->addElement('hidden', 'email', $USER->email); // Add elements to your form
//
//        $mform->addElement('text', 'solicitante', get_string('solicitename', 'block_eva_form_library')); // Add elements to your form
//        $mform->setType('solicitante', PARAM_NOTAGS);                   //Set type of element
//        $mform->addRule('solicitante', get_string('missingsolicitename', 'block_eva_form_library'), 'required', null, 'client');
//        $mform->setForceLtr('solicitante');
//
//        $mform->addElement('text', 'email_institucional', get_string('emailinstitucionallib', 'block_eva_form_library'));
//        $mform->setType('email_institucional', PARAM_NOTAGS);
//        $mform->addRule('email_institucional', get_string('missingemaillib', 'block_eva_form_library'), 'required', null, 'client');
//        $mform->setForceLtr('email_institucional');
//
//
//
//        $artigo = $mform->addElement('checkbox', 'artigo', 'Tipo de Solicitação', get_string('tipoartigo', 'block_eva_form_library'));
//        $artigo->_attributes = array('name'=>'artigo','type'=>'checkbox','value'=>'copia de artigo periodicos');
//
//        if (!$_GET['externo']) {
//            $emprestimo = $mform->addElement('checkbox', 'emprestimo', '', get_string('tipoemplivro', 'block_eva_form_library'));
//            $emprestimo->_attributes = array('name'=>'emprestimo','type'=>'checkbox','value'=>'emprestimo de livro');
//        }
//
//        $copia = $mform->addElement('checkbox', 'livro', '', get_string('tipocopialivro', 'block_eva_form_library'));
//        $copia->_attributes = array('name'=>'livro','type'=>'checkbox','value'=>'copia capitulo de livro');
//
//        $solicitacao = $mform->addElement('checkbox', 'pesquisa', '', get_string('tiposolicitacao', 'block_eva_form_library'));
//        $solicitacao->_attributes = array('name'=>'pesquisa','type'=>'checkbox','value'=>'solicitacao de pesquisa');
//
//
//        $dadolocals = $DB->get_records('eva_biblioteca_local', array(), 'id ASC');
//        foreach ($dadolocals as $key=>$dadolocal) {
//            $local[$dadolocal->local] = $dadolocal->local;
//        }
//
//        $default_local[''] = get_string('selectlocal', 'block_eva_form_library');
//        $local = array_merge($default_local, $local);
//        $localemail = $mform->addElement('select', 'biblioteca', get_string('locallib', 'block_eva_form_library'), $local);
//        $mform->setType('biblioteca', PARAM_NOTAGS);
//        $mform->addRule('biblioteca', get_string('missinglocallib', 'block_eva_form_library'), 'required', null, 'client');
//
//
//        $mform->addElement('textarea', 'area_message', get_string("solicitacaotext", "block_eva_form_library"), 'wrap="virtual" rows="5" cols="20"
//        placeholder=" Descreva informações completas a respeito da(s) demanda(s) para que a Biblioteca possa lhe dar um retorno. Quanto mais informações, maior a probabilidade de obter-se resultados relevantes para a(s) solicitação(ões)."');
//        $mform->setType('area_message', PARAM_NOTAGS);                   //Set type of element
//        $mform->addRule('area_message', get_string('missingsolicitetext', 'block_eva_form_library'), 'required', null, 'client');
//        $mform->setForceLtr('area_message');
//
//        $mform->addElement('static', '1_info','', '<div class="ml-2" style="font-family: "Montserrat", Verdana, Sans-Serif"><b>ATENÇÃO! <br></b><p>- O empréstimo de livros físicos está disponível apenas para servidores e membros de Brasília cadastrados na Biblioteca.</p>');
//
//        $mform->addElement('static', 'teste', '', 'testestte');
//////                Sou de Brasília e estou de acordo com o Regulamento da Biblioteca (<span style="color: red">Portaria nº 4, de 11 de março de 2020</span> ).</p>
////
////        $mform->addElement('static', 'information2', '
////                <p>* A Biblioteca receberá as seguintes informações do usuário logado para fins de cadastro nos sistemas de gerenciamento de acervo: nome completo, e-mail institucional, e-mail alternativo, cargo, lotação e SIAPE,CPF.
////                </p>
////            </div>
////        ');
//
//        $ccemail = $mform->addElement('checkbox', 'cc', '', get_string('comcopia', 'block_eva_form_library'));
//        $ccemail->_attributes = array('name'=>'cc','type'=>'checkbox','value'=>$USER->email);
//
//        $mform->addElement('hidden', 'created', $data);
//
//        $this->add_action_buttons(true, get_string('submitLibrary', 'block_eva_form_library'));
//
//    }
//    /**
//     * Validate user supplied data on the signup form.
//     *
//     * @param array $data array of ("fieldname"=>value) of submitted data
//     * @param array $files array of uploaded files "element_name"=>tmp_file_path
//     * @return array of "element_name"=>"error_description" if there are errors,
//     *         or an empty array if everything is OK (true allowed for backwards compatibility too).
//     */
//    public function validation($data, $files) {
//
//        $errors = parent::validation($data, $files);
//        $errors += library_validate_data($data, $files);
//        return $errors;
//    }
//
//    /**
//     * Export this data so it can be used as the context for a mustache template.
//     *
//     * @param \renderer_base $output
//     * @return stdClass
//     */
//
//    public function export_for_template(renderer_base $output) {
//        global $CFG;
//
//        $data = new \stdClass();
//
//        ob_start();
//        $this->display();
//        $formhtml = ob_get_contents();
//        ob_end_clean();
//
//        $data->formhtml = $formhtml;
//
//        return $data;
//
//
//
//////        $mform = new \block_eva_form_library\output\form_library();
////        $mform = new \block_eva_form_library\output\form_library('http://localhost:8000/blocks/eva_form_library/view.php');
////
//////        var_dump('teste');die();
////
////
////        if ($mform->is_cancelled()) {
////
////        } else if ($fromform = $mform->get_data()) {
////
////        } else {
////            $mform->set_data($toform);
////
////            $data->formhtml = $mform->render();
////        }
////
////        return $data;
//
//    }
//
//}