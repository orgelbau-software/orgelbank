<?php

class ProjektDetailsAction implements GetRequestHandler, PostRequestHandler
{

    /**
     *
     * {@inheritdoc}
     *
     * @see GetRequestHandler::executeGet()
     */
    public function executeGet()
    {
        $tpl = new Template("projekt_details.tpl");
        $p = new Projekt(intval($_GET['pid']));
        $g = new Gemeinde($p->getGemeindeID());
        
        Log::debug("lade alle Projektaufgaben");
        $ha = ProjektAufgabeUtilities::getAlleProjektAufgaben($p->getID());
        
        $tplHADS = new BufferedTemplate("projekt_details_aufgabe_ds.tpl", "CSS", "td1", "td2");
        $tplStatus = null;
        
        // Rechnungs Eingabe Formular
        $pRechnung = new ProjektRechnung(); // dummy
        $pNKRechnung = new NebenkostenRechnung(); // dummy
        
        if (isset($_GET['prid'], $_GET['action'])) {
            $pRechnung = new ProjektRechnung($_GET['prid']);
            if ($_GET['action'] == "delete") {
                $tpl->replace("SubmitButtonPR", "Stornieren");
            }
        }
        
        if (isset($_GET['nkid'], $_GET['action'])) {
            $pNKRechnung = new NebenkostenRechnung($_GET['nkid']);
            if ($_GET['action'] == "delete") {
                $tpl->replace("SubmitButtonNK", "Stornieren");
            }
        }
        
        if ($_POST && isset($_POST['pr_id'])) {
            if ($_POST['submit'] == "Buchen") {
                if ($_POST['pr_id'] > 0) {
                    $pRechnung = new ProjektRechnung($_POST['pr_id']);
                } else {
                    $pRechnung = new ProjektRechnung();
                }
                $pRechnung->setProjektID($p->getID());
                $pRechnung->setAufgabeID($_POST['kostenstelle']);
                $pRechnung->setKommentar($_POST['kommentar']);
                $pRechnung->setBetrag($_POST['betrag']);
                $pRechnung->setDatum($_POST['datum']);
                $pRechnung->setLieferant($_POST['lieferant']);
                $pRechnung->setNummer($_POST['nummer']);
                
                if($_POST['betrag'] > 0) {
                    $pRechnung->speichern();
                } else {
                    $tplStatus = new HTMLStatus("Der Rechnungsbetrag darf nicht 0 EUR sein.", HTMLStatus::$STATUS_ERROR);
                }
            } else if ($_POST['submit'] == "Stornieren") {
                $pRechnung = new ProjektRechnung($_POST['pr_id']);
                $pRechnung->loeschen();
            }
            $pRechnung = new ProjektRechnung(); // dummy
        } else if ($_POST && isset($_POST['nk_id'])) {
            if ($_POST['submit'] == "Buchen") {
                if ($_POST['nk_id'] > 0) {
                    $pNKRechnung = new NebenkostenRechnung($_POST['nk_id']);
                } else {
                    $pNKRechnung = new NebenkostenRechnung();
                }
                $pNKRechnung->setProjektID($p->getID());
                $pNKRechnung->setKommentar($_POST['nk_kommentar']);
                $pNKRechnung->setBetrag($_POST['nk_betrag']);
                $pNKRechnung->setDatum($_POST['nk_datum']);
                $pNKRechnung->setLieferant($_POST['nk_lieferant']);
                $pNKRechnung->setLeistung($_POST['nk_leistung']);
                $pNKRechnung->setNummer($_POST['nk_nummer']);
                $pNKRechnung->setBenutzerID($_POST['nk_mitarbeiter']);
                if($_POST['nk_betrag'] > 0) {
                    $pNKRechnung->speichern();
                } else  {
                    $tplStatus = new HTMLStatus("Der Rechnungsbetrag darf nicht 0 EUR sein.", HTMLStatus::$STATUS_ERROR);
                }
            } else if ($_POST['submit'] == "Stornieren") {
                $pNKRechnung = new NebenkostenRechnung($_POST['nk_id']);
                $pNKRechnung->loeschen();
            }
            $pNKRechnung = new NebenkostenRechnung(); // dummy
        }
        
        // Projekt Rechnung
        $tpl->replace("Datum", ($pRechnung->getID() < 0 ? date("Y-m-d") : $pRechnung->getDatum()));
        $tpl->replace("Kommentar", $pRechnung->getKommentar());
        $tpl->replace("Betrag", WaehrungUtil::formatDoubleToWaehrung($pRechnung->getBetrag()));
        $tpl->replace("PRID", $pRechnung->getID());
        $tpl->replace("Lieferant", $pRechnung->getLieferant());
        $tpl->replace("Nummer", $pRechnung->getNummer());
        $select = new HTMLSelect($ha, "getBezeichnung", $pRechnung->getAufgabeID());
        
        // Nebenkosten Rechnung
        $tpl->replace("NKDatum", ($pNKRechnung->getID() < 0 ? date("Y-m-d") : $pNKRechnung->getDatum()));
        $tpl->replace("NKKommentar", $pNKRechnung->getKommentar());
        $tpl->replace("NKBetrag", WaehrungUtil::formatDoubleToWaehrung($pNKRechnung->getBetrag()));
        $tpl->replace("NKID", $pNKRechnung->getID());
        $tpl->replace("NKLieferant", $pNKRechnung->getLieferant());
        $tpl->replace("NKLeistung", $pNKRechnung->getLeistung());
        $tpl->replace("NKNummer", $pNKRechnung->getNummer());
        
        $nebenkostenArten = ConstantLoader::getAuswahlNebenkosten();
        $tplNebenkostenDL = new HTMLDatalistForArray($nebenkostenArten);
        $tpl->replace("NebenkostenDatalist", $tplNebenkostenDL->getOutput());
        
        $cBenutzer = BenutzerUtilities::getBenutzer();
        $tplMitarbeiter = new HTMLSelect($cBenutzer, "getBenutzername", $pNKRechnung->getBenutzerID());
        $tpl->replace("SelectMitarbeiter", $tplMitarbeiter->getOutput());
        
        // Projektdetails
        $tpl->replace("Start", $p->getStart(true));
        $tpl->replace("Ende", $p->getEnde(true));
        $tpl->replace("Bezeichnung", $p->getBezeichnung());
        $tpl->replace("Beschreibung", $p->getBeschreibung());
        $tpl->replace("Gemeinde", $g->getKirche());
        $tpl->replace("Angebotspreis", WaehrungUtil::formatDoubleToWaehrung($p->getAngebotsPreis()));
        $tpl->replace("GID", $g->getID());
        
        // Request Parameter Handling
        $handler = new ProjektRequestHandler();
        $handledRequest = $handler->prepareProjektDetails();
        
        // Projekt Rechnungen ausgeben
        $tpl->replace("TPLPRDIR", $handledRequest['PR']['TPLDIR']);
        $tpl->replace("TPLNKDIR", $handledRequest['NK']['TPLDIR']);
        
        $rechnungen = ProjektRechnungUtilities::getProjektRechnungen($p->getID(), $handledRequest['PR']['SQLADD']);
        $tplRechnungen = new BufferedTemplate("projekt_details_projrechnung_ds.tpl", "CSS", "td1", "td2");
        $alleAufgabenArray = AufgabeUtilities::getAlleAufgabenAsArray();
        foreach ($rechnungen as $projRechnung) {
            $tplRechnungen->replace("PRID", $projRechnung->getID());
            $tplRechnungen->replace("ProjektID", $p->getID());
            $tplRechnungen->replace("Nummer", $projRechnung->getNummer());
            $tplRechnungen->replace("Lieferant", $projRechnung->getLieferant());
            $tplRechnungen->replace("Datum", $projRechnung->getDatum(true));
            $tplRechnungen->replace("Betrag", WaehrungUtil::formatDoubleToWaehrung($projRechnung->getBetrag()));
            if (isset($alleAufgabenArray[$projRechnung->getAufgabeID()])) {
                $tplRechnungen->replace("Kostenstelle", $alleAufgabenArray[$projRechnung->getAufgabeID()]['bezeichnung']);
            } else {
                $tplRechnungen->replace("Kostenstelle", "Fehler");
            }
            $tplRechnungen->next();
        }
        $tpl->replace("ProjektRechnungen", $tplRechnungen->getOutput());
        
        // Nebenkosten Rechnungen ausgeben
        $rechnungen = NebenkostenRechnungUtilities::getNebenkostenRechnungen($p->getID(), $handledRequest['NK']['SQLADD']);
        $tplNKRechnungen = new BufferedTemplate("projekt_details_nkrechnung_ds.tpl", "CSS", "td1", "td2");
        foreach ($rechnungen as $nkRechnung) {
            $tplNKRechnungen->replace("NKID", $nkRechnung->getID());
            $tplNKRechnungen->replace("ProjektID", $p->getID());
            $tplNKRechnungen->replace("Nummer", $nkRechnung->getNummer());
            $tplNKRechnungen->replace("Lieferant", $nkRechnung->getLieferant());
            $tplNKRechnungen->replace("Leistung", $nkRechnung->getLeistung());
            $tplNKRechnungen->replace("Datum", $nkRechnung->getDatum(true));
            $tplNKRechnungen->replace("Betrag", WaehrungUtil::formatDoubleToWaehrung($nkRechnung->getBetrag()));
            $tplNKRechnungen->next();
        }
        $tpl->replace("NebenkostenRechnungen", $tplNKRechnungen->getOutput());
        
        // Berechnung durchführen
        $aufgabenKosten = ProjektRechnungUtilities::getProjektRechnungssummenByAufgabe($p->getID());
        $nebenkostenSumme = NebenkostenRechnungUtilities::getNebenkostenRechnungenSumme($p->getID());
        $lohnKosten = ZeiterfassungUtilities::getProjektLohnkostenByHauptaufgabe($p->getID());
        $arRK = ReisekostenUtilities::getProjektReisekosten($p->getID());
        $rechner = new ProjektKostenRechner();
        $ergebnis = $rechner->calculate($p->getAngebotsPreis(), $ha, $aufgabenKosten, $lohnKosten, $arRK, $nebenkostenSumme);
        $ha = $ergebnis['aufgaben'];
        
        // Aufgaben / Kosten Übersicht
        foreach ($ha as $haufgabe) {
            $tmpBezeichnung = $haufgabe->getBezeichnung();
            if ($haufgabe->getSelected() == "false")
                $tmpBezeichnung .= "*";
            $tplHADS->replace("Aufgabe", $tmpBezeichnung);
            
            $tmpRech = $tmpLohnKosten = 0;
            if (isset($aufgabenKosten[$haufgabe->getID()])) {
                $tmpRech = $aufgabenKosten[$haufgabe->getID()];
            }
            if (isset($lohnKosten[$haufgabe->getID()])) {
                $tmpLohnKosten = $lohnKosten[$haufgabe->getID()];
            }
            
            $tplHADS->replace("ProjektID", $p->getID());
            $tplHADS->replace("AufgabeID", $haufgabe->getID());
            if (isset($alleAufgabenArray[$haufgabe->getID()])) {
                $tplHADS->replace("ParentID", $alleAufgabenArray[$haufgabe->getID()]['parentid']);
                if ($haufgabe->getID() != - 1) {} else {
                    print_r($haufgabe);
                }
            }
            $tplHADS->replace("Lohnkosten", WaehrungUtil::formatDoubleToWaehrung($tmpLohnKosten));
            $tplHADS->replace("Materialkosten", WaehrungUtil::formatDoubleToWaehrung($tmpRech));
            $tplHADS->replace("Gesamtkosten", WaehrungUtil::formatDoubleToWaehrung(($tmpLohnKosten + $tmpRech)));
            $tplHADS->replace("Plankosten", WaehrungUtil::formatDoubleToWaehrung(($haufgabe->getPlankosten())));
            $tplHADS->next();
        }
        
        // Reisekosten
        $tplRK = new Template("projekt_details_aufgabe_reisekosten.tpl");
        $tplRK->replace("Spesen", WaehrungUtil::formatDoubleToWaehrung($arRK['spesen']));
        $tplRK->replace("Hotel", WaehrungUtil::formatDoubleToWaehrung($arRK['hotel']));
        $tplRK->replace("KMKosten", WaehrungUtil::formatDoubleToWaehrung($arRK['kmkosten']));
        $tplRK->replace("Gesamt", WaehrungUtil::formatDoubleToWaehrung($arRK['gesamt']));
        $tpl->replace("Reisekosten", $tplRK->getOutput());
        $gesRK = $arRK['gesamt'];
        
        // Exclude?
        $exclude = ZeiterfassungUtilities::getProjektLohnkosten($p->getID(), false) - $ergebnis['lohnkosten'];
        $tpl->replace("NichtBeachtet", WaehrungUtil::formatDoubleToWaehrung($exclude));
        
        // Kostenzusammenfassung
        $tpl->replace("GesamtReisekosten", WaehrungUtil::formatDoubleToWaehrung($arRK['gesamt']));
        $tpl->replace("GesamtMaterialkosten", WaehrungUtil::formatDoubleToWaehrung($ergebnis['rechnungen']));
        $tpl->replace("GesamtNebenkosten", WaehrungUtil::formatDoubleToWaehrung($ergebnis['nebenkosten']));
        $tpl->replace("GesamtLohnkosten", WaehrungUtil::formatDoubleToWaehrung($ergebnis['lohnkosten']));
        $tpl->replace("Gesamtkosten", WaehrungUtil::formatDoubleToWaehrung($ergebnis['gesamtkosten']));
        $tpl->replace("GesamtPlankosten", WaehrungUtil::formatDoubleToWaehrung($ergebnis['plankosten']));
        $tpl->replace("Rest", WaehrungUtil::formatDoubleToWaehrung($ergebnis['differenz_plan_gesamt']));
        
        if ($ergebnis['differenz_plan_gesamt'] > 0) {
            $tpl->replace("ZwischensummePlankosten", "summePositiv");
            $tpl->replace("GewinnVerlustClass", "summePositiv");
        } else {
            $tpl->replace("ZwischensummePlankosten", "summeUeberPlan");
            if ($p->getAngebotsPreis() - $ergebnis['gesamtkosten'] < 0) {
                $tpl->replace("GewinnVerlustClass", "summeNegativ");
            } else {
                $tpl->replace("GewinnVerlustClass", "summePositiv");
            }
        }
        $tpl->replace("GewinnVerlust", WaehrungUtil::formatDoubleToWaehrung($ergebnis['gewinn_oder_verlust']));
        
        // Allgemein
        $tpl->replace("Hauptaufgaben", $select->getOutput());
        $tpl->replace("Projektaufgaben", $tplHADS->getOutput());
        $tpl->replace("ProjektID", $p->getID());
        $tpl->replace("SubmitButtonPR", "Buchen");
        $tpl->replace("SubmitButtonNK", "Buchen");
        
        if($tplStatus != null) {
            $tpl->replace("StatusMeldung", $tplStatus->getOutput());
        }
        
        return $tpl;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see GetRequestHandler::validateGetRequest()
     */
    public function validateGetRequest()
    {
        if (! isset($_GET['pid'])) {
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
        return new HTMLStatus("Allgemeiner Fehler", HTMLStatus::$STATUS_ERROR);
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
     * @see PostRequestHandler::preparePost()
     */
    public function preparePost()
    {}

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
}