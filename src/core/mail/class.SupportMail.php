<?php

class SupportMail
{

    public static function send($betreff, $content)
    {
        return SupportMail::sendSupportMail(SUPPORT_MAIL_ADDR, $betreff, $content);
    }

    public static function sendSupportMail($to, $betreff, $content)
    {
        $retVal = false;
        if (SupportMail::isOnline() && SUPPORT_MAIL_ENABLED) {
            $retVal = mail($to, INSTALLATION_NAME . ": " . $betreff, $content, "from:" . SUPPORT_MAIL_FROM);
        }
        return $retVal;
    }

    private static function isOnline()
    {
        return $_SERVER['REMOTE_ADDR'] != "127.0.0.1";
    }
}
?>