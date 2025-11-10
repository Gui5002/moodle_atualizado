<?php
global $CFG;
require_once($CFG->dirroot. '/theme/evagu/ccn/block_handler/ccn_block_handler.php');
defined('MOODLE_INTERNAL') || die();

class block_eva_training_suggestion extends block_base {

    /**
     * Start block instance.
     */
    function init() {
        $this->title = get_string('pluginname', 'block_eva_training_suggestion');
    }

    function applicable_formats() {
        return array('mod' => true);
    }

    function instance_allow_multiple() {
        return false;
    }

     /**
     * Customize the block title dynamically.
     */
    function specialization() {
        global $CFG, $DB, $USER;

        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/specialization.php');
        if (empty($this->config)) {
          $this->config->slidesnumber = '1';
          $this->config->title        = 'Levantamento de Necessidades de Capacitação';
          $this->config->subtitle     = 'Dados da capacitação sugerida';
          $this->config->heading      = 'A Escola Superior da Advocacia-Geral da União (ESAGU) desenvolveu o Levantamento de Necessidades de Capacitação (LNC) com o objetivo de identificar as demandas de formação prioritárias dos membros e servidores técnicos administrativos da Advocacia-Geral da União. O LNC é realizado anualmente e baseia-se na necessidade de alinhar as ações de desenvolvimento promovidas pela ESAGU com as diferentes áreas de atividade da instituição.';
          $this->config->stylesheet   = 'Style block';
          $this->config->alt_text     = 'Selecione a unidade da AGU, à qual se refere a necessidade de treinamento';
          $this->config->alt_text2    = 'Grave resumidamente o assunto do treinamento pretendido';
          $this->config->alt_text3    = 'Se o treinamento se referir a mais de uma área prioritária, selecione cada uma das áreas correspondentes';
          $this->config->alt_text4    = 'Se o treinamento se referir a mais de uma área prioritária, selecione cada uma das áreas correspondentes';
          $this->config->alt_text5    = 'Discuta brevemente a necessidade de desenvolvimento em sua unidade que será atendida pelo treinamento sugerido';
          $this->config->alt_text6    = 'Descreva qual é o público-alvo do treinamento sugerido. Ex equipe, tema ou coordenação';
          $this->config->alt_text7    = 'Informe o número de participantes que precisam do treinamento sugerido';
          $this->config->alt_text8    = 'Informe se este treinamento é relevante apenas para o seu órgão / unidade ou pode atender a todos';
          $this->config->alt_text9    = 'Informar, em média, a carga horária adequada para o treinamento sugerido';
          $this->config->alt_text10   = 'Caso queira sugerir, selecione as modalidades de treinamento, entre as opções que melhor atenderiam a demanda, em função do tema, necessidades e público';
          $this->config->alt_text11   = 'Informe se você conhece a instituição ou o nome do instrutor para oferecer o treinamento sugerido';
          $this->config->alt_text12   = 'Informe se conhece o valor estimado da aquisição / contratação';
          $this->config->select1      = 'eva_slc_1';
          $this->config->select2      = 'eva_slc_2';
          $this->config->txtarea1     = 'eva_txtarea_1';
          $this->config->txtarea2     = 'eva_txtarea_2';
          $this->config->check_id     = 'eva_checkbox_1';
          $this->config->check_id2    = 'eva_checkbox_2';
          $this->config->check_id3    = 'eva_checkbox_3';
          $this->config->input1       = 'eva_input_1';
          $this->config->input2       = 'eva_input_2';
          $this->config->input3       = 'eva_input_3';
          $this->config->input4       = 'eva_input_4';
          $this->config->input5       = 'eva_input_5';
          $this->config->button1      = 'eva_button_1';
          $this->config->button2      = 'eva_button_2';
          $this->config->userid       = $USER->id;
          $this->config->pageid       = 'Page ID';
          $this->config->ts           = 'Training Suggestion ID';

        }
    }

    /**
     * Build the block content.
     */
    function get_content() {
        global $CFG, $PAGE, $DB, $USER;

        require_once($CFG->libdir . '/filelib.php');
        $PAGE->requires->css(new moodle_url($CFG->wwwroot . '/blocks/eva_training_suggestion/css/styles.css'));
        // $PAGE->requires->css(new moodle_url($CFG->wwwroot . '/blocks/eva_training_suggestion/css/select2.css'));
        // $PAGE->requires->css(new moodle_url($CFG->wwwroot . '/blocks/eva_training_suggestion/css/select2-bootstrap.css'));

        //var_dump($CFG->libdir);

        if ($this->content !== NULL) {
            return $this->content;
        }

        if (!empty($this->config) && is_object($this->config)) {
            $this->content = new \stdClass();
            if(!empty($this->config->title)){$this->content->title = $this->config->title;} else {$this->content->title = '';}
            if(!empty($this->config->heading)){$this->content->heading = $this->config->heading;} else {$this->content->heading = '';}
            if(!empty($this->config->subtitle)){$this->content->subtitle = $this->config->subtitle;} else {$this->content->subtitle = '';}
            if(!empty($this->config->stylesheet)){$this->content->stylesheet = $this->config->stylesheet;} else {$this->content->stylesheet = '';}

            $data = $this->config;
            $data->slidesnumber = is_numeric($data->slidesnumber) ? (int)$data->slidesnumber : 0;
        } else {
            $data = new stdClass();
            $data->slidesnumber = 0;
        }
        //var_dump($data);die;
        $rs = $DB->get_records_sql('SELECT * FROM {eva_superior_organ} WHERE st_status = 1');
        if($rs){
            $this->config->resultSet = $rs;
        }

        $ts_id = optional_param('id_ts', 0, PARAM_INT);
        $page_id = optional_param('id', 0, PARAM_INT);
        $this->config->ts = ((!empty($ts_id)) ? $this->config->ts = $ts_id : $this->config->ts = 0);
        $this->config->pageid = ((!empty($page_id)) ? $this->config->pageid = $page_id : $this->config->pageid = 0);

        $sql = 'SELECT
                id,
                id_user,
                id_superior_organ,
                dt_suggestion,
                st_suggestion,
                ds_theme,
                slc_priority_area_legal,
                slc_technical_legal,
                ds_development_need,
                ds_target_audience,
                nu_participants,
                ds_transversality,
                ds_workload,
                slc_modality,
                no_institution_instructor,
                nu_estimated_value
            FROM
                {eva_training_suggestion}
            WHERE
                st_suggestion = 1
                AND id = ' . $this->config->ts;
        $rsAll = $DB->get_records_sql($sql);

        if($rsAll){
            $this->config->rsAll = $rsAll;
        }

        $this->config->name1        = 'Órgão de direção superior';
        $this->config->name2        = 'Tema do treinamento';
        $this->config->name3        = 'Articulação com área prioritária para treinamento da AGU - eixo jurídico';
        $this->config->name4        = 'Articulação com área prioritária para capacitação da AGU - eixo técnico-jurídico de gestão';
        $this->config->name5        = 'Necessidade de desenvolvimento a ser atendida com a capacitação solicitada';
        $this->config->name6        = 'Público-alvo';
        $this->config->name7        = 'Número de participantes';
        $this->config->name8        = 'Transversalidade';
        $this->config->name9        = 'Carga horária';
        $this->config->name10       = 'Modalidade';
        $this->config->name11       = 'Instituição / Instrutor';
        $this->config->name12       = 'Valor estimado';
        $this->config->name13       = 'Enviar';
        $this->config->name14       = 'Voltar';
        $this->config->op1          = 'Não aplicável';
        $this->config->op2          = '1. Combate à corrupção e recuperação de ativos';
        $this->config->op3          = '2. Judicialização da saúde pública';
        $this->config->op4          = '3. Mecanismos para resolver controvérsias e disputas em organizações internacionais';
        $this->config->op5          = 'Não aplicável';
        $this->config->op6          = '38. Gestão de competências';
        $this->config->op7          = '39. Educação Corporativa';
        $this->config->op8          = '40. Designer industrial';
        $this->config->op9          = '41. Produção e edição de vídeo';
        $this->config->op10         = 'Presencial';
        $this->config->op11         = 'Transmissão interna ao vivo (teams)';
        $this->config->op12         = 'Sala de estudo virtual (moodle)';
        $this->config->op13         = 'Ciclo permanente de ações de treinamento a distância';

        $text = '';
        //$text .= var_dump($this->config->ts);
        // $text .= '<script src="'. $CFG->wwwroot . '/blocks/eva_training_suggestion/js/select2.js' .'"></script>';
        // $text .= '<script src="'. $CFG->wwwroot . '/blocks/eva_training_suggestion/js/i18n/pt-BR.js' .'"></script>';
        // $text .= '<script>
        // $(document).ready(function() {
        //     $(".js-example-basic-single").select2({
        //         "language": "pt-BR",
        //         "theme": "bootstrap",
        //         "tags": true
        //     });
        // });
        // </script>';

        if ($data->slidesnumber > 0) {
            if(!empty($this->config->alt_text)){$this->content->alt_text = $this->config->alt_text;} else {$this->content->alt_text = '';}
            if(!empty($this->config->alt_text2)){$this->content->alt_text2 = $this->config->alt_text2;} else {$this->content->alt_text2 = '';}
            if(!empty($this->config->alt_text3)){$this->content->alt_text3 = $this->config->alt_text3;} else {$this->content->alt_text3 = '';}
            if(!empty($this->config->alt_text4)){$this->content->alt_text4 = $this->config->alt_text4;} else {$this->content->alt_text4 = '';}
            if(!empty($this->config->alt_text5)){$this->content->alt_text5 = $this->config->alt_text5;} else {$this->content->alt_text5 = '';}
            if(!empty($this->config->alt_text6)){$this->content->alt_text6 = $this->config->alt_text6;} else {$this->content->alt_text6 = '';}
            if(!empty($this->config->alt_text7)){$this->content->alt_text7 = $this->config->alt_text7;} else {$this->content->alt_text7 = '';}
            if(!empty($this->config->alt_text8)){$this->content->alt_text8 = $this->config->alt_text8;} else {$this->content->alt_text8 = '';}
            if(!empty($this->config->alt_text9)){$this->content->alt_text9 = $this->config->alt_text9;} else {$this->content->alt_text9 = '';}
            if(!empty($this->config->alt_text10)){$this->content->alt_text10 = $this->config->alt_text10;} else {$this->content->alt_text10 = '';}
            if(!empty($this->config->alt_text11)){$this->content->alt_text11 = $this->config->alt_text11;} else {$this->content->alt_text11 = '';}
            if(!empty($this->config->alt_text12)){$this->content->alt_text12 = $this->config->alt_text12;} else {$this->content->alt_text12 = '';}
            if(!empty($this->config->select1)){$this->content->select1 = $this->config->select1;} else {$this->content->select1 = '';}
            if(!empty($this->config->select2)){$this->content->select2 = $this->config->select2;} else {$this->content->select2 = '';}
            if(!empty($this->config->txtarea1)){$this->content->txtarea1 = $this->config->txtarea1;} else {$this->content->txtarea1 = '';}
            if(!empty($this->config->txtarea2)){$this->content->txtarea2 = $this->config->txtarea2;} else {$this->content->txtarea2 = '';}
            if(!empty($this->config->check_id)){$this->content->check_id = $this->config->check_id;} else {$this->content->check_id = '';}
            if(!empty($this->config->check_id2)){$this->content->check_id2 = $this->config->check_id2;} else {$this->content->check_id2 = '';}
            if(!empty($this->config->check_id3)){$this->content->check_id3 = $this->config->check_id3;} else {$this->content->check_id3 = '';}
            if(!empty($this->config->input1)){$this->content->input1 = $this->config->input1;} else {$this->content->input1 = '';}
            if(!empty($this->config->input2)){$this->content->input2 = $this->config->input2;} else {$this->content->input2 = '';}
            if(!empty($this->config->input3)){$this->content->input3 = $this->config->input3;} else {$this->content->input3 = '';}
            if(!empty($this->config->input4)){$this->content->input4 = $this->config->input4;} else {$this->content->input4 = '';}
            if(!empty($this->config->input5)){$this->content->input5 = $this->config->input5;} else {$this->content->input5 = '';}
            if(!empty($this->config->button1)){$this->content->button1 = $this->config->button1;} else {$this->content->button1 = '';}
            if(!empty($this->config->button2)){$this->content->button2 = $this->config->button2;} else {$this->content->button2 = '';}
            if(!empty($this->config->userid)){$this->content->userid = $this->config->userid;} else {$this->content->userid = '';}
            if(!empty($this->config->name1)){$this->content->name1 = $this->config->name1;} else {$this->content->name1 = '';}
            if(!empty($this->config->name2)){$this->content->name2 = $this->config->name2;} else {$this->content->name2 = '';}
            if(!empty($this->config->name3)){$this->content->name3 = $this->config->name3;} else {$this->content->name3 = '';}
            if(!empty($this->config->name4)){$this->content->name4 = $this->config->name4;} else {$this->content->name4 = '';}
            if(!empty($this->config->name5)){$this->content->name5 = $this->config->name5;} else {$this->content->name5 = '';}
            if(!empty($this->config->name6)){$this->content->name6 = $this->config->name6;} else {$this->content->name6 = '';}
            if(!empty($this->config->name7)){$this->content->name7 = $this->config->name7;} else {$this->content->name7 = '';}
            if(!empty($this->config->name8)){$this->content->name8 = $this->config->name8;} else {$this->content->name8 = '';}
            if(!empty($this->config->name9)){$this->content->name9 = $this->config->name9;} else {$this->content->name9 = '';}
            if(!empty($this->config->name10)){$this->content->name10 = $this->config->name10;} else {$this->content->name10 = '';}
            if(!empty($this->config->name11)){$this->content->name11 = $this->config->name11;} else {$this->content->name11 = '';}
            if(!empty($this->config->name12)){$this->content->name12 = $this->config->name12;} else {$this->content->name12 = '';}
            if(!empty($this->config->name13)){$this->content->name13 = $this->config->name13;} else {$this->content->name13 = '';}
            if(!empty($this->config->name14)){$this->content->name14 = $this->config->name14;} else {$this->content->name14 = '';}
            if(!empty($this->config->op1)){$this->content->op1 = $this->config->op1;} else {$this->content->op1 = '';}
            if(!empty($this->config->op2)){$this->content->op2 = $this->config->op2;} else {$this->content->op2 = '';}
            if(!empty($this->config->op3)){$this->content->op3 = $this->config->op3;} else {$this->content->op3 = '';}
            if(!empty($this->config->op4)){$this->content->op4 = $this->config->op4;} else {$this->content->op4 = '';}
            if(!empty($this->config->op5)){$this->content->op5 = $this->config->op5;} else {$this->content->op5 = '';}
            if(!empty($this->config->op6)){$this->content->op6 = $this->config->op6;} else {$this->content->op6 = '';}
            if(!empty($this->config->op7)){$this->content->op7 = $this->config->op7;} else {$this->content->op7 = '';}
            if(!empty($this->config->op8)){$this->content->op8 = $this->config->op8;} else {$this->content->op8 = '';}
            if(!empty($this->config->op9)){$this->content->op9 = $this->config->op9;} else {$this->content->op9 = '';}
            if(!empty($this->config->op10)){$this->content->op10 = $this->config->op10;} else {$this->content->op10 = '';}
            if(!empty($this->config->op11)){$this->content->op11 = $this->config->op11;} else {$this->content->op11 = '';}
            if(!empty($this->config->op12)){$this->content->op12 = $this->config->op12;} else {$this->content->op12 = '';}
            if(!empty($this->config->op13)){$this->content->op13 = $this->config->op13;} else {$this->content->op13 = '';}
            //$text .= var_dump($this->content);
            if(!empty($this->config->stylesheet)){
                $text .= '<style>';
                    $text .= '.sombreamento{';
                        $text .= '-webkit-box-shadow: 2px 3px 20px 1px rgba(0,0,0,0.42);';
                        $text .= 'box-shadow: 2px 3px 20px 1px rgba(0,0,0,0.42);';
                    $text .= '}';

                    $text .= '.row{';
                        $text .= 'margin-left: 1vw;';
                        $text .= 'margin-right: 1vw;';
                    $text .= '}';

                    $text .= '.jumbotron{';
                      $text .= 'margin-left: 1vw;';
                      $text .= 'margin-right: 1vw;';
                    $text .= '}';

                    $text .= '#ccnSearchOverlayWrap{';
                        $text .= 'display: none;';
                    $text .= '}';

                    $text .= '
                    .ccnDashBlHd{
                        display: none;
                    }
                    ';
                $text .= '</style>';
            }

            // $text .= '<form action="'. $CFG->wwwroot .'/blocks/eva_training_suggestion/service.php" method="POST">';
                $text .= '
                <div id="divMsg" name="divMsg"></div>
                <div class="shortcode_widget_accprdons" style="width: 100%;">';

                    if(!empty($this->config->title)){
                    $text .= '<div>';
                        $text .= '<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">';
                        $text .=' <h1 class="fontSize" data-ccn="title" style="text-align: center;">'.format_text($this->content->title, FORMAT_HTML, array('filter' => true)).'</h1>';
                        $text .= '</div>';
                    $text .= '</div>';
                    }
                    if(!empty($this->config->heading)){
                    $text .= '<div>';
                        $text .= '<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">';
                        $text .='  <p class="fontSize2" style="text-align: justify;" data-ccn="heading">'.format_text($this->content->heading, FORMAT_HTML, array('filter' => true)).'</p>';
                        $text .= '</div>';
                    $text .= '</div>';
                    }
                    if(!empty($this->config->subtitle)){
                    $text .= '<br/><div>';
                        $text .= '<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">';
                        $text .='  <h3 class="fontSize" data-ccn="subtitle">'.format_text($this->content->subtitle, FORMAT_HTML, array('filter' => true)).'</h3>';
                        $text .= '</div>';
                    $text .= '</div>';
                    }

                    $text .= '<div class="jumbotron" style="margin-left: 0px !important; margin-right: 0px !important;">';
                        // $text .= '<input type="hidden" id="userid" name="userid" value="'. $this->config->userid .'">';
                        $text .= '<input type="hidden" id="ts" name="ts" value="'. $this->config->ts .'">';
                        $text .= '<input type="hidden" id="pageid" name="pageid" value="'. $this->config->pageid .'">';
                        $text .= '<div class="row">';
                            $text .= '<div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">';
                                $text .= '<span class="fontSize2" style="font-weight: 600;">'.$this->content->name1.'<i style="color: red;"> * </i>:&nbsp;</span>';
                                $text .= '<i class="fa fa-exclamation-circle" style="float: right; color: #000066" title="'. $this->content->alt_text .'"></i>';
                            $text .= '</div>';
                            $text .= '<div class="col-sm-12 col-md-9 col-lg-9 col-xl-9">';
                                $text .= '<select data-ccn="select1" class="form-control" id="'. $this->content->select1 .'" name="'. $this->content->select1 .'">';
                                    $text .= '<option value="">&nbsp;</option>';
                                    foreach($this->config->resultSet as $row => $val){
                                        if($this->config->rsAll[$this->config->ts]->id_superior_organ == $val->id){
                                            $text .= '<option value="'. $val->id .'" selected>'. $val->no_organ .'</option>';
                                        }else{
                                            $text .= '<option value="'. $val->id .'">'. $val->no_organ .'</option>';
                                        }
                                    }
                                $text .= '</select>';
                            $text .= '</div>';
                        $text .= '</div>';
                        $text .= '<br/>';
                        $text .= '<div class="row">';
                            $text .= '<div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">';
                                $text .= '<span class="fontSize2" style="font-weight: 600;">'.$this->content->name2.'<i style="color: red;"> * </i>:&nbsp;</span>';
                                $text .= '<i class="fa fa-exclamation-circle" style="float: right; color: #000066" title="'. $this->content->alt_text2 .'"></i>';
                            $text .= '</div>';
                            $text .= '<div class="col-sm-12 col-md-9 col-lg-9 col-xl-9">';
                                $text .= '<textarea class="form-control" rows="3" id="'. $this->content->txtarea1 .'" name="'. $this->content->txtarea1 .'">';
                                $text .= $this->config->rsAll[$this->config->ts]->ds_theme;
                                $text .= '</textarea >';
                            $text .= '</div>';
                        $text .= '</div>';
                        $text .= '<br/><hr/><br/>';

                        $slc_priority_area_legal = explode(",",$this->config->rsAll[$this->config->ts]->slc_priority_area_legal);

                        $text .= '<div class="row">';
                            $text .= '<div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">';
                                $text .= '<span class="fontSize2" style="font-weight: 600;">'.$this->content->name3.'<i style="color: red;"> * </i>:&nbsp;</span>';
                                $text .= '<i class="fa fa-exclamation-circle" style="float: right; color: #000066" title="'. $this->content->alt_text3 .'"></i>';
                            $text .= '</div>';
                            $text .= '<div class="col-sm-12 col-md-9 col-lg-9 col-xl-9">';
                                $text .= '<div class="form-check">';
                                    $text .= '<input class="form-check-input" type="checkbox" value="0" id="'. $this->content->check_id .'1" name="'. $this->content->check_id .'" '. ((in_array("0",$slc_priority_area_legal)) ? "checked" : "") .'>';
                                    $text .= '<label class="form-check-label fontSize2" for="'. $this->content->check_id .'1">';
                                        $text .= ''.$this->content->op1.'';
                                    $text .= '</label>';
                                $text .= '</div>';
                                $text .= '<div class="form-check">';
                                    $text .= '<input class="form-check-input" type="checkbox" value="1" id="'. $this->content->check_id .'2" name="'. $this->content->check_id .'" '. ((in_array("1",$slc_priority_area_legal)) ? "checked" : "") .'>';
                                    $text .= '<label class="form-check-label fontSize2" for="'. $this->content->check_id .'2">';
                                        $text .= ''.$this->content->op2.'';
                                    $text .= '</label>';
                                $text .= '</div>';
                                $text .= '<div class="form-check">';
                                    $text .= '<input class="form-check-input" type="checkbox" value="2" id="'. $this->content->check_id .'3" name="'. $this->content->check_id .'" '. ((in_array("2",$slc_priority_area_legal)) ? "checked" : "") .'>';
                                    $text .= '<label class="form-check-label fontSize2" for="'. $this->content->check_id .'3">';
                                        $text .= ''.$this->content->op3.'';
                                    $text .= '</label>';
                                $text .= '</div>';
                                $text .= '<div class="form-check">';
                                    $text .= '<input class="form-check-input" type="checkbox" value="3" id="'. $this->content->check_id .'3" name="'. $this->content->check_id .'" '. ((in_array("3",$slc_priority_area_legal)) ? "checked" : "") .'>';
                                    $text .= '<label class="form-check-label fontSize2" for="'. $this->content->check_id .'3">';
                                        $text .= ''.$this->content->op4.'';
                                    $text .= '</label>';
                                $text .= '</div>';
                            $text .= '</div>';
                        $text .= '</div>';
                        $text .= '<br/><hr/><br/>';

                        $slc_technical_legal = explode(",",$this->config->rsAll[$this->config->ts]->slc_technical_legal);

                        $text .= '<div class="row">';
                            $text .= '<div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">';
                                $text .= '<span class="fontSize2" style="font-weight: 600;">'.$this->content->name4.':&nbsp;</span>';
                                $text .= '<i class="fa fa-exclamation-circle" style="float: right; color: #000066" title="'. $this->content->alt_text4 .'"></i>';
                            $text .= '</div>';
                            $text .= '<div class="col-sm-12 col-md-9 col-lg-9 col-xl-9">';
                                $text .= '<div class="form-check">';
                                    $text .= '<input class="form-check-input" type="checkbox" value="0" id="'. $this->content->check_id2 .'1" name="'. $this->content->check_id2 .'" '. ((in_array("0",$slc_technical_legal)) ? "checked" : "") .'>';
                                    $text .= '<label class="form-check-label fontSize2" for="'. $this->content->check_id2 .'1">';
                                        $text .= ''.$this->content->op5.'';
                                    $text .= '</label>';
                                $text .= '</div>';
                                $text .= '<div class="form-check">';
                                    $text .= '<input class="form-check-input" type="checkbox" value="38" id="'. $this->content->check_id2 .'2" name="'. $this->content->check_id2 .'" '. ((in_array("38",$slc_technical_legal)) ? "checked" : "") .'>';
                                    $text .= '<label class="form-check-label fontSize2" for="'. $this->content->check_id2 .'2">';
                                        $text .= ''.$this->content->op6.'';
                                    $text .= '</label>';
                                $text .= '</div>';
                                $text .= '<div class="form-check">';
                                    $text .= '<input class="form-check-input" type="checkbox" value="39" id="'. $this->content->check_id2 .'3" name="'. $this->content->check_id2 .'" '. ((in_array("39",$slc_technical_legal)) ? "checked" : "") .'>';
                                    $text .= '<label class="form-check-label fontSize2" for="'. $this->content->check_id2 .'3">';
                                        $text .= ''.$this->content->op7.'';
                                    $text .= '</label>';
                                $text .= '</div>';
                                $text .= '<div class="form-check">';
                                    $text .= '<input class="form-check-input" type="checkbox" value="40" id="'. $this->content->check_id2 .'4" name="'. $this->content->check_id2 .'" '. ((in_array("40",$slc_technical_legal)) ? "checked" : "") .'>';
                                    $text .= '<label class="form-check-label fontSize2" for="'. $this->content->check_id2 .'4">';
                                        $text .= ''.$this->content->op8.'';
                                    $text .= '</label>';
                                $text .= '</div>';
                                $text .= '<div class="form-check">';
                                    $text .= '<input class="form-check-input" type="checkbox" value="41" id="'. $this->content->check_id2 .'5" name="'. $this->content->check_id2 .'" '. ((in_array("41",$slc_technical_legal)) ? "checked" : "") .'>';
                                    $text .= '<label class="form-check-label fontSize2" for="'. $this->content->check_id2 .'5">';
                                        $text .= ''.$this->content->op9.'';
                                    $text .= '</label>';
                                $text .= '</div>';
                            $text .= '</div>';
                        $text .= '</div>';
                        $text .= '<br/><hr/><br/>';
                        $text .= '<div class="row">';
                            $text .= '<div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">';
                                $text .= '<span class="fontSize2" style="font-weight: 600;">'.$this->content->name5.'<i style="color: red;"> * </i>:&nbsp;</span>';
                                $text .= '<i class="fa fa-exclamation-circle" style="float: right; color: #000066" title="'. $this->content->alt_text5 .'"></i>';
                            $text .= '</div>';
                            $text .= '<div class="col-sm-12 col-md-9 col-lg-9 col-xl-9">';
                                $text .= '<textarea class="form-control fontSize2" rows="3" id="'. $this->content->txtarea2 .'" name="'. $this->content->txtarea2 .'">';
                                $text .= $this->config->rsAll[$this->config->ts]->ds_development_need;
                                $text .= '</textarea >';
                            $text .= '</div>';
                        $text .= '</div>';
                        $text .= '<br/>';
                        $text .= '<div class="row">';
                            $text .= '<div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">';
                                $text .= '<span class="fontSize2" style="font-weight: 600;">'.$this->content->name6.'<i style="color: red;"> * </i>:&nbsp;</span>';
                                $text .= '<i class="fa fa-exclamation-circle" style="float: right; color: #000066" title="'. $this->content->alt_text6 .'"></i>';
                            $text .= '</div>';
                            $text .= '<div class="col-sm-12 col-md-9 col-lg-9 col-xl-9">';
                                $text .= '<input class="form-control" type="text" value="'. $this->config->rsAll[$this->config->ts]->ds_target_audience .'" id="'. $this->content->input1 .'" name="'. $this->content->input1 .'">';
                            $text .= '</div>';
                        $text .= '</div>';
                        $text .= '<br/>';
                        $text .= '<div class="row">';
                            $text .= '<div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">';
                                $text .= '<span class="fontSize2" style="font-weight: 600;">'.$this->content->name7.'<i style="color: red;"> * </i>:&nbsp;</span>';
                                $text .= '<i class="fa fa-exclamation-circle" style="float: right; color: #000066" title="'. $this->content->alt_text7 .'"></i>';
                            $text .= '</div>';
                            $text .= '<div class="col-sm-12 col-md-9 col-lg-9 col-xl-9">';
                                $text .= '<input class="form-control" type="number" min="1" value="'. $this->config->rsAll[$this->config->ts]->nu_participants .'" id="'. $this->content->input2 .'" name="'. $this->content->input2 .'">';
                            $text .= '</div>';
                        $text .= '</div>';
                        $text .= '<br/>';
                        $text .= '<div class="row">';
                            $text .= '<div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">';
                                $text .= '<span class="fontSize2" style="font-weight: 600;">'.$this->content->name8.'<i style="color: red;"> * </i>:&nbsp;</span>';
                                $text .= '<i class="fa fa-exclamation-circle" style="float: right; color: #000066" title="'. $this->content->alt_text8 .'"></i>';
                            $text .= '</div>';
                            $text .= '<div class="col-sm-12 col-md-9 col-lg-9 col-xl-9">';
                                $text .= '<select data-ccn="select2" class="form-control" id="'. $this->content->select2 .'" name="'. $this->content->select2 .'">';
                                    $text .= '<option value="">&nbsp;</option>';
                                    $text .= '<option value="1" '. (($this->config->rsAll[$this->config->ts]->ds_transversality == "1") ? "selected" : "") .'>Apenas para este órgão / unidade</option>';
                                    $text .= '<option value="2" '. (($this->config->rsAll[$this->config->ts]->ds_transversality == "2") ? "selected" : "") .'>Atende a todos</option>';
                                $text .= '</select>';
                            $text .= '</div>';
                        $text .= '</div>';
                        $text .= '<br/>';
                        $text .= '<div class="row">';
                            $text .= '<div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">';
                                $text .= '<span class="fontSize2" style="font-weight: 600;">'.$this->content->name9.'<i style="color: red;"> * </i>:&nbsp;</span>';
                                $text .= '<i class="fa fa-exclamation-circle" style="float: right; color: #000066" title="'. $this->content->alt_text9 .'"></i>';
                            $text .= '</div>';
                            $text .= '<div class="col-sm-12 col-md-9 col-lg-9 col-xl-9">';
                                $text .= '<input class="form-control" type="text" value="'. $this->config->rsAll[$this->config->ts]->ds_workload .'" id="'. $this->content->input3 .'" name="'. $this->content->input3 .'">';
                            $text .= '</div>';
                        $text .= '</div>';
                        $text .= '<br/><hr/><br/>';

                        $slc_modality = explode(",",$this->config->rsAll[$this->config->ts]->slc_modality);

                        $text .= '<div class="row">';
                            $text .= '<div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">';
                                $text .= '<span class="fontSize2" style="font-weight: 600;">'.$this->content->name10.':&nbsp;</span>';
                                $text .= '<i class="fa fa-exclamation-circle" style="float: right; color: #000066" title="'. $this->content->alt_text10 .'"></i>';
                            $text .= '</div>';
                            $text .= '<div class="col-sm-12 col-md-9 col-lg-9 col-xl-9">';
                                $text .= '<div class="form-check">';
                                    $text .= '<input class="form-check-input" type="checkbox" value="1" id="'. $this->content->check_id3 .'1" name="'. $this->content->check_id3 .'" '. ((in_array("1",$slc_modality)) ? "checked" : "") .'>';
                                    $text .= '<label class="form-check-label fontSize2" for="'. $this->content->check_id3 .'1">';
                                        $text .= ''.$this->content->op10.'';
                                    $text .= '</label>';
                                $text .= '</div>';
                                $text .= '<div class="form-check">';
                                    $text .= '<input class="form-check-input" type="checkbox" value="2" id="'. $this->content->check_id3 .'2" name="'. $this->content->check_id3 .'" '. ((in_array("2",$slc_modality)) ? "checked" : "") .'>';
                                    $text .= '<label class="form-check-label fontSize2" for="'. $this->content->check_id3 .'2">';
                                        $text .= ''.$this->content->op11.'';
                                    $text .= '</label>';
                                $text .= '</div>';
                                $text .= '<div class="form-check">';
                                    $text .= '<input class="form-check-input" type="checkbox" value="3" id="'. $this->content->check_id3 .'3" name="'. $this->content->check_id3 .'" '. ((in_array("3",$slc_modality)) ? "checked" : "") .'>';
                                    $text .= '<label class="form-check-label fontSize2" for="'. $this->content->check_id3 .'3">';
                                        $text .= ''.$this->content->op12.'';
                                    $text .= '</label>';
                                $text .= '</div>';
                                $text .= '<div class="form-check">';
                                    $text .= '<input class="form-check-input" type="checkbox" value="4" id="'. $this->content->check_id3 .'4" name="'. $this->content->check_id3 .'" '. ((in_array("4",$slc_modality)) ? "checked" : "") .'>';
                                    $text .= '<label class="form-check-label fontSize2" for="'. $this->content->check_id3 .'4">';
                                        $text .= ''.$this->content->op13.'';
                                    $text .= '</label>';
                                $text .= '</div>';
                            $text .= '</div>';
                        $text .= '</div>';
                        $text .= '<br/><hr/><br/>';
                        $text .= '<div class="row">';
                            $text .= '<div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">';
                                $text .= '<span class="fontSize2" style="font-weight: 600;">'.$this->content->name11.':&nbsp;</span>';
                                $text .= '<i class="fa fa-exclamation-circle" style="float: right; color: #000066" title="'. $this->content->alt_text11 .'"></i>';
                            $text .= '</div>';
                            $text .= '<div class="col-sm-12 col-md-9 col-lg-9 col-xl-9">';
                                $text .= '<input class="form-control" type="text" value="'. $this->config->rsAll[$this->config->ts]->no_institution_instructor .'" id="'. $this->content->input4 .'" name="'. $this->content->input4 .'">';
                            $text .= '</div>';
                        $text .= '</div>';
                        $text .= '<br/>';
                        $text .= '<div class="row">';
                            $text .= '<div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">';
                                $text .= '<span class="fontSize2" style="font-weight: 600;">'.$this->content->name12.':&nbsp;</span>';
                                $text .= '<i class="fa fa-exclamation-circle" style="float: right; color: #000066" title="'. $this->content->alt_text12 .'"></i>';
                            $text .= '</div>';
                            $text .= '<div class="col-sm-12 col-md-9 col-lg-9 col-xl-9">';
                                $text .= '<div class="input-group mb-3">';
                                    $text .= '<span class="fontSize2" class="input-group-text">R$</span>';
                                    $text .= '<input class="form-control" type="text" onKeyPress="return(moeda(this,\'.\',\',\',event))" value="'. $this->config->rsAll[$this->config->ts]->nu_estimated_value .'" id="'. $this->content->input5 .'" name="'. $this->content->input5 .'">';
                                $text .= '</div>';
                            $text .= '</div>';
                        $text .= '</div>';

                    $text .= '</div>';

                    $url = $data->url;
                    $text .= '<div class="row">';
                        $text .= '<input onclick="saveTrainingSuggestion();" class="btn btn-primary btn-lg ml-3" type="button" id="'. $this->config->button1 .'" name="'. $this->config->button1 .'" value="'.$this->content->name13.'">';
                        $text .= '<input onclick="window.location.href = \''. $url .'\'" class="btn btn-primary btn-lg ml-3" type="button" id="'. $this->config->button2 .'" name="'. $this->config->button2 .'" value="'.$this->content->name14.'">';
                    $text .= '</div>';

                $text .= '</div>';
            // $text .= '</form>';
        }

        require_once($CFG->dirroot . '/blocks/eva_training_suggestion/functions.php');

        $this->content = new stdClass;
        $this->content->footer = '';
        $this->content->text = '';

        return $this->content;

    }

    public function html_attributes() {
      global $CFG;
      $attributes = parent::html_attributes();
      include($CFG->dirroot . '/theme/evagu/ccn/block_handler/attributes.php');
      return $attributes;
    }
}
