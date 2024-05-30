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
        $suchbegriff = "";
        $boFirst = true;

        $handler = new OrgelRequestHandler();
        $handledRequest = $handler->prepareOrgelListe();
        
        $orgelStatusSelection = $handledRequest->getValueOf('ORGELSTATUS');
        if (in_array(Orgel::ORGEL_STATUS_ID_NEUBAU, $orgelStatusSelection)) {
            $strChecked1 = Constant::$HTML_CHECKED_CHECKED;
            $_SESSION['suchbegriff']['neubau'] = "true";
        }
        
        if (in_array(Orgel::ORGEL_STATUS_ID_RENOVIERT, $orgelStatusSelection)) {
            $strChecked2 = Constant::$HTML_CHECKED_CHECKED;
            $_SESSION['suchbegriff']['removiert'] = "true";
        }
       
        if (in_array(Orgel::ORGEL_STATUS_ID_RESTAURIERT, $orgelStatusSelection)) {
            $strChecked3 = Constant::$HTML_CHECKED_CHECKED;
            $_SESSION['suchbegriff']['restauriert'] = "true";
        }
        
        //if (isset($_SESSION['suchbegriff']['nichtzugeordnet']) && $_SESSION['suchbegriff']['nichtzugeordnet'] != "") {
        //   $strChecked4 = Constant::$HTML_CHECKED_CHECKED;
        //}
        
        $suchbegriff = $handledRequest->getValueOf('SUCHBEGRIFF');
        $_SESSION['suchstring'] = $suchbegriff;
        
        $tplOrgeldetails->replace("SessionID", session_id());
        $tplOrgeldetails->replace("checked1", $strChecked1);
        $tplOrgeldetails->replace("checked2", $strChecked2);
        $tplOrgeldetails->replace("checked3", $strChecked3);
        $tplOrgeldetails->replace("checked4", $strChecked4);
        
        $tplOrgeldetails->replace("Dir", $handledRequest->getValueOf('TPLDIR'));
        $tplOrgeldetails->replace("Order", $handledRequest->getValueOf('TPLORDER'));
        $tplOrgeldetails->replace("Index", $handledRequest->getValueOf('INDEX'));
        $tplOrgeldetails->replace("Suchbegriff", $handledRequest->getValueOf('SUCHBEGRIFF'));
        
        $c = OrgelUtilities::getGesuchteOrgeln($handledRequest->getValueOf('SUCHBEGRIFF'), $handledRequest->getValueOf('ORGELSTATUS'), $handledRequest->getValueOf('RESULT'));
        $xForQuickJump = OrgelUtilities::getGesuchteOrgeln($handledRequest->getValueOf('SUCHBEGRIFF'), $handledRequest->getValueOf('ORGELSTATUS'));

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
                $tplOrgellisterubrik->replace("Dir", $handledRequest->getValueOf('TPLDIR'));
                $tplOrgellisterubrik->replace("Index", $handledRequest->getValueOf('INDEX'));
                $tplOrgellisteDs->addToBuffer($tplOrgellisterubrik);
                if ($boFirst) {
                    $boFirst = false;
                    $tplOrgellisterubrik = $tplOrgellisteRubrik2;
                }
                $tplOrgellisterubrik->restoreTemplate();
            }
            
            $manual = OrgelUtilities::getOrgelManualeUebersicht($oOrgel);
            
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
        $q = new Quickjump($xForQuickJump, $handledRequest->getValueOf('GETTER'), "index.php?page=2&do=20&order=" . $handledRequest->getValueOf('TPLORDER') . "&dir=asc&index=<!--Index-->", $handledRequest->getValueOf('SKALA'));
        
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
        if(isset($_POST['submit']) && $_POST['submit'] == "Zurücksetzen") {
            $_SESSION['suchbegriff'] = array();
            $_SESSION['suchstring'] = "";
        }
        return $this->executeGet();
    }
}