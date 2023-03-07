<?php

class NeueGemeindeAnlegen implements GetRequestHandler
{

    /**
     *
     * {@inheritdoc}
     *
     * @return bool
     */
    public function validateGetRequest()
    {
        return true;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @return HTMLStatus
     */
    public function handleInvalidGet()
    {
        return new HTMLStatus("Alles ok");
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function prepareGet()
    {
        return;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @return Template
     */
    public function executeGet()
    {
        $tplGemeindeDetails = new Template("gemeinde_details_neu.tpl");
        
        // Konfessionsausgabe
        $cKonf = KonfessionUtilities::getKonfessionen("ORDER BY k_name ASC");
        $htmlSelect = new HTMLSelect($cKonf, "getBezeichnung", 0);
        $tplGemeindeDetails->replace("Konfessionen", $htmlSelect->getOutput());
        
        // Andere Daten
        $tplGemeindeDetails->replace("GemeindeID", "");
        $tplGemeindeDetails->replace("Kirche", "");
        $tplGemeindeDetails->replace("Strasse", "");
        $tplGemeindeDetails->replace("Hausnummer", "");
        $tplGemeindeDetails->replace("PLZ", "");
        $tplGemeindeDetails->replace("Ort", "");
        $tplGemeindeDetails->replace("RKirchenamt", "");
        $tplGemeindeDetails->replace("RGemeinde", "");
        $tplGemeindeDetails->replace("RStrasse", "");
        $tplGemeindeDetails->replace("RHausnummer", "");
        $tplGemeindeDetails->replace("RPLZ", "");
        $tplGemeindeDetails->replace("ROrt", "");
        $tplGemeindeDetails->replace("Bezirk", "");
        $tplGemeindeDetails->replace("KM", "");
        $tplGemeindeDetails->replace("Fahrzeit", "");
        
        $selectLand = new HTMLSelectForArray(ConstantLoader::getLaenderAuswahl(), "");
        $tplGemeindeDetails->replace("Land", $selectLand->getOutput());
        $selectLand = new HTMLSelectForArray(ConstantLoader::getLaenderAuswahl(), "");
        $tplGemeindeDetails->replace("RLand", $selectLand->getOutput());
        
        return $tplGemeindeDetails;
    }
}

