ALTER TABLE `orgel` ADD `o_kostenhauptstimmung` VARCHAR(128) NOT NULL DEFAULT '' AFTER `o_aktiv`, ADD `o_kostenteilstimmung` VARCHAR(128) NOT NULL DEFAULT '' AFTER `o_kostenhauptstimmung`;
