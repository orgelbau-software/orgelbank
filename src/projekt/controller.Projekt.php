<?php

class ProjektController
{

    public static function zeigeProjekte()
    {
        // $t = new Template("jquerytest.tpl");
        // $t->anzeigen();
        $tpl = new Template("projekt_anzeigen.tpl");
        $tplDS = new BufferedTemplate("projekt_anzeigen_ds.tpl", "CSS", "td1", "td2");
        $htmlStatus = null;
        
        // GET Handling Start
        $handler = new ProjektListeRequestHandler();
        $handledRequest = $handler->prepareRequest();
        
        // $strWhere .= " ORDER BY r_datum DESC";
        $strWhere = $handledRequest['RESULT'];
        
        $tpl->replace("Dir", $handledRequest->getValueOf("TPLDIR"));
        // GET Handling End
        
        $c = ProjektUtilities::getAnzeigeProjekte($strWhere);
        
        $gLohnkosten = 0;
        $gGesamtkosten = 0;
        $gAngebotspreis = 0;
        $gGewinn = 0;
        
        foreach ($c as $projekt) {
            $tplDS->replace("ProjektID", $projekt->getID());
            $g = new Gemeinde($projekt->getGemeindeID());
            $tplDS->replace("GemeindeBezeichnung", $g->getKirche());
            $tplDS->replace("Bezeichnung", $projekt->getBezeichnung());
            $tplDS->replace("Starttermin", $projekt->getStart(true, true));
            $tplDS->replace("Endtermin", $projekt->getEnde(true, true));
            
            // Berechnungszahlen
            $ha = ProjektAufgabeUtilities::getAlleProjektAufgaben($projekt->getID());
            $aufgabenKosten = ProjektRechnungUtilities::getProjektRechnungssummenByAufgabe($projekt->getID());
            $lohnKosten = ZeiterfassungUtilities::getProjektLohnkostenByHauptaufgabe($projekt->getID());
            $arRK = ReisekostenUtilities::getProjektReisekosten($projekt->getID());
            $rechner = new ProjektKostenRechner();
            $ergebnis = $rechner->calculate($projekt->getAngebotsPreis(), $ha, $aufgabenKosten, $lohnKosten, $arRK);
            
            $tplDS->replace("Lohnkosten", WaehrungUtil::formatDoubleToWaehrung($ergebnis['lohnkosten']));
            $tplDS->replace("Materialkosten", WaehrungUtil::formatDoubleToWaehrung($ergebnis['rechnungen']));
            $tplDS->replace("Reisekosten", WaehrungUtil::formatDoubleToWaehrung($ergebnis['reisekosten']));
            $tplDS->replace("Gesamtkosten", WaehrungUtil::formatDoubleToWaehrung($ergebnis['gesamtkosten']));
            $tplDS->replace("Angebotspreis", WaehrungUtil::formatDoubleToWaehrung($projekt->getAngebotsPreis()));
            $tplDS->replace("GewinnOderVerlust", WaehrungUtil::formatDoubleToWaehrung($ergebnis['gewinn_oder_verlust']));
            $tplDS->next();
            
            $gLohnkosten += $ergebnis['lohnkosten'];
            $gGesamtkosten += $ergebnis['gesamtkosten'];
            $gAngebotspreis += $projekt->getAngebotsPreis();
            $gGewinn += $ergebnis['gewinn_oder_verlust'];
        }
        
        if ($htmlStatus != null)
            $tpl->replace("Statusmeldung", $htmlStatus->getOutput());
        $tpl->replace("Statusmeldung", "");
        
        $tpl->replace("GLohnkosten", WaehrungUtil::formatDoubleToWaehrung($gLohnkosten));
        $tpl->replace("GGesamtkosten", WaehrungUtil::formatDoubleToWaehrung($gGesamtkosten));
        $tpl->replace("GAngebotspreis", WaehrungUtil::formatDoubleToWaehrung($gAngebotspreis));
        $tpl->replace("GGewinn", WaehrungUtil::formatDoubleToWaehrung($gGewinn));
        
        $tpl->replace("Projektliste", $tplDS->getOutput());
        $tpl->anzeigen();
    }

    public static function zeigeAufgabenVerwaltung()
    {
        $tpl = new Template("projekt_aufgabe_verwalten.tpl");
        $tplAufgDS = new BufferedTemplate("projekt_aufgabe_verwalten_ds.tpl");
        $tplAufgUADS = new BufferedTemplate("projekt_aufgabe_verwalten_uaufg_ds.tpl", "CSS", "td1", "td2");
        $tplUADS = new BufferedTemplate("projekt_aufgabe_verwalten_uaufgabe_ds.tpl", "CSS", "td1", "td2");
        $ProjektAufgabe = new Aufgabe();
        $htmlStatus = null;
        
        if ($_POST) {
            Utilities::escapePost();
            $htmlStatus = new HTMLStatus();
            $paID = 0;
            
            if ($_POST['paid'] != - 1)
                $paID = $_POST['paid'];
            
            $ProjektAufgabe = new Aufgabe($paID);
            
            if ($_POST['form'] == "hauptaufgabe") {
                if ($_POST['submit'] == "Ja, Aufgabe löschen" && isset($_POST['paid'])) {
                    $ProjektAufgabe->loeschen();
                    $ProjektAufgabe->speichern(false);
                    AufgabeUtilities::loescheUnteraufgaben($_POST['paid']);
                    $htmlStatus->setText("Projektaufgabe gel&ouml;scht");
                    $htmlStatus->setStatusclass(2);
                } else {
                    if (trim($_POST['bezeichnung']) != "") {
                        $ProjektAufgabe->setBeschreibung($_POST['beschreibung']);
                        $ProjektAufgabe->setBezeichnung($_POST['bezeichnung']);
                        $ProjektAufgabe->setChangeAt(0);
                        
                        if ($_POST['submit'] == "Speichern" && AufgabeUtilities::aufgabeExists($ProjektAufgabe->getBezeichnung())) {
                            $htmlStatus->setText("Aufgabe existiert oder existierte bereits.<br/> Soll sie wiederhergestellt werden? Wenden Sie sich an ihren Systembetreuer.");
                            $htmlStatus->setStatusclass(1);
                        } else {
                            $ProjektAufgabe->speichern(true);
                            $htmlStatus->setText("Aufgabe gespeichert.");
                            $htmlStatus->setStatusclass(2);
                        }
                        
                        $tpl->replace("UnteraufgabeSubmit", "Hinzufügen");
                        $tpl->replace("UnteraufgabeDisabled", "");
                        $tpl->replace("BenutzerDisabled", "");
                    } else {
                        $htmlStatus->setText("Aufgabenbezeichnung darf nicht leer sein!");
                        $htmlStatus->setStatusclass(1);
                    }
                }
            } elseif ($_POST['form'] == "unteraufgabe") {
                if ($_POST['submit'] == "Hinzufügen" || $_POST['submit'] == "Bearbeiten") {
                    $p = new Aufgabe();
                    if (isset($_POST['unteraufgabeid']))
                        $p = new Aufgabe(intval($_POST['unteraufgabeid']));
                    
                    $p->setBezeichnung($_POST['unteraufgabe_bez']);
                    $p->setParentID($_POST['paid']);
                    
                    if (AufgabeUtilities::aufgabeExists($p->getBezeichnung())) {
                        $htmlStatus->setText("Unteraufgabe existiert oder existierte bereits.<br/> Soll sie wiederhergestellt werden? Wenden Sie sich an ihren Systembetreuer.");
                        $htmlStatus->setStatusclass(1);
                        $p = new Aufgabe();
                    } elseif (trim($_POST['unteraufgabe_bez']) == "") {
                        $htmlStatus->setText("Bezeichnung muss gef&uuml;lt sein.");
                        $htmlStatus->setStatusclass(1);
                    } else {
                        $htmlStatus->setText("Unteraufgabe gespeichert");
                        $htmlStatus->setStatusclass(2);
                        $p->speichern(true);
                    }
                } elseif ($_POST['submit'] == "Ja, löschen" && isset($_POST['unteraufgabeid'])) {
                    $p = new Aufgabe(intval($_POST['unteraufgabeid']));
                    $p->loeschen();
                    $p->speichern(false);
                    
                    $htmlStatus->setText("Unteraufgabe gel&ouml;scht.");
                    $htmlStatus->setStatusclass(2);
                }
            } elseif ($_POST['form'] == "mitarbeiter") {
                AufgabeUtilities::resetAufgabeMitarbeiterZuordnung($ProjektAufgabe->getID());
                
                foreach ($_POST as $key => $val) {
                    if (substr($key, 0, 6) == "aktiv_") {
                        $s = substr($key, 6);
                        AufgabeUtilities::addMitarbeiterAufgabeFreischalten($ProjektAufgabe->getID(), $s);
                    }
                }
                
                $htmlStatus->setText("Mitarbeiterzuordnung ge&auml;ndert.");
                $htmlStatus->setStatusclass(2);
            }
        }
        
        // Beschriftungen Hauptaufgabe laden
        if (isset($_GET['a'], $_GET['paid'])) {
            $ProjektAufgabe = new Aufgabe($_GET['paid']);
            
            if ($_GET['a'] == "view") {
                $tpl->replace("Submit", "Speichern");
                $tpl->replace("BenutzerDisabled", "Disabled");
                $tpl->replace("UnteraufgabeDisabled", "Disabled");
            } elseif ($_GET['a'] == "edit") {
                $tpl->replace("Submit", "Bearbeiten");
                $tpl->replace("BenutzerDisabled", "");
                $tpl->replace("UnteraufgabeDisabled", "");
            } elseif ($_GET['a'] == "del") {
                $tpl->replace("Submit", "Ja, Aufgabe löschen");
                $tpl->replace("BenutzerDisabled", "Disabled");
                $tpl->replace("UnteraufgabeDisabled", "Disabled");
            }
        }
        
        // Beschriftungen der Unteraufgabe laden
        if (isset($_GET['ua'], $_GET['upaid'])) {
            $UnterProjektAufgabe = new Aufgabe(intval($_GET['upaid']));
            $tpl->replace("UPaID", $UnterProjektAufgabe->getID());
            
            if ($_GET['ua'] == "edit") {
                $tpl->replace("UnteraufgabeSubmit", "Bearbeiten");
                $tpl->replace("UnteraufgabeBezeichnung", $UnterProjektAufgabe->getBezeichnung());
            } elseif ($_GET['ua'] == "del") {
                $tpl->replace("UnteraufgabeSubmit", "Ja, löschen");
                $tpl->replace("UnteraufgabeBezeichnung", $UnterProjektAufgabe->getBezeichnung());
            }
        }
        
        // Projektaufgaben ausgeben
        $col = AufgabeUtilities::getHauptAufgaben();
        foreach ($col as $o) {
            $tplAufgDS->replace("Aufgabe", $o->getBezeichnung());
            $desc = strlen($o->getBeschreibung()) > 55 ? substr($o->getBeschreibung(), 0, 55) . "..." : $o->getBeschreibung();
            $tplAufgDS->replace("Beschreibung", $desc);
            $tplAufgDS->replace("PaID", $o->getID());
            $tplAufgDS->next();
            
            $cUAufg = AufgabeUtilities::loadChildrenAufgaben($o->getID());
            if ($cUAufg->getSize() > 0) {
                foreach ($cUAufg as $oUAufg) {
                    $tplAufgUADS->replace("Aufgabe", $oUAufg->getBezeichnung());
                    $tplAufgUADS->next();
                }
            } else {
                $tplAufgUADS->replace("Aufgabe", "Sie m&uuml;ssen Unteraufgaben eingeben um Zeiten erfassen zu k&ouml;nnen.");
                $tplAufgUADS->next();
            }
            $tplAufgDS->addToBufferBT($tplAufgUADS);
            $tplAufgUADS->reset();
        }
        
        // Unteraufgaben ausgeben
        if (isset($_GET['paid'])) {
            $c = AufgabeUtilities::loadChildrenAufgaben($_GET['paid']);
            
            foreach ($c as $unteraufgabe) {
                $tplUADS->replace("Bezeichnung", $unteraufgabe->getBezeichnung());
                $tplUADS->replace("ParentID", $ProjektAufgabe->getID());
                $tplUADS->replace("PaID", $unteraufgabe->getID());
                $tplUADS->next();
            }
            $tpl->replace("UnteraufgabenListe", $tplUADS->getOutput());
        }
        // Benutzerliste ausgeben
        $iAufgabeID = 0;
        if (isset($_GET['paid'])) {
            $iAufgabeID = intval($_GET['paid']);
        }
        $c = BenutzerUtilities::getMitarbeiterAufgabe($iAufgabeID);
        $tplMaDs = new Template("projekt_aufgabe_verwalten_mads.tpl");
        $strMA = "";
        $iCounter = 1;
        $iBenutzerProZeile = 3;
        foreach ($c as $benutzer) {
            $tplMaDs->replace("Benutzername", $benutzer->getBenutzername());
            $tplMaDs->replace("BenutzerID", $benutzer->getID());
            if ($benutzer->isFreigeschaltet())
                $tplMaDs->replace("checked", Constant::$HTML_CHECKED_CHECKED);
            $tplMaDs->replace("checked", "");
            $tplMaDs->replace("Disabled", "");
            
            if ($iCounter == 1)
                $strMA .= "<tr>";
            
            $strMA .= $tplMaDs->getOutputAndRestore();
            
            if ($iCounter % $iBenutzerProZeile == 0) {
                $strMA .= "</tr>";
                $iCounter = 0;
            }
            $iCounter ++;
        }
        
        if ($htmlStatus != null)
            $tpl->replace("Statusmeldung", $htmlStatus->getOutput());
        
        $tpl->replace("UnteraufgabeSubmit", "Hinzufügen");
        $tpl->replace("UnteraufgabeBezeichnung", "");
        $tpl->replace("BenutzerDisabled", "Disabled");
        $tpl->replace("UnteraufgabeDisabled", "Disabled");
        $tpl->replace("Submit", "Speichern");
        $tpl->replace("MitarbeiterAufgabenliste", $strMA);
        $tpl->replace("Aufgabenliste", $tplAufgDS->getOutput());
        $tpl->replace("Submit", "Speichern");
        $tpl->replace("PaID", $ProjektAufgabe->getID());
        $tpl->replace("Bezeichnung", $ProjektAufgabe->getBezeichnung());
        $tpl->replace("Beschreibung", $ProjektAufgabe->getBeschreibung());
        
        $tpl->anzeigen();
    }

    public static function mitarbeiterVerwalten()
    {
        $tpl = new Template("projekt_mitarbeiter_verwalten.tpl");
        $tplDS = new BufferedTemplate("projekt_mitarbeiter_verwalten_ds.tpl", "CSS", "td1", "td2");
        $tplStatus = null;
        $tplStatus2 = null;
        
        $benutzer = new Benutzer();
        
        if ($_POST) {
            Utilities::escapePost();
            
            $benutzerID = 0;
            if ($_POST['ben_id'] != - 1)
                $benutzerID = $_POST['ben_id'];
            
            $benutzer = new Benutzer($benutzerID);
            
            $tplStatus = new HTMLStatus("", 0);
            
            // Benutzer speichern
            if ($_POST['submit'] == "Speichern" || $_POST['submit'] == "Bearbeiten") {
                $benutzer->setVorname($_POST['vorname']);
                $benutzer->setNachname($_POST['nachname']);
                
                $oldBenutzername = $benutzer->getBenutzername();
                $benutzer->setBenutzername($_POST['benutzername']);
                $benutzer->setBenutzerlevel($_POST['benutzerlevel']);
                if ($_POST['eintrittsdatum'] != "" && strtotime($_POST['eintrittsdatum']) > 0) {
                    $benutzer->setEintrittsDatum(date("Y-m-d", strtotime($_POST['eintrittsdatum'])));
                }
                
                // Nur Passwort setzen, wenn eine Eingabe gemacht wurde
                if ($_POST['passwort'] != "") {
                    $benutzer->setPasswort(md5($_POST['passwort']));
                }
                
                // Wochenstunden speichern
                $iStdGesamt = 0;
                foreach ($_POST as $key => $val) {
                    if (substr($key, 0, 8) == "stunden_") {
                        $_POST[$key] = str_replace(",", ".", $val);
                        $iStdGesamt += $val;
                    }
                }
                
                $benutzer->setStdMontag($_POST['stunden_0']);
                $benutzer->setStdDienstag($_POST['stunden_1']);
                $benutzer->setStdMittwoch($_POST['stunden_2']);
                $benutzer->setStdDonnerstag($_POST['stunden_3']);
                $benutzer->setStdFreitag($_POST['stunden_4']);
                $benutzer->setStdSamstag($_POST['stunden_5']);
                $benutzer->setStdSonntag($_POST['stunden_6']);
                $benutzer->setStdGesamt(doubleval($iStdGesamt));
                $benutzer->setStdLohn(WaehrungUtil::formatWaehrungToDB($_POST['lohn']));
                $benutzer->setVerrechnungsSatz(WaehrungUtil::formatWaehrungToDB($_POST['verrechnungssatz']));
                $benutzer->setAktiviert($_POST['aktiviert']);
                $benutzer->setChangeAt(0);
                if (isset($_POST['zeiterfassung'])) {
                    $benutzer->setZeiterfassung(1);
                } else {
                    $benutzer->setZeiterfassung(0);
                }
                
                // Urlaubstage umrechen & speichern
                $_POST['urlaubstage'] = str_replace(",", ".", $_POST['urlaubstage']);
                $_POST['urlaubstage'] *= ($iStdGesamt / 5); // 40 / 5 = 8 Stunden pro Tag
                $benutzer->setUrlaubstage($_POST['urlaubstage']);
                
                $strText = "";
                // Benutzername existiert?
                if (! preg_match("/^[a-zA-Z]/", $benutzer->getBenutzername()))
                    $strText = "<li>Benutzername darf nur aus Buchstaben bestehen.</li>";
                if ($benutzer->getBenutzername() == "")
                    $strText .= "<li>Benutzername darf nicht leer sein.</li>";
                if (strlen($benutzer->getBenutzername()) > ConstantLoader::getBenutzerMaxUsernameLength())
                    $strText .= "<li>Benutzername darf höchstens " . ConstantLoader::getBenutzerMaxUsernameLength() . " Zeichen haben.</li>";
                
                if ($_POST['passwort'] != "") {
                    if (strlen($_POST['passwort']) > 0 && strlen($_POST['passwort']) < ConstantLoader::getBenutzerMinPasswortLength()) {
                        $strText .= "<li>Passwort muss mindestens " . ConstantLoader::getBenutzerMinPasswortLength() . " Zeichen haben.</li>";
                    }
                    if ($_POST['passwort'] == $benutzer->getVorname() || $_POST['passwort'] == $benutzer->getNachname() || $_POST['passwort'] == $benutzer->getBenutzername()) {
                        $strText .= "<li>Passwort darf nicht dem Vor- Nach- oder Benutzernamen entsprechen.</li>";
                    }
                    
                    // wurde bereits von einenem anderen nutzer gewaehlt
                    if (BenutzerUtilities::loadByPin($_POST['passwort'], true) != null) {
                        $strText .= "<li>Dieses Passwort wird bereits verwendet. Bitte ein anderes waehlen.</li>";
                    }
                }
                $isBenutzernameChanged = $oldBenutzername != $benutzer->getBenutzername();
                if ($_POST['submit'] != "Bearbeiten" && BenutzerUtilities::exists($benutzer->getBenutzername())) {
                    $strText .= "<li>Benutzername existiert bereits.</li>";
                } elseif ($isBenutzernameChanged && BenutzerUtilities::exists($benutzer->getBenutzername())) {
                    $strText .= "<li>Benutzer kann nicht umbenannt werden, da der neueu Name bereits verwendet wird.</li>";
                }
                
                if ($strText == "") {
                    $isNew = false;
                    if ($benutzer->getID() == - 1) {
                        $isNew = true;
                    }
                    $benutzer->speichern(true);
                    
                    // Neue Mitarbeiter fuer alle Aufgaben berechtigen
                    if ($isNew) {
                        $aufgaben = AufgabeUtilities::getAlleAufgaben();
                        foreach ($aufgaben as $currentAufgabe) {
                            AufgabeUtilities::addMitarbeiterAufgabeFreischalten($currentAufgabe->getID(), $benutzer->getID());
                        }
                    } else {
                        AufgabeUtilities::resetMitarbeiterAufgabeZuordnung($benutzer->getID());
                        foreach ($_POST as $key => $val) {
                            if (strpos($key, "aufgabe_") === 0) {
                                AufgabeUtilities::addMitarbeiterAufgabeFreischalten($val, $benutzer->getID());
                            }
                        }
                    }
                    
                    $tplStatus->setText("Benutzer gespeichert. <b>Ggf. Aufgabenverwaltung anpassen!</b>");
                    $tplStatus->setStatusclass(2);
                    $tpl->replace("Submit", "Bearbeiten");
                    $tpl->replace("BenID", $benutzer->getID());
                } else {
                    $tplStatus->setText("Es sind Fehler aufgetreten: <ul>" . $strText);
                    $tplStatus->setStatusclass(1);
                    $tpl->replace("Submit", $_POST['submit']);
                    if ($_POST['submit'] == "Bearbeiten") {
                        $tpl->replace("BenID", $benutzer->getID());
                    }
                }
            } elseif ($_POST['submit'] == "Ja, Mitarbeiter löschen" && isset($_POST['ben_id'])) {
                $tplStatus->setText("Benutzer gel&ouml;scht.");
                $tplStatus->setStatusclass(2);
                if ($benutzer->getBenutzername() != "swatermeyer") {
                    $benutzer->loeschen();
                    $benutzer->setAktiviert(0);
                    $benutzer->speichern(false);
                } else {
                    $tplStatus->setText("Dieser Benutzer kann nur in dieser Version nicht gel&ouml;scht werden.");
                    $tplStatus->setStatusclass(1);
                }
                
                $benutzer = new Benutzer();
            }
            
            $tpl->replace("Submit", "Speichern");
        }
        
        // Submit Button Text aendern
        if (isset($_GET['a'], $_GET['id'])) {
            $benutzer = new Benutzer($_GET['id']);
            if ($benutzer->isDemo()) {
                throw new Exception("its not valid to edit demo users: " . $_GET['id']);
            }
            
            if ($_GET['a'] == "view") {
                $tpl->replace("Submit", "Speichern");
            } elseif ($_GET['a'] == "edit") {
                $tpl->replace("Submit", "Bearbeiten");
            } elseif ($_GET['a'] == "del") {
                $tpl->replace("Submit", "Ja, Mitarbeiter löschen");
            }
            $tpl->replace("BenID", $benutzer->getID());
        }
        
        // Benutzerliste ausgeben
        $col = BenutzerUtilities::getBenutzer("ORDER BY be_sortierung ASC, be_nachname ASC");
        foreach ($col as $o) {
            $tplDS->replace("Benutzernamen", $o->getBenutzername());
            $tplDS->replace("Vorname", $o->getVorname());
            $tplDS->replace("Nachname", $o->getNachname());
            if (10 == $o->getBenutzerlevel())
                $tplDS->replace("Benutzerlevel", "Administrator");
            if (5 == $o->getBenutzerlevel())
                $tplDS->replace("Benutzerlevel", "Monteur");
            $tplDS->replace("Benutzerlevel", "Mitarbeiter");
            
            if ($o->isAktiviert())
                $tplDS->replace("Aktiviert", "Aktiviert");
            $tplDS->replace("Aktiviert", "Deaktiviert");
            $tplDS->replace("BenID", $o->getID());
            $tplDS->next();
        }
        
        // Benutzerdaten
        $tpl->replace("BenID", "-1");
        $tpl->replace("Aufgabenliste", $tplDS->getOutput());
        $tpl->replace("Submit", "Speichern");
        $tpl->replace("benutzerID", $benutzer->getID());
        $tpl->replace("Vorname", $benutzer->getVorname());
        $tpl->replace("Nachname", $benutzer->getNachname());
        $tpl->replace("Benutzername", $benutzer->getBenutzername());
        $tpl->replace("Eintrittsdatum", ($benutzer->getEintrittsDatum(false) != 0 ? $benutzer->getEintrittsDatum(true) : ""));
        $tpl->replace("Lohn", WaehrungUtil::formatDoubleToWaehrung($benutzer->getStdLohn()));
        $tpl->replace("VerrechnungsSatz", WaehrungUtil::formatDoubleToWaehrung($benutzer->getVerrechnungsSatz()));
        
        // Benutzer Aufgabe
        $mitarbeiterAufgaben = AufgabeUtilities::getMitarbeiterAufgaben($benutzer->getID());
        $maAufgaben = array();
        foreach ($mitarbeiterAufgaben as $currentAufgabe) {
            $maAufgaben[$currentAufgabe->getID()] = $currentAufgabe;
        }
        
        $alleAufgaben = AufgabeUtilities::getHauptAufgaben();
        $tplAufgaben = new BufferedTemplate("projekt_mitarbeiter_aufgaben_ds.tpl", "CSS", "td1", "td2");
        foreach ($alleAufgaben as $currentAufgabe) {
            if (isset($maAufgaben[$currentAufgabe->getID()])) {
                $tplAufgaben->replace("Checked", "checked=\"checked\"");
            } else {
                $tplAufgaben->replace("Checked", "");
            }
            $tplAufgaben->replace("Bezeichnung", $currentAufgabe->getBezeichnung());
            $tplAufgaben->replace("AufgabeID", $currentAufgabe->getID());
            
            if ($benutzer->getID() > 0) {
                $tplAufgaben->replace("Disabled", "");
            } else {
                $tplAufgaben->replace("Disabled", "disabled=\"disabled\"");
            }
            $tplAufgaben->next();
        }
        
        $tpl->replace("Aufgaben", $tplAufgaben->getOutput());
        
        // Urlaubstage
        $dblUrlaubstage = ConstantLoader::getStandardUrlaubstage();
        if ($benutzer->getStdGesamt() > 0) {
            $dblUrlaubstage = $benutzer->getUrlaubstage() / ($benutzer->getStdGesamt() / 5);
        }
        $tpl->replace("Urlaubstage", str_replace(".", ",", $dblUrlaubstage));
        
        // Benutzerstunden
        $tpl->replace("Stunden0", $benutzer->getID() == - 1 ? ConstantLoader::getStandardArbeitsstundenMontag() : $benutzer->getStdMontag());
        $tpl->replace("Stunden1", $benutzer->getID() == - 1 ? ConstantLoader::getStandardArbeitsstundenDienstag() : $benutzer->getStdDienstag());
        $tpl->replace("Stunden2", $benutzer->getID() == - 1 ? ConstantLoader::getStandardArbeitsstundenMittwoch() : $benutzer->getStdMittwoch());
        $tpl->replace("Stunden3", $benutzer->getID() == - 1 ? ConstantLoader::getStandardArbeitsstundenDonnerstag() : $benutzer->getStdDonnerstag());
        $tpl->replace("Stunden4", $benutzer->getID() == - 1 ? ConstantLoader::getStandardArbeitsstundenFreitag() : $benutzer->getStdFreitag());
        $tpl->replace("Stunden5", $benutzer->getID() == - 1 ? ConstantLoader::getStandardArbeitsstundenSamstag() : $benutzer->getStdSamstag());
        $tpl->replace("Stunden6", $benutzer->getID() == - 1 ? ConstantLoader::getStandardArbeitsstundenSonntag() : $benutzer->getStdSonntag());
        $tpl->replace("Summe", $benutzer->getID() == - 1 ? ConstantLoader::getStandardWochenstunden() : $benutzer->getStdGesamt());
        
        // Ueberstunden
        $ueberstunden = 0;
        if ($benutzer->getID() > 0) {
            $ueberstunden = ProjektUtilities::countMitarbeiterUeberstunden($benutzer->getID());
        }
        $tpl->replace("Ueberstunden", $ueberstunden);
        
        // Benutzerstatus
        if ($benutzer->isAdmin()) {
            $tpl->replace("Admin", Constant::$HTML_SELECTED_SELECTED);
            $tpl->replace("Mitarbeiter", "");
        }
        if ($benutzer->isMonteur()) {
            $tpl->replace("Monteur", Constant::$HTML_SELECTED_SELECTED);
            $tpl->replace("Mitarbeiter", "");
        }
        $tpl->replace("Admin", "");
        $tpl->replace("Monteur", "");
        $tpl->replace("Mitarbeiter", Constant::$HTML_SELECTED_SELECTED);
        
        if ($benutzer->isAktiviert()) {
            $tpl->replace("Aktiviert", Constant::$HTML_SELECTED_SELECTED);
            $tpl->replace("Deaktiviert", "");
        }
        $tpl->replace("Aktiviert", "");
        $tpl->replace("Deaktiviert", Constant::$HTML_SELECTED_SELECTED);
        
        if ($benutzer->isZeiterfassung())
            $tpl->replace("ZeiterfassungCheck", Constant::$HTML_CHECKED_CHECKED);
        $tpl->replace("ZeiterfassungCheck", "");
        
        // HTML Status Ausgabe
        if ($tplStatus != null)
            $tpl->replace("Status", $tplStatus->getOutput());
        $tpl->replace("Status", "");
        
        if ($tplStatus2 != null)
            $tpl->replace("Status2", $tplStatus2->getOutput());
        $tpl->replace("Status2", "");
        
        $tpl->anzeigen();
    }

    public static function zeigeProjektarchiv()
    {
        $tpl = new Template("projekt_archiv.tpl");
        $tplDS = new BufferedTemplate("projekt_archiv_ds.tpl", "CSS", "td1", "td2");
        $htmlStatus = null;
        
        if (isset($_GET['pid'], $_GET['a'])) {
            $p = new Projekt(intval($_GET['pid']));
            $htmlStatus = new HTMLStatus();
            
            if ($_GET['a'] == "d") {
                $p->loeschen();
                $p->speichern(false);
                $htmlStatus->setText("Projekt gel&ouml;scht.");
                $htmlStatus->setStatusclass(2);
            } elseif ($_GET['a'] == "r") {
                $p->setArchviert(0);
                $p->setArchivdatum(0);
                $p->speichern(true);
                $htmlStatus->setText("Projekt wiederhergestellt.");
                $htmlStatus->setStatusclass(2);
            } else {
                $htmlStatus->setText("Ung&uuml;ltige Eingabe.");
                $htmlStatus->setStatusclass(1);
            }
        }
        
        $c = ProjektUtilities::getArchivierteProjekte();
        foreach ($c as $projekt) {
            $tplDS->replace("ProjektID", $projekt->getID());
            $g = new Gemeinde($projekt->getGemeindeID());
            $tplDS->replace("GemeindeBezeichnung", $g->getKirche());
            $tplDS->replace("Bezeichnung", $projekt->getBezeichnung());
            $tplDS->replace("Starttermin", $projekt->getStart(true));
            $tplDS->replace("Endtermin", $projekt->getEnde(true));
            $tplDS->replace("Archiviert", $projekt->getArchivdatum(true));
            $tplDS->next();
        }
        
        if ($htmlStatus != null)
            $tpl->replace("Statusmeldung", $htmlStatus->getOutput());
        $tpl->replace("Statusmeldung", "");
        $tpl->replace("Projektliste", $tplDS->getOutput());
        
        $tpl->anzeigen();
    }

    public static function bearbeiteProjektdetails()
    {
        RequestHandler::handle(new ProjektBearbeitenAction());
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

    public static function zeigeArbeitszeitVerwaltung()
    {
        RequestHandler::handle(new ArbeitszeitVerwaltungAction());
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

    public static function zeigeProjektDetails()
    {
        RequestHandler::handle(new ProjektDetailsAction());
    }

    public static function zeigeZeiterfassungWrapper()
    {
        BenutzerController::zeigeZeiterfassung();
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
                } elseif ($kw->getEingabeOffen ()) {
                    $tplLinksFuerDenDatensatz = $tplLinksNormal;
                    $tplMaDS->replace("Status", "Offen");
                    $tplMaDS->replace("StatusClass", "awStatusOffen");
                } elseif ($kw->getEingabeKomplett()) {
                    $tplLinksFuerDenDatensatz = $tplLinksNormal;
                    $tplMaDS->replace("Status", "Fertig");
                    $tplMaDS->replace("StatusClass", "awStatusFertig");
                } else {
                    $tplMaDS->replace("Status", "Status: ".$kw->getStatus());
                    $tplMaDS->replace("StatusClass", "awStatusFertig");
                }
                
                $tplLinksFuerDenDatensatz->replace("BenutzerID", $kw->getBenutzerID());
                $tplLinksFuerDenDatensatz->replace("Datum",strtotime($kw->getWochenstart()));
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
    
}
?>