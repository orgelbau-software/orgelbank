<?php

class SupportMail
{

    /**
     * 
     * @param string $betreff
     * @param string $content 
     * @return bool 
     */
    public static function send($betreff, $content): bool
    {
        return SupportMail::sendSupportMail(SUPPORT_MAIL_ADDR, $betreff, $content);
    }

    /**
     * 
     * @param string $to 
     * @param string $betreff 
     * @param string $content 
     * @return bool 
     */
    public static function sendSupportMail($to, $betreff, $content): bool
    {
        $retVal = false;
        if (SupportMail::isOnline() && SUPPORT_MAIL_ENABLED) {
            $retVal = mail($to, INSTALLATION_NAME . ": " . $betreff, $content, "from:" . SUPPORT_MAIL_FROM);
        }
        return $retVal;
    }

    private static function isOnline(): bool
    {
        return $_SERVER['REMOTE_ADDR'] != "127.0.0.1" && $_SERVER['REMOTE_ADDR'] != "::1";
    }
}
?>