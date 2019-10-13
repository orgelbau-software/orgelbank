<?php

class GemeindeGoogleMapsForwardAction implements GetRequestHandler
{

    public function __construct()
    {}

    public function validateGetRequest()
    {
        return isset($_GET['start'], $_GET['end']);
    }

    public function handleInvalidGet()
    {
        $htmlStatus = new HTMLRedirect();
        $htmlStatus->setLink("index.php?page=1&do=1");
        return $htmlStatus;
    }

    public function prepareGet()
    {
        $_GET['start'] = intval($_GET['start']);
        $_GET['end'] = intval($_GET['end']);
    }

    public function executeGet()
    {
        $oOrgelStart = new Orgel($_GET['start']);
        $oOrgelEnd = new Orgel($_GET['end']);
        
        $oGemeindeStart = new Gemeinde($oOrgelStart->getGemeindeId());
        $oGemeindeEnde = new Gemeinde($oOrgelEnd->getGemeindeId());
        
        $startAdresse = $oGemeindeStart->getKircheAdresse()->getFormattedAdress();
        $endAdresse = $oGemeindeEnde->getKircheAdresse()->getFormattedAdress();
        
        // https://www.google.de/maps/dir/Twierweg+35A,+D-37671+Höxter,+Deutschland/Baumschulweg+22,+37688+Beverungen/
        $startAdresse = str_replace(" ", "+", $startAdresse);
        $endAdresse = str_replace(" ", "+", $endAdresse);
        
        $url = "https://www.google.de/maps/dir/" . $startAdresse . "/" . $endAdresse . "/data=!4m2!4m1!3e0";
        
        header('Location: ' . $url);
        return null;
    }
}
?>