TRUNCATE TABLE rechnung_pflege;

<?php
$sql = "INSERT INTO `rechnung_pflege` (`rp_nummer`, `rp_text1`, `rp_pos1`, `rp_pos2`, `rp_pos3`, `rp_pos4`, `rp_pos5`, `rp_pos6`, `rp_pos7`, `rp_pos8`, `rp_pos9`, `rp_pos10`, `rp_text2`, `rp_nettobetrag`, `rp_bruttobetrag`, `rp_mwst`, `rp_mwstsatz`, `rp_betrag`, `rp_pflegekosten`, `rp_fahrtkosten`, `rp_eingangsbetrag`, `rp_eingangsdatum`, `rp_eingangsanmerkung`, `g_id`, `rp_datum`, `rp_zieldatum`, `rp_lastchange`, `rp_createdate`) VALUES
('<!--RechNr-->/16', 'Einleitung', '', '', '', '', '', '', '', '', '', '', 'Schluss', <!--GK-->, <!--GS-->, <!--MWST-->, 0.19, '', <!--PK-->, <!--FK-->, 0.00, '0000-00-00', '', <!--GemeindeID-->, '<!--Datum-->', '<!--Zieldatum-->', '2016-11-01 12:34:57', '2016-11-01 12:34:57');";

$alle = array ();

for($i = 0; $i < 34; $i ++) {
	$theSQL = str_replace ( "<!--RechNr2-->", $i, $sql );
	$theSQL = str_replace ( "<!--GemeindeID-->", $i, $theSQL );
	
	$pk = mt_rand ( 1, 1 * 1000 );
	$theSQL = str_replace ( "<!--PK-->", $pk, $theSQL );
	$fk = mt_rand ( 1, 1 * 50 );
	$theSQL = str_replace ( "<!--FK-->", $fk, $theSQL );
	$theSQL = str_replace ( "<!--GK-->", $pk + $fk, $theSQL );
	$theSQL = str_replace ( "<!--MWST-->", ($pk + $fk) * 0.19, $theSQL );
	$theSQL = str_replace ( "<!--GS-->", ($pk + $fk) + (($pk + $fk) * 0.19), $theSQL );
	
	$date = mktime ( 0, 0, 0, 1, mt_rand ( 1, 1 * 365 ), 2016 );
	$rechDat = date ( "Y-m-d", $date );
	$theSQL = str_replace ( "<!--Datum-->", $rechDat, $theSQL );
	$theSQL = str_replace ( "<!--Zieldatum-->", $rechDat, $theSQL );
	$alle [intval ( $date )] = $theSQL;
}

ksort ( $alle );

$i = 100;
foreach ( $alle as $key => $val ) {
//     echo $key;
 	echo str_replace ( "<!--RechNr-->", $i ++, $val );
	echo "\r\n";
}