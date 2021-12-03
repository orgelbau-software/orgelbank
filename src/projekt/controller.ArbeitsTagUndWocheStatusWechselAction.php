<?php

class ArbeitsTagUndWocheStatusWechselAction implements GetRequestHandler
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
        
        if (! isset($_GET['uid'])) {
            $this->mFehlerMeldung = "Keine BenutzerID";
            return false;
        }
        
        if (! isset($_GET['status'])) {
            $this->mFehlerMeldung = "Kein Status";
            return false;
        }
        
        if (! isset($_GET['date'])) {
            $this->mFehlerMeldung = "Kein Datum";
            return false;
        }
        
        $requestedStatus = intval($_GET['status']);
        if ($requestedStatus != Arbeitstag::$STATUS_GEBUCHT && $requestedStatus != Arbeitstag::$STATUS_KOMPLETT && $requestedStatus != Arbeitstag::$STATUS_OFFEN && $requestedStatus != Arbeitstag::$STATUS_WIEDEROEFFNEN_UND_BEARBEITEN) {
            $this->mFehlerMeldung = "Ungueltiger Status: " . $requestedStatus;
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
    {}

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
        
        $status = intval($_GET['status']);
        
        $uid = intval($_GET['uid']);
        $date = $_GET['date'];
        
        $weiterleitung = "index.php?page=6&do=108";
        
        if ($status == Arbeitstag::$STATUS_GEBUCHT) {
            ArbeitstagUtilities::markBenutzerArbeitswocheGebucht($date, $uid);
            $htmlStatus = new HTMLStatus("Die Arbeitswoche wurde gebucht.");
        } else if ($status == Arbeitstag::$STATUS_KOMPLETT) {
            ArbeitstagUtilities::markBenutzerArbeitswocheKomplett($date, $uid);
            $htmlStatus = new HTMLStatus("Die Arbeitswoche wurde als komplett markiert.");
        } else if ($status == Arbeitstag::$STATUS_OFFEN) {
            ArbeitstagUtilities::markBenutzerArbeitswocheOffen($date, $uid);
            $htmlStatus = new HTMLStatus("Die Arbeitswoche wurde zur Buchung geöffnet.");
            
            // TODO: EMail an Mitarbeiter schicken?
            
        } else if ($status == Arbeitstag::$STATUS_WIEDEROEFFNEN_UND_BEARBEITEN) {
            ArbeitstagUtilities::markBenutzerArbeitswocheOffen($date, $uid);
            $htmlStatus = new HTMLStatus("Die Arbeitswoche wurde zur Buchung geöffnet. Sie werden zur Bearbeitung weitergeleitet.");
            $weiterleitung = "index.php?page=6&do=101&uid=" . $uid . "&date=" . $date;
        } else {
            $htmlStatus = new HTMLStatus("Ein Fehler ist aufgetreten.");
        }
        
        $htmlRedirect = new HTMLRedirect($htmlStatus->getOutput(), $weiterleitung);
        return $htmlRedirect;
    }
}