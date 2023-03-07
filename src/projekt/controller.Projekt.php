<?php

class ProjektController
{

    public static function zeigeProjekte()
    {
        RequestHandler::handle(new ProjektListeAction());
    }

    public static function zeigeAufgabenVerwaltung()
    {
        RequestHandler::handle(new ProjektAufgabenVerwaltung());
    }

    public static function mitarbeiterVerwalten()
    {
        RequestHandler::handle(new ProjektMitarbeiterVerwaltung());
    }

    public static function zeigeProjektarchiv()
    {
        RequestHandler::handle(new ProjektArchiv());
    }

    public static function bearbeiteProjektdetails()
    {
        RequestHandler::handle(new ProjektBearbeitenAction());
    }

    public static function zeigeArbeitszeitVerwaltung()
    {
        RequestHandler::handle(new ArbeitszeitVerwaltungAction());
    }

    public static function zeigeProjektDetails()
    {
        RequestHandler::handle(new ProjektDetailsAction());
    }

    public static function zeigeZeiterfassungWrapper()
    {
        BenutzerController::zeigeZeiterfassung();
    }

    public static function projektArchivierenAbfrage()
    {
        if (! isset($_GET['pid']) && ! isset($_POST['objektid']))
            return;
        
        if ($_POST && isset($_POST['objektid'])) {
            $p = new Projekt(intval($_POST['objektid']));
            $htmlStatus = new HTMLRedirect();
            
            $p->setArchviert(1);
            $p->setArchivdatum(0);
            $p->speichern(true);
            $htmlStatus->setLink("index.php?page=6&do=100");
            $htmlStatus->setNachricht("Projekt erfolgreich archiviert.");
            $htmlStatus->setSekunden(ConstantLoader::getDefaultRedirectSecondsTrue());
            $htmlStatus->anzeigen();
        } else {
            $p = new Projekt(intval($_GET['pid']));
            
            $tpl = new HTMLSicherheitsAbfrage();
            $tpl->setText("M&ouml;chten Sie das Projekt\"" . $p->getBezeichnung() . "\" wirklich archivieren?");
            $tpl->setButtonJa("Ja, Projekt archivieren!");
            $tpl->setButtonNein("Nein, zur&uuml;ck");
            $tpl->setButtonNeinLink("index.php?page=6&do=100");
            $tpl->setFormLink("index.php?page=6&do=106");
            $tpl->setObjektID($_GET['pid']);
            
            $tpl->anzeigen();
        }
    }

    public static function projektLoeschenAbfrage()
    {
        if (! isset($_GET['pid']) && ! isset($_POST['objektid']))
            return;
        
        if ($_POST && isset($_POST['objektid'])) {
            $p = new Projekt(intval($_POST['objektid']));
            $htmlStatus = new HTMLRedirect();
            
            $p->setGeloescht(1);
            $p->speichern(true);
            $htmlStatus->setLink("index.php?page=6&do=104");
            $htmlStatus->setNachricht("Projekt erfolgreich gel&ouml;scht.");
            $htmlStatus->setSekunden(ConstantLoader::getDefaultRedirectSecondsTrue());
            
            $htmlStatus->anzeigen();
        } else {
            $p = new Projekt(intval($_GET['pid']));
            
            $tpl = new HTMLSicherheitsAbfrage();
            $tpl->setText("M&ouml;chten Sie das Projekt\"" . $p->getBezeichnung() . "\" wirklich endg&uuml;ltig l&ouml;schen? ");
            $tpl->setButtonJa("Ja, Projekt l&ouml;schen!");
            $tpl->setButtonNein("Nein, zur&uuml;ck");
            $tpl->setButtonNeinLink("index.php?page=6&do=104");
            $tpl->setFormLink("index.php?page=6&do=107");
            $tpl->setObjektID($_GET['pid']);
            
            $tpl->anzeigen();
        }
    }

    public static function verwalteArbeitszeiten()
    {
        if (! $_POST)
            return;
        
        foreach ($_POST as $key => $val) {
            $ts = substr($key, strpos($key, "_") + 1);
            $ts = substr($ts, strpos($ts, "_"));
        }
        
        ArbeitswocheUtilities::bucheArbeitswoche($ts);
    }

    public static function ajaxGetMitarbeiterWochenStunden($wochenTag)
    {
        if (! is_numeric($wochenTag)) {
            throw new IllegalArgumentException("wochenTag must be a timestamp, is='" . $wochenTag . "'");
        }
        
        $tplMaDS = new BufferedTemplate("projekt_zeiten_ma_ds.tpl", "CSS", "td3", "td4");
        
        $tplLinksNormal = new Template("projekt_zeiten_ma_ds_links_normal.tpl");
        $tplLinksGebucht = new Template("projekt_zeiten_ma_ds_links_gebucht.tpl");
        
        $arbeitswoche = Date::berechneArbeitswocheTimestamp($wochenTag);
        $c = ArbeitswocheUtilities::getArbeitswochen(Date::getSQLDate($arbeitswoche['0']));
        
        // Benutzerdaten laden & in Array cachen
        $cBenutzer = BenutzerUtilities::getAlleBenutzer();
        $mitarbeiter = array();
        foreach ($cBenutzer as $benutzer) {
            if ($benutzer->getGeloescht() == 1) {
                $benutzer->setBenutzername($benutzer->getBenutzername() . " [gel&ouml;scht]");
            }
            $mitarbeiter[$benutzer->getID()] = $benutzer;
        }
        $rowId = 0;
        
        $tplLinksFuerDenDatensatz = $tplLinksNormal;
        foreach ($c as $kw) {
            if ($mitarbeiter[$kw->getBenutzerId()]->getGeloescht() != 1 || ($mitarbeiter[$kw->getBenutzerId()]->getGeloescht() == 1 && $kw->getWochenStundenIst() > 0)) {
                $tplMaDS->replace("Benutzername", htmlspecialchars(utf8_encode($mitarbeiter[$kw->getBenutzerId()]->getBenutzername())));
                $tplMaDS->replace("BenutzerID", $kw->getBenutzerId());
                $tplMaDS->replace("StdBisher", $kw->getWochenStundenIst());
                $tplMaDS->replace("StdGesamt", $kw->getWochenStundenSoll());
                $tplMaDS->replace("TSWoche", $wochenTag); // CSS Klasse setzen, damit jQuery Child Elemente finden und löschen kann
                $tplMaDS->replace("Datum", strtotime($kw->getWochenstart()));
                
                $tplMaDS->replace("RowID", $rowId);
                
                if ($kw->getEingabeGebucht()) {
                    $tplLinksFuerDenDatensatz = $tplLinksGebucht;
                    $tplMaDS->replace("Status", "Gebucht");
                    $tplMaDS->replace("StatusClass", "awStatusGebucht");
                } elseif ($kw->getEingabeOffen()) {
                    $tplLinksFuerDenDatensatz = $tplLinksNormal;
                    $tplMaDS->replace("Status", "Offen");
                    $tplMaDS->replace("StatusClass", "awStatusOffen");
                } elseif ($kw->getEingabeKomplett()) {
                    $tplLinksFuerDenDatensatz = $tplLinksNormal;
                    $tplMaDS->replace("Status", "Fertig");
                    $tplMaDS->replace("StatusClass", "awStatusFertig");
                } else {
                    $tplMaDS->replace("Status", "Status: " . $kw->getStatus());
                    $tplMaDS->replace("StatusClass", "awStatusFertig");
                }
                
                $tplLinksFuerDenDatensatz->replace("BenutzerID", $kw->getBenutzerID());
                $tplLinksFuerDenDatensatz->replace("Datum", strtotime($kw->getWochenstart()));
                $tplMaDS->replace("Links", $tplLinksFuerDenDatensatz->getOutput());
                $tplLinksFuerDenDatensatz->reset();
                
                $tplMaDS->next();
            }
        }
        return $tplMaDS;
    }

    public static function ajaxSortiereProjectliste()
    {
        $orderPIDs = $_GET['order'];
        $orderPIDs = explode(",", $orderPIDs);
        $currentSortNumber = 0;
        foreach ($orderPIDs as $currentPID) {
            if (is_numeric($currentPID)) {
                ProjektUtilities::updateProjektOrder($currentPID, $currentSortNumber ++);
            }
        }
        return array(
            "result" => "ok"
        );
    }

    public static function ajaxSortiereMitarbeiter()
    {
        $orderPIDs = $_GET['order'];
        $orderPIDs = explode(",", $orderPIDs);
        $currentSortNumber = 0;
        foreach ($orderPIDs as $currentPID) {
            if (is_numeric($currentPID)) {
                ProjektUtilities::updateMitarbeiterOrder($currentPID, $currentSortNumber ++);
            }
        }
        return array(
            "result" => "ok"
        );
    }

    public static function ajaxSortiereProjektAufgaben()
    {
        $projektId = $_GET['pid'];
        $orderAufgabenIds = $_GET['order'];
        $orderAufgabenIds = explode(",", $orderAufgabenIds);
        $currentSortNumber = 0;
        foreach ($orderAufgabenIds as $currentAufgabeId) {
            if (is_numeric($currentAufgabeId)) {
                ProjektUtilities::updateProjektAufgabeOrder($projektId, $currentAufgabeId, $currentSortNumber ++);
            }
        }
        
        return array(
            "result" => "ok"
        );
    }

    public static function druckeStundenzettel()
    {
        ConstantLoader::performAutoload();
        RequestHandler::handle(new MitarbeiterStundenzettelAction());
    }

    public static function zeigeStempeluhr()
    {
        RequestHandler::handle(new ProjektStempeluhrAction());
    }

    public static function zeigeMaterialRechnungen()
    {
        RequestHandler::handle(new ProjektMaterialRechnungenAction());
    }

    public static function zeigeStundenFreigabe()
    {
        RequestHandler::handle(new ProjektStundenFreigabeAction());
    }

    public static function bearbeiteArbeitsTagUndWocheStatus()
    {
        RequestHandler::handle(new ArbeitsTagUndWocheStatusWechselAction());
    }

    public static function zeigeUrlaubsVerwaltung()
    {
        RequestHandler::handle(new UrlaubsVerwaltungAction());
    }

    public static function verwalteJahresurlaub()
    {
        RequestHandler::handle(new JahresurlaubAnlegenAction());
    }
}
?>