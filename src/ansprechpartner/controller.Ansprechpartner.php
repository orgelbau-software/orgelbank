<?php

class AnsprechpartnerController
{

    public static function loescheAnsprechpartner()
    {
        if (! isset($_GET['aid']) && ! isset($_POST['objektid']))
            return;
        
        if ($_POST && isset($_POST['objektid'])) {
            $o = new Ansprechpartner($_POST['objektid']);
            
            // Gemeindenzuordnung auch loeschen
            $gemeinden = GemeindeUtilities::getAnsprechpartnerGemeinden($o->getID());
            foreach ($gemeinden as $g) {
                AnsprechpartnerUtilities::loescheGemeindeAnsprechpartner($o->getID(), $g->getID());
            }
            
            $o->loeschen();
            
            $htmlStatus = new HTMLRedirect();
            $htmlStatus->setLink("index.php?page=3&do=40");
            $htmlStatus->setNachricht("Ansprechpartner erfolgreich gel&ouml;scht.");
            $htmlStatus->setSekunden(ConstantLoader::getDefaultRedirectSecondsTrue());
            
            $htmlStatus->anzeigen();
        } else {
            $o = new Ansprechpartner(intval($_GET['aid']));
            
            $tpl = new HTMLSicherheitsAbfrage();
            $tpl->setText("M&ouml;chten Sie den Ansprechpartner \"" . $o->getVorname() . " " . $o->getNachname() . "\" wirklich endg&uuml;ltig l&ouml;schen? ");
            $tpl->setButtonJa("Ja, Ansprechpartner l&ouml;schen!");
            $tpl->setButtonNein("Nein, zur&uuml;ck");
            $tpl->setButtonNeinLink("index.php?page=3&do=40");
            $tpl->setFormLink("index.php?page=3&do=42");
            $tpl->setObjektID($o->getID());
            
            $tpl->anzeigen();
        }
    }

    public static function loescheGemeindeAnsprechpartner()
    {
        if (! isset($_GET['gid'], $_GET['aid']))
            return;
        
        AnsprechpartnerUtilities::loescheGemeindeAnsprechpartner($_GET['aid'], $_GET['gid']);
        
        $oA = new Ansprechpartner($_GET['aid']);
        $oG = new Gemeinde($_GET['gid']);
        
        $redirect = "index.php?page=3&do=40&aid=" . $oA->getID();
        
        // Wenn eine Orgel uebergeben wurde, dann gehen wir davon aus, dass es aus den WartungsDetails her gemacht wurde
        if(isset($_GET['oid'])) {
            $redirect = "index.php?page=2&do=28&oid=" . $_GET['oid'];
        }
        $htmlStatus = new HTMLRedirect($oA->getAnrede() . " " . $oA->getNachname() . " wurde der Gemeinde " . $oG->getKirche() . " als Ansprechpartner entfernt!", $redirect);
        $htmlStatus->anzeigen();
    }

    public static function neueVerbindungZuGemeinde()
    {
        if (! $_POST || ! isset($_POST['aid']))
            return;
        
        $oA = new Ansprechpartner(intval($_POST['aid']));
        $oG = new Gemeinde(intval($_POST['gemeinde']));
        
        if (AnsprechpartnerController::addAnsprechpartnerZuGemeinde($oA->getID(), $oG->getID())) {
            $htmlStatus = new HTMLStatus("Der Gemeinde " . $oG->getKirche() . " wurde " . $oA->getAnrede() . " " . $oA->getNachname() . " (" . $oA->getFunktion() . ") als Ansprechpartner hinzugef&uuml;gt!", HTMLStatus::$STATUS_OK);
            $html = new HTMLRedirect($htmlStatus->getOutput(), "index.php?page=3&do=40&aid=" . $oA->getID());
        } else {
            $htmlStatus = new HTMLStatus("Der Gemeinde " . $oG->getKirche() . " wurde zuvor bereits " . $oA->getAnrede() . " " . $oA->getNachname() . " (" . $oA->getFunktion() . ") als Ansprechpartner hinzugef&uuml;gt!", HTMLStatus::$STATUS_ERROR);
            $html = new HTMLRedirect($htmlStatus->getOutput(), "index.php?page=3&do=40&aid=" . $oA->getID(), ConstantLoader::getDefaultRedirectSecondsFalse());
        }
        
        $html->anzeigen();
    }

    /**
     *
     * @param unknown $iAnsprechpartnerID            
     * @param unknown $iGemeindeID            
     * @return boolean
     */
    private static function addAnsprechpartnerZuGemeinde($iAnsprechpartnerID, $iGemeindeID)
    {
        $retVal = false;
        $alreadyAdded = AnsprechpartnerUtilities::existiertVerbindung($iAnsprechpartnerID, $iGemeindeID);
        if ($alreadyAdded == false) {
            AnsprechpartnerUtilities::neuerGemeindeAnsprechpartner($iAnsprechpartnerID, $iGemeindeID);
            $retVal = true;
        }
        return $retVal;
    }

    public static function zeigeAnsprechpartnerVerwaltung()
    {
        $tplAnsprechpartner = new Template("ansprechpartner_verwaltung.tpl");
        $tplAnsprechpartnerDS = new BufferedTemplate("ansprechpartner_verwaltung_liste_ds.tpl", "css", "td1", "td2");
        $tplAnsprechpartnerRubrik = new Template("ansprechpartner_verwaltung_liste_rubrik_first.tpl");
        $tplAnsprechpartnerGemeindeDS = new BufferedTemplate("ansprechpartner_verwaltung_aliste_ds.tpl", "css", "td1", "td2");
        $tplSelect = new Template("select_option.tpl");
        
        $tplAnsprechpartner = new Template("ansprechpartner_verwaltung.tpl");
        if (! isset($_GET['aid'])) {
            $tplSub = new Template("ansprechpartner_verwaltung_neu.tpl");
            $tplAnsprechpartner->replace("ButtonTitle", "Anlegen");
        } else {
            $tplSub = new Template("ansprechpartner_verwaltung_edit.tpl");
            $tplAnsprechpartner->replace("ButtonTitle", "Bearbeiten");
        }
        
        // z.B. wenn aus einer Wartung heraus auf die Gemeinde referenziert wird und nur die Ansprechpartner der Gemeinde angezeigt werden sollen.
        $gid = 0;
        if (isset($_GET['gid'])) {
            $gid = intval($_GET['gid']);
            $tplAnsprechpartner->replace("GID", intval($_GET['gid']));
        } else {
            $tplAnsprechpartner->replace("GID", "");
        }
        
        $newIndex = "";
        $oldIndex = "-1";
        $arAnfangszeichen = array();
        $boFirst = true;
        
        // Ansprechpartner Liste Start
        $_POST ? $strSuchbegriff = $_POST['suchbegriff'] : $strSuchbegriff = "";
        
        // Sortierung
        if (! isset($_GET['order']) || $_GET['order'] == "name") {
            $strSQLOrderBy = "a_name";
        } else {
            $strSQLOrderBy = "a_funktion";
        }
        
        // Sortierung
        if (! isset($_GET['dir']) || $_GET['dir'] == "asc") {
            $strSQLDir = "ASC";
            $strTPLDir = "desc";
        } else {
            $strSQLDir = "DESC";
            $strTPLDir = "asc";
        }
        
        // Wenn der Request aus einer Wartungsbearbeitung kommt, soll nach der Bearbeitung wieder in die Wartung zurückgeleitet werden.
        if (isset($_GET['oid'])) {
            $_SESSION['request']['oid'] = intval($_GET['oid']);
        }
        // Vorab den Ansprechpartner laden, damit z.B. der Suchbegriff aus dem Namen des Ansprechpartners abgeleitet werden kann.
        if (isset($_GET['aid'])) {
            $oAnsprechpartner = new Ansprechpartner($_GET['aid']);
            $tplAnsprechpartner->replace("AID", $oAnsprechpartner->getID());
            $cAnsprGemeinden = GemeindeUtilities::getAnsprechpartnerGemeinden($oAnsprechpartner->getID(), " ORDER BY g_kirche");
        } else {
            $oAnsprechpartner = new Ansprechpartner();
            $tplAnsprechpartner->replace("AID", 0);
            $cAnsprGemeinden = new DatabaseStorageObjektCollection();
        }
        
        // Ansprechpartner filtern ausser bei Suche
        $strWhere = "";
        if (! isset($_POST['suchbegriff'])) {
            if (isset($_GET['a'])) {
                if ($_GET['a'] != "Alle")
                    $strWhere = " AND a_name LIKE '" . $_GET['a'] . "%'";
                $_SESSION['request']['anfangsbuchstabe'] = $_GET['a'];
            } else if ($oAnsprechpartner->getNachname() != "") {
                $strWhere = " AND a_name LIKE '" . substr($oAnsprechpartner->getNachname(), 0, 1) . "%'";
            } elseif (isset($_SESSION['request']['anfangsbuchstabe'])) {
                if ($_SESSION['request']['anfangsbuchstabe'] != "Alle")
                    $strWhere = " AND a_name LIKE '" . $_SESSION['request']['anfangsbuchstabe'] . "%'";
            } else {
                $strWhere = " AND a_name LIKE 'A%'";
            }
        }
        
        $tplAnsprechpartner->replace("Suchbegriff", $strSuchbegriff);
        $c = AnsprechpartnerUtilities::getSuchAnsprechpartner($strSuchbegriff, $strWhere . " ORDER BY " . $strSQLOrderBy . " " . $strSQLDir);
        foreach ($c as $curAnsprechpartner) {
            if (! isset($_GET['order']) || $_GET['order'] == "name") {
                $newIndex = strtoupper(substr($curAnsprechpartner->getNachname(), 0, 1));
            } else {
                $newIndex = strtoupper(substr($curAnsprechpartner->getFunktion(), 0, 1));
            }
            
            if ($newIndex != $oldIndex) {
                if (trim($newIndex) != "")
                    $arAnfangszeichen[] = $newIndex;
                $tplAnsprechpartnerRubrik->replace("Rubrik", $newIndex);
                $tplAnsprechpartnerDS->addToBuffer($tplAnsprechpartnerRubrik);
                $tplAnsprechpartnerRubrik->restoreTemplate();
                if ($boFirst) {
                    $boFirst = false;
                    $tplAnsprechpartnerRubrik = new Template("ansprechpartner_verwaltung_liste_rubrik.tpl");
                }
            }
            
            if (isset($_GET['aid']) && $curAnsprechpartner->getID() == $_GET['aid']) {
                $tplAnsprechpartnerDS->replace("css", "");
            }
            
            $tplAnsprechpartnerDS->replace("Name", $curAnsprechpartner->getAnzeigename());
            $tplAnsprechpartnerDS->replace("AID", $curAnsprechpartner->getID());
            $tplAnsprechpartnerDS->replace("Funktion", $curAnsprechpartner->getFunktion());
            $tplAnsprechpartnerDS->next();
            
            $oldIndex = $newIndex;
        }
        
        // Quickjump einfügen
        asort($arAnfangszeichen);
        $quickJump = array(
            "A",
            "B",
            "C",
            "D",
            "E",
            "F",
            "G",
            "H",
            "I",
            "J",
            "K",
            "L",
            "M",
            "N",
            "O",
            "P",
            "Q",
            "R",
            "S",
            "T",
            "U",
            "V",
            "W",
            "X",
            "Y",
            "Z",
            "Alle"
        );
        $tplQJ = new BufferedTemplate("ansprechpartner_quickjump_link.tpl");
        
        foreach ($quickJump as $value) {
            $tplQJ->replace("Anfangszeichen", $value);
            $tplQJ->next();
        }
        $tplAnsprechpartner->replace("Quickjump", $tplQJ->getOutput());
        
        $tplAnsprechpartner->replace("Ansprechpartnerliste", $tplAnsprechpartnerDS->getOutput());
        // Ansprechpartner Liste Ende
        
        $tplAnsprechpartner->replace("Dir", $strTPLDir);
        
        $htmlSelectAnrede = new HTMLSelectForArray(Constant::getAnredeAuswahl(), $oAnsprechpartner->getAnrede());
        $tplAnsprechpartner->replace("SelectAnrede", $htmlSelectAnrede->getOutput());
        
        $htmlSelectAnrede = new HTMLSelectForArray(Constant::getTitelAuswahl(), $oAnsprechpartner->getTitel());
        $tplAnsprechpartner->replace("SelectTitel", $htmlSelectAnrede->getOutput());
        
        $selectLand = new HTMLSelectForArray(ConstantLoader::getLaenderAuswahl(), $oAnsprechpartner->getAdresse()->getLand());
        $tplAnsprechpartner->replace("Laender", $selectLand->getOutput());
        
        $tplAnsprechpartner->replace("Funktion", $oAnsprechpartner->getFunktion());
        $tplAnsprechpartner->replace("Anrede", $oAnsprechpartner->getAnrede());
        $tplAnsprechpartner->replace("Vorname", $oAnsprechpartner->getVorname());
        $tplAnsprechpartner->replace("Nachname", $oAnsprechpartner->getNachname());
        $tplAnsprechpartner->replace("Strasse", $oAnsprechpartner->getAdresse()
            ->getStrasse());
        $tplAnsprechpartner->replace("Hsnr", $oAnsprechpartner->getAdresse()
            ->getHausnummer());
        $tplAnsprechpartner->replace("PLZ", $oAnsprechpartner->getAdresse()
            ->getPLZ());
        $tplAnsprechpartner->replace("Ort", $oAnsprechpartner->getAdresse()
            ->getOrt());
        $tplAnsprechpartner->replace("Telefon", $oAnsprechpartner->getTelefon());
        $tplAnsprechpartner->replace("Fax", $oAnsprechpartner->getFax());
        $tplAnsprechpartner->replace("Mobil", $oAnsprechpartner->getMobil());
        $tplAnsprechpartner->replace("EMail", $oAnsprechpartner->getEmail());
        $tplAnsprechpartner->replace("Bemerkung", $oAnsprechpartner->getBemerkung());
        $tplAnsprechpartner->replace("Andere", $oAnsprechpartner->getAndere());
        
        // AnsprechpartnerDetails Ende
        
        // Ansprechpartner Gemeinden anzeigen
        $c = GemeindeUtilities::getGemeindenAusserVonAnsprechpartner($oAnsprechpartner->getID(), " ORDER BY g_kirche ASC");
        
        // Gemeindeliste nicht ausgeben wenn leer oder die dem Ansprechpartner zugeordneten Gemeinden == der GesamtAnzahl der Gemeinden ist
        // SWA, 10.2016: der obige kommentar macht gerade keinen Sinn. Das greift, wenn die Anzahl der der bereits zugeordneten Gemeinden = der noch zuzuordnen ist.
        // if($c->getSize() > 0 && $c->getSize() > $cAnsprGemeinden->getSize()) {
        if ($c->getSize() > 0) {
            $tplSub->replace("GemeindeHinzufuegenDisabled", "");
            $tplSelect = new HTMLSelectForKey($c, "getGemeindeId", "getKirche,getOrt", 0);
        } else {
            $tplSelect->replace("Name", "keine Gemeinde verf&uuml;gbar");
            $tplSelect->replace("Value", - 1);
            $tplSelect->replace("Selected", "");
            $tplSub->replace("GemeindeHinzufuegenDisabled", "disabled");
        }
        $tplSub->replace("GemeindeListe", $tplSelect->getOutput());
        
        $cGemeindeAnsprechpartner = GemeindeUtilities::getAnsprechpartnerGemeinden($oAnsprechpartner->getID(), " ORDER BY g_kirche ASC");
        // Ansprechpartner Gemeinden anzeigen
        foreach ($cGemeindeAnsprechpartner as $kircheOrtBean) {
            $tplAnsprechpartnerGemeindeDS->replace("GID", $kircheOrtBean->getID());
            $tplAnsprechpartnerGemeindeDS->replace("Gemeinde", $kircheOrtBean->getKirche());
            $tplAnsprechpartnerGemeindeDS->replace("Ort", $kircheOrtBean->getOrt());
            $tplAnsprechpartnerGemeindeDS->replace("AID", $oAnsprechpartner->getID());
            $tplAnsprechpartnerGemeindeDS->next();
        }
        
        $tplSub->replace("AID", $oAnsprechpartner->getID());
        $tplSub->replace("AnsprechpartnerGemeinden", $tplAnsprechpartnerGemeindeDS->getOutput());
        
        $tplAnsprechpartner->replace("Subtemplate", $tplSub->getOutput());
        
        // AnsprechpartnerDetails Rechts Ende
        echo $tplAnsprechpartner->forceOutput();
    }

    public static function aendereAnsprechpartner()
    {
        if (! isset($_POST['submit']))
            return;
        
        if ($_POST['submit'] == "Speichern") {
            AnsprechpartnerController::speichereAnsprechpartner();
        } elseif ($_POST['submit'] == "Löschen") {
            AnsprechpartnerController::loescheAnsprechpartner();
        } else {}
    }

    public static function speichereAnsprechpartner()
    {
        if (! $_POST || ! isset($_POST['aid']))
            return;
        if ($_POST['aid'] == 0) {
            $oAnsprechpartner = new Ansprechpartner();
        } else {
            $oAnsprechpartner = new Ansprechpartner(intval($_POST['aid']));
        }
        
        if (isset($_POST['name']) && $_POST['name'] != "") {
            Utilities::escapePost();
            $oAnsprechpartner->setAnrede($_POST['anrede']);
            $oAnsprechpartner->setTitel($_POST['titel']);
            $oAnsprechpartner->setVorname($_POST['vorname']);
            $oAnsprechpartner->setFunktion($_POST['funktion']);
            $oAnsprechpartner->setNachname($_POST['name']);
            $oAnsprechpartner->getAdresse()->setType(Adresse::TYPE_ANSPRECHPARTNER);
            $oAnsprechpartner->getAdresse()->setStrasse($_POST['strasse']);
            $oAnsprechpartner->getAdresse()->setHausnummer($_POST['hausnummer']);
            $oAnsprechpartner->getAdresse()->setPLZ($_POST['plz']);
            $oAnsprechpartner->getAdresse()->setOrt($_POST['ort']);
            if (isset($_POST['land'])) {
                $oAnsprechpartner->getAdresse()->setLand($_POST['land']);
            }
            $oAnsprechpartner->setTelefon($_POST['telefon']);
            $oAnsprechpartner->setFax($_POST['fax']);
            $oAnsprechpartner->setMobil($_POST['mobil']);
            $oAnsprechpartner->setEmail($_POST['email']);
            $oAnsprechpartner->setBemerkung($_POST['bemerkung']);
            $oAnsprechpartner->setAktiv(1);
            
            // war auskommentiert, wegen Speichern der Firmendaten wieder einkommentiert
            $oAnsprechpartner->setAndere($_POST['andere']);
            
            $oAnsprechpartner->speichern(true);
            if (isset($_POST['gid']) && intval($_POST['gid']) != 0) {
                AnsprechpartnerController::addAnsprechpartnerZuGemeinde($oAnsprechpartner->getID(), $_POST['gid']);
            }
            
            // == 1 bedeutet die Änderung der Firmenanschrift des Inhabers. Also kein Ansprechpartner.
            if ($oAnsprechpartner->getID() === 1) {
                
                $geocoder = new OrgelbankGoogleMapsGeocoder();
                $geocoder->setAddress($oAnsprechpartner->getAdresse()
                    ->getFormattedAdress());
                $result = $geocoder->geocode();
                if (IGeolocationConstants::OK == $result) {
                    $oAnsprechpartner->getAdresse()->setLat($geocoder->getAdresse()
                        ->getLat());
                    $oAnsprechpartner->getAdresse()->setLng($geocoder->getAdresse()
                        ->getLng());
                    $oAnsprechpartner->getAdresse()->setGeoStatus(IGeolocationConstants::OK);
                    $oAnsprechpartner->getAdresse()->speichern(true);
                    
                    $htmlRedirect = new HTMLRedirect("Ansprechpartner wurde gespeichert", "index.php?page=7&do=121");
                } else {
                    $htmlRedirect = new HTMLRedirect("Ansprechpartner wurde gespeichert aber die Adressdaten konnten nicht ermittelt werden. " . Constant::getGeoStatusUserMessage($result, "Firmenanschrift"), "index.php?page=7&do=121");
                }
            } else {
                $nachnameAnfangsbuchstabe = substr($oAnsprechpartner->getNachname(), 0, 1);
                if (strlen($nachnameAnfangsbuchstabe) != 1) {
                    $nachnameAnfangsbuchstabe = "A";
                }
                $link = "index.php?page=3&do=40&aid=" . $oAnsprechpartner->getID() . "&a=" . $nachnameAnfangsbuchstabe;
                if (isset($_SESSION['request']['oid'])) {
                    $link = "index.php?page=2&do=28&oid=" . $_SESSION['request']['oid'];
                    unset($_SESSION['request']['oid']);
                }
                $htmlRedirect = new HTMLRedirect("Ansprechpartner wurde gespeichert", $link);
            }
        } else {
            $htmlStatus = new HTMLStatus("Bitte geben Sie mindestens einen <strong>Nachnamen</strong> an. Ansprechpartner wurde nicht gespeichert", 1);
            $htmlRedirect = new HTMLRedirect($htmlStatus->getOutput(), "index.php?page=3&do=40", ConstantLoader::getDefaultRedirectSecondsFalse());
        }
        
        $htmlRedirect->anzeigen();
    }
}
?>
