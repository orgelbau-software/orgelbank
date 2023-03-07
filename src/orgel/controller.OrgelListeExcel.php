<?php

class OrgelListeExcel implements GetRequestHandler
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
        $requestHandler = new OrgelRequestHandler();
        $handledRequest = $requestHandler->prepareOrgelListe();
        // error_reporting ( null ); // gibt sonst h�ssliche Fehler im Code
        
        $workbook = new OrgelbankPHPSpreadsheetWriter();
        $workbook->setTempDir(TMPDIR);
        $worksheet = $workbook->addWorksheet("Orgelliste");
        
        $frmFett = "bold";
        
        $cOrgeln = OrgelUtilities::getDruckAnsichtOrgeln();
        Log::debug("orgel anzahl=" . $cOrgeln->getSize());
        if ($cOrgeln->getSize() == 0) {
            $tpl = new HTMLFehlerseite("Ihre Auswahl enth&auml;lt keine Orgeln");
            $tpl->anzeigen();
            return;
        }
        
        $worksheet->write("A1", "Nr.", $frmFett);
        $worksheet->write("B1", "Gemeinde", $frmFett);
        $worksheet->write("C1", "Erbauer", $frmFett);
        $worksheet->write("D1", "Baujahr", $frmFett);
        $worksheet->write("E1", "Letzte Pflege", $frmFett);
        $worksheet->write("F1", "Manuale", $frmFett);
        $worksheet->write("G1", "Register", $frmFett);
        $worksheet->write("H1", "PLZ", $frmFett);
        $worksheet->write("I1", "Ort", $frmFett);
        $worksheet->write("J1", "Bezirk", $frmFett);
        $worksheet->write("K1", "Funktion", $frmFett);
        $worksheet->write("L1", "Name", $frmFett);
        $worksheet->write("M1", "Telefon", $frmFett);
        $worksheet->write("N1", "KostenHS", $frmFett);
        $worksheet->write("O1", "KostenTS", $frmFett);
        
        // Temporaer
        $worksheet->write("P1", "GemeindeID", $frmFett);
        $worksheet->write("Q1", "OrgelID", $frmFett);
        $worksheet->write("R1", "AnsprechpartnerId", $frmFett);
        
        $iZeile = 2;
        if ($cOrgeln != null) {
            foreach ($cOrgeln as $orgel) {
                $name = $orgel->getNachname();
                if ($orgel->getVorname() != "") {
                    $name .= ", " . $orgel->getVorname();
                }
                $worksheet->write("A" . $iZeile, $iZeile);
                $worksheet->write("B" . $iZeile, $orgel->getGemeindeNamen());
                $worksheet->write("C" . $iZeile, $orgel->getErbauer());
                $worksheet->write("D" . $iZeile, $orgel->getBaujahr());
                $worksheet->write("E" . $iZeile, $orgel->getLetztePflege(true));
                $worksheet->write("F" . $iZeile, $orgel->getManual1());
                $worksheet->write("G" . $iZeile, $orgel->getRegisterAnzahl());
                $worksheet->write("H" . $iZeile, $orgel->getGemeindePLZ());
                $worksheet->write("I" . $iZeile, $orgel->getGemeindeOrt());
                $worksheet->write("J" . $iZeile, $orgel->getGemeindeBezirk());
                $worksheet->write("K" . $iZeile, $orgel->getFunktion());
                $worksheet->write("L" . $iZeile, $name);
                $worksheet->write("M" . $iZeile, $orgel->getTelefon());
                
                $worksheet->write("N" . $iZeile, $orgel->getKostenHauptstimmung());
                $worksheet->write("O" . $iZeile, $orgel->getKostenTeilstimmung());
                
                $worksheet->write("P" . $iZeile, $orgel->getOrgelId());
                $worksheet->write("Q" . $iZeile, $orgel->getGemeindeId());
                $worksheet->write("R" . $iZeile, $orgel->getAnsprechpartnerId());
                
                $iZeile += 1;
            }
        }
        
        $workbook->download("GemeindeList-" . date("Ymd_Hi") . ".xls");
        $workbook->close();
        return new Template("leer.tpl");
    }
}