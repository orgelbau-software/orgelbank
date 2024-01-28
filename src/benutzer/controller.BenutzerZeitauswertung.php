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
        
        $benutzerStunden = ArbeitswocheUtilities::ladeArbeitswochenByBenutzerId($benutzer->getID(), date("Y"));
        $totalStundenDif = 0;
        
        $tplDS = new BufferedTemplate("benutzer_stunden_liste_ds.tpl", "CSS", "td1", "td2");
        
        foreach ($benutzerStunden as $currentWoche) {            
            $tplDS->replace("KW", $currentWoche->getJahr() . "/" . $currentWoche->getKalenderWoche());
            $tplDS->replace("Woche", date("d.m.Y", strtotime($currentWoche->getWochenStart())));
            $tplDS->replace("Soll", $this->formatStunde($currentWoche->getWochenStundenSoll()));
            $tplDS->replace("Ist", $this->formatStunde($currentWoche->getWochenStundenIst()));
            $tplDS->replace("Diff", $this->formatStunde($currentWoche->getWochenStundenDif()));
            $tplDS->replace("Vorwoche", $this->formatStunde($totalStundenDif.""));
            $totalStundenDif += $currentWoche->getWochenStundenDif();
            $tplDS->replace("Gesamt", $this->formatStunde($totalStundenDif.""));
            
            $tplDS->next();
        }
        
        $tpl->replace("Stunden", $tplDS->getOutput());
        
        
        $benutzerUrlaub = UrlaubsUtilities::getUrlaubsTageProBenutzer($benutzer->getID(), date("Y"));
        
        $tplDS = new BufferedTemplate("benutzer_urlaub_liste_ds.tpl", "CSS", "td1", "td2");
        foreach ($benutzerUrlaub as $currentUrlaubsTag) {
            $tplDS->replace("Verbleibend", $this->formatStunde($currentUrlaubsTag->getVerbleibend()));
            $tplDS->replace("VerbleibendInTagen", number_format(doubleval($currentUrlaubsTag->getVerbleibend() / 8)));
            $tplDS->replace("Summe", $this->formatStunde($currentUrlaubsTag->getSumme()));
            $tplDS->replace("Stunden", $this->formatStunde($currentUrlaubsTag->getstunden()));
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
    
    private function formatStunde($pStunde) {
        
        // TODO: Change for PHP8 to Decimals = ","
        return number_format($pStunde, 2);
    }
}