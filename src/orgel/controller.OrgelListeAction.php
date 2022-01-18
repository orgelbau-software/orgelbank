<?php

class OrgelListeAction implements GetRequestHandler, PostRequestHandler
{

    /**
     *
     * {@inheritdoc}
     *
     * @see GetRequestHandler::validateGetRequest()
     */
    public function validateGetRequest()
    {
        return true;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see GetRequestHandler::handleInvalidGet()
     */
    public function handleInvalidGet()
    {
        $htmlStatus = new HTMLRedirect();
        $htmlStatus->setLink("index.php?page=1&do=1");
        return $htmlStatus;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see GetRequestHandler::prepareGet()
     */
    public function prepareGet()
    {
        // TODO Auto-generated method stub
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see GetRequestHandler::executeGet()
     */
    public function executeGet()
    {
        $tplOrgeldetails = new Template("orgel_liste.tpl");
        if (ConstantLoader::getGemeindeListeStandardSortierung() == "ort") {
            $tplOrgellisteDs = new BufferedTemplate("orgel_liste_ort_ds.tpl", "Farbwechsel", "td1", "td2");
            $tplOrgellisterubrik = new Template("orgel_liste_ort_rubrik_first.tpl");
            $tplOrgellisteRubrik2 = new Template("orgel_liste_ort_rubrik.tpl");
        } else {
            $tplOrgellisteDs = new BufferedTemplate("orgel_liste_ds.tpl", "Farbwechsel", "td1", "td2");
            $tplOrgellisterubrik = new Template("orgel_liste_rubrik_first.tpl");
            $tplOrgellisteRubrik2 = new Template("orgel_liste_rubrik.tpl");
        }
        
        $oldindex = - 1;
        $strChecked1 = "";
        $strChecked2 = "";
        $strChecked3 = "";
        $strChecked4 = "";
        $boFirst = true;
        
        $xForQuickJump = OrgelUtilities::getOrgelListe();
        
        // Bei wenig Kunden immer den Gesamtbestand anzeigen
        if (! isset($_GET['index']) && $xForQuickJump->getSize() < ConstantLoader::getMindestAnzahlOrgelnFuerGruppierung()) {
            $_GET['index'] = "all";
        }
        
        $handler = new OrgelRequestHandler();
        $handledRequest = $handler->prepareOrgelListe();
        
        if (isset($_SESSION['suchbegriff']['ost_id-1']) && $_SESSION['suchbegriff']['ost_id-1'] != "") {
            $strChecked1 = Constant::$HTML_CHECKED_CHECKED;
        }
        if (isset($_SESSION['suchbegriff']['ost_id-2']) && $_SESSION['suchbegriff']['ost_id-2'] != "") {
            $strChecked2 = Constant::$HTML_CHECKED_CHECKED;
        }
        if (isset($_SESSION['suchbegriff']['ost_id-3']) && $_SESSION['suchbegriff']['ost_id-3'] != "") {
            $strChecked3 = Constant::$HTML_CHECKED_CHECKED;
        }
        if (isset($_SESSION['suchbegriff']['nichtzugeordnet']) && $_SESSION['suchbegriff']['nichtzugeordnet'] != "") {
            $strChecked4 = Constant::$HTML_CHECKED_CHECKED;
        }
        
        $tplOrgeldetails->replace("SessionID", session_id());
        $tplOrgeldetails->replace("checked1", $strChecked1);
        $tplOrgeldetails->replace("checked2", $strChecked2);
        $tplOrgeldetails->replace("checked3", $strChecked3);
        $tplOrgeldetails->replace("checked4", $strChecked4);
        
        $tplOrgeldetails->replace("Dir", $handledRequest['TPLDIR']);
        $tplOrgeldetails->replace("Order", $handledRequest['TPLORDER']);
        $tplOrgeldetails->replace("Index", $handledRequest['INDEX']);
        $c = OrgelUtilities::getOrgelListe($handledRequest['SQLADD']);
        
        $tplOrgeldetails->replace("OrgelAnzahlAnzeige", $c->getSize());
        $tplOrgeldetails->replace("OrgelAnzahlGesamt", OrgelUtilities::getAnzahlOrgeln());
        
        foreach ($c as $oOrgel) {
            // Neue Rubrik einfuegen, wenn neuer Anfangsbuchstabe/Zeichen
            if (isset($_GET['order']) && $_GET['order'] == "bezirk") {
                $newindex = $oOrgel->getGemeindeBezirk();
            } elseif (isset($_GET['order']) && $_GET['order'] == "konfession") {
                $newindex = $oOrgel->getKID();
            } elseif (isset($_GET['order']) && $_GET['order'] == "baujahr") {
                $newindex = $oOrgel->getBaujahr();
            } elseif (isset($_GET['order']) && $_GET['order'] == "wartung") {
                if (strlen($oOrgel->getLetztePflege()) == 10) {
                    $newindex = substr($oOrgel->getLetztePflege(), 6, 4);
                } else {
                    $newindex = $oOrgel->getLetztePflege();
                }
            } elseif (isset($_GET['order']) && $_GET['order'] == "erbauer") {
                $newindex = substr($oOrgel->getErbauer(), 0, 1);
            } elseif (isset($_GET['order']) && $_GET['order'] == "plz") {
                $newindex = substr($oOrgel->getGemeindePLZ(), 0, 1);
            } elseif (isset($_GET['order']) && $_GET['order'] == "ort") {
                $newindex = substr($oOrgel->getGemeindeOrt(), 0, 1);
            } else {
                $standardSortierung = ConstantLoader::getGemeindeListeStandardSortierung();
                if ($standardSortierung == "ort") {
                    $newindex = substr($oOrgel->getGemeindeOrt(), 0, 1);
                } else {
                    $newindex = substr($oOrgel->getGemeindeNamen(), 0, 1);
                }
            }
            
            if ($newindex != $oldindex) {
                $tplOrgellisterubrik->replace("Rubrik", $newindex);
                $tplOrgellisterubrik->replace("Dir", $handledRequest['TPLDIR']);
                $tplOrgellisterubrik->replace("Index", $handledRequest['INDEX']);
                $tplOrgellisteDs->addToBuffer($tplOrgellisterubrik);
                if ($boFirst) {
                    $boFirst = false;
                    $tplOrgellisterubrik = $tplOrgellisteRubrik2;
                }
                $tplOrgellisterubrik->restoreTemplate();
            }
            
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
            
            // Werte ins Template einfuegen
            $tplOrgellisteDs->replace("OID", $oOrgel->getOrgelID());
            $tplOrgellisteDs->replace("GID", $oOrgel->getGemeindeID());
            $tplOrgellisteDs->replace("Gemeinde", $oOrgel->getGemeindeNamen());
            $tplOrgellisteDs->replace("Erbauer", $oOrgel->getErbauer());
            $tplOrgellisteDs->replace("Baujahr", $oOrgel->getBaujahr());
            $tplOrgellisteDs->replace("LetztePflege", $oOrgel->getLetztePflege(true));
            $tplOrgellisteDs->replace("Manuale", $manual);
            $tplOrgellisteDs->replace("Register", $oOrgel->getRegisterAnzahl());
            $tplOrgellisteDs->replace("PLZ", $oOrgel->getGemeindePLZ());
            $tplOrgellisteDs->replace("Ort", $oOrgel->getGemeindeOrt());
            $tplOrgellisteDs->replace("Bezirk", $oOrgel->getGemeindeBezirk());
            $tplOrgellisteDs->replace("Rubrik", $newindex);
            $tplOrgellisteDs->next();
            
            // Alten Index speichern
            $oldindex = $newindex;
        }
        
        // Orgeldatensätze ins Template einfügen
        $tplOrgeldetails->replace("Content", $tplOrgellisteDs->getOutput());
        
        // Quickjump einfügen
        $q = new Quickjump($xForQuickJump, $handledRequest['GETTER'], "index.php?page=2&do=20&order=" . $handledRequest['TPLORDER'] . "&dir=asc&index=<!--Index-->", $handledRequest['SKALA']);
        
        $tplOrgeldetails->replace("Quickjump", $q->getOutput());
        
        // Orgelliste ausgeben
        return $tplOrgeldetails;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see PostRequestHandler::preparePost()
     */
    public function preparePost()
    {
        return $this->prepareGet();
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see PostRequestHandler::executePost()
     */
    public function executePost()
    {
        return $this->executeGet();
    }
}