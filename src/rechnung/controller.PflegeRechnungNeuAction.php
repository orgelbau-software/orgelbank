<?php

class PflegeRechnungNeuAction implements GetRequestHandler
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
        $tplRechnung = new Template("rechnung_pflegerechnung.tpl");
        $tplRechnung->replace("RechnungsKopf", RechnungUtilities::baueRechnungsAuswahlKopf()->getOutput());
        $tplRechnung->replace("disableForm", "");
        $tplRechnung->replace("SubmitValue", "Rechnung erstellen");
        $tplRechnung->replace("XMLRechnungChecked", "");
        $tplRechnung->replace("KopfzeilenZusatz", "");
        $tplRechnung->replace("Betrag", "");
        $tplRechnung->replace("MwSt", "");
        $tplRechnung->replace("BruttoBetrag", "");
        $tplRechnung->replace("Fahrtkosten", "");
        $tplRechnung->replace("Pflegebetrag", "");
        $tplRechnung->replace("MwStSatz", MWST_SATZ * 100);
        $tplRechnung->replace("StandardZahlungsziel", ConstantLoader::getStandardZahlungsziel());
        
        if (isset($_GET['gid'])) {
            $oGemeinde = new Gemeinde($_GET['gid']);
            $r = PflegeRechnungUtilities::getLetztePflegeRechnung($oGemeinde->getID());
            if ($r != null) {
                // Fahrtkosten sind noch bei den ersten Rechnungen leer, Betrag war Brutto
                if ($r->getFahrtkosten() == "") {
                    $tplRechnung->replace("LetzteFahrt", "-,-- Euro");
                    $tplRechnung->replace("LetztePflege", "-,-- Euro");
                }
                
                // Pflegekosten == "", wenn Rechnung vor 6.11.2008 erstellt
                if ($r->getPflegekosten() == "") {
                    $tplRechnung->replace("LetzteFahrt", $r->getFahrtkosten(true));
                    $tplRechnung->replace("LetztePflege", 0);
                }
                
                $tplRechnung->replace("LetzteNetto", $r->getNettoBetrag(true));
                $tplRechnung->replace("LetzteBrutto", $r->getBruttoBetrag(true));
                $tplRechnung->replace("LetzteMwSt", $r->getMwSt(true));
                $tplRechnung->replace("LetzteFahrt", $r->getFahrtkosten(true));
                $tplRechnung->replace("LetztePflege", $r->getPflegekosten(true));
            }
            $tplRechnung->replace("GID", $oGemeinde->getID());
            $tplRechnung->replace("GemeindeBezeichnung", $oGemeinde->getKirche());
        }
        
        $tplRechnung->replace("LetzteNetto", "");
        $tplRechnung->replace("LetzteBrutto", "");
        $tplRechnung->replace("LetzteMwSt", "");
        $tplRechnung->replace("LetzteFahrt", "");
        $tplRechnung->replace("LetztePflege", "");
        
        $tplRechnung->replace("GID", 0);
        $tplRechnung->replace("GemeindeBezeichnung", "GEMEINDE");
        $tplRechnung->replace("Bemerkung1", "");
        $tplRechnung->replace("Bemerkung2", ConstantLoader::getPflegeRechnungSchlusstext());
        
        // Rechnungsdatum & Zahlungsziel
        $iZahlungsZiel = ConstantLoader::getStandardZahlungsziel();
        $tplRechnung->replace("Rechnungsdatum", date("Y-m-d"));
        $tplRechnung->replace("Zahlungsziel", date("Y-m-d", strtotime("+" . $iZahlungsZiel . " day")));
        
        // Rechnungsnummer
        $tplRechnung->replace("Rechnungsjahr", date("y"));
        
        $tplRechnung->replace("Rechnungsnummer", ConstantLoader::getPflegeRechnungsNummerNaechste());
        
        // Standardantworten
        $tplRechnung->replace("Standardposition1", ConstantLoader::getStandardPflegerechnungPos1());
        $tplRechnung->replace("Standardposition2", ConstantLoader::getStandardPflegerechnungPos2());
        $tplRechnung->replace("Standardposition3", ConstantLoader::getStandardPflegerechnungPos3());
        $tplRechnung->replace("Standardposition4", ConstantLoader::getStandardPflegerechnungPos4());
        $tplRechnung->replace("Standardposition5", ConstantLoader::getStandardPflegerechnungPos5());
        $tplRechnung->replace("Standardposition6", ConstantLoader::getStandardPflegerechnungPos6());
        $tplRechnung->replace("Standardposition7", ConstantLoader::getStandardPflegerechnungPos7());
        $tplRechnung->replace("Standardposition8", ConstantLoader::getStandardPflegerechnungPos8());
        $tplRechnung->replace("Standardposition9", ConstantLoader::getStandardPflegerechnungPos9());
        $tplRechnung->replace("Standardposition10", ConstantLoader::getStandardPflegerechnungPos10());
        
        $tplRechnung->replace("Hauptstimmung", "");
        $tplRechnung->replace("Nebenstimmung", "");
        // Rechnung ausgeben
        return $tplRechnung;
    }
}