<?php

class Utilities
{

    /**
     * Ersetzt Umlaute und Sonderzeichen
     *
     * Wird u.a. in den Rechnungs-Klassen zur Generierung des Speicherorts verwendet.
     *
     * @param String $zielpfad            
     * @return String mit ersetzten Sonderzeichen
     */
    public static function ersetzeZeichen($zielpfad)
    {
        $zielpfad = str_replace("ä", "ae", $zielpfad);
        $zielpfad = str_replace("ü", "ue", $zielpfad);
        $zielpfad = str_replace("ö", "oe", $zielpfad);
        $zielpfad = str_replace("ß", "sz", $zielpfad);
        $zielpfad = str_replace("Ä", "Ae", $zielpfad);
        $zielpfad = str_replace("Ü", "Ue", $zielpfad);
        $zielpfad = str_replace("Ö", "Oe", $zielpfad);
        $zielpfad = str_replace(" ", "_", $zielpfad);
		$zielpfad = str_replace(",", "_", $zielpfad);
        return $zielpfad;
    }

    public static function escapePost()
    {
        foreach ($_POST as $key => $val) {
            $val = trim($val);
            $val = addslashes($val);
            // $val = htmlspecialchars($val);
            // echo " htmlspecial: " .$val;
            $_POST[$key] = $val;
        }
    }
}

?>
