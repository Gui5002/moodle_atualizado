<?php
// This file is part of Moodle - http://moodle.org/
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Migrate vídeos from mod_url
 *
 * @package   mod_supervideo
 * @copyright 2024 Eduardo kraus (http://eduardokraus.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once('../../config.php');
(new \core\task\file_trash_cleanup_task())->execute();

$assets = [
    'uZmBB_ag5tg' => 'UNIT_2',
    'r-X16LKQll0' => 'UNIT_1_VIDEO',
    'qcxxcDGxmUM' => 'UNIT_33',
    'qD3DMgiu-po' => 'UNIT_18',
    'q8paY15w7x4' => 'UNIT_12',
    'kb-2EYi7mjM' => 'UNIT_20',
    'fR8mia1tKqI' => 'UNIT_22',
    'eiJGjg5V50w' => 'UNIT_5',
    'co-p2yBCecI' => 'UNIT_17',
    'bWrdxImLKH4' => 'UNIT_4',
    'bG7KOJsn-Hg' => 'UNIT_10',
    '_l1nQ9eNYFA' => 'UNIT_26',
    '_42tQi9C1eI' => 'UNIT_8',
    'Yw052M9XMwY' => 'UNIT_34',
    'YrIN_v_nsjQ' => 'UNIT_30',
    'Y4SM6vh8GIE' => 'UNIT_15',
    'VcV-XVpgwKE' => 'UNIT_36',
    'Ru88XTR56K4' => 'UNIT_11',
    'Ru6ZTXMQtwE' => 'UNIT_25',
    'RNuhZZX1y4A' => 'UNIT_23',
    'Qxcx0T3YCXQ' => 'UNIT_35',
    'Pr5TSqt1cYc' => 'UNIT_13',
    'O3MPkMQdtBo' => 'UNIT_16',
    'DIKDkjQCOP0' => 'UNIT_32',
    'COE4cDW1Oko' => 'UNIT_28',
    'C7R_WZwEXio' => 'UNIT_24',
    'C1pIUsYAA9Y' => 'UNIT_6',
    '6c2UQN42syA' => 'UNIT_14',
    '5kwQlZxno6I' => 'UNIT_7',
    '3fkMy66uhLA' => 'UNIT_29',
    '1usPN6Etx-c' => 'UNIT_9',
    '0rJBA70Rkfs' => 'UNIT_31',
    '0gT51UA-Tr8' => 'UNIT_19',
    'UNIT 16 NEW' => 'UNIT_16_NEW',
    'znEhDk0krtA' => 'LIVE_14_OS_TOP_20_PHRASAL_VERBS_QUE_VOCE_AINDA_NAO',
    'x7FF3-UXo6U' => 'UNIT_3_WHAT_DID_YOU_DO',
    'wYZDYzcI3uo' => 'UNIT_5_WHO_OR_WHOSE',
    'viIwq8MfXTc' => 'SBC_22_DE_OUTUBRO_DE_2023',
    'sFqyz9S4e6E' => 'UNIT_5_COMPARATIVES_LESS',
    'rKjNz8NdWUs' => 'SATURDAY_BONUS_CLASS_VERB_TO_BE',
    'pPAjBy9o54Q' => 'UNIT_1_WHATS_YOUR_FAMILY_NAME',
    'p2vq_F7ASao' => 'LIVE_7__8_ERROS_QUE_VOCE_ESTA_COMETENDO_NA_HORA_DE',
    'nwWwieEMsDM' => 'UNIT_3_MONTHS_OF_THE_YEAR',
    'nmdP1dPu7Og' => 'LIVE_8__10_GAFES_MAIS_COMETIDAS_POR_PROFISSIONAIS_',
    'mpJ3s1_je2k' => 'UNIT_3_ADDRESS',
    'm2zwmZFfRNU' => 'UNIT_6_PARTS_OF_THE_HOUSE',
    'i4_ueUR6YHU' => 'UNIT_2_HOW_OLD_ARE_YOU',
    'ghBYT07YGxg' => 'LIVE_9__5_DICAS_PRECIOSAS_DE_COMO_ACELERAR_SUA_FLU',
    'gM2cItGzWx8' => 'LIVE_13__ELEVATOR_PITCH_COMO_APRESENTAR_SUA_EMPRES',
    'fpB6PjJX8vY' => 'LIVE_3_OS_100_TERMOS_MAIS_UTILIZADOS_EM_INGLES_NO_',
    'eCsAgdQppVc' => 'SAIC_18072023',
    'azjHCvaxYIA' => 'UNIT_1_HOW_DO_YOU_SPELL_IT',
    '__fekPQlU1k' => 'LIVE_10_O_QUE_O_PROFISSIONAL_QUE_FALA_INGLES_CORPO',
    'YNYZjR9F3Tk' => 'UNIT_5_COMPARATIVES_LONG_ADJECTIVES',
    'YE5kTGouD14' => 'UNIT_3_5WS__1H',
    'XO91s0y4QEA' => 'LIVE_4_TECNICAS_E_FERRAMENTAS_DE_COMO_FAZER_UMA_BO',
    'Wi8CWz8IC0U' => 'UNIT_1_DEPARTMENTS_IN_A_COMPANY',
    'VujHKtM1G6M' => 'COMO_RESPONDER_AS_10_PERGUNTAS_MAIS_COMUNS_EM_UMA_',
    'VNokaT7zA9Y' => 'EXTRA_CLASS_ABRINDO_A_CAIXA_PRETA_PARTE_1',
    'VNgEz6ZlIHo' => 'SBC_26_DE_NOVEMBRO_DE_2022',
    'VKlcwsyG4tg' => 'UNIT_3_HOW_WAS_YOUR_WEEKEND',
    'VK-4rAPOc-I' => 'UNIT_2_DOCUMENTS_SAMPLES',
    'UEMYcUXUh00' => 'LIVE_5_PALAVRAS_EXPRESSOES_E_TERMOS_EM_INGLES_QUE_',
    'UBsko8dwFWs' => 'LIVE_12_E_SE_O_TELEFONE_TOCAR_E_FOR_EM_INGLES_O_QU',
    'TdXpPcHxcIQ' => 'UNIT_74_DOES_NO_S',
    'S2SbgYrF6Ho' => 'UNIT_2_WHAT_IS_YOUR_PHONE_NUMBER',
    'Rl39kmdIvkY' => 'UNIT_9_IN_ON_OR_AT',
    'QWbJrS_h380' => 'OS_15_PASSOS_PARA_SE_DAR_BEM_EM_FEIRAS_INTERNACION',
    'Q4GQvEI3saU' => 'SAIC_17072023',
    'PphBoMkMmEQ' => 'LIVE_15_AS_100_EXPRESSOES_DO_MEIO_CORPORATIVO_MAIS',
    'Pkxea20Q_CU' => '15_TERMOS_E_EXPRESSOES_DE_CONTRATOS_QUE_TODO_PROFI',
    'OZ7qMlSBZGg' => 'UNIT_5_COMPARATIVES_EQUALITY',
    'OEyu10-wYtY' => 'UNIT_5_COMPARATIVES_SHORT_ADJECTIVES',
    'NPTlwr4bBL4' => 'UNIT_1_GREETINGS',
    'MAXR0vgoAi4' => 'LIVE_16_AS_100_EXPRESSOES_DO_MEIO_CORPORATIVO_MAIS',
    'LZ_Fkd0l9mQ' => 'SBC_30092023_SIMPLE_PRESENT',
    'KXRtjGx8x0M' => 'OS_15_TERMOS_BANCARIOS_BASICOS_QUE_OS_PROFISSIONAI',
    'JnJ4g5245O8' => 'SBC_PRESENT_PERFECT_071023',
    'J87UmsQZ1J4' => 'LIVE_6__OS_20_DOCUMENTOS_MAIS_USADOS_TODOS_OS_DIAS',
    'IsyjqZ1FiCc' => 'SBC_05_DE_NOVEMBRO_DE_2022',
    'Ho5AB0wUyj4' => 'UNIT_6_PREPOSITIONS_OF_PLACE',
    'GtQQoEExGos' => 'UNIT_8_NEGOTIATION_TERMS',
    'GeZSdybDc8E' => 'UNIT_2_WHAT_IS_YOUR_EMAIL',
    'Feo81pQZWCo' => 'UNIT_7_WHAT_KIND_OF_MUSIC_DO_YOU_LIKE',
    'EOgyxGuomWk' => 'LIVE_1_COMO_STARTAR_QUALQUER_CONVERSA_CORPORATIVA_',
    'DYUmuN-RNOQ' => 'SATURDAY_BONUS_CLASS_23092023_PRONOUNS',
    'CmGwZpMhais' => 'UNIT_7_I_LIKE_HE_LIKES_YOU_LIKE',
    'BjCAcHWifm0' => 'SATURDAY_BONUS_CLASS_251123',
    'Beoi8QFmmjk' => 'UNIT_4_ARE_YOU_BORED_OR_BORING',
    'BcYyh771QYI' => 'SBC_29_DE_OUTUBRO_DE_2022',
    'B4SUUgIAG9c' => 'LIVE_2_OS_100_TERMOS_MAIS_UTILIZADOS_EM_INGLES_NO_',
    'AhMN4Ok8fzY' => 'SBC_16092023_JOB_INTERVIEW',
    'AWsH10vkddM' => 'UNIT_9_TIME_TELLING',
    '9kR5-U0TSTs' => 'UNIT_75_SHE_REALLY_LIKES_ME',
    '9hAGcfH4e_c' => 'UNIT_5_MUCH_NOT_VERY',
    '8x7gD2AzrNw' => 'LIVE_11_15_TERMOS_E_EXPRESSOES_IMPACTANTES_PARA_US',
    '8mM6aLGkHuw' => 'UNIT_4_VERB_TO_BE',
    '7y9Ljwu1cCc' => 'SBC_15_DE_OUTUBRO_DE_2022',
    '6z-qeEWuC3c' => 'SBC_12_DE_NOVEMBRO_DE_2022',
    '6UopdLHZHfI' => 'SBC_19_DE_NOVEMBRO_DE_2022',
    '5Ul4xmUoPeY' => 'UNIT_3_WHAT_WILL_YOU_DO',
    '5OZzo6RCveU' => 'UNIT_2_IT_IS_MATH_TIME',
    '5K831WiQ3gI' => 'UNIT_7_DOES_HE_WORK_HERE',
    '5BWMUhkipC4' => 'SBC_11_11_23_BASIC_ONLINE_MEETINGS_INTERACTIONS',
    '4KRujwWsvoU' => 'UNIT_4_COUNTRIES_AND_NATIONALITIES',
    '4HIn0PGvq6s' => 'UNIT_1_SAYING_GOODBYE',
    '4Bx93_D8LJQ' => 'UNIT_2_THIS_AND_THAT',
    '320EYi4Aeak' => 'UNIT_6_THERE_IS_AND_THERE_ARE',
    '2OlPqmgMdRM' => 'SBC_19082023_THERE_BE',
    '1yrkrFAhOUA' => 'UNIT_8_HOW_MUCH_IS_IT',
    '1KTE8Rklb8g' => 'SBC_211023_THE_ALPHABET',
    '0GjbuCXGEMM' => 'UNIT_1_JOBS_IN_A_COMPANY',
    "GDYYkDKZPbQ" => 'UNIT_3_VIDEO_1',
    "nPKX76JylvQ" => 'UNIT_3_VIDEO_2',
    "bxXH1Orm00g" => 'UNIT_21',
    "v7ub4WYuvU4" => 'UNIT_27',
    "OaUZ6agpoK4" => 'LIVE_1_COMO_STARTAR_QUALQUER_CONVERSA_CORPORATIVA_'
];

require_login();
$context = context_system::instance();
require_capability('moodle/site:config', $context);
session_write_close();

$moduleurl = $DB->get_record('modules', ['name' => 'url']);
if (!$moduleurl) {
    die("Você não tem o MOD_URL instalado");
}
$modulesupervideo = $DB->get_record('modules', ['name' => 'supervideo']);

$urls = $DB->get_records_sql("SELECT * FROM ead_url WHERE externalurl LIKE '%youtu%'");
foreach ($urls as $url) {

    echo "<h4>URL externalurl: {$url->externalurl}</h4>";


    preg_match('/(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user|shorts)\/))([^\?&\"\'>]+)/', $url->externalurl, $video);
    if (isset($video[1])) {
        $tagVideo = $video[0];
        $youtubeId = $video[1];

        echo "<h4>youtubeId: {$youtubeId}</h4>";

        if (isset($assets[$youtubeId])) {
            $coursemodules = $DB->get_record("course_modules", [
                'module' => $moduleurl->id,
                'instance' => $url->id,
                'deletioninprogress' => 0
            ]);

            if ($coursemodules) {

                $identifier = $assets[$youtubeId];

                $supervideo = (object)[
                    'course' => $url->course,
                    'name' => $url->name,
                    'intro' => $url->intro,
                    'introformat' => $url->introformat,
                    'videourl' => "https://app.ottflix.com.br/Share/player/{$identifier}",
                    'playersize' => 1,
                    'showcontrols' => 1,
                    'autoplay' => 0,
                    'timemodified' => $url->timemodified,
                ];
                $supervideo->id = $DB->insert_record("supervideo", $supervideo);

                $coursemodules->module = $modulesupervideo->id;
                $coursemodules->instance = $supervideo->id;

                $DB->update_record('course_modules', $coursemodules);

                echo "<a href='https://trainingrichardsedu.aulaemvideo.com.br/MOODLE_401/mod/supervideo/view.php?id={$coursemodules->id}' target='a'>Abrir</a>";
            }

           // die();
        }
    }
}
