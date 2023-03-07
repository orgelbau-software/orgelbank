<?php

class GemeindeListeExcel implements GetRequestHandler
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
        $requestHandler = new GemeindeRequestHandler();
        $handledRequest = $requestHandler->prepareGemeindeListRequest();
        error_reporting(E_ALL); // gibt sonst haessliche Fehler im Code
        
        $workbook = new OrgelbankPHPSpreadsheetWriter();
        $workbook->setTempDir(TMPDIR);
        $worksheet = $workbook->addWorksheet();
        
        $frmFett = "bold";
        $cGemeinden = GemeindeUtilities::getDruckAnsichtGemeinden();
        Log::debug("count gemeinden=" . $cGemeinden->getSize());
        
        if ($cGemeinden->getSize() == 0) {
            $tpl = new HTMLFehlerseite("Ihre Auswahl enth&auml;lt keine Gemeinden");
            $tpl->anzeigen();
            return;
        }
        
        $konfession = KonfessionUtilities::getKonfessionenAsArray();
        
        $worksheet->write("A1", "Nr.", $frmFett);
        $worksheet->write("B1", "Gemeinde", $frmFett);
        $worksheet->write("C1", "PLZ", $frmFett);
        $worksheet->write("D1", "Ort", $frmFett);
        $worksheet->write("E1", "Konfession", $frmFett);
        $worksheet->write("F1", "Bezirk", $frmFett);
        $worksheet->write("G1", "Funktion", $frmFett);
        $worksheet->write("H1", "Nachname", $frmFett);
        $worksheet->write("I1", "Vorname", $frmFett);
        $worksheet->write("J1", "Telefon", $frmFett);
        
        $iZeile = 2;
        foreach ($cGemeinden as $gemeinde) {
            $worksheet->write("A" . $iZeile, $iZeile);
            $worksheet->write("B" . $iZeile, $gemeinde->getKirche());
            $worksheet->write("C" . $iZeile, $gemeinde->getGemeindePLZ());
            $worksheet->write("D" . $iZeile, $gemeinde->getGemeindeOrt());
            $worksheet->write("E" . $iZeile, $konfession[$gemeinde->getKID()]);
            $worksheet->write("F" . $iZeile, $gemeinde->getGemeindeBezirk());
            $worksheet->write("G" . $iZeile, $gemeinde->getFunktion());
            $worksheet->write("H" . $iZeile, $gemeinde->getNachname());
            $worksheet->write("I" . $iZeile, $gemeinde->getVorname());
            $worksheet->write("J" . $iZeile, $gemeinde->getTelefon());
            $iZeile ++;
            Log::debug($iZeile);
        }
        
        $workbook->download("GemeindeList-" . date("Ymd_Hi") . ".xlsx");
        $workbook->close();
        return new Template("leer.tpl");
    }
}