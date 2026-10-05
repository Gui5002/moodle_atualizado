<?php
defined('MOODLE_INTERNAL') || die();

/**
 * Busca as cargas horárias dos cursos informados na tabela customizada.
 * 
 * @param array $course_ids Array com os IDs dos cursos.
 * @return array Array associativo [courseid => workload]
 */
function block_eva_course_promotion_get_workloads(array $course_ids) {
    global $DB;
    
    $course_workloads = [];
    if (empty($course_ids)) {
        return $course_workloads;
    }

    list($in_sql, $params) = $DB->get_in_or_equal($course_ids);
    $sql = "SELECT courseid, workload FROM {eva_course_workload} WHERE courseid $in_sql";
    $registros = $DB->get_records_sql($sql, $params);

    foreach ($registros as $reg) {
        $course_workloads[$reg->courseid] = $reg->workload;
    }

    return $course_workloads;
}

/**
 * Busca os dados dos cursos visíveis no banco de dados.
 * 
 * @param array $course_ids Array com os IDs dos cursos.
 * @return array Array de objetos dos cursos.
 */
function block_eva_course_promotion_get_courses(array $course_ids) {
    global $DB;

    if (empty($course_ids)) {
        return [];
    }

    list($in_sql, $params) = $DB->get_in_or_equal($course_ids);
    $sql = "SELECT id, fullname, shortname, summary 
            FROM {course} 
            WHERE id $in_sql AND visible = 1";
            
    return $DB->get_records_sql($sql, $params);
}