<?php

abstract class DeckblattPDF extends OrgelbankBasisPDF
{
    function __construct()
    {
        parent::__construct();
        
        $this->AddFont($this->font, '', 'DejaVuSansCondensed.ttf', true);
        $this->AddFont($this->fontBold, '', 'DejaVuSansCondensed-Bold.ttf', true);
    }

    function Header()
    {
        $cellsize = $this->getDefaultCellSize();
        
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

    function Footer()
    {
        $this->SetSubject("Deckblatt", $this->utf8);
        $this->SetTitle("Deckblatt", $this->utf8);
        $this->SetCreator("Pflegesoftware von watermeyer IT", $this->utf8);
    }

    public function addOrgel(Orgel $oOrgel)
    {
        $oGemeinde = new Gemeinde($oOrgel->getGemeindeId());
        $cAnsprechpartner = AnsprechpartnerUtilities::getGemeindeAnsprechpartner($oGemeinde->getID(), " LIMIT 4");
        $alleAnsprechpartner = AnsprechpartnerUtilities::getGemeindeAnsprechpartner($oGemeinde->getID());
        
        $this->AliasNbPages();
        $this->AddPage();
        $this->addGemeindeDaten($oGemeinde, $oOrgel, $cAnsprechpartner);
        $this->addOrgelDetails($oOrgel);
        $this->addDisposition($oOrgel);
        
        $this->addKompletteKontaktdaten($alleAnsprechpartner);
    }

    private function addGemeindeDaten(Gemeinde $oGemeinde, Orgel $oOrgel)
    {
        if ($oGemeinde == null || $oGemeinde->getID() == "" || $oGemeinde->getID() <= 0)
            return;
        
        // Lokale Variablen
        $cellsize = $this->getDefaultCellSize();
        $counter = 0;
        
        // Rand setzen
        $this->SetMargins($this->getRandLinks(), 10, 0);
        
        $k = KonfessionUtilities::getKonfessionenAsArray();
        
        // Bookmark Kapitel
        //$this->Bookmark($oGemeinde->getKirche());

        $this->activateFontColorBlack();
        $this->activateFontNormal();
        
        $rahmen = 0;
        
        // Anschrift
        $this->activateFontTextHeadline();
        $this->Cell($cellsize, $this->cellheight, "Anschrift", $rahmen, 0, "L");
        $this->Cell($cellsize, $this->cellheight, "Rechnungsanschrift", $rahmen, 0, "L");
        $this->ln($this->cellheight);
        $this->activateFontNormal();
        
        $this->Cell($cellsize, $this->cellheight, $k[$oGemeinde->getKID()] . "e Gemeinde", $rahmen, 0, "L");
        $this->Cell($cellsize, $this->cellheight, $oGemeinde->getRAnschrift(), $rahmen, 0, "L");
        
        $this->ln($this->cellheight);
        $this->activateFontColorRed();
        $this->Cell($cellsize, $this->cellheight, $oGemeinde->getKirche(), $rahmen, 0, "L", 0);
        $this->Cell($cellsize, $this->cellheight, $oGemeinde->getRGemeinde(), $rahmen, 0, "L");
        $this->activateFontColorBlack();
        $this->ln($this->cellheight);
        $this->Cell($cellsize, $this->cellheight, $oGemeinde->getKircheAdresse()
            ->getStrasse() . " " . $oGemeinde->getKircheAdresse()
            ->getHausnummer(), $rahmen, 0, "L");
        $this->Cell($cellsize, $this->cellheight, $oGemeinde->getRechnungAdresse()
            ->getStrasse() . " " . $oGemeinde->getRechnungAdresse()
            ->getHausnummer(), $rahmen, 0, "L");
        $this->ln($this->cellheight);
        $this->Cell($cellsize, $this->cellheight, $oGemeinde->getKircheAdresse()
            ->getPLZ() . " " . $oGemeinde->getKircheAdresse()
            ->getOrt(), $rahmen, 0, "L");
        $this->Cell($cellsize, $this->cellheight, $oGemeinde->getRechnungAdresse()
            ->getPLZ() . " " . $oGemeinde->getRechnungAdresse()
            ->getOrt(), $rahmen, 0, "L");
        $this->ln($this->cellheight);
        
        // Allgemein PDF Daten erstellen
        if ($this->getKeywords() == "") {
            $this->SetKeywords("Wartungsbogen für: ", $this->utf8);
        }
        $this->SetKeywords($this->getKeywords() . $oGemeinde->getKirche() . ", ", $this->utf8);
        
        // Header Bezirk & OrgelId
        $this->addBezirkUndOrgelID($oOrgel->getID(), $oGemeinde->getBID());
        
        // Trennstrich
        $this->ln($this->cellheight);
        $this->zeichneTrennstrich();
    }

    private function addOrgelDetails(Orgel $oOrgel)
    {
        $th = 30;
        $td = 23;
        
        // Ueberschrift
        $this->activateFontTextHeadline();
        $this->Cell($th + $th + $td + $td, $this->cellheight, 'Allgemeine & Technische Daten:', 0, 1, "L");
        
        $w = Constant::getWindladen();
        $r = Constant::getRegisterTrakturen();
        $s = Constant::getSpieltrakturen();
        $k = Constant::getKoppeln();
        $z = Constant::getZyklus();
        $p = Constant::getPflegevertrag();
        
        $rahmen = 0;
        $ausrichtungTH = "L";
        $ausrichtungTD = "L";
        $this->activateFontBold();
        $this->Cell($th, $this->cellheight, 'Baujahr:', $rahmen, 0, $ausrichtungTH);
        $this->activateFontNormal();
        $this->Cell($td, $this->cellheight, $oOrgel->getBaujahr(), $rahmen, 0, $ausrichtungTD);
        $this->activateFontBold();
        $this->Cell($th, $this->cellheight, 'Erbauer:', $rahmen, 0, $ausrichtungTH);
        $this->activateFontNormal();
        $this->Cell($td, $this->cellheight, $oOrgel->getErbauer(), $rahmen, 1, $ausrichtungTD);
        $this->activateFontBold();
        $this->Cell($th, $this->cellheight, 'Windladen:', $rahmen, 0, $ausrichtungTH);
        $this->activateFontNormal();
        $this->Cell($td, $this->cellheight, $w[$oOrgel->getWindladeID()], $rahmen, 0, $ausrichtungTD);
        $this->activateFontBold();
        $this->Cell($th, $this->cellheight, 'Spieltraktur:', $rahmen, 0, $ausrichtungTH);
        $this->activateFontNormal();
        $this->Cell($td, $this->cellheight, $s[$oOrgel->getSpieltrakturID()], $rahmen, 1, $ausrichtungTD);
        
        $this->activateFontBold();
        $this->Cell($th, $this->cellheight, 'Koppeln:', $rahmen, 0, $ausrichtungTH);
        $this->activateFontNormal();
        $this->Cell($td, $this->cellheight, $k[$oOrgel->getKoppelID()], $rahmen, 0, $ausrichtungTD);
        $this->activateFontBold();
        $this->Cell($th, $this->cellheight, 'Registertraktur:', $rahmen, 0, $ausrichtungTH);
        $this->activateFontNormal();
        $this->Cell($td, $this->cellheight, $r[$oOrgel->getRegistertrakturID()], $rahmen, 1, $ausrichtungTD);
        
        $this->activateFontBold();
        $this->Cell($th, $this->cellheight, 'Pflegevertrag:', $rahmen, 0, $ausrichtungTH);
        $this->activateFontNormal();
        
        if(isset($p[$oOrgel->getPflegevertrag()])) {
            $this->Cell($td, $this->cellheight, $p[$oOrgel->getPflegevertrag()], $rahmen, 0, $ausrichtungTD);    
        } else {
            $this->Cell($td, $this->cellheight, $oOrgel->getPflegevertrag(), $rahmen, 0, $ausrichtungTD);
        }
        
        $this->activateFontBold();
        $this->Cell($th, $this->cellheight, 'Zyklus:', $rahmen, 0, $ausrichtungTH);
        $this->activateFontNormal();
        
        if(isset($p[$oOrgel->getPflegevertrag()])) {
            $this->Cell($td, $this->cellheight, $z[$oOrgel->getZyklus()], $rahmen, 1, $ausrichtungTD);
        } else {
            $this->Cell($td, $this->cellheight, $oOrgel->getZyklus(), $rahmen, 1, $ausrichtungTD);    
        }
        
        $this->activateFontBold();
        $this->Cell($th, $this->cellheight, 'Stimmung nach:', $rahmen, 0, $ausrichtungTH);
        $this->activateFontNormal();
        $this->Cell($td + $td + $th, $this->cellheight, $oOrgel->getStimmung(), $rahmen, 1, $ausrichtungTD);
        
        $massnahmen = $oOrgel->getMassnahmen() != "" ? $oOrgel->getMassnahmen() : "(keine)";
        $anmerkungen = $oOrgel->getAnmerkung() != "" ? $oOrgel->getAnmerkung() : "(keine)";
        
        $this->ln(3);
        $this->activateFontBold();
        $this->SetAutoPageBreak(1);
        $tmpX = $this->GetX();
        $tmpY = $this->GetY();
        $this->Cell(90, $this->cellheight, 'Allgemeine Anmerkungen:', 0, 1, "L");
        $this->activateFontNormal();
        $this->MultiCell(90, $this->cellheight, $anmerkungen, 0, "L");
        
        $this->activateFontBold();
        $this->SetAutoPageBreak(1);
        $this->setXY($tmpX + 90, $tmpY);
        $this->Cell(90, $this->cellheight, 'Notwendige Maßnahmen:', 0, 1, "L");
        $this->setXY($tmpX + 90, $tmpY + $this->cellheight);
        $this->activateFontNormal();
        $this->MultiCell(90, $this->cellheight, $massnahmen, 0, "L");
        
        // Anzahl Zeilen zwischen Notwendigen Maßnahme + Allgemein Anmerkung und Disposition bestimmen
        $zeichenProZeile = 44;
        $zeilenAnmerkungen = strlen($anmerkungen) / $zeichenProZeile;
        $zeilenMassnahmen = strlen($massnahmen) / $zeichenProZeile;
        if ($zeilenAnmerkungen > $zeilenMassnahmen) {
            $this->ln((intval($zeilenAnmerkungen - $zeilenMassnahmen) + 1) * $this->cellheight);
        } else {
            $this->ln(1);
        }
        
        // Bild einfügen
        if (ConstantLoader::getWartungsBogenBildAnzeige() == "true") {
            $bildpfad = ORGELBILD_THUMB_PFAD . $oOrgel->getID() . "_1.jpg";
            if (file_exists($bildpfad)) {
                $img = getimagesize($bildpfad);
                if ($img[0] < $img[1]) {
                    $this->Image($bildpfad, 130, 59, 30);
                } else {
                    $this->Image($bildpfad, 130, 59, 50);
                }
                $this->ln(2);
            }
        }
        
        // Trennstrich
        $this->zeichneTrennstrich();
    }
    
    private function addKompletteKontaktdaten(DatabaseStorageObjektCollection $pAnsprechpartner)
    {
        $rahmen = 0;
        $platzhalter = 0;
        $iBreiteBeschreibung = 51;
        $iBreiteWert = 40;
        
        // Spalten etwas hoeher machen, da ja manuell etwas eingetragen werden muss
        $iHeight = $this->cellheight + 3;
        
        $rahmen = 0;
        
        $this->Ln(3);
        $this->activateFontTextHeadline();
        $this->Cell(50, $this->cellheight, 'Kontakte:', $rahmen, 1, "L");
        $this->activateFontNormal();
        $this->Ln(1);
        
        
        
        $counter = 0;
        foreach ($pAnsprechpartner as $oAnsprechpartner) {
            $counter++;
            
            if($counter % 2 == 0) {
                $breiteBezeichnung = $breiteBezeichnung + 80;
                $this->SetXY($this->getRandLinks(), $this->getY() - 24);
            } else {
                $breiteBezeichnung = 20;
            }
            
            $this->Cell($breiteBezeichnung, $this->cellheight, "Funktion: ", $rahmen, 0, "R");
            $this->Cell(60, $this->cellheight, $oAnsprechpartner->getFunktion(), $rahmen, 1);
            
            $this->Cell($breiteBezeichnung, $this->cellheight, "Name: ", $rahmen, 0, "R");
            $this->Cell(60, $this->cellheight, $oAnsprechpartner->getAnzeigename(), $rahmen, 1);
            
            $this->Cell($breiteBezeichnung, $this->cellheight, ($oAnsprechpartner->getTelefon() == "" ? "" : "Telefon: "), $rahmen, 0, "R");
            $this->Cell(60, $this->cellheight, $oAnsprechpartner->getTelefon(), $rahmen, 1);
            
            $this->Cell($breiteBezeichnung, $this->cellheight, ($oAnsprechpartner->getMobil() == "" ? "" : "Mobil: "), $rahmen, 0, "R");
            $this->Cell(60, $this->cellheight, $oAnsprechpartner->getMobil(), $rahmen, 1);
            
            $this->Cell($breiteBezeichnung, $this->cellheight, ($oAnsprechpartner->getEmail() == "" ? "" : "Mail: "), $rahmen, 0, "R");
            $this->Cell(60, $this->cellheight, $oAnsprechpartner->getEmail(), $rahmen, 1);
            
             // Abstandszeile
            $this->Cell($breiteBezeichnung, $this->cellheight, "", $rahmen, 0);
            $this->Cell(60, $this->cellheight, "", $rahmen, 1);
            
            
            
        }
        
        $this->Cell(1, $iHeight, '', 0, 1);
    }
}
?>