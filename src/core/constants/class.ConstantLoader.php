<?php

/**
 * Stellt wichtige Konstanten zur Laufzeit zur Verfuegung
 * 
 * @author swatermeyer
 * @version $Revision:  $
 *
 */
class ConstantLoader
{

    private static $htValues;

    /**
     * Standarkonstruktor
     *
     * @access private
     */
    private function __construct()
    {}

    /**
     * Laedt die Konstanten aus der Datenbank in die Klasse
     */
    public static function performAutoload()
    {
        $oDSOC = OptionValueUtilities::getAutoloadOptions();
        $ht = new HashTable();
        
        foreach ($oDSOC as $o) {
            $value = stripslashes($o->getValue());
            $ht->put($o->getName(), $value);
        }
        ConstantLoader::$htValues = $ht;
    }

    public static function getOrgelbankAPIKey()
    {
        return ConstantLoader::$htValues->getValueOf("orgelbank_api_key");
    }

    public static function getPflegeRechnungsNummerNaechste()
    {
        return ConstantLoader::$htValues->getValueOf("rechnung_pflege_naechste_nummer");
    }

    public static function getAbschlagRechnungsNummerNaechste()
    {
        return ConstantLoader::$htValues->getValueOf("rechnung_abschlag_naechste_nummer");
    }

    public static function getStandardPflegerechnungPos1()
    {
        return ConstantLoader::$htValues->getValueOf("pflegerechnung_pos_1");
    }

    public static function getStandardPflegerechnungPos2()
    {
        return ConstantLoader::$htValues->getValueOf("pflegerechnung_pos_2");
    }

    public static function getStandardPflegerechnungPos3()
    {
        return ConstantLoader::$htValues->getValueOf("pflegerechnung_pos_3");
    }

    public static function getStandardPflegerechnungPos4()
    {
        return ConstantLoader::$htValues->getValueOf("pflegerechnung_pos_4");
    }

    public static function getStandardPflegerechnungPos5()
    {
        return ConstantLoader::$htValues->getValueOf("pflegerechnung_pos_5");
    }

    public static function getStandardPflegerechnungPos6()
    {
        return ConstantLoader::$htValues->getValueOf("pflegerechnung_pos_6");
    }

    public static function getStandardPflegerechnungPos7()
    {
        return ConstantLoader::$htValues->getValueOf("pflegerechnung_pos_7");
    }

    public static function getStandardPflegerechnungPos8()
    {
        return ConstantLoader::$htValues->getValueOf("pflegerechnung_pos_8");
    }

    public static function getStandardPflegerechnungPos9()
    {
        return ConstantLoader::$htValues->getValueOf("pflegerechnung_pos_9");
    }

    public static function getStandardPflegerechnungPos10()
    {
        return ConstantLoader::$htValues->getValueOf("pflegerechnung_pos_10");
    }

    public static function getStandardStundenrechnungPos1()
    {
        return ConstantLoader::$htValues->getValueOf("stundenrechnung_pos_1");
    }

    public static function getStandardStundenrechnungPos2()
    {
        return ConstantLoader::$htValues->getValueOf("stundenrechnung_pos_2");
    }

    public static function getStandardStundenrechnungPos3()
    {
        return ConstantLoader::$htValues->getValueOf("stundenrechnung_pos_3");
    }

    public static function getStandardStundenrechnungPos4()
    {
        return ConstantLoader::$htValues->getValueOf("stundenrechnung_pos_4");
    }

    public static function getStandardStundenrechnungPos5()
    {
        return ConstantLoader::$htValues->getValueOf("stundenrechnung_pos_5");
    }

    public static function getStandardStundenrechnungPos6()
    {
        return ConstantLoader::$htValues->getValueOf("stundenrechnung_pos_6");
    }

    public static function getStandardStundenrechnungPos7()
    {
        return ConstantLoader::$htValues->getValueOf("stundenrechnung_pos_7");
    }

    public static function getStandardStundenrechnungPos8()
    {
        return ConstantLoader::$htValues->getValueOf("stundenrechnung_pos_8");
    }

    public static function getStandardStundenrechnungPos9()
    {
        return ConstantLoader::$htValues->getValueOf("stundenrechnung_pos_9");
    }

    public static function getStandardStundenrechnungPos10()
    {
        return ConstantLoader::$htValues->getValueOf("stundenrechnung_pos_10");
    }

    public static function getStandardZahlungsziel()
    {
        return ConstantLoader::$htValues->getValueOf("standardzahlungsziel");
    }

    public static function getRechnungPflegeText()
    {
        return ConstantLoader::$htValues->getValueOf("rechnung_pflege_text");
    }

    public static function getRechnungAuftragText()
    {
        return ConstantLoader::$htValues->getValueOf("rechnung_auftrag_text");
    }

    public static function getRechnungAngebotText()
    {
        return ConstantLoader::$htValues->getValueOf("rechnung_angebot_text");
    }

    public static function getPflegeRechnungSchlusstext()
    {
        return ConstantLoader::$htValues->getValueOf("rechnung_pflege_schlusstext");
    }
    
    public static function getStundenRechnungSchlusstext()
    {
        return ConstantLoader::$htValues->getValueOf("rechnung_stunden_schlusstext");
    }
    
    public static function getRechnungStandardZahlungsziele()
    {
        return ConstantLoader::$htValues->getValueOf("rechnung_zahlungsziele");
    }

    public static function getDefaultRedirectSecondsTrue()
    {
        return ConstantLoader::$htValues->getValueOf("default_redirect_seconds_true");
    }

    public static function getDefaultRedirectSecondsFalse()
    {
        return ConstantLoader::$htValues->getValueOf("default_redirect_seconds_false");
    }

    public static function getMaximumIdleTime()
    {
        return ConstantLoader::$htValues->getValueOf("max_idle_time");
    }

    public static function getMaximumIdleTimeInSeconds()
    {
        return ConstantLoader::getMaximumIdleTime() * 60;
    }

    /**
     * Minimale Passwort Länge
     *
     * @return int
     */
    public static function getBenutzerMinPasswortLength()
    {
        return ConstantLoader::$htValues->getValueOf("min_user_password_length");
    }

    /**
     * Maximale Benutzernamenlänge
     *
     * @return int
     */
    public static function getBenutzerMaxUsernameLength()
    {
        return ConstantLoader::$htValues->getValueOf("max_user_username_length");
    }

    public static function getGoogleHTMLBodyProperty()
    {
        return ConstantLoader::$htValues->getValueOf("google_htmlbody_property");
    }

    public static function setConstantHashtable(HashTable $h)
    {
        ConstantLoader::$htValues = $h;
    }

    public static function getRechnungAbschlag1Text()
    {
        return ConstantLoader::$htValues->getValueOf("rechnung_abschlag1_text");
    }

    public static function getRechnungAbschlag2Text()
    {
        return ConstantLoader::$htValues->getValueOf("rechnung_abschlag2_text");
    }

    public static function getRechnungAbschlag3Text()
    {
        return ConstantLoader::$htValues->getValueOf("rechnung_abschlag3_text");
    }

    public static function getRechnungAbschlag1Prozent()
    {
        return ConstantLoader::$htValues->getValueOf("rechnung_abschlag1_prozent");
    }

    public static function getRechnungAbschlag2Prozent()
    {
        return ConstantLoader::$htValues->getValueOf("rechnung_abschlag2_prozent");
    }

    public static function getRechnungAbschlag3Prozent()
    {
        return ConstantLoader::$htValues->getValueOf("rechnung_abschlag3_prozent");
    }

    public static function getUrlaubUnteraufgaben()
    {
        return array(
            260
        );
    }

    public static function getKrankProjekte()
    {
        return array(
            35
        );
    }

    public static function getVerwaltungsProjekte()
    {
        $merge = array();
        $merge = array_merge($merge, ConstantLoader::getUrlaubUnteraufgaben());
        $merge = array_merge($merge, ConstantLoader::getKrankProjekte());
        return $merge;
    }

    public static function getStandardUrlaubstage()
    {
        return ConstantLoader::$htValues->getValueOf("standard_urlaubstage");
    }

    public static function getStandardArbeitsstundenMontag()
    {
        return ConstantLoader::$htValues->getValueOf("standard_stunden_montag");
    }

    public static function getStandardArbeitsstundenDienstag()
    {
        return ConstantLoader::$htValues->getValueOf("standard_stunden_dienstag");
    }

    public static function getStandardArbeitsstundenMittwoch()
    {
        return ConstantLoader::$htValues->getValueOf("standard_stunden_mittwoch");
    }

    public static function getStandardArbeitsstundenDonnerstag()
    {
        return ConstantLoader::$htValues->getValueOf("standard_stunden_donnerstag");
    }

    public static function getStandardArbeitsstundenFreitag()
    {
        return ConstantLoader::$htValues->getValueOf("standard_stunden_freitag");
    }

    public static function getStandardArbeitsstundenSamstag()
    {
        return ConstantLoader::$htValues->getValueOf("standard_stunden_samstag");
    }

    public static function getStandardArbeitsstundenSonntag()
    {
        return ConstantLoader::$htValues->getValueOf("standard_stunden_sonntag");
    }

    public static function getStandardWochenstunden()
    {
        $retVal = 0;
        $retVal += ConstantLoader::getStandardArbeitsstundenMontag();
        $retVal += ConstantLoader::getStandardArbeitsstundenDienstag();
        $retVal += ConstantLoader::getStandardArbeitsstundenMittwoch();
        $retVal += ConstantLoader::getStandardArbeitsstundenDonnerstag();
        $retVal += ConstantLoader::getStandardArbeitsstundenFreitag();
        $retVal += ConstantLoader::getStandardArbeitsstundenSamstag();
        $retVal += ConstantLoader::getStandardArbeitsstundenSonntag();
        return $retVal;
    }

    /**
     * Die ID des Kunden in der Ansprechpartner Tabelle
     *
     * @return int
     */
    public static function getKundeId()
    {
        return ConstantLoader::$htValues->getValueOf("kunde_ansprechpartner_id");
    }

    public static function getSiteTitle()
    {
        return ConstantLoader::$htValues->getValueOf("site_title");
    }

    /**
     * Der Nachrichtentext in der Fusszeile, der beim Klicken ausgeblendet wird
     *
     * @return String
     */
    public static function getAdminNachrichtenHoverText()
    {
        return ConstantLoader::$htValues->getValueOf("admin_kurzinfo_hover_txt");
    }

    public static function getPDFUntertext1()
    {
        return ConstantLoader::$htValues->getValueOf("pdf_untertext1");
    }

    public static function getPDFUntertext2()
    {
        return ConstantLoader::$htValues->getValueOf("pdf_untertext2");
    }

    public static function getMaxFailedLogins()
    {
        return ConstantLoader::$htValues->getValueOf("max_failed_logins");
    }

    /**
     * Liefert die Anzahl der Standardmäßigen Default Register zurück, die im DispositionsEditor angezeigt werden
     */
    public static function getDefaultTOPRegister()
    {
        return ConstantLoader::$htValues->getValueOf("default_top_register");
    }

    public static function getKilometerpauschale()
    {
        return ConstantLoader::$htValues->getValueOf("kilometerpauschale");
    }

    public static function getWartungsBogenBildAnzeige()
    {
        return ConstantLoader::$htValues->getValueOf("wartung_bogen_bildanzeige");
    }

    public static function getMindestAnzahlGemeindenFuerGruppierung()
    {
        return ConstantLoader::$htValues->getValueOf("gemeinde_anzahl_listengruppierung");
    }

    public static function getMindestAnzahlOrgelnFuerGruppierung()
    {
        return ConstantLoader::$htValues->getValueOf("orgel_anzahl_listengruppierung");
    }

    public static function getCronjobGeoStatusLimit()
    {
        return ConstantLoader::$htValues->getValueOf("cronjob_geostatus_limit");
    }

    public static function getLaenderAuswahl()
    {
        $a = explode(",", ConstantLoader::$htValues->getValueOf("laenderauswahl"));
        $retVal = array();
        foreach ($a as $val) {
            $val = trim($val);
            $retVal[$val] = $val;
        }
        return $retVal;
    }

    /**
     *
     * @return the API key.
     */
    public static function getGeocodeAPIServiceKey()
    {
        return ConstantLoader::$htValues->getValueOf("geocode_api_service_apikey");
    }

    /**
     *
     * @return true if the external Geolocation service should be used.
     */
    public static function getGeocodeAPIServiceActive()
    {
        return ConstantLoader::$htValues->getValueOf("geocode_api_service_active") == "true";
    }

    /**
     *
     * @return the URL of the service which is used for the geo location.
     */
    public static function getGeocodeAPIServiceEndpoint()
    {
        return ConstantLoader::$htValues->getValueOf("geocode_api_service_endpoint");
    }
    
    /**
    * @return true oder false wenn der Wartungsbogen die Checkliste enthalten soll.
    */
    public static function getWartungsBogenCheckliste()
    {
        return ConstantLoader::$htValues->getValueOf("wartung_bogen_checkliste");
    }
    
}
?>