<?php
global $CFG;
require_once($CFG->dirroot. '/theme/evagu/ccn/block_handler/ccn_block_handler.php');

class block_eva_publicidade extends block_base {
    /**
     * Start block instance.
     */
    function init() {
        $this->title = get_string('pluginname', 'block_eva_publicidade');
    }

    /**
     * The block is usable in all pages
     */
    function applicable_formats() {
        $ccnBlockHandler = new ccnBlockHandler();
        
        return $ccnBlockHandler->ccnGetBlockApplicability(array('all'));
    }


    /**
     * Customize the block title dynamically.
     */

    function specialization() {
        global $CFG, $DB;

        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/specialization.php');
       
        if (empty($this->config)) {
            $this->config = new \stdClass();
        }
   }

    /**
     * The block can be used repeatedly in a page.
     */
    function instance_allow_multiple() {
        return true;
    }

    /**
     * Build the block content.
     */
    function get_content() {
        global $CFG, $PAGE;

        require_once($CFG->libdir . '/filelib.php');
        
        $PAGE->requires->css(new moodle_url($CFG->wwwroot . '/blocks/eva_publicidade/style.css'));
        $PAGE->requires->js(new moodle_url($CFG->wwwroot . '/blocks/eva_publicidade/js/eva_publicidade_js_functions.js'));
        
        if ($this->content !== NULL) {
            return $this->content;
        }

        if (!empty($this->config) && is_object($this->config)) {
            $data = $this->config;
        } else {
            $data = new stdClass();
        }

        $fs = get_file_storage();                           
        $sliderimageTransmition = 'file_slide_transmition';
        
        if (!empty($data->$sliderimageTransmition)) {
            $filesTransmition = $fs->get_area_files($this->context->id, 'block_eva_publicidade', 'slide_transmition', 1, 'sortorder DESC, id ASC', false, 0, 0, 1);
            
            if (count($filesTransmition) >= 1) {
                $mainfileTransmition = reset($filesTransmition);
                $mainfileTransmition = $mainfileTransmition->get_filename();
            }

            $srcTransmition = moodle_url::make_file_url("$CFG->wwwroot/pluginfile.php", "/{$this->context->id}/block_eva_publicidade/slide_transmition/1" . '/' . $mainfileTransmition);
        }

        $date = usergetdate($data->date_select);

        if($date['weekday'] == "Monday"){
            $wday = "SEGUNDA";
        }else if($date['weekday'] == "Tuesday"){
            $wday = "TERÇA";
        }else if($date['weekday'] == "Wednesday"){
            $wday = "QUARTA";
        }else if($date['weekday'] == "Thursday"){
            $wday = "QUINTA";
        }else if($date['weekday'] == "Friday"){
            $wday = "SEXTA";
        }else if($date['weekday'] == "Saturday"){
            $wday = "SÁBADO";
        }else if($date['weekday'] == "Sunday"){
            $wday = "DOMINGO";
        }

        $mday = $date['mday'];

        $ccnBlogHandler = new ccnBlogHandler();
        
        $text = '';

        $text .= '
        <style>
            .bg_main_color{
                background-color: ' . $data->bg_color . ';
            }
            
            .txt_main_color{
                color: ' . $data->first_color . ';
            }
            
            .txt_second_color{
                color: ' . $data->second_color . ';
            }
            
            .txt_third_color{
                color: ' . $data->third_color . ';
            }
        </style>
        ';

        $text .= '
        <div class="content">
            <div class="container-fluid p-0">
                <div class="row">
                    <div class="col-md-12">
                        <div class="container-fluid">
                            <div class="container">
                                <section style="padding-bottom: 0px !important;">
                                    <div class="row" onclick="DownloadAsImage();">
                                        <button class="btn btn-primary">Salvar como imagem</button>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content" id="capture">
            <div class="container-fluid p-0">
                <div class="row">
                    <div class="col-md-12">
                        <div class="container-fluid">
                            <div class="container bg_main_color">
                                <section style="padding-bottom: 0px !important;padding-top: 30px;padding-left: 30px;padding-right: 30px;">
                                    <div>
                                        <div class="row">
                                            <div class="col-sm-12 col-md-12">
                                                <img class="bolinhas" src="'.$CFG->wwwroot.'/blocks/eva_publicidade/images/bolinhas.png">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 col-md-12" style="text-align: center;">
                                                <h1 class="txt_main_color u-text-1">'.$data->title.'</h1>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 col-md-12" style="text-align: center;">
                                                <h1 class="txt_second_color u-text-2">'.$data->title2.'</h1>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 col-md-12" style="text-align: center;">
                                                <p class="txt_main_color u-text-3">
                                                '.$data->subtitle.'
                                                </p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 col-md-12" style="text-align: center;margin: 20px auto;">
                                                <span class="txt_third_color u-text-4">'.$data->aula_id.'</span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 col-md-12" style="text-align: center;">
                                                <h2 class="txt_second_color u-text-5">'.$data->palestrante.'</h2>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <section style="padding-top: 0px !important;padding-left: 30px;padding-right: 30px;">
                                    <div class="row">';
                                    $fs = get_file_storage();
                                    
                                    for ($i = 1; $i <= $data->slidesnumber; $i++) {
                                        $sliderimage = 'file_slide' . $i;
                                        $palestrante_nome = 'palestrante_nome' . $i;
                                        $palestrante_profissao = 'palestrante_profissao' . $i;
                                        $palestrante_texto = 'palestrante_texto' . $i;
                                        
                                        if (!empty($data->$sliderimage)) {
                                            $files = $fs->get_area_files($this->context->id, 'block_eva_publicidade', 'slides', $i, 'sortorder DESC, id ASC', false, 0, 0, 1);
                                            
                                            if (count($files) >= 1) {
                                                $mainfile = reset($files);
                                                $mainfile = $mainfile->get_filename();
                                            } else {
                                                continue;
                                            }
                        
                                            $src = moodle_url::make_file_url("$CFG->wwwroot/pluginfile.php", "/{$this->context->id}/block_eva_publicidade/slides/" . $i . '/' . $mainfile);
                                            
                                            $text .= '
                                            <div class="col-sm-12 col-md-6">
                                                <div class="u-expanded-width u-list u-list-1">
                                                    <div class="u-repeater u-repeater-1">
                                                        <div class="u-container-style u-list-item u-repeater-item">
                                                            <div class="u-container-layout u-similar-container u-valign-middle-xl u-container-layout-1">
                                                                <img class="u-image u-image-round u-radius-20 u-image-1" src="'.$src.'">
                                                                <div class="u-container-style u-group u-radius-10 u-shape-round u-white u-group-1">
                                                                    <div class="u-container-layout u-container-layout-2">
                                                                        <h4 class="u-align-center u-custom-font u-font-oswald u-text u-text-6">
                                                                            <a class="u-active-none u-border-none u-btn u-button-link u-button-style u-hover-none u-none u-text-body-color u-btn-2" href="https://www.gov.br/agu/pt-br/canais_atendimento/escola-da-agu">'.$data->$palestrante_nome.'</a>
                                                                        </h4>
                                                                        <h6 class="u-align-center u-custom-font u-text u-text-font u-text-palette-2-base u-text-7">'.$data->$palestrante_profissao.'</h6>
                                                                        <p class="u-align-justify u-text u-text-8">'.$data->$palestrante_texto.'</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>';
                                        }
                                    }
                                    $text .= '
                                    </div>
                                </section>
                                <section style="padding-top: 0px !important;padding-left: 30px;padding-right: 30px;">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6">
                                            <div class="row">
                                                <div class="col-5 col-sm-5 col-md-5">
                                                    <span class="txt_main_color u-text u-text-default u-title u-text-20">'.$mday.'</span>
                                                </div>
                                                <div class="col-6 col-sm-6 col-md-6">
                                                    <span class="u-align-right u-text u-text-default u-text-palette-1-light-2 u-text-18">'.$wday.'</span>
                                                    <br/>
                                                    <span class="txt_main_color u-align-right u-custom-font u-font-montserrat u-text u-text-default u-text-19">'.$data->hour_range.'</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <div class="u-container-layout u-valign-top-lg u-valign-top-xl u-container-layout-10 reAlign">
                                                <h2 class="txt_main_color u-align-left-lg u-align-left-xl u-align-right-md u-align-right-sm u-align-right-xs u-custom-font u-font-montserrat u-text u-text-21">PÚBLICO ALVO</h2>
                                                <ul class="txt_main_color u-align-left u-text u-text-22 ul-padding no-list-style">
                                                    '.((!empty($data->target_audience1)) ? "<li>".$data->target_audience1."</li>" : "").'
                                                    '.((!empty($data->target_audience2)) ? "<li>".$data->target_audience2."</li>" : "").'
                                                    '.((!empty($data->target_audience3)) ? "<li>".$data->target_audience3."</li>" : "").'
                                                    '.((!empty($data->target_audience4)) ? "<li>".$data->target_audience4."</li>" : "").'
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <section style="padding-top: 0px !important;padding-left: 30px;padding-right: 30px;">
                                    <div class="row gone">
                                        <div class="col-sm-12 col-md-12">
                                            <h4 class="txt_main_color u-align-right u-text u-text-23">Realização</h4>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6">
                                            <div class="row">
                                                <div class="col-4 col-sm-4 col-md-4">
                                                    <img class="u-image u-image-round u-preserve-proportions u-radius-10 u-image-5" src="'.$srcTransmition.'">
                                                </div>
                                                <div class="col-6 col-sm-6 col-md-6">
                                                    <span class="u-text-24"><a class="txt_main_color social_link" href="'.$data->transmissao_link1.'">'.$data->transmissao_line1.'</a></span>
                                                    <br/><br/>
                                                    <span class="u-text-25"><a class="u-text-palette-1-light-2 social_link" href="'.$data->transmissao_link2.'">'.$data->transmissao_line2.'</a></span>
                                                    <br/><br/>
                                                    <span class="u-text-26"><a class="txt_main_color social_link" href="'.$data->transmissao_link3.'">'.$data->transmissao_line3.'</a></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row come">
                                            <div class="col-sm-12 col-md-12 to_right">
                                                <h4 class="txt_main_color u-align-right u-text u-text-23">Realização</h4>
                                            </div>
                                        </div>';
                                        $fs = get_file_storage();
                                        
                                        for ($i = 1; $i <= $data->slidesnumber_realizacao; $i++) {
                                            $sliderimage = 'file_slide_realizacao' . $i;
                                            
                                            if (!empty($data->$sliderimage)) {
                                                $files = $fs->get_area_files($this->context->id, 'block_eva_publicidade', 'slides_realizacao', $i, 'sortorder DESC, id ASC', false, 0, 0, 1);
                                                
                                                if (count($files) >= 1) {
                                                    $mainfile = reset($files);
                                                    $mainfile = $mainfile->get_filename();
                                                } else {
                                                    continue;
                                                }
                            
                                                $src = moodle_url::make_file_url("$CFG->wwwroot/pluginfile.php", "/{$this->context->id}/block_eva_publicidade/slides_realizacao/" . $i . '/' . $mainfile);
                                                
                                                $text .= '
                                                <div class="col-sm-12 '.(($data->slidesnumber_realizacao < 2) ? "col-md-6" : "col-md-3").'">
                                                    <div class="u-align-left u-container-style u-gradient u-layout-cell u-size-15 u-size-30-md u-layout-cell-5">
                                                        <div class="u-container-layout u-align-center u-container-layout-13">
                                                            <img class="u-image u-image-default u-image-7" src="'.$src.'">
                                                        </div>
                                                    </div>
                                                </div>';
                                            }
                                        }
                                        $text .= '
                                    </div>
                                </section>
                                <section style="padding-top: 0px !important;padding-bottom: 0px !important;padding-left: 30px;padding-right: 30px;">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12">
                                            <h4 class="txt_main_color u-align-center u-text u-text-23">APOIO</h4>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12">
                                            <hr style="height:5px;border-width:0;color:gray;background-color:' . $data->first_color . ';">
                                        </div>
                                    </div>
                                </section>
                                <section style="padding-top: 0px !important;padding-left: 30px;padding-right: 30px;">
                                    <div class="row">';
                                    $fs = get_file_storage();
                                    
                                    for ($i = 1; $i <= $data->slidesnumber_apoio; $i++) {
                                        $sliderimage = 'file_slide_apoio' . $i;
                                        
                                        if (!empty($data->$sliderimage)) {
                                            $files = $fs->get_area_files($this->context->id, 'block_eva_publicidade', 'slides_apoio', $i, 'sortorder DESC, id ASC', false, 0, 0, 1);
                                            
                                            if (count($files) >= 1) {
                                                $mainfile = reset($files);
                                                $mainfile = $mainfile->get_filename();
                                            } else {
                                                continue;
                                            }
                        
                                            $src = moodle_url::make_file_url("$CFG->wwwroot/pluginfile.php", "/{$this->context->id}/block_eva_publicidade/slides_apoio/" . $i . '/' . $mainfile);
                                            
                                            $text .= '
                                            <div class="col-sm-12 col-md-3" style="padding-top: 15px; padding-bottom: 15px;">
                                                <div class="u-align-left u-container-style u-gradient u-layout-cell u-size-15 u-size-30-md">
                                                    <div class="u-container-layout u-align-center u-container-layout-13">
                                                        <img class="u-image u-image-default u-image-6" src="'.$src.'">
                                                    </div>
                                                </div>
                                            </div>';
                                        }
                                    }
                                    $text .= '
                                    </div>
                                </section>
                                <section style="padding-top: 0px !important;padding-left: 30px;padding-right: 30px;">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12">';
                                        if($data->facebook_url !== "#"){
                                            $text .= '<a href="'.$data->facebook_url.'" class="txt_main_color social_link"><i class="fa fa-facebook fa-5x" style="margin-left: 30px; margin-right: 30px;"></i></a>';
                                        }
                                        if($data->tweeter_url !== "#"){
                                            $text .= '<a href="'.$data->facebook_url.'" class="txt_main_color social_link"><i class="fa fa-tweeter fa-5x" style="margin-left: 30px; margin-right: 30px;"></i></a>';
                                        }
                                        if($data->instagram_url !== "#"){
                                            $text .= '<a href="'.$data->facebook_url.'" class="txt_main_color social_link"><i class="fa fa-instagram fa-5x" style="margin-left: 30px; margin-right: 30px;"></i></a>';
                                        }
                                        if($data->youtube_url !== "#"){
                                            $text .= '<a href="'.$data->facebook_url.'" class="txt_main_color social_link"><i class="fa fa-youtube fa-5x" style="margin-left: 30px; margin-right: 30px;"></i></a>';
                                        }
                                        $text .= '
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        ';

        $this->content = new stdClass;
        $this->content->footer = '';
        $this->content->text = $text;

        return $this->content;
    }

    /**
     * When a block instance is deleted.
     */
    function instance_delete() {
        global $DB;
        
        $fs = get_file_storage();
        $fs->delete_area_files($this->context->id, 'block_eva_publicidade');
        
        return true;
    }

    /**
     * Enables global configuration of the block in settings.php.
     *
     * @return bool True if the global configuration is enabled.
     */
    function has_config() {
        return true;
    }

    public function html_attributes() {
        global $CFG;
        
        $attributes = parent::html_attributes();
        
        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/attributes.php');
        
        return $attributes;
    }

    /**
     * The block should only be dockable when the title of the block is not empty
     * and when parent allows docking.
     *
     * @return bool
     */
    public function instance_can_be_docked() {
        return (!empty($this->config->title) && parent::instance_can_be_docked());
    }
}
