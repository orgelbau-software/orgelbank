<?php
include "../../conf/config.inc.php";

$db = DB::getInstance();
$db->connect();
$sql = array();
// $sql[] = "TRUNCATE TABLE benutzer;";
// //$sql[] = "TRUNCATE TABLE adresse;";
// $sql[] = "TRUNCATE TABLE ansprechpartner;";
// $sql[] = "TRUNCATE TABLE disposition;";
// //$sql[] = "TRUNCATE TABLE gemeinde;";
// //$sql[] = "TRUNCATE TABLE orgel;";
// $sql[] = "TRUNCATE TABLE wartung;";
// $sql[] = "TRUNCATE TABLE gemeindeansprechpartner;";
// foreach ($sql as $key => $val) {
// echo $val . "<br>";
// $db->NonSelectQuery($val);
// }

// error_reporting(E_ALL);

// // Firma
// $oFirma = new Ansprechpartner();
// $oFirma->setAktiv(1);
// $oFirma->setNachname("Freiburger Orgelbau");
// $oFirma->getAdresse()->setStrasse("Herrenstraße");
// $oFirma->getAdresse()->setHausnummer("9");
// $oFirma->getAdresse()->setPlz("79232");
// $oFirma->getAdresse()->setOrt("March");
// $oFirma->getAdresse()->setGeoStatus(IGeolocationConstants::OK);
// $oFirma->getAdresse()->setLat("48.0522");
// $oFirma->getAdresse()->setLng("7.7815809");
// $oFirma->setAnrede("keine");
// $oFirma->setFunktion("Firma");
// $oFirma->speichern(true);

// // Benutzer
// $oSWA = new Benutzer();
// $oSWA->setBenutzername("swatermeyer");
// $oSWA->setVorname("Stephan");
// $oSWA->setNachname("Watermeyer");
// $oSWA->setBenutzerlevel(10);
// $oSWA->setAktiviert(1);
// $oSWA->setDemo(1);
// $oSWA->setGeloescht(0);
// $oSWA->setPasswort(md5(MYSQL_PASS));
// $oSWA->speichern(false);

$row = 1;

$exportfile = fopen("export_bente.sql", "w+");
fwrite($exportfile, "DELETE FROM ansprechpartner WHERE a_id > 927;");
fwrite($exportfile, "DELETE FROM adresse WHERE ad_id > 1965;");

if (($handle = fopen("kontakte.original.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
        // echo "<p> $num Felder in Zeile $row: <br /></p>\n";
        
        $entity = new CSVBenteEntity();
        
        $fieldCounter = 0;
        $entity->Anrede = $data[$fieldCounter ++];
        $entity->Vorname = $data[$fieldCounter ++];
        $entity->Weitere_Vornamen = $data[$fieldCounter ++];
        $entity->Nachname = $data[$fieldCounter ++];
        $entity->Suffix = $data[$fieldCounter ++];
        $entity->Firma = $data[$fieldCounter ++];
        $entity->Abteilung = $data[$fieldCounter ++];
        $entity->Position = $data[$fieldCounter ++];
        $entity->Straße_geschäftlich = $data[$fieldCounter ++];
        $entity->Straße_geschäftlich_2 = $data[$fieldCounter ++];
        $entity->Straße_geschäftlich_3 = $data[$fieldCounter ++];
        $entity->Ort_geschäftlich = $data[$fieldCounter ++];
        $entity->Region_geschäftlich = $data[$fieldCounter ++];
        $entity->Postleitzahl_geschäftlich = $data[$fieldCounter ++];
        $entity->LandRegion_geschäftlich = $data[$fieldCounter ++];
        $entity->Straße_privat = $data[$fieldCounter ++];
        $entity->Straße_privat_2 = $data[$fieldCounter ++];
        $entity->Straße_privat_3 = $data[$fieldCounter ++];
        $entity->Ort_privat = $data[$fieldCounter ++];
        // $entity->BundeslandKanton_privat = $data[$fieldCounter ++];
        $fieldCounter ++;
        $entity->Postleitzahl_privat = $data[$fieldCounter ++];
        $entity->LandRegion_privat = $data[$fieldCounter ++];
        $entity->Weitere_Straße = $data[$fieldCounter ++];
        $entity->Weitere_Straße_2 = $data[$fieldCounter ++];
        $entity->Weitere_Straße_3 = $data[$fieldCounter ++];
        $entity->Weiterer_Ort = $data[$fieldCounter ++];
        // $entity->Weiteresr_BundeslandKanton = $data[$fieldCounter ++];
        $fieldCounter ++;
        $entity->Weitere_Postleitzahl = $data[$fieldCounter ++];
        $entity->Weiterese_LandRegion = $data[$fieldCounter ++];
        $entity->Telefon_Assistent = $data[$fieldCounter ++];
        $entity->Fax_geschäftlich = $data[$fieldCounter ++];
        $entity->Telefon_geschäftlich = $data[$fieldCounter ++];
        $entity->Telefon_geschäftlich_2 = $data[$fieldCounter ++];
        // $entity->Rückmeldung = $data[$fieldCounter ++];
        $fieldCounter ++;
        
        // $entity->Autotelefon = $data[$fieldCounter ++];
        $fieldCounter ++;
        
        $entity->Telefon_Firma = $data[$fieldCounter ++];
        $entity->Fax_privat = $data[$fieldCounter ++];
        $entity->Telefon_privat = $data[$fieldCounter ++];
        $entity->Telefon_privat_2 = $data[$fieldCounter ++];
        $entity->ISDN = $data[$fieldCounter ++];
        $entity->Mobiltelefon = $data[$fieldCounter ++];
        $entity->Weiteres_Fax = $data[$fieldCounter ++];
        $entity->Weiteres_Telefon = $data[$fieldCounter ++];
        $entity->Pager = $data[$fieldCounter ++];
        $entity->Haupttelefon = $data[$fieldCounter ++];
        $entity->Mobiltelefon_2 = $data[$fieldCounter ++];
        // $entity->Telefon_für_Hörbehinderte = $data[$fieldCounter ++];
        $fieldCounter ++;
        // $entity->Telex = $data[$fieldCounter ++];
        $fieldCounter ++;
        // $entity->Abrechnungsinformation = $data[$fieldCounter ++];
        $fieldCounter ++;
        // $entity->Assistentin = $data[$fieldCounter ++];
        $fieldCounter ++;
        
        // $entity->Benutzer_1 = $data[$fieldCounter ++];
        $fieldCounter ++;
        // $entity->Benutzer_2 = $data[$fieldCounter ++];
        $fieldCounter ++;
        // $entity->Benutzer_3 = $data[$fieldCounter ++];
        $fieldCounter ++;
        // $entity->Benutzer_4 = $data[$fieldCounter ++];
        $fieldCounter ++;
        $entity->Beruf = $data[$fieldCounter ++];
        $entity->Büro = $data[$fieldCounter ++];
        $entity->EMailAdresse = $data[$fieldCounter ++];
        // $entity->EMailTyp = $data[$fieldCounter ++];
        $fieldCounter ++;
        
        // $entity->EMail_Angezeigter_Name = $data[$fieldCounter ++];
        $fieldCounter ++;
        $entity->EMail_2_Adresse = $data[$fieldCounter ++];
        $entity->EMail_2_Typ = $data[$fieldCounter ++];
        // $entity->EMail_2_Angezeigter_Name = $data[$fieldCounter ++];
        $fieldCounter ++;
        $entity->EMail_3_Adresse = $data[$fieldCounter ++];
        $entity->EMail_3_Typ = $data[$fieldCounter ++];
        // $entity->EMail_3_Angezeigter_Name = $data[$fieldCounter ++];
        $fieldCounter ++;
        
        // $entity->Empfohlen_von = $data[$fieldCounter ++];
        $fieldCounter ++;
        
        $entity->Geburtstag = $data[$fieldCounter ++];
        // $entity->Geschlecht = $data[$fieldCounter ++];
        $fieldCounter ++;
        
        // $entity->Hobby = $data[$fieldCounter ++];
        $fieldCounter ++;
        $entity->Initialen = $data[$fieldCounter ++];
        // $entity->Internet_FreiGebucht = $data[$fieldCounter ++];
        $fieldCounter ++;
        $entity->Jahrestag = $data[$fieldCounter ++];
        $entity->Kategorien = $data[$fieldCounter ++];
        // $entity->Kinder = $data[$fieldCounter ++];
        $fieldCounter ++;
        // $entity->Konto = $data[$fieldCounter ++];
        $fieldCounter ++;
        // $entity->Name_desr_Vorgesetzten = $data[$fieldCounter ++];
        $fieldCounter ++;
        $entity->Notizen = $data[$fieldCounter ++];
        $entity->Organisationsnr = $data[$fieldCounter ++];
        $entity->Ort = $data[$fieldCounter ++];
        // $entity->Partnerin = $data[$fieldCounter ++];
        $fieldCounter ++;
        
        // $entity->Postfach_geschäftlich = $data[$fieldCounter ++];
        $fieldCounter ++;
        
        // $entity->Postfach_privat = $data[$fieldCounter ++];
        $fieldCounter ++;
        
        $entity->Priorität = $data[$fieldCounter ++];
        $entity->Privat = $data[$fieldCounter ++];
        // $entity->Reisekilometer = $data[$fieldCounter ++];
        $fieldCounter ++;
        // $entity->Sozialversicherungsnr = $data[$fieldCounter ++];
        $fieldCounter ++;
        // $entity->Sprache = $data[$fieldCounter ++];
        $fieldCounter ++;
        // $entity->Stichwörter = $data[$fieldCounter ++];
        $fieldCounter ++;
        
        $entity->Vertraulichkeit = $data[$fieldCounter ++];
        if ($num > 89) {
            // $entity->Verzeichnisserver = $data[$fieldCounter ++];
            $fieldCounter ++;
        }
        if ($num > 90) {
            $entity->Webseite = $data[$fieldCounter ++];
        }
        if ($num > 91) {
            // $entity->Weiteres_Postfach = $data[$fieldCounter ++];
            $fieldCounter ++;
        }
        
        // print_r($entity);
        
        $ansprechpartner = new Ansprechpartner();
        $ansprechpartner->setAktiv(1);
        $ansprechpartner->setAndere($entity->Telefon_geschäftlich);
        $entity->Telefon_geschäftlich = "";
        $ansprechpartner->setBemerkung($entity->Notizen);
        $entity->Notizen = "";
        $ansprechpartner->setEmail($entity->EMailAdresse);
        $entity->EMailAdresse = "";
        $ansprechpartner->setFax($entity->Fax_geschäftlich);
        $entity->Fax_geschäftlich = "";
        $ansprechpartner->setFunktion($entity->Position);
        $entity->Position = "";
        $ansprechpartner->setMobil($entity->Mobiltelefon);
        $entity->Mobiltelefon = "";
        $ansprechpartner->setNachname($entity->Nachname);
        $entity->Nachname = "";
        $ansprechpartner->setTelefon($entity->Telefon_geschäftlich);
        $entity->Telefon_geschäftlich = "";
        $ansprechpartner->setTitel($entity->Anrede);
        $entity->Anrede = "";
        $ansprechpartner->setVorname($entity->Vorname);
        $entity->Vorname = "";
        $ansprechpartner->setFirma($entity->Firma);
        $entity->Firma = "";
        $ansprechpartner->setWebseite($entity->Webseite);
        $entity->Webseite = "";
        
        $ansprechpartner->getAdresse()->setStrasse($entity->Straße_geschäftlich);
        $entity->Straße_geschäftlich = "";
        $ansprechpartner->getAdresse()->setPlz($entity->Postleitzahl_geschäftlich);
        $entity->Postleitzahl_geschäftlich = "";
        $ansprechpartner->getAdresse()->setOrt($entity->Ort_geschäftlich);
        $entity->Ort_geschäftlich = "";
        $ansprechpartner->getAdresse()->setLand($entity->LandRegion_geschäftlich);
        $entity->LandRegion_geschäftlich = "";
        
        $bemerkung = $entity->createAttributeList();
        $bemerkung .= "---\r\n";
        
        $bemerkung .= $ansprechpartner->getBemerkung();
        $ansprechpartner->setBemerkung($bemerkung);
        
        echo $bemerkung;
        
        fwrite($exportfile, $ansprechpartner->export());
        fwrite($exportfile, "\r\n\r\n");
        $row ++;
    }
    fclose($handle);
}

fclose($exportfile);

class CSVBenteEntity
{

    public $Anrede;

    public $Vorname;

    public $Weitere_Vornamen;

    public $Nachname;

    public $Suffix;

    public $Firma;

    public $Abteilung;

    public $Position;

    public $Straße_geschäftlich;

    public $Straße_geschäftlich_2;

    public $Straße_geschäftlich_3;

    public $Ort_geschäftlich;

    public $Region_geschäftlich;

    public $Postleitzahl_geschäftlich;

    public $LandRegion_geschäftlich;

    public $Straße_privat;

    public $Straße_privat_2;

    public $Straße_privat_3;

    public $Ort_privat;

    public $BundeslandKanton_privat;

    public $Postleitzahl_privat;

    public $LandRegion_privat;

    public $Weitere_Straße;

    public $Weitere_Straße_2;

    public $Weitere_Straße_3;

    public $Weiterer_Ort;

    // public $Weiteresr_BundeslandKanton;
    public $Weitere_Postleitzahl;

    public $Weiterese_LandRegion;

    public $Telefon_Assistent;

    public $Fax_geschäftlich;

    public $Telefon_geschäftlich;

    public $Telefon_geschäftlich_2;

    // public $Rückmeldung;
    
    // public $Autotelefon;
    public $Telefon_Firma;

    public $Fax_privat;

    public $Telefon_privat;

    public $Telefon_privat_2;

    public $ISDN;

    public $Mobiltelefon;

    public $Weiteres_Fax;

    public $Weiteres_Telefon;

    public $Pager;

    public $Haupttelefon;

    public $Mobiltelefon_2;

    // public $Telefon_für_Hörbehinderte;
    
    // public $Telex;
    // public $Abrechnungsinformation;
    
    // public $Assistentin;
    
    // public $Benutzer_1;
    
    // public $Benutzer_2;
    
    // public $Benutzer_3;
    
    // Fpublic $Benutzer_4;
    public $Beruf;

    public $Büro;

    public $EMailAdresse;

    // public $EMailTyp;
//     public $EMail_Angezeigter_Name;

    public $EMail_2_Adresse;

//     public $EMail_2_Typ;

//     public $EMail_2_Angezeigter_Name;

    public $EMail_3_Adresse;

//     public $EMail_3_Typ;

//     public $EMail_3_Angezeigter_Name;

    // public $Empfohlen_von;
    public $Geburtstag;

    // public $Geschlecht;
    
    // public $Hobby;
    public $Initialen;

    // public $Internet_FreiGebucht;
    public $Jahrestag;

    public $Kategorien;

    // public $Kinder;
    
    // public $Konto;
    
    // public $Name_desr_Vorgesetzten;
    public $Notizen;

    public $Organisationsnr;

    public $Ort;

    // public $Partnerin;
    // public $Postfach_geschäftlich;
    
    // public $Postfach_privat;
    public $Priorität;

    public $Privat;

    // public $Reisekilometer;
    
    // public $Sozialversicherungsnr;
    
    // public $Sprache;
    
    // public $Stichwörter;
    public $Vertraulichkeit;

    // public $Verzeichnisserver;
    public $Webseite;

    // public $Weiteres_Postfach;
    public function __toString()
    {
        $retVal = "CSVBenteEntity [";
        $retVal = $this->Anrede . ", ";
        $retVal = $this->Vorname . ", ";
        $retVal .= "]";
        return $retVal;
    }

    public function createAttributeList()
    {
        $content = "";
        if ("" != $this->Anrede) {
            $content .= "Anrede: " . $this->Anrede . "\r\n";
        }
        if ("" != $this->Vorname) {
            $content .= "Vorname: " . $this->Vorname . "\r\n";
        }
        if ("" != $this->Weitere_Vornamen) {
            $content .= "Weitere_Vornamen: " . $this->Weitere_Vornamen . "\r\n";
        }
        if ("" != $this->Nachname) {
            $content .= "Nachname: " . $this->Nachname . "\r\n";
        }
        if ("" != $this->Suffix) {
            $content .= "Suffix: " . $this->Suffix . "\r\n";
        }
        if ("" != $this->Firma) {
            $content .= "Firma: " . $this->Firma . "\r\n";
        }
        if ("" != $this->Abteilung) {
            $content .= "Abteilung: " . $this->Abteilung . "\r\n";
        }
        if ("" != $this->Position) {
            $content .= "Position: " . $this->Position . "\r\n";
        }
        if ("" != $this->Straße_geschäftlich) {
            $content .= "Straße_geschäftlich: " . $this->Straße_geschäftlich . "\r\n";
        }
        if ("" != $this->Straße_geschäftlich_2) {
            $content .= "Straße_geschäftlich_2: " . $this->Straße_geschäftlich_2 . "\r\n";
        }
        if ("" != $this->Straße_geschäftlich_3) {
            $content .= "Straße_geschäftlich_3: " . $this->Straße_geschäftlich_3 . "\r\n";
        }
        if ("" != $this->Ort_geschäftlich) {
            $content .= "Ort_geschäftlich: " . $this->Ort_geschäftlich . "\r\n";
        }
        if ("" != $this->Region_geschäftlich) {
            $content .= "Region_geschäftlich: " . $this->Region_geschäftlich . "\r\n";
        }
        if ("" != $this->Postleitzahl_geschäftlich) {
            $content .= "Postleitzahl_geschäftlich: " . $this->Postleitzahl_geschäftlich . "\r\n";
        }
        if ("" != $this->LandRegion_geschäftlich) {
            $content .= "LandRegion_geschäftlich: " . $this->LandRegion_geschäftlich . "\r\n";
        }
        if ("" != $this->Straße_privat) {
            $content .= "Straße_privat: " . $this->Straße_privat . "\r\n";
        }
        if ("" != $this->Straße_privat_2) {
            $content .= "Straße_privat_2: " . $this->Straße_privat_2 . "\r\n";
        }
        if ("" != $this->Straße_privat_3) {
            $content .= "Straße_privat_3: " . $this->Straße_privat_3 . "\r\n";
        }
        if ("" != $this->Ort_privat) {
            $content .= "Ort_privat: " . $this->Ort_privat . "\r\n";
        }
        if ("" != $this->BundeslandKanton_privat) {
            $content .= "BundeslandKanton_privat: " . $this->BundeslandKanton_privat . "\r\n";
        }
        if ("" != $this->Postleitzahl_privat) {
            $content .= "Postleitzahl_privat: " . $this->Postleitzahl_privat . "\r\n";
        }
        if ("" != $this->LandRegion_privat) {
            $content .= "LandRegion_privat: " . $this->LandRegion_privat . "\r\n";
        }
        if ("" != $this->Weitere_Straße) {
            $content .= "Weitere_Straße: " . $this->Weitere_Straße . "\r\n";
        }
        if ("" != $this->Weitere_Straße_2) {
            $content .= "Weitere_Straße_2: " . $this->Weitere_Straße_2 . "\r\n";
        }
        if ("" != $this->Weitere_Straße_3) {
            $content .= "Weitere_Straße_3: " . $this->Weitere_Straße_3 . "\r\n";
        }
        if ("" != $this->Weiterer_Ort) {
            $content .= "Weiterer_Ort: " . $this->Weiterer_Ort . "\r\n";
        }
        // if ("" != $this->Weiteresr_BundeslandKanton) {
        // $content .= ": " . $this->Weiteresr_BundeslandKanton . "\r\n";
        // }
        if ("" != $this->Weitere_Postleitzahl) {
            $content .= "Weitere_Postleitzahl: " . $this->Weitere_Postleitzahl . "\r\n";
        }
        if ("" != $this->Weiterese_LandRegion) {
            $content .= "Weiterese_LandRegion: " . $this->Weiterese_LandRegion . "\r\n";
        }
        if ("" != $this->Telefon_Assistent) {
            $content .= "Telefon_Assistent: " . $this->Telefon_Assistent . "\r\n";
        }
        if ("" != $this->Fax_geschäftlich) {
            $content .= "Fax_geschäftlich: " . $this->Fax_geschäftlich . "\r\n";
        }
        if ("" != $this->Telefon_geschäftlich) {
            $content .= "Telefon_geschäftlich: " . $this->Telefon_geschäftlich . "\r\n";
        }
        if ("" != $this->Telefon_geschäftlich_2) {
            $content .= "Telefon_geschäftlich_2: " . $this->Telefon_geschäftlich_2 . "\r\n";
        }
        // if ("" != $this->Rückmeldung) {
        // $content .= ": " . $this->Rückmeldung . "\r\n";
        // }
        // if ("" != $this->Autotelefon) {
        // $content .= ": " . $this->Autotelefon . "\r\n";
        // }
        if ("" != $this->Telefon_Firma) {
            $content .= "Telefon_Firma: " . $this->Telefon_Firma . "\r\n";
        }
        if ("" != $this->Fax_privat) {
            $content .= "Fax_privat: " . $this->Fax_privat . "\r\n";
        }
        if ("" != $this->Telefon_privat) {
            $content .= "Telefon_privat: " . $this->Telefon_privat . "\r\n";
        }
        if ("" != $this->Telefon_privat_2) {
            $content .= "Telefon_privat_2: " . $this->Telefon_privat_2 . "\r\n";
        }
        if ("" != $this->ISDN) {
            $content .= "ISDN: " . $this->ISDN . "\r\n";
        }
        if ("" != $this->Mobiltelefon) {
            $content .= "Mobiltelefon: " . $this->Mobiltelefon . "\r\n";
        }
        if ("" != $this->Weiteres_Fax) {
            $content .= "Weiteres_Fax: " . $this->Weiteres_Fax . "\r\n";
        }
        if ("" != $this->Weiteres_Telefon) {
            $content .= "Weiteres_Telefon: " . $this->Weiteres_Telefon . "\r\n";
        }
        if ("" != $this->Pager) {
            $content .= "Pager: " . $this->Pager . "\r\n";
        }
        if ("" != $this->Haupttelefon) {
            $content .= "Haupttelefon: " . $this->Haupttelefon . "\r\n";
        }
        if ("" != $this->Mobiltelefon_2) {
            $content .= "Mobiltelefon_2: " . $this->Mobiltelefon_2 . "\r\n";
        }
        // if ("" != $this->Telefon_für_Hörbehinderte) {
        // $content .= ": " . $this->Telefon_für_Hörbehinderte . "\r\n";
        // }
        // if ("" != $this->Telex) {
        // $content .= ": " . $this->Telex . "\r\n";
        // }
        // if ("" != $this->Abrechnungsinformation) {
        // $content .= ": " . $this->Abrechnungsinformation . "\r\n";
        // }
        // if ("" != $this->Assistentin) {
        // $content .= ": " . $this->Assistentin . "\r\n";
        // }
        if ("" != $this->Beruf) {
            $content .= "Beruf: " . $this->Beruf . "\r\n";
        }
        if ("" != $this->Büro) {
            $content .= "Büro: " . $this->Büro . "\r\n";
        }
        if ("" != $this->EMailAdresse) {
            $content .= "EMailAdresse: " . $this->EMailAdresse . "\r\n";
        }
        // if ("" != $this->EMailTyp) {
        // $content .= ": " . $this->EMailTyp . "\r\n";
        // }
        // if ("" != $this->EMail_Angezeigter_Name) {
        // $content .= "EMail_Angezeigter_Name: " . $this->EMail_Angezeigter_Name . "\r\n";
        // }
        if ("" != $this->EMail_2_Adresse) {
            $content .= "EMail_2_Adresse: " . $this->EMail_2_Adresse . "\r\n";
        }
//         if ("" != $this->EMail_2_Typ) {
//             $content .= "EMail_2_Typ: " . $this->EMail_2_Typ . "\r\n";
//         }
        // if ("" != $this->EMail_2_Angezeigter_Name) {
        // $content .= "EMail_2_Angezeigter_Name: " . $this->EMail_2_Angezeigter_Name . "\r\n";
        // }
        if ("" != $this->EMail_3_Adresse) {
            $content .= "EMail_3_Adresse: " . $this->EMail_3_Adresse . "\r\n";
        }
//         if ("" != $this->EMail_3_Typ) {
//             $content .= "EMail_3_Typ: " . $this->EMail_3_Typ . "\r\n";
//         }
        // if ("" != $this->EMail_3_Angezeigter_Name) {
        // $content .= "EMail_3_Angezeigter_Name: " . $this->EMail_3_Angezeigter_Name . "\r\n";
        // }
        // if ("" != $this->Empfohlen_von) {
        // $content .= ": " . $this->Empfohlen_von . "\r\n";
        // }
        if ("0.0.00" != $this->Geburtstag) {
            $content .= "Geburtstag: " . $this->Geburtstag . "\r\n";
        }
        // if ("" != $this->Geschlecht) {
        // $content .= ": " . $this->Geschlecht . "\r\n";
        // }
        // if ("" != $this->Hobby) {
        // $content .= ": " . $this->Hobby . "\r\n";
        // }
        if ("" != $this->Initialen) {
            $content .= "Initialen: " . $this->Initialen . "\r\n";
        }
        // if ("" != $this->Internet_FreiGebucht) {
        // $content .= ": " . $this->Internet_FreiGebucht . "\r\n";
        // }
        if ("0.0.00" != $this->Jahrestag) {
            $content .= "Jahrestag: " . $this->Jahrestag . "\r\n";
        }
        if ("" != $this->Kategorien) {
            $content .= "Kategorien: " . $this->Kategorien . "\r\n";
        }
        // if ("" != $this->Kinder) {
        // $content .= ": " . $this->Kinder . "\r\n";
        // }
        // if ("" != $this->Konto) {
        // $content .= ": " . $this->Konto . "\r\n";
        // }
        // if ("" != $this->Name_desr_Vorgesetzten) {
        // $content .= ": " . $this->Name_desr_Vorgesetzten . "\r\n";
        // }
        if ("" != $this->Notizen) {
            $content .= "Notizen: " . $this->Notizen . "\r\n";
        }
        if ("" != $this->Organisationsnr) {
            $content .= "Organisationsnr: " . $this->Organisationsnr . "\r\n";
        }
        if ("" != $this->Ort) {
            $content .= "Ort: " . $this->Ort . "\r\n";
        }
        // if ("" != $this->Partnerin) {
        // $content .= ": " . $this->Partnerin . "\r\n";
        // }
        // if ("" != $this->Postfach_geschäftlich) {
        // $content .= ": " . $this->Postfach_geschäftlich . "\r\n";
        // }
        // if ("" != $this->Postfach_privat) {
        // $content .= ": " . $this->Postfach_privat . "\r\n";
        // }
        if ("" != $this->Priorität && "Normal" != $this->Priorität) {
            $content .= "Priorität: " . $this->Priorität . "\r\n";
        }
        if ("" != $this->Privat && "Aus" != $this->Privat) {
            $content .= "Privat: " . $this->Privat . "\r\n";
        }
        // if ("" != $this->Reisekilometer) {
        // $content .= ": " . $this->Reisekilometer . "\r\n";
        // }
        // if ("" != $this->Sozialversicherungsnr) {
        // $content .= ": " . $this->Sozialversicherungsnr . "\r\n";
        // }
        // if ("" != $this->Sprache) {
        // $content .= ": " . $this->Sprache . "\r\n";
        // }
        // if ("" != $this->Stichwörter) {
        // $content .= ": " . $this->Stichwörter . "\r\n";
        // }
        if ("" != $this->Vertraulichkeit && "Normal" != $this->Vertraulichkeit) {
            $content .= "Vertraulichkeit: " . $this->Vertraulichkeit . "\r\n";
        }
        // if ("" != $this->Verzeichnisserver) {
        // $content .= ": " . $this->Verzeichnisserver . "\r\n";
        // }
        if ("" != $this->Webseite) {
            $content .= "Webseite: " . $this->Webseite . "\r\n";
        }
        // if ("" != $this->Weiteres_Postfach) {
        // $content .= ": " . $this->Weiteres_Postfach . "\r\n";
        // }
        return $content;
    }
}

?>