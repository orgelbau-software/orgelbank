<?php

abstract class OrgelbankBasisPDF extends tFPDFWithBookmark
{

    protected $iRandLinks = 15;

    protected $iTrennstrichLaenge = 185;

    protected $arHeaderMeta;

    protected $cellheight = 4;

    protected $mDBInstance;
    
    protected $iDefaultCellSize = 70;
    
    protected $mVariante;
    
    protected $keywords;

    /**
     *
     * @var string
     */
    protected $font = "DejaVu";

    protected $fontBold = "DejaVu B";

    /**
     * Wether to use UTF-8 or not.
     *
     * @var boolean
     */
    protected $utf8 = true;

    function __construct($pVariante = "")
    {
        parent::__construct();
        $this->mDBInstance = DB::getInstance();
        
        $this->AddFont($this->font, '', 'DejaVuSansCondensed.ttf', true);
        $this->AddFont($this->getFontBold(), '', 'DejaVuSansCondensed-Bold.ttf', true);
        
        $this->mVariante = $pVariante;
    }

    function Header()
    {
        $cellsize = 70;
        
        // Rand setzen
        $this->SetMargins($this->getRandLinks(), 10, 10);
        $this->ln(1);
        
        $this->activateFontNormal();
        

        $this->Cell($cellsize, $this->cellheight, $this->arHeaderMeta->getValueOf(0), 0, 0, "C");
        $this->Cell($cellsize, $this->cellheight, $this->arHeaderMeta->getValueOf(1), 0, 0, "C");
        $this->ln($this->cellheight);
        $this->Cell($cellsize, $this->cellheight, $this->arHeaderMeta->getValueOf(2), 0, 0, "C");
        $this->Cell($cellsize, $this->cellheight, $this->arHeaderMeta->getValueOf(3), 0, 0, "C");
        $this->ln($this->cellheight);
        $this->Cell($cellsize, $this->cellheight, $this->arHeaderMeta->getValueOf(4), 0, 0, "C");
        $this->Cell($cellsize, $this->cellheight, $this->arHeaderMeta->getValueOf(5), 0, 0, "C");
        $this->ln(8);
        
        $this->zeichneTrennstrich();
    }

    /**
     */
    protected function activateFontNormal()
    {
        $this->SetFont($this->font, '', 10);
    }

    /**
     */
    protected function activateFontTextHeadlineSmall()
    {
        $this->SetFont($this->getFontBold(), '', 8);
    }

    /**
     */
    protected function activateFontTextHeadline()
    {
        $this->SetFont($this->getFontBold(), '', 11);
    }

    /**
     */
    protected function activateFontBold()
    {
        $this->SetFont($this->getFontBold(), '', 10);
    }
    
    protected function activateFontColorRed() 
    {
        $this->SetTextColor(255, 0, 0);
    }
    
    protected function activateFontColorBlue() 
    {
        $this->SetTextColor(0, 0, 255);
    }
    
    protected function activateFontColorBlack() 
    {
        $this->SetTextColor(0, 0, 0);
    }
    
    protected function activateFontColorGreen() 
    {
        $this->SetTextColor(0, 200, 0);
    }
    
    
    protected function getFontBold()
    {
        return $this->fontBold;
    }

    function Footer()
    {
        $this->SetSubject("Deckblatt", $this->utf8);
        $this->SetTitle("Deckblatt", $this->utf8);
        $this->SetCreator("Pflegesoftware von watermeyer IT", $this->utf8);
    }
    
    protected function addBezirkUndOrgelID($pOrgelID, $pBezirk) 
    {
        $tmpX = $this->getX();
        $tmpY = $this->getY();
        
        $this->SetXY(170, 9);
        $this->activateFontNormal();
        $this->activateFontColorRed();
        $this->Cell($this->getDefaultCellSize(), 8, "Bezirk: " . $pBezirk, 0, 0, "L");
        $this->SetXY(170, 16);
        $this->activateFontColorBlue();
        $this->Cell($this->getDefaultCellSize(), 10, "Orgel:  " . $pOrgelID, 0, 0, "L");
        $this->activateFontNormal();
        $this->activateFontColorBlack();
        
        $this->setXY($tmpX, $tmpY);
    }
        
      

    /**
     * Zeichnet einen Trennstrich ins aktuelle Dokument an die aktuelle Stelle des Cursors.
     *
     * Die Länge kann in der Objektvariablen bestimmt werden
     */
    protected function zeichneTrennstrich()
    {
        $this->Cell($this->getTrennstrichLaenge(), 0, '', 1, 0, "C");
        $this->ln(3);
    }

    protected function getKeywords()
    {
        // return $this->metadata['Keywords'];
        return $this->keywords;
    }

    public function Output($dest = '', $name = '', $utf8 = true)
    {
        parent::Output($dest, $name, $this->utf8);
    }
    
    protected function getRandLinks()
    {
        return $this->iRandLinks;
    }
    
    protected function setRandLinks($iRandLinks)
    {
        $this->iRandLinks = $iRandLinks;
    }
    
    protected function getDBInstance()
    {
        return $this->mDBInstance;
    }
    
    protected function getTrennstrichLaenge()
    {
        return $this->iTrennstrichLaenge;
    }
    
    protected function getDefaultCellSize()
    {
        return $this->iDefaultCellSize;
    }
    
    /**
     * Neue Funktion zum ausprobieren
     *
     * @param Orgel $oOrgel            
     */
    protected function addDisposition(Orgel $oOrgel)
    {
        $oid = $oOrgel->getID();
        $strGroesstesManual = 0;
        $iRegisterAnzahl = 0;
        $strPedal = 1;
        
        RegisterUtilities::getDispositionAsArray($oOrgel->getID());
        
        $iGroesstesManual = 0;
        
        // Manual 1
        $m = $this->handleManual($oid, 1);
        $manual1name = $m[0];
        $manual1fuss = $m[1];
        $iGroesstesManual = ($iGroesstesManual < $m[2] ? $m[2] : $iGroesstesManual);
        if ($m[2] > 0) {
            $strGroesstesManual = "I";
        }
        
        // Manual 2
        $m = $this->handleManual($oid, 2);
        $manual2name = $m[0];
        $manual2fuss = $m[1];
        $iGroesstesManual = ($iGroesstesManual < $m[2] ? $m[2] : $iGroesstesManual);
        if ($m[2] > 0) {
            $strGroesstesManual = "II";
        }
        
        // Manual 3
        $m = $this->handleManual($oid, 3);
        $manual3name = $m[0];
        $manual3fuss = $m[1];
        $iGroesstesManual = ($iGroesstesManual < $m[2] ? $m[2] : $iGroesstesManual);
        if ($m[2] > 0) {
            $strGroesstesManual = "III";
        }
        
        // Manual 4
        $m = $this->handleManual($oid, 4);
        $manual4name = $m[0];
        $manual4fuss = $m[1];
        $iGroesstesManual = ($iGroesstesManual < $m[2] ? $m[2] : $iGroesstesManual);
        if ($m[2] > 0) {
            $strGroesstesManual = "IV";
        }
        
        // Manual 5
        $m = $this->handleManual($oid, 5);
        $manual5name = $m[0];
        $manual5fuss = $m[1];
        $iGroesstesManual = ($iGroesstesManual < $m[2] ? $m[2] : $iGroesstesManual);
        if ($m[2] > 0) {
            $strGroesstesManual = "V";
        }
        
        // Manual 6
        $m = $this->handleManual($oid, 6);
        $manual6name = $m[0];
        $manual6fuss = $m[1];
        $iGroesstesManual = ($iGroesstesManual < $m[2] ? $m[2] : $iGroesstesManual);
        if ($m[2] > 0) {
            $strPedal = "/Pedal";
        }
        
        $iRegisterAnzahl = $oOrgel->getRegisterAnzahl();
        
        // Dipositionsüberschrift
        $cellsize = $this->getDefaultCellSize();
        
        $this->activateFontBold();
        $this->Cell($cellsize, $this->cellheight, 'Disposition: ' . $strGroesstesManual . $strPedal . " " . $iRegisterAnzahl, 0, 0, "L");
        $this->SetFont($this->font, '', 7);
        $this->ln(5);
        
        // Tabelle erzeugen
        $iSizeRegisterBez = 27;
        
        $this->activateFontBold();
        if (count($manual6name) > 0) {
            $this->Cell($iSizeRegisterBez, $this->cellheight, 'Pedal', 0, 0);
            $this->Cell(10, $this->cellheight, '', 0, 0);
        }
        if (count($manual1name) > 0) {
            $this->Cell($iSizeRegisterBez, $this->cellheight, 'Manual I', 0, 0);
            $this->Cell(10, $this->cellheight, '', 0, 0);
        }
        if (count($manual2name) > 0) {
            $this->Cell($iSizeRegisterBez, $this->cellheight, 'Manual II', 0, 0);
            $this->Cell(10, $this->cellheight, '', 0, 0);
        }
        if (count($manual3name) > 0) {
            $this->Cell($iSizeRegisterBez, $this->cellheight, 'Manual III', 0, 0);
            $this->Cell(10, $this->cellheight, '', 0, 0);
        }
        if (count($manual4name) > 0) {
            $this->Cell($iSizeRegisterBez, $this->cellheight, 'Manual IV', 0, 0);
            $this->Cell(10, $this->cellheight, '', 0, 0);
        }
        $this->Cell(10, $this->cellheight, '', 0, 1);
        
        // Winddruck und Manualgröße ausgeben
        $sql = "SELECT o_m1wd, o_m2wd, o_m3wd, o_m4wd, o_m5wd, o_m6wd,
						o_m1groesse, o_m2groesse, o_m3groesse, o_m4groesse,
						o_m5groesse, o_m6groesse
				FROM orgel
				WHERE o_id = '" . $oid . "'";
        
        $row = $this->mDBInstance->SelectQuery($sql)[0];
        $this->activateFontTextHeadlineSmall();
        $this->activateFontColorGreen();
        if (count($manual6name) > 0) {
            $text = "";
            if ($row['o_m6wd'] != "" ) {
                $text = $row['o_m6wd'] . " mm/WS";
            } 
            if ($oOrgel->getGroesseM6() != "") {
                $text = ($text == "" ? $oOrgel->getGroesseM6() :  $text . " / " . $oOrgel->getGroesseM6());
            }
            $this->Cell($iSizeRegisterBez, $this->cellheight, ($text == "" ? "Unbekannt" : $text), 0, 0);
            $this->Cell(10, $this->cellheight, '', 0, 0);
        }
        if (count($manual1name) > 0) {
            $text = "";
            if ($row['o_m1wd'] != "" ) {
                $text = $row['o_m1wd'] . " mm/WS";
            } 
            if ($oOrgel->getGroesseM1() != "") {
                $text = ($text == "" ? $oOrgel->getGroesseM1() :  $text . " / " . $oOrgel->getGroesseM1());
            }
            $this->Cell($iSizeRegisterBez, $this->cellheight, ($text == "" ? "Unbekannt" : $text), 0, 0);
            $this->Cell(10, $this->cellheight, '', 0, 0);
        }
        if (count($manual2name) > 0) {
            $text = "";
            if ($row['o_m2wd'] != "" ) {
                $text = $row['o_m2wd'] . " mm/WS";
            } 
            if ($oOrgel->getGroesseM2() != "") {
                $text = ($text == "" ? $oOrgel->getGroesseM2() :  $text . " / " . $oOrgel->getGroesseM2());
            }
            $this->Cell($iSizeRegisterBez, $this->cellheight, ($text == "" ? "Unbekannt" : $text), 0, 0);
            $this->Cell(10, $this->cellheight, '', 0, 0);
        }
        if (count($manual3name) > 0) {            
            $text = "";
            if ($row['o_m3wd'] != "" ) {
                $text = $row['o_m3wd'] . " mm/WS";
            } 
            if ($oOrgel->getGroesseM3() != "") {
                $text = ($text == "" ? $oOrgel->getGroesseM3() :  $text . " / " . $oOrgel->getGroesseM3());
            }
            $this->Cell($iSizeRegisterBez, $this->cellheight, ($text == "" ? "Unbekannt" : $text), 0, 0);
            $this->Cell(10, $this->cellheight, '', 0, 0);
        }
        if (count($manual4name) > 0) {
            
            $text = "";
            if ($row['o_m4wd'] != "" ) {
                $text = $row['o_m4wd'] . " mm/WS";
            } 
            if ($oOrgel->getGroesseM4() != "") {
                $text = ($text == "" ? $oOrgel->getGroesseM4() :  $text . " / " . $oOrgel->getGroesseM4());
            }
            $this->Cell($iSizeRegisterBez, $this->cellheight, ($text == "" ? "Unbekannt" : $text), 0, 0);
            $this->Cell(10, $this->cellheight, '', 0, 0);
        }
        $this->activateFontBold();
        $this->activateFontColorBlack();
        $this->Cell(10, $this->cellheight, '', 0, 1);
        $this->activateFontNormal();
        
        $platzhalter = 10;
        for ($i = 0; $i < $iGroesstesManual; $i ++) {
            
            // Platzhalter
            $leerzeile = $platzhalter + $iSizeRegisterBez;
            $rahmen = 0;
            
            if (isset($manual6name[$i]) && $manual6name[$i] != "") {
                $this->Cell($iSizeRegisterBez, $this->cellheight, substr($manual6name[$i], 0, 16), $rahmen, 0);
                $this->Cell($platzhalter, $this->cellheight, $manual6fuss[$i] . "'", $rahmen, 0);
            } else {
                $this->Cell($leerzeile, $this->cellheight, '', $rahmen, 0);
            }
            
            if (isset($manual1name[$i]) && $manual1name[$i] != "") {
                $this->Cell($iSizeRegisterBez, $this->cellheight, substr($manual1name[$i], 0, 16), $rahmen, 0);
                $this->Cell($platzhalter, $this->cellheight, $manual1fuss[$i] . "'", $rahmen, 0);
            } else {
                $this->Cell($leerzeile, $this->cellheight, '', $rahmen, 0);
            }
            if (isset($manual2name[$i]) && $manual2name[$i] != "") {
                $this->Cell($iSizeRegisterBez, $this->cellheight, substr($manual2name[$i], 0, 16), $rahmen, 0);
                $this->Cell($platzhalter, $this->cellheight, $manual2fuss[$i] . "'", $rahmen, 0);
            } else {
                $this->Cell($leerzeile, $this->cellheight, '', $rahmen, 0);
            }
            if (isset($manual3name[$i]) && $manual3name[$i] != "") {
                $this->Cell($iSizeRegisterBez, $this->cellheight, substr($manual3name[$i], 0, 16), $rahmen, 0);
                $this->Cell($platzhalter, $this->cellheight, $manual3fuss[$i] . "'", $rahmen, 0);
            } else {
                $this->Cell($leerzeile, $this->cellheight, '', $rahmen, 0);
            }
            if (isset($manual4name[$i]) && $manual4name[$i] != "") {
                $this->Cell($iSizeRegisterBez, $this->cellheight, substr($manual4name[$i], 0, 16), $rahmen, 0);
                $this->Cell($platzhalter, $this->cellheight, $manual4fuss[$i] . "'", $rahmen, 0);
            } else {
                $this->Cell($leerzeile, $this->cellheight, '', $rahmen, 0);
            }
            
            $this->Cell($platzhalter, $this->cellheight, '', 0, 1);
        }
        $this->Cell($platzhalter, $this->cellheight, '', 0, 1);
        $this->zeichneTrennstrich();
    }
    
    protected function handleManual($pOID, $pManualID)
    {
        $sqldisp = "SELECT d_id, o_id, m_id, d_name, d_fuss
					FROM disposition
					WHERE o_id = '" . $pOID . "' AND m_id = '" . $pManualID . "'
					ORDER BY m_id";
        
        $manualName = array();
        $manualFuss = array();
        
        $iRegisterCount = 0;
        if (($resultdisp = $this->mDBInstance->SelectQuery($sqldisp)) !== false) {
            foreach ($resultdisp as $row) {
                $manualName[] = $row['d_name'];
                $manualFuss[] = $row['d_fuss'];
                $iRegisterCount ++;
            }
        }
        
        return array(
            $manualName,
            $manualFuss,
            $iRegisterCount
        );
    }
    
}
?>