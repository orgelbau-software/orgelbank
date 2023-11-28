<?php

class ProjektMitarbeiterVerwaltung implements GetRequestHandler, PostRequestHandler
{

    /**
     *
     * {@inheritdoc}
     *
     * @return bool
     */
    public function validateGetRequest()
    {
        return true;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @return HTMLStatus
     */
    public function handleInvalidGet()
    {
        return new HTMLStatus("Alles ok");
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function prepareGet()
    {
        return;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @return Template
     */
    public function executeGet()
    {
        $tpl = new Template("projekt_mitarbeiter_verwalten.tpl");
        $tplDS = new BufferedTemplate("projekt_mitarbeiter_verwalten_ds.tpl", "CSS", "td1", "td2");
        $tplStatus = null;
        $tplStatus2 = null;
        
        $benutzer = new Benutzer();
        
        if ($_POST) {
            Utilities::escapePost();
            
            $benutzerID = 0;
            if ($_POST['ben_id'] != - 1) {
                $benutzerID = $_POST['ben_id'];
            }
            
            $benutzer = new Benutzer($benutzerID);
            
            $tplStatus = new HTMLStatus("", 0);
            
            // Benutzer speichern
            if ($_POST['submit'] == "Speichern" || $_POST['submit'] == "Bearbeiten") {
                $benutzer->setVorname($_POST['vorname']);
                $benutzer->setNachname($_POST['nachname']);
                
                $oldBenutzername = $benutzer->getBenutzername();
                $benutzer->setBenutzername($_POST['benutzername']);
                $benutzer->setEmail($_POST['email']);
                $benutzer->setBenutzerlevel($_POST['benutzerlevel']);
                if ($_POST['eintrittsdatum'] != "" && strtotime($_POST['eintrittsdatum']) > 0) {
                    $benutzer->setEintrittsDatum($_POST['eintrittsdatum']);
                }
                
                // Nur Passwort setzen, wenn eine Eingabe gemacht wurde
                if ($_POST['passwort'] != "") {
                    $benutzer->setPasswort(PasswordUtility::encrypt($_POST['passwort']));
                    $benutzer->setFailedLoginCount(0); // Bei Neuvergabe auch den FehlerCount zurücksetzen.
                }
                
                // Wochenstunden speichern
                $iStdGesamt = 0;
                foreach ($_POST as $key => $val) {
                    if (substr($key, 0, 8) == "stunden_") {
                        $formattedValue = str_replace(",", ".", $val);
                        $_POST[$key] = $formattedValue;
                        $iStdGesamt += $formattedValue;
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
                $benutzer->setStdLohn($_POST['lohn']);
                $benutzer->setVerrechnungsSatz($_POST['verrechnungssatz']);
                $benutzer->setAktiviert($_POST['aktiviert']);
                $benutzer->setChangeAt(0);
                if (isset($_POST['zeiterfassung'])) {
                    $benutzer->setZeiterfassung(1);
                } else {
                    $benutzer->setZeiterfassung(0);
                }
                
                // Urlaubstage umrechen & speichern
                $_POST['urlaubstage'] = $_POST['urlaubstage'];
                $benutzer->setUrlaubstage($_POST['urlaubstage']);
                
                $strText = "";
                // Benutzername existiert?
                if (! preg_match("/^[a-zA-Z]/", $benutzer->getBenutzername()))
                    $strText = "<li>Benutzername darf nur aus Buchstaben bestehen.</li>";
                if ($benutzer->getBenutzername() == "")
                    $strText .= "<li>Benutzername darf nicht leer sein.</li>";
                if (strlen($benutzer->getBenutzername()) > ConstantLoader::getBenutzerMaxUsernameLength())
                    $strText .= "<li>Benutzername darf höchstens " . ConstantLoader::getBenutzerMaxUsernameLength() . " Zeichen haben.</li>";
                if ($_POST['email'] != "" && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    $strText .= "<li>Email Adresse ist ungültig: " . $_POST['email'] . "</li>";
                }
                
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
        $tpl->replace("Email", $benutzer->getEmail());
        $tpl->replace("Eintrittsdatum", ($benutzer->getEintrittsDatum(false) != 0 ? $benutzer->getEintrittsDatum(false) : ""));
        $tpl->replace("Lohn",$benutzer->getStdLohn());
        $tpl->replace("VerrechnungsSatz",$benutzer->getVerrechnungsSatz());
        
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
            $dblUrlaubstage = $benutzer->getUrlaubstage();
        }
        $tpl->replace("Urlaubstage", $dblUrlaubstage);
        
        // Benutzerstunden
        $tpl->replace("Stunden0", $benutzer->getID() == - 1 ? ConstantLoader::getStandardArbeitsstundenMontag() : $benutzer->getStdMontag());
        $tpl->replace("Stunden1", $benutzer->getID() == - 1 ? ConstantLoader::getStandardArbeitsstundenDienstag() :  $benutzer->getStdDienstag());
        $tpl->replace("Stunden2", $benutzer->getID() == - 1 ? ConstantLoader::getStandardArbeitsstundenMittwoch() :  $benutzer->getStdMittwoch());
        $tpl->replace("Stunden3", $benutzer->getID() == - 1 ? ConstantLoader::getStandardArbeitsstundenDonnerstag() :  $benutzer->getStdDonnerstag());
        $tpl->replace("Stunden4", $benutzer->getID() == - 1 ? ConstantLoader::getStandardArbeitsstundenFreitag() : $benutzer->getStdFreitag());
        $tpl->replace("Stunden5", $benutzer->getID() == - 1 ? ConstantLoader::getStandardArbeitsstundenSamstag() : $benutzer->getStdSamstag());
        $tpl->replace("Stunden6", $benutzer->getID() == - 1 ? ConstantLoader::getStandardArbeitsstundenSonntag() :  $benutzer->getStdSonntag());
        $tpl->replace("Summe", $benutzer->getID() == - 1 ? ConstantLoader::getStandardWochenstunden() : $benutzer->getStdGesamt());
        
        // Ueberstunden
        $ueberstunden = 0;
        if ($benutzer->getID() > 0) {
            $ueberstunden = ProjektUtilities::countMitarbeiterUeberstunden($benutzer->getID());
        }
        $tpl->replace("Ueberstunden", $ueberstunden);
        
        $urlaub = UrlaubsUtilities::getLetzterUrlaubsEintrag($benutzer->getID());
        if ($urlaub != null) {
            $tpl->replace("AktuellerUrlaub", $urlaub->getVerbleibend());
            $tpl->replace("Resturlaub", $urlaub->getResturlaub());
        } else {
            $tpl->replace("AktuellerUrlaub", "");
            $tpl->replace("Resturlaub", "");
        }
        
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
        
        
        // Ueberstunden / Wochenstatistik
        if ($benutzer->getID() > 0) {
            $data = ArbeitswocheUtilities::ladeArbeitswochenByBenutzerId($benutzer->getID(), date("Y"));
            $letztesJahr = 0;
            $totalStundenDif = 0;
            
            $tplUeberstunden = new BufferedTemplate("projekt_mitarbeiter_uestunden_ds.tpl", "CSS", "td1", "td2");
            foreach ($data as $currentData) {
                
                if ($letztesJahr == 0 || $currentData->getJahr() != $letztesJahr) {
                    
                    // TODO: Hier muss die Option rein ob Stunden genullt werden sollen oder nicht. Elmar will es haben.
                    $totalStundenDif = 0;
                }

                $tplUeberstunden->replace("Kalenderwoche", $currentData->getKalenderWoche());
                $tplUeberstunden->replace("Jahr", $currentData->getJahr());
                $tplUeberstunden->replace("WochenStart", $currentData->getWochenStart());
                $tplUeberstunden->replace("Soll", $currentData->getWochenStundenSoll());
                $tplUeberstunden->replace("Ist", $currentData->getWochenStundenIst());
                $tplUeberstunden->replace("Differenz", $currentData->getWochenStundenDif());
                $tplUeberstunden->replace("TotalDif1", $totalStundenDif);
                $totalStundenDif += $currentData->getWochenStundenDif();
                $tplUeberstunden->replace("TotalDif2", $totalStundenDif);
                
                $letztesJahr = $currentData->getJahr();
                $tplUeberstunden->next();
            }
            
            $tpl->replace("UeberstundenData", $tplUeberstunden->getOutput());
        } else {
            $tpl->replace("UeberstundenData", "");
        }
        
        
        // HTML Status Ausgabe
        if ($tplStatus != null)
            $tpl->replace("Status", $tplStatus->getOutput());
        $tpl->replace("Status", "");
        
        if ($tplStatus2 != null)
            $tpl->replace("Status2", $tplStatus2->getOutput());
        $tpl->replace("Status2", "");
        
        return $tpl;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see PostRequestHandler::preparePost()
     */
    public function preparePost()
    {
        return;
    }

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