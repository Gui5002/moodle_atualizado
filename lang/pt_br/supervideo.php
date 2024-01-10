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
 * Strings for component 'supervideo', language 'pt_br', version '3.11'.
 *
 * @package     supervideo
 * @category    string
 * @copyright   1999 Martin Dougiamas and contributors
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['autoplay'] = 'Reproduzir automaticamente';
$string['autoplay_desc'] = 'Reproduzir automaticamente o carregamento do player';
$string['completiondetail:completionpercent'] = 'Você precisa assistir {$a}% do vídeo';
$string['completionpercent'] = 'Requer porcentagem';
$string['completionpercent_error'] = 'Aceita valores de 1 a 100';
$string['completionpercent_help'] = 'Definir como concluído quando os estudantes visualizarem a porcentagem definida do vídeo. Aceita valores de 1 a 100.';
$string['completionpercent_label'] = 'Habilitar:';
$string['dnduploadlabel-mp3'] = 'Adicionar Áudio com Super Vídeo';
$string['dnduploadlabel-mp4'] = 'Adicionar Vídeo com Super Vídeo';
$string['dnduploadlabeltext'] = 'Adicionar Vídeo com Super Vídeo';
$string['idnotfound'] = 'Link não reconhecido como Youtube, Google Drive ou Vimeo';
$string['modulename'] = 'Super Video';
$string['modulename_help'] = 'Este módulo adiciona um Super Vídeo dentro do Moodle.';
$string['modulenameplural'] = 'Super Videos';
$string['no_data'] = 'Sem registros';
$string['playersize'] = 'Tamanho do vídeo';
$string['pluginadministration'] = 'Super Videos';
$string['pluginname'] = 'Super Video';
$string['privacy:metadata:supervideo_view'] = 'Salvar Mapa de Visualizações das sessões';
$string['privacy:metadata:supervideo_view:cm_id'] = 'ID do módulo sendo salvo';
$string['privacy:metadata:supervideo_view:currenttime'] = 'Tempo atual do player salvo se o estudante visualizar o vídeo novamente';
$string['privacy:metadata:supervideo_view:duration'] = 'Duração total do vídeo';
$string['privacy:metadata:supervideo_view:map'] = 'Mapa de Visualização JSON';
$string['privacy:metadata:supervideo_view:mapa'] = '';
$string['privacy:metadata:supervideo_view:percent'] = 'Porcentagem do mapa assistido. De 0 a 100';
$string['privacy:metadata:supervideo_view:timecreated'] = 'Data e hora de criação do registro';
$string['privacy:metadata:supervideo_view:timemodified'] = 'Data e hora da última modificação do registro';
$string['privacy:metadata:supervideo_view:user_id'] = 'ID de usuário';
$string['report'] = 'Visualizar relatório';
$string['report_all'] = 'Todas as visualizações deste estudante';
$string['report_assistiu'] = 'Assistido em';
$string['report_comecou'] = 'Quando comecei a assistir';
$string['report_download_title'] = 'Exibições de vídeo do plugin de super vídeo';
$string['report_duracao'] = 'Duração do vídeo';
$string['report_email'] = 'Email';
$string['report_filename'] = 'Visualização do vídeo do Super Video Plugin - {$a}';
$string['report_filename_geral'] = 'Geral';
$string['report_mapa'] = 'Visualizar Mapa';
$string['report_nome'] = 'Nome Completo';
$string['report_porcentagem'] = 'Porcentagem assistida';
$string['report_tempo'] = 'Tempo assistido';
$string['report_terminou'] = 'Terminei de assistir quando';
$string['report_title'] = 'Relatório';
$string['report_userid'] = 'ID do Usuário';
$string['report_visualizacoes'] = 'Visualizações';
$string['settings_obrigatorio_desmarcado'] = 'Será desabilitado para todos e não há como editá-lo no FORM';
$string['settings_obrigatorio_marcado'] = 'Será habilitado para todos e não há como editá-lo no FORM';
$string['settings_opcional_desmarcado'] = 'O FORM aparecerá desativado e o professor poderá ativar ou desativar';
$string['settings_opcional_marcado'] = 'No FORM aparecerá ativado e o professor poderá ativá-lo ou desativá-lo';
$string['seu_mapa_ir_para'] = 'Ir para {$a}';
$string['seu_mapa_view'] = 'Seu mapa de visualização:';
$string['showcontrols'] = 'Controles';
$string['showcontrols_desc'] = 'Mostrar os controles do player';
$string['showmapa'] = 'Mostrar mapa';
$string['showmapa_desc'] = 'Se marcado, mostre o mapa após o player de vídeo!';
$string['supervideo:addinstance'] = 'Crie novas atividades com Super Video';
$string['supervideo:view'] = 'Ver e interajir com o Super Video';
$string['videofile'] = 'Ou selecione um arquivo MP3 ou MP4';
$string['videofile_help'] = 'Você pode fazer upload de um arquivo MP3 ou MP4, hospedá-lo no MoodleData e exibi-lo no Super Video player';
$string['videourl'] = 'Youtube, Vimeo, Google Drive, link externo com extensão MP4/MP3 ou arquivo MP4/MP3';
$string['videourl_error'] = 'URL do Super Vídeo';
$string['videourl_help'] = '<h4>Youtube</h4>
<div>Adicione um URL do Youtube que você usou. deseja adicionar ao curso:</div>
<div><strong>Ex:</strong> https://www.youtube.com/watch?v=SNhUMChfolc</div>
<div><strong>Ex:</strong> https://youtu.be/kwjhXQUpyvA</div>
<h4>Google Drive</h4>
<div>No Google Drive, clique em compartilhar vídeo e definir permissões e cole o link aqui.</div>
<h4>Vimeo</h4>
<div>Adicione um URL do Vimeo que você usou. deseja adicionar ao curso:</div>
<div><strong>Ex:</strong> https://vimeo.com/300138942</div>
<h4>Vídeo ou áudio externo</h4>
<div>Adicione o URL de um vídeo hospedado em seu próprio servidor:</div>
<div><strong>Ex:</strong> https://host.com.br/file/video.mp4</div>
<div><strong>Ex:</strong> https://host.com.br/file/video.mp3</div>';
