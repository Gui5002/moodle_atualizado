<?php

namespace block_eva_form_barema\output;

defined('MOODLE_INTERNAL') || die;

use plugin_renderer_base;


class renderer extends plugin_renderer_base {


    public function render_customize_conf(customize_conf $customize_conf) {
        $returncustomconf = $this->render_from_template('block_eva_form_barema/customize_config', $customize_conf->export_for_template($this));
        return $returncustomconf;
    }

    public function render_ModeloController(ModeloController $modelos) {
        $criarmodelo = $this->render_from_template('block_eva_form_barema/modelos/create', $modelos->export_for_template($this));

        return $criarmodelo;
    }

    public function render_AtribuicaoController(AtribuicaoController $atribuicao) {
        $returnoform = $this->render_from_template('block_eva_form_barema/atribuicoes/create', $atribuicao->export_for_template($this));
        return $returnoform;
    }

    public function render_form_barema(form_barema $form_barema) {
        $returnoformedit = $this->render_from_template('block_eva_form_barema/barema', $form_barema->export_for_template($this));
//        $returno .= $this->render_from_template('block_eva_form_barema/barema', $criarbarema->export_for_template($this));

        return $returnoformedit;
    }


    public function render_avaliador_config_view(avaliador_config_view $avaliador_conf) {
        $returnbaremaconf = $this->render_from_template('block_eva_form_barema/avaliador_barema_view', $avaliador_conf->export_for_template($this));

        return $returnbaremaconf;
    }

    public function render_avaliacao_config_view(avaliacao_config_view $avaliador_conf) {
        $returnavaliacaoview = $this->render_from_template('block_eva_form_barema/avaliacao_view', $avaliador_conf->export_for_template($this));

        return $returnavaliacaoview;
    }

    public function render_avaliacao_config_pdf(avaliacao_config_pdf $avaliador_conf) {
        $returnavaliacaopdf = $this->render_from_template('block_eva_form_barema/avaliacao_individual_pdf', $avaliador_conf->export_for_template($this));
        return $returnavaliacaopdf;
    }

    public function render_relatorio_barema_config_pdf(relatorio_barema_config_pdf $relatorio_barema_pdf) {
        $returnrelariobaremapdf = $this->render_from_template('block_eva_form_barema/relatorio_barema_pdf', $relatorio_barema_pdf->export_for_template($this));
        return $returnrelariobaremapdf;
    }

    public function render_relatorio_barema_config_xls(relatorio_barema_config_xls $relatorio_barema_xls) {
        $returnrelariobaremaxls = $this->render_from_template('block_eva_form_barema/relatorio_barema_xls', $relatorio_barema_xls->export_for_template($this));
        return $returnrelariobaremaxls;
    }

    public function render_AvaliadorController(AvaliadorController $avaliadores) {
        $returnbaremaconf = $this->render_from_template('block_eva_form_barema/avaliadores/list', $avaliadores->export_for_template($this));

        return $returnbaremaconf;
    }

    public function render_barema_config_lista(barema_config_lista $barema_config_lista) {
        $returnbaremalista = $this->render_from_template('block_eva_form_barema/barema_lista', $barema_config_lista->export_for_template($this));

        return $returnbaremalista;
    }


    public function render_gerenciar_alunos(gerenciar_alunos $gerenciar_alunos) {
        $gerenciaralunos = $this->render_from_template('block_eva_form_barema/gerenciar_alunos', $gerenciar_alunos->export_for_template($this));

        return $gerenciaralunos;
    }

    public function render_alunos(alunos $alunos) {
        $alunos = $this->render_from_template('block_eva_form_barema/alunos', $alunos->export_for_template($this));

        return $alunos;
    }

    public function render_admin_avaliadores(admin_avaliadores $admin_avaliadores) {
        $adminavaliadres = $this->render_from_template('block_eva_form_barema/admin_avaliadores', $admin_avaliadores->export_for_template($this));

        return $adminavaliadres;
    }

    public function render_admin_curso(admin_curso $admin_curso) {
        $admincurso = $this->render_from_template('block_eva_form_barema/admin_curso', $admin_curso->export_for_template($this));

        return $admincurso;
    }

    public function render_admin_alunos(admin_alunos $admin_alunos) {
        $adminalunos = $this->render_from_template('block_eva_form_barema/admin_alunos', $admin_alunos->export_for_template($this));

        return $adminalunos;
    }


    // public function render_avaliador_form_edit(avaliador_form_edit $avaliador_form_edit) {
    //     $returnavaformedit = $this->render_from_template('block_eva_form_barema/avaliador_modals_edit', $avaliador_form_edit->export_for_template($this));
    //     return $returnavaformedit;
    // }


}
