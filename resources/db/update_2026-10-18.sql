UPDATE `reisekosten`
SET `rk_datum` = STR_TO_DATE(CONCAT(`rk_jahr`, ' ', LPAD(`rk_kw`, 2, '0'), ' Monday'), '%x %v %W')
WHERE `rk_datum` = '0000-00-00';

ALTER TABLE `reisekosten`
  DROP INDEX `proj_id`,
  DROP INDEX `proj_id_2`,
  ADD UNIQUE KEY `proj_id_datum` (`proj_id`, `be_id`, `rk_datum`),
  DROP COLUMN `rk_timestamp`;
