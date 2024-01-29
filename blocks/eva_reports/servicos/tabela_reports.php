<?php

defined('MOODLE_INTERNAL') || die();

class tabela_reports
{
    public function get_curso_categoria()
    {
        global $DB;

        $sql = "SELECT 
                    `id`,
                    course_id,
                    UPPER(`nome_curso`) as nome_curso,
                    UPPER(`pcategoria`) as pcategoria,
                    UPPER(`scategoria`) as scategoria,
                    UPPER(`tcategoria`) as tcategoria,
                    `carga_horaria`,
                    `inscritos`,
                    `concluintes`,
                    `data_inicio` as `inicio`,
                    `data_fim` as `fim`,
                    `total_certificados`,
                    `visivel`,
                    CASE
                        WHEN `data_inicio` > CURRENT_DATE() THEN 'PROGRAMADO'
                        WHEN `visivel` < 1 THEN 'OCULTO'
                        WHEN `data_fim` <> '1969-12-31' AND `data_fim` < CURRENT_DATE() THEN 'ENCERRADO'
                        ELSE 'EM ANDAMENTO'
                    END AS `status`
                FROM
                    vw_courses_and_categories
                ORDER BY nome_curso ASC";


        $rs = $DB->get_records_sql($sql);

        return $rs;
    }

    public function get_resultado_por_curso()
    {
        global $DB;

        $nowDate = new DateTime();
		$nowDateFormatted = $nowDate->format('Y-m-d H:i:s');

        $start = isset($_REQUEST['matriculaStart2']) ? $_REQUEST['matriculaStart2'] : "1969-12-31";
        $end   = isset($_REQUEST['matriculaEnd2'])   ? $_REQUEST['matriculaEnd2']   : $nowDateFormatted;
        if(empty($end)){
            $end = $nowDate;
        }
        $filtro2 = 'WHERE 1 = 1';

        $filtro2 .= " 
                AND COALESCE(
                    (
                        SELECT FROM_UNIXTIME(ue.timecreated) AS data_inscricao
                        FROM mdl_user_enrolments ue
                        JOIN mdl_enrol e ON ue.enrolid = e.id
                        WHERE ue.userid = vw.user_id AND e.courseid = vw.course_id
                        ORDER BY ue.timecreated DESC
                        LIMIT 1
                    ),
                    'Não registrado!'
                ) BETWEEN '$start' AND '$end'
            ";

        $sql = "SELECT 
                    id,
                    user_id,
                    nome_completo,
                    sigla_exercicio,
                    cargo,
                    course_id,
                    nome_curso,
                    UPPER(`pcategoria`) as pcategoria,
                    UPPER(`scategoria`) as scategoria,
                    UPPER(`tcategoria`) as tcategoria,
                    carga_horaria,
                    completas,
                    total,
                    progresso,
                    COALESCE(
                                (
                                    SELECT FROM_UNIXTIME(ue.timecreated) AS data_inscricao
                                    FROM mdl_user_enrolments ue
                                    JOIN mdl_enrol e ON ue.enrolid = e.id
                                    WHERE ue.userid = vw.user_id AND e.courseid = vw.course_id
                                    ORDER BY ue.timecreated DESC
                                    LIMIT 1
                                ),
                                'Não registrado!'
                            ) AS data_matricula,
                    CASE
                        WHEN `progresso` = 100 THEN 'Concluído'
                        WHEN `progresso` = 0 THEN 'N/I'
                        ELSE 'N/C'
                    END AS `status`
                FROM vw_courses_per_user AS vw $filtro2";

        $rs = $DB->get_records_sql($sql);
        return $rs;
    }

    public function get_curso_usuario()
    {
        global $DB;

        $nowDate = new DateTime();
        $nowDate = $nowDate->format('Y-m-d H:i:s');

        $start = isset($_REQUEST['matriculaStart3']) ? $_REQUEST['matriculaStart3'] : "1969-12-31";
        $end   = isset($_REQUEST['matriculaEnd3'])   ? $_REQUEST['matriculaEnd3']   : $nowDate;
        if(empty($end)){
            $end = $nowDate;
        }
        $filtro3 = 'WHERE 1 = 1';

            $filtro3 .= " 
                AND COALESCE(
                    (
                        SELECT FROM_UNIXTIME(ue.timecreated) AS data_inscricao
                        FROM mdl_user_enrolments ue
                        JOIN mdl_enrol e ON ue.enrolid = e.id
                        WHERE ue.userid = vw.user_id AND e.courseid = vw.course_id
                        ORDER BY ue.timecreated DESC
                        LIMIT 1
                    ),
                    'Não registrado!'
                ) BETWEEN '$start' AND '$end'
            ";


                $sql = "SELECT 
                    id,
                    user_id,
                    nome_completo,
                    course_id,
                    nome_curso,
                    email,
                    cidade,
                    exercicio,
                    cargo,
                    progresso,
                    COALESCE(
                        (
                            SELECT FROM_UNIXTIME(ue.timecreated) AS data_inscricao
                            FROM mdl_user_enrolments ue
                            JOIN mdl_enrol e ON ue.enrolid = e.id
                            WHERE ue.userid = vw.user_id AND e.courseid = vw.course_id
                            ORDER BY ue.timecreated DESC
                            LIMIT 1
                        ),
                        'Não registrado!'
                    ) AS data_matricula
                FROM vw_courses_per_user AS vw $filtro3";



        $rs = $DB->get_records_sql($sql);

        return $rs;
    }

    public function get_consolidado_cursos()
    {
        global $DB;

        $nowDate = new DateTime();
        $nowDate = $nowDate->format('Y-m-d H:i:s');

        $start = isset($_REQUEST['criacaoStart4']) ? $_REQUEST['criacaoStart4'] : "1969-12-31";
        $end   = isset($_REQUEST['criacaoEnd4'])   ? $_REQUEST['criacaoEnd4']   : $nowDate;
        if(empty($end)){
            $end = $nowDate;
        }
        $filtro4 = 'WHERE 1 = 1';

        $filtro4 .= " 
                AND `data_criacao` BETWEEN '$start' AND '$end'
            ";
        $sql = "SELECT 
                    `id`,
                    course_id,
                    UPPER(`nome_curso`) as nome_curso,
                    UPPER(`pcategoria`) as pcategoria,
                    UPPER(`scategoria`) as scategoria,
                    UPPER(`tcategoria`) as tcategoria,
                    `carga_horaria`,
                    `inscritos`,
                    `concluintes`,
                    `naoconcluidos`,
                    `naoiniciados`,
                    `data_criacao` as `criacao`
                FROM
                    vw_courses_and_categories
                $filtro4";
        $rs = $DB->get_records_sql($sql);
        return $rs;
    }
    public function get_consolidado_eva()
    {
        global $DB;


        $sql = "SELECT 
                    `id`,
                    course_id,
                    UPPER(`nome_curso`) as nome_curso,
                    `carga_horaria`,
                    UPPER(`pcategoria`) as pcategoria,
                    UPPER(`scategoria`) as scategoria,
                    UPPER(`tcategoria`) as tcategoria,
                    `inscritos`,
                    `concluintes`,
                    `naoconcluidos`,
                    `naoiniciados`
                FROM
                    vw_courses_and_categories";
        $rs = $DB->get_records_sql($sql);
        return $rs;
    }

	public function get_sub_categorias()
	{
		global $DB;
		$categoria = $_REQUEST['categoria'];

		$sql = "SELECT
            c.id AS categoriaid,
            UPPER(c.name) AS categoria_nome,
            c.parent AS ppid,
            pp.name AS ppname,
            sp.id AS spid,
            sp.name AS spname
        FROM 
            mdl_course_categories c
        LEFT JOIN 
            mdl_course_categories pp ON c.parent = pp.id
        LEFT JOIN 
            mdl_course_categories sp ON pp.parent = sp.id
        WHERE pp.name = '$categoria' OR sp.name = '$categoria' OR c.name = '$categoria'";

			$rs = $DB->get_records_sql($sql);
			return $rs;

	}


}