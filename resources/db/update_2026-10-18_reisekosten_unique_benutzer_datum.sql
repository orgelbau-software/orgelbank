-- Vorhandene Mehrfachdatensätze vor dem Index manuell zusammenführen.
SELECT
  `be_id`,
  `rk_datum`,
  COUNT(*) AS `anzahl`
FROM `reisekosten`
GROUP BY `be_id`, `rk_datum`
HAVING COUNT(*) > 1;

-- Bei Duplikaten schlägt dieses Statement fehl und der bisherige Index bleibt erhalten.
ALTER TABLE `reisekosten`
  ADD UNIQUE KEY `be_id_datum` (`be_id`, `rk_datum`);

ALTER TABLE `reisekosten`
  DROP INDEX `proj_id_datum`;
