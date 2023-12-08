<?php

namespace block_eva_barema_bolsa\output;

defined('MOODLE_INTERNAL') || die;

use plugin_renderer_base;

class renderer extends plugin_renderer_base
{

    public function render_criar_bolsa_atribuicao(criar_bolsa_atribuicao $criar_bolsa_atribuicao) {
        $criarbolsaatribuicao = $this->render_from_template('block_eva_barema_bolsa/criar_bolsa_atribuicao', $criar_bolsa_atribuicao->export_for_template($this));
        return $criarbolsaatribuicao;
    }

    public function render_avaliacao_bolsa_page(avaliacao_bolsa_page $avaliacao_bolsa_page) {
        $returnoformedit = $this->render_from_template('block_eva_barema_bolsa/avaliacao_bolsa_page', $avaliacao_bolsa_page->export_for_template($this));
//        $returno .= $this->render_from_template('block_eva_form_barema/barema', $criarbarema->export_for_template($this));

        return $returnoformedit;
    }

    public function render_avaliador_lista(avaliador_lista $avaliador_lista) {
        $returnavaliadores = $this->render_from_template('block_eva_barema_bolsa/avaliador_lista', $avaliador_lista->export_for_template($this));

        return $returnavaliadores;
    }

    public function render_gerenciar_avaliacao(gerenciar_avaliacao $gerenciar_avaliacao) {
        $gerenciaravaliacao = $this->render_from_template('block_eva_barema_bolsa/gerenciar_avaliacao', $gerenciar_avaliacao->export_for_template($this));

        return $gerenciaravaliacao;
    }

    public function render_admin_avaliacao(admin_avaliacao $admin_avaliacao) {
        $adminavaliacao = $this->render_from_template('block_eva_barema_bolsa/admin_avaliacao', $admin_avaliacao->export_for_template($this));

        return $adminavaliacao;
    }

    public function render_gerenciar_espelho_pdf(gerenciar_espelho_pdf $avaliador_espelho) {
        $avaliadorespelho = $this->render_from_template('block_eva_barema_bolsa/avaliador_espelho_pdf', $avaliador_espelho->export_for_template($this));
        return $avaliadorespelho;
    }

    public function render_avaliacao_lista(avaliacao_lista $avaliacao_lista) {
        $returnavaliacaolista = $this->render_from_template('block_eva_barema_bolsa/avaliacao_lista', $avaliacao_lista->export_for_template($this));

        return $returnavaliacaolista;
    }

    public function render_anteprojeto_espelho_pdf(anteprojeto_espelho_pdf $anteprojeto_espelho) {
        $anteprojetoespelho = $this->render_from_template('block_eva_barema_bolsa/anteprojeto_espelho_pdf', $anteprojeto_espelho->export_for_template($this));
        return $anteprojetoespelho;
    }

    public function render_avaliacao_lista_pdf(avaliacao_lista_pdf $avaliacao_lista_pdf) {
        $avaliacaolistapdf = $this->render_from_template('block_eva_barema_bolsa/avaliacao_lista_pdf', $avaliacao_lista_pdf->export_for_template($this));

        return $avaliacaolistapdf;
    }

    public function render_criar_modelo_bolsa(criar_modelo_bolsa $criar_modelo_bolsa) {
        $criarmodelobolsa = $this->render_from_template('block_eva_barema_bolsa/criar_modelo_bolsa', $criar_modelo_bolsa->export_for_template($this));

        return $criarmodelobolsa;
    }


}