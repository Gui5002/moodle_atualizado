<?php

global $CFG;

require_once($CFG->dirroot. '/theme/evagu/ccn/block_handler/ccn_block_handler.php');

defined('MOODLE_INTERNAL') || die();

class block_eva_ts_controll extends block_base {
    function init() {
        $this->title = get_string('pluginname', 'block_eva_ts_controll');
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
        $text .= '<a href="'. $CFG->wwwroot .'/mod/eva/eva_ts_view.php"><i class="fa fa-eye"></i>&nbsp;Visualizar sugestões</a>';
        $text .= '<br/>';
        $text .= '<a href="'. $CFG->wwwroot .'/mod/eva/eva_training_suggestion.php"><i class="fa fa-edit"></i>&nbsp;Cadastrar sugestão</a>';

        $this->content = new stdClass;
        $this->content->footer = '';
        $this->content->text = $text;

        return $this->content;
    }

    public function html_attributes() {
        global $CFG;

        $attributes = parent::html_attributes();
        include_once($CFG->dirroot . '/theme/evagu/ccn/block_handler/attributes.php');

        return $attributes;
    }
}
