<?php

class Template extends Output
{

    protected $pfad = "web/tpl/";

    protected $perceive = null;

    public function __construct($template)
    {
        $p = $this->pfad . $template;
        parent::__construct($p);
    }

    /**
     * Ersetzt <code>$platzhalter</code> im Template mit <code>$wert</code>
     * Intern wird <code>$platzhalter</code> um führende Kommentarzeichen
     * <code><!--</code> und abschließende Kommentarzeichen <code>--></code>
     * ergänzt
     *
     * @param string $platzhalter            
     * @param mixed $wert            
     */
    public function replace($platzhalter, $wert)
    {
        $platzhalter1 = "<!--" . $platzhalter . "-->";
        parent::replace($platzhalter1, $wert);
        $platzhalter2 = "<!-- " . $platzhalter . " -->";
        parent::replace($platzhalter2, $wert);
    }

    public function plainReplace($platzhalter, $wert)
    {
        parent::replace($platzhalter, $wert);
    }

    public function anzeigen()
    {
        echo $this->forceOutput();
    }

    public function reset()
    {
        parent::restoreTemplate();
    }

    /**
     * Merkt sich alle Änderungen am Template, die nach diesem Aufruf gemacht werden.
     * Über "forget" können die Änderungen wieder "vergessen", also verworfen werden
     * "Perceive" heisst "sich etw. Merken"
     */
    public function perceive()
    {
        $this->perceive = $this->template;
    }

    /**
     * Vergisst alle Änderungen, die seit dem letzten Aufruf von "perceive" gemacht wurden.
     */
    public function forget()
    {
        if ($this->perceive != null)
            $this->template = $this->perceive;
    }

    public function restoreTemplate()
    {
        $this->perceive = null;
        return parent::restoreTemplate();
    }
}
?>
