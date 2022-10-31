<?php

class EinstellungController
{

    public static function zeigeRechnungsEinstellungen()
    {
        if ($_POST) {
            Utilities::escapePost();
            $c = new ConstantSetter();
            
            $c->setStandardZahlungsziel($_POST['zahlungsziel']);
            
            $c->setStandardPflegerechnungPos1($_POST['standardposition1']);
            $c->setStandardPflegerechnungPos2($_POST['standardposition2']);
            $c->setStandardPflegerechnungPos3($_POST['standardposition3']);
            $c->setStandardPflegerechnungPos4($_POST['standardposition4']);
            $c->setStandardPflegerechnungPos5($_POST['standardposition5']);
            $c->setStandardPflegerechnungPos6($_POST['standardposition6']);
            $c->setStandardPflegerechnungPos7($_POST['standardposition7']);
            $c->setStandardPflegerechnungPos8($_POST['standardposition8']);
            $c->setStandardPflegerechnungPos9($_POST['standardposition9']);
            $c->setStandardPflegerechnungPos10($_POST['standardposition10']);
            
            $c->setAbschlag1Text($_POST['abschlag1text']);
            $c->setAbschlag2Text($_POST['abschlag2text']);
            $c->setAbschlag3Text($_POST['abschlag3text']);
            $c->setAbschlag1Prozent($_POST['abschlag1prozent']);
            $c->setAbschlag2Prozent($_POST['abschlag2prozent']);
            $c->setAbschlag3Prozent($_POST['abschlag3prozent']);
            
            $c->setRechnungAngebotText($_POST['angebottext']);
            $c->setRechnungAuftragText($_POST['auftragtext']);
            $c->setRechnungPflegeText($_POST['pflegetext']);
            
            $htmlRedirect = new HTMLRedirect("Einstellungen wurden gespeichert.", "index.php?page=7&do=120", 1);
            $htmlRedirect->anzeigen();
        } else {
            
            $zahlungsziele = ConstantLoader::getRechnungStandardZahlungsziele();
            $zahlungsziele = explode(",", $zahlungsziele);
            
            $tplEinstellung = new Template("einstellung_rechnung.tpl");
            $tplSelect = new Template("select_option.tpl");
            $strTmp = "";
            
            $iZahlungszielTage = ConstantLoader::getStandardZahlungsziel();
            
            foreach ($zahlungsziele as $val) {
                $tplSelect->replace("Value", $val);
                $tplSelect->replace("Name", $val . " Tage");
                if ($iZahlungszielTage == $val)
                    $tplSelect->replace("Selected", Constant::$HTML_SELECTED_SELECTED);
                $tplSelect->replace("Selected", "");
                $strTmp .= $tplSelect->getOutputAndRestore();
            }
            $tplEinstellung->replace("Zahlungsziele", $strTmp);
            
            $tplEinstellung->replace("Standardposition1", ConstantLoader::getStandardPflegerechnungPos1());
            $tplEinstellung->replace("Standardposition2", ConstantLoader::getStandardPflegerechnungPos2());
            $tplEinstellung->replace("Standardposition3", ConstantLoader::getStandardPflegerechnungPos3());
            $tplEinstellung->replace("Standardposition4", ConstantLoader::getStandardPflegerechnungPos4());
            $tplEinstellung->replace("Standardposition5", ConstantLoader::getStandardPflegerechnungPos5());
            $tplEinstellung->replace("Standardposition6", ConstantLoader::getStandardPflegerechnungPos6());
            $tplEinstellung->replace("Standardposition7", ConstantLoader::getStandardPflegerechnungPos7());
            $tplEinstellung->replace("Standardposition8", ConstantLoader::getStandardPflegerechnungPos8());
            $tplEinstellung->replace("Standardposition9", ConstantLoader::getStandardPflegerechnungPos9());
            $tplEinstellung->replace("Standardposition10", ConstantLoader::getStandardPflegerechnungPos10());
            
            $tplEinstellung->replace("Abschlag1Text", ConstantLoader::getRechnungAbschlag1Text());
            $tplEinstellung->replace("Abschlag2Text", ConstantLoader::getRechnungAbschlag2Text());
            $tplEinstellung->replace("Abschlag3Text", ConstantLoader::getRechnungAbschlag3Text());
            
            $tplEinstellung->replace("Abschlag1Prozent", ConstantLoader::getRechnungAbschlag1Prozent());
            $tplEinstellung->replace("Abschlag2Prozent", ConstantLoader::getRechnungAbschlag2Prozent());
            $tplEinstellung->replace("Abschlag3Prozent", ConstantLoader::getRechnungAbschlag3Prozent());
            
            $tplEinstellung->replace("AuftragText", ConstantLoader::getRechnungAuftragText());
            $tplEinstellung->replace("PflegeText", ConstantLoader::getRechnungPflegeText());
            $tplEinstellung->replace("AngebotText", ConstantLoader::getRechnungAngebotText());
            
            $tplEinstellung->anzeigen();
        }
    }

    public static function zeigeFirmenDaten()
    {
        $tpl = new Template("einstellung_firmendaten_aendern.tpl");
        $firma = new Ansprechpartner(1);
        if ($firma == null) {
            $firma = new Ansprechpartner();
        }
        
        $tplAnredeArtenDL = new HTMLDatalistForArray(Constant::getAnredeAuswahl());
        $tpl->replace("AnredeDatalist", $tplAnredeArtenDL->getOutput());
        $tpl->replace("Anrede", $firma->getAnrede());
        
        $tplTitelArtenDL = new HTMLDatalistForArray(Constant::getTitelAuswahl());
        $tpl->replace("TitelDatalist", $tplTitelArtenDL->getOutput());
        $tpl->replace("Titel", $firma->getTitel());
        
        $tpl->replace("AID", $firma->getID());
        $tpl->replace("AnredeHerr", "");
        $tpl->replace("AnredeFrau", "");
        $tpl->replace("AnredeKeine", "");
        
        $tpl->replace("Funktion", $firma->getFunktion());
        
        $tpl->replace("Vorname", $firma->getVorname());
        $tpl->replace("Nachname", $firma->getNachname());
        $tpl->replace("Strasse", $firma->getAdresse()
            ->getStrasse());
        $tpl->replace("Hsnr", $firma->getAdresse()
            ->getHausnummer());
        $tpl->replace("PLZ", $firma->getAdresse()
            ->getPLZ());
        $tpl->replace("Ort", $firma->getAdresse()
            ->getOrt());
        $tpl->replace("Telefon", $firma->getTelefon());
        $tpl->replace("Fax", $firma->getFax());
        $tpl->replace("Mobil", $firma->getMobil());
        $tpl->replace("EMail", $firma->getEmail());
        $tpl->replace("Bemerkung", $firma->getBemerkung());
        $tpl->replace("Andere", $firma->getAndere());
        
        $tpl->anzeigen();
    }

    public static function speichereFirmenDaten()
    {
        AnsprechpartnerController::speichereAnsprechpartner();
    }

    public static function zeigeOptions()
    {
        $tpl = new Template("einstellung_allgemein.tpl");
        
        $tpl->replace("Untertext1", ConstantLoader::getPDFUntertext1());
        $tpl->replace("Untertext2", ConstantLoader::getPDFUntertext2());
        $tpl->anzeigen();
    }

    public static function saveOptions()
    {
        if (! $_POST)
            return;
        
        $saver = new ConstantSetter();
        
        $saver->setPDFUntertext1($_POST['untertext1']);
        $saver->setPDFUntertext2($_POST['untertext2']);
        
        $status = new HTMLRedirect("Einstellungen gespeichert", "index.php?page=7&do=123");
        $status->anzeigen();
    }

    public static function showOptionMeta()
    {
        RequestHandler::handle(new OptionValueAction());
    }

    public static function showBenutzerVerlaufUebersicht()
    {
        RequestHandler::handle(new BenutzerVerlaufUebersichtAction());
    }
}
?>