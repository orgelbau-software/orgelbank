<?php

class UrlaubsVerwaltungAction implements GetRequestHandler, PostRequestHandler, PostRequestValidator
{

    private $mFehlerMeldung = "";

    private $benutzerId;

    private $jahresauswahl;

    /**
     *
     * {@inheritdoc}
     *
     * @see GetRequestHandler::validateGetRequest()
     */
    public function validateGetRequest()
    {
        global $webUser;
        if (! $webUser->isAdmin()) {
            $this->mFehlerMeldung = "Keine Berechtigung";
            return false;
        }
        
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
    {
        if (isset($_GET['benutzerId'])) {
            $this->benutzerId = intval($_GET['benutzerId']);
        }
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see GetRequestHandler::executeGet()
     */
    public function executeGet()
    {
        global $webUser;
        $benutzer = $webUser->getBenutzer();
        
        $tpl = new Template("projekt_urlaub.tpl");
        
        if (isset($_GET['action'], $_GET['uid']) && $_GET['action'] == "delete") {
            $urlaubsTagId = intval($_GET['uid']);
            $zuLoeschen = new Urlaub($urlaubsTagId);
            $letzterUrlaubsTag = UrlaubsUtilities::getLetzterUrlaubsEintrag($zuLoeschen->getBenutzerId());
            if ($zuLoeschen->getID() == $letzterUrlaubsTag->getID()) {
                $letzterUrlaubsTag->loeschen();
                $this->mFehlerMeldung = new HTMLStatus("Urlaubseintrag erfolgreich gelöscht");
            } else {
                $this->mFehlerMeldung = new HTMLStatus("Urlaubseintrag konnte nicht gelöscht werden.", HTMLStatus::$STATUS_ERROR);
            }
        }
        
        $c = BenutzerUtilities::getZeiterfassungsBenutzer();
        $htmlSelect = new HTMLSelect($c, "getBenutzername", $this->benutzerId);
        $tpl->replace("Mitarbeiter", $htmlSelect->getOutput());
        
        $jahre = array();
        $startJahr = 2022;
        for ($i = $startJahr; $i <= date("Y") + 1; $i ++) {
            $jahre[$i] = $i;
        }
        
        $htmlJahre = new HTMLSelectForArray($jahre, $this->jahresauswahl);
        $tpl->replace("Jahresauswahl", $htmlJahre->getOutput());
        
        // Status Abfrage
        if ($this->mFehlerMeldung != null) {
            $tpl->replace("Statusmeldung", $this->mFehlerMeldung->getOutput());
        }
        
        // Urlaubskontrolle
        $tpl->replace("DatumVon", "");
        $tpl->replace("DatumBis", "");
        $tpl->replace("Tage", "1");
        $tpl->replace("Bemerkung", "");
        $tpl->replace("SubmitValue", "Speichern");
        
        if ($this->benutzerId != "" && $this->benutzerId != 0) {
            $filterBenutzer = "u.be_id = " . $this->benutzerId;
        } else {
            $filterBenutzer = "";
        }
        
        if ($this->jahresauswahl > 0) {
            if ($filterBenutzer != "") {
                $filterBenutzer .= " AND ";
            }
            $filterBenutzer .= " DATE(u.u_datum_von) >= '" . $this->jahresauswahl . "-01-01' ";
        }
        
        $letzteUrlaubsTage = UrlaubsUtilities::getLetzteUrlaubsTagsIdProBenutzer();
        
        $u = UrlaubsUtilities::getUrlaubsEintraege($filterBenutzer);
        
        $tplDS = new BufferedTemplate("projekt_urlaub_liste_ds.tpl", "CSS", "td1", "td2");
        $tplIconLoeschen = new Template("projekt_urlaub_liste_ds_loeschen.tpl");
        $tplIconNichtLoeschen = new Template("projekt_urlaub_liste_ds_nichtloeschen.tpl");
        foreach ($u as $urlaubseintrag) {
            $tplDS->replace("UrlaubsID", $urlaubseintrag->getID());
            $tplDS->replace("DatumVon", $urlaubseintrag->getDatumVon(true));
            
            if($urlaubseintrag->getDatumBis() == null || $urlaubseintrag->getDatumBis(true) == "01.01.1970") {
                $tplDS->replace("DatumBis","");
            } else {
                $tplDS->replace("DatumBis", $urlaubseintrag->getDatumBis(true));
            }
            $tplDS->replace("Benutzername", $urlaubseintrag->getBenutzername());
            $tplDS->replace("Tage", $urlaubseintrag->getTage());
            $tplDS->replace("Status", $urlaubseintrag->getStatus());
            $tplDS->replace("Verbleibend", $urlaubseintrag->getVerbleibend());
            if ($urlaubseintrag->getResturlaub() == 0) {
                $tplDS->replace("Resturlaub", "");
            } else {
                $tplDS->replace("Resturlaub", $urlaubseintrag->getResturlaub());
            }
            $tplDS->replace("Bemerkung", $urlaubseintrag->getBemerkung());
            $tplDS->replace("Summe", $urlaubseintrag->getSumme());
            
            if (isset($letzteUrlaubsTage[$urlaubseintrag->getBenutzerId()]) && $letzteUrlaubsTage[$urlaubseintrag->getBenutzerId()] == $urlaubseintrag->getID()) {
                $tplIconLoeschen->replace("UrlaubsID", $urlaubseintrag->getID());
                $tplDS->replace("LoeschenIcon", $tplIconLoeschen->getOutput());
                $tplIconLoeschen->reset();
            } else {
                $tplDS->replace("LoeschenIcon", $tplIconNichtLoeschen->forceOutput());
            }
            $tplDS->next();
        }
        
        $tpl->replace("UrlaubsListe", $tplDS->getOutput());
        return $tpl;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see PostRequestHandler::preparePost()
     */
    public function preparePost()
    {
        if (isset($_POST['benutzerId'])) {
            $this->benutzerId = intval($_POST['benutzerId']);
        } else if (isset($_POST['quickswitchBenutzerId'])) {
            $this->benutzerId = intval($_POST['quickswitchBenutzerId']);
        }
        
        if (isset($_POST['quickswitchJahr'])) {
            $this->jahresauswahl = $_POST['quickswitchJahr'];
        } else {
            $this->jahresauswahl = date("Y");
        }
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see PostRequestHandler::executePost()
     */
    public function executePost()
    {
        if (isset($_POST['datumvon'])) {
            $statusOrTrue = UrlaubsUtilities::bucheUrlaub($this->benutzerId, date("Y-m-d", strtotime($_POST['datumvon'])), date("Y-m-d", strtotime($_POST['datumbis'])), $_POST['tage'], $_POST['urlaubstyp'], Urlaub::STATUS_MANUELL, $_POST['bemerkung']);
            if($statusOrTrue !== true) {
                $this->mFehlerMeldung = $statusOrTrue;
            }
            $this->benutzerId = 0;
        }
        
        return $this->executeGet();
    }

    

    /**
     *
     * {@inheritdoc}
     *
     * @see PostRequestValidator::validatePostRequest()
     */
    public function validatePostRequest()
    {
        return isset($_POST['quickswitchBenutzerId']) && ! isset($_POST['datumvon']) || isset($_POST['datumvon']) && $_POST['datumvon'] != "";
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see PostRequestValidator::handleInvalidPost()
     */
    public function handleInvalidPost()
    {
        $status = new HTMLStatus("Bitte wählen Sie einen Benutzer aus. Bei Urlaubskorrekturen muss das Feld \"Datum Von\" gesetzt sein.", HTMLStatus::$STATUS_ERROR);
        
        return new HTMLRedirect($status->getOutput(), "index.php?page=6&do=115");
    }
}