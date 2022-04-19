ALTER TABLE `gemeinde` ADD `g_kundennr` INT NULL DEFAULT NULL AFTER `k_id`;


-- Urlaub
CREATE TABLE `urlaub` ( `u_id` INT NOT NULL AUTO_INCREMENT ,  `u_datum_von` DATE NOT NULL ,  `u_datum_bis` DATE NOT NULL ,  `u_tage` INT NOT NULL ,  `be_id` INT NOT NULL ,  `u_verbleibend` INT NOT NULL COMMENT 'Aktueller Urlaub' ,  `u_rest` INT NOT NULL COMMENT 'Resturlaub' ,  `u_summe` INT NOT NULL COMMENT 'Aktuell und Verbleibend' ,  `u_status` INT NOT NULL ,  `u_createdate` DATETIME NOT NULL ,  `u_lastchange` DATETIME NOT NULL ,    PRIMARY KEY  (`u_id`)) ENGINE = MyISAM;