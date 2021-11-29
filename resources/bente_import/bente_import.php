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

if (($handle = fopen("kontakte.original.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
        echo "<p> $num Felder in Zeile $row: <br /></p>\n";
        
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
        //$entity->Weiteresr_BundeslandKanton = $data[$fieldCounter ++];
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
        //$entity->EMailTyp = $data[$fieldCounter ++];
        $fieldCounter ++;
        
        $entity->EMail_Angezeigter_Name = $data[$fieldCounter ++];
        $entity->EMail_2_Adresse = $data[$fieldCounter ++];
        $entity->EMail_2_Typ = $data[$fieldCounter ++];
        $entity->EMail_2_Angezeigter_Name = $data[$fieldCounter ++];
        $entity->EMail_3_Adresse = $data[$fieldCounter ++];
        $entity->EMail_3_Typ = $data[$fieldCounter ++];
        $entity->EMail_3_Angezeigter_Name = $data[$fieldCounter ++];
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
        //$entity->Reisekilometer = $data[$fieldCounter ++];
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
//             $entity->Weiteres_Postfach = $data[$fieldCounter ++];
            $fieldCounter ++;
        }
        
        print_r($entity);
        
        
        
        $ansprechpartner = new Ansprechpartner();
        $ansprechpartner->setAktiv(1);
        $ansprechpartner->setAndere($entity->Telefon_geschäftlich);
        $ansprechpartner->setBemerkung($entity->Notizen);
        $ansprechpartner->setEmail($entity->EMailAdresse);
        $ansprechpartner->setFax($entity->Fax_geschäftlich);
        $ansprechpartner->setFunktion("");
        $ansprechpartner->setMobil($entity->Mobiltelefon);
        $ansprechpartner->setNachname($entity->Nachname);
        $ansprechpartner->setTelefon($entity->Telefon_geschäftlich);
        $ansprechpartner->setTitel($entity->Anrede);
        $ansprechpartner->setVorname($entity->Vorname) && $entity->Vorname = "";
        
        
        $row ++;
    }
    fclose($handle);
}

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

    //public $EMailTyp;

    public $EMail_Angezeigter_Name;

    public $EMail_2_Adresse;

    public $EMail_2_Typ;

    public $EMail_2_Angezeigter_Name;

    public $EMail_3_Adresse;

    public $EMail_3_Typ;

    public $EMail_3_Angezeigter_Name;

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

    //public $Reisekilometer;

    // public $Sozialversicherungsnr;
    
    // public $Sprache;
    
    // public $Stichwörter;
    public $Vertraulichkeit;

    // public $Verzeichnisserver;
    public $Webseite;

    //public $Weiteres_Postfach;

    public function __toString()
    {
        $retVal = "CSVBenteEntity [";
        $retVal = $this->Anrede . ", ";
        $retVal = $this->Vorname . ", ";
        $retVal .= "]";
        return $retVal;
    }
}

?>