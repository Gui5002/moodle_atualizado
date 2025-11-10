<?php

namespace block_eva_reports_users\output;

defined('MOODLE_INTERNAL') || die();

use renderable;
use renderer_base;
use templatable;

class reports_users implements renderable, templatable {
    /**
     * Export this data so it can be used as the context for a mustache template.
     * @param renderer_base $output
     * @return \stdClass
     */
    public function export_for_template(renderer_base $output) {
        global $CFG, $PAGE, $DB, $USER;

        require_once $CFG->libdir . '/filelib.php';

        $id = optional_param('id', null, PARAM_INT);

        $data = new \stdClass();
        $data->wwwroot = $CFG->wwwroot;

        $sql_user = "SELECT CONCAT(firstname, ' ', lastname) AS full_name, ds_cargo, lotacao, cpf FROM {user} WHERE id = :userid";
        $infoUser = $DB->get_record_sql($sql_user, ['userid' => $USER->id]);

        $sql_ctotal = "SELECT COUNT(DISTINCT e.courseid) as total
                        FROM {user_enrolments} ue
                        JOIN {enrol} e ON ue.enrolid = e.id
                       WHERE ue.userid = :userid";
        $cursosTotal = $DB->get_record_sql($sql_ctotal, ['userid' => $USER->id]);

        $sql_htotal = "SELECT CONCAT(
                            FLOOR(SUM(total) / 60), ' hrs ',
                            LPAD(SUM(total) % 60, 2, '0'), ' min') AS total
                       FROM (
                            SELECT COALESCE(SUM(workload_minutes), 480) AS total
                              FROM (
                                   SELECT DISTINCT e.courseid AS enrolid,
                                          CASE
                                            WHEN ew.workload IS NULL THEN 480
                                            ELSE (CAST(SUBSTRING(ew.workload, 1, 2) AS SIGNED) * 60) +
                                                 CAST(SUBSTRING(ew.workload, 7, 2) AS SIGNED)
                                          END AS workload_minutes
                                     FROM {user_enrolments} ue
                                     JOIN {enrol} e ON ue.enrolid = e.id
                                LEFT JOIN {eva_course_workload} ew ON e.courseid = ew.courseid
                                    WHERE ue.userid = :userid
                              ) AS subquery
                         ) AS result";
        $horaTotal = $DB->get_record_sql($sql_htotal, ['userid' => $USER->id]);

        $text = '<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />';

        if (empty($id)) {
            $text .= '<div class="alert alert-warning" role="alert">Nenhum código de relatório foi encontrado.</div>';
        } else if ($id == 1) {
            $text .= '<input id="idReport" name="idReport" value="' . $id . '" type="hidden">
                      <button id="btn-pdf" class="btn" style="width: auto">PDF</button>
                      <div class="card sombreamento">
                        <div id="pdf" class="card-body" style="padding-top: 20px!important;">
                          <div class="row">
                            <div class="col-12 table-responsive">
                              <table class="table table-hover table-bordered" style="font-size: 1rem; width: 100%;" id="tab_sug">
                                <thead>
                                  <tr>
                                    <td colspan="6">
                                      <h3 class="text-center mb-2"><strong>HISTÓRICO GERAL</strong></h3>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td colspan="6">
                                      <div class="border bg-light text-center py-2"><h4><strong>DADOS DO ALUNO</strong></h4></div>
                                      <div class="row m-2">
                                        <div class="col-md-6">
                                          <p><b>Nome:</b> ' . $infoUser->full_name . '</p>
                                          <p><b>CPF:</b> ' . $infoUser->cpf . '</p>
                                        </div>
                                        <div class="col-md-6">
                                          <p><b>Cargo:</b> ' . $infoUser->ds_cargo . '</p>
                                          <p><b>Lotacão:</b> ' . $infoUser->lotacao . '</p>
                                        </div>
                                      </div>
                                      <div class="border bg-light text-center py-2"><h4><strong>DADOS DOS CURSOS</strong></h4></div>
                                      <div class="row m-2">
                                        <div class="col-md-6">
                                          <p><b>Cursos Inscritos:</b> ' . $cursosTotal->total . '</p>
                                          <p><b>Cursos Concluídos:</b> <span id="concluidos">Calculando<img width="15" src="' . $CFG->wwwroot . '/blocks/eva_reports/img/load.gif"></span></p>
                                        </div>
                                        <div class="col-md-6">
                                          <p><b>Carga Horária Total:</b> ' . (($cursosTotal->total == 0) ? '0 hrs 00 min' : $horaTotal->total) . '</p>
                                          <p><b>Carga Horária Concluída:</b> <span id="totalConcluido">Calculando<img width="15" src="' . $CFG->wwwroot . '/blocks/eva_reports/img/load.gif"></span></p>
                                        </div>
                                      </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <th>Curso</th>
                                    <th>Carga Horária</th>
                                    <th>% Conclusão</th>
                                    <th>Nota</th>
                                    <th>Menção</th>
                                    <th>Comprovantes</th>
                                  </tr>
                                </thead>
                                <tbody></tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>';
        } else {
            $text .= '<div class="alert alert-warning" role="alert">Nenhum código de relatório foi encontrado.</div>';
        }

        $data->text = $text;
        return $data;
    }
}
