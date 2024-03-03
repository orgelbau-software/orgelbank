<?php

class AnsprechpartnerVerwaltung implements GetRequestHandler
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
            $strSQLOrderBy = "anzeigename ";
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
                if($curAnsprechpartner->getNachname() == "") {
                    // nix?
                } else {
                    $newIndex = strtoupper(substr($curAnsprechpartner->getNachname(), 0, 1));
                }
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
        
        $tplAnredeArtenDL = new HTMLDatalistForArray(Constant::getAnredeAuswahl());
        $tplAnsprechpartner->replace("AnredeDatalist", $tplAnredeArtenDL->getOutput());
        $tplAnsprechpartner->replace("Anrede", $oAnsprechpartner->getAnrede());
        
        $tplTitelArtenDL = new HTMLDatalistForArray(Constant::getTitelAuswahl());
        $tplAnsprechpartner->replace("TitelDatalist", $tplTitelArtenDL->getOutput());
        $tplAnsprechpartner->replace("Titel", $oAnsprechpartner->getTitel());
        
        $selectLand = new HTMLSelectForArray(ConstantLoader::getLaenderAuswahl(), $oAnsprechpartner->getAdresse()->getLand());
        $tplAnsprechpartner->replace("Laender", $selectLand->getOutput());
        
        $tplAnsprechpartner->replace("Funktion", $oAnsprechpartner->getFunktion());
        $tplAnsprechpartner->replace("Firma", $oAnsprechpartner->getFirma());
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
        $tplAnsprechpartner->replace("AnsprechpartnerWebseite", $oAnsprechpartner->getWebseite());
        
        // AnsprechpartnerDetails Ende
        
        // Ansprechpartner Gemeinden anzeigen
        $standardSortierung = ConstantLoader::getGemeindeListeStandardSortierung();
        if ($standardSortierung == "ort") {
            $c = GemeindeUtilities::getGemeindenAusserVonAnsprechpartner($oAnsprechpartner->getID(), " ORDER BY a.ad_ort ASC");
        } else {
            $c = GemeindeUtilities::getGemeindenAusserVonAnsprechpartner($oAnsprechpartner->getID(), " ORDER BY g_kirche ASC");
        }
        
        // Gemeindeliste nicht ausgeben wenn leer oder die dem Ansprechpartner zugeordneten Gemeinden == der GesamtAnzahl der Gemeinden ist
        // SWA, 10.2016: der obige kommentar macht gerade keinen Sinn. Das greift, wenn die Anzahl der der bereits zugeordneten Gemeinden = der noch zuzuordnen ist.
        // if($c->getSize() > 0 && $c->getSize() > $cAnsprGemeinden->getSize()) {
        if ($c->getSize() > 0) {
            $tplSub->replace("GemeindeHinzufuegenDisabled", "");
            if ($standardSortierung == "ort") {
                $tplSelect = new HTMLSelectForKey($c, "getGemeindeId", "getOrt,getKirche", 0);
            } else {
                $tplSelect = new HTMLSelectForKey($c, "getGemeindeId", "getKirche,getOrt", 0);
            }
            $tplSelect->setValueMaxLength(57);
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
        // echo $tplAnsprechpartner->forceOutput();
        return $tplAnsprechpartner;
    }
}