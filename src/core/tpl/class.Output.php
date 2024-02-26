<?php

/**
 * Ließt Templates ein und kann die Platzhalter in den Templates mit
 * Werten füllen. Gibt das Template wieder aus.
 * Ist im Allgemeinen für die Ausgabe von Ansichten zuständig.
 * 
 * @name class.Output.php
 * @author swatermeyer
 * @version $Revision: 1.3 $
 *
 */
class Output
{

    protected $template = null;

    protected $ORGTEMPLATE = null;

    protected $file = null;

    protected $hasChanged;

    /**
     * Standardkonstruktor
     *
     * @param String $templatePfad
     *            Pfad zum Template
     */
    public function __construct($templatePfad)
    {
        $this->loadNewTemplate($templatePfad);
    }

    protected function aenderePfad($path)
    {
        $path = ROOTDIR . $path;
        if (strpos(" " . $path, "templates")) {
            $path = str_replace("templates/", "web/tpl/", $path);
        }
        return $path;
    }

    /**
     * Ersetzt $platzhalter mit $wert in dem Template.
     * Bei gefundenem und ersetzem
     * Platzhalter wird TRUE zurückgegeben.
     * 
     * @return boolean
     */
    public function replace($platzhalter, $wert)
    {
        $boReturn = false;
        $this->hasChanged = true;
        
        if ($this->template != null) {
            if($wert == "") { 
                $this->template = str_replace($platzhalter, "", $this->template);
            } else {
                $this->template = str_replace($platzhalter, ($wert != "" ? $wert : ""), $this->template);
            }
            
            $boReturn = true;
        }
        return $boReturn;
    }

    /**
     * Gibt das Template als String zurueck, sofern es nicht dem Ursprung entspricht
     * 
     * @return String
     */
    public function getOutput()
    {
        $strReturn = "";
        if ($this->template != null) {
            if ($this->hasChanged || $this->ORGTEMPLATE != $this->template) {
                $this->doInternalFormat();
                $strReturn = $this->template;
            }
        }
        return $strReturn;
    }

    /**
     *
     * @see forceOutput()
     */
    public function __toString()
    {
        return $this->forceOutput();
    }

    /**
     * Gibt das Template als String zurueck und stellt das Ursprungstemplate wieder her
     *
     * @return String
     */
    public function getOutputAndRestore()
    {
        $str = $this->getOutput();
        $this->restoreTemplate();
        return $str;
    }

    /**
     * Gibt das Template zurück, egal ob es NULL oder unverändert ist
     * 
     * @return String
     */
    public function forceOutput()
    {
        $this->doInternalFormat();
        return $this->template;
    }

    /**
     * Stellt das geladene Template wieder aus dem internen Speicher her
     * 
     * @return boolean
     */
    public function restoreTemplate()
    {
        if ($this->template != null) {
            $this->template = $this->ORGTEMPLATE;
            return true;
        } else {
            return false;
        }
    }

    /**
     * Ließt ein neues Template ein
     *
     * @param String $template
     *            Pfadangabe
     */
    public function loadNewTemplate($pfad)
    {
        $pfad = $this->aenderePfad($pfad);
        $this->template = file_get_contents($pfad);
        $this->ORGTEMPLATE = $this->template;
        $this->file = $pfad;
    }

    /**
     * Ersetzt unförmige Zeichen im Template mit den richtigen Zeichen
     */
    private function doInternalFormat()
    {
        $this->template = str_replace("&amp;uuml;", "&uuml;", $this->template);
        $this->template = str_replace("&amp;auml;", "&auml;", $this->template);
        $this->template = str_replace("Ã¶", "&ouml;", $this->template);
        $this->template = str_replace("ÃƒÂ§", "c", $this->template);
        $this->template = str_replace("ÃŸ", "&szlig;", $this->template);
        $this->template = str_replace("Ã¢", "", $this->template);
        $this->template = str_replace("Â„", "", $this->template);
        $this->template = str_replace("Ã¤", "&auml;", $this->template);
        $this->template = str_replace("Ã¼", "&uuml;", $this->template);
    }

    public static function formatString($s)
    {
        $s = str_replace("ß", "ss", $s); // könnte das Copyright "(c)" sein!
        $s = str_replace("ä", "ae;", $s); // könnte das Copyright "(c)" sein!
        $s = str_replace("Ä", "Ae;", $s); // könnte das Copyright "(c)" sein!
        $s = str_replace("ü", "ue", $s); // könnte das Copyright "(c)" sein!
        $s = str_replace("Ü", "Ue", $s); // könnte das Copyright "(c)" sein!
        $s = str_replace("ö", "oe", $s); // könnte das Copyright "(c)" sein!
        $s = str_replace("Ö", "Oe", $s); // könnte das Copyright "(c)" sein!
        return $s;
    }
}
?>