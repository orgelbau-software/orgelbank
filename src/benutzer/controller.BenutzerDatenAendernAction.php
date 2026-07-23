<?php

class BenutzerDatenAendernAction implements GetRequestHandler, PostRequestHandler
{
    #[Override]
    public function executeGet()
    {
        global $webUser;
        
        if (! $webUser->isAuthed()) {
            die("Forbidden!");
        }
        
        $tpl = new Template("benutzer_datenaendern.tpl");
        $tplStatus = null;
        $benutzer = new Benutzer($webUser->getID());
        
        $iMinPWLength = ConstantLoader::getBenutzerMinPasswortLength();
        $tpl->replace("MinPWLength", $iMinPWLength);
        
        $eingabeFehler = true;
        // wird auch direkt nach dem Login aufgerufen, deshalb pruefen ob das Feld "bestaetigung" gesetzt ist
        if ($_POST && isset($_POST['bestaetigung'])) {
            $tplStatus = new HTMLStatus("", 3, false);
            
            if ($_POST['passwort'] != "" && BenutzerUtilities::validatePassword($_POST['passwort']) == false) {
                $strText ="<ul>";
                $strText .= "<li>Passwort muss mindestens " . ConstantLoader::getBenutzerMinPasswortLength() . " Zeichen haben.</li>";
                $strText .= "<li>Passwort muss mindestens einen Großbuchstaben haben</li>";
                $strText .= "<li>Passwort muss mindestens einen Kleinbuchstaben haben</li>";
                $strText .= "<li>Passwort muss mindestens ein Sonderzeichen aus Punkt, Komma, Ausrufezeichen, Fragezeichen, @, Bindestrich oder Unterstrich.</li>";
                $strText .="</ul>";
                $tplStatus->setText($strText);
                $tplStatus->setStatusclass(1);
            } elseif ($_POST['passwort'] != "" && $_POST['passwort'] != $_POST['bestaetigung']) {
                $tplStatus->setText("Zwei verschiedene Passw&ouml;rter eingegeben!");
                $tplStatus->setStatusclass(1);
            } elseif ($_POST['passwort'] != "" && ($_POST['passwort'] == $benutzer->getVorname() || $_POST['passwort'] == $benutzer->getNachname() || $_POST['passwort'] == $benutzer->getBenutzername())) {
                $tplStatus->setText("Passwort darf weder Vor- Nach- noch dem Benutzernamen entsprechen!" . $_POST['passwort']);
                $tplStatus->setStatusclass(1);
            } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $tplStatus->setText("Email Adresse ungültig: " . $_POST['email']);
                $tplStatus->setStatusclass(1);
            } else {
                // Benutzer laden
                $benutzer->setVorname($_POST['vorname']);
                $benutzer->setNachname($_POST['nachname']);
                $benutzer->setEmail($_POST['email']);
                
                if ($_POST['passwort'] != "") {
                    $benutzer->setPasswort(md5(PASSWORD_SALT . $_POST['passwort']));
                }
                $eingabeFehler = false;
                $tplStatus->setText("Benutzerdaten gespeichert");
                $tplStatus->setStatusclass(2);
                
                $benutzer->speichern(false);
            }
        }

        $weiterleitung2FADialog = false;
        if ($_POST && isset($_POST['2fastatus'])) {
            if(intval($_POST['2fastatus']) == Benutzer::$ZWEIFAKTOR_STATUS_DEAKTIVIERT) {
                $benutzer->set2FASecret("");
                $benutzer->set2FAAktiv("1"); // Inaktiv
            } else {
                // Aktiviert wird erst, wenn der Code erfolgreich gesetzt wurde
                //$benutzer->set2FAAktiv("0"); // Aktiv
                $weiterleitung2FADialog = true;
            }
            $benutzer->speichern(false);
        }
        
        $tpl->replace("Vorname", $benutzer->getVorname());
        $tpl->replace("Nachname", $benutzer->getNachname());
        $tpl->replace("Benutzername", $benutzer->getBenutzername());
        $tpl->replace("Email", $benutzer->getEmail());
        
        $zweiFAStatus = $benutzer->get2FAAktiv();
        $tpl2FAStatus = new HTMLSelectForArray(array(Benutzer::$ZWEIFAKTOR_STATUS_DEAKTIVIERT => "Deaktiviert", Benutzer::$ZWEIFAKTOR_STATUS_AKTIVIERT => "Aktiviert"), $zweiFAStatus);
        $tpl->replace("2FAStatus", $tpl2FAStatus->getOutput());
        
        if ($tplStatus != null) {
            $tpl->replace("Statusmeldung", $tplStatus->getOutput());
        }

        // Weiterleitung zur 2FA Einrichtungs Seite.
        if($eingabeFehler == false && $weiterleitung2FADialog) {
            $tpl = new HTMLRedirect("Sie werden weitergeleitet.", "index.php?page=8&do=146");
        }
        return $tpl;
    }

    #[Override]
    public function preparePost()
    {
        // Nothing to Do
    }

    #[Override]
    public function executePost()
    {
        return $this->executeGet();
    }
    #[Override]
    public function validateGetRequest()
    {
        return true;
    }

    #[Override]
    public function handleInvalidGet()
    {
        return new HTMLStatus("Ungueltige Anfrage.", 4);
    }

    #[Override]
    public function prepareGet()
    {
        // Nothing
    }

    
    
}