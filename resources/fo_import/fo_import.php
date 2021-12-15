<?php
include "../../conf/config.inc.php";

$db = DB::getInstance();
$db->connect();
$sql = array();
$sql[] = "TRUNCATE TABLE benutzer;";
//$sql[] = "TRUNCATE TABLE adresse;";
$sql[] = "TRUNCATE TABLE ansprechpartner;";
$sql[] = "TRUNCATE TABLE disposition;";
//$sql[] = "TRUNCATE TABLE gemeinde;";
//$sql[] = "TRUNCATE TABLE orgel;";
$sql[] = "TRUNCATE TABLE wartung;";
$sql[] = "TRUNCATE TABLE gemeindeansprechpartner;";
foreach ($sql as $key => $val) {
    echo $val . "<br>";
    $db->NonSelectQuery($val);
}

error_reporting(E_ALL);

// Firma
$oFirma = new Ansprechpartner();
$oFirma->setAktiv(1);
$oFirma->setNachname("Freiburger Orgelbau");
$oFirma->getAdresse()->setStrasse("Herrenstraße");
$oFirma->getAdresse()->setHausnummer("9");
$oFirma->getAdresse()->setPlz("79232");
$oFirma->getAdresse()->setOrt("March");
$oFirma->getAdresse()->setGeoStatus(IGeolocationConstants::OK);
$oFirma->getAdresse()->setLat("48.0522");
$oFirma->getAdresse()->setLng("7.7815809");
$oFirma->setAnrede("keine");
$oFirma->setFunktion("Firma");
$oFirma->speichern(true);

// Benutzer
$oSWA = new Benutzer();
$oSWA->setBenutzername("swatermeyer");
$oSWA->setVorname("Stephan");
$oSWA->setNachname("Watermeyer");
$oSWA->setBenutzerlevel(10);
$oSWA->setAktiviert(1);
$oSWA->setDemo(1);
$oSWA->setGeloescht(0);
$oSWA->setPasswort(PasswordUtility::encrypt(MYSQL_PASS));
$oSWA->speichern(false);

$row = 1;

$bezirke = array();
if (($handle = fopen("Stim_DAT ab 2010-a.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        $num = count($data);
        echo "<p> $num Felder in Zeile $row: <br /></p>\n";
        $row ++;
        for ($c = 0; $c < $num; $c ++) {
            echo $c . ":" . $data[$c] . "<br />\n";
        }
        
        if($row == 2) {
            continue;
        }
        
        $oGemeinde = new Gemeinde();
        
        $kirche = $data[8] . " " . $data[9];
        $oGemeinde->setAktiv(1);
        
        if (strpos(strtolower($kirche), "luth") !== false) {
            $oGemeinde->setKID(5); // Ev Luth
            $kirche = str_replace("Evang. Luth.", "", $kirche);
            $kirche = str_replace("Ev.-luth.", "", $kirche);
            $kirche = str_replace("Ev. Luth.", "", $kirche);
            $kirche = str_replace("Evang.-Luth.", "", $kirche);
            $kirche = str_replace("Evang.-luth.", "", $kirche);
            $kirche = str_replace("Evang.- Luth.", "", $kirche);
            $kirche = str_replace("Ev.", "", $kirche);
        } elseif (strpos(strtolower($kirche), "ev.") !== false || strpos(strtolower($kirche), "evang") !== false) {
            $oGemeinde->setKID(1); // Evangelisch
            $kirche = str_replace("Evang.", "", $kirche);
            $kirche = str_replace("Evangelische", "", $kirche);
            $kirche = str_replace("Ev.", "", $kirche);
        } elseif (strpos(strtolower($kirche), "kath") !== false) {
            $kirche = str_replace("Kath.", "", $kirche);
            $kirche = str_replace("Katholisches", "", $kirche);
            $oGemeinde->setKID(2); // Katholisch
        } else {
            $oGemeinde->setKID(3); // Sonstiges
        }
        
        $kirche = str_replace("Kirchengemeinderat", "", $kirche);
        $kirche = str_replace("Kirchengemeinde", "", $kirche);
        $kirche = str_replace("Pfarramt", "", $kirche);
        $kirche = str_replace("Pfarrgemeinde", "", $kirche);
        $kirche = trim($kirche);
        if ($kirche == "") {
            $kirche = $data[12];
        }
        
        $oGemeinde->setKundenNr($data[3]);
        $oGemeinde->setKirche(ucfirst($kirche));
        $oGemeinde->getKircheAdresse()->setType(Adresse::TYPE_KIRCHE);
        $oGemeinde->getKircheAdresse()->setPlz($data[11]);
        $oGemeinde->getKircheAdresse()->setOrt($data[12]);
        $oGemeinde->getKircheAdresse()->setLand("Deutschland");
        handleAdresse($oGemeinde->getKircheAdresse(), $data[10]);
        if (substr($data[11], 0, 1) == "A") {
            $oGemeinde->getKircheAdresse()->setLand("Österreich");
        }
        
        // $oGemeinde->setRGemeinde($kunde->ReEmpName);
        $oGemeinde->setRAnschrift($data[8] . " " . $data[9]);
        $oGemeinde->getRechnungAdresse()->setType(Adresse::TYPE_RECHNUNG);
        $oGemeinde->getRechnungAdresse()->setPlz($data[11]);
        $oGemeinde->getRechnungAdresse()->setOrt($data[12]);
        $oGemeinde->getRechnungAdresse()->setLand("Deutschland");
        handleAdresse($oGemeinde->getRechnungAdresse(), $data[10]);
        if (substr($data[11], 0, 1) == "A") {
            $oGemeinde->getRechnungAdresse()->setLand("Österreich");
        }
        
        if(isset($bezirke[$data[4]])) {
            $bezirkId = $bezirke[$data[4]];
        } else {
            $bezirkId = count($bezirke);
            $bezirke[$data[4]] = $bezirkId;            
        }
        $oGemeinde->setBID($bezirkId);
        
        $oGemeinde->speichern(true);
        
        // Gemeinde
        $oAnsprechpartnerKirche = new Ansprechpartner();
        $oAnsprechpartnerKirche->setAktiv(1);
        $oAnsprechpartnerKirche->setTelefon($data[13]);
        $oAnsprechpartnerKirche->setFax($data[14]);
        
        $oAnsprechpartnerKirche->setAnrede("");
        $oAnsprechpartnerKirche->setNachname($data[12]);
        $oAnsprechpartnerKirche->setEmail($data[15]);
        $oAnsprechpartnerKirche->setFunktion("Pfarrbüro");
        $oAnsprechpartnerKirche->speichern(true);
        AnsprechpartnerUtilities::neuerGemeindeAnsprechpartner($oAnsprechpartnerKirche->getID(), $oGemeinde->getID());
        
        $oGemeinde->setAID($oAnsprechpartnerKirche->getID());
        $oGemeinde->speichern(true);
        
        // Organist
        if ($data[16] != "") {
            $oAnsprechpartnerOrganist = new Ansprechpartner();
            $oAnsprechpartnerOrganist->setAktiv(1);
            $oAnsprechpartnerOrganist->setFunktion("Organist");
            $oAnsprechpartnerOrganist->setNachname($data[12]);
            $oAnsprechpartnerOrganist->setBemerkung($data[16]);
            $oAnsprechpartnerOrganist = handleAnredeNamen($data[16], $oAnsprechpartnerOrganist);
            $oAnsprechpartnerOrganist->speichern(true);
            
            AnsprechpartnerUtilities::neuerGemeindeAnsprechpartner($oAnsprechpartnerOrganist->getID(), $oGemeinde->getID());
        }
        
        // Pfarrer
        if ($data[19] != "") {
            $oAnsprechpartnerPfarrer = new Ansprechpartner();
            $oAnsprechpartnerPfarrer->setAktiv(1);
            $oAnsprechpartnerPfarrer->setFunktion("Pfarrer");
            $oAnsprechpartnerPfarrer->setNachname($data[12]);
            $oAnsprechpartnerPfarrer->setBemerkung($data[19]);
            $oAnsprechpartnerPfarrer = handleAnredeNamen($data[19], $oAnsprechpartnerPfarrer);
            $oAnsprechpartnerPfarrer->speichern(true);
            AnsprechpartnerUtilities::neuerGemeindeAnsprechpartner($oAnsprechpartnerPfarrer->getID(), $oGemeinde->getID());
        }
        
        // Orgel
        $oOrgel = new Orgel();
        $oOrgel->setAktiv(1);
        
        $oOrgel->setErbauer($data[21]);
        $oOrgel->setBaujahr($data[22]);
        $oOrgel->setAnmerkung("Opus: " . $data[21] . "\nTermin: " . $data[18] . "\nBemerkung: " . $data[17] . "\nTemperatur: " . $data[20]);
        $oOrgel->setRegisterAnzahl($data[7]);
        $oOrgel->setLetztePflege(date("Y-m-d", strtotime($data[38])));
        $oOrgel->setGemeindeId($oGemeinde->getID());
        
        $oOrgel->setWindladeID(0);
        $oOrgel->setRegistertrakturID(0);
        $oOrgel->setKoppelID(0);
        $oOrgel->setSpieltrakturID(0);
        $oOrgel->setZyklus("0");
        
        $oOrgel->speichern(true);
        
        // Wartung
        if ($data[38] != "") {
            $oWartung = new Wartung();
            $oWartung->setOrgelId($oOrgel->getId());
            $oWartung->setDatum(date("Y-m-d", strtotime($data[38])));
            $oWartung->setBemerkung($data[5]);
            $oWartung->speichern();
        }
    }
    fclose($handle);
}

function handleAdresse(Adresse $oAdresse, $pStrasse)
{
    $oAdresse->setLand("Deutschland");
    if ($pStrasse != "") {
        $parts = explode(" ", $pStrasse);
        $last = $parts[count($parts) - 1];
        if (is_numeric($last)) {
            // echo "Strasse: " . $pStrasse . " HausNr: " . $last."<br/>";
            $oAdresse->setStrasse(trim(str_replace($last, "", $pStrasse)));
            $oAdresse->setHausnummer(trim($last));
        } else {
            $oAdresse->setStrasse($pStrasse);
        }
    } else {
        $oAdresse->setStrasse("");
    }
}

/**
 *
 * @param
 *            data
 * @param
 *            oAnsprechpartnerOrganist
 */
function handleAnredeNamen($data, $oAnsprechpartnerOrganist)
{
    if ($data != "") {
        $parts = explode(" ", $data);
        $count = count($parts);
        $first = $parts[0];
        if ($count > 0) {
            if ($first == "Hr" || $first == "Herr" || $first == "H." || $first == "Hr." ) {
                $oAnsprechpartnerOrganist->setAnrede("Herr");
                $oAnsprechpartnerOrganist->setNachname(str_replace(",", "", $parts[1]));
            } else if ($first == "Fr" || $first == "Frau" || $first == "Fr.") {
                $oAnsprechpartnerOrganist->setAnrede("Frau");
                $oAnsprechpartnerOrganist->setNachname(str_replace(",", "", $parts[1]));
            } else if ($first == "Pfr." || $first == "Pastor") {
                $oAnsprechpartnerOrganist->setAnrede("Herr");
                $oAnsprechpartnerOrganist->setNachname(str_replace(",", "", $parts[1]));
            } else if ($first == "Diakon") {
                $oAnsprechpartnerOrganist->setAnrede("Herr");
                $oAnsprechpartnerOrganist->setNachname(str_replace(",", "", $parts[1]));
            } else {
                //
            }
        } else {
            echo "count";
        }
    } else {
        echo "empty";
    }
    return $oAnsprechpartnerOrganist;
}