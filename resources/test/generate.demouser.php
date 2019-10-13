<pre>
<?php
include "../../conf/config.inc.php";
$benutzer = new Benutzer(4);
$benutzer->setDemo("1");
$benutzer->setAktiviert("1");
$benutzer->setZeiterfassung(1);
$benutzer->setStdLohn(30);
$benutzer->setVerrechnungsSatz(30);
$benutzer->setBenutzerlevel(10);
$benutzer->setGeloescht(0);
$benutzer->setFailedLoginCount(0);
$benutzer->setStdMontag(8);
$benutzer->setStdDienstag(8);
$benutzer->setStdMittwoch(8);
$benutzer->setStdDonnerstag(8);
$benutzer->setStdFreitag(8);
$benutzer->setStdSamstag(8);
$benutzer->setStdSonntag(8);
$benutzer->setUrlaubAktuell(30);
$benutzer->setUrlaubRest(30);
$benutzer->setEintrittsDatum(date("Y-m-d"));
$benutzer->setCreatedAt(date("Y-m-d"));


$f = fopen("demouser.csv", "r");

// Liest Zeile für Zeile bis zum Ende der Datei
while(!feof($f)) {
	$line =  fgets($f);
	$explode = explode(";", $line);
	
	$benutzer->setBenutzername(strtolower(trim($explode[0])));
	$benutzer->setPasswort(md5(utf8_decode(trim($explode[1]))));
	
	if($explode[0] == "" || $explode[0] == "0") {
	} else {
	echo $benutzer->export()."\n";
	}	
}

fclose($f);

