-- Vorhandene Mehrfachdatensätze vor dem Index manuell zusammenführen.
SELECT
  `be_id`,
  `rk_datum`,
  COUNT(*) AS `anzahl`
FROM `reisekosten`
GROUP BY `be_id`, `rk_datum`
HAVING COUNT(*) > 1;

UPDATE `reisekosten`
SET `rk_datum` = STR_TO_DATE(CONCAT(`rk_jahr`, ' ', LPAD(`rk_kw`, 2, '0'), ' Monday'), '%x %v %W');

ALTER TABLE `reisekosten` DROP INDEX `proj_id`;
ALTER TABLE `reisekosten` DROP INDEX `proj_id_2`;

ALTER TABLE `reisekosten`
  ADD UNIQUE KEY `be_id_datum` (`be_id`, `rk_datum`);

ALTER TABLE `projekt` CHANGE `proj_geloescht` `proj_geloescht` INT(11) NULL DEFAULT '0';
ALTER TABLE `projekt` CHANGE `proj_archivdatum` `proj_archivdatum` DATETIME NULL;
ALTER TABLE `benutzerverlauf` CHANGE `bv_post` `bv_post` VARCHAR(1000) NULL;
ALTER TABLE `projekt` CHANGE `proj_keinezeitenfuer` `proj_keinezeitenfuer` VARCHAR(255) NULL;
ALTER TABLE `projekt` CHANGE `proj_sortierung` `proj_sortierung` INT(11) NULL;
ALTER TABLE `projekt_aufgabe` CHANGE `pa_lastchange` `pa_lastchange` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `projekt_aufgabe` CHANGE `pa_createdate` `pa_createdate` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;