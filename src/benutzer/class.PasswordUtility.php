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
//         echo md5(PASSWORD_SALT.$pPasswordKlartext);
        return md5(PASSWORD_SALT.$pPasswordKlartext);
    }

}