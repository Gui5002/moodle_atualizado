<?php
global $CFG;

require_once($CFG->dirroot. '/theme/evagu/ccn/block_handler/ccn_block_handler.php');

defined('MOODLE_INTERNAL') || die();

class block_eva_reports_users_controll extends block_base {
    function init() {
        $this->title = get_string('pluginname', 'block_eva_reports_users_controll');
    }

    function applicable_formats() {
        return array('my' => true);
    }

    function instance_allow_multiple() {
        return false;
    }

    function get_content() {
        global $CFG, $PAGE, $DB, $USER;

        require_once($CFG->libdir . '/filelib.php');

        if ($this->content !== NULL) {
            return $this->content;
        }

        if (!empty($this->config) && is_object($this->config)) {
            $this->content = new \stdClass();
        } else {
            $data = new stdClass();
        }

        $text = '';
        $text .= '<br/>';
        $text .= '<a href="'. $CFG->wwwroot .'/mod/eva/eva_reports_users.php?id=2"><i class="fa fa-bookmark"></i>&nbsp;Resultados por Curso</a>';
        $text .= '<br/>';
        $text .= '<a href="'. $CFG->wwwroot .'/mod/eva/eva_reports_users.php?id=3"><i class="fa fa-users"></i>&nbsp;Relatório de Conclusão de Cursos</a>';
        $text .= '<br/>';
        // $text .= '<br/>';
        // $text .= '<a href="'. $CFG->wwwroot .'/mod/eva/eva_reports.php?id=6"><i class="fa fa-bookmark"></i>&nbsp;Histórico por curso</a>';

        $this->content = new stdClass;
        $this->content->footer = '';
        $this->content->text = $text;

        return $this->content;
    }

    public function html_attributes() {
          global $CFG;

          $attributes = parent::html_attributes();
          include($CFG->dirroot . '/theme/evagu/ccn/block_handler/attributes.php');

          return $attributes;
    }
}
