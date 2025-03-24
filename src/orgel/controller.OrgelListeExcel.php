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
        // error_reporting ( null ); // gibt sonst hässliche Fehler im Code
        
        $user = new WebBenutzer();
        $user->validateSession();
        $user = BenutzerUtilities::loadByBenutzername($user->getBenutzername());


        $workbook = new OrgelbankPHPSpreadsheetWriter();
        $workbook->setTempDir(TMPDIR);
        $worksheet = $workbook->addWorksheet("Orgelliste");
        
        $frmFett = "bold";
        
        $cOrgeln = OrgelUtilities::getDruckAnsichtOrgeln();
        Log::debug("orgel anzahl=" . $cOrgeln->getSize());
        if ($cOrgeln->getSize() == 0) {
            $tpl = new HTMLFehlerseite("Ihre Auswahl enth&auml;lt keine Orgeln");
            return $tpl->getTemplate();
        }
        
        $worksheet->write("A1", "Nr.", $frmFett);
        $worksheet->write("B1", "Gemeinde", $frmFett);
        $worksheet->write("C1", "Erbauer", $frmFett);
        $worksheet->write("D1", "Baujahr", $frmFett);
        $worksheet->write("E1", "Letzte Pflege", $frmFett);
        $worksheet->write("F1", "Pflegevertrag", $frmFett);
        $worksheet->write("G1", "Zyklus", $frmFett);
        $worksheet->write("H1", "Manuale", $frmFett);
        $worksheet->write("I1", "Register", $frmFett);
        $worksheet->write("J1", "PLZ", $frmFett);
        $worksheet->write("K1", "Ort", $frmFett);
        $worksheet->write("L1", "Bezirk", $frmFett);
        $worksheet->write("M1", "Funktion", $frmFett);
        $worksheet->write("N1", "Name", $frmFett);
        $worksheet->write("O1", "Telefon", $frmFett);
        $worksheet->write("P1", "KostenHS", $frmFett);
        $worksheet->write("Q1", "KostenTS", $frmFett);
        
        // Temporaer
        $worksheet->write("R1", "GemeindeID", $frmFett);
        $worksheet->write("S1", "OrgelID", $frmFett);
        $worksheet->write("T1", "AnsprechpartnerId", $frmFett);
        
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
                $worksheet->write("F" . $iZeile, $orgel->getPflegevertrag());
                $worksheet->write("G" . $iZeile, $orgel->getZyklusAnzeige());
                $worksheet->write("H" . $iZeile, $orgel->getManual1());
                $worksheet->write("I" . $iZeile, $orgel->getRegisterAnzahl());
                $worksheet->write("J" . $iZeile, $orgel->getGemeindePLZ());
                $worksheet->write("K" . $iZeile, $orgel->getGemeindeOrt());
                $worksheet->write("L" . $iZeile, $orgel->getGemeindeBezirk());
                $worksheet->write("M" . $iZeile, $orgel->getFunktion());
                $worksheet->write("N" . $iZeile, $name);
                $worksheet->write("O" . $iZeile, $orgel->getTelefon());
                if($user->isAdmin()) {
                    $worksheet->write("P" . $iZeile, $orgel->getKostenHauptstimmung());
                    $worksheet->write("Q" . $iZeile, $orgel->getKostenTeilstimmung());
                } else {
                    $worksheet->write("P" . $iZeile, "");
                    $worksheet->write("Q" . $iZeile, "");
                }
                
                $worksheet->write("R" . $iZeile, $orgel->getOrgelId());
                $worksheet->write("S" . $iZeile, $orgel->getGemeindeId());
                $worksheet->write("T" . $iZeile, $orgel->getAnsprechpartnerId());
                
                $iZeile += 1;
            }
        }
        
        $workbook->download("GemeindeList-" . date("Ymd_Hi") . ".xls");
        $workbook->close();
        return new Template("leer.tpl");
    }
}