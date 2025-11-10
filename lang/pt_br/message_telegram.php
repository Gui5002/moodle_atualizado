<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Strings for component 'message_telegram', language 'pt_br', version '3.9'.
 *
 * @package     message_telegram
 * @category    string
 * @copyright   1999 Martin Dougiamas and contributors
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['configsitebotname'] = 'Isso será preenchido automaticamente quando você salvar o token do bot.';
$string['configsitebottoken'] = 'Digite aqui o token do bot do site fornecido pelo Botfather.';
$string['configsitebotusername'] = 'Isso será preenchido automaticamente quando você salvar o token do bot.';
$string['connectinstructions'] = 'Depois de clicar no link abaixo, será necessário permitir que o link seja aberto no Telegram com
sua conta do Telegram. No Telegram, clique no botão "Iniciar" no chat "{$a}" que abrirá para conectar sua conta ao Moodle.
Depois de concluído, volte para esta página e clique em "Salvar alterações". Documentação completa
<a href="https://docs.moodle.org/en/Telegram_message_processor#Configuring_user_preferences" target="_blank">aqui</a>.';
$string['connectme'] = 'Conectar minha conta ao Telegram.';
$string['notconfigured'] = 'O servidor do Telegram não foi configurado, portanto, mensagens do Telegram não podem ser enviadas';
$string['pluginname'] = 'Telegram';
$string['removetelegram'] = 'Remover conexão do Telegram';
$string['requirehttps'] = 'O site deve usar HTTPS para a função de webhook do Telegram.';
$string['setupinstructions'] = 'Crie um novo bot do Telegram usando o Botfather. Clique no link Botfather abaixo e abra no Telegram.
Use o comando "/newbot" no Telegram para começar a criar o bot. Você precisará especificar um nome para o bot, por exemplo, "{$a->name}", e um
nome de usuário exclusivo para o bot, por exemplo, "{$a->username}". Documentação completa
<a href="https://docs.moodle.org/en/Telegram_message_processor" target="_blank">aqui</a>.';
$string['setwebhook'] = 'Configurar webhook do Telegram';
$string['sitebotname'] = 'Nome do bot para o site';
$string['sitebottoken'] = 'Token do bot para o site';
$string['sitebottokennotsetup'] = 'O token do bot para o site deve ser especificado nas configurações do plugin.';
$string['sitebotusername'] = 'Nome de usuário do bot para o site';
$string['telegrambottoken'] = 'Token do bot do Telegram';
$string['telegramchatid'] = 'ID do chat do Telegram';
