ALTER TABLE `projekt_aufgabe` ADD `pa_sollstunden` DOUBLE(10,2) NOT NULL DEFAULT '0' AFTER `pa_lastchange`;
ALTER TABLE `projekt_aufgabe` ADD `pa_sollmaterial` DOUBLE(10,2) NOT NULL DEFAULT '0' AFTER `pa_lastchange`;
ALTER TABLE `projekt_aufgabe` ADD `pa_iststunden`  DOUBLE(10,2) NOT NULL  DEFAULT '0' AFTER `pa_sollstunden`;


CREATE TABLE `wartungsprotokolle` ( `wp_id` INT NOT NULL AUTO_INCREMENT , `wp_name` VARCHAR(100) NOT NULL , `wp_bemerkung` VARCHAR(250) NOT NULL , `wp_dateiname` VARCHAR(100) NOT NULL , `wp_lastchange` DATE NOT NULL , `wp_createdate` DATE NOT NULL , PRIMARY KEY (`wp_id`)) ENGINE = InnoDB;
ALTER TABLE `orgel` ADD `wp_id` INT NULL DEFAULT NULL AFTER `o_kostenteilstimmung`;

ALTER TABLE `disposition` ADD `d_typ` INT(1) NOT NULL DEFAULT '1' AFTER `d_id`;

ALTER TABLE `orgel` ADD `o_intervallhaupstimmung` INT(1) NOT NULL DEFAULT '0' AFTER `wp_id`;

ALTER TABLE `ansprechpartner` ADD `a_firma` VARCHAR(100) NOT NULL AFTER `a_id`;
ALTER TABLE `ansprechpartner` ADD `a_webseite` VARCHAR(200) NULL AFTER `a_aktiv`;
ALTER TABLE `ansprechpartner` CHANGE `a_bemerkung` `a_bemerkung` VARCHAR(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;