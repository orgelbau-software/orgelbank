<?php

class NeueOrgelAnlegen implements GetRequestHandler
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
        $tplOrgelDetails = new Template("orgel_details_neu.tpl");
        
        $gid = 0;
        if (isset($_GET['gid'])) {
            $gid = intval($_GET['gid']);
        }
        
        $standardSortierung = ConstantLoader::getGemeindeListeStandardSortierung();
        if ($standardSortierung == "ort") {
            $htmlGemeinden = new HTMLSelectForKey(GemeindeUtilities::getGemeinden(" ORDER BY ad_ort"), "getGemeindeId", "getOrt,getKirche", 0);
        } else {
            $htmlGemeinden = new HTMLSelectForKey(GemeindeUtilities::getGemeinden(" ORDER BY g_kirche"), "getGemeindeId", "getKirche,getOrt", 0);
        }
        $htmlGemeinden->setValueMaxLength(56);
        $tplOrgelDetails->replace("Gemeinden", $htmlGemeinden->getOutput());
        
        $htmlSelectStatus = new HTMLSelectForArray(Constant::getOrgelStatus(), 0);
        $tplOrgelDetails->replace("Orgelstatus", $htmlSelectStatus->getOutput());
        
        $htmlSelectWinlade = new HTMLSelectForArray(Constant::getWindladen(), 0);
        $tplOrgelDetails->replace("Windlade", $htmlSelectWinlade->getOutput());
        
        $htmlSelectTraktur = new HTMLSelectForArray(Constant::getSpieltrakturen(), 0);
        $tplOrgelDetails->replace("Spieltraktur", $htmlSelectTraktur->getOutput());
        
        $htmlSelectKoppel = new HTMLSelectForArray(Constant::getKoppeln(), 0);
        $tplOrgelDetails->replace("Koppel", $htmlSelectKoppel->getOutput());
        
        $htmlSelectRegister = new HTMLSelectForArray(Constant::getRegisterTrakturen(), 0);
        $tplOrgelDetails->replace("Registertraktur", $htmlSelectRegister->getOutput());
        
        $htmlZyklusSelect = new HTMLSelectForArray(Constant::getZyklus(), 0);
        $tplOrgelDetails->replace("ZyklusSelect", $htmlZyklusSelect->getOutput());
        
        // Kosten Haupt und Teilstimmung
        $tplOrgelDetails->replace("KostenHauptstimmung", "");
        $tplOrgelDetails->replace("KostenTeilstimmung", "");
        $tplOrgelDetails->replace("Stimmton", "");
        $tplOrgelDetails->replace("OID", "");
        
        $htmlIntervalHauptstimmung = new HTMLSelectForArray(Constant::getIntervallHauptstimmung());
        $tplOrgelDetails->replace("IntervallHaupstimmungSelect", $htmlIntervalHauptstimmung->getOutput());
        
        // Pflegevertrag
        foreach (Constant::getPflegevertrag() as $zahl => $text) {
            $tplOrgelDetails->replace("SelectedPflege" . $zahl, "");
        }
        
        $tplOrgelDetails->replace("StimmungNach", "");
        return $tplOrgelDetails;
    }
}