<?php

namespace block_eva_barema_afastamento\output;

defined('MOODLE_INTERNAL') || die;

use plugin_renderer_base;

class renderer extends plugin_renderer_base
{

    public function render_criar_afastamento(criar_afastamento $criar_afastamento) {
        $returnoform = $this->render_from_template('block_eva_barema_afastamento/criar_afastamento', $criar_afastamento->export_for_template($this));
        return $returnoform;
    }

    public function render_avaliacao_afastamento_page(avaliacao_afastamento_page $avaliacao_afastamento_page) {
        $returnoformedit = $this->render_from_template('block_eva_barema_afastamento/avaliacao_afastamento_page', $avaliacao_afastamento_page->export_for_template($this));
//        $returno .= $this->render_from_template('block_eva_form_barema/barema', $criarbarema->export_for_template($this));

        return $returnoformedit;
    }

    public function render_avaliador_lista(avaliador_lista $avaliador_lista) {
        $returnavaliadores = $this->render_from_template('block_eva_barema_afastamento/avaliador_lista', $avaliador_lista->export_for_template($this));

        return $returnavaliadores;
    }

    public function render_criar_modelo_afastamento(criar_modelo_afastamento $criar_modelo_afastamento) {
        $criarmodeloafastamento = $this->render_from_template('block_eva_barema_afastamento/criar_modelo_afastamento', $criar_modelo_afastamento->export_for_template($this));

        return $criarmodeloafastamento;
    }

    public function render_gerenciar_espelho_pdf(gerenciar_espelho_pdf $avaliador_espelho) {
        $avaliadorespelho = $this->render_from_template('block_eva_barema_afastamento/avaliador_espelho_pdf', $avaliador_espelho->export_for_template($this));
        return $avaliadorespelho;
    }

public function render_gerenciar_avaliacao(gerenciar_avaliacao $gerenciar_avaliacao) {
        $gerenciaravaliacao = $this->render_from_template('block_eva_barema_afastamento/gerenciar_avaliacao', $gerenciar_avaliacao->export_for_template($this));

        return $gerenciaravaliacao;
    }

    public function render_admin_avaliacao(admin_avaliacao $admin_avaliacao) {
        $adminavaliacao = $this->render_from_template('block_eva_barema_afastamento/admin_avaliacao', $admin_avaliacao->export_for_template($this));

        return $adminavaliacao;
    }

}