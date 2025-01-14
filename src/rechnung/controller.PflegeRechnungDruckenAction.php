<?php

use horstoeko\zugferd\ZugferdDocument;

class PflegeRechnungDruckenAction implements GetRequestHandler, PostRequestHandler
{
    public function preparePost() { }

    public function executePost() {
        if (! isset($_POST['gemeindeid']) || intval($_POST['gemeindeid']) <= 0) {
            $tplStatus = new HTMLStatus("Bitte wählen Sie eine Gemeinde aus.", HTMLStatus::$STATUS_ERROR, false);
            return $tplStatus;
        }
        
        foreach ($_POST as $key => $val) {
            $_POST[$key] = addslashes(trim($val));
        }
        
        if($_POST['pflegebetrag'] == "") {
            $tplStatus = new HTMLStatus("Bitte geben Sie zuerst einen Rechnungsbetrag ein.", HTMLStatus::$STATUS_ERROR, false);
            return $tplStatus;
        }
        
        $fahrtkosten = doubleval($_POST['fahrtkosten']);
        
        $oRechnung = new PflegeRechnung();
        $oRechnung->setGemeindeID($_POST['gemeindeid']);
        $oRechnung->setDatum($_POST['datum']);
        $oRechnung->setZieldatum($_POST['zahlungsziel']);
        $oRechnung->setNummer($_POST['rechnungsnummer']);
        $oRechnung->setText1($_POST['bemerkung1']);
        $oRechnung->setText2($_POST['bemerkung2']);
        $oRechnung->setFahrtkosten($fahrtkosten);
        $oRechnung->setPflegekosten($_POST['pflegebetrag']);
        $oRechnung->errechneGesamtBetrag(true);
        $oRechnung->setMwStSatz(MWST_SATZ);
        $oRechnung->speichern(true);
        
        // Update der Rechnungsnummer
        $cs = new ConstantSetter();
        $rechnungsNummerOhneJahr = substr($_POST['rechnungsnummer'], 0, strpos($_POST['rechnungsnummer'], "/"));
        $cs->setPflegeRechnungsNummerNaechste($rechnungsNummerOhneJahr + 1);
        
        // Rechnungspositionen speichern
        // Positionen speichern
        $iPosition = 1;
        foreach ($_POST as $key => $val) {
            // 'osition' ist richtig!
            if (strpos($key, "osition_") > 0) {
                $p = new RechnungsPosition();
                $p->setRechnungsID($oRechnung->getID());
                $p->setType(1);
                $p->setText($val);
                $p->setPosition($iPosition ++);
                $p->speichern();
            }
        }
        
        
        // Template öffnen
        // $tplRechnung = new PflegeRechnungOutput("resources/vorlagen/rechnung_pflege", $oRechnung);
        if(isset($_POST['xmlrechnung']) && "on" == $_POST['xmlrechnung']) {
            $tplRechnung = new PflegeRechnungOutput("resources/vorlagen/".RECHNUNG_PREFIX."rechnung_pflege", $oRechnung);
            $templateRechnung = new MSWordOutput("resources/vorlagen/".RECHNUNG_PREFIX."rechnung_pflege");
        } else {
            $tplRechnung = new PflegeRechnungOutput("resources/vorlagen/".RECHNUNG_PREFIX."rechnung_pflege", $oRechnung);
            $templateRechnung = new ZUGFeRDOutput("");
        }
        
        $builder = new PflegeRechnungTemplateBuilder($templateRechnung, $oRechnung);

        $tplRechnung->erstellen($templateRechnung);
        $neuerSpeicherort = $tplRechnung->speichern();
        
        // Zur Rechnung weiterleiten
        $tplStatus = new HTMLRedirect("Rechnung wurde erstellt.", $neuerSpeicherort);
        return $tplStatus;
     }

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
        $htmlStatus = new HTMLRedirect();
        $htmlStatus->setLink("index.php?page=1&do=1");
        return $htmlStatus;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see GetRequestHandler::prepareGet()
     */
    public function prepareGet()
    {
        // TODO Auto-generated method stub
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see GetRequestHandler::executeGet()
     */
    public function executeGet()
    {
       return $this->executePost();
    }
}