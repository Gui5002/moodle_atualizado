<?php
// Este script é chamado automaticamente ao desinstalar o bloco.
defined('MOODLE_INTERNAL') || die();

/**
 * Função executada durante a desinstalação do bloco eva_form_barema_controll.
 *
 * @return bool Sucesso da desinstalação
 */
function xmldb_block_eva_form_barema_controll_uninstall()
{
    global $DB;
    return true;
}
