<?php

class ArbeitszeitVerwaltungAction implements GetRequestHandler
{

    private $mFehlerMeldung = "";

    /**
     *
     * {@inheritdoc}
     *
     * @see GetRequestHandler::executeGet()
     */
    public function executeGet()
    {
        $tpl = new Template("projekt_zeiten.tpl");
        
        $filter = "";
        if (isset($_GET['filter'])) {
            if ($_GET['filter'] == "alle") {
                $filter = "";
            } else if ($_GET['filter'] == "aktuell") {
                $filter = "WHERE aw_wochenstart >= \"" . date("Y-m-d", strtotime("-8 weeks")) . "\"";
            } else {
                $filter = "WHERE aw_jahr = " . intval($_GET['filter']);
            }
        } else {
            $filter = "WHERE aw_wochenstart >= \"" . date("Y-m-d", strtotime("-8 weeks")) . "\"";
        }
        
        $c = ArbeitswocheUtilities::ladeArbeitswochen($filter);
        $tplDS = new BufferedTemplate("projekt_zeiten_ds.tpl", "CSS", "td1", "td2");
        $tmpKW = 0;
        
        // Benutzerdaten laden & in Array cachen
        $cBenutzer = BenutzerUtilities::getAlleBenutzer();
        $mitarbeiter = array();
        foreach ($cBenutzer as $benutzer) {
            if ($benutzer->getGeloescht() == 1) {
                $benutzer->setBenutzername($benutzer->getBenutzername() . " [gel&ouml;scht]");
            }
            $mitarbeiter[$benutzer->getID()] = $benutzer;
        }
        $rowId = 0;
        $wocheKomplett = true;
        $wocheGebucht = true;
        $aufgeklappteWochen = 2;
        $jsArrayInput = "";
        
        if ($c->getSize() > 0) {
            foreach ($c as $kw) {
                if ($mitarbeiter[$kw->getBenutzerId()]->getGeloescht() != 1 || ($mitarbeiter[$kw->getBenutzerId()]->getGeloescht() == 1 && $kw->getWochenStundenIst() > 0)) {
                    if ($tmpKW != $kw->getKalenderWoche()) {
                        
                        // GesamtwochenStatus muss vorher geprüft werden
                        if ($rowId > 0) {
                            if ($wocheGebucht) {
                                $tplDS->replaceInBuffer("WocheGesamtStatus", "Woche gebucht");
                                $tplDS->replaceInBuffer("WocheGesamtStatusClass", "awStatusGebucht");
                                $tplDS->replaceInBuffer("BuchenDisabled", "disabled");
                            } elseif ($wocheKomplett) {
                                $tplDS->replaceInBuffer("WocheGesamtStatus", "Fertig zur Freigabe");
                                $tplDS->replaceInBuffer("WocheGesamtStatusClass", "awStatusFertig");
                            } else {
                                $tplDS->replaceInBuffer("WocheGesamtStatus", "Offen");
                                $tplDS->replaceInBuffer("WocheGesamtStatusClass", "awStatusOffen");
                            }
                            $tplDS->replaceInBuffer("BuchenDisabled", "");
                        }
                        $wocheKomplett = true;
                        $wocheGebucht = true;
                        
                        $arbeitswoche = Date::berechneArbeitswoche(strtotime($kw->getWochenstart()));
                        $tplDS->replace("KW", $kw->getKalenderWoche());
                        $tplDS->replace("Jahr", $kw->getJahr());
                        $tplDS->replace("TSWoche", strtotime($kw->getWochenstart()));
                        $tplDS->replace("DatumVon", $arbeitswoche[0]);
                        $tplDS->replace("DatumBis", $arbeitswoche[6]);
                        $rowId = $kw->getID();
                        $tplDS->replace("RowID", $rowId);
                        $tplDS->next();
                        
                        if ($aufgeklappteWochen > 0) {
                            $x = ProjektController::ajaxGetMitarbeiterWochenStunden(strtotime($kw->getWochenstart()));
                            $tplDS->addToBufferBT($x);
                            $aufgeklappteWochen --;
                            if (strlen($jsArrayInput) > 0) {
                                $jsArrayInput .= ",";
                            }
                            $jsArrayInput .= strtotime($kw->getWochenstart());
                        }
                    }
                    
                    if ($kw->getEingabeGebucht() == false) {
                        $wocheKomplett &= false;
                    }
                    $wocheGebucht &= $kw->getEingabeGebucht();
                    
                    $tmpKW = $kw->getKalenderWoche();
                }
            }
            
            // Für den letzten Datensatz aus der Schleife noch den WochenStatus setzen
            if ($wocheKomplett) {
                $tplDS->replaceInBuffer("WocheGesamtStatus", "Fertig");
                $tplDS->replaceInBuffer("WocheGesamtStatusClass", "awStatusFertig");
            } else {
                $tplDS->replaceInBuffer("WocheGesamtStatus", "Offen");
                $tplDS->replaceInBuffer("WocheGesamtStatusClass", "awStatusOffen");
            }
            $tplDS->replaceInBuffer("BuchenDisabled", "");
        } else {
            $tplDS = new Template("projekt_zeiten_ds_keine.tpl");
            $tplDS->replace("x", ""); // dummy
        }
        
        $tpl->replace("Datensaetze", $tplDS->getOutput());
        $tpl->replace("jsArrayInput", $jsArrayInput);
        return $tpl;
    }

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
        return new HTMLStatus($this->mFehlerMeldung, HTMLStatus::$STATUS_ERROR);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see GetRequestHandler::prepareGet()
     */
    public function prepareGet()
    {}
}