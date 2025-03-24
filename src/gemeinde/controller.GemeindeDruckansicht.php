<?php

class GemeindeDruckansicht implements GetRequestHandler, PostRequestHandler
{

    private $strSQLDir = "";

    private $strSQLOrder = "";

    private $strTPLDir = "";

    public function __construct()
    {}

    public function validatePostRequest()
    {
        return true;
    }

    public function handleInvalidPost()
    {}

    public function preparePost()
    {}

    public function validateGetRequest()
    {
        return true;
    }

    public function handleInvalidGet()
    {}

    public function executePost()
    {
        if ($_GET['action'] == "ajax" && isset($_GET['item']) && $_GET['item'] == "columns") {
            $_POST['name'] = str_replace(".", "", $_POST['name']);
            if ($_POST['value'] == "none") {
                $_SESSION['REQUEST']['CHKBX'][$_POST['name']] = $_POST['name'];
            } else {
                unset($_SESSION['REQUEST']['CHKBX'][$_POST['name']]);
            }
        } elseif ($_GET['action'] == "ajax" && isset($_GET['item']) && $_GET['item'] == "fontsize") {
            $_SESSION['REQUEST']['FONTSIZE'] = intval($_POST['value']);
        } elseif ($_GET['action'] == "ajax" && isset($_GET['item']) && $_GET['item'] == "menu") {
            $_SESSION['REQUEST']['MENU'] = $_POST['value'];
        }
    }

    public function prepareGet()
    {
        // Sortierung
        if (! isset($_GET['dir']) || $_GET['dir'] == "asc") {
            $this->strSQLDir = "ASC";
            $this->strTPLDir = "desc";
        } else {
            $this->strSQLDir = "DESC";
            $this->strTPLDir = "asc";
        }
        
        // SQL Anhang bei Sortierung
        if (! isset($_GET['order'])) {
            $this->strSQLOrder = "g_kirche";
        } elseif ($_GET['order'] == "gemeinde") {
            $this->strSQLOrder = "g_kirche";
        } elseif ($_GET['order'] == "plz") {
            $this->strSQLOrder = "ad_plz";
        } elseif ($_GET['order'] == "ort") {
            $this->strSQLOrder = "ad_ort";
        } elseif ($_GET['order'] == "konfession") {
            $this->strSQLOrder = "k_id";
        } elseif ($_GET['order'] == "bezirk") {
            $this->strSQLOrder = "b_id";
        }
    }

    public function executeGet()
    {
        $tplDruckansicht = new Template("gemeinde_druck.tpl");
        $tplGemeindeDS = new Template("gemeinde_druck_ds.tpl");
        
        // Wenn durch Switch Abfrage nichts ersetzt wurde, dann jetzt ersetzen
        $tplDruckansicht->replace("Dir", $this->strTPLDir);
        
        $js = "";
        if (false == isset($_SESSION['REQUEST']['CHKBX'])) {
            $_SESSION['REQUEST']['CHKBX']['jsKonfession'] = 'jsKonfession';
            $_SESSION['REQUEST']['CHKBX']['jsKM'] = 'jsKM';
        }
        foreach ($_SESSION['REQUEST']['CHKBX'] as $value) {
            $js .= ($js != "" ? ", " : "");
            $js .= "'" . $value . "'";
        }
        $js = "new Array(" . $js . ")";
        
        $iFontsize = 14;
        if (isset($_SESSION['REQUEST']['FONTSIZE']) == true) {
            $iFontsize = $_SESSION['REQUEST']['FONTSIZE'];
        }
        
        $cssDruckmenu = "block";
        if (isset($_SESSION['REQUEST']['MENU']) == true) {
            $cssDruckmenu = $_SESSION['REQUEST']['MENU'];
        }
        
        if ($iFontsize == 12) {
            $tplDruckansicht->replace("SmallChecked", Constant::$HTML_CHECKED_CHECKED);
        } else if ($iFontsize == 16) {
            $tplDruckansicht->replace("LargeChecked", Constant::$HTML_CHECKED_CHECKED);
        } else {
            $tplDruckansicht->replace("NormalChecked", Constant::$HTML_CHECKED_CHECKED);
        }
        $tplDruckansicht->replace("SmallChecked", "");
        $tplDruckansicht->replace("NormalChecked", "");
        $tplDruckansicht->replace("LargeChecked", "");
        
        $tplDruckansicht->replace("InvisibleColumsArray", $js);
        $tplDruckansicht->replace("Fontsize", $iFontsize);
        $tplDruckansicht->replace("DisplayDruckmenu", $cssDruckmenu);
        
        // Anzahl aller Gemeinden
        $tplDruckansicht->replace("GemeindeAnzahl", GemeindeUtilities::getAnzahlGemeinden());
        
        $strGemeinden = "";
        $c = GemeindeUtilities::getDruckAnsichtGemeinden(" ORDER BY " . $this->strSQLOrder . " " . $this->strSQLDir);
        $k = KonfessionUtilities::getKonfessionenKurzformAsArray();
        $konfNormal = KonfessionUtilities::getKonfessionenAsArray();
        
        $i = 1;
        foreach ($c as $oGemeindeAnsprechpartner) {
            $tplGemeindeDS->replace("Lfdnr", $i ++);
            $tplGemeindeDS->replace("Gemeinde", $oGemeindeAnsprechpartner->getKirche());
            $tplGemeindeDS->replace("PLZ", $oGemeindeAnsprechpartner->getGemeindePLZ());
            $tplGemeindeDS->replace("Ort", $oGemeindeAnsprechpartner->getGemeindeOrt());
            $tplGemeindeDS->replace("KonfessionKurz", $k[$oGemeindeAnsprechpartner->getKID()]);
            $tplGemeindeDS->replace("Konfession", $konfNormal[$oGemeindeAnsprechpartner->getKID()]);
            $tplGemeindeDS->replace("Bezirk", $oGemeindeAnsprechpartner->getGemeindeBezirk());
            $tplGemeindeDS->replace("KM", $oGemeindeAnsprechpartner->getDistanz());
            
            if ($oGemeindeAnsprechpartner->getFunktion() == "" || trim($oGemeindeAnsprechpartner->getFunktion()) == "")
                $tplGemeindeDS->replace("AFunktion", "&nbsp;");
            $tplGemeindeDS->replace("AFunktion", $oGemeindeAnsprechpartner->getFunktion());
            
            if ($oGemeindeAnsprechpartner->getNachname() == "" || trim($oGemeindeAnsprechpartner->getNachname()) == "")
                $tplGemeindeDS->replace("Nachname", "---");
            $tplGemeindeDS->replace("Nachname", $oGemeindeAnsprechpartner->getNachname());
            
            if ($oGemeindeAnsprechpartner->getVorname() == "" || trim($oGemeindeAnsprechpartner->getVorname()) == "")
                $tplGemeindeDS->replace("Vorname", "---");
            $tplGemeindeDS->replace("Vorname", $oGemeindeAnsprechpartner->getVorname());
            
            if ($oGemeindeAnsprechpartner->getTelefon() == "" || trim($oGemeindeAnsprechpartner->getTelefon()) == "")
                $tplGemeindeDS->replace("ATelefon", "&nbsp;");
            $tplGemeindeDS->replace("ATelefon", $oGemeindeAnsprechpartner->getTelefon());
            
            $strGemeinden .= $tplGemeindeDS->getOutput();
            $tplGemeindeDS->restoreTemplate();
        }
        
        // Ins Template einfügen
        $tplDruckansicht->replace("Gemeinden", $strGemeinden);
        
        return $tplDruckansicht;
    }

    public function __toString()
    {
        return "GemeindeLoeschenAction";
    }

    public function getHistoryEnry()
    {
        return "Orgel mit ID 1";
    }
}
?>