<?php

class AnsprechpartnerLoeschen implements GetRequestHandler, PostRequestHandler, PostRequestValidator
{

    /**
     *
     * {@inheritdoc}
     *
     * @return bool
     */
    public function validateGetRequest()
    {
        if (! isset($_GET['aid']))
            return false;
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
        return new HTMLStatus("Keine AnsprechpartnerID uebergeben");
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
        $retVal = null;
        if ($_POST && isset($_POST['objektid'])) {
            $o = new Ansprechpartner($_POST['objektid']);
            
            // Gemeindenzuordnung auch loeschen
            $gemeinden = GemeindeUtilities::getAnsprechpartnerGemeinden($o->getID());
            foreach ($gemeinden as $g) {
                AnsprechpartnerUtilities::loescheGemeindeAnsprechpartner($o->getID(), $g->getID());
            }
            
            $o->loeschen();
            
            $redirect = "index.php?page=3&do=40";
            // Wenn eine Orgel uebergeben wurde, dann gehen wir davon aus, dass es aus den WartungsDetails her gemacht wurde
            if (isset($_GET['oid'])) {
                $redirect = "index.php?page=2&do=28&oid=" . $_GET['oid'];
            }
            
            $retVal = new HTMLRedirect();
            $retVal->setLink($redirect);
            $retVal->setNachricht("Ansprechpartner erfolgreich gel&ouml;scht.");
            $retVal->setSekunden(ConstantLoader::getDefaultRedirectSecondsTrue());
        } else {
            $o = new Ansprechpartner(intval($_GET['aid']));
            
            $neinLink = "index.php?page=3&do=40";
            $jaLink = "index.php?page=3&do=42";
            if (isset($_GET['oid'])) {
                $neinLink .= "&oid=" . $_GET['oid'];
                $jaLink .= "&oid=" . $_GET['oid'];
            }
            
            $retVal = new HTMLSicherheitsAbfrage();
            $retVal->setText("M&ouml;chten Sie den Ansprechpartner \"" . $o->getVorname() . " " . $o->getNachname() . "\" wirklich endg&uuml;ltig l&ouml;schen? ");
            $retVal->setButtonJa("Ja, Ansprechpartner l&ouml;schen!");
            $retVal->setButtonNein("Nein, zur&uuml;ck");
            
            $retVal->setButtonNeinLink($neinLink);
            $retVal->setFormLink($jaLink);
            $retVal->setObjektID($o->getID());
        }
        return $retVal;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see PostRequestHandler::preparePost()
     */
    public function preparePost()
    {
        // TODO Auto-generated method stub
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
     * {@inheritDoc}
     * @see PostRequestValidator::validatePostRequest()
     */
    public function validatePostRequest()
    {
        if(! isset($_POST['objektid'])) {
            return false;
        }
        return true;
    }

    /**
     * {@inheritDoc}
     * @see PostRequestValidator::handleInvalidPost()
     */
    public function handleInvalidPost()
    {
        return new HTMLStatus("Keine AnsprechpartnerID uebergeben");
    }

}