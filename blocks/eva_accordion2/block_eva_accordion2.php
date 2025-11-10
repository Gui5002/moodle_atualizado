<?php
require_once ($CFG->dirroot . '/theme/evagu/ccn/block_handler/ccn_block_handler.php');

class block_eva_accordion2 extends block_base {
    function init()
    {
        $this->title = get_string('pluginname', 'block_eva_accordion2');
    }

    function applicable_formats()
    {
        $ccnBlockHandler = new ccnBlockHandler();
        return $ccnBlockHandler->ccnGetBlockApplicability(array('all'));
    }

    function specialization()
    {
        global $CFG, $DB;
        include ($CFG->dirroot . '/theme/evagu/ccn/block_handler/specialization.php');

        if (empty($this->config)) {
            $this->config->slidesnumber = '4';
            $this->config->title = 'Estrutura EVA';
            $this->config->title1 = 'HISTORICO';
            $this->config->title2 = 'COMPETENCIAS DE FUNCIONAMENTO';
            $this->config->title3 = 'QUEM É QUEM';
            $this->config->title4 = 'COMPLEMENTAR';
            $this->config->subtitle1 = 'Description';
            $this->config->subtitle2 = 'Description';
            $this->config->subtitle3 = 'Description';
            $this->config->subtitle4 = 'Description';
            $this->config->text1['text'] = "Texto modelo para preenchimento dos campos do bloco utilizar quantidade mínima de dados para teste'Texto modelo para preenchimento dos campos do bloco utilizar quantidade mínima de dados para teste.";
            $this->config->text2['text'] = "Texto modelo para preenchimento dos campos do bloco utilizar quantidade mínima de dados para teste'Texto modelo para preenchimento dos campos do bloco utilizar quantidade mínima de dados para teste.";
            $this->config->text3['text'] = "Texto modelo para preenchimento dos campos do bloco utilizar quantidade mínima de dados para teste'Texto modelo para preenchimento dos campos do bloco utilizar quantidade mínima de dados para teste.";
            $this->config->text4['text'] = "Texto modelo para preenchimento dos campos do bloco utilizar quantidade mínima de dados para teste'Texto modelo para preenchimento dos campos do bloco utilizar quantidade mínima de dados para teste.";
        }
    }

    function instance_allow_multiple()
    {
        return true;
    }

    function get_content()
    {
        global $CFG, $PAGE;
        require_once ($CFG->libdir . '/filelib.php');
        if ($this->content !== NULL) {
            return $this->content;
        }
        if (!empty($this->config) && is_object($this->config)) {
            $this->content = new \stdClass();
            if (!empty($this->config->title)) {
                $this->content->title = $this->config->title;
            } else {
                $this->content->title = '';
            }
            $data = $this->config;
            $data->slidesnumber = is_numeric($data->slidesnumber) ? (int) $data->slidesnumber : 0;
        } else {
            $data = new stdClass();
            $data->slidesnumber = 0;
        }
        $text = '';
        if ($data->slidesnumber > 0) {
            $text .= '
            <div class="shortcode_widget_accprdons">';
            if (!empty($this->config->title)) {
                $text .= '<h4 data-ccn="title">' . format_text($this->content->title, FORMAT_HTML, array('filter' => true)) . '</h4>';
            }
              $ccnAccInstance = 'accordion2-' . $this->instance->id;
            $text .= "
            <div class=\"faq_according\">
            <div class=\"accordion2\" id=\"" . $ccnAccInstance . '">';
            for ($i = 1; $i <= $data->slidesnumber; $i++) {
                $ccnAccTitle = 'title' . $i;
                $ccnAccBody = 'text' . $i;
                $ccnAccLink = 'heading-' . $this->instance->id . '-' . $i;
                $ccnCollapseLink = 'heading-' . $this->instance->id . '-' . $i;
                $ccnAriaSelected = 'false';
                $ccnClass = 'nav-link';
                if ($i == 1) {
                    $ccnAriaSelected = 'true';
                    $ccnClass .= ' active';
                }

                $text .= "
            <div class=\"card\">
            <div class=\"card-header\" id=\"#" . $ccnAccLink . "\">
            <h2 class=\"mb-0\">
            <button data-ccn=\"" . $ccnAccTitle . '" class="btn btn-link" type="button" data-toggle="collapse" data-target="#' . $ccnCollapseLink . '" aria-expanded="true" aria-controls="' . $ccnCollapseLink . "\">
            " . format_text($data->$ccnAccTitle, FORMAT_HTML, array('filter' => true)) . "
            <span class=\"flaticon-right-arrow float-right\"></span>
                        </button>
                    </h2>
                </div>
            <div id=\"" . $ccnCollapseLink . '" class="collapse' . (($i == 1) ? ' show' : '') . '" aria-labelledby="' . $ccnAccLink . '" data-parent="#' . $ccnAccInstance . "\">
            <div class=\"card-body\" data-ccn=\"" . $ccnAccBody . "\">
            " . format_text($data->$ccnAccBody['text'], FORMAT_HTML, array('filter' => true)) . "
                        </div>
                    </div>
            </div>";
            }
            $text .= "
            </div>
            </div>
           </div>";
        }
        $this->content = new stdClass;
        $this->content->footer = '';
        $this->content->text = $text;
        return $this->content;
    }

    public function html_attributes()
    {
        global $CFG;
        $attributes = parent::html_attributes();
        include ($CFG->dirroot . '/theme/evagu/ccn/block_handler/attributes.php');
        return $attributes;
    }
}
