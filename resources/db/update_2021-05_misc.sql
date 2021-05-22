
INSERT INTO `option_meta` (`option_id`, `option_modul`, `option_name`, `option_value`, `option_autoload`, `option_comment`, `option_editable`, `option_lastchange`, `option_createdate`) VALUES (NULL, 'Rechnung', 'rechnung_pflege_schlusstext', 'Vielen Dank für ihr Vertrauen', '1', 'Schlusstext für Pflege Rechnungen', '1', '', '');
INSERT INTO `option_meta` (`option_id`, `option_modul`, `option_name`, `option_value`, `option_autoload`, `option_comment`, `option_editable`, `option_lastchange`, `option_createdate`) VALUES (NULL, 'Rechnung', 'rechnung_stunden_schlusstext', 'Vielen Dank für ihren Auftrag', '1', 'Schlusstext für Stunden Rechnungen', '1', '', '');


ALTER TABLE `projekt_aufgabe` ADD `pa_reihenfolge` INT NOT NULL AFTER `au_id`;