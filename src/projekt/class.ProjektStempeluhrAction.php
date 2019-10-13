<?php

class ProjektStempeluhrAction implements GetRequestHandler

{
    /**
     * {@inheritDoc}
     * @see GetRequestHandler::validateGetRequest()
     */
    public function validateGetRequest()
    {
        return true;
    }

    /**
     * {@inheritDoc}
     * @see GetRequestHandler::handleInvalidGet()
     */
    public function handleInvalidGet()
    {
        // TODO Auto-generated method stub
        
    }

    /**
     * {@inheritDoc}
     * @see GetRequestHandler::prepareGet()
     */
    public function prepareGet()
    {
        // TODO Auto-generated method stub
        
    }

    /**
     * {@inheritDoc}
     * @see GetRequestHandler::executeGet()
     */
    public function executeGet()
    {
        $tpl = new Template("projekt_stempeluhr.tpl");
        
        $all = StempeluhrUtilities::ladeAlleStempeluhrEintraege();
        
        $tplDS = new BufferedTemplate("projekt_stempeluhr_liste_ds.tpl", "css", "td1", "td2");
        foreach($all as $eintrag) {
            $tplDS->replace("Mitarbeiter", $eintrag->getBenutzername());
             $tplDS->replace("Datum", $eintrag->getZeit());
             $tplDS->replace("Projekt", $eintrag->getProjektBezeichnung());
             $tplDS->replace("Status", $eintrag->getStatus());
             $tplDS->replace("Aufgabe", $eintrag->getAufgabeBezeichnung());
             $tplDS->replace("Unteraufgabe", $eintrag->getUnteraufgabeBezeichnung());
             $tplDS->replace("Dauer", $eintrag->getDauer());
            $tplDS->next();
        }
        $tpl->replace("Content", $tplDS->getOutput());
        
        return $tpl;
        
    }

}
    