/*
 Descrição: visualiza o progresso dos curssos dos alunos
> Relatórios Administrativos
*/
create or replace view vw_all_progression as
select cm.id,
	CASE
		WHEN m.name = 'assign' THEN (
			SELECT name
			FROM mdl_assign
			WHERE id = cm.instance
		)
		WHEN m.name = 'assignment' THEN (
			SELECT name
			FROM mdl_assignment
			WHERE id = cm.instance
		)
		WHEN m.name = 'book' THEN (
			SELECT name
			FROM mdl_book
			WHERE id = cm.instance
		)
		WHEN m.name = 'chat' THEN (
			SELECT name
			FROM mdl_chat
			WHERE id = cm.instance
		)
		WHEN m.name = 'choice' THEN (
			SELECT name
			FROM mdl_choice
			WHERE id = cm.instance
		)
		WHEN m.name = 'data' THEN (
			SELECT name
			FROM mdl_data
			WHERE id = cm.instance
		)
		WHEN m.name = 'feedback' THEN (
			SELECT name
			FROM mdl_feedback
			WHERE id = cm.instance
		)
		WHEN m.name = 'folder' THEN (
			SELECT name
			FROM mdl_folder
			WHERE id = cm.instance
		)
		WHEN m.name = 'forum' THEN (
			SELECT name
			FROM mdl_forum
			WHERE id = cm.instance
		)
		WHEN m.name = 'glossary' THEN (
			SELECT name
			FROM mdl_glossary
			WHERE id = cm.instance
		)
		WHEN m.name = 'h5pactivity' THEN (
			SELECT name
			FROM mdl_h5pactivity
			WHERE id = cm.instance
		)
		WHEN m.name = 'imscp' THEN (
			SELECT name
			FROM mdl_imscp
			WHERE id = cm.instance
		)
		WHEN m.name = 'label' THEN (
			SELECT name
			FROM mdl_label
			WHERE id = cm.instance
		)
		WHEN m.name = 'lesson' THEN (
			SELECT name
			FROM mdl_lesson
			WHERE id = cm.instance
		)
		WHEN m.name = 'lti' THEN (
			SELECT name
			FROM mdl_lti
			WHERE id = cm.instance
		)
		WHEN m.name = 'page' THEN (
			SELECT name
			FROM mdl_page
			WHERE id = cm.instance
		)
		WHEN m.name = 'quiz' THEN (
			SELECT name
			FROM mdl_quiz
			WHERE id = cm.instance
		)
		WHEN m.name = 'resource' THEN (
			SELECT name
			FROM mdl_resource
			WHERE id = cm.instance
		)
		WHEN m.name = 'scorm' THEN (
			SELECT name
			FROM mdl_scorm
			WHERE id = cm.instance
		)
		WHEN m.name = 'survey' THEN (
			SELECT name
			FROM mdl_survey
			WHERE id = cm.instance
		)
		WHEN m.name = 'url' THEN (
			SELECT name
			FROM mdl_url
			WHERE id = cm.instance
		)
		WHEN m.name = 'wiki' THEN (
			SELECT name
			FROM mdl_wiki
			WHERE id = cm.instance
		)
		WHEN m.name = 'workshop' THEN (
			SELECT name
			FROM mdl_workshop
			WHERE id = cm.instance
		)
		ELSE "Other activity"
	END AS activityname,
	cm.course as courseid,
	c.fullname as coursename,
	cm.section as sectionid,
	case
		when cs.name is null then '--'
		else cs.name
	end as sectionname,
	tab.userid,
	case
		when tab.instrumento is null then '--'
		else tab.instrumento
	end as instrumento,
	case
		when tab.its_done is null then 'Não'
		else tab.its_done
	end as its_done
from mdl_course_modules cm
	join mdl_course c on c.id = cm.course
	join mdl_modules m on m.id = cm.module
	join mdl_course_sections cs on cs.id = cm.section
	and cs.course = c.id
	left join (
		select distinct u.id as userid,
			c.id as courseid,
			m.name as instrumento,
			cs.id as sectionid,
			cs.name as sectionname,
			cm.id as activityid,
			case
				when cmc.completionstate = 0 then 'Não'
				when cmc.completionstate = 1 then 'Sim'
				when cmc.completionstate = 2 then 'Sim'
				when cmc.completionstate = 3 then 'Sim com falha'
				else 'Não'
			end as its_done
		from mdl_course_modules_completion cmc
			left join mdl_user u on cmc.userid = u.id
			left join mdl_course_modules cm on cmc.coursemoduleid = cm.id
			left join mdl_course c on cm.course = c.id
			left join mdl_course_sections cs on cs.course = c.id
			and cs.id = cm.`section`
			join mdl_modules m on cm.module = m.id
		order by cm.`section`,
			m.id
	) tab on tab.courseid = cm.course
	and tab.sectionid = cm.section
	and tab.activityid = cm.id
where cm.completion > 0
	and cm.visible = 1
order by cm.section,
	cm.id asc;
/*
 Descrição: visualiza as notas dos alunos no cursos
 > Relatórios Administrativos
 */
create or replace view `vw_users_exams_grades` as
select `mgg`.`userid` as `userid`,
	`mgg`.`itemid` as `examcode`,
	upper(`mgi`.`itemname`) as `examname`,
	`mgi`.`grademax` as `grademax`,
	`mgi`.`gradepass` as `pointstopass`,
	round(`mgg`.`finalgrade`, 0) as `pointsobtained`,
	round(
		((`mgg`.`finalgrade` / `mgi`.`grademax`) * 100),
		0
	) as `finalgradepercent`,
	(
		case
			when (
				round(`mgg`.`finalgrade`, 0) >= `mgi`.`gradepass`
			) then 'Passou'
			when (round(`mgg`.`finalgrade`, 0) < `mgi`.`gradepass`) then 'Reprovou'
			when (`mgg`.`finalgrade` is null) then 'Não realizado'
		end
	) as `passorfail`,
	`mgi`.`courseid` as `courseid`
from (
		(
			(
				`mdl_grade_grades` `mgg`
				left join `mdl_grade_items` `mgi` on ((`mgi`.`id` = `mgg`.`itemid`))
			)
			left join `mdl_user` `mu` on ((`mu`.`id` = `mgg`.`userid`))
		)
		left join `mdl_course` `mc` on ((`mc`.`id` = `mgi`.`courseid`))
	)
where (
		(`mgi`.`itemtype` = 'mod')
		and (`mgi`.`gradetype` <> 3)
		and (`mgg`.`hidden` = 0)
	)
order by `mgg`.`userid`,
	`mgg`.`itemid`;
/*
 Descrição: visualiza usuário, curso, e a nota final da avaliação de aprendizagem
 > Relatórios Administrativos
 */
create or replace view `vw_consolidado_provas` as
select `vueg`.`userid` as `userid`,
	`vueg`.`courseid` as `courseid`,
	`vueg`.`examname` as `examname`,
	`vueg`.`pointsobtained` as `finalgrade`,
	row_number() over (
		partition by `vueg`.`userid`,
		`vueg`.`courseid`
		order by `vueg`.`userid`,
			`vueg`.`courseid`
	) as `exam_order`
from `vw_users_exams_grades` `vueg`;
create or replace view `vw_course_access` as
select `logs`.`userid` AS `userid`,
	count(`logs`.`userid`) AS `total_acessos`
from `mdl_logstore_standard_log` `logs`
where (
		(`logs`.`eventname` like '%course_viewed%')
		and (`logs`.`contextlevel` = 50)
	)
group by `logs`.`userid`;
/*
 Descrição: visualiza as notas das avaluações de aprendizagem dos alunos de modo geral. 
 > Relatórios Administrativos
 */
create or replace view `vw_all_users_grades` as
select vcp.userid AS userid,
	vcp.courseid AS courseid,
	max(
		(
			case
				when (vcp.exam_order = 1) then vcp.finalgrade
			end
		)
	) AS AV1,
	max(
		(
			case
				when (vcp.exam_order = 2) then vcp.finalgrade
			end
		)
	) AS AV2,
	max(
		(
			case
				when (vcp.exam_order = 3) then vcp.finalgrade
			end
		)
	) AS AV3,
	max(
		(
			case
				when (vcp.exam_order = 4) then vcp.finalgrade
			end
		)
	) AS AV4,
	max(
		(
			case
				when (vcp.exam_order = 5) then vcp.finalgrade
			end
		)
	) AS AV5,
	max(
		(
			case
				when (vcp.exam_order = 6) then vcp.finalgrade
			end
		)
	) AS AV6,
	max(
		(
			case
				when (vcp.exam_order = 7) then vcp.finalgrade
			end
		)
	) AS AV7,
	max(
		(
			case
				when (vcp.exam_order = 8) then vcp.finalgrade
			end
		)
	) AS AV8,
	max(
		(
			case
				when (vcp.exam_order = 9) then vcp.finalgrade
			end
		)
	) AS AV9,
	max(
		(
			case
				when (vcp.exam_order = 10) then vcp.finalgrade
			end
		)
	) AS AV10,
	max(
		(
			case
				when (vcp.exam_order = 11) then vcp.finalgrade
			end
		)
	) AS AV11,
	max(
		(
			case
				when (vcp.exam_order = 12) then vcp.finalgrade
			end
		)
	) AS AV12,
	max(
		(
			case
				when (vcp.exam_order = 13) then vcp.finalgrade
			end
		)
	) AS AV13,
	max(
		(
			case
				when (vcp.exam_order = 14) then vcp.finalgrade
			end
		)
	) AS AV14
from vw_consolidado_provas vcp
group by vcp.userid,
	vcp.courseid;
/*
 Descrição: visualiza os exames (notas) de um aluno em um curso
 > Relatórios Administrativos
 */
create or replace view `vw_all_user_exams_in_course` as
select distinct `vcp`.`userid` AS `userid`,
	`vcp`.`courseid` AS `courseid`,
	`t1`.`AV1` AS `AV1`,
	`t2`.`AV2` AS `AV2`,
	`t3`.`AV3` AS `AV3`,
	`t4`.`AV4` AS `AV4`,
	`t5`.`AV5` AS `AV5`
from (
		(
			(
				(
					(
						`vw_consolidado_provas` `vcp`
						left join (
							select `vcp1`.`userid` AS `userid`,
								`vcp1`.`courseid` AS `courseid`,
								`vcp1`.`finalgrade` AS `AV1`
							from `vw_consolidado_provas` `vcp1`
							where (`vcp1`.`exam_order` = 1)
						) `t1` on(
							(
								(`t1`.`userid` = `vcp`.`userid`)
								and (`t1`.`courseid` = `vcp`.`courseid`)
							)
						)
					)
					left join (
						select `vcp2`.`userid` AS `userid`,
							`vcp2`.`courseid` AS `courseid`,
							`vcp2`.`finalgrade` AS `AV2`
						from `vw_consolidado_provas` `vcp2`
						where (`vcp2`.`exam_order` = 2)
					) `t2` on(
						(
							(`t2`.`userid` = `vcp`.`userid`)
							and (`t2`.`courseid` = `vcp`.`courseid`)
						)
					)
				)
				left join (
					select `vcp3`.`userid` AS `userid`,
						`vcp3`.`courseid` AS `courseid`,
						`vcp3`.`finalgrade` AS `AV3`
					from `vw_consolidado_provas` `vcp3`
					where (`vcp3`.`exam_order` = 3)
				) `t3` on(
					(
						(`t3`.`userid` = `vcp`.`userid`)
						and (`t3`.`courseid` = `vcp`.`courseid`)
					)
				)
			)
			left join (
				select `vcp4`.`userid` AS `userid`,
					`vcp4`.`courseid` AS `courseid`,
					`vcp4`.`finalgrade` AS `AV4`
				from `vw_consolidado_provas` `vcp4`
				where (`vcp4`.`exam_order` = 4)
			) `t4` on(
				(
					(`t4`.`userid` = `vcp`.`userid`)
					and (`t4`.`courseid` = `vcp`.`courseid`)
				)
			)
		)
		left join (
			select `vcp5`.`userid` AS `userid`,
				`vcp5`.`courseid` AS `courseid`,
				`vcp5`.`finalgrade` AS `AV5`
			from `vw_consolidado_provas` `vcp5`
			where (`vcp5`.`exam_order` = 5)
		) `t5` on(
			(
				(`t5`.`userid` = `vcp`.`userid`)
				and (`t5`.`courseid` = `vcp`.`courseid`)
			)
		)
	);
/*
 Descrição: visualiza as atividades contidas em um curso. Atividades que podem ser: fórum, pesquisa, chat, quiz, atividade h5p etc.
 > Relatórios Administrativos
 */
create or replace view `vw_atividades_do_curso` as
select `cm`.`course` as `course`,
	`c`.`fullname` as `fullname`,
	`cm`.`section` as `section`,
	`cs`.`name` as `sectionname`,
	`m`.`id` as `activid`,
	(
		case
			when (`m`.`name` = 'assign') then (
				select `mdl_assign`.`name`
				from `mdl_assign`
				where (`mdl_assign`.`id` = `cm`.`instance`)
			)
			when (`m`.`name` = 'assignment') then (
				select `mdl_assignment`.`name`
				from `mdl_assignment`
				where (`mdl_assignment`.`id` = `cm`.`instance`)
			)
			when (`m`.`name` = 'book') then (
				select `mdl_book`.`name`
				from `mdl_book`
				where (`mdl_book`.`id` = `cm`.`instance`)
			)
			when (`m`.`name` = 'chat') then (
				select `mdl_chat`.`name`
				from `mdl_chat`
				where (`mdl_chat`.`id` = `cm`.`instance`)
			)
			when (`m`.`name` = 'choice') then (
				select `mdl_choice`.`name`
				from `mdl_choice`
				where (`mdl_choice`.`id` = `cm`.`instance`)
			)
			when (`m`.`name` = 'data') then (
				select `mdl_data`.`name`
				from `mdl_data`
				where (`mdl_data`.`id` = `cm`.`instance`)
			)
			when (`m`.`name` = 'feedback') then (
				select `mdl_feedback`.`name`
				from `mdl_feedback`
				where (`mdl_feedback`.`id` = `cm`.`instance`)
			)
			when (`m`.`name` = 'folder') then (
				select `mdl_folder`.`name`
				from `mdl_folder`
				where (`mdl_folder`.`id` = `cm`.`instance`)
			)
			when (`m`.`name` = 'forum') then (
				select `mdl_forum`.`name`
				from `mdl_forum`
				where (`mdl_forum`.`id` = `cm`.`instance`)
			)
			when (`m`.`name` = 'glossary') then (
				select `mdl_glossary`.`name`
				from `mdl_glossary`
				where (`mdl_glossary`.`id` = `cm`.`instance`)
			)
			when (`m`.`name` = 'h5pactivity') then (
				select `mdl_h5pactivity`.`name`
				from `mdl_h5pactivity`
				where (`mdl_h5pactivity`.`id` = `cm`.`instance`)
			)
			when (`m`.`name` = 'imscp') then (
				select `mdl_imscp`.`name`
				from `mdl_imscp`
				where (`mdl_imscp`.`id` = `cm`.`instance`)
			)
			when (`m`.`name` = 'label') then (
				select `mdl_label`.`name`
				from `mdl_label`
				where (`mdl_label`.`id` = `cm`.`instance`)
			)
			when (`m`.`name` = 'lesson') then (
				select `mdl_lesson`.`name`
				from `mdl_lesson`
				where (`mdl_lesson`.`id` = `cm`.`instance`)
			)
			when (`m`.`name` = 'lti') then (
				select `mdl_lti`.`name`
				from `mdl_lti`
				where (`mdl_lti`.`id` = `cm`.`instance`)
			)
			when (`m`.`name` = 'page') then (
				select `mdl_page`.`name`
				from `mdl_page`
				where (`mdl_page`.`id` = `cm`.`instance`)
			)
			when (`m`.`name` = 'quiz') then (
				select `mdl_quiz`.`name`
				from `mdl_quiz`
				where (`mdl_quiz`.`id` = `cm`.`instance`)
			)
			when (`m`.`name` = 'resource') then (
				select `mdl_resource`.`name`
				from `mdl_resource`
				where (`mdl_resource`.`id` = `cm`.`instance`)
			)
			when (`m`.`name` = 'scorm') then (
				select `mdl_scorm`.`name`
				from `mdl_scorm`
				where (`mdl_scorm`.`id` = `cm`.`instance`)
			)
			when (`m`.`name` = 'survey') then (
				select `mdl_survey`.`name`
				from `mdl_survey`
				where (`mdl_survey`.`id` = `cm`.`instance`)
			)
			when (`m`.`name` = 'url') then (
				select `mdl_url`.`name`
				from `mdl_url`
				where (`mdl_url`.`id` = `cm`.`instance`)
			)
			when (`m`.`name` = 'wiki') then (
				select `mdl_wiki`.`name`
				from `mdl_wiki`
				where (`mdl_wiki`.`id` = `cm`.`instance`)
			)
			when (`m`.`name` = 'workshop') then (
				select `mdl_workshop`.`name`
				from `mdl_workshop`
				where (`mdl_workshop`.`id` = `cm`.`instance`)
			)
			else 'Other activity'
		end
	) as `activityname`
from (
		(
			(
				`mdl_course_modules` `cm`
				join `mdl_course` `c` on ((`c`.`id` = `cm`.`course`))
			)
			join `mdl_modules` `m` on ((`m`.`id` = `cm`.`module`))
		)
		join `mdl_course_sections` `cs` on (
			(
				(`cs`.`id` = `cm`.`section`)
				and (`cs`.`course` = `c`.`id`)
			)
		)
	);
/*
 Descrição: visualiza as atividades de um aluno dentro de um determinado curso.
 Atividades que podem ser: fórum, pesquisa, chat, quiz, atividade h5p etc.
 > Relatórios Administrativos
 */
create or replace view `vw_atividades_user_curso` as
select row_number() over () as `id`,
	`cm`.`course` as `courseid`,
	`tab`.`userid` as `userid`,
	(
		case
			when (`tab`.`its_done` is null) then 'Não'
			else `tab`.`its_done`
		end
	) as `its_done`,
	count(`tab`.`its_done`) as `total`
from (
		(
			(
				(
					`mdl_course_modules` `cm`
					join `mdl_course` `c` on ((`c`.`id` = `cm`.`course`))
				)
				join `mdl_modules` `m` on ((`m`.`id` = `cm`.`module`))
			)
			join `mdl_course_sections` `cs` on (
				(
					(`cs`.`id` = `cm`.`section`)
					and (`cs`.`course` = `c`.`id`)
				)
			)
		)
		left join (
			select distinct `u`.`id` as `userid`,
				`c`.`id` as `courseid`,
				`m`.`name` as `instrumento`,
				`cs`.`id` as `sectionid`,
				`cs`.`name` as `sectionname`,
				`cm`.`id` as `activityid`,
				(
					case
						when (`cmc`.`completionstate` = 0) then 'Não'
						when (`cmc`.`completionstate` = 1) then 'Sim'
						when (`cmc`.`completionstate` = 2) then 'Sim'
						when (`cmc`.`completionstate` = 3) then 'Sim com falha'
						else 'Não'
					end
				) as `its_done`
			from (
					(
						(
							(
								(
									`mdl_course_modules_completion` `cmc`
									left join `mdl_user` `u` on ((`cmc`.`userid` = `u`.`id`))
								)
								left join `mdl_course_modules` `cm` on ((`cmc`.`coursemoduleid` = `cm`.`id`))
							)
							left join `mdl_course` `c` on ((`cm`.`course` = `c`.`id`))
						)
						left join `mdl_course_sections` `cs` on (
							(
								(`cs`.`course` = `c`.`id`)
								and (`cs`.`id` = `cm`.`section`)
							)
						)
					)
					join `mdl_modules` `m` on ((`cm`.`module` = `m`.`id`))
				)
		) `tab` on (
			(
				(`tab`.`courseid` = `cm`.`course`)
				and (`tab`.`sectionid` = `cm`.`section`)
				and (`tab`.`activityid` = `cm`.`id`)
			)
		)
	)
where (
		(`cm`.`completion` > 0)
		and (`cm`.`visible` = 1)
	)
group by `cm`.`course`,
	`tab`.`its_done`;
/*
 Descrição: visualiza as categorias e respecticas subcategorias dos cursos.
 > Relatórios Administrativos
 */
create or replace view `vw_category_subcategory` as
select `tab`.`co_seq` as `co_seq`,
	`tab`.`categoryid` as `categoryid`,
	`tab`.`categoryname` as `categoryname`,
	`tab`.`subcategoryid` as `subcategoryid`,
	`tab`.`subcategoryname` as `subcategoryname`,
	`tab2`.`sub_subcategoryid` as `sub_subcategoryid`,
	`tab2`.`sub_subcategoryname` as `sub_subcategoryname`
from (
		(
			select row_number() over () as `co_seq`,
				`mcc`.`id` as `categoryid`,
				`mcc`.`name` as `categoryname`,
				`mcc2`.`subcategoryid` as `subcategoryid`,
				`mcc2`.`subcategoryname` as `subcategoryname`
			from (
					`mdl_course_categories` `mcc`
					left join (
						select `mcc`.`parent` as `parent`,
							`mcc`.`id` as `subcategoryid`,
							`mcc`.`name` as `subcategoryname`
						from `mdl_course_categories` `mcc`
						where (`mcc`.`parent` <> 0)
					) `mcc2` on ((`mcc`.`id` = `mcc2`.`parent`))
				)
			where (`mcc`.`parent` = 0)
			order by `mcc`.`id`,
				`mcc2`.`subcategoryid`
		) `tab`
		left join (
			select `mcc`.`parent` as `parent`,
				`mcc`.`id` as `sub_subcategoryid`,
				`mcc`.`name` as `sub_subcategoryname`
			from `mdl_course_categories` `mcc`
			where (`mcc`.`parent` <> 0)
		) `tab2` on ((`tab2`.`parent` = `tab`.`subcategoryid`))
	);
/*
 Descrição: visualiza a categoria de um curso.
> Relatórios Administrativos
 */
create or replace view `vw_course_category` as
select `mc`.`id` as `id`,
	(
		case
			when (`cat`.`categoryid` is not null) then `cat`.`categoryid`
			when (
				(`cat`.`categoryid` is null)
				and (`sub`.`categoryid` is not null)
			) then `sub`.`categoryid`
			when (
				(`cat`.`categoryid` is null)
				and (`sub`.`categoryid` is null)
				and (`sub2`.`categoryid` is not null)
			) then `sub2`.`categoryid`
		end
	) as `categoryid`,
	(
		case
			when (`cat`.`categoryname` is not null) then `cat`.`categoryname`
			when (
				(`cat`.`categoryname` is null)
				and (`sub`.`categoryname` is not null)
			) then `sub`.`categoryname`
			when (
				(`cat`.`categoryname` is null)
				and (`sub`.`categoryname` is null)
				and (`sub2`.`categoryname` is not null)
			) then `sub2`.`categoryname`
		end
	) as `categoryname`,
	(
		case
			when (`sub`.`subcategoryid` is not null) then `sub`.`subcategoryid`
			when (
				(`sub`.`subcategoryid` is null)
				and (`sub2`.`subcategoryid` is not null)
			) then `sub2`.`subcategoryid`
		end
	) as `subcategoryid`,
	(
		case
			when (`sub`.`subcategoryname` is not null) then `sub`.`subcategoryname`
			when (
				(`sub`.`subcategoryname` is null)
				and (`sub2`.`subcategoryname` is not null)
			) then `sub2`.`subcategoryname`
		end
	) as `subcategoryname`,
	`sub2`.`sub_subcategoryid` as `sub_subcategoryid`,
	`sub2`.`sub_subcategoryname` as `sub_subcategoryname`
from (
		(
			(
				`mdl_course` `mc`
				left join (
					select `cat`.`categoryid` as `categoryid`,
						`cat`.`categoryname` as `categoryname`
					from `vw_category_subcategory` `cat`
				) `cat` on ((`cat`.`categoryid` = `mc`.`category`))
			)
			left join (
				select `sub`.`categoryid` as `categoryid`,
					`sub`.`categoryname` as `categoryname`,
					`sub`.`subcategoryid` as `subcategoryid`,
					`sub`.`subcategoryname` as `subcategoryname`
				from `vw_category_subcategory` `sub`
			) `sub` on ((`sub`.`subcategoryid` = `mc`.`category`))
		)
		left join (
			select `sub2`.`categoryid` as `categoryid`,
				`sub2`.`categoryname` as `categoryname`,
				`sub2`.`subcategoryid` as `subcategoryid`,
				`sub2`.`subcategoryname` as `subcategoryname`,
				`sub2`.`sub_subcategoryid` as `sub_subcategoryid`,
				`sub2`.`sub_subcategoryname` as `sub_subcategoryname`
			from `vw_category_subcategory` `sub2`
		) `sub2` on ((`sub2`.`sub_subcategoryid` = `mc`.`category`))
	);
/*
 Descrição: visualiza a data da matrícula de um aluno em um curso
 > Relatórios Administrativos
 */
create or replace view `vw_users_enrol_date` as
select `logs`.`relateduserid` as `relateduserid`,
	`logs`.`courseid` as `courseid`,
	(
		case
			when (`logs`.`timecreated` > 0) then min(
				date_format(from_unixtime(`logs`.`timecreated`), '%d/%m/%Y')
			)
		end
	) as `timecreated`
from `mdl_logstore_standard_log` `logs`
where (
		(`logs`.`contextlevel` = 50)
		and (
			`logs`.`eventname` like '%user_enrolment_created%'
		)
	)
group by `logs`.`relateduserid`,
	`logs`.`courseid`,
	`logs`.`timecreated`;
/*
 Descrição: visualiza o primeiro acesso dos alunos em um curso.
 > Relatórios Administrativos
 */
create or replace view `vw_user_first_course_access` as
select `logs`.`userid` as `userid`,
	`logs`.`courseid` as `courseid`,
	(
		case
			when (`logs`.`timecreated` > 0) then min(
				date_format(from_unixtime(`logs`.`timecreated`), '%d/%m/%Y')
			)
		end
	) as `data_acesso`
from `mdl_logstore_standard_log` `logs`
where (
		(`logs`.`eventname` like '%course_viewed%')
		and (`logs`.`contextlevel` = 50)
	)
group by `logs`.`userid`,
	`logs`.`courseid`,
	`logs`.`timecreated`;
/*
 Descrição: visualiza informações sobre um certificado de um aluno.
 > Relatórios Administrativos
 */
create or replace view `vw_historico_cabecalho` as
select `mu`.`id` as `userid`,
	upper(
		concat(
			trim(`mu`.`firstname`),
			' ',
			trim(`mu`.`lastname`)
		)
	) as `nome`,
	(
		case
			when (`mu`.`ds_cargo` is not null) then `mu`.`ds_cargo`
			else '-'
		end
	) as `cargo`,
	`mu`.`cpf` as `cpf`,
	(
		case
			when (`cert`.`userid` is not null) then 'EMITIDO'
			else '--'
		end
	) as `certificado`,
	(
		case
			when (`cert`.`timecreated` is null) then '--'
			else date_format(
				from_unixtime(`cert`.`timecreated`),
				'%d/%m/%Y'
			)
		end
	) as `dt_emissao_cert`,
	(
		case
			when (`mu`.`department` is null) then '--'
			when (`mu`.`department` = '') then '--'
			else `mu`.`department`
		end
	) as `lotacao`,
	(
		case
			when (`mcc`.`timecompleted` <> 0) then 'CONCLUÍDO'
			else 'CURSANDO'
		end
	) as `statusCurso`,
	`me`.`courseid` as `courseid`,
	`cur`.`fullname` as `curso`,
	`vcc`.`categoryname` as `categoryname`,
	(
		case
			when (`vcc`.`sub_subcategoryname` is not null) then concat(
				`vcc`.`subcategoryname`,
				' / ',
				`vcc`.`sub_subcategoryname`
			)
			else `vcc`.`subcategoryname`
		end
	) as `subcategoryname`,
	(
		case
			when (`cur`.`startdate` > 0) then date_format(from_unixtime(`cur`.`startdate`), '%d/%m/%Y')
			else '--'
		end
	) as `data_inicio_curso`,
	(
		case
			when (`cur`.`enddate` > 0) then date_format(from_unixtime(`cur`.`enddate`), '%d/%m/%Y')
			else '--'
		end
	) as `data_fim_curso`,
	`vued`.`timecreated` as `data_inscricao`,
	(
		case
			when (`mue`.`timestart` <> 0) then date_format(from_unixtime(`mue`.`timestart`), '%d/%m/%Y')
			else '--'
		end
	) as `data_inicio`,
	(
		case
			when (`mue`.`timeend` <> 0) then date_format(from_unixtime(`mue`.`timeend`), '%d/%m/%Y')
			else '--'
		end
	) as `data_fim`
from (
		(
			(
				(
					(
						(
							(
								(
									(
										(
											`mdl_user` `mu`
											join `mdl_user_enrolments` `mue` on ((`mue`.`userid` = `mu`.`id`))
										)
										join `mdl_enrol` `me` on ((`me`.`id` = `mue`.`enrolid`))
									)
									left join `mdl_course` `cur` on ((`cur`.`id` = `me`.`courseid`))
								)
								left join `vw_course_category` `vcc` on ((`vcc`.`id` = `cur`.`id`))
							)
							left join (
								select `msi`.`userid` as `userid`,
									`msi`.`timecreated` as `timecreated`,
									`ms`.`course` as `course`
								from (
										`mdl_simplecertificate_issues` `msi`
										left join `mdl_simplecertificate` `ms` on ((`ms`.`id` = `msi`.`certificateid`))
									)
								where (`msi`.`timedeleted` is null)
							) `cert` on (
								(
									(`cert`.`userid` = `mu`.`id`)
									and (`cert`.`course` = `me`.`courseid`)
								)
							)
						)
						left join `mdl_course_completions` `mcc` on (
							(
								(`mcc`.`userid` = `mu`.`id`)
								and (`mcc`.`course` = `me`.`courseid`)
							)
						)
					)
					left join `mdl_backup_courses` `mbc` on ((`mbc`.`courseid` = `cur`.`id`))
				)
				join `vw_users_enrol_date` `vued` on (
					(
						(`vued`.`relateduserid` = `mue`.`userid`)
						and (`vued`.`courseid` = `me`.`courseid`)
					)
				)
			)
			left join `vw_user_first_course_access` `vufca` on (
				(
					(`vufca`.`userid` = `mue`.`userid`)
					and (`me`.`courseid` = `vufca`.`courseid`)
				)
			)
		)
		left join `mdl_user_lastaccess` `mul` on (
			(
				(`mul`.`userid` = `mue`.`userid`)
				and (`me`.`courseid` = `mul`.`courseid`)
			)
		)
	);
/*
 Descrição: visualiza os usuários com papel de aluno.
 > Relatórios Administrativos
 */
create or replace view `vw_roles_students` as
select distinct `u`.`id` as `id`,
	concat(`u`.`firstname`, ' ', `u`.`lastname`) as `nome`
from (
		(
			`mdl_role_assignments` `ra`
			join `mdl_role` `r` on ((`r`.`id` = `ra`.`roleid`))
		)
		join `mdl_user` `u` on ((`u`.`id` = `ra`.`userid`))
	)
where (`r`.`id` = 5)
order by concat(`u`.`firstname`, ' ', `u`.`lastname`);
/*
 Descrição: visualiza os alunos inscritos em um determinado curso.
 > Relatórios Administrativos
 */
create or replace view `vw_students_in_course` as
select `tab`.`courseid` as `id`,
	count(`tab`.`id`) as `total_inscritos`
from (
		select `mu`.`id` as `id`,
			`mu`.`firstname` as `firstname`,
			`mu`.`lastname` as `lastname`,
			`mue`.`timestart` as `timestart`,
			`mue`.`timeend` as `timeend`,
			`me`.`courseid` as `courseid`
		from (
				(
					`mdl_user` `mu`
					join `mdl_user_enrolments` `mue` on ((`mue`.`userid` = `mu`.`id`))
				)
				join `mdl_enrol` `me` on ((`me`.`id` = `mue`.`enrolid`))
			)
	) `tab`
group by `tab`.`courseid`;
/*
 Descrição: visualiza os cursos que já foram concluídos.
> Relatórios Administrativos
 */
create or replace view `vw_user_course_complete` as
select `mcc`.`course` as `course`,
	count(`mcc`.`course`) as `total_completos`
from `mdl_course_completions` `mcc`
where (`mcc`.`timecompleted` <> 0)
group by `mcc`.`course`;
/*
 Descrição: visualiza informações sobre um aluno: nome, sobrenome, lotação, cidade, último acesso, qtd cursos completos etc.
 > Relatórios Administrativos
 */
create or replace view `vw_usuarios_geral` as
select `mu`.`id` as `id`,
	upper(
		concat(
			trim(`mu`.`firstname`),
			' ',
			trim(`mu`.`lastname`)
		)
	) as `nome`,
	(
		case
			when (`mu`.`ds_cargo` is not null) then `mu`.`ds_cargo`
			else '-'
		end
	) as `cargo`,
	(
		case
			when (`mu`.`lotacao` is null) then '-'
			when (`mu`.`lotacao` = '') then '-'
			else `mu`.`lotacao`
		end
	) as `lotacao`,
	(
		case
			when (`mr`.`shortname` is null) then '-'
			else `mr`.`shortname`
		end
	) as `perfil`,
	(
		case
			when (`mu`.`city` is null) then '-'
			when (`mu`.`city` = '') then '-'
			else `mu`.`city`
		end
	) as `cidade`,
	coalesce(`compl`.`total_completos`, 0) as `cursos_completos`,
	(
		case
			when (`mu`.`lastaccess` <> 0) then date_format(from_unixtime(`mu`.`lastaccess`), '%d/%m/%Y')
			else '-'
		end
	) as `ultimo_acesso`,
	(
		case
			when (
				(`vca`.`total_acessos` is null)
				and (`mu`.`lastaccess` <> 0)
			) then 1
			when (
				(`vca`.`total_acessos` is null)
				and (`mu`.`lastaccess` = 0)
			) then 0
			when (`vca`.`total_acessos` is not null) then `vca`.`total_acessos`
		end
	) as `total_acessos`
from (
		(
			(
				(
					`mdl_user` `mu`
					left join (
						select `mcc`.`userid` as `userid`,
							count(`mcc`.`userid`) as `total_completos`
						from `mdl_course_completions` `mcc`
						where (`mcc`.`timecompleted` <> 0)
						group by `mcc`.`userid`,
							`mcc`.`course`
					) `compl` on ((`compl`.`userid` = `mu`.`id`))
				)
				left join `vw_course_access` `vca` on ((`vca`.`userid` = `mu`.`id`))
			)
			left join `mdl_role_assignments` `mra` on ((`mra`.`userid` = `mu`.`id`))
		)
		left join `mdl_role` `mr` on ((`mr`.`id` = `mra`.`roleid`))
	)
group by `mu`.`id`,
	upper(
		concat(
			trim(`mu`.`firstname`),
			' ',
			trim(`mu`.`lastname`)
		)
	),
	`mr`.`shortname`,
	`compl`.`total_completos`;
/*
 Descrição: visualiza os certificados por curso.
 > Relatórios Administrativos
 */
create or replace view `vw_certificados_por_curso` as
select `msc`.`course` as `course`,
	count(`msc`.`course`) as `total_certi`
from (
		`mdl_simplecertificate_issues` `msi`
		left join `mdl_simplecertificate` `msc` on ((`msc`.`id` = `msi`.`certificateid`))
	)
where (`msi`.`timedeleted` is null)
group by `msc`.`course`;
/*
 Descrição: utilizada nos baremas para auto-completar o nome dos usuários ao serem digitados.
 > Barema
 */
create or replace view `vw_autocomplete_user` as
select `mdl_user`.`id` AS `id`,
	concat(`mdl_user`.`firstname`, ' ', `mdl_user`.`lastname`) AS `fullname`,
	`mdl_user`.`email` AS `email`
from `mdl_user`
where (
		(`mdl_user`.`confirmed` = 1)
		and (`mdl_user`.`deleted` = 0)
		and (`mdl_user`.`id` > 1)
	)
order by concat(`mdl_user`.`firstname`, ' ', `mdl_user`.`lastname`);
/*
 Descrição: visualiza usuários, avaliadores, notas, curso, modelo do Barema e data da avaliação para o Relatório do Barema de Pós Graduação
 > Barema de Pós Graduação
 */
create or replace view `vw_relatorio_barema` as
select `cao`.`id` AS `id`,
	`b`.`nome_modelo` AS `barema`,
	`c`.`fullname` AS `curso`,
	`q`.`name` AS `atividade`,
	`dorvw`.`fullname` AS `avaliador`,
	`caovw`.`fullname` AS `aluno`,
	`dor`.`barema_modelo` AS `modelo`,
	`cao`.`data_avaliacao` AS `data`,
	`cao`.`nt_avaliador` AS `nt_avaliador`,
	`cao`.`status` AS `status`
from (
		(
			(
				(
					(
						(
							`mdl_eva_barema_avaliacao` `cao`
							join `vw_autocomplete_user` `caovw` on((`cao`.`aluno_tb_user_id` = `caovw`.`id`))
						)
						join `mdl_eva_barema_avaliador` `dor` on((`cao`.`tb_avaliador_id` = `dor`.`id`))
					)
					join `vw_autocomplete_user` `dorvw` on((`dor`.`avaliador_tb_user_id` = `dorvw`.`id`))
				)
				join `mdl_eva_barema` `b` on((`dor`.`tb_barema_id` = `b`.`id`))
			)
			join `mdl_quiz` `q` on((`dor`.`tb_atividade_id` = `q`.`id`))
		)
		join `mdl_course` `c` on((`q`.`course` = `c`.`id`))
	)
where (`cao`.`flag` = 0)
order by `cao`.`id`;
/*
 Descrição: visualiza usuários, avaliadores, notas, curso, modelo do Barema, data da avaliação e nota do avaliador para o Relatório do Barema de Bolsas
 > Barema de Bolsas
 */
create or replace view `vw_relatorio_bolsa` as
SELECT ava.id as id,
	ant.anteprojeto,
	atr.avaliador_tb_user_id as avaliadores_id,
	vwu.fullname as avaliador,
	atr.barema_modelo,
	ava.data_avaliacao,
	ava.nt_avaliador,
	ant.notacapes
FROM mdl_eva_bolsa_avaliacao ava
	JOIN mdl_eva_bolsa_atribuicao atr ON (ava.tb_atribuicao_id = atr.id)
	JOIN vw_autocomplete_user vwu ON (atr.avaliador_tb_user_id = vwu.id)
	JOIN mdl_eva_bolsa_anteprojeto ant ON (ant.id = atr.tb_anteprojeto_id)
ORDER BY ava.id;
/*
 Descrição: utilizada nos baremas para o relatorio um vw_select_relatorio_um.
 > Relatórios Administrativos [VERIFICAR]
 */
create or replace view `vw_select_relatorio_um` as
select *,
	CASE
		WHEN tab.visible = 0 then 'CANCELADO'
		else CASE
			WHEN (tab.dta_inicio is null)
			and (tab.dta_final is null) then 'EM ANDAMENTO'
			WHEN (now() < tab.dta_inicio) then 'PROGRAMADO'
			WHEN (now() > tab.dta_final) then 'ENCERRADO'
			WHEN (now() <= tab.dta_final) then 'EM ANDAMENTO'
			WHEN (tab.dta_final is null) then 'EM ANDAMENTO'
		END
	END as status_curso
from (
		SELECT mc.id,
			upper(trim(vcc.categoryname)) as categoryname,
			CASE
				WHEN vcc.sub_subcategoryname is not null then upper(
					trim(
						concat(
							vcc.subcategoryname,
							' / ',
							vcc.sub_subcategoryname
						)
					)
				)
				ELSE upper(trim(vcc.subcategoryname))
			END AS subcategoryname,
			upper(trim(mc.fullname)) as fullname,
			mecw.workload,
			coalesce(vsic.total_inscritos, 0) AS total_inscritos,
			coalesce(vucc.total_completos, 0) AS concluidos,
			coalesce(vcpc.total_certi, 0) AS certificados,
			CASE
				WHEN mc.startdate = 0 THEN NULL
				ELSE FROM_UNIXTIME(mc.startdate)
			END AS dta_inicio,
			CASE
				WHEN mc.enddate = 0 THEN NULL
				ELSE FROM_UNIXTIME(mc.enddate)
			END AS dta_final,
			mc.visible
		FROM mdl_course mc
			LEFT JOIN vw_course_category vcc on vcc.id = mc.id
			LEFT JOIN vw_students_in_course vsic on vsic.id = mc.id
			LEFT JOIN vw_user_course_complete vucc on vucc.course = mc.id
			LEFT JOIN vw_certificados_por_curso vcpc on vcpc.course = mc.id
			LEFT JOIN mdl_eva_course_workload mecw on mecw.courseid = mc.id
	) tab;
/*
 Barema de Afastamentos - Tela do avaliador: espelho da avaliação realizada.
 > Barema de Afastamentos
 */
create or replace view `vw_relatorio_afastamento` AS
select `ava`.`id` AS `id`,
	`ant`.`anteprojeto` AS `anteprojeto`,
	`atr`.`avaliador_tb_user_id` AS `avaliadores_id`,
	`vwu`.`fullname` AS `avaliador`,
	`atr`.`barema_modelo` AS `barema_modelo`,
	`ava`.`data_avaliacao` AS `data_avaliacao`,
	`ava`.`nt_avaliador` AS `nt_avaliador`,
	`ant`.`notacapes` AS `notacapes`
from (
		(
			(
				`mdl_eva_afastamento_avaliacao` `ava`
				join `mdl_eva_afastamento_atribuicao` `atr` on((`ava`.`tb_atribuicao_id` = `atr`.`id`))
			)
			join `vw_autocomplete_user` `vwu` on((`atr`.`avaliador_tb_user_id` = `vwu`.`id`))
		)
		join `mdl_eva_afastamento_anteprojeto` `ant` on((`ant`.`id` = `atr`.`tb_anteprojeto_id`))
	)
order by `ava`.`id`;
/*
 Descrição: visualiza painel administrativo do Barema de Pós Graduação. [VERIFICAR]
 Traz informações tais como: aluno, avaliador, atividade, curso, quantidade de alunos em um curso.
 */
-- ===================================  CURSO POR USUÁRIO =================================
CREATE OR REPLACE VIEW vw_rel_curso_por_usuario AS
SELECT mcc.id,
	mcc.userid,
	upper(concat(trim(u.firstname), ' ', trim(u.lastname))) as nome,
	upper(trim(u.email)) as email,
	upper(trim(u.city)) as cidade,
	upper(trim(u.exercicio)) as exercicio,
	case
		when u.ds_cargo is not null then upper(trim(u.ds_cargo))
		else '--'
	end as cargo,
	upper(trim(u.lotacao)) as lotacao,
	c.id as courseid,
	upper(trim(c.fullname)) as curso,
	upper(trim(vcc.categoryname)) as categoria,
	-- 		date_format(from_unixtime(mcc.timeenrolled),'%d/%m/%Y') AS `inscricao`,
	FROM_UNIXTIME(mcc.timeenrolled) AS `inscricao`,
	CASE
		WHEN (mcc.timestarted = 0)
		AND (mcc.timecompleted is null) THEN 'NAO INICIADO'
		WHEN (mcc.timestarted > 0)
		AND (mcc.timecompleted is null) THEN 'CURSANDO'
		ELSE 'CONCLUIDO'
	END AS `status`
FROM mdl_course_completions mcc
	INNER JOIN mdl_user u ON mcc.userid = u.id
	INNER JOIN mdl_course c ON mcc.course = c.id
	LEFT JOIN vw_course_category vcc ON vcc.id = c.id
WHERE u.deleted = 0
GROUP BY mcc.id
ORDER BY mcc.id ASC;
-- ===================================  CURSO E CATEGORIA =================================
CREATE OR REPLACE VIEW vw_rel_curso_e_categoria AS
SELECT mc.id,
	upper(trim(vcc.categoryname)) as categoryname,
	CASE
		WHEN vcc.sub_subcategoryname IS NOT NULL THEN upper(
			trim(
				concat(
					vcc.subcategoryname,
					' / ',
					vcc.sub_subcategoryname
				)
			)
		)
		ELSE upper(trim(vcc.subcategoryname))
	END AS subcategoryname,
	upper(trim(mc.fullname)) AS fullname,
	mecw.workload,
	count(tab1.id) AS total_inscritos,
	COALESCE(tab2.total_completos, 0) AS concluidos,
	COALESCE(tab3.total_certi, 0) as certificado,
	CASE
		WHEN mc.startdate = 0 THEN NULL
		ELSE FROM_UNIXTIME(mc.startdate)
	END AS dta_inicio,
	CASE
		WHEN mc.enddate = 0 THEN NULL
		ELSE FROM_UNIXTIME(mc.enddate)
	END AS dta_final,
	mc.visible,
	CASE
		WHEN mc.visible = 0 THEN 'CANCELADO'
		WHEN (mc.startdate = 0) THEN 'NAO INICIADO'
		WHEN (
			(mc.startdate > 0)
			AND (UNIX_TIMESTAMP(now()) < mc.startdate)
		) THEN 'PROGRAMADO'
		WHEN (mc.startdate > 0)
		AND (mc.enddate = 0) THEN 'EM ANDAMENTO'
		WHEN (mc.enddate > 0) THEN 'ENCERRADO'
	END AS status_curso
FROM mdl_course mc
	LEFT JOIN vw_course_category vcc ON vcc.id = mc.id
	LEFT JOIN (
		select mu.id AS id,
			mcc.course AS courseid
		FROM mdl_user mu
			INNER JOIN mdl_course_completions mcc ON mcc.userid = mu.id
	) tab1 ON tab1.courseid = mc.id
	LEFT JOIN (
		SELECT mcc.course AS courseid,
			COUNT(mcc.course) AS total_completos
		FROM mdl_course_completions mcc
		WHERE mcc.timecompleted > 0
		GROUP BY mcc.course
	) tab2 ON tab2.courseid = tab1.courseid
	LEFT JOIN (
		SELECT msc.course AS courseid,
			COUNT(msc.course) AS total_certi
		FROM mdl_simplecertificate msc
			LEFT JOIN mdl_simplecertificate_issues msi ON msc.id = msi.certificateid
		WHERE msi.timedeleted is null
		GROUP BY courseid
	) tab3 ON tab3.courseid = tab1.courseid
	LEFT JOIN mdl_eva_course_workload mecw on mecw.courseid = mc.id
GROUP BY tab1.courseid
ORDER BY id;
-- ==============================  RESULTADO POR CURSOS =================================
CREATE OR REPLACE VIEW vw_rel_resultado_por_curso AS
SELECT mcc.id,
	mcc.userid,
	upper(concat(trim(u.firstname), ' ', trim(u.lastname))) as nome,
	upper(trim(vcc.categoryname)) as categoria,
	mcc.course,
	upper(trim(c.fullname)) as curso,
	FROM_UNIXTIME(mcc.timeenrolled) AS inscricao,
	cw.workload,
	CASE
		WHEN tab1.atividades IS NULL THEN 0
		WHEN tab1.atividades IS NOT NULL THEN tab1.atividades
	END AS progresso,
	CASE
		WHEN tab1.atividade_total IS NULL THEN 0
		WHEN tab1.atividade_total IS NOT NULL THEN tab1.atividade_total
	END AS atividade_total
FROM mdl_course_completions mcc
	INNER JOIN mdl_user u ON u.id = mcc.userid
	INNER JOIN mdl_course c ON c.id = mcc.course
	LEFT JOIN mdl_eva_course_workload cw ON mcc.course = cw.courseid
	LEFT JOIN vw_course_category vcc ON mcc.course = vcc.id
	LEFT JOIN (
		SELECT cm.course,
			cmc.userid,
			COUNT(*) as atividades,
			tab2.atividade_total
		FROM mdl_course_modules_completion cmc
			INNER JOIN mdl_user u ON u.id = cmc.userid
			INNER JOIN mdl_course_modules cm ON cm.id = cmc.coursemoduleid
			INNER JOIN mdl_course c ON c.id = cm.course
			LEFT JOIN (
				SELECT c.id,
					COUNT(*) AS atividade_total
				FROM mdl_course_modules cm
					JOIN mdl_course c ON c.id = cm.course
					JOIN mdl_modules m ON m.id = cm.module -- AND cm.completion > 0 
					-- AND cm.visible = 1 
				GROUP BY c.id
				ORDER BY c.id
			) tab2 ON c.id = tab2.id
		GROUP BY course,
			userid
		ORDER BY cmc.userid ASC
	) tab1 ON mcc.course = tab1.course
	AND mcc.userid = tab1.userid
ORDER BY mcc.userid ASC;

/* 
Descrição: View que calcula o progressso dos usuários nos cursos
> Relatórios Administrativos
 */
CREATE OR REPLACE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `vw_progress` AS
SELECT `ue`.`userid` AS `user_id`,
	`c`.`id` AS `course_id`,
	COUNT(
		DISTINCT (
			CASE
				WHEN (`cm`.`completion` > 0) THEN `cm`.`id`
			END
		)
	) AS `total_required_activities`,
	CAST(
		(
			SUM(
				(
					CASE
						WHEN (
							(`cm`.`completion` > 0)
							AND (`cmpl`.`completionstate` = 1)
						) THEN 1
						ELSE 0
					END
				)
			) / COUNT(DISTINCT `ue`.`id`)
		) AS SIGNED
	) AS `completed_required_activities`,
	CAST(
		(
			(
				(
					SUM(
						(
							CASE
								WHEN (
									(`cm`.`completion` > 0)
									AND (`cmpl`.`completionstate` = 1)
								) THEN 1
								ELSE 0
							END
						)
					) / COALESCE(
						NULLIF(
							COUNT(
								DISTINCT (
									CASE
										WHEN (`cm`.`completion` > 0) THEN `cm`.`id`
									END
								)
							),
							0
						),
						1
					)
				) / COALESCE(NULLIF(COUNT(DISTINCT `ue`.`id`), 0), 1)
			) * 100
		) AS SIGNED
	) AS `progress`
FROM (
		(
			(
				(
					(
						`mdl_user_enrolments` `ue`
						JOIN `mdl_user` `u` ON ((`ue`.`userid` = `u`.`id`))
					)
					JOIN `mdl_enrol` `e` ON ((`ue`.`enrolid` = `e`.`id`))
				)
				JOIN `mdl_course` `c` ON ((`e`.`courseid` = `c`.`id`))
			)
			JOIN `mdl_course_modules` `cm` ON ((`cm`.`course` = `c`.`id`))
		)
		LEFT JOIN `mdl_course_modules_completion` `cmpl` ON (
			(
				(`cmpl`.`coursemoduleid` = `cm`.`id`)
				AND (`cmpl`.`userid` = `u`.`id`)
			)
		)
	)
GROUP BY `ue`.`userid`,
	`c`.`id`;
/* 
Descrição: View que controla as informações de cada usuário em cada curso
> Relatórios Administrativos
 */
CREATE OR REPLACE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `vw_courses_per_user` AS
select row_number() OVER () AS `id`,
	`subquery`.`user_id` AS `user_id`,
	`subquery`.`nome_completo` AS `nome_completo`,
	`subquery`.`email` AS `email`,
	`subquery`.`cidade` AS `cidade`,
	`subquery`.`siglaexercicio` AS `sigla_exercicio`,
	`subquery`.`exercicio` AS `exercicio`,
	`subquery`.`cargo` AS `cargo`,
	`subquery`.`lotacao` AS `lotacao`,
	`subquery`.`course_id` AS `course_id`,
	`subquery`.`nome_curso` AS `nome_curso`,
	`subquery`.`nome_abreviado_curso` AS `nome_abreviado_curso`,
	`subquery`.`tcategoria` AS `tcategoria`,
	`subquery`.`scategoria` AS `scategoria`,
	`subquery`.`pcategoria` AS `pcategoria`,
	coalesce(`subquery`.`carga_horaria`, '08 hrs 00 min') AS `carga_horaria`,
	`subquery`.`completas` AS `completas`,
	`subquery`.`total` AS `total`,
	`subquery`.`progresso` AS `progresso`
from (
		select `u`.`id` AS `user_id`,
			upper(
				trim(concat(`u`.`firstname`, ' ', `u`.`lastname`))
			) AS `nome_completo`,
			upper(trim(`u`.`city`)) AS `cidade`,
			upper(trim(`u`.`siglaexercicio`)) AS `siglaexercicio`,
			upper(trim(`u`.`exercicio`)) AS `exercicio`,
			upper(trim(`u`.`ds_cargo`)) AS `cargo`,
			upper(trim(`u`.`lotacao`)) AS `lotacao`,
			`u`.`email` AS `email`,
			`c`.`id` AS `course_id`,
			upper(trim(`c`.`fullname`)) AS `nome_curso`,
			upper(trim(`c`.`shortname`)) AS `nome_abreviado_curso`,
			upper(trim(`cc`.`name`)) AS `pcategoria`,
			upper(trim(coalesce(`subcat`.`name`, NULL))) AS `scategoria`,
			upper(trim(coalesce(`ssubcat`.`name`, NULL))) AS `tcategoria`,
			`ecw`.`workload` AS `carga_horaria`,
			`p`.`completed_required_activities` AS `completas`,
			`p`.`total_required_activities` AS `total`,
			if(
				(`p`.`total_required_activities` > 0),
				round(
					(
						(`p`.`completed_required_activities` * 100.0) / `p`.`total_required_activities`
					),
					0
				),
				0
			) AS `progresso`
		from (
				(
					(
						(
							(
								(
									(
										(
											select `u`.`id` AS `user_id`,
												`c`.`id` AS `course_id`
											from (
													(
														(
															`mdl_user` `u`
															join `mdl_user_enrolments` `ue` on((`u`.`id` = `ue`.`userid`))
														)
														join `mdl_enrol` `e` on((`ue`.`enrolid` = `e`.`id`))
													)
													join `mdl_course` `c` on((`e`.`courseid` = `c`.`id`))
												)
											group by `u`.`id`,
												`c`.`id`
										) `unique_users_courses`
										join `mdl_user` `u` on((`unique_users_courses`.`user_id` = `u`.`id`))
									)
									join `mdl_course` `c` on((`unique_users_courses`.`course_id` = `c`.`id`))
								)
								left join `mdl_eva_course_workload` `ecw` on((`c`.`id` = `ecw`.`courseid`))
							)
							left join `mdl_course_categories` `cc` on((`c`.`category` = `cc`.`id`))
						)
						left join `mdl_course_categories` `subcat` on((`cc`.`parent` = `subcat`.`id`))
					)
					left join `mdl_course_categories` `ssubcat` on((`subcat`.`parent` = `ssubcat`.`id`))
				)
				left join `vw_progress` `p` on(
					(
						(`u`.`id` = `p`.`user_id`)
						and (`c`.`id` = `p`.`course_id`)
					)
				)
			)
	) `subquery`;
/*
Descrição: View que exibe as informações gerais de cada curso
> Relatórios Administrativos
*/

CREATE OR REPLACE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `vw_courses_and_categories` AS
select row_number() OVER (
		ORDER BY `c`.`id`
	) AS `id`,
	`c`.`id` AS `course_id`,
	`c`.`fullname` AS `nome_curso`,
	`cc`.`name` AS `pcategoria`,
	`ccc`.`name` AS `scategoria`,
	`cccc`.`name` AS `tcategoria`,
	coalesce(`ew`.`workload`, '08 hrs 00 min') AS `carga_horaria`,
	count(distinct `ue`.`userid`) AS `inscritos`,
	coalesce(`progress_count`.`concluintes`, 0) AS `concluintes`,
	coalesce(`progress_count`.`naoiniciados`, 0) AS `naoiniciados`,
	coalesce(`progress_count`.`naoconcluidos`, 0) AS `naoconcluidos`,
	date_format(from_unixtime(`c`.`timecreated`), '%Y-%m-%d') AS `data_criacao`,
	date_format(from_unixtime(`c`.`startdate`), '%Y-%m-%d') AS `data_inicio`,
	date_format(from_unixtime(`c`.`enddate`), '%Y-%m-%d') AS `data_fim`,
	coalesce(`vwcpc`.`total_certi`, 0) AS `total_certificados`,
	`c`.`visible` AS `visivel`
from (
		(
			(
				(
					(
						(
							(
								(
									`mdl_course` `c`
									join `mdl_course_categories` `cc` on((`c`.`category` = `cc`.`id`))
								)
								left join `mdl_course_categories` `ccc` on((`cc`.`parent` = `ccc`.`id`))
							)
							left join `mdl_course_categories` `cccc` on((`ccc`.`parent` = `cccc`.`id`))
						)
						left join `mdl_eva_course_workload` `ew` on((`c`.`id` = `ew`.`courseid`))
					)
					left join `mdl_enrol` `e` on((`c`.`id` = `e`.`courseid`))
				)
				left join `mdl_user_enrolments` `ue` on((`e`.`id` = `ue`.`enrolid`))
			)
			left join (
				select `vw_progress`.`course_id` AS `course_id`,
					count(
						distinct (
							case
								when (`vw_progress`.`progress` = 100) then `vw_progress`.`user_id`
							end
						)
					) AS `concluintes`,
					count(
						distinct (
							case
								when (`vw_progress`.`progress` = 0) then `vw_progress`.`user_id`
							end
						)
					) AS `naoiniciados`,
					count(
						distinct (
							case
								when (
									`vw_progress`.`progress` between 1 and 99
								) then `vw_progress`.`user_id`
							end
						)
					) AS `naoconcluidos`
				from `vw_progress`
				group by `vw_progress`.`course_id`
			) `progress_count` on((`c`.`id` = `progress_count`.`course_id`))
		)
		left join (
			select `vw_certificados_por_curso`.`course` AS `course`,
				sum(`vw_certificados_por_curso`.`total_certi`) AS `total_certi`
			from `vw_certificados_por_curso`
			group by `vw_certificados_por_curso`.`course`
		) `vwcpc` on((`c`.`id` = `vwcpc`.`course`))
	)
group by `c`.`id`,
	`cc`.`id`,
	`ccc`.`id`,
	`ew`.`id`,
	`progress_count`.`course_id`,
	`vwcpc`.`total_certi`;
/*
Descrição: View para visualizar relatório de conclusão de curso individual
> Relatórios Administrativos
*/
CREATE OR REPLACE ALGORITHM = UNDEFINED
SQL SECURITY DEFINER VIEW `vw_relatorio_conclusao` AS
select
    row_number() OVER () AS `id`,
    `sub`.`user_id` AS `user_id`,
    `sub`.`course_id` AS `course_id`,
    `sub`.`nome_curso` AS `nome_curso`,
    `sub`.`pcategoria` AS `pcategoria`,
    `sub`.`scategoria` AS `scategoria`,
    `sub`.`nome_completo` AS `nome_completo`,
    `sub`.`email` AS `email`,
    `sub`.`cargo` AS `cargo`,
    `sub`.`exercicio` AS `exercicio`,
    `sub`.`sigla` AS `sigla`,
    `sub`.`progresso` AS `progresso`,
    `sub`.`data_conclusao` AS `data_conclusao`,
    `sub`.`timecompleted` AS `timecompleted`
from
    (
        select
            `ue`.`userid` AS `user_id`,
            `c`.`id` AS `course_id`,
            `c`.`fullname` AS `nome_curso`,
(
                case
                    when (`ccat`.`parent` > 0) then `parent_cat`.`name`
                    else `ccat`.`name`
                end
            ) AS `pcategoria`,
(
                case
                    when (`ccat`.`parent` > 0) then `ccat`.`name`
                    else ''
                end
            ) AS `scategoria`,
            concat(`u`.`firstname`, ' ', `u`.`lastname`) AS `nome_completo`,
            `u`.`email` AS `email`,
            `u`.`ds_cargo` AS `cargo`,
            `u`.`exercicio` AS `exercicio`,
            `u`.`siglaexercicio` AS `sigla`,
            cast(
                (
                    (
                        (
                            sum(
                                (
                                    case
                                        when (
                                            (`cm`.`completion` > 0)
                                            and (`cmpl`.`completionstate` = 1)
                                        ) then 1
                                        else 0
                                    end
                                )
                            ) / coalesce(
                                nullif(
                                    count(
                                        distinct (
                                            case
                                                when (`cm`.`completion` > 0) then `cm`.`id`
                                            end
                                        )
                                    ),
                                    0
                                ),
                                1
                            )
                        ) / coalesce(nullif(count(distinct `ue`.`id`), 0), 1)
                    ) * 100
                ) as signed
            ) AS `progresso`,
            max(`cmpl`.`timemodified`) AS `data_conclusao`,
            `cc`.`timecompleted` AS `timecompleted`
        from
            (
                (
                    (
                        (
                            (
                                (
                                    (
                                        (
                                            `mdl_user_enrolments` `ue`
                                            join `mdl_user` `u` on((`ue`.`userid` = `u`.`id`))
                                        )
                                        join `mdl_enrol` `e` on((`ue`.`enrolid` = `e`.`id`))
                                    )
                                    join `mdl_course` `c` on((`e`.`courseid` = `c`.`id`))
                                )
                                join `mdl_course_modules` `cm` on((`cm`.`course` = `c`.`id`))
                            )
                            left join `mdl_course_modules_completion` `cmpl` on(
                                (
                                    (`cmpl`.`coursemoduleid` = `cm`.`id`)
                                    and (`cmpl`.`userid` = `u`.`id`)
                                )
                            )
                        )
                        left join `mdl_course_completions` `cc` on(
                            (
                                (`cc`.`userid` = `u`.`id`)
                                and (`cc`.`course` = `c`.`id`)
                            )
                        )
                    )
                    left join `mdl_course_categories` `ccat` on((`c`.`category` = `ccat`.`id`))
                )
                left join `mdl_course_categories` `parent_cat` on((`ccat`.`parent` = `parent_cat`.`id`))
            )
        group by
            `ue`.`userid`,
            `c`.`id`
    ) `sub`;