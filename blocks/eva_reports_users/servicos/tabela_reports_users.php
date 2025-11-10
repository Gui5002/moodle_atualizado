<?php

defined('MOODLE_INTERNAL') || die();

class tabela_reports_users
{
    public function get_cursos_user()
    {
        global $DB,$USER;



        $sql = "SELECT 
					vw.id,
					vw.user_id, 
					vw.course_id, 
					vw.nome_curso, 
					vw.carga_horaria, 
					vw.progresso,
					ROUND(AVG(gg.finalgrade)) AS media_nota,
					MAX(ev.code) AS codigo_ev,
					MAX(sc.code) AS codigo_sc
				FROM 
					vw_courses_per_user vw
				JOIN 
					mdl_grade_items gi ON vw.course_id = gi.courseid
				LEFT JOIN 
					mdl_grade_grades gg ON gi.id = gg.itemid AND gg.userid = vw.user_id
				LEFT JOIN 
					mdl_evadeclaration_issues ev ON ev.userid = vw.user_id AND ev.coursename = vw.nome_curso
				LEFT JOIN 
					mdl_simplecertificate_issues sc ON sc.userid = vw.user_id AND sc.coursename = vw.nome_curso
				WHERE
					vw.user_id = $USER->id
				GROUP BY
					vw.id,
					vw.user_id, 
					vw.course_id, 
					vw.nome_curso, 
					vw.carga_horaria, 
					vw.progresso;";

        $rs = $DB->get_records_sql($sql);

        return $rs;
    }

}