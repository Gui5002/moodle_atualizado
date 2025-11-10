<?php
namespace block_eva_continuing_education_b2\output;
defined('MOODLE_INTERNAL') || die();
use moodle_url;
use renderable;
use renderer_base;
use templatable;
class eva_continuing_education_b2 implements renderable, templatable {
    var $config;
    var $context;
    var $content; // Definindo explicitamente a variável content para evitar warnings.
    public function __construct($config, $context) {
        $this->config = $config;
        $this->context = $context;
        $this->content = null; // Inicializando a propriedade content.
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
        // Verificação da existência de $this->content antes de acessá-la.
        if (isset($this->content) && $this->content !== null) {
            return $this->content;
        }
        $data = new \stdClass();
        $data->title = !empty($this->config->title) ? $this->config->title : '';
        $data->blockquote = !empty($this->config->blockquote) ? $this->config->blockquote : '';
        $data->text = !empty($this->config->text) ? $this->config->text : '';
        $data->button = !empty($this->config->button) ? $this->config->button : '';
        $data->url = !empty($this->config->url) ? $this->config->url : '';
        $data->color = !empty($this->config->color) ? $this->config->color : '#eff2fc';
        $data->title_color = !empty($this->config->title_color) ? $this->config->title_color : '#6f809e';
        $data->button_color = !empty($this->config->button_color) ? $this->config->button_color : '#6f809e';
        $data->button_color_hover = !empty($this->config->button_color_hover) ? $this->config->button_color_hover : '#a4a4a5';
        $data->button_text_color = !empty($this->config->button_text_color) ? $this->config->button_text_color : '#ffffff';
        $style = $CFG->wwwroot . '/blocks/eva_continuing_education_b2/style2.css';
        $data->style = $style;
        $fs = get_file_storage();
        $files = $fs->get_area_files($this->context->id, 'block_eva_continuing_education_b2', 'content');
        $this->config->image = $CFG->wwwroot . '/theme/evagu/images/about/8.jpg';
        foreach ($files as $file) {
            $filename = $file->get_filename();
            if ($filename !== '.') {
                $url = moodle_url::make_pluginfile_url(
                    $file->get_contextid(),
                    $file->get_component(),
                    $file->get_filearea(),
                    null,
                    $file->get_filepath(),
                    $filename
                );
                $this->config->image = $url;
            }
        }
        $data->imgs = $this->config->image;
        // Evitar erro ao acessar propriedades não definidas diretamente.
        $data->color = isset($this->config->color) ? $this->config->color : '#eff2fc';
        return $data;
    }
}
