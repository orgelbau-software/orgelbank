<?php

class OrgelDruckansicht implements GetRequestHandler
{

    /**
     *
     * {@inheritdoc}
     *
     * @return bool
     */
    public function validateGetRequest()
    {
        return true;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @return HTMLStatus
     */
    public function handleInvalidGet()
    {
        return new HTMLStatus("Alles ok");
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function prepareGet()
    {
        return;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @return Template
     */
    public function executeGet()
    {
        $tplOrgelDruck = new Template("orgel_liste_druck.tpl");
        $tplOrgelDruckDs = new BufferedTemplate("orgel_liste_druck_ds.tpl");
        $strOrgelOutput = "";
        $i = 0;
        $strSQLOrderBy = "";
        
        if (! isset($_GET['order']) || $_GET['order'] == "erbauer") {
            $strSQLOrderBy = "o_erbauer";
        } elseif ($_GET['order'] == "baujahr") {
            $strSQLOrderBy = "o_baujahr";
        } elseif ($_GET['order'] == "wartung") {
            $strSQLOrderBy = "o_letztepflege";
        } elseif ($_GET['order'] == "manual") {
            // !!!
        } elseif ($_GET['order'] == "register") {
            $strSQLOrderBy = "o_anzahlregister";
        } elseif ($_GET['order'] == "gemeinde") {
            $strSQLOrderBy = "g_kirche";
        } elseif ($_GET['order'] == "plz") {
            $strSQLOrderBy = "ad_plz";
        } elseif ($_GET['order'] == "ort") {
            $strSQLOrderBy = "ad_ort";
        } elseif ($_GET['order'] == "bezirk") {
            $strSQLOrderBy = "b_id";
        } elseif ($_GET['order'] == "pflegevertrag") {
            $strSQLOrderBy = "o_pflegevertrag";
        } elseif ($_GET['order'] == "zyklus") {
            $strSQLOrderBy = "o_zyklus";
        }
        
        // Sortierueberschriften ausgeben
        if (! isset($_GET['dir']) || $_GET['dir'] == "asc") {
            $strSQLDir = "ASC";
            $strTPLDir = "desc";
        } else {
            $strSQLDir = "DESC";
            $strTPLDir = "asc";
        }
        
        $tplOrgelDruck->replace("Dir", $strTPLDir);
        $tplOrgelDruck->replace("OrgelAnzahl", OrgelUtilities::getAnzahlOrgeln());
        $tplOrgelDruck->replace("Datum", date("d.m.Y, H:i") . " Uhr");
        
        $c = OrgelUtilities::getDruckAnsichtOrgeln("ORDER BY " . $strSQLOrderBy . " " . $strSQLDir);
        
        // Ausgabe der Datensätze
        foreach ($c as $oOrgel) {
            
            // Manuale aus der Datenbank lesen
            if ($oOrgel->getManual5() == 1) {
                $manual = "V";
            } elseif ($oOrgel->getManual4() == 1) {
                $manual = "IV";
            } elseif ($oOrgel->getManual3() == 1) {
                $manual = "III";
            } elseif ($oOrgel->getManual2() == 1) {
                $manual = "II";
            } elseif ($oOrgel->getManual1() == 1) {
                $manual = "I";
            } else {
                $manual = "keine Manuale";
            }
            if ($oOrgel->getPedal() == 1) {
                $manual = $manual . "/Pedal";
            }
            
            // Werte ins Template einfügen
            $tplOrgelDruckDs->replace("Lfnr", ++ $i);
            $tplOrgelDruckDs->replace("OID", $oOrgel->getOrgelID());
            $tplOrgelDruckDs->replace("GID", $oOrgel->getGemeindeID());
            if ($oOrgel->getGemeindeNamen() == "")
                $tplOrgelDruckDs->replace("Gemeinde", "&nbsp;");
            $tplOrgelDruckDs->replace("Gemeinde", $oOrgel->getGemeindeNamen());
            
            if ($oOrgel->getErbauer() == "")
                $tplOrgelDruckDs->replace("Erbauer", "&nbsp;");
            $tplOrgelDruckDs->replace("Erbauer", $oOrgel->getErbauer());
            
            if ($oOrgel->getBaujahr() == "")
                $tplOrgelDruckDs->replace("Baujahr", "&nbsp;");
            $tplOrgelDruckDs->replace("Baujahr", $oOrgel->getBaujahr());
            
            if ($oOrgel->getLetztePflege() == "")
                $tplOrgelDruckDs->replace("LetztePflege", "&nbsp;");
            $tplOrgelDruckDs->replace("LetztePflege", $oOrgel->getLetztePflege(true));
            
            if ($oOrgel->getPflegevertrag() != null && trim($oOrgel->getPflegevertrag()) == "")
                $tplOrgelDruckDs->replace("Pflegevertrag", "&nbsp;");
            $tplOrgelDruckDs->replace("Pflegevertrag", ($oOrgel->getPflegevertrag() == "1" ? "Ja" : "Nein"));
            
            if ($oOrgel->getZyklus() != null && trim($oOrgel->getZyklus()) == "")
                $tplOrgelDruckDs->replace("Zyklus", "&nbsp;");
            $tplOrgelDruckDs->replace("Zyklus", ($oOrgel->getZyklusAnzeige()));
            
            $tplOrgelDruckDs->replace("Manuale", $manual);
            $tplOrgelDruckDs->replace("Register", $oOrgel->getRegisterAnzahl());
            if ($oOrgel->getGemeindePLZ() == "")
                $tplOrgelDruckDs->replace("PLZ", "&nbsp;");
            $tplOrgelDruckDs->replace("PLZ", $oOrgel->getGemeindePLZ());
            
            if ($oOrgel->getGemeindeOrt() == "")
                $tplOrgelDruckDs->replace("Ort", "&nbsp;");
            $tplOrgelDruckDs->replace("Ort", $oOrgel->getGemeindeOrt());
            
            if ($oOrgel->getGemeindeBezirk() == "")
                $tplOrgelDruckDs->replace("Bezirk", "&nbsp;");
            $tplOrgelDruckDs->replace("Bezirk", $oOrgel->getGemeindeBezirk());
            
            if ($oOrgel->getFunktion() != null && trim($oOrgel->getFunktion()) == "")
                $tplOrgelDruckDs->replace("Funktion", "&nbsp;");
            $tplOrgelDruckDs->replace("Funktion", $oOrgel->getFunktion());
            
            if ($oOrgel->getNachname() != null && trim($oOrgel->getNachname()) == "")
                $tplOrgelDruckDs->replace("Nachname", "---");
            $tplOrgelDruckDs->replace("Nachname", $oOrgel->getNachname());
            
            if ($oOrgel->getVorname() != null && trim($oOrgel->getVorname()) == "")
                $tplOrgelDruckDs->replace("Vorname", "---");
            $tplOrgelDruckDs->replace("Vorname", $oOrgel->getVorname());
            
            if ($oOrgel->getTelefon() != null && trim($oOrgel->getTelefon()) == "")
                $tplOrgelDruckDs->replace("Telefon", "&nbsp;");
            $tplOrgelDruckDs->replace("Telefon", $oOrgel->getTelefon());
            
            $tplOrgelDruckDs->next();
        }
        
        // Orgeldatensätze ins Template einfügen
        $tplOrgelDruck->replace("Content", $tplOrgelDruckDs->getOutput());
        
        // Template ausgeben
        return $tplOrgelDruck;
    }
}