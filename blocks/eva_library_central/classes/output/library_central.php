<?php

namespace block_eva_library_central\output;

defined('MOODLE_INTERNAL') || die();

use renderable;
use renderer_base;
use templatable;

class library_central implements renderable, templatable {
/** @var mixed */
public $content = null;

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

    public function export_for_template(renderer_base $output) {
        global $USER, $OUTPUT, $PAGE, $CFG;

        require_once($CFG->libdir . '/filelib.php');
        if ($this->content !== null) {
            return $this->content;
        }

        $data = new \stdClass();
        if(!empty($this->config->title)){$data->title = $this->config->title;} else {$this->config->title = '';}
        if(!empty($this->config->body)){$data->body = $this->config->body['text'];} else {$this->config->body = ''; }


        return $data;

    }

}
