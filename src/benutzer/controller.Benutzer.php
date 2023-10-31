<?php

class BenutzerController
{

    public static function benutzerZeitauswertung()
    {
        RequestHandler::handle(new BenutzerZeitauswertung());
    }

    public static function benutzerdatenAendern()
    {
        global $webUser;
        
        if (! $webUser->isAuthed())
            die("Forbidden!");
        
        $tpl = new Template("benutzer_datenaendern.tpl");
        $tplStatus = null;
        $benutzer = new Benutzer($webUser->getID());
        
        $iMinPWLength = ConstantLoader::getBenutzerMinPasswortLength();
        $tpl->replace("MinPWLength", $iMinPWLength);
        
        // wird auch direkt nach dem Login aufgerufen, deshalb pruefen ob das Feld "bestaetigung" gesetzt ist
        if ($_POST && isset($_POST['bestaetigung'])) {
            $tplStatus = new HTMLStatus("", 3);
            
            if ($_POST['passwort'] != "" && strlen($_POST['passwort']) < $iMinPWLength) {
                $tplStatus->setText("Passwort ist zu kurz. Mindestens " . $iMinPWLength . " Zeichen");
                $tplStatus->setStatusclass(1);
            } elseif ($_POST['passwort'] != "" && $_POST['passwort'] != $_POST['bestaetigung']) {
                $tplStatus->setText("Zwei verschiedene Passw&ouml;rter eingegeben!");
                $tplStatus->setStatusclass(1);
            } elseif ($_POST['passwort'] != "" && ($_POST['passwort'] == $benutzer->getVorname() || $_POST['passwort'] == $benutzer->getNachname() || $_POST['passwort'] == $benutzer->getBenutzername())) {
                $tplStatus->setText("Passwort darf weder Vor- Nach- noch dem Benutzernamen entsprechen!" . $_POST['passwort']);
                $tplStatus->setStatusclass(1);
            } else {
                // Benutzer laden
                $benutzer->setVorname($_POST['vorname']);
                $benutzer->setNachname($_POST['nachname']);
                
                if ($_POST['passwort'] != "") {
                    $benutzer->setPasswort(md5(PASSWORD_SALT . $_POST['passwort']));
                }
                
                $tplStatus->setText("Benutzerdaten gespeichert");
                $tplStatus->setStatusclass(2);
                
                $benutzer->speichern(false);
            }
        }
        
        $tpl->replace("Vorname", $benutzer->getVorname());
        $tpl->replace("Nachname", $benutzer->getNachname());
        $tpl->replace("Benutzername", $benutzer->getBenutzername());
        
        if ($tplStatus != null) {
            $tpl->replace("Statusmeldung", $tplStatus->getOutput());
        }
        $tpl->anzeigen();
    }

    public static function benutzerLogout()
    {
        global $webUser;
        
        $webUser->logout();
        
        $h = new HTMLRedirect("Sie wurden erfolgreich ausgeloggt", "index.php");
        $h->anzeigen();
    }

    public static function zeigeZeiterfassung()
    {
        RequestHandler::handle(new ZeiterfassungsAction());
    }

    public static function doHilfeRufen()
    {
        global $webUser;
        
        $firmenAnschrift = new Ansprechpartner(1);
        
        $sekunden = 1;
        if ($_POST['nachricht'] != ConstantLoader::getAdminNachrichtenHoverText()) {
            $titel = INSTALLATION_NAME . ": Hilferuf!";
            $msg = "";
            $msg .= "Adresse: " . $_POST['help_addr'] . "\r\n";
            $msg .= "\r\n";
            $msg .= "Datum: " . date("d.m.Y H:i:s") . "\r\n";
            $msg .= "\r\n";
            $msg .= "Nachricht: " . $_POST['nachricht'] . "\r\n";
            $msg .= "\r\n";
            $msg .= "Benutzer: " . $webUser->getBenutzername() . " \r\n";
            $msg .= "\r\n";
            $msg .= "Umgebungsvariabel:\r\n";
            $msg .= "\tPOST: " . print_r($_POST['help_post'], true) . "\r\n";
            
            $htmlStatus = new HTMLStatus("Nachricht wurde verschickt.", 2);
            $header = "from:" . SUPPORT_MAIL_FROM . "\r\n";
            if ($firmenAnschrift->getEmail() != "") {
                $header .= "Reply-To: " . $firmenAnschrift->getEmail() . "\r\n";
            } else {}
            @mail(SUPPORT_MAIL_ADDR, $titel, $msg, $header);
        } else {
            $sekunden = 3;
            $htmlStatus = new HTMLStatus("Sie haben keinen Text eingegeben!", 1);
        }
        $html = new HTMLRedirect($htmlStatus->getOutput(), $_POST['help_addr'], $sekunden);
        $html->anzeigen();
    }

    public static function doHilfeRufenCronjob($pCronjobArray)
    {
        $firmenAnschrift = new Ansprechpartner(1);
        
        if(is_array($pCronjobArray)) {
            $content = print_r($pCronjobArray, true);
        } else {
            $content = $pCronjobArray;
        }
        
        $sekunden = 1;
        $titel = INSTALLATION_NAME . ": Cronjob!";
        $msg = "";
        $msg .= "Datum: " . date("d.m.Y H:i:s") . "\r\n";
        $msg .= "\r\n";
        $msg .= "Nachricht: " . $content . "\r\n";
        $msg .= "\r\n";
        
        $header = "from:" . SUPPORT_MAIL_FROM . "\r\n";
        if ($firmenAnschrift->getEmail() != "") {
            $header .= "Reply-To: " . $firmenAnschrift->getEmail() . "\r\n";
        } else {}
        @mail(SUPPORT_MAIL_ADDR, $titel, $msg, $header);
    }
}

?>