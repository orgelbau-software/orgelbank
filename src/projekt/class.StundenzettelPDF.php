<?php
include_once ROOTDIR . 'lib/tFPDF/tfpdf.php';
include_once ROOTDIR . '/lib/tFPDF/tfpdfbookmark.php';

class StundenzettelPDF extends BasisPDF
{

    private $benutzername;

    function __construct()
    {
        parent::__construct();
        $this->fontSizeNormal = 10;
    }

    public function printData(Benutzer $pBenutzer, $pData)
    {
        $counter = 0;
        $letztesJahr = 0;
        $totalStundenDif = 0;
        $starthoehe = 40;
        $this->benutzername = $pBenutzer->getBenutzername();
        $resturlaub = $pBenutzer->getUrlaubstage();
        
        
        foreach ($pData as $currentData) {
            
            if ($letztesJahr == 0 || $currentData->getJahr() != $letztesJahr) {
                $this->AddPage();
                
				// TODO: Hier muss die Option rein ob Stunden genullt werden sollen oder nicht. Elmar will es haben.
				$totalStundenDif = 0;
				
                // Mitarbeiter Daten
                $this->activateFontTextHeadline();
                $this->Cell($this->iRandLinks, $this->cellheight, 'Stundenzettel von ' . $pBenutzer->getVorname() . " " . $pBenutzer->getNachname() . " erstellt am " . date("d.m.Y, H:i") . " Uhr", 0, 1, "L");
                $resturlaub = $pBenutzer->getUrlaubstage();
                
                $this->activateFontNormal();
                $this->Cell($this->iRandLinks, $this->cellheight, 'Urlaub: '.$resturlaub.' Stunden', 0, 1, "L");
                
                $this->SetXY($this->iRandLinks, $starthoehe);
                $rahmen = 0;
                $this->Cell(20, $this->cellheight, 'KW', $rahmen);
                $this->Cell(25, $this->cellheight, 'Wochenanfang', $rahmen);
                $this->Cell(20, $this->cellheight, 'Soll', $rahmen);
                $this->Cell(20, $this->cellheight, 'Ist', $rahmen);
                $this->Cell(20, $this->cellheight, 'Diff', $rahmen);
                $this->Cell(20, $this->cellheight, 'Vorwoche', $rahmen);
                $this->Cell(20, $this->cellheight, 'Gesamt', $rahmen);
                $this->Cell(20, $this->cellheight, 'Urlaub', $rahmen);
                $this->Cell(20, $this->cellheight, 'Resturlaub', $rahmen);
                $this->activateFontNormal();
                $this->SetXY($this->iRandLinks, $starthoehe + $this->cellheight);
                $counter = 0;
            }
            
            // next table row
            $counter ++;
            $this->SetXY($this->iRandLinks, $starthoehe + $this->cellheight * $counter);
            
            $this->Cell(20, $this->cellheight, $currentData->getJahr() . "/" . $currentData->getKalenderWoche());
            $this->Cell(25, $this->cellheight, date("d.m.Y", strtotime($currentData->getWochenStart())));
            $this->Cell(20, $this->cellheight, $currentData->getWochenStundenSoll());
            $this->Cell(20, $this->cellheight, $currentData->getWochenStundenIst());
            $this->Cell(20, $this->cellheight, $currentData->getWochenStundenDif());
            $this->Cell(20, $this->cellheight, $totalStundenDif);
            $totalStundenDif += $currentData->getWochenStundenDif();
            $this->Cell(29, $this->cellheight, $totalStundenDif);
            $this->Cell(20, $this->cellheight, $currentData->getWochenStundenUrlaub());
            $resturlaub -= $currentData->getWochenStundenUrlaub();
            $this->Cell(20, $this->cellheight, $resturlaub);
            
            $letztesJahr = $currentData->getJahr();
        }
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see BasisPDF::getDocumentFilename()
     */
    public function getDocumentFilename()
    {
        return "Stundenzettel-" . date("Ym") . "-" . $this->benutzername . ".pdf";
    }
}