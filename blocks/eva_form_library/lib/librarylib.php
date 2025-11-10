<?php
/**
* ${PLUGINNAME} file description here.
 * @package    ${PLUGINNAME}
* @copyright  2024 ESAGU
* @author     celio
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();
    /**
     * Validates the standard sign-up data (except recaptcha that is validated by the form element).
     *
     * @param  array $data  the sign-up data
     * @param  array $files files among the data
     * @return array list of errors, being the key the data element name and the value the error itself
     * @since Moodle 3.2     */
    function library_validate_data($data, $files) {
        global $USER;
        $errors = array();
        if (! validate_email($data['email_institucional'])) {
            $errors['email_institucional'] = get_string('invalidemaillib', 'block_eva_form_library');
        }
        return $errors;
    }
    function solicitacao_create_library($library, $returnurl) {
        global $DB, $PAGE;
        $dadoslocal = $DB->get_record('eva_biblioteca_local', ['local'=> $library->biblioteca]);
        $library->id_biblioteca = $dadoslocal->id;
        $library->message .= "fone: ".$library->celular;
        if ($library->externo == true && (is_null($library->cc) == false)){
            $library->cc = $library->email_institucional;
        }
        $data = $DB->insert_record('eva_form_library', $library);
        if ($data){
            $contact = new library_contact();
            unset($_POST['id_user'], $_POST['created'], $_POST['externo'], $_POST['mform_isexpanded_id_createuserandpass'], $_POST['submitbutton'], $_POST['Cc']);
            if ($contact->sendmessage($dadoslocal->email, $name, null ,$library->cc)) {
                // Share a gratitude and Say Thank You! Your user will love to know their message was sent.
                $message = '<h5 class="text-center">' . get_string('msgsolicitacao', 'block_eva_form_library') . '</h5>';
            } else {
                // Oh no! What are the chances. Looks like we failed to meet user expectations (message not sent).
                $message = '<h5 class="text-center">'.get_string('errorsendingtitle', 'local_contact').'</h5>';
            }
        }
        if (isset($message)) {
            if (!$PAGE->url->compare($returnurl, URL_MATCH_BASE)) {
                redirect($returnurl, $message);
            }
            // We are already on the purge caches page, add the notification.
            \core\notification::add($message, \core\output\notification::NOTIFY_INFO);
        }
    }
