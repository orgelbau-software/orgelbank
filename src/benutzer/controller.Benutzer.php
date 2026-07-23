<?php

class BenutzerController
{

    public static function benutzerZeitauswertung()
    {
        RequestHandler::handle(new BenutzerZeitauswertung());
    }

    public static function benutzerdatenAendern()
    {
        RequestHandler::handle(new BenutzerDatenAendernAction());
    }

      public static function zeige2FADialog()
    {
        RequestHandler::handle(new BenutzerDaten2FAAction());
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