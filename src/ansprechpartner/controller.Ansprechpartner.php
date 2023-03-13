<?php

class AnsprechpartnerController
{

    public static function loescheAnsprechpartner()
    {
        RequestHandler::handle(new AnsprechpartnerLoeschen());
    }

    public static function zeigeAnsprechpartnerVerwaltung()
    {
        RequestHandler::handle(new AnsprechpartnerVerwaltung());
    }

    public static function speichereAnsprechpartner()
    {
        RequestHandler::handle(new AnsprechpartnerSpeichern());
    }

    public static function neueVerbindungZuGemeinde()
    {
        RequestHandler::handle(new AnsprechpartnerGemeindeVerbindung());
    }

    public static function loescheGemeindeAnsprechpartner()
    {
        if (! isset($_GET['gid'], $_GET['aid']))
            return;
        
        AnsprechpartnerUtilities::loescheGemeindeAnsprechpartner($_GET['aid'], $_GET['gid']);
        
        $oA = new Ansprechpartner($_GET['aid']);
        $oG = new Gemeinde($_GET['gid']);
        
        $redirect = "index.php?page=3&do=40&aid=" . $oA->getID();
        
        // Wenn eine Orgel uebergeben wurde, dann gehen wir davon aus, dass es aus den WartungsDetails her gemacht wurde
        if (isset($_GET['oid'])) {
            $redirect = "index.php?page=2&do=28&oid=" . $_GET['oid'];
        }
        $htmlStatus = new HTMLRedirect($oA->getAnrede() . " " . $oA->getNachname() . " wurde der Gemeinde " . $oG->getKirche() . " als Ansprechpartner entfernt!", $redirect);
        $htmlStatus->anzeigen();
    }

    /**
     *
     * @param unknown $iAnsprechpartnerID            
     * @param unknown $iGemeindeID            
     * @return boolean
     */
    public static function addAnsprechpartnerZuGemeinde($iAnsprechpartnerID, $iGemeindeID)
    {
        $retVal = false;
        $alreadyAdded = AnsprechpartnerUtilities::existiertVerbindung($iAnsprechpartnerID, $iGemeindeID);
        if ($alreadyAdded == false) {
            AnsprechpartnerUtilities::neuerGemeindeAnsprechpartner($iAnsprechpartnerID, $iGemeindeID);
            $retVal = true;
        }
        return $retVal;
    }

    public static function aendereAnsprechpartner()
    {
        if (! isset($_POST['submit']))
            return;
        
        if ($_POST['submit'] == "Speichern") {
            AnsprechpartnerController::speichereAnsprechpartner();
        } elseif ($_POST['submit'] == "Löschen") {
            AnsprechpartnerController::loescheAnsprechpartner();
        } else {}
    }
}
?>
