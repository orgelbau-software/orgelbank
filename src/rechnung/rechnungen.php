<?php

// Config einbinden
include_once ("../../conf/config.inc.php");

// Datenbankverbindung herstellen
$db = DB::getInstance();
$db->connect();

session_start();

$user = new WebBenutzer();
if ($user->validateSession() == false) {
    die("keine gueltige session");
}

if (isset($_GET['action'])) {
    if (isset($_GET['gid'], $_GET['action'], $_GET['target']) && $_GET['action'] == "ajax") {
        
        // Der "gemeindeanschriftxml"-Teil wurde durch den weiter unten stehenden JSON Teil ersetzt und wird nicht mehr verwendet.
        if (intval($_GET['gid']) == 0) {
            // Kann vorkommen, wenn mal keine Auswahl getroffen wird. Einfach nichts machen
        } elseif ($_GET['target'] == "gemeindeanschriftxml") {
            $oDB = DB::getInstance();
            $oDB->connect();
            $g = new Gemeinde($_GET['gid']);
            $t = new Output("./templates/rechnung_ajax.tpl");
            $r = PflegeRechnungUtilities::getLetztePflegeRechnung($_GET['gid']);
            
            $oKonfession = new Konfession($g->getKID());
            
            $titel = $oKonfession->getGenitiv() . " Kirchengemeinde";
            
            $t->replace("<!--Anschrift-->", Output::formatString($g->getRAnschrift()));
            $t->replace("<!--Kirche-->", Output::formatString($g->getKirche()));
            $t->replace("<!--Strasse-->", Output::formatString($g->getRechnungAdresse()
                ->getStrasse()));
            $t->replace("<!--Hausnummer-->", Output::formatString($g->getRechnungAdresse()
                ->getHausnummer()));
            $t->replace("<!--PLZ-->", Output::formatString($g->getRechnungAdresse()
                ->getPLZ()));
            $t->replace("<!--Ort-->", Output::formatString($g->getRechnungAdresse()
                ->getOrt()));
            $t->replace("<!--Land-->", Output::formatString($g->getRechnungAdresse()
                ->getLand()));
            $t->replace("<!--Text-->", $titel);
            
            if ($r != null) {
                if ($r->getFahrtkosten() == "") {
                    $t->replace("<!--Fahrtkosten-->", 0);
                    $t->replace("<!--Pflegekosten-->", 0);
                    $t->replace("<!--Rechnungsbetrag-->", $r->getNettoBetrag(true));
                }
                
                // Pflegekosten == "", wenn Rechnung vor 6.11.2008 erstellt
                if ($r->getPflegekosten() == "") {
                    $t->replace("<!--Pflegekosten-->", 0);
                    $t->replace("<!--Fahrtkosten-->", $r->getFahrtkosten(true));
                    $t->replace("<!--Rechnungsbetrag-->", $r->getNettoBetrag(true));
                }
                $t->replace("<!--Pflegekosten-->", $r->getPflegekosten(true));
                $t->replace("<!--Fahrtkosten-->", $r->getFahrtkosten(true));
                $t->replace("<!--Rechnungsbetrag-->", $r->getNettoBetrag(true));
                $t->replace("<!--Datum-->", $r->getDatum(true));
            }
            $t->replace("<!--Pflegekosten-->", "unbekannt");
            $t->replace("<!--Fahrtkosten-->", "unbekannt");
            $t->replace("<!--Rechnungsbetrag-->", "unbekannt");
            $t->replace("<!--Datum-->", "unbekannt");
            
            header('Content-Type: text/xml;  charset=utf-8');
            echo $t->getOutput();
        } elseif ($_GET['target'] == "gemeindeanschrift") {
            
            $oDB = DB::getInstance();
            $oDB->connect();
            $g = new Gemeinde($_GET['gid']);
            $r = PflegeRechnungUtilities::getLetztePflegeRechnung($_GET['gid']);
            $oKonfession = new Konfession($g->getKID());
            
            $titel = $oKonfession->getGenitiv() . " Kirchengemeinde";
            
            $retVal = array();
            $retVal['anschrift'] = $g->getRAnschrift();
            $retVal['kirche'] = $g->getRGemeinde();
            $retVal['strasse'] = $g->getRechnungAdresse()->getStrasse();
            $retVal['hausnummer'] = $g->getRechnungAdresse()->getHausnummer();
            $retVal['plz'] = $g->getRechnungAdresse()->getPLZ();
            $retVal['ort'] = $g->getRechnungAdresse()->getOrt();
            $retVal['land'] = $g->getRechnungAdresse()->getLand();
            $retVal['text'] = $titel;
            $retVal['pflegekosten'] = "0.0";
            $retVal['fahrtkosten'] = "0.0";
            $retVal['nettobetrag'] = "0.0";
            $retVal['bruttobetrag'] = "0.0";
            $retVal['mwst'] = "0.0";
            $retVal['datum'] = "unbekannt";
            
            if ($r != null) {
                if ($r->getFahrtkosten() == "") {} elseif ($r->getPflegekosten() == "") {
                    $retVal['fahrtkosten'] = $r->getFahrtkosten();
                } else {
                    $retVal['pflegekosten'] = $r->getPflegekosten();
                    $retVal['fahrtkosten'] = $r->getFahrtkosten();
                }
                $retVal['nettobetrag'] = $r->getNettoBetrag();
                $retVal['bruttobetrag'] = $r->getBruttoBetrag();
                $retVal['mwst'] = $r->getMwSt();
                $retVal['datum'] = $r->getDatum(true);
            }
            
            // Kosten Haupt und Teilstimmung aber für welche Orgel? Wir nehmen mal die erste
            $orgeln = OrgelUtilities::getGemeindeOrgeln($g->getID());
            if($orgeln->getSize() == 1) {
                $ersteOrgel = $orgeln->getValueOf(0);
                $retVal['kosten_hauptstimmung'] = $ersteOrgel->getKostenHauptstimmung();
                $retVal['kosten_nebenstimmung'] = $ersteOrgel->getKostenTeilstimmung();
            } else {
                $retVal['kosten_hauptstimmung'] = "0.0";
                $retVal['kosten_nebenstimmung'] = "0.0";
            }
            
            
            
            
            
            foreach ($retVal as $key => $val) {
                $retVal[$key] = ($val == null ? "" : $val);
            }
            
            header("Content-Type: text/json;  charset=utf-8 ");
            echo json_encode($retVal);
        } elseif ($_GET['target'] == "abschlagsrechnungen" && $_GET['tpl']) {
            
            $oDB = DB::getInstance();
            $oDB->connect();
            $g = new Gemeinde($_GET['gid']);
            
            if ($_GET['tpl'] == 1) {
                $t = new BufferedTemplate("rechnung_abschlag_ajax_ds.tpl");
                $t->addToBuffer(new Template("rechnung_abschlag_ajax_kopf.tpl"));
            } else {
                $t = new BufferedTemplate("rechnung_end_ajax_ds.tpl");
                $t->addToBuffer(new Template("rechnung_end_ajax_kopf.tpl"));
            }
            $retVal['gesamtnetto'] = 0;
            $retVal['naechster_abschlag'] = 0;
            
            $r = AbschlagrechnungUtilities::getAbschlagsRechnungenOhneEndRechnung($g->getID(), " ORDER BY ra_datum ASC");
            if ($r->getSize() >= 0) {
                foreach ($r as $oAbschlagsRechnung) {
                    $t->replace("ABezeichnung", $oAbschlagsRechnung->getANr() . ". Abschlag");
                    $t->replace("ANr", $oAbschlagsRechnung->getID());
                    $t->replace("NettoGesamt", $oAbschlagsRechnung->getGesamtNetto());
                    $t->replace("NettoGesamtFormatiert", $oAbschlagsRechnung->getGesamtNetto(true));
                    $t->replace("BruttoGesamt", $oAbschlagsRechnung->getGesamtBrutto());
                    $t->replace("BruttoGesamtFormatiert", $oAbschlagsRechnung->getGesamtBrutto(true));
                    $t->replace("NettoBetrag", $oAbschlagsRechnung->getNettoBetrag());
                    $t->replace("NettoBetragFormatiert", $oAbschlagsRechnung->getNettoBetrag(true));
                    $t->replace("BruttoBetrag", $oAbschlagsRechnung->getBruttoBetrag());
                    $t->replace("BruttoBetragFormatiert", $oAbschlagsRechnung->getBruttoBetrag(true));
                    $t->replace("ADatum", $oAbschlagsRechnung->getDatum(true));
                    $t->replace("disabled", "");
                    $t->replace("checked", "");
                    $t->next();
                    
                    $retVal['gesamtnetto'] = $oAbschlagsRechnung->getGesamtNetto();
                    if ($oAbschlagsRechnung->getANr() > $retVal['naechster_abschlag']) {
                        $retVal['naechster_abschlag'] = $oAbschlagsRechnung->getANr();
                    }
                }
            } else {
                $t->addToBuffer(new Template("rechnung_abschlag_ajax_keine.tpl"));
            }
            
            $retVal['content'] = $t->getOutput();
            $retVal['naechster_abschlag'] += 1;
            echo json_encode($retVal);
        }
    } elseif ($_GET['action'] == "view" && isset($_GET['gid'])) {
        RechnungController::zeigeRechnungen();
    } elseif ($_GET['action'] == "drucken") {} elseif ($_GET['action'] == "eingangsrechnung") {
        RechnungController::verbucheEingangsRechnung();
    } elseif ($_GET['action'] == "rechnungspositionen") {
        RechnungController::sucheRechnungsPosition();
    } else {
        throw new IllegalArgumentException("Ungueltiges oder fehlendes Parameter, GET=" . json_encode($_GET) . ", POST=" . json_encode($_POST));
    }
} else {
    throw new IllegalArgumentException("Keine Aktion definiert");
}
?>