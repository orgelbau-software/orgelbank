
<?php
include "../../conf/config.inc.php";

$db = DB::getInstance();
$db->connect();
$sql = array();
$sql[] = "TRUNCATE TABLE benutzer;";
$sql[] = "TRUNCATE TABLE adresse;";
$sql[] = "TRUNCATE TABLE ansprechpartner;";
$sql[] = "TRUNCATE TABLE disposition;";
$sql[] = "TRUNCATE TABLE gemeinde;";
$sql[] = "TRUNCATE TABLE orgel;";
$sql[] = "TRUNCATE TABLE gemeindeansprechpartner;";
foreach ($sql as $key => $val) {
    echo $val . "<br>";
    $db->NonSelectQuery($val);
}

error_reporting(E_ALL);
$filehandle = file_get_contents("Kunden.xml");
$kunden = new SimpleXMLElement($filehandle);

// <Kunden>
// <KundenNr>1</KundenNr>
// <PLZ_K>60327</PLZ_K>
// <Ort_K>Frankfurt</Ort_K>
// <Strasse_K>Hohenstaufenstr.30</Strasse_K>
// <Kirche_K>Matth�uskirche evang.</Kirche_K>
// <Vorwahl_K>069</Vorwahl_K>
// <Telefon_K>74 80 69</Telefon_K>
// <Vorwahl_Kfax>069</Vorwahl_Kfax>
// <Telefax_K>97 40 96 01</Telefax_K>
// <Filialgemeinde>0</Filialgemeinde>
// <Anrede_B>Frau</Anrede_B>
// <Buero>Hofmann</Buero>
// <Manuale>III</Manuale>
// <Umfang_1>C - g&apos;&apos;&apos;</Umfang_1>
// <Pedal>paralel</Pedal>
// <Umfang_2>C - f&apos;</Umfang_2>
// <Baujahr>1956</Baujahr>
// <Erbauer>E.F.Walcker, opus 3523</Erbauer>
// <Registerzahl>47 + 5</Registerzahl>
// <Windladen>mech.Schleifladen, Pesal elektrisch</Windladen>
// <Traktur>mechanisch</Traktur>
// <Registratur>elektropneumatisch</Registratur>
// <Wartungsvertrag>1</Wartungsvertrag>
// <ReEmpName>Evang. Regilnalverband Bauabteilung</ReEmpName>
// <PLZ_Re>60311</PLZ_Re>
// <Ort_Re>Frankfurt</Ort_Re>
// <Strasse_Re>Kurt Schumacher Str.23</Strasse_Re>
// <Vorwahl_Re>069</Vorwahl_Re>
// <Telefon_Re>21 65 12 73</Telefon_Re>
// <KirchenDName>Schmitt Hr.</KirchenDName>
// <Vorwahl_KiDi>069</Vorwahl_KiDi>
// <Telefon_KiDi>74 56 79</Telefon_KiDi>
// <SachVName>Bauermann Klaus</SachVName>
// <PLZ_SachV>60389</PLZ_SachV>
// <Ort_SachV>Frankfurt</Ort_SachV>
// <Strasse_SachV>Erlenbacher Str.12</Strasse_SachV>
// <Vorwahl_SachV>069</Vorwahl_SachV>
// <Telefon_SachV>45 24 58</Telefon_SachV>
// <Vorwahl_SachVfax>069</Vorwahl_SachVfax>
// <Telefax_SachV>24 77 19 40</Telefax_SachV>
// <OrgnistName>Blum Freia</OrgnistName>
// <Vorwahl_Org>069</Vorwahl_Org>
// <Telefon_Org>72 95 61</Telefon_Org>
// <Vorwahl_Mobil_Org>0177</Vorwahl_Mobil_Org>
// <Mobiltelefon_Org>33 35 510</Mobiltelefon_Org>
// <Telefax_Org>dt o.</Telefax_Org>
// <Sonstiges>128, 1,5 St</Sonstiges>
// </Kunden>

// $allElements = array();

// foreach ($kunden->children() as $child) {
// foreach($child as $key => $val) {
// // echo $key."-".$val."<br>";
// $allElements[$key] = "x";
// }
// }
// echo "<pre>";
// print_r($allElements);
// die("foo");

// Firma
$oFirma = new Ansprechpartner();
$oFirma->setAktiv(1);
$oFirma->setNachname("Orgelbau Graser");
$oFirma->getAdresse()->setStrasse("Im Sand");
$oFirma->getAdresse()->setHausnummer("36");
$oFirma->getAdresse()->setPlz("67376");
$oFirma->getAdresse()->setOrt("Harthausen");
$oFirma->getAdresse()->setGeoStatus(IGeolocationConstants::OK);
$oFirma->getAdresse()->setLat("49.2942944");
$oFirma->getAdresse()->setLng("8.3539526");
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
$oSWA->setPasswort(md5(MYSQL_PASS));
$oSWA->speichern(false);

$oMGraser = new Benutzer();
$oMGraser->setBenutzername("mgraser");
$oMGraser->setVorname("Markus");
$oMGraser->setNachname("Graser");
$oMGraser->setBenutzerlevel(10);
$oMGraser->setAktiviert(1);
$oMGraser->setGeloescht(0);
$oMGraser->setPasswort(md5("Gedact"));
$oMGraser->speichern(false);

$oMGraser = new Benutzer();
$oMGraser->setBenutzername("rgraser");
$oMGraser->setVorname("Renate");
$oMGraser->setNachname("Graser");
$oMGraser->setBenutzerlevel(10);
$oMGraser->setAktiviert(1);
$oMGraser->setGeloescht(0);
$oMGraser->setPasswort(md5("Principal"));
$oMGraser->speichern(false);


$alleAnsprechpartnerPfarrer = array();
$alleAnsprechpartnerSV = array();
$alleAnsprechpartnerOrganist = array();
$alleKirchendiener = array();

$windladen = Constant::getWindladen();
$koppeln = Constant::getKoppeln();
$register = Constant::getRegisterTrakturen();
$spiel = Constant::getSpieltrakturen();

foreach ($kunden->Kunden as $kunde) {
    $oGemeinde = new Gemeinde();
    
    $kirche = $kunde->Kirche_K;
    $kirche = strtoupper(substr($kunde->Kirche_K, 0, 1)) . substr($kunde->Kirche_K, 1);
    
    // Ort nciht vorweg, sind bei Ansprechpartnerverwaltung bl�d aus
//     $kirche = $kunde->Ort_K.", ".$kirche;
    
    $oGemeinde->setKirche($kirche);
    $oGemeinde->setAktiv(1);
    
    if (strpos(strtolower($kirche), "ev.") !== false || strpos(strtolower($kirche), "evang") !== false) {
        $oGemeinde->setKID(1); // Evangelisch
    } elseif (strpos(strtolower($kirche), "kath.") !== false) {
        $oGemeinde->setKID(2); // Katholisch
    } else {
        $oGemeinde->setKID(3); // Sonstiges
    }
    
    $oGemeinde->getKircheAdresse()->setType(Adresse::TYPE_KIRCHE);
    $oGemeinde->getKircheAdresse()->setPlz($kunde->PLZ_K);
    $oGemeinde->getKircheAdresse()->setOrt($kunde->Ort_K);
    $oGemeinde->getKircheAdresse()->setLand("Deutschland");
    handleAdresse($oGemeinde->getKircheAdresse(), $kunde->Strasse_K);
    
    // $oGemeinde->setRGemeinde($kunde->ReEmpName);
    $oGemeinde->setRAnschrift($kunde->ReEmpName);
    $oGemeinde->getRechnungAdresse()->setType(Adresse::TYPE_RECHNUNG);
    $oGemeinde->getRechnungAdresse()->setPlz($kunde->PLZ_Re);
    $oGemeinde->getRechnungAdresse()->setOrt($kunde->Ort_Re);
    $oGemeinde->getRechnungAdresse()->setLand("Deutschland");
    handleAdresse($oGemeinde->getRechnungAdresse(), $kunde->Strasse_Re);
    
    $oGemeinde->speichern(true);
    
    // Gemeinde
    $oAnsprechpartnerKirche = new Ansprechpartner();
    $oAnsprechpartnerKirche->setTelefon(kombiniereNummern($kunde->Vorwahl_K, $kunde->Telefon_K));
    $oAnsprechpartnerKirche->setFax(kombiniereNummern($kunde->Vorwahl_Kfax, $kunde->Telefax_K));
    $oAnsprechpartnerKirche->setMobil(kombiniereNummern($kunde->Vorwahl_Mobil_K, $kunde->Mobiltelefon_K));
    $oAnsprechpartnerKirche->setAnrede($kunde->Anrede_B);
    $oAnsprechpartnerKirche->setNachname($kunde->Buero);
    $oAnsprechpartnerKirche->setEmail($kunde->EMail_K);
    $oAnsprechpartnerKirche->setFunktion("Gemeinde");
    
    // Rechnung
    $oAnsprechpartnerRechnung = new Ansprechpartner();
    $oAnsprechpartnerRechnung->setAktiv(1);
    $oAnsprechpartnerRechnung->setTelefon(kombiniereNummern($kunde->Vorwahl_Re, $kunde->Telefon_Re));
    $oAnsprechpartnerRechnung->setFax(kombiniereNummern($kunde->Vorwahl_Refax, $kunde->Telefax_Re));
    $oAnsprechpartnerRechnung->setMobil(kombiniereNummern($kunde->Vorwahl_Mobil_Re, $kunde->Mobiltelefon_Re));
    $oAnsprechpartnerRechnung->setNachname($kunde->ReEmpName);
    $oAnsprechpartnerKirche->setAnrede("keine");
    $oAnsprechpartnerKirche->setFunktion("Rechnung");
    $oAnsprechpartnerKirche->speichern(true);
    
    // Kirchendiener
    if ($kunde->KirchenDName != "") {
        if (! isset($alleKirchendiener[$kunde->KirchenDName . ""])) {
            $oAnsprechpartnerDiener = new Ansprechpartner();
            $oAnsprechpartnerDiener->setAktiv(1);
            $oAnsprechpartnerDiener->setNachname($kunde->KirchenDName);
            $oAnsprechpartnerDiener->getAdresse()->setType(Adresse::TYPE_ANSPRECHPARTNER);
            $oAnsprechpartnerDiener->getAdresse()->setPlz($kunde->PLZ_KiDi);
            $oAnsprechpartnerDiener->getAdresse()->setOrt($kunde->Ort_KiDi);
            handleAdresse($oAnsprechpartnerDiener->getAdresse(), $kunde->Strasse_KiDi);
            $oAnsprechpartnerDiener->setTelefon(kombiniereNummern($kunde->Vorwahl_KiDi, $kunde->Telefon_KiDi));
            $oAnsprechpartnerDiener->setFax(kombiniereNummern($kunde->Vorwahl_KiDifax, $kunde->Telefax_Re));
            $oAnsprechpartnerDiener->setMobil(kombiniereNummern($kunde->Vorwahl_Mobil_KiDi, $kunde->Mobiltelefon_KiDi));
            // $oAnsprechpartnerDiener->setAnrede("keine");
            $oAnsprechpartnerDiener->setFunktion("Kirchendiener");
            handleAnredeNamen($oAnsprechpartnerDiener);
            handleNamensteilung($oAnsprechpartnerDiener);
            $oAnsprechpartnerDiener->speichern(true);
            
            $alleAnsprechpartnerSV[$kunde->KirchenDName . ""] = $oAnsprechpartnerDiener->getID();
            AnsprechpartnerUtilities::neuerGemeindeAnsprechpartner($oAnsprechpartnerDiener->getID(), $oGemeinde->getID());
        } else {
            AnsprechpartnerUtilities::neuerGemeindeAnsprechpartner($alleKirchendiener[$kunde->KirchenDName . ""], $oGemeinde->getID());
            echo "Doppelter Kontakt KiDi: " . $kunde->KirchenDName . "<br>";
        }
    }
    // Sachverstaendige
    if ($kunde->SachVName != "") {
        if (! isset($alleAnsprechpartnerSV[$kunde->SachVName . ""])) {
            $oAnsprechpartnerSV = new Ansprechpartner();
            $oAnsprechpartnerSV->setAktiv(1);
            $oAnsprechpartnerSV->setNachname($kunde->SachVName);
            $oAnsprechpartnerSV->getAdresse()->setType(Adresse::TYPE_ANSPRECHPARTNER);
            $oAnsprechpartnerSV->getAdresse()->setPlz($kunde->PLZ_SachV);
            $oAnsprechpartnerSV->getAdresse()->setOrt($kunde->Ort_SachV);
            handleAdresse($oAnsprechpartnerSV->getAdresse(), $kunde->Strasse_SachV);
            $oAnsprechpartnerSV->setTelefon(kombiniereNummern($kunde->Vorwahl_SachV, $kunde->Telefon_SachV));
            $oAnsprechpartnerSV->setFax(kombiniereNummern($kunde->Vorwahl_SachVfax, $kunde->Telefax_Re));
            $oAnsprechpartnerSV->setMobil(kombiniereNummern($kunde->Vorwahl_Mobil_SachV, $kunde->Mobiltelefon_SachV));
            // $oAnsprechpartnerDiener->setAnrede("keine");
            $oAnsprechpartnerSV->setFunktion(utf8_encode("Sachverst�ndiger"));
            handleAnredeNamen($oAnsprechpartnerSV);
            handleNamensteilung($oAnsprechpartnerSV);
            $oAnsprechpartnerSV->speichern(true);
            $alleAnsprechpartnerSV[$kunde->SachVName . ""] = $oAnsprechpartnerSV->getID();
            AnsprechpartnerUtilities::neuerGemeindeAnsprechpartner($oAnsprechpartnerSV->getID(), $oGemeinde->getID());
        } else {
            AnsprechpartnerUtilities::neuerGemeindeAnsprechpartner($alleAnsprechpartnerSV[$kunde->SachVName . ""], $oGemeinde->getID());
            echo "Doppelter Kontakt SachV: " . $kunde->SachVName . "<br>";
        }
    }
    // Organist
    if ($kunde->OrgnistName != "") {
        if (! isset($alleAnsprechpartnerOrganist[$kunde->OrgnistName . ""])) {
            $oAnsprechpartnerOrganist = new Ansprechpartner();
            $oAnsprechpartnerOrganist->setAktiv(1);
            $oAnsprechpartnerOrganist->setNachname($kunde->OrgnistName);
            $oAnsprechpartnerOrganist->getAdresse()->setType(Adresse::TYPE_ANSPRECHPARTNER);
            $oAnsprechpartnerOrganist->getAdresse()->setPlz($kunde->PLZ_Org);
            $oAnsprechpartnerOrganist->getAdresse()->setOrt($kunde->Ort_Org);
            handleAdresse($oAnsprechpartnerOrganist->getAdresse(), $kunde->Strasse_Org);
            $oAnsprechpartnerOrganist->setTelefon(kombiniereNummern($kunde->Vorwahl_Org, $kunde->Telefon_Org));
            $oAnsprechpartnerOrganist->setFax(kombiniereNummern($kunde->Vorwahl_Orgfax, $kunde->Telefax_Org));
            $oAnsprechpartnerOrganist->setMobil(kombiniereNummern($kunde->Vorwahl_Mobil_Org, $kunde->Mobiltelefon_Org));
            // $oAnsprechpartnerDiener->setAnrede("keine");
            $oAnsprechpartnerOrganist->setFunktion("Organist");
            handleAnredeNamen($oAnsprechpartnerOrganist);
            handleNamensteilung($oAnsprechpartnerOrganist);
            $oAnsprechpartnerOrganist->speichern(true);
            $alleAnsprechpartnerOrganist[$kunde->OrgnistName . ""] = $oAnsprechpartnerOrganist->getID();
            AnsprechpartnerUtilities::neuerGemeindeAnsprechpartner($oAnsprechpartnerOrganist->getID(), $oGemeinde->getID());
        } else {
            AnsprechpartnerUtilities::neuerGemeindeAnsprechpartner($alleAnsprechpartnerOrganist[$kunde->OrgnistName . ""], $oGemeinde->getID());
            echo "Doppelter Kontakt Organist: " . $kunde->OrgnistName . "<br>";
        }
    }
    // Pfarrer
    if ($kunde->Pfarrer != "") {
        if (! isset($alleAnsprechpartnerPfarrer[$kunde->Pfarrer . ""])) {
            $oAnsprechpartnerPfarrer = new Ansprechpartner();
            $oAnsprechpartnerPfarrer->setNachname($kunde->Pfarrer);
            $oAnsprechpartnerPfarrer->setFunktion("Pfarrer");
            $oAnsprechpartnerPfarrer->getAdresse()->setStrasse("");
            $oAnsprechpartnerPfarrer->setAktiv(1);
            handleAnredeNamen($oAnsprechpartnerPfarrer);
            handleNamensteilung($oAnsprechpartnerPfarrer);
            $oAnsprechpartnerPfarrer->speichern(true);
            $alleAnsprechpartnerPfarrer[$kunde->Pfarrer . ""] = $oAnsprechpartnerPfarrer->getID();
            AnsprechpartnerUtilities::neuerGemeindeAnsprechpartner($oAnsprechpartnerPfarrer->getID(), $oGemeinde->getID());
        } else {
            AnsprechpartnerUtilities::neuerGemeindeAnsprechpartner($alleAnsprechpartnerPfarrer[$kunde->Pfarrer . ""], $oGemeinde->getID());
            echo "Doppelter Kontakt Pfarrer: " . $kunde->Pfarrer . "<br>";
        }
        // echo $oAnsprechpartnerPfarrer->getID();
    }
    
    // Orgel
    $oOrgel = new Orgel();
    $oOrgel->setAktiv(1);
    
    $oOrgel->setErbauer($kunde->Erbauer);
    $oOrgel->setBaujahr($kunde->Baujahr);
    
    $oOrgel->setWindladeID(0);
    $oOrgel->setRegistertrakturID(0);
    $oOrgel->setKoppelID(0);
    $oOrgel->setSpieltrakturID(0);
    
    if ($kunde->Winladen != "") {
        foreach ($windladen as $key => $val) {
            if (strtolower($val) == strtolower($kunde->Winladen)) {
                $oOrgel->setWindladeID($key);
            }
        }
        if ($oOrgel->getWindladeID() == 0) {
            echo "getWindladeID matched nicht: " . $kunde->Winladen . "<br>";
            $oOrgel->setAnmerkung($oOrgel->getAnmerkung() . "Winlade: " . $kunde->Winladen . "\n");
        }
    }
    if ($kunde->Registratur != "") {
        foreach ($register as $key => $val) {
            if (strtolower($val) == strtolower($kunde->Registratur)) {
                $oOrgel->setRegistertrakturID($key);
            }
        }
        if ($oOrgel->getRegistertrakturID() == 0) {
            echo "getRegistertrakturID matched nicht: " . $kunde->Registratur . "<br>";
            $oOrgel->setAnmerkung($oOrgel->getAnmerkung() . "Registertraktur: " . $kunde->Registratur . "\n");
        }
    }
    
    if ($kunde->Traktur != "") {
        foreach ($spiel as $key => $val) {
            if (strtolower($val) == strtolower($kunde->Traktur)) {
                $oOrgel->setSpieltrakturID($key);
            }
        }
        if ($oOrgel->getSpieltrakturID() == 0) {
            echo "getSpieltrakturID matched nicht: " . $kunde->Traktur . "<br>";
            $oOrgel->setAnmerkung($oOrgel->getAnmerkung() . "Spieltraktur: " . $kunde->Traktur . "\n");
        }
    }
    
    $oOrgel->setPflegevertrag($kunde->Wartungsvertrag);
    $oOrgel->setGemeindeId($oGemeinde->getID());
    $oOrgel->setRegisterAnzahl($kunde->Registerzahl);
    
    if (strpos($kunde->Manuale, "I") === 0) {
        $oOrgel->setManual1(1);
        $oOrgel->setGroesseM1($kunde->Umfang_1);
    } else 
        if (strpos($kunde->Manuale, "II") === 0) {
            $oOrgel->setManual1(1);
            $oOrgel->setManual2(1);
            $oOrgel->setGroesseM1($kunde->Umfang_1);
            $oOrgel->setGroesseM2($kunde->Umfang_1);
        } else {
            $oOrgel->setManual1(1);
            $oOrgel->setManual2(1);
            $oOrgel->setManual3(1);
            $oOrgel->setGroesseM1($kunde->Umfang_1);
            $oOrgel->setGroesseM2($kunde->Umfang_1);
            $oOrgel->setGroesseM3($kunde->Umfang_1);
        }
    
    if ($kunde->Umfang_2 != "") {
        $oOrgel->setPedal(1);
    }
    if ($kunde->Pedal != "") {
        $oOrgel->setGroesseM6($kunde->Umfang_2 . " # " . $kunde->Pedal);
    } else {
        $oOrgel->setGroesseM6($kunde->Umfang_2);
    }
    
    if($kunde->Bemerkungen != "") {
        $oOrgel->setAnmerkung($oOrgel->getAnmerkung() . $kunde->Bemerkungen ."\n");
    }
    if($kunde->Registerzahl != "") {
        $oOrgel->setAnmerkung($oOrgel->getAnmerkung() ."Registeranzahl: " . $kunde->Registerzahl . "\n");
    }
    $oOrgel->setZyklus("0");
    
    $oOrgel->speichern();
}
echo "done";

function kombiniereNummern($pVorwahl, $pNummer)
{
    if ($pVorwahl == "" && $pNummer == "") {
        return "";
    } else {
        $vorwahl = trim(str_replace(" ", "", $pVorwahl));
        $nummer = trim(str_replace(" ", "", $pNummer));
        return $vorwahl . "/" . $nummer;
    }
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

function handleNamensteilung(Ansprechpartner $oAnsprechpartner)
{
    if ($oAnsprechpartner->getVorname() == "") {
        if (strpos($oAnsprechpartner->getNachname(), "Pfr.") === 0 || strpos($oAnsprechpartner->getNachname(), "Pfarrer") === 0) {
            if ($oAnsprechpartner->getFunktion() == "Pfarrer") {
                $oAnsprechpartner->setVorname("");
            } else {
                $oAnsprechpartner->setVorname("");
                $oAnsprechpartner->setFunktion("Pfarrer");
            }
            $oAnsprechpartner->setNachname(str_replace("Pfr. ", "", $oAnsprechpartner->getNachname()));
        } else 
            if (substr_count($oAnsprechpartner->getNachname(), " ") == 1) {
                $posSpace = strpos($oAnsprechpartner->getNachname(), " ");
                $vorname = substr($oAnsprechpartner->getNachname(), 0, $posSpace);
                $nachname = substr($oAnsprechpartner->getNachname(), $posSpace + 1);
                $oAnsprechpartner->setVorname($vorname);
                $oAnsprechpartner->setNachname($nachname);
                echo "Vor/Nachname: " . $vorname . " " . $nachname . "<br>";
            } else {
                echo "Keine Namensbehandling: " . $oAnsprechpartner->getNachname() . "<br>";
            }
    }
}

function handleAnredeNamen(Ansprechpartner $pAnsprechpartner)
{
    if (strpos($pAnsprechpartner->getNachname(), "Dr.") !== false || strpos($pAnsprechpartner->getNachname(), "Doktor") !== false) {
        $pAnsprechpartner->setTitel("Dr.");
        $pAnsprechpartner->setNachname(str_replace("Doktor", "", $pAnsprechpartner->getNachname()));
        $pAnsprechpartner->setNachname(trim(str_replace("Dr.", "", $pAnsprechpartner->getNachname())));
    }
    
    if (strpos($pAnsprechpartner->getNachname(), "Frau") !== false || strpos($pAnsprechpartner->getNachname(), "Fr.") !== false) {
        $pAnsprechpartner->setAnrede("Frau");
        $pAnsprechpartner->setNachname(str_replace("Frau", "", $pAnsprechpartner->getNachname()));
        $pAnsprechpartner->setNachname(trim(str_replace("Fr.", "", $pAnsprechpartner->getNachname())));
    } else 
        if (strpos($pAnsprechpartner->getNachname(), "Herr") !== false || strpos($pAnsprechpartner->getNachname(), "Hr.") !== false) {
            $pAnsprechpartner->setAnrede("Herr");
            $pAnsprechpartner->setNachname(str_replace("Herr", "", $pAnsprechpartner->getNachname()));
            $pAnsprechpartner->setNachname(trim(str_replace("Hr.", "", $pAnsprechpartner->getNachname())));
        } else {
            // echo "Weder noch: " . $kunde->Pfarrer . "->" . strpos($oAnsprechpartnerPfarrer->getNachname(), "Herr") . "<br>";
        }
}
?>