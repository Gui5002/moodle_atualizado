<?php
namespace block_eva_form_library\output;
defined('MOODLE_INTERNAL') || die();
use renderable;
use renderer_base;
use templatable;
/**
 *  file description here.
 *
* @package    ${PLUGINNAME}
* @copyright  2024 ESAGU
* @author     2023 celio <>
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class formlib implements renderable, templatable {
    var $config;
    var $context;
    public function __construct($config, $context) {
        $this->config = $config;
        $this->context = $context;
    }
    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return stdClass
     */
    public function export_for_template(renderer_base $output)
    {
        global $DB, $USER, $PAGE;
        if ($_GET['externo'] != "true"){
            if (!isloggedin()) {
                require_login();
            }
        }
        $data = new \stdClass();
        if(!empty($this->config->title)){$data->title = $this->config->title;}
        if(!empty($this->config->subtitle)){$data->subtitle = $this->config->subtitle;}
        if(!empty($this->config->items)){$data->items = $this->config->items;}
        $data->externo = $_GET['externo'];
        $data->userid = $USER->id;
        $data->fullname = fullname($USER);
        $data->emailuser = $USER->email;
        $data->alternativoemail = $USER->pemail;
        $data->dscargo = $USER->ds_cargo;
        $data->lotacao = $USER->lotacao;
        $data->siape = $USER->siape;
        $data->cpf = $USER->cpf;
        $data->createdata =  date("Y-m-d h:i:sa");
        return $data;
    }
}
