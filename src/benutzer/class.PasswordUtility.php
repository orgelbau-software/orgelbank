<?php

class PasswordUtility
{

    /**
     * Erstellen des Passwort Hashes
     * 
     * @param unknown $pPasswordKlartext            
     */
    public static function encrypt($pPasswordKlartext)
    {
        return md5(PASSWORD_SALT.$pPasswordKlartext);
    }

}