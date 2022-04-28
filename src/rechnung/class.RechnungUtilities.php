<?php

class RechnungUtilities
{

    public static function zeigeGemeindeRechnungen($iGemeindeID, $strOrderBy = null)
    {
        $sql = "SELECT 
					*
				FROM 
					rechnung_view 
				WHERE g_id = " . $iGemeindeID;
        if ($strOrderBy != null)
            $sql .= $strOrderBy;
        return RechnungUtilities::queryDB($sql);
    }

    public static function renderRechnungsJavaScripts(Output $tpl)
    {
        $a = array(
            ConstantLoader::getRechnungPflegeText(),
            ConstantLoader::getRechnungAuftragText(),
            ConstantLoader::getRechnungAngebotText()
        );
        $b = array(
            "ScriptPflegeText",
            "ScriptAuftragText",
            "ScriptAngebotText"
        );
        
        for ($i = 0; $i <= 2; $i ++) {
            $text = $a[$i];
            $text = str_replace("&lt;", "", $text);
            $text = str_replace("&gt;", "", $text);
            $text = str_replace("\r\n", "\\n", $text);
            $text = str_replace("GEMEINDE", "\"+ titel +\"", $text);
            $tpl->replace($b[$i], $text);
        }
    }

    public static function baueRechnungsAuswahlKopf(Rechnung $oRechnung = null, Output $tplRechnungsKopf = null)
    {
        $tplSelect = new Template("select_option.tpl");
        
        if ($tplRechnungsKopf == null) {
            $tplRechnungsKopf = new Template("rechnung_kopf.tpl");
        }
        
        $strContent = "";
        
        $oZielGemeinde = null;
        if ($oRechnung != null) {
            $oZielGemeinde = new Gemeinde($oRechnung->getGemeindeID());
        } elseif (isset($_GET['gid'])) {
            $oZielGemeinde = new Gemeinde(intval($_GET['gid']));
        }
        
        $standardSortierung = ConstantLoader::getGemeindeListeStandardSortierung();
        if ($standardSortierung == "ort") {
            $c = GemeindeUtilities::getGemeinden(" ORDER BY ad_ort ASC");
        } else {
            $c = GemeindeUtilities::getGemeinden(" ORDER BY g_kirche ASC");
        }
        
        foreach ($c as $oGemeinde) {
            if ($oZielGemeinde != null && $oZielGemeinde->getID() == $oGemeinde->getID())
                $tplSelect->replace("Selected", Constant::$HTML_SELECTED_SELECTED);
            $tplSelect->replace("Selected", "");
            $tplSelect->replace("Value", $oGemeinde->getID());
            if ($standardSortierung == "ort") {
                $tplSelect->replace("Name", $oGemeinde->getOrt() .", ".$oGemeinde->getKirche());
            } else {
                $tplSelect->replace("Name", $oGemeinde->getKirche() . ", " . $oGemeinde->getOrt());
            }
            $strContent .= $tplSelect->getOutputAndRestore();
        }
        
        $tplRechnungsKopf->replace("Gemeinden", $strContent);
        
        if ($oZielGemeinde != null) {
            // Rechnungsanschrift
            $tplRechnungsKopf->replace("Anschrift", $oZielGemeinde->getRAnschrift());
            $tplRechnungsKopf->replace("Gemeinde", $oZielGemeinde->getRGemeinde());
            $tplRechnungsKopf->replace("Strasse", $oZielGemeinde->getRechnungAdresse()
                ->getStrasse());
            $tplRechnungsKopf->replace("Hausnummer", $oZielGemeinde->getRechnungAdresse()
                ->getHausnummer());
            $tplRechnungsKopf->replace("PLZ", $oZielGemeinde->getRechnungAdresse()
                ->getPLZ());
            $tplRechnungsKopf->replace("Ort", $oZielGemeinde->getRechnungAdresse()
                ->getOrt());
            
            // Titel der Kirchengemeinde bilden
            if ($oGemeinde->getKID() == 1) {
                $titel = "evangelischen Kirchengemeinde " . $oZielGemeinde->getRGemeinde();
            } elseif ($oGemeinde->getKID() == 2) {
                $titel = "katholischen Kirchengemeinde " . $oZielGemeinde->getRGemeinde();
            } else {
                $titel = "Gemeinde " . $oZielGemeinde->getRGemeinde();
            }
            
            $tplRechnungsKopf->replace("GemeindeBezeichnung", $titel);
            $tplRechnungsKopf->replace("GID", $oZielGemeinde->getID());
        }
        return $tplRechnungsKopf;
    }

    private static function queryDB($sql)
    {
        $oCol = new DatabaseStorageObjektCollection();
        if (($res = DB::getInstance()->SelectQuery($sql)) !== false) {
            foreach ($res as $rs) {
                $tmp = new RechnungView(); // Objekt wird nur als Container benutzt
                $tmp->setNettoBetrag($rs['r_nettobetrag']);
                $tmp->setBruttoBetrag($rs['r_bruttobetrag']);
                $tmp->setDatum($rs['r_datum']);
                $tmp->setNummer($rs['r_nummer']);
                $tmp->setRechnungsTyp($rs['r_typ']);
                $tmp->setRechnungsTypId($rs['r_typid']);
                $tmp->setID($rs['r_id']);
                $tmp->setPersistent(true);
                $tmp->setChanged(false);
                
                $oCol->add($tmp);
            }
        }
        return $oCol;
    }

    public static function searchRechnungsPositionen($pSuchbegriff)
    {
        $retVal = array();
        $qSuchbegriff = addslashes($pSuchbegriff);
        $sql = "SELECT DISTINCT rpos_text FROM rechnung_position WHERE rpos_text LIKE '%" . $qSuchbegriff . "%' OR rpos_text = '" . $qSuchbegriff . "' LIMIT 0,10;";
        Log::sql($sql);
        if (($res = DB::getInstance()->SelectQuery($sql)) !== false) {
            foreach ($res as $row) {
                $retVal[] = $row['rpos_text'];
            }
        }
        return array_unique($retVal);
    }
}

?>
