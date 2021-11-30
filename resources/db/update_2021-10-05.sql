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

CREATE TABLE `nebenkosten_rechnung` ( `nk_id` int(10) NOT NULL, `proj_id` int(10) NOT NULL, `be_id` int(11) NOT NULL, `nk_nummer` varchar(100) NOT NULL, `nk_datum` date NOT NULL, `nk_kommentar` text NOT NULL, `nk_betrag` double(10,2) NOT NULL, `nk_lieferant` varchar(100) NOT NULL, `nk_leistung` varchar(100) NOT NULL, `nk_lastchange` datetime NOT NULL, `nk_createdate` datetime NOT NULL ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
ALTER TABLE `nebenkosten_rechnung`
  ADD PRIMARY KEY (`nk_id`);

ALTER TABLE `nebenkosten_rechnung`
  MODIFY `nk_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

INSERT INTO `option_meta` (`option_id`, `option_modul`, `option_name`, `option_value`, `option_autoload`, `option_comment`, `option_editable`, `option_lastchange`, `option_createdate`) VALUES (NULL, 'Projekt', 'nebenkosten', 'LKW,PKW,Anhänger,Spesen,Hotel', '1', 'Standard Nebenkosten Auswahl ', '1', '2021-11-30 22:05:34.000000', '2021-11-30 22:05:34.000000');