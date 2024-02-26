<?php

class GemeindeKarteAction implements GetRequestHandler, PostRequestHandler
{

    private $mPflegevertrag = "";
    private $mOffeneWartungen = "";

    public function __construct()
    {}

    public function validatePostRequest()
    {
        return true;
    }

    public function handleInvalidPost()
    {
        $htmlStatus = new HTMLRedirect();
        $htmlStatus->setLink("index.php?page=1&do=1");
        return $htmlStatus;
    }

    public function validateGetRequest()
    {
        // return isset($_GET['gid']);
        return true;
    }

    public function handleInvalidGet()
    {
        $htmlStatus = new HTMLRedirect();
        $htmlStatus->setLink("index.php?page=1&do=1");
        return $htmlStatus;
    }

    public function preparePost()
    {
        if (isset($_POST['pflegevertrag'])) {
            $this->mPflegevertrag = intval($_POST['pflegevertrag']);
        }
        if (isset($_POST['offenewartungen'])) {
            $this->mOffeneWartungen = intval($_POST['offenewartungen']);
        }
    }

    public function prepareGet()
    {
        // $_GET['gid'] = intval($_GET['gid']);
    }

    public function executeGet()
    {
        $tpl = new Template("gemeinde_karte.tpl");
        $tpl->replace("checked1", ($this->mPflegevertrag == "" ? "" : Constant::$HTML_CHECKED_CHECKED));
        $tpl->replace("checked2", ($this->mOffeneWartungen == "" ? "" : Constant::$HTML_CHECKED_CHECKED));
        $tpl->replace("GoogleAPIKey", GOOGLE_MAPS_API_KEY);
        $tpl->replace("InstanceUrl", INSTANCE_URL);
        
        $tplMarker = new BufferedTemplate("gemeinde_karte_marker.tpl");
        $tmp = GemeindeUtilities::loadGemeindeLandkarte();
        $anzahlAlle = $tmp->getSize();
        
        $alleGemeinden = GemeindeUtilities::loadGemeindeLandkarte($this->mPflegevertrag, $this->mOffeneWartungen);
        $anzahlAnzeige = $alleGemeinden->getSize();
        
        foreach ($alleGemeinden as $current) {
            $tplMarker->replace("Lat", $current->getLat());
            $tplMarker->replace("Lng", $current->getLng());
            $tplMarker->replace("Ort", $current->getOrt());
            $tplMarker->replace("PLZ", $current->getPLZ());
            $tplMarker->replace("Kirche", $current->getKirche());
            if ($current->getLetztePflege() == null || "1970-01-01" == $current->getLetztePflege()) {
                $tplMarker->replace("LetztePflege", "Keine");
            } else {
                $tplMarker->replace("LetztePflege", (date("d.m.y", strtotime($current->getLetztePflege()))));
            }
            if(empty($current->getNaechstePflege())) {
                $tplMarker->replace("NaechstePflege", "unbekannt");
            } else {
                $tplMarker->replace("NaechstePflege", (date("d.m.y", strtotime($current->getNaechstePflege()))));
            }
            $tplMarker->replace("Bezirk", $current->getBezirkId());
            $tplMarker->replace("Register", $current->getAnzahlRegister());
            $tplMarker->replace("Pflegevertrag", ($current->getPflegevertrag() == "1" ? "Ja" : "Nein"));
            $tplMarker->replace("Zyklus", $current->getZyklus());
            $tplMarker->replace("Title", $current->getPflegevertrag());
            $tplMarker->replace("Massnahmen", addslashes(str_replace("\r\n", " ", $current->getMassnahmen())));
            $tplMarker->replace("AID", $current->getAdressId());
            $tplMarker->replace("OrgelId", $current->getOrgelId());
            $tplMarker->next();
        }
        $tpl->replace("OrgelAnzahlAnzeige", $anzahlAnzeige);
        $tpl->replace("OrgelAnzahlGesamt", $anzahlAlle);
        
        $firmensitz = new Ansprechpartner(1);
        $tpl->replace("FirmensitzLat", $firmensitz->getAdresse()->getLat());
        $tpl->replace("FirmensitzLng", $firmensitz->getAdresse()->getLng());
        $tpl->replace("Marker", $tplMarker->getOutput());
        return $tpl;
    }

    public function executePost()
    {
        return $this->executeGet();
    }
}
?>