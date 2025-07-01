<?php
global $CFG;

require_once($CFG->dirroot. '/theme/evagu/ccn/block_handler/ccn_block_handler.php');

defined('MOODLE_INTERNAL') || die();

class block_eva_reports_controll extends block_base {
    function init() {
        $this->title = get_string('pluginname', 'block_eva_reports_controll');
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
        $text .= '<a href="'. $CFG->wwwroot .'/mod/eva/eva_reports.php?id=4"><i class="fa fa-info-circle"></i>&nbsp;Consolidado por Cursos</a>';
        $text .= '<br/>';
        $text .= '<a href="'. $CFG->wwwroot .'/mod/eva/eva_reports.php?id=5"><i class="fa fa-info-circle"></i>&nbsp;Consolidado EVA</a>';
        $text .= '<br/>';
        $text .= '<a href="'. $CFG->wwwroot .'/mod/eva/eva_reports.php?id=1"><i class="fa fa-copy"></i>&nbsp;Cursos e categorias</a>';
        $text .= '<br/>';
        $text .= '<a href="'. $CFG->wwwroot .'/mod/eva/eva_reports.php?id=2"><i class="fa fa-bookmark"></i>&nbsp;Resultados por curso</a>';
        $text .= '<br/>';
        $text .= '<a href="'. $CFG->wwwroot .'/mod/eva/eva_reports.php?id=3"><i class="fa fa-user"></i>&nbsp;Cursos por usuário</a>';
        $text .= '<br/>';

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
