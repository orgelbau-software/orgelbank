<?php

class JahresurlaubAnlegenAction implements GetRequestHandler, PostRequestHandler, PostRequestValidator
{

    private $mFehlerMeldung = "";

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
        $tpl = new Template("projekt_jahresurlaub_eingabe.tpl");
        $tpl->replace("Jahr", date("Y"));
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
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see PostRequestHandler::executePost()
     */
    public function executePost()
    {
        $c = BenutzerUtilities::getZeiterfassungsBenutzer();
        
        foreach ($c as $benutzer) {
            $urlaubsTage = $benutzer->getUrlaubsTage() / ($benutzer->getStdGesamt() / 5);
            $urlaub = new Urlaub();
            $urlaub->setBenutzerId($benutzer->getID());
            $urlaub->setVerbleibend($urlaubsTage);
            if(isset($_POST['resturlaub']) && $_POST['resturlaub'] == "on") {
                $urlaub->setSumme($urlaub->getResturlaub() + $urlaub->getVerbleibend());
            } else {
                $urlaub->setSumme($urlaub->getVerbleibend());
            }
            $urlaub->setResturlaub(0);
            $urlaub->setStatus(Urlaub::STATUS_ANGELEGT);
            $urlaub->setDatumVon($_POST['jahr'] . "-01-01");
            $urlaub->setDatumBis($_POST['jahr'] . "-12-31");
            $urlaub->speichern(false);
        }
        
        $htmlStatus = new HTMLStatus("Jahresurlaub angelegt.");
        $htmlRedirect = new HTMLRedirect($htmlStatus->getOutput(), "index.php?page=6&do=115");
        return $htmlRedirect;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see PostRequestValidator::validatePostRequest()
     */
    public function validatePostRequest()
    {
        return isset($_POST['jahr']) && strlen($_POST['jahr']) == 4;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see PostRequestValidator::handleInvalidPost()
     */
    public function handleInvalidPost()
    {
        $htmlStatus = new HTMLStatus("Leider ist etwas schief gegangen.");
        $htmlRedirect = new HTMLRedirect($htmlStatus->getOutput(), "index.php?page=6&do=115");
        return $htmlRedirect;
    }
}