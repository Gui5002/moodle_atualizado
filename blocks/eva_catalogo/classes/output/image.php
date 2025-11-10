<?php

// Requisitos básicos do Moodle
require_once(__DIR__ . '/../../config.php');

// Parâmetros necessários da URL
$contextid = required_param('contextid', PARAM_INT);
$courseid  = required_param('courseid', PARAM_INT); // Adicionado para segurança extra

// Valida se o contexto realmente pertence ao curso
$context = \context_course::instance($courseid);
if ($context->id != $contextid) {
    // Se alguém tentar passar um contextid que não bate com o courseid, nega o acesso.
    send_file_not_found();
}

// O Moodle requer que o usuário esteja logado para usar o send_stored_file.
// Se quiser que funcione para visitantes, você pode precisar de uma lógica de login temporário
// ou apenas garantir que a página do catálogo exija login.
require_login();

// Obtém o arquivo da forma como você já fazia, mas agora o script faz isso, não o usuário.
$fs = get_file_storage();
$files = $fs->get_area_files($contextid, 'course', 'course_summary', 0, 'itemid, filepath, filename', false);

if ($files) {
    $file = reset($files);
    // send_stored_file é a função segura do Moodle para servir um arquivo
    // ao navegador, cuidando de headers, cache, etc.
    send_stored_file($file);
} else {
    // Se o arquivo não for encontrado, envia um erro 404.
    send_file_not_found();
}