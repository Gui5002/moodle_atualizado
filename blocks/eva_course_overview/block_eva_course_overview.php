<?php
global $CFG;
require_once($CFG->dirroot. '/theme/evagu/ccn/block_handler/ccn_block_handler.php');
class block_eva_course_overview extends block_base
{
    public function init()
    {
        $this->title = get_string('eva_course_overview', 'block_eva_course_overview');

    }

    public function specialization()
    {
        global $CFG, $DB;
        include($CFG->dirroot . '/theme/evagu/ccn/block_handler/specialization.php');
        if (empty($this->config)) {
          $this->config->title = 'Informações Gerais';
          $this->config->description['text'] = '
										<h4 class="subtitle">Objetivo do Curso</h4>
										<p class="mb30">Capacitar os Membros da AGU. Idealizado pelo Prof. nome, que vai analisar todas as modificações inauguradas pela Lei Numero, de 18 de junho de 2019 no Regime Geral de Previdência Social e RPPS do servidor federal.</p>
										<h4 class="subtitle">Justificativa</h4>
                    <p class="mb30">A Lei 13.846/2019, vigente desde 18/6/2019, é fruto da conversão com mudanças da MP 871/2019, que alterou regras no RGPS, tais como período de carência, período de graça, enquadramento do segurado especial, processo administrativo, benefícios por incapacidade, pensão por morte, auxílio-reclusão, decadência, ação regressiva previdenciária e competência do CRPS, além de alterar a pensão por morte do servidor federal.</p>
										<h4 class="subtitle">Publico Alvo</h4>
                    <p class="mb30">Membros da PGF.</p>
                    <h4 class="subtitle">Carga Horária</h4>
                    <p class="mb30">06 h/a.</p>
                    <h4 class="subtitle">Metodologia</h4>
                    <p class="mb30">O curso é realizado na metodologia de educação a distância, autoinstrucional, ou seja, sem tutoria. Serão abordados ponto a ponto o que foi alterado da MP 871/2019 com os aspectos intertemporais por meio de videoaulas e material de apoio em PDF.</p>
                    <h4 class="subtitle"O que você vai aprender</h4>
										<ul class="cs_course_syslebus">
											<li><i class="fa fa-check"></i><p>Aspectos intertemporais da MP 871 e Lei 13.846.</p></li>
											<li><i class="fa fa-check"></i><p>Alterações nos arts. 15, 16, 17 e 18 da Lei 8.213</p></li>
											<li><i class="fa fa-check"></i><p>Alterações nos arts. 25, 26, 27-A e 32 da Lei 8.213</p></li>
											<li><i class="fa fa-check"></i><p>Segurado especial (arts. 38-A, 38-B, 39 e 106)</p></li>
											<li><i class="fa fa-check"></i><p>Benefícios por incapacidade (arts. 42, 59, 60,62, 101)</p></li>
										</ul>
										<ul class="cs_course_syslebus2">
											<li><i class="fa fa-check"></i><p>Prova e salário-maternidade (55, 71-D e 73).</p></li>
											<li><i class="fa fa-check"></i><p>Pensão por morte (74, 76, 77, 79 e 110).</p></li>
											<li><i class="fa fa-check"></i><p>Auxílio-reclusão (art. 80).</p></li>
											<li><i class="fa fa-check"></i><p>Contagem recíproca (art. 96).</p></li>
											<li><i class="fa fa-check"></i><p>Arts. 103, 115, 120, 121</p></li>
										</ul>
										<h4 class="subtitle">Critérios para participação</h4>
										<ul class="list_requiremetn">
											<li><i class="fa fa-circle"></i><p>Ser Procurador Federal da PGF.</p></li>
											<li><i class="fa fa-circle"></i><p>Ser Procurador Federal da PF.</p></li>
											<li><i class="fa fa-circle"></i><p>Ser Procurador Federal da AGU.</p></li>
										</ul>
									';
        }
    }

    function applicable_formats() {
      $ccnBlockHandler = new ccnBlockHandler();
      return $ccnBlockHandler->ccnGetBlockApplicability(array('course-view'));
    }

    public function get_content()
    {
        global $CFG, $DB;

        if ($this->content !== null) {
            return $this->content;
        }

        // Declare third
        $this->content         =  new stdClass;

        if(!empty($this->config->title)){$this->content->title = $this->config->title;} else {$this->content->title = '';}
        if(!empty($this->config->description)){$this->content->description = $this->config->description['text'];} else {$this->content->description = '';}

        $this->content->text = '
        <div class="cs_row_two">
          <div class="cs_overview">
            <h4 data-ccn="title" class="title">'. format_text($this->content->title, FORMAT_HTML, array('filter' => true)) .'</h4>
            <div data-ccn="description">'. format_text($this->content->description, FORMAT_HTML, array('filter' => true)) .'</div>
          </div>
        </div>';
        return $this->content;
    }
    public function html_attributes() {
      global $CFG;
      $attributes = parent::html_attributes();
      include($CFG->dirroot . '/theme/evagu/ccn/block_handler/attributes.php');
      return $attributes;
    }
}
