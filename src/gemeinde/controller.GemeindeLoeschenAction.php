<?php

class GemeindeLoeschenAction implements GetRequestHandler, PostRequestHandler
{

    public function __construct()
    {}

    public function validatePostRequest()
    {
        return isset($_POST['objektid']);
    }

    public function handleInvalidPost()
    {
        $htmlStatus = new HTMLRedirect();
        $htmlStatus->setLink("index.php?page=1&do=1");
        return $htmlStatus;
    }

    public function validateGetRequest()
    {
        return isset($_GET['gid']);
    }

    public function handleInvalidGet()
    {
        $htmlStatus = new HTMLRedirect();
        $htmlStatus->setLink("index.php?page=1&do=1");
        return $htmlStatus;
    }

    public function preparePost()
    {
        $_POST['objektid'] = intval($_POST['objektid']);
    }

    public function prepareGet()
    {
        $_GET['gid'] = intval($_GET['gid']);
    }

    public function executeGet()
    {
        $o = new Gemeinde(intval($_GET['gid']));
        
        $tpl = new HTMLSicherheitsAbfrage();
        $tpl->setText("M&ouml;chten Sie die Gemeinde\"" . $o->getKirche() . "\" wirklich endg&uuml;ltig l&ouml;schen? ");
        $tpl->setButtonJa("Ja, Gemeinde l&ouml;schen!");
        $tpl->setButtonNein("Nein, zur&uuml;ck");
        $tpl->setButtonNeinLink("index.php?page=1&do=1");
        $tpl->setFormLink("index.php?page=1&do=5");
        $tpl->setObjektID($o->getID());
        
        return $tpl;
    }

    public function executePost()
    {
        $oGemeinde = new Gemeinde($_POST['objektid']);
        $oGemeinde->loeschen();
        
        $htmlStatus = new HTMLRedirect();
        $htmlStatus->setLink("index.php?page=1&do=1");
        $htmlStatus->setNachricht("Gemeinde erfolgreich gel&ouml;scht.");
        $htmlStatus->setSekunden(ConstantLoader::getDefaultRedirectSecondsTrue());
        
        return $htmlStatus;
    }
}
?>