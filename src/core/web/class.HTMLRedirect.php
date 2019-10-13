<?php

class HTMLRedirect
{

    private $tpl;

    private $str = "status_zurueck_u_redirect.tpl";

    private $nachricht;

    private $link;

    private $sekunden;

    public function __construct($nachricht = "", $zieladresse = "", $sekunden = "default")
    {
        if ($sekunden == "default")
            $sekunden = ConstantLoader::getDefaultRedirectSecondsTrue();
        
        $this->sekunden = $sekunden;
        $this->link = $zieladresse;
        $this->nachricht = $nachricht;
        $this->tpl = new Template($this->str);
    }

    public function setTemplate(Template $t)
    {
        $this->tpl = $t;
    }

    private function init()
    {
        $this->tpl->replace("Text", $this->nachricht);
        $this->tpl->replace("Sekunden", $this->sekunden);
        $this->tpl->replace("Ziel", $this->link);
    }

    public function anzeigen()
    {
        $this->init();
        echo $this->tpl->forceOutput();
    }

    public function getOutput()
    {
        $this->init();
        return $this->out;
    }

    public function getLink()
    {
        return $this->link;
    }

    public function getNachricht()
    {
        return $this->nachricht;
    }

    public function getSekunden()
    {
        return $this->sekunden;
    }

    public function setLink($link)
    {
        $this->link = $link;
    }

    public function setNachricht($nachricht)
    {
        $this->nachricht = $nachricht;
    }

    public function setSekunden($sekunden)
    {
        $this->sekunden = $sekunden;
    }
}

?>
