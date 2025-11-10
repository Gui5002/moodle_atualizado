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
        $text .= '<a href="'. $CFG->wwwroot .'/mod/eva/eva_reports.php?id=4"><i class="fa fa-server"></i>&nbsp;Consolidado por Cursos</a><br/>';
        $text .= '<a href="'. $CFG->wwwroot .'/mod/eva/eva_reports.php?id=5"><i class="fa fa-database"></i>&nbsp;Consolidado EVA</a><br/>';
        $text .= '<a href="'. $CFG->wwwroot .'/mod/eva/eva_reports.php?id=1"><i class="fa fa-graduation-cap"></i>&nbsp;Cursos e categorias</a><br/>';
        $text .= '<a href="'. $CFG->wwwroot .'/mod/eva/eva_reports.php?id=2"><i class="fa fa-id-card-o"></i>&nbsp;Resultados por curso</a><br/>';
        $text .= '<a href="'. $CFG->wwwroot .'/mod/eva/eva_reports.php?id=3"><i class="fa fa-user-circle-o"></i>&nbsp;Cursos por usuário</a><br/>';


        $this->content = new stdClass;
        $this->content->footer = '';
        $this->content->text = $text;

        return $this->content;
    }

    public function html_attributes() {
        global $CFG;

        $attributes = parent::html_attributes();

        if (!isset($attributes['class'])) {
            $attributes['class'] = '';
        }
        $attributes['class'] .= ' ccnDashBl';

        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/attributes.php');

        return $attributes;
    }
}
