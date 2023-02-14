<?php

class ProjektListeAction implements GetRequestHandler
{
    
	/**
	 * {@inheritdoc}
	 * @return bool
	 */
	public function validateGetRequest() {
        return true;
	}
	
	/**
	 * {@inheritdoc}
	 * @return HTMLStatus
	 */
	public function handleInvalidGet() {
        return new HTMLStatus("Alles ok");
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function prepareGet() {
        return;
	}
	
	/**
	 * {@inheritdoc}
	 * @return Template
	 */
	public function executeGet() {
		// $t = new Template("jquerytest.tpl");
        // $t->anzeigen();
        $tpl = new Template("projekt_anzeigen.tpl");
        $tplDS = new BufferedTemplate("projekt_anzeigen_ds.tpl", "CSS", "td1", "td2");
        $htmlStatus = null;
        
        // GET Handling Start
        $handler = new ProjektListeRequestHandler();
        $handledRequest = $handler->prepareRequest();
        
        // $strWhere .= " ORDER BY r_datum DESC";
        $strWhere = $handledRequest['RESULT'];
        
        $tpl->replace("Dir", $handledRequest->getValueOf("TPLDIR"));
        // GET Handling End
        
        $c = ProjektUtilities::getAnzeigeProjekte($strWhere);
        
        $gLohnkosten = 0;
        $gGesamtkosten = 0;
        $gAngebotspreis = 0;
        $gGewinn = 0;
        
        foreach ($c as $projekt) {
            $tplDS->replace("ProjektID", $projekt->getID());
            $g = new Gemeinde($projekt->getGemeindeID());
            $tplDS->replace("GemeindeBezeichnung", $g->getKirche());
            $tplDS->replace("Bezeichnung", $projekt->getBezeichnung());
            $tplDS->replace("Starttermin", $projekt->getStart(true, true));
            $tplDS->replace("Endtermin", $projekt->getEnde(true, true));
            
            // Berechnungszahlen
            $ha = ProjektAufgabeUtilities::getAlleProjektAufgaben($projekt->getID());
            $aufgabenKosten = ProjektRechnungUtilities::getProjektRechnungssummenByAufgabe($projekt->getID());
            $lohnKosten = ZeiterfassungUtilities::getProjektLohnkostenByHauptaufgabe($projekt->getID());
            $arRK = ReisekostenUtilities::getProjektReisekosten($projekt->getID());
            $rechner = new ProjektKostenRechner();
            $ergebnis = $rechner->calculate($projekt->getAngebotsPreis(), $ha, $aufgabenKosten, $lohnKosten, $arRK);
            
            $tplDS->replace("Lohnkosten", WaehrungUtil::formatDoubleToWaehrung($ergebnis['lohnkosten']));
            $tplDS->replace("Materialkosten", WaehrungUtil::formatDoubleToWaehrung($ergebnis['rechnungen']));
            $tplDS->replace("Reisekosten", WaehrungUtil::formatDoubleToWaehrung($ergebnis['reisekosten']));
            $tplDS->replace("Gesamtkosten", WaehrungUtil::formatDoubleToWaehrung($ergebnis['gesamtkosten']));
            $tplDS->replace("Angebotspreis", WaehrungUtil::formatDoubleToWaehrung($projekt->getAngebotsPreis()));
            $tplDS->replace("GewinnOderVerlust", WaehrungUtil::formatDoubleToWaehrung($ergebnis['gewinn_oder_verlust']));
            $tplDS->next();
            
            $gLohnkosten += $ergebnis['lohnkosten'];
            $gGesamtkosten += $ergebnis['gesamtkosten'];
            $gAngebotspreis += $projekt->getAngebotsPreis();
            $gGewinn += $ergebnis['gewinn_oder_verlust'];
        }
        
        if ($htmlStatus != null)
            $tpl->replace("Statusmeldung", $htmlStatus->getOutput());
        $tpl->replace("Statusmeldung", "");
        
        $tpl->replace("GLohnkosten", WaehrungUtil::formatDoubleToWaehrung($gLohnkosten));
        $tpl->replace("GGesamtkosten", WaehrungUtil::formatDoubleToWaehrung($gGesamtkosten));
        $tpl->replace("GAngebotspreis", WaehrungUtil::formatDoubleToWaehrung($gAngebotspreis));
        $tpl->replace("GGewinn", WaehrungUtil::formatDoubleToWaehrung($gGewinn));
        
        $tpl->replace("Projektliste", $tplDS->getOutput());
        return $tpl;
	}
	
	
}