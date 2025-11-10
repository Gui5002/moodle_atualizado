<?php
namespace block_eva_catalogo\output;

defined('MOODLE_INTERNAL') || die();

use plugin_renderer_base;
use renderable;
use stdClass;
use context_course;
use moodle_url;

class renderer extends plugin_renderer_base implements renderable {

    public function render_catalogo_page() {
        global $DB, $PAGE;

        $nome = optional_param('nome', '', PARAM_TEXT);
        $ch = optional_param_array('ch', [], PARAM_INT);
        $page = optional_param('page', 1, PARAM_INT);

        $limit = 12;
        $offset = ($page - 1) * $limit;

        $filtersql = " WHERE c.id != 1 AND c.visible = 1 ";
        $filterparams = [];

        // Cursos com campo personalizado catalogo = 1
        $filtersql .= " AND c.id IN (
            SELECT cd.instanceid
            FROM {customfield_data} cd
            JOIN {customfield_field} cf ON cf.id = cd.fieldid
            WHERE cf.shortname = 'catalogo' AND cd.value = '1'
        )";

        // 🔎 Filtro por nome
        if (!empty($nome)) {
            $filtersql .= " AND c.fullname LIKE :nome";
            $filterparams['nome'] = "%$nome%";
        }

        // 🏷️ Filtro por carga horária (baseado na média)
        if (!empty($ch)) {
            $faixasql = [];
            foreach ($ch as $faixa) {
                switch ($faixa) {
                    case 1: $faixasql[] = "ROUND(SUM(w.tempoemmin)/COUNT(DISTINCT ue.userid)) <= 1200"; break;
                    case 2: $faixasql[] = "ROUND(SUM(w.tempoemmin)/COUNT(DISTINCT ue.userid)) BETWEEN 1201 AND 2400"; break;
                    case 3: $faixasql[] = "ROUND(SUM(w.tempoemmin)/COUNT(DISTINCT ue.userid)) BETWEEN 2401 AND 3600"; break;
                    case 4: $faixasql[] = "ROUND(SUM(w.tempoemmin)/COUNT(DISTINCT ue.userid)) > 3600"; break;
                }
            }
            if (!empty($faixasql)) {
                $havingsql = " HAVING (" . implode(" OR ", $faixasql) . ")";
            }
        }

        $sql = "
            SELECT
                c.id, c.fullname, c.summary, c.summaryformat,
                ctx.id AS contextid,
                cc.name AS category,
                CONCAT(
                    FLOOR(SUM(w.tempoemmin)/COUNT(DISTINCT ue.userid)/60), 'h ',
                    LPAD(MOD(SUM(w.tempoemmin)/COUNT(DISTINCT ue.userid), 60), 2, '0'), ' min'
                ) AS workload
            FROM {course} c
            JOIN {context} ctx ON ctx.instanceid = c.id AND ctx.contextlevel = :contextlevel
            JOIN {course_categories} cc ON cc.id = c.category
            JOIN {enrol} e ON e.courseid = c.id
            JOIN {user_enrolments} ue ON ue.enrolid = e.id
            JOIN {eva_course_workload} w ON w.courseid = c.id
            $filtersql
            GROUP BY c.id, c.fullname, c.summary, c.summaryformat, ctx.id, cc.name
            ORDER BY c.fullname ASC
            LIMIT $limit OFFSET $offset
        ";

        $filterparams['contextlevel'] = CONTEXT_COURSE;
        $registros = $DB->get_records_sql($sql, $filterparams);

        // Total de cursos para paginação
        $totalcount = $DB->count_records_sql("
            SELECT COUNT(DISTINCT c.id)
            FROM {course} c
            JOIN {customfield_data} cd ON cd.instanceid = c.id
            JOIN {customfield_field} cf ON cf.id = cd.fieldid
            WHERE cf.shortname = 'catalogo' AND cd.value = '1' AND c.visible = 1 AND c.id != 1
        ");
        $totalpages = ceil($totalcount / $limit);

        // Dados para renderização
        $cursos = [];
        foreach ($registros as $curso) {
            $image = $this->get_course_image($curso->id, $curso->contextid);
            $cursos[] = [
                'id'       => $curso->id,
                'title'    => format_string($curso->fullname),
                'summary'  => format_text($curso->summary, $curso->summaryformat),
                'category' => $curso->category,
                'workload' => $curso->workload,
                'url'      => (new moodle_url('/course/view.php', ['id' => $curso->id]))->out(false),
                'image'    => $image,
            ];
        }

        // Marcadores para checkboxes
        $ch1 = in_array(1, $ch);
        $ch2 = in_array(2, $ch);
        $ch3 = in_array(3, $ch);
        $ch4 = in_array(4, $ch);

        // Mock de filtros (dinâmico futuro)
        $temas = [
            ['id' => 'educacao', 'nome' => 'Educação'],
            ['id' => 'tecnologia', 'nome' => 'Tecnologia'],
            ['id' => 'gestao', 'nome' => 'Gestão Pública'],
        ];
        $instituicoes = [
            ['id' => 'EVG', 'nome' => 'EVG'],
            ['id' => 'IFG', 'nome' => 'IFG'],
            ['id' => 'ENAP', 'nome' => 'ENAP'],
        ];

        // Paginação para Mustache
        $pages = [];
        for ($i = 1; $i <= $totalpages; $i++) {
            $pages[] = ['num' => $i, 'current' => ($i == $page)];
        }

        // Renderizar com Mustache
        return $this->render_from_template('block_eva_catalogo/catalogo', [
            'cursos'        => $cursos,
            'temas'         => $temas,
            'instituicoes'  => $instituicoes,
            'totalpages'    => $totalpages,
            'pages'         => $pages,
            'nome'          => $nome,
            'ch1' => $ch1,
            'ch2' => $ch2,
            'ch3' => $ch3,
            'ch4' => $ch4
        ]);
    }

    private function get_course_image($courseid, $contextid) {
        global $CFG;
        $fs = get_file_storage();
        $files = $fs->get_area_files($contextid, 'course', 'summary', 0, 'itemid, filepath, filename', false);
        foreach ($files as $file) {
            $url = moodle_url::make_pluginfile_url(
                $file->get_contextid(), $file->get_component(), $file->get_filearea(),
                $file->get_itemid(), $file->get_filepath(), $file->get_filename()
            );
            return $url->out();
        }
        return $CFG->wwwroot . '/blocks/eva_catalogo/pix/default_course.png';
    }
}
