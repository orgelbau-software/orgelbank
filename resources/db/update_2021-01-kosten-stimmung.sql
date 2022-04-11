ALTER TABLE `orgel` ADD `o_kostenhauptstimmung` VARCHAR(128) NOT NULL DEFAULT '' AFTER `o_aktiv`, ADD `o_kostenteilstimmung` VARCHAR(128) NOT NULL DEFAULT '' AFTER `o_kostenhauptstimmung`;
ALTER TABLE `gemeinde` ADD `g_kundennr` VARCHAR(32) NULL AFTER `g_rgemeinde`;

INSERT INTO `option_meta` (`option_id`, `option_modul`, `option_name`, `option_value`, `option_autoload`, `option_comment`, `option_editable`, `option_lastchange`, `option_createdate`) VALUES (NULL, 'Wartung', 'wartung_bogen_alle_ansprechpartner', 'true', '1', 'Alle Ansprechpartner auf dem Wartungsbogen anzeigen', '1', '', '');
INSERT INTO `option_meta` (`option_id`, `option_modul`, `option_name`, `option_value`, `option_autoload`, `option_comment`, `option_editable`, `option_lastchange`, `option_createdate`) VALUES (NULL, 'Wartung', 'wartung_bogen_checkliste', 'true', '1', 'Checkliste auf dem Wartungsbogen anzeigen', '1', '', '');
