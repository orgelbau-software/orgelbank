<?php

class OrgelLoeschen implements GetRequestHandler, PostRequestHandler
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

    public function preparePost()
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
        if (! isset($_GET['oid']) && ! isset($_POST['objektid'])) {
            return new HTMLStatus("Problem", HTMLStatus::$STATUS_ERROR,false);
        }
        
        if ($_POST && isset($_POST['objektid'])) {
            
            $oOrgel = new Orgel($_POST['objektid']);
            $oOrgel->setAktiv(0);
            $oOrgel->speichern(false);
            
            $htmlStatus = new HTMLRedirect();
            $htmlStatus->setLink("index.php?page=2&do=20");
            $htmlStatus->setNachricht("Orgel erfolgreich gel&ouml;scht.");
            $htmlStatus->setSekunden(ConstantLoader::getDefaultRedirectSecondsTrue());
            
            return $htmlStatus;
        } else {
            $o = new Orgel(intval($_GET['oid']));
            
            $tpl = new HTMLSicherheitsAbfrage();
            $tpl->setText("M&ouml;chten Sie die Orge wirklich endg&uuml;ltig l&ouml;schen? ");
            $tpl->setButtonJa("Ja, Orgel l&ouml;schen!");
            $tpl->setButtonNein("Nein, zur&uuml;ck");
            $tpl->setButtonNeinLink("index.php?page=2&do=20");
            $tpl->setFormLink("index.php?page=2&do=27");
            $tpl->setObjektID($o->getID());
            
            return $tpl;
        }
    }

    public function executePost()
    {
        if (! isset($_POST['objektid'])) {
            return new HTMLStatus("Problem", HTMLStatus::$STATUS_ERROR,false);
        }
        
            
        $oOrgel = new Orgel($_POST['objektid']);
        $oOrgel->setAktiv(0);
        $oOrgel->speichern(false);
           
        $htmlStatus = new HTMLRedirect();
        $htmlStatus->setLink("index.php?page=2&do=20");
        $htmlStatus->setNachricht("Orgel erfolgreich gel&ouml;scht.");
        $htmlStatus->setSekunden(ConstantLoader::getDefaultRedirectSecondsTrue());
            
        return $htmlStatus;
        
    }
}