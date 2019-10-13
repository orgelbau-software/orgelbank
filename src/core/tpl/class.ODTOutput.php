<?php

class ODTOutput extends Output
{

    public function loadNewTemplate($pfad)
    {
        $pfad = $this->aenderePfad($pfad);
        $this->template = new Odf($pfad . ".odt");
        $this->ORGTEMPLATE = new Odf($pfad . ".odt");
        $this->file = $pfad;
    }

    public function replace($platzhalter, $wert)
    {
        try {
            $this->template->setVars($platzhalter, $wert);
        } catch (OdfException $e) {
            echo " Variable nicht in Dokument gefunden: '" . $platzhalter . "'<br>";
        }
        // return parent::replace($platzhalter, $wert);
        return true;
    }

    public function save($file)
    {
        return $this->template->saveToDisk($file . ".odt");
    }
}