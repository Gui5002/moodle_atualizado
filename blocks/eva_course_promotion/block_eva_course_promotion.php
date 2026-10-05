<?php
// Impede o acesso direto ao arquivo
defined('MOODLE_INTERNAL') || die();

// Carrega o arquivo de funções auxiliares do bloco
require_once(__DIR__ . '/locallib.php');

class block_eva_course_promotion extends block_base {

    public function init() {
        $this->title = get_string('pluginname', 'block_eva_course_promotion');
    }

    public function applicable_formats() {
        return array('site-index' => true, 'course-view' => true, 'my' => true, 'all' => true);
    }

    public function instance_allow_multiple() {
        return true;
    }

    public function hide_header() {
        return true;
    }

    public function html_attributes() {
        $attributes = parent::html_attributes();
        
        if (!isset($attributes['class'])) {
            $attributes['class'] = '';
        }
        $attributes['class'] .= ' bg-transparent border-0 shadow-none';
        
        if (!isset($attributes['style'])) {
            $attributes['style'] = '';
        }
        $attributes['style'] .= ' background-color: transparent !important; border: none !important; box-shadow: none !important; padding-top: 0 !important;';
        
        return $attributes;
    }

    public function get_content() {
        if ($this->content !== null) {
            return $this->content;
        }

        global $DB, $CFG, $OUTPUT;

        $this->content = new stdClass();
        $this->content->text = '';
        $this->content->footer = '';

        $main_title = !empty($this->config->main_title) ? $this->config->main_title : 'Nossos melhores cursos';
        $subtitle = !empty($this->config->subtitle) ? $this->config->subtitle : 'Seleção dos melhores cursos da plataforma';
        $config_abas = !empty($this->config->custom_tabs) ? $this->config->custom_tabs : '';

        $abas_manuais = [];
        $todos_cursos_ids = [];

        // Processamento das abas e IDs configurados
        if (!empty($config_abas)) {
            $linhas = explode("\n", $config_abas);
            foreach ($linhas as $index => $linha) {
                $linha = trim($linha);
                if (empty($linha)) continue;

                $partes = explode('|', $linha);
                if (count($partes) == 2) {
                    $nome_aba = trim($partes[0]);
                    $ids_string = trim($partes[1]);
                    
                    $itens = explode(',', $ids_string);
                    $ids_cursos_da_aba = [];

                    foreach ($itens as $item) {
                        $cid = trim($item);
                        if (is_numeric($cid)) {
                            $ids_cursos_da_aba[] = (int)$cid;
                            $todos_cursos_ids[] = (int)$cid;
                        }
                    }

                    if (!empty($ids_cursos_da_aba)) {
                        $slug_aba = 'cat-' . md5($nome_aba . $index); 
                        $abas_manuais[$slug_aba] = [
                            'nome' => $nome_aba,
                            'cursos' => $ids_cursos_da_aba
                        ];
                    }
                }
            }
        }

        $todos_cursos_ids = array_unique($todos_cursos_ids);

        // ====================================================================
        // CHAMADAS LIMPAS USANDO O ARQUIVO AUXILIAR
        // ====================================================================
        $course_workloads = block_eva_course_promotion_get_workloads($todos_cursos_ids);
        $courses = block_eva_course_promotion_get_courses($todos_cursos_ids);
        // ====================================================================
        
        $html = '<div class="container eva-course-grid-module pt-4 pb-4">';
        
        // Cabeçalho
        $html .= '  <div class="row">';
        $html .= '    <div class="col-lg-10 offset-lg-1 text-center">';
        $html .= '      <div class="main-title text-center">';
        $html .= '        <h1 class="mt0" data-ccn="title">' . s($main_title) . '</h1>';
        $html .= '        <p data-ccn="subtitle">' . s($subtitle) . '</p>';
        $html .= '      </div>';
        $html .= '    </div>';
        $html .= '  </div>';

        if (!empty($abas_manuais) && !empty($courses)) {
            
            // Abas de Navegação
            $html .= '  <div class="row mb-5">';
            $html .= '    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">';
            $html .= '      <div id="options" class="alpha-pag full text-center">';
            $html .= '        <div class="option-isotop">';
            $html .= '          <ul id="filter" class="option-set" data-option-key="filter" style="padding-left: 0; margin-bottom: 0;">';
            
            $is_first = true;
            foreach ($abas_manuais as $slug => $aba) {
                $selected_class = $is_first ? 'selected' : '';
                $html .= '            <li class="list-inline-item"><a href="#' . $slug . '" data-option-value=".' . $slug . '" class="eva-filter-btn ' . $selected_class . '" data-target="' . $slug . '" style="cursor: pointer;">' . s($aba['nome']) . '</a></li>';
                $is_first = false;
            }
            $html .= '<style>
            .eva-filter-btn.selected, #filter li a.selected {
                padding-bottom: 0px !important;
                margin-bottom: 0px !important;
            }
        </style>';
            $html .= '          </ul>';
            $html .= '        </div>';
            $html .= '      </div>';
            $html .= '    </div>';
            $html .= '  </div>';

            // Container da Grade
            $html .= '  <div class="emply-text-sec">';
            $html .= '    <div class="row eva-courses-grid-wrap d-flex flex-wrap justify-content-start">';

            foreach ($courses as $curso) {
                $classes_do_curso = [];
                foreach ($abas_manuais as $slug => $aba) {
                    if (in_array($curso->id, $aba['cursos'])) {
                        $classes_do_curso[] = $slug;
                    }
                }
                $string_classes = implode(' ', $classes_do_curso);

                // Imagem
                $course_context = context_course::instance($curso->id);
                $fs = get_file_storage();
                $files = $fs->get_area_files($course_context->id, 'course', 'overviewfiles', false, 'filename', false);
                $course_image = '';
                foreach ($files as $file) {
                    if ($file->is_valid_image()) {
                        $course_image = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), null, $file->get_filepath(), $file->get_filename())->out();
                        break;
                    }
                }
                if (empty($course_image)) {
                    $course_image = $OUTPUT->image_url('course', 'theme')->out(); 
                }

                // Dados Numéricos
                $qtd_alunos = $DB->count_records_sql("SELECT COUNT(DISTINCT u.id) FROM {user} u JOIN {user_enrolments} ue ON ue.userid = u.id JOIN {enrol} e ON e.id = ue.enrolid WHERE e.courseid = ? AND u.deleted = 0 AND u.suspended = 0", array($curso->id));
                $qtd_comentarios = $DB->count_records_sql("SELECT COUNT(p.id) FROM {forum_posts} p JOIN {forum_discussions} d ON d.id = p.discussion WHERE d.course = ?", array($curso->id));
                
                // Carga horária
                $texto_direita = isset($course_workloads[$curso->id]) ? $course_workloads[$curso->id] : 'Gratuito';

                if ($texto_direita !== 'Gratuito') {
                    if (preg_match('/\d+/', $texto_direita, $matches)) {
                        $num = str_pad($matches[0], 2, '0', STR_PAD_LEFT);
                        $texto_direita = $num . ' horas';
                    }
                    $render_preco = '<span class="ccn-flaticon-time" style="margin-right: 3px;"></span> ' . $texto_direita;
                } else {
                    $render_preco = $texto_direita;
                }

                // Card
                $html .= '      <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-4 mb-4 eva-course-card ' . $string_classes . '" style="transition: opacity 0.3s ease;">';
                $html .= '        <div data-ccn-c="color_course_card" data-ccn-co="bg" style="background-color: rgb(255, 255, 255);" class="top_courses ccnWithFoot h-100 d-flex flex-column">';
                
                $html .= '          <a href="' . $CFG->wwwroot . '/course/view.php?id=' . $curso->id . '">';
                $html .= '            <div class="thumb" style="overflow: hidden;">';
                $html .= '              <img class="img-whp" src="' . $course_image . '" alt="' . s($curso->fullname) . '" style="object-fit: cover; height: 210px; width: 100%; display: block;">';
                $html .= '              <div class="overlay">';
                $html .= '                <div class="tag" data-ccn="hover_accent">Mais Vistos</div>';
                $html .= '                <span class="tc_preview_course" data-ccn="hover_text">Prévia do Curso</span>';
                $html .= '              </div>';
                $html .= '            </div>';
                $html .= '          </a>';
                
                $html .= '          <div class="details" style="flex-grow: 1;">';
                $html .= '            <div class="tc_content">';
                $html .= '              <a href="' . $CFG->wwwroot . '/course/view.php?id=' . $curso->id . '">';
                $html .= '                <h5 data-ccn-c="color_course_title" data-ccn-co="content" style="color: rgb(32, 51, 103);">' . s($curso->fullname) . '</h5>';
                $html .= '              </a>';
                $html .= '            </div>';
                $html .= '          </div>';
                
                $html .= '          <div class="tc_footer">';
                $html .= '            <ul class="tc_meta float-left">';
                $html .= '              <li class="list-inline-item"><i class="flaticon-profile"></i></li>';
                $html .= '              <li class="list-inline-item">' . $qtd_alunos . '</li>';
                $html .= '              <li class="list-inline-item"><i class="flaticon-comment"></i></li>';
                $html .= '              <li class="list-inline-item">' . $qtd_comentarios . '</li>';
                $html .= '            </ul>';
                $html .= '            <div class="tc_price float-right" data-ccn="course_price" data-ccn-co="content" data-ccn-c="color_course_price" style="color: rgb(63, 63, 63); display: block !important; visibility: visible !important;">' . $render_preco . '</div>';
                $html .= '          </div>'; 
                
                $html .= '        </div>'; 
                $html .= '      </div>'; 
            }

            $html .= '    </div>'; 
            $html .= '  </div>'; 

        } else {
            $html .= '  <div class="alert alert-info">Nenhum curso configurado ou encontrado. Configure as abas nas configurações do bloco.</div>';
        }

        // Botão
        $html .= '  <div class="row mt-5 pt-3">';
        $html .= '    <div class="col-12 text-center">';
        $html .= '      <div class="courses_all_btn text-center">';
        $html .= '        <a class="btn btn-transparent" data-ccn="button_text" href="' . $CFG->wwwroot . '/course/">Ver todos os Cursos</a>';
        $html .= '      </div>';
        $html .= '    </div>';
        $html .= '  </div>';

        $html .= '</div>'; 

        // Script de controle das abas
        $html .= '<script>';
        $html .= 'document.addEventListener("DOMContentLoaded", function() {';
        $html .= '    var modulos = document.querySelectorAll(".eva-course-grid-module");';
        $html .= '    modulos.forEach(function(modulo) {';
        $html .= '        var abas = modulo.querySelectorAll(".eva-filter-btn");';
        $html .= '        var cards = modulo.querySelectorAll(".eva-course-card");';
        $html .= '        abas.forEach(function(aba) {';
        $html .= '            aba.addEventListener("click", function(e) {';
        $html .= '                e.preventDefault();';
        $json_abas = ''; // mantido padrão
        $html .= '                abas.forEach(function(btn) { btn.classList.remove("selected"); });';
        $html .= '                this.classList.add("selected");';
        $html .= '                var targetSlug = this.getAttribute("data-target");';
        $html .= '                cards.forEach(function(card) {';
        $html .= '                    if (card.classList.contains(targetSlug)) {';
        $html .= '                        card.style.display = "block";';
        $html .= '                        setTimeout(function() { card.style.opacity = "1"; }, 50);';
        $html .= '                    } else {';
        $html .= '                        card.style.display = "none";';
        $html .= '                        card.style.opacity = "0";';
        $html .= '                    }';
        $html .= '                });';
        $html .= '            });';
        $html .= '        });';
        $html .= '        if (abas.length > 0) { abas[0].click(); }';
        $html .= '    });';
        $html .= '});';
        $html .= '</script>';

        $this->content->text = $html;

        return $this->content;
    }
}