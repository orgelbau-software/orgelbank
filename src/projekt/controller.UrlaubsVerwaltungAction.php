<?php

class UrlaubsVerwaltungAction implements GetRequestHandler, PostRequestHandler, PostRequestValidator
{

    private $mFehlerMeldung = "";

    private $benutzerId;

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
        
        $c = BenutzerUtilities::getZeiterfassungsBenutzer();
        $htmlSelect = new HTMLSelect($c, "getBenutzername", $this->benutzerId);
        $tpl->replace("Mitarbeiter", $htmlSelect->getOutput());
        
        if (isset($_GET['action']) && $_GET['action'] == "jahresurlaub") {
            foreach ($c as $benutzer) {
                $urlaubsTage = $benutzer->getUrlaubsTage() / ($benutzer->getStdGesamt() / 5);
                $urlaub = new Urlaub();
                $urlaub->setBenutzerId($benutzer->getID());
                $urlaub->setResturlaub(0);
                $urlaub->setVerbleibend($urlaubsTage);
                $urlaub->setSumme($urlaub->getResturlaub() + $urlaub->getVerbleibend());
                $urlaub->setStatus(Urlaub::STATUS_ANGELEGT);
                $urlaub->setDatumVon(date("Y") . "-01-01");
                $urlaub->setDatumBis(date("Y") . "-12-31");
                $urlaub->speichern(false);
            }
        }
        
        if ($this->benutzerId != "" && $this->benutzerId != 0) {
            $filterBenutzer = "u.be_id = " . $this->benutzerId;
        } else {
            $filterBenutzer = "";
        }
        $u = UrlaubsUtilities::getUrlaubsEintraege($filterBenutzer);
        $tplDS = new BufferedTemplate("projekt_urlaub_liste_ds.tpl", "CSS", "td1", "td2");
        foreach ($u as $urlaubseintrag) {
            $tplDS->replace("UrlaubsID", $urlaubseintrag->getID());
            $tplDS->replace("DatumVon", $urlaubseintrag->getDatumVon(true));
            $tplDS->replace("DatumBis", $urlaubseintrag->getDatumBis(true));
            $tplDS->replace("Benutzername", $urlaubseintrag->getBenutzername());
            $tplDS->replace("Tage", $urlaubseintrag->getTage());
            $tplDS->replace("Status", $urlaubseintrag->getStatus());
            $tplDS->replace("Verbleibend", $urlaubseintrag->getVerbleibend());
            $tplDS->replace("Resturlaub", $urlaubseintrag->getResturlaub());
            $tplDS->replace("Summe", $urlaubseintrag->getSumme());
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
        $this->benutzerId = intval($_POST['benutzerId']);
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

    /**
     *
     * {@inheritdoc}
     *
     * @see PostRequestValidator::validatePostRequest()
     */
    public function validatePostRequest()
    {
        return isset($_POST['benutzerId']);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see PostRequestValidator::handleInvalidPost()
     */
    public function handleInvalidPost()
    {
        return new HTMLStatus("Leider ist etwas schief gegangen.");
    }
}