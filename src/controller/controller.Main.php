<?php
/**
 * Applikation Container
 */
$pageApplication = new PageApp();

/**
 * Gemeindecontroller
 */
$pcGemeinde = new PageController(1, "GemeindeController", 5);
$pcGemeinde->addChild(new SubPage(1, "zeigeGemeindeListe", 10));
$pcGemeinde->addChild(new SubPage(2, "zeigeGemeindeDetails", 5));
$pcGemeinde->addChild(new SubPage(3, "speichereGemeindeDetails", 10));
$pcGemeinde->addChild(new SubPage(4, "neueGemeindeAnlegen", 10));
$pcGemeinde->addChild(new SubPage(5, "loescheGemeinde", 10));
$pcGemeinde->addChild(new SubPage(6, "exportGemeindeListeExcel", 10));
$pcGemeinde->addChild(new SubPage(7, "ajaxGemeindeListeDruckansicht", 10));
$pcGemeinde->addChild(new SubPage(8, "zeigeGemeindeLandkarte", 10));
$pageApplication->addController($pcGemeinde);

/**
 * Orgelcontroller
 */
$pcOrgel = new PageController(2, "OrgelController", 5);
$pcOrgel->addChild(new SubPage(20, "zeigeOrgelListe", 5));
$pcOrgel->addChild(new SubPage(21, "zeigeOrgelDetails", 5));
$pcOrgel->addChild(new SubPage(22, "speicherOrgelDetails", 10));
$pcOrgel->addChild(new SubPage(23, "verwalteOrgelBild", 10));
$pcOrgel->addChild(new SubPage(24, "neueOrgelAnlegen", 10));
$pcOrgel->addChild(new SubPage(25, "neueOrgelGemeindeVerbindung", 10));
$pcOrgel->addChild(new SubPage(26, "loescheOrgelGemeindeVerbindung", 10));
$pcOrgel->addChild(new SubPage(27, "loescheOrgel", 10));
$pcOrgel->addChild(new SubPage(28, "zeigeWartungsListe", 5));
$pcOrgel->addChild(new SubPage(29, "zeigeOffeneWartungen", 5));
$pcOrgel->addChild(new SubPage(30, "deleteOrgelPicture", 5));
$pcOrgel->addChild(new SubPage(31, "zeigeWartungsprotokolle", 5));
$pageApplication->addController($pcOrgel);

/**
 * Ansprechpartner Controller
 */
$pcAnsprechpartner = new PageController(3, "AnsprechpartnerController", 5);
$pcAnsprechpartner->addChild(new SubPage(40, "zeigeAnsprechpartnerVerwaltung", 5));
$pcAnsprechpartner->addChild(new SubPage(41, "speichereAnsprechpartner", 10));
$pcAnsprechpartner->addChild(new SubPage(42, "loescheAnsprechpartner", 10));
$pcAnsprechpartner->addChild(new SubPage(43, "neueVerbindungZuGemeinde", 10));
$pcAnsprechpartner->addChild(new SubPage(44, "loescheGemeindeAnsprechpartner", 10));
$pcAnsprechpartner->addChild(new SubPage(45, "aendereAnsprechpartner", 10));
$pageApplication->addController($pcAnsprechpartner);

/**
 * Dispositions Controller
 */
$pcDisposition = new PageController(4, "DispositionController", 5);
$pcDisposition->addChild(new SubPage(60, "zeigeDisposition", 5));
$pcDisposition->addChild(new SubPage(61, "bearbeiteDisposition", 10));
$pcDisposition->addChild(new SubPage(62, "speichereDispositionsEintrag", 10));
$pcDisposition->addChild(new SubPage(63, "speichereReihenfolge", 10));
$pageApplication->addController($pcDisposition);

/**
 * Rechnungs Controller
 */
$pcRechnung = new PageController(5, "RechnungController", 10);
$pcRechnung->addChild(new SubPage(80, "neuePflegerechnung", 10));
$pcRechnung->addChild(new SubPage(81, "zeigeRechnungen", 10));
$pcRechnung->addChild(new SubPage(82, "druckePflegerechnung", 10));
$pcRechnung->addChild(new SubPage(83, "neueStundenrechnung", 10));
$pcRechnung->addChild(new SubPage(84, "neueAbschlagsrechnung", 10));
$pcRechnung->addChild(new SubPage(85, "neueEndrechnung", 10));
$pcRechnung->addChild(new SubPage(86, "druckeStundenrechnung", 10));
$pcRechnung->addChild(new SubPage(87, "druckeAbschlagsrechnung", 10));
$pcRechnung->addChild(new SubPage(88, "druckeEndrechnung", 10));
$pcRechnung->addChild(new SubPage(89, "zeigeRechnungsListe", 10));
$pcRechnung->addChild(new SubPage(90, "zeigeReadOnlyRechnung", 10));
$pcRechnung->addChild(new SubPage(91, "loescheRechnung", 10));
$pageApplication->addController($pcRechnung);

/**
 * Projekt Controller
 */
$pcProjekt = new PageController(6, "ProjektController", 10);
$pcProjekt->addChild(new SubPage(100, "zeigeProjekte", 10));
$pcProjekt->addChild(new SubPage(101, "zeigeZeiterfassungWrapper", 10));
$pcProjekt->addChild(new SubPage(102, "zeigeAufgabenVerwaltung", 10));
$pcProjekt->addChild(new SubPage(103, "mitarbeiterVerwalten", 10));
$pcProjekt->addChild(new SubPage(104, "zeigeProjektarchiv", 10));
$pcProjekt->addChild(new SubPage(105, "bearbeiteProjektdetails", 10));
$pcProjekt->addChild(new SubPage(106, "projektArchivierenAbfrage", 10));
$pcProjekt->addChild(new SubPage(107, "projektLoeschenAbfrage", 10));
$pcProjekt->addChild(new SubPage(108, "zeigeArbeitszeitVerwaltung", 10));
$pcProjekt->addChild(new SubPage(109, "verwalteArbeitszeiten", 10));
$pcProjekt->addChild(new SubPage(110, "zeigeProjektDetails", 10));
$pcProjekt->addChild(new SubPage(111, "zeigeStempeluhr", 10));
$pcProjekt->addChild(new SubPage(112, "zeigeMaterialRechnungen", 10));
$pcProjekt->addChild(new SubPage(113, "zeigeStundenFreigabe", 10));
$pcProjekt->addChild(new SubPage(114, "bearbeiteArbeitsTagUndWocheStatus", 10));
$pageApplication->addController($pcProjekt);

/**
 * Einstellungs Controller
 */
$pcEinstellung = new PageController(7, "EinstellungController", 10);
$pcEinstellung->addChild(new SubPage(120, "zeigeRechnungsEinstellungen", 10));
$pcEinstellung->addChild(new SubPage(121, "zeigeFirmenDaten", 10));
$pcEinstellung->addChild(new SubPage(122, "speichereFirmenDaten", 10));
$pcEinstellung->addChild(new SubPage(123, "zeigeOptions", 10));
$pcEinstellung->addChild(new SubPage(124, "saveOptions", 10));
$pcEinstellung->addChild(new SubPage(125, "showOptionMeta", 10));
$pcEinstellung->addChild(new SubPage(126, "showBenutzerVerlaufUebersicht", 10));
$pageApplication->addController($pcEinstellung);

/**
 * Benutzer Controller
 */
$pcBenutzer = new PageController(8, "BenutzerController", 0);
$pcBenutzer->addChild(new SubPage(140, "benutzerdatenAendern", 0));
$pcBenutzer->addChild(new SubPage(141, "benutzerLogout", 0));
$pcBenutzer->addChild(new SubPage(142, "zeigeZeiterfassung", 0));
$pcBenutzer->addChild(new SubPage(143, "datumsTest", 0));
$pcBenutzer->addChild(new SubPage(144, "benutzerZeitauswertung", 0));
$pcBenutzer->addChild(new SubPage(145, "benutzerZeitauswertung", 0));
$pcBenutzer->addChild(new SubPage(200, "doHilfeRufen", 0));
$pageApplication->addController($pcBenutzer);

// Hier wird die Funktion im Contentbereich der Seite geoeffnet

if (! $pageApplication->isPageRequested()) {
    global $webUser;
    if ($webUser->isMonteur()) {
        Log::debug("Zeige Startseite fuer Monteur");
        $pageApplication->showPage(1, 1);
    } else {
        Log::debug("Zeige Startseite fuer Mitarbeiter");
        $pageApplication->showPage(8, 142);
    }
} else {
    
    if (! $pageApplication->show()) {
        // Fehler
        Log::debug("Keine Berechtigung fuer Seite.");
        $htmlError = new HTMLStatus("Sie haben keine Berechtigung die angeforderte Seite zu sehen.", 1);
        $htmlError->anzeigen();
    }
}
?>