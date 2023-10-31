<?php

class BenutzerZeitauswertung implements GetRequestHandler
{

    /**
     *
     * {@inheritdoc}
     *
     * @see GetRequestHandler::validateGetRequest()
     */
    public function validateGetRequest()
    {
        return true;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see GetRequestHandler::handleInvalidGet()
     */
    public function handleInvalidGet()
    {
        return null;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see GetRequestHandler::prepareGet()
     */
    public function prepareGet()
    {
        return;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see GetRequestHandler::executeGet()
     */
    public function executeGet()
    {
        global $webUser;
        
        $tpl = new Template("benutzer_zeiten_auswertung.tpl");
        
        $benutzer = new Benutzer($webUser->getID());
        $eintritt = strtotime($benutzer->getEintrittsDatum());
        $heute = time();
        $dauer = $heute - $eintritt;
        $dauer = $dauer / (60 * 60 * 24 * 30 * 12);
        $dauer = round($dauer, 1);
        
        $tpl->replace("Eintrittsdatum", $benutzer->getEintrittsDatum(true));
        $tpl->replace("Dauer", $dauer);
        
        $ueberstunden = BenutzerUtilities::berechneUeberstunden($benutzer->getID());
        $ueberstunden = $ueberstunden == "" ? 0 : $ueberstunden;
        
        $benutzerUrlaub = UrlaubsUtilities::getUrlaubsTageProBenutzer($benutzer->getID(), date("Y"));
        
        $tplDS = new BufferedTemplate("benutzer_urlaub_liste_ds.tpl", "CSS", "td1", "td2");
        foreach ($benutzerUrlaub as $currentUrlaubsTag) {
            $tplDS->replace("Verbleibend", $currentUrlaubsTag->getVerbleibend());
            $tplDS->replace("Summe", $currentUrlaubsTag->getSumme());
            $tplDS->replace("Resturlaub", $currentUrlaubsTag->getResturlaub());
            $tplDS->replace("DatumVon", $currentUrlaubsTag->getDatumVon(true));
            $tplDS->replace("DatumBis", $currentUrlaubsTag->getDatumBis(true));
            $tplDS->replace("Status", $currentUrlaubsTag->getStatus());
            $tplDS->replace("Bemerkung", $currentUrlaubsTag->getBemerkung());
            $tplDS->next();
        }
        
        $tpl->replace("Urlaubstage", $tplDS->getOutput());
        return $tpl;
    }
}