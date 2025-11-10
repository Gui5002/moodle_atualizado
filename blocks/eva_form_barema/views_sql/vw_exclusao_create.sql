CREATE OR REPLACE VIEW vw_autocomplete_user AS
SELECT id, concat(firstname,' ',lastname) AS fullname, email
FROM mdl_user
WHERE confirmed = 1
  AND deleted = 0
  AND id > 1
GROUP BY fullname ORDER BY fullname;

CREATE OR REPLACE VIEW vw_relatorio_barema AS
SELECT cao.id, b.nome_modelo as barema, c.fullname as curso,
       q.`name` as atividade, dorvw.fullname as avaliador, caovw.fullname as aluno, dor.barema_modelo as modelo,
       cao.data_avaliacao as `data`, cao.nt_avaliador, cao.`status`as status
FROM mdl_eva_barema_avaliacao cao
         INNER JOIN vw_autocomplete_user caovw ON cao.aluno_tb_user_id = caovw.id
         INNER JOIN mdl_eva_barema_avaliador dor  ON cao.tb_avaliador_id = dor.id
         INNER JOIN vw_autocomplete_user dorvw ON dor.avaliador_tb_user_id = dorvw.id
         INNER JOIN mdl_eva_barema b ON dor.tb_barema_id = b.id
         INNER JOIN mdl_quiz q ON dor.tb_atividade_id = q.id
         INNER JOIN mdl_course c ON q.course = c.id
         WHERE cao.flag = 0
ORDER BY cao.id;