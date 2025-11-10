<?php
namespace block_eva_catalogo\output;

defined('MOODLE_INTERNAL') || die();

use context_course;
use moodle_url;
use plugin_renderer_base;

class renderer extends plugin_renderer_base {
    public function render_catalogo_page($instanceid = 0) {
        global $DB, $CFG;

        $nome           = optional_param('nome', '', PARAM_TEXT);
        $carga          = optional_param('carga_horaria', '', PARAM_RAW);
        $tagsfilter     = optional_param_array('tags', [], PARAM_TEXT);
        $categoryfilter = optional_param_array('category', [], PARAM_PATH);
        $datainicio     = optional_param('datainicio', '', PARAM_RAW);
        $datafim        = optional_param('datafim', '', PARAM_RAW);
        $page           = optional_param('page', 1, PARAM_INT);

        $sql = "
            WITH RECURSIVE cat_tree AS (
                SELECT id, parent, name, CAST(name AS CHAR(1000)) AS fullpath
                FROM {course_categories} WHERE parent = 0
                UNION ALL
                SELECT c.id, c.parent, c.name, CONCAT(t.fullpath, '/', c.name)
                FROM {course_categories} c INNER JOIN cat_tree t ON c.parent = t.id
            )
            SELECT
                c.id, c.fullname, c.summary, c.summaryformat, ctx.id AS contextid,
                ct.fullpath AS categorypath,
                ROUND(SUM(w.tempoemmin) / COUNT(DISTINCT ue.userid)) AS total_minutos,
                CONCAT(
                    FLOOR(SUM(w.tempoemmin) / COUNT(DISTINCT ue.userid) / 60), 'h',
                    LPAD(MOD(SUM(w.tempoemmin) / COUNT(DISTINCT ue.userid), 60), 2, '0')
                ) AS carga_formatada,
                FROM_UNIXTIME(c.startdate, '%Y-%m-%d') AS data_inicio,
                COALESCE(GROUP_CONCAT(DISTINCT t.name ORDER BY t.name SEPARATOR ','), '') AS tags
            FROM {course} c
            JOIN {context} ctx ON ctx.instanceid = c.id AND ctx.contextlevel = :ctxcourse
            JOIN {course_categories} cc ON cc.id = c.category
            JOIN cat_tree ct ON ct.id = cc.id
            LEFT JOIN {enrol} e ON e.courseid = c.id
            LEFT JOIN {user_enrolments} ue ON ue.enrolid = e.id
            LEFT JOIN {eva_course_workload} w ON w.courseid = c.id
            LEFT JOIN {tag_instance} ti ON ti.itemid = c.id AND ti.component = 'core' AND ti.itemtype = 'course'
            LEFT JOIN {tag} t ON t.id = ti.tagid
            WHERE c.id != 1 AND c.visible = 1
            GROUP BY c.id, c.fullname, c.summary, c.summaryformat, ctx.id, ct.fullpath, c.startdate
            ORDER BY c.fullname ASC
        ";
        $sql_params = ['ctxcourse' => CONTEXT_COURSE];
        $all_courses_from_db = $DB->get_records_sql($sql, $sql_params);

        $allItemsForJs = [];
        foreach ($all_courses_from_db as $curso) {
            $allItemsForJs[] = [
                'id'            => $curso->id,
                'title'         => $curso->fullname,
                'image'         => $this->get_course_image($curso),
                'courseurl'     => (new moodle_url('/course/view.php', ['id' => $curso->id]))->out(false),
                'url'           => (new moodle_url('/enrol/index.php', ['id' => $curso->id]))->out(false),
                'workload'      => $curso->carga_formatada,
                'raw_workload'  => (int)$curso->total_minutos,
                'tags_array'    => array_filter(array_map('trim', explode(',', $curso->tags ?? ''))),
                'category'      => $curso->categorypath,
                'startdate'     => $curso->data_inicio
            ];
        }

        $filtered_courses = array_filter($all_courses_from_db, function($course) use ($nome, $carga, $tagsfilter, $categoryfilter, $datainicio, $datafim) {
            if ($nome && stripos($course->fullname, $nome) === false) return false;
            if ($datainicio && $course->data_inicio < $datainicio) return false;
            if ($datafim && $course->data_inicio > $datafim) return false;
            if ($carga) {
                $carga_val = (int)$carga;
                if ($carga_val === 101) { if ((int)$course->total_minutos <= 6000) return false; }
                else { if ((int)$course->total_minutos > ($carga_val * 60)) return false; }
            }
            if (!empty($categoryfilter)) {
                $match = false;
                foreach ($categoryfilter as $cat_path) { if (strpos($course->categorypath, $cat_path) === 0) { $match = true; break; } }
                if (!$match) return false;
            }
            if (!empty($tagsfilter)) {
                if (empty(array_intersect($tagsfilter, array_filter(array_map('trim', explode(',', $course->tags ?? '')))))) return false;
            }
            return true;
        });
        
        $totalcursos = count($filtered_courses);
        $limit       = 12;
        $offset      = ($page - 1) * $limit;
        $pagedcursos = array_slice($filtered_courses, $offset, $limit, true);

        $items = [];
        foreach ($pagedcursos as $curso) {
            $items[] = [
                'id' => $curso->id, 'title' => $curso->fullname, 'summary' => format_text($curso->summary, $curso->summaryformat),
                'workload' => $curso->carga_formatada, 'tags_array' => array_filter(array_map('trim', explode(',', $curso->tags ?? ''))),
                'image' => $this->get_course_image($curso), 'url' => (new moodle_url('/enrol/index.php', ['id' => $curso->id]))->out(false),
                'courseurl' => (new moodle_url('/course/view.php', ['id' => $curso->id]))->out(false)
            ];
        }

        $cargas_int = $carga ? (int)$carga : 0;
        $cargas = [
            ['value'=>'', 'label'=>'Todas', 'checked'=>($cargas_int == 0)], ['value'=>'10', 'label'=>'Até 10h', 'checked'=>($cargas_int == 10)],
            ['value'=>'20', 'label'=>'Até 20h', 'checked'=>($cargas_int == 20)], ['value'=>'40', 'label'=>'Até 40h', 'checked'=>($cargas_int == 40)],
            ['value'=>'60', 'label'=>'Até 60h', 'checked'=>($cargas_int == 60)], ['value'=>'100', 'label'=>'Até 100h', 'checked'=>($cargas_int == 100)],
            ['value'=>'101', 'label'=>'Acima de 100h', 'checked'=>($cargas_int == 101)],
        ];
        $sqlcat = "WITH RECURSIVE cat_tree AS ( SELECT id, parent, name, id as topparent, 0 as nivel, CAST(name AS CHAR(255)) AS caminho FROM {course_categories} WHERE parent = 0 UNION ALL SELECT c.id, c.parent, c.name, t.topparent, t.nivel + 1, CONCAT(t.caminho, '/', c.name) FROM {course_categories} c INNER JOIN cat_tree t ON c.parent = t.id ) SELECT id, parent, name, nivel, caminho, topparent FROM cat_tree ORDER BY topparent, caminho";
        $categorias = $DB->get_records_sql($sqlcat);
        $categorylist = [];
        foreach ($categorias as $cat) {
            if (!isset($categorylist[$cat->topparent])) { $categorylist[$cat->topparent] = ['topparent' => $cat->name, 'categories' => []]; }
            $displayName = $cat->name;
            if ($cat->nivel > 0) { $firstSeparatorPos = strpos($cat->caminho, '/'); if ($firstSeparatorPos !== false) { $displayName = substr($cat->caminho, $firstSeparatorPos + 1); } }
            $categorylist[$cat->topparent]['categories'][] = ['id' => $cat->id, 'name' => $displayName, 'value' => $cat->caminho, 'checked' => in_array($cat->caminho, $categoryfilter)];
        }
        $categorylist = array_values($categorylist);
        $tagrecords = $DB->get_records_sql("SELECT DISTINCT t.name AS rawname FROM {tag} t JOIN {tag_instance} ti ON ti.tagid = t.id WHERE ti.component = 'core' AND ti.itemtype = 'course' ORDER BY t.name ASC");
        $filtro_tags = [];
        foreach ($tagrecords as $tag) { $filtro_tags[] = ['name' => $tag->rawname, 'checked' => in_array($tag->rawname, $tagsfilter)]; }

        $data_to_render = [
            'instanceid'     => $instanceid,
            'cursos'         => $items,
            'tagslist'       => $filtro_tags,
            'categorylist'   => $categorylist,
            'datainicio'     => $datainicio,
            'datafim'        => $datafim,
            'cargas'         => $cargas,
            'nome'           => $nome,
            'totalcursos'    => $totalcursos,
            'cursos_json'    => json_encode(array_values($allItemsForJs))
        ];

        return $this->render_from_template('block_eva_catalogo/catalogo', $data_to_render);
    }

    private function get_course_image($course_data_object) {
        global $CFG;
        try {
            $course_element = new \core_course_list_element($course_data_object);
            $files = $course_element->get_course_overviewfiles();
            if ($files) {
                $file = reset($files);
                $url = \file_encode_url( "$CFG->wwwroot/pluginfile.php", '/' . $file->get_contextid() . '/' . $file->get_component() . '/' . $file->get_filearea() . $file->get_filepath() . $file->get_filename(), false );
                return $url;
            }
        } catch (\Exception $e) {}
        return $CFG->wwwroot . '/blocks/eva_catalogo/pix/default_course.png';
    }
}