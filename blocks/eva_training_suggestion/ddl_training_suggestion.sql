CREATE TABLE moodle.mdl_eva_superior_organ (
	id BIGINT auto_increment NOT NULL,
	st_status INT(1) DEFAULT 1 NOT NULL,
	no_organ VARCHAR(100) NOT NULL,
	CONSTRAINT mdl_eva_superior_organ_pk PRIMARY KEY (id)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;
CREATE INDEX mdl_eva_superior_organ_id_IDX USING BTREE ON moodle.mdl_eva_superior_organ (id,dt_registry,st_status,no_organ);

CREATE TABLE `mdl_eva_training_suggestion` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `id_user` bigint(10) NOT NULL,
  `id_superior_organ` bigint(10) NOT NULL,
  `id_slc_cargo` bigint(10) NOT NULL,
  `st_suggestion` int(1) NOT NULL DEFAULT 1,
  `ds_theme` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slc_priority_area_legal` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slc_technical_legal` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ds_development_need` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ds_target_audience` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nu_participants` int(11) DEFAULT NULL,
  `ds_transversality` int(1) DEFAULT NULL,
  `ds_workload` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slc_modality` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_institution_instructor` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nu_estimated_value` decimal(10,0) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mdl_eva_training_suggestion_FK` (`id_user`),
  KEY `mdl_eva_training_suggestion_FK_1` (`id_superior_organ`),
  CONSTRAINT `mdl_eva_training_suggestion_FK` FOREIGN KEY (`id_user`) REFERENCES `mdl_user` (`id`),
  CONSTRAINT `mdl_eva_training_suggestion_FK_1` FOREIGN KEY (`id_superior_organ`) REFERENCES `mdl_eva_superior_organ` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci

ALTER TABLE moodle.mdl_eva_training_suggestion ADD dt_suggestion TIMESTAMP DEFAULT current_timestamp() NOT NULL;
ALTER TABLE moodle.mdl_eva_superior_organ ADD dt_registry TIMESTAMP DEFAULT current_timestamp() NOT NULL;

INSERT INTO moodle.mdl_eva_superior_organ
(id, st_status, no_organ, dt_registry)
VALUES(1, 1, 'Advocacia Geral da União (AGU)', '2021-06-10 17:55:47');
INSERT INTO moodle.mdl_eva_superior_organ
(id, st_status, no_organ, dt_registry)
VALUES(6, 1, 'Conselho Superior da Advocacia-Geral da União (CSAGU)', '2021-06-15 22:01:20');
INSERT INTO moodle.mdl_eva_superior_organ
(id, st_status, no_organ, dt_registry)
VALUES(7, 1, 'Secretaria Geral de Consultoria (SGCS)', '2021-06-15 22:01:25');
INSERT INTO moodle.mdl_eva_superior_organ
(id, st_status, no_organ, dt_registry)
VALUES(8, 1, 'Secretaria Geral de Contencioso (SGCT)', '2021-06-15 22:01:29');
INSERT INTO moodle.mdl_eva_superior_organ
(id, st_status, no_organ, dt_registry)
VALUES(9, 1, 'Consultoria Geral da União (CGU)', '2021-06-15 22:01:33');
INSERT INTO moodle.mdl_eva_superior_organ
(id, st_status, no_organ, dt_registry)
VALUES(10, 1, 'Procuradoria Geral da União (PGU)', '2021-06-15 22:01:37');
INSERT INTO moodle.mdl_eva_superior_organ
(id, st_status, no_organ, dt_registry)
VALUES(11, 1, 'Procuradoria Geral Federal (PGF)', '2021-06-15 22:01:41');
INSERT INTO moodle.mdl_eva_superior_organ
(id, st_status, no_organ, dt_registry)
VALUES(12, 1, 'Procuradoria Geral da Fazenda Nacional (PGFN)', '2021-06-15 22:01:44');
INSERT INTO moodle.mdl_eva_superior_organ
(id, st_status, no_organ, dt_registry)
VALUES(13, 1, 'Procuradoria Geral do Banco Central (PGBC)', '2021-06-15 22:01:47');
INSERT INTO moodle.mdl_eva_superior_organ
(id, st_status, no_organ, dt_registry)
VALUES(14, 1, 'Secretaria Geral de Administração (SGA)', '2021-06-15 22:01:51');
INSERT INTO moodle.mdl_eva_superior_organ
(id, st_status, no_organ, dt_registry)
VALUES(15, 1, 'Escola Superior da AGU (ESAGU)', '2021-06-15 22:01:55');
INSERT INTO moodle.mdl_eva_superior_organ
(id, st_status, no_organ, dt_registry)
VALUES(16, 1, 'Corregedoria-Geral da Advocacia da União (CGAU)', '2021-06-15 22:01:59');
INSERT INTO moodle.mdl_eva_superior_organ
(id, st_status, no_organ, dt_registry)
VALUES(17, 1, 'Departamento de Gestão Estratégica (DGE)', '2021-06-15 22:02:03');
INSERT INTO moodle.mdl_eva_superior_organ
(id, st_status, no_organ, dt_registry)
VALUES(18, 1, 'Comissão de Ética da AGU', '2021-06-15 22:02:06');
INSERT INTO moodle.mdl_eva_superior_organ
(id, st_status, no_organ, dt_registry)
VALUES(19, 1, 'Ouvidoria da AGU', '2021-06-15 22:02:10');
