<?php

class IntrusionNotifactionMailer
{

    public static function send($betreff, $content)
    {
        $retVal = false;
        if (IntrusionNotifactionMailer::isOnline()) {
            $retVal = mail(SUPPORT_MAIL_ADDR, "Intrusion Notification: " . INSTALLATION_NAME . ": " . $betreff, $content . IntrusionNotifactionMailer::generateContent(), "from:" . SUPPORT_MAIL_FROM);
        }
        return $retVal;
    }

    private static function generateContent()
    {
        $out = "";
        $out .= "\n\n";
        $out .= "---------------------\n";
        $out .= "URL: " . $_SERVER['REQUEST_URI'] . "\n";
        $out .= "---------------------\n";
        $out .= "SERVER\n";
        $out .= "---------------------\n";
        $out .= print_r($_SERVER, true);
        $out .= "";
        $out .= "---------------------\n";
        $out .= "SESSION \n";
        $out .= "---------------------\n";
        $out .= print_r($_SESSION, true);
        return $out;
    }

    private static function isOnline()
    {
        return $_SERVER['REMOTE_ADDR'] != "127.0.0.1";
    }
}
?>