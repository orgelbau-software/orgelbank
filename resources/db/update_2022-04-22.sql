ALTER TABLE `gemeinde` ADD `g_kundennr` INT NULL DEFAULT NULL AFTER `k_id`;


-- Urlaub
CREATE TABLE `urlaub` (
  `u_id` int(11) NOT NULL,
  `u_datum_von` date NOT NULL,
  `u_datum_bis` date DEFAULT NULL,
  `u_tage` double(10,1) NOT NULL,
  `be_id` int(11) NOT NULL,
  `u_verbleibend` double(10,2) NOT NULL COMMENT 'Aktueller Urlaub',
  `u_resturlaub` double(10,2) NOT NULL COMMENT 'Resturlaub',
  `u_summe` double(10,2) NOT NULL COMMENT 'Aktuell und Verbleibend',
  `u_status` int(11) NOT NULL,
  `u_bemerkung` varchar(100) NOT NULL,
  `u_createdate` datetime NOT NULL,
  `u_lastchange` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

ALTER TABLE `urlaub`
  ADD PRIMARY KEY (`u_id`);

ALTER TABLE `urlaub`
  MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
COMMIT;