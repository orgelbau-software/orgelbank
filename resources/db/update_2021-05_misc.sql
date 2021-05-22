
INSERT INTO `option_meta` (`option_id`, `option_modul`, `option_name`, `option_value`, `option_autoload`, `option_comment`, `option_editable`, `option_lastchange`, `option_createdate`) VALUES (NULL, 'Rechnung', 'rechnung_pflege_schlusstext', 'Vielen Dank für ihr Vertrauen', '1', 'Schlusstext für Pflege Rechnungen', '1', '', '');
INSERT INTO `option_meta` (`option_id`, `option_modul`, `option_name`, `option_value`, `option_autoload`, `option_comment`, `option_editable`, `option_lastchange`, `option_createdate`) VALUES (NULL, 'Rechnung', 'rechnung_stunden_schlusstext', 'Vielen Dank für ihren Auftrag', '1', 'Schlusstext für Stunden Rechnungen', '1', '', '');


ALTER TABLE `projekt_aufgabe` ADD `pa_reihenfolge` INT NOT NULL AFTER `au_id`;

INSERT INTO `option_meta` (`option_id`, `option_modul`, `option_name`, `option_value`, `option_autoload`, `option_comment`, `option_editable`, `option_lastchange`, `option_createdate`) VALUES (NULL, 'Gemeinde', 'gemeinde_liste_standard_sortierung', 'g_kirche', '1', 'Entweder "kirche" oder "ort"', '1', '', '');

ALTER TABLE `rechnung_position` ADD `rpos_type` INT NOT NULL AFTER `rpos_id`;
ALTER TABLE `arbeitswoche` ADD `aw_stunden_urlaub` INT NULL DEFAULT NULL AFTER `aw_stunden_dif`;
ALTER TABLE `arbeitstag` ADD `aw_id` INT NULL DEFAULT NULL AFTER `at_id`;

INSERT INTO `option_meta` (`option_id`, `option_modul`, `option_name`, `option_value`, `option_autoload`, `option_comment`, `option_editable`, `option_lastchange`, `option_createdate`) VALUES (NULL, 'Einstellung', 'cronjob_geostatus_limit', '30', '1', 'Wieviele Adressen der GeoStatus Cronjob pro Lauf verarbeiten soll', '1', '', '');