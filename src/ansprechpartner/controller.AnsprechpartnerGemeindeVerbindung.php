<?php

class AnsprechpartnerGemeindeVerbindung implements GetRequestHandler
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
        if (! $_POST || ! isset($_POST['aid'], $_POST['gemeinde']) || "" == $_POST['gemeinde']) {
            $htmlStatus = new HTMLStatus("Fehlerhafte Auswahl!", HTMLStatus::$STATUS_ERROR);
            $aid = (isset($_POST['aid']) ? $_POST['aid'] : "");
            $html = new HTMLRedirect($htmlStatus->getOutput(), "index.php?page=3&do=40&aid=" . $aid);
        } else {
            
            $oA = new Ansprechpartner(intval($_POST['aid']));
            $oG = new Gemeinde(intval($_POST['gemeinde']));
            
            if (AnsprechpartnerController::addAnsprechpartnerZuGemeinde($oA->getID(), $oG->getID())) {
                $htmlStatus = new HTMLStatus("Der Gemeinde " . $oG->getKirche() . " wurde " . $oA->getAnrede() . " " . $oA->getNachname() . " (" . $oA->getFunktion() . ") als Ansprechpartner hinzugef&uuml;gt!", HTMLStatus::$STATUS_OK);
                $html = new HTMLRedirect($htmlStatus->getOutput(), "index.php?page=3&do=40&aid=" . $oA->getID());
            } else {
                $htmlStatus = new HTMLStatus("Der Gemeinde " . $oG->getKirche() . " wurde zuvor bereits " . $oA->getAnrede() . " " . $oA->getNachname() . " (" . $oA->getFunktion() . ") als Ansprechpartner hinzugef&uuml;gt!", HTMLStatus::$STATUS_ERROR);
                $html = new HTMLRedirect($htmlStatus->getOutput(), "index.php?page=3&do=40&aid=" . $oA->getID(), ConstantLoader::getDefaultRedirectSecondsFalse());
            }
        }
        return $html;
    }
}