<?php

class SupportMail
{

    public static function send($betreff, $content)
    {
        $retVal = false;
        if (SupportMail::isOnline() && SUPPORT_MAIL_ENABLED) {
            $retVal = mail(SUPPORT_MAIL_ADDR, INSTALLATION_NAME . ": " . $betreff, $content, "from:" . SUPPORT_MAIL_FROM);
        }
        return $retVal;
    }

    private static function isOnline()
    {
        return $_SERVER['REMOTE_ADDR'] != "127.0.0.1";
    }
}
?>