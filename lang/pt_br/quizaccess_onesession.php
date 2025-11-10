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
 * Strings for component 'quizaccess_onesession', language 'pt_br', version '3.9'.
 *
 * @package     quizaccess_onesession
 * @category    string
 * @copyright   1999 Martin Dougiamas and contributors
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['anothersession'] = 'Você está tentando acessar a tentativa do questionário de um computador, dispositivo ou navegador diferente daquele usado para iniciar esta tentativa. Se você acidentalmente fechou seu navegador, entre em contato com o professor.';
$string['eventattemptblocked'] = 'A tentativa do estudante de continuar o teste usando outro dispositivo foi bloqueada';
$string['eventattemptunlocked'] = 'O estudante foi autorizado a continuar a tentativa do questionário usando outro dispositivo';
$string['onesession'] = 'Bloquear conexões simultâneas';
$string['onesession:unlockattempt'] = 'Desbloquear tentativa do questionário';
$string['onesession_help'] = 'Se ativado, os usuários poderão continuar uma tentativa do questionário apenas na mesma sessão do navegador. Qualquer tentativa de abrir o mesmo questionário usando outro computador, dispositivo ou navegador será bloqueada. Isto pode ser útil para garantir que ninguém ajude um estudante abrindo a mesma tentativa em outro computador.';
$string['pluginname'] = 'Bloquear regra de acesso ao questionário de sessões simultâneas';
$string['privacy:metadata'] = 'O plugin armazena o hash da string usada para identificar a sessão do dispositivo cliente. Embora a string original contenha o endereço IP do cliente e o cabeçalho User-Agent enviado pelo navegador do cliente, o hash não permite extrair esta informação. O hash é excluído automaticamente imediatamente após o término da sessão de teste.';
$string['studentinfo'] = 'Atenção! É proibido mudar de dispositivo durante uma tentativa. Observe que após o início da tentativa do questionário, todas as conexões a este questionário usando outros computadores, dispositivos e navegadores serão bloqueadas. Não feche a janela do navegador até o final da tentativa, caso contrário você não conseguirá completar este questionário.';
$string['unlockthisattempt'] = 'Permitir que o estudante continue esta tentativa usando um dispositivo diferente';
$string['unlockthisattempt_header'] = 'Bloquear conexões simultâneas';
$string['whitelist'] = 'Redes sem verificação de IP';
$string['whitelist_desc'] = 'Esta opção tem como objetivo reduzir falsos positivos quando os usuários realizam questionários em redes móveis, onde o IP pode ser alterado durante o questionário. Não é necessário na maioria das situações. Você pode fornecer uma lista de sub-redes separadas por vírgulas (por exemplo, 88.0.0.0/8, 77.77.0.0/16). Se um endereço IP estiver nessa rede, ele não será verificado. Para desabilitar totalmente a verificação de IP, você pode definir o valor como 0.0.0.0/0.';
