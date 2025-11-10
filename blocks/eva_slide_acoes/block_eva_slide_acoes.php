<?php
global $CFG;
require_once($CFG->dirroot . '/theme/evagu/ccn/block_handler/ccn_block_handler.php');
class block_eva_slide_acoes extends block_base {
    function init() {
        $this->title = get_string('pluginname', 'block_eva_slide_acoes');
    }
    function applicable_formats() {
        $ccnBlockHandler = new ccnBlockHandler();
        return $ccnBlockHandler->ccnGetBlockApplicability(array('all'));
    }
    function specialization() {
        global $CFG, $DB;
        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/specialization.php');
    }
    function instance_allow_multiple() {
        return true;
    }
    function get_content() {
        global $DB, $CFG, $PAGE;
        require_once($CFG->libdir . '/filelib.php');
        if ($this->content !== NULL) {
            return $this->content;
        }
        $total = $DB->get_field_sql("SELECT COUNT(*) AS total
                             FROM {block_instances} bi
                             JOIN {context} ctx ON bi.parentcontextid = ctx.id
                             WHERE ctx.instanceid = 9
                             AND bi.blockname = 'eva_featured_event'");
        $sql = "SELECT bi.id AS blockid, bi.configdata, ctx.id AS contextid
                FROM {block_instances} bi
                JOIN {context} ctx ON ctx.instanceid = bi.id
                WHERE ctx.contextlevel = 80
                AND bi.parentcontextid IN (SELECT id FROM {context} WHERE instanceid = 9)
                AND bi.blockname = 'eva_featured_event'";
        // Executar a consulta e obter os resultados
        $eventos = $DB->get_records_sql($sql);
        $fs = get_file_storage();
        $lista_eventos = []; // Criar um array para armazenar os eventos processados
        $dataAtual = date('Y-m-d');
        $i = 0;
        foreach ($eventos as $evento) {
            $configdata = unserialize(base64_decode($evento->configdata));
            if (is_object($configdata)) {
                $configdata = (array) $configdata;
            }
            $configdata['bloco'] = $evento->blockid;
            // Buscar a imagem associada ao evento
            $img = context_block::instance($evento->blockid);
            $files = $fs->get_area_files($img->id, 'block_eva_featured_event', 'content', 0, 'sortorder DESC, id ASC', false, 0, 0, 1);
            $imgurl = ''; // URL padrão caso não tenha imagem
            foreach ($files as $file) {
                if (!$file->is_directory()) {
                    $imgurl = moodle_url::make_pluginfile_url(
                        $file->get_contextid(),
                        $file->get_component(),
                        $file->get_filearea(),
                        $file->get_itemid(),
                        $file->get_filepath(),
                        $file->get_filename()
                    )->out(false);
                    break;
                }
            }
            $diaData = date('d', $configdata['date']);
            $mesData = date('m', $configdata['date']);
            $anoData = date('Y', $configdata['date']);
            // Converter número do mês para nome abreviado
            $meses = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
            $mesNome = $meses[(int)$mesData - 1];
            // Corrigir a URL da imagem
            $imgurl = str_replace('/0/', '/', $imgurl);
            // Adicionar os dados ao array estruturado
            $lista_eventos[] = [
                'blockid' => $evento->blockid,
                'configdata' => $configdata,
                'date' => $configdata['date'], // Armazenar a data para ordenação
                'dia' => $diaData,
                'mesNome' => $mesNome,
                'mesData' => $mesData,
                'ano' => $anoData,
                'imgurl' => $imgurl,
                'dataEvento' => date('Y-m-d', $configdata['date'])
            ];
        }
        usort($lista_eventos, function ($a, $b) {
            return $a['date'] - $b['date']; // Ordenação crescente
        });
        // Recupera os campos de título e subtítulo configurados
        $ccn_title    = !empty($this->config->title) ? $this->config->title : '';
        $ccn_subtitle = !empty($this->config->subtitle) ? $this->config->subtitle : '';
        // Define a variável de configuração
        if (!empty($this->config) && is_object($this->config)) {
            $data = $this->config;
            $data->slidesnumber = $total;
            // Define o estilo do slider conforme a configuração (Standard ou Fullsize)
                $slidersize = 'slide slide-one home6';
        } else {
            $data = new stdClass();
            $data->slidesnumber = 0;
        }
        $text = '';
        if ($data->slidesnumber >= 0) {
            $text .= '<section class="our-blog">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 offset-lg-3">
          <div class="main-title text-center">';
            if (!empty($ccn_title)) {
                $text .= '<h1 class="mt0">' . format_text($ccn_title, FORMAT_HTML, array('filter' => true)) . '</h1>';
            }
            if (!empty($ccn_subtitle)) {
                $text .= '<p>' . format_text($ccn_subtitle, FORMAT_HTML, array('filter' => true)) . '</p>';
            }
            $text .= '</div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <div class="blog_post_slider_home2">';
            foreach ($lista_eventos as $evento) {
                if($evento['dataEvento'] < $dataAtual ){
                        continue;
                    } else {
                    $i++;
                    $text .= '<div class="item">';
                    if ($evento['imgurl']) {
                        $text .= '<a href="' . $evento['configdata']['button_link'] . '">';
                    }
                    $text .= '<div class="blog_post_home2" style="background-color: transparent!important;">
                          <div class="bph2_header" style="background-color: transparent!important;">
                            <img class="img-fluid" src="' . $evento['imgurl'] . '" alt="">';
                    $text .= '</div>
                          <div class="details" style="width: 100%; background-color: #0d1c29c4; padding: 0!important; bottom: 0px!important; padding-top: 5px; font-family: helvetica, arial, sans-serif; font-weight: 600; line-height: 1.5;">
                            <div class="post_meta">
                                <div class="row" style="margin: 5px 0 5px 0!important">
                                    <div class="col-2" style="display: flex; align-items: center; justify-content: left; color: white;">
                                        <center style="margin-left: 15px;"><span><div style="font-size: 18px;">' . $evento['dia'] . '</div>'. $evento['mesNome'] . ' </span></center>
                                    </div>
                                    <div class="col-10" style="display: flex; align-items: center; justify-content: left;">
                                            <ul style="margin: 0!important;">';
                                        if (!empty($evento['configdata']['time'])) {
                                            $text .= '<li class="list-inline-item"><span><i class="flaticon-clock"></i>' . $evento['configdata']['time'] . '</span></li>';
                                        }
                                        if (!empty($evento['configdata']['location'])) {
                                            $text .= '<br><li class="list-inline-item""><span><i class="flaticon-placeholder"></i>' . $evento['configdata']['location'] . '</span></li>';
                                        }
                                    $text .= '</ul>
                                    </div>
                                </div>
                            </div>';
                    $text .= '</div>
                        </div>';
                    if ($evento['imgurl']) {
                        $text .= '</a>';
                    }
                    $text .= '</div>';
                }
            }
            //$i = 0;
            switch ($i) {
                case '0':
                    $completar = '  <div class="item">
                                    <a href="https://www.youtube.com/@EscoladaAGU">
                                        <div class="blog_post_home2" style="background-color: transparent!important;">
                                            <div class="bph2_header" style="background-color: transparent!important;">
                                                <img class="img-fluid" src="/theme/evagu/images/acoes/youtube.jpg" alt="">
                                            </div>
                                            <div class="details" style="width: 100%; background-color: #0d1c29c4; padding: 0!important; bottom: 0px!important; padding-top: 5px; font-family: helvetica, arial, sans-serif; font-weight: 600; line-height: 1.5;">
                                                <div class="post_meta">
                                                    <div class="row" style="margin: 5px 0 5px 0!important">
                                                        <div class="col-12" style="display: flex; align-items: center; justify-content: center;margin-bottom: 27px;">
                                                            <ul style="margin: 0!important;">
                                                                <li class="list-inline-item"">
                                                                    <span>
                                                                    <i class="flaticon-placeholder"></i>Canal ESAGU
                                                                    </span>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>';
                    $completar .= '  <div class="item">
                                <a href="https://www.instagram.com/escolasuperiordagu/">
                                    <div class="blog_post_home2" style="background-color: transparent!important;">
                                        <div class="bph2_header" style="background-color: transparent!important;">
                                            <img class="img-fluid" src="/theme/evagu/images/acoes/instagram.jpg" alt="">
                                        </div>
                                        <div class="details" style="width: 100%; background-color: #0d1c29c4; padding: 0!important; bottom: 0px!important; padding-top: 5px; font-family: helvetica, arial, sans-serif; font-weight: 600; line-height: 1.5;">
                                            <div class="post_meta">
                                                <div class="row" style="margin: 5px 0 5px 0!important">
                                                    <div class="col-12" style="display: flex; align-items: center; justify-content: center;margin-bottom: 27px;">
                                                        <ul style="margin: 0!important;">
                                                            <li class="list-inline-item"">
                                                                <span>
                                                                <i class="flaticon-placeholder"></i>Instagram ESAGU
                                                                </span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>';
                    $completar .= '  <div class="item">
                                    <a href="https://revistaagu.agu.gov.br/index.php/EAGU">
                                        <div class="blog_post_home2" style="background-color: transparent!important;">
                                            <div class="bph2_header" style="background-color: transparent!important;">
                                                <img class="img-fluid" src="/theme/evagu/images/acoes/publicacoes.jpg" alt="">
                                            </div>
                                            <div class="details" style="width: 100%; background-color: #0d1c29c4; padding: 0!important; bottom: 0px!important; padding-top: 5px; font-family: helvetica, arial, sans-serif; font-weight: 600; line-height: 1.5;">
                                                <div class="post_meta">
                                                    <div class="row" style="margin: 5px 0 5px 0!important">
                                                        <div class="col-12" style="display: flex; align-items: center; justify-content: center;margin-bottom: 27px;">
                                                            <ul style="margin: 0!important;">
                                                                <li class="list-inline-item"">
                                                                    <span>
                                                                    <i class="flaticon-placeholder"></i>Publicações ESAGU
                                                                    </span>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>';
                    break;
                case '1':
                    $completar = '  <div class="item">
                                    <a href="https://www.youtube.com/@EscoladaAGU">
                                        <div class="blog_post_home2" style="background-color: transparent!important;">
                                            <div class="bph2_header" style="background-color: transparent!important;">
                                                <img class="img-fluid" src="/theme/evagu/images/acoes/youtube.jpg" alt="">
                                            </div>
                                            <div class="details" style="width: 100%; background-color: #0d1c29c4; padding: 0!important; bottom: 0px!important; padding-top: 5px; font-family: helvetica, arial, sans-serif; font-weight: 600; line-height: 1.5;">
                                                <div class="post_meta">
                                                    <div class="row" style="margin: 5px 0 5px 0!important">
                                                        <div class="col-12" style="display: flex; align-items: center; justify-content: center;margin-bottom: 27px;">
                                                            <ul style="margin: 0!important;">
                                                                <li class="list-inline-item"">
                                                                    <span>
                                                                    <i class="flaticon-placeholder"></i>Canal ESAGU
                                                                    </span>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>';
                    $completar .= '  <div class="item">
                                <a href="https://www.instagram.com/escolasuperiordagu/">
                                    <div class="blog_post_home2" style="background-color: transparent!important;">
                                        <div class="bph2_header" style="background-color: transparent!important;">
                                            <img class="img-fluid" src="/theme/evagu/images/acoes/instagram.jpg" alt="">
                                        </div>
                                        <div class="details" style="width: 100%; background-color: #0d1c29c4; padding: 0!important; bottom: 0px!important; padding-top: 5px; font-family: helvetica, arial, sans-serif; font-weight: 600; line-height: 1.5;">
                                            <div class="post_meta">
                                                <div class="row" style="margin: 5px 0 5px 0!important">
                                                    <div class="col-12" style="display: flex; align-items: center; justify-content: center;margin-bottom: 27px;">
                                                        <ul style="margin: 0!important;">
                                                            <li class="list-inline-item"">
                                                                <span>
                                                                <i class="flaticon-placeholder"></i>Instagram ESAGU
                                                                </span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>';
                    break;
                case '2':
                    $completar = '  <div class="item">
                                    <a href="https://www.youtube.com/@EscoladaAGU">
                                        <div class="blog_post_home2" style="background-color: transparent!important;">
                                            <div class="bph2_header" style="background-color: transparent!important;">
                                                <img class="img-fluid" src="/theme/evagu/images/acoes/youtube.jpg" alt="">
                                            </div>
                                            <div class="details" style="width: 100%; background-color: #0d1c29c4; padding: 0!important; bottom: 0px!important; padding-top: 5px; font-family: helvetica, arial, sans-serif; font-weight: 600; line-height: 1.5;">
                                                <div class="post_meta">
                                                    <div class="row" style="margin: 5px 0 5px 0!important">
                                                        <div class="col-12" style="display: flex; align-items: center; justify-content: center;margin-bottom: 27px;">
                                                            <ul style="margin: 0!important;">
                                                                <li class="list-inline-item"">
                                                                    <span>
                                                                    <i class="flaticon-placeholder"></i>Canal ESAGU
                                                                    </span>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>';
                    break;
               default:
                $completar ='';
                    break;
            }
            $text .= $completar;
            $text .= '</div>
        </div>
      </div>
    </div>';
            // *** Área do Footer com Botão ***
            // O botão só será exibido se o campo "footer_text" estiver preenchido.
            $textDiv = '';
            if (!empty($data->button_text)) {
                $button = '<div style="margin-top: -40px;">'.$data->footer_text.'</div><br>
                <div class="col-lg-6 offset-lg-3">
                    <div class="courses_all_btn">
                      <a class="btn btn-transparent" data-ccn="button_text"
                      href="' . $CFG->wwwroot.format_text($data->button_link, FORMAT_HTML, array('filter' => true)) . '">
                        ' . $data->button_text . '
                        <span class="flaticon-right-arrow pl10"></span>
                      </a>
                    </div>
                  </div>';
            $textDiv = '<div class="row">
              <div class="col-lg-12">
                <div class="read_more_home text-center">' . $button . '</div>
              </div>
            </div>';
                $text .= $textDiv;
            }
            $text .= '</section>';
        }
        $text .= '<script>
        $(document).ready(function(){
          let footerHtml = \'' . addslashes($textDiv) . '\';
          if ($(".block_eva_slide_acoes .owl-stage-outer").length > 0) {
              $(".block_eva_slide_acoes .owl-stage-outer").append(footerHtml);
          } else {
              $(".block_eva_slide_acoes").append(footerHtml);
          }
          $(".block_eva_slide_acoes .details h4 a").css("color","#fff");
        });
        </script>';
        $this->content = new stdClass;
        $this->content->footer = '';
        $this->content->text = $text;
        return $this->content;
    }
    function instance_config_save($data, $nolongerused = false) {
        global $CFG;
        $filemanageroptions = array(
            'maxbytes'       => $CFG->maxbytes,
            'subdirs'        => 0,
            'maxfiles'       => 1,
            'accepted_types' => array('.jpg', '.png', '.gif')
        );
        parent::instance_config_save($data, $nolongerused);
    }
    /*function instance_delete() {
        global $DB;
        $fs = get_file_storage();
        $fs->delete_area_files($this->context->id, 'block_eva_slide_acoes');
        return true;
    }*/
    /**
     * The block should only be dockable when the title of the block is not empty
     * and when parent allows docking.
     * @return bool
     */
    public function instance_can_be_docked() {
        return (!empty($this->config->title) && parent::instance_can_be_docked());
    }
    public function html_attributes() {
        global $CFG;
        $attributes = parent::html_attributes();
        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/attributes.php');
        return $attributes;
    }
}
