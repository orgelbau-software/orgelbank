ALTER TABLE `projekt_aufgabe` ADD `pa_sollstunden` DOUBLE(10,2) NOT NULL DEFAULT '0' AFTER `pa_lastchange`;
ALTER TABLE `projekt_aufgabe` ADD `pa_sollmaterial` DOUBLE(10,2) NOT NULL DEFAULT '0' AFTER `pa_lastchange`;


CREATE TABLE `krawinkel`.`wartungsprotokolle` ( `wp_id` INT NOT NULL AUTO_INCREMENT , `wp_name` VARCHAR(100) NOT NULL , `wp_bemerkung` VARCHAR(250) NOT NULL , `wp_dateiname` VARCHAR(100) NOT NULL , `wp_lastchange` DATE NOT NULL , `wp_createdate` DATE NOT NULL , PRIMARY KEY (`wp_id`)) ENGINE = InnoDB;