<?php

class GemeindeListeAction implements GetRequestHandler, PostRequestHandler
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
        $tplGemeindeListe = new Template("gemeinde_liste.tpl");
        
        if(ConstantLoader::getGemeindeListeStandardSortierung() == "ort") {
            $tplGemeindeDS = new BufferedTemplate("gemeinde_liste_ort_ds.tpl", "Farbwechsel", "td1", "td2");
            $tplGemeindeRubrik = new Template("gemeinde_liste_ort_rubrik_first.tpl");
            $tplGemeindeRubrik2 = new Template("gemeinde_liste_ort_rubrik.tpl");
        } else {
            $tplGemeindeDS = new BufferedTemplate("gemeinde_liste_ds.tpl", "Farbwechsel", "td1", "td2");
            $tplGemeindeRubrik = new Template("gemeinde_liste_rubrik_first.tpl");
            $tplGemeindeRubrik2 = new Template("gemeinde_liste_rubrik.tpl");
        }
        $strRubriken = "";
        $boFirst = true;
        $iAnzahlGemeinden = GemeindeUtilities::getAnzahlGemeinden();
        // Rubriken für die Gemeindeansicht
        $konfession = KonfessionUtilities::getKonfessionenAsArray();
        
        // Bei wenig Kunden immer den Gesamtbestand anzeigen
        if (! isset($_GET['index']) && $iAnzahlGemeinden < ConstantLoader::getMindestAnzahlGemeindenFuerGruppierung()) {
            $_GET['index'] = "all";
        }
        
        $requestHandler = new GemeindeRequestHandler();
        $handledRequest = $requestHandler->prepareGemeindeListRequest();
        
        $tplGemeindeListe->replace("Dir", $handledRequest->getValueOf("TPLDIR"));
        $tplGemeindeListe->replace("Order", $handledRequest->getValueOf("TPLORDER"));
        
        $c = GemeindeUtilities::getGesuchteGemeinden($handledRequest->getValueOf("SUCHBEGRIFF"), $handledRequest->getValueOf("RESULT"));
        $x = GemeindeUtilities::getGesuchteGemeinden($handledRequest->getValueOf("SUCHBEGRIFF"));
        
        $tplGemeindeListe->replace("AnzahlGemeindenAnzeige", $c->getSize());
        $tplGemeindeListe->replace("AnzahlGemeindenGesamt", $iAnzahlGemeinden);
        
        $tplGemeindeListe->replace("SessionID", session_id());
        
        // Rubriken einbauen
        $oldindex = "null";
        $newindex = "foobar";
        
        // Array, der die Anfangszeichen speichert, damit sie nachher in der Rubrikenliste ausgegeben werden k�nnen
        $Anfangszeichen = array();
        foreach ($c as $oGemeinde) {
            
            // Neue Rubrik einfuegen, wenn neuer Anfangsbuchstabe/Zeichen
            if ($handledRequest->getValueOf("TPLORDER") == "bezirk") {
                $newindex = array(
                    "bezirk",
                    $oGemeinde->getGemeindeBezirk(),
                    $oGemeinde->getGemeindeBezirk()
                );
            } elseif ($handledRequest->getValueOf("TPLORDER") == "konfession") {
                $newindex = array(
                    "konfession",
                    $oGemeinde->getKID(),
                    $konfession[$oGemeinde->getKID()]
                );
            } elseif ($handledRequest->getValueOf("TPLORDER") == "ort") {
                $newindex = array(
                    "ort",
                    substr($oGemeinde->getGemeindeOrt(), 0, 1),
                    substr($oGemeinde->getGemeindeOrt(), 0, 1)
                );
            } elseif ($handledRequest->getValueOf("TPLORDER") == "plz") {
                $newindex = array(
                    "plz",
                    substr($oGemeinde->getGemeindePLZ(), 0, 1),
                    substr($oGemeinde->getGemeindePLZ(), 0, 1)
                );
            } else {
                $newindex = array(
                    "gemeinde",
                    substr($oGemeinde->getKirche(), 0, 1),
                    substr($oGemeinde->getKirche(), 0, 1)
                );
            }
            
            if ($newindex[1] != $oldindex[1]) {
                $tplGemeindeRubrik->replace("Rubrik", $newindex[2]);
                $tplGemeindeRubrik->replace("Dir", $handledRequest->getValueOf("TPLDIR"));
                $tplGemeindeRubrik->replace("Show", $handledRequest->getValueOf("TPLSHOW"));
                $tplGemeindeRubrik->replace("Index", $handledRequest->getValueOf("INDEX"));
                $tplGemeindeDS->addToBuffer($tplGemeindeRubrik);
                $tplGemeindeRubrik->restoreTemplate();
                if (trim($newindex[1]) != "")
                    $Anfangszeichen[] = $newindex;
                if ($boFirst) {
                    $boFirst = false;
                    $tplGemeindeRubrik = $tplGemeindeRubrik2;
                }
            }
            
            // Platzhalter ersetzen, Datensatz der Variablen anh�ngen, Template zur�cksetzen
            $tplGemeindeDS->replace("Gemeinde", $oGemeinde->getKirche());
            $tplGemeindeDS->replace("GemeindeID", $oGemeinde->getGemeindeID());
            $tplGemeindeDS->replace("PLZ", $oGemeinde->getGemeindePLZ());
            $tplGemeindeDS->replace("Ort", $oGemeinde->getGemeindeOrt());
            $tplGemeindeDS->replace("Land", ($oGemeinde->getGemeindeLand() == "Deutschland" ? "" : ", " . $oGemeinde->getGemeindeLand()));
            $tplGemeindeDS->replace("Konfession", $konfession[$oGemeinde->getKID()]);
            $tplGemeindeDS->replace("Bezirk", $oGemeinde->getGemeindeBezirk());
            
            if ($oGemeinde->getGeoStatus() != IGeolocationConstants::OK) {
                $tplGeoStatus = new Template("gemeinde_liste_geostatus.tpl");
                $tplGeoStatus->replace("Title", Constant::getGeoStatusUserMessage($oGemeinde->getGeoStatus()));
                $tplGemeindeDS->replace("GeoStatus", $tplGeoStatus->getOutput());
            }
            $tplGemeindeDS->replace("GeoStatus", "");
            $tplGemeindeDS->next();
            
            // Alten Index speichern
            $oldindex = $newindex;
        }
        
        // Gemeinden in Template einf�gen
        $tplGemeindeListe->replace("GemeindeListe", $tplGemeindeDS->getOutput());
        
        $suchbegriff = $handledRequest->getValueOf("SUCHBEGRIFF") == "" ? "Suchbegriff..." : $handledRequest->getValueOf("SUCHBEGRIFF");
        $tplGemeindeListe->replace("Suchbegriff", $suchbegriff);
        
        $lblArray = null;
        if ($handledRequest->getValueOf("TPLORDER") == "konfession") {
            $lblArray = $konfession;
        }
        
        $q = new Quickjump($x, $handledRequest->getValueOf("GETTER"), "index.php?page=1&do=1&order=" . $handledRequest->getValueOf("TPLORDER") . "&dir=asc&index=<!--Index-->", $handledRequest->getValueOf("SKALA"), $lblArray);
        $tplGemeindeListe->replace("Quickjump", $q->getOutput());
        
        // Template ausgeben
        return $tplGemeindeListe;
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