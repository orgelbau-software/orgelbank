<?php

abstract class WartungsbogenPDF extends OrgelbankBasisPDF
{

    /**
     * Wether to use UTF-8 or not.
     *
     * @var boolean
     */
    protected $utf8 = true;
    
    const NEWROW = 1;

    function __construct()
    {
        parent::__construct();
        
        $this->AddFont($this->font, '', 'DejaVuSansCondensed.ttf', true);
        $this->AddFont($this->fontBold, '', 'DejaVuSansCondensed-Bold.ttf', true);
    }

    function Footer()
    {
        $this->SetSubject("Wartungsbogen", $this->utf8);
        $this->SetTitle("Wartungsbögen", $this->utf8);
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
        if(ConstantLoader::getWartungsBogenCheckliste() == "true") {
            $this->addCheckliste($oOrgel);
        }
        
        if($oOrgel->getWartungsprotokollID() > 0) {
            $protokoll = new Wartungsprotokoll($oOrgel->getWartungsprotokollID());
            $this->addWartungsprotokoll($protokoll);
        }
    }
    

    private function addGemeindeDaten(Gemeinde $oGemeinde, Orgel $oOrgel, $pAnsprechpartner)
    {
        if ($oGemeinde == null || $oGemeinde->getID() == "" || $oGemeinde->getID() <= 0)
            return;
        
        // Lokale Variablen
        $cellsize = $this->getDefaultCellSize();
        $counter = 0;
        
        // Rand setzen
        $this->SetMargins($this->iRandLinks, 10, 0);
        
        $k = KonfessionUtilities::getKonfessionenAsArray();
        
        // Bookmark Kapitel
        // FIXME: $this->Bookmark($oGemeinde->getKirche());

        //$this->SetXY($this->iRandLinks, 30);
        $this->activateFontColorBlack();
        $this->activateFontNormal();
        $this->Cell($cellsize, $this->cellheight, $k[$oGemeinde->getKID()] . "e Gemeinde", 0, 0, "L");
        $this->ln($this->cellheight);
        $this->activateFontColorRed();
        $this->Cell($cellsize, $this->cellheight, $oGemeinde->getKirche(), 0, 0, "L", 0);
        $this->activateFontColorBlack();
        $this->ln($this->cellheight);
        $this->Cell($cellsize, $this->cellheight, $oGemeinde->getKircheAdresse()
            ->getStrasse() . " " . $oGemeinde->getKircheAdresse()
            ->getHausnummer(), 0, 0, "L");
        $this->ln($this->cellheight);
        $this->Cell($cellsize, $this->cellheight, $oGemeinde->getKircheAdresse()
            ->getPLZ() . " " . $oGemeinde->getKircheAdresse()
            ->getOrt(), 0, 0, "L");
        $this->ln($this->cellheight);
        
        // Allgemein PDF Daten erstellen
        if ($this->getKeywords() == "") {
            $this->SetKeywords("Wartungsbogen für: ", $this->utf8);
        }
        $this->SetKeywords($this->getKeywords() . $oGemeinde->getKirche() . ", ", $this->utf8);
        
        // Ansprechpartner
//         $this->SetXY(83, 30);
        $this->activateFontTextHeadline();
        $this->Cell(50, 6, 'Ansprechpartner:', 0, 0, "L");
        $this->ln(5);
        
        // Tabellenkopf
//         $this->SetXY(85, 36);
        $this->SetFont("DejaVu B", '', 10);
        $rahmen = 0;
        $this->Cell(35, $this->cellheight, 'Funktion', $rahmen);
        $this->Cell(50, $this->cellheight, 'Name', $rahmen);
        $this->Cell(40, $this->cellheight, 'Telefon', $rahmen);
        $this->Cell(40, $this->cellheight, 'Mobil', $rahmen, WartungsbogenPDF::NEWROW);
        $this->activateFontNormal();
        
        $starthoehe = 36;
        foreach ($pAnsprechpartner as $oAnsprechpartner) {
            // Laufvariablen
            $counter = $counter + 1;
            
//             $this->SetXY(85, $starthoehe + $this->cellheight * $counter);
            $this->Cell(35, $this->cellheight, ($oAnsprechpartner->getFunktion() == null ? "" : substr($oAnsprechpartner->getFunktion(), 0, 20)), 1);
            $this->Cell(50, $this->cellheight, ($oAnsprechpartner->getAnzeigename() == null ? "" : substr($oAnsprechpartner->getAnzeigename(), 0, 30)), 1);
            $this->Cell(40, $this->cellheight, ($oAnsprechpartner->getTelefon() == null ? "" : $oAnsprechpartner->getTelefon()), 1);
            $this->Cell(40, $this->cellheight, ($oAnsprechpartner->getMobil() == null ? "" : $oAnsprechpartner->getMobil()), 1, WartungsbogenPDF::NEWROW);
        }
        $this->ln(2);
        // Header Bezirk & OrgelId
        $this->addBezirkUndOrgelID($oOrgel->getID(), $oGemeinde->getBID());
        
        // Trennstrich
//         $this->SetXY($this->iRandLinks, 60);
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
            $this->Cell($td, $this->cellheight, "Unbekannt", $rahmen, 0, $ausrichtungTD);
        }
        $this->activateFontBold();
        $this->Cell($th, $this->cellheight, 'Zyklus:', $rahmen, 0, $ausrichtungTH);
        $this->activateFontNormal();
        $this->Cell($td, $this->cellheight, $z[$oOrgel->getZyklus()], $rahmen, 1, $ausrichtungTD);
        
        $this->activateFontBold();
        $this->Cell($th, $this->cellheight, 'Stimmung nach:', $rahmen, 0, $ausrichtungTH);
        $this->activateFontNormal();
        $this->Cell($td, $this->cellheight, $oOrgel->getStimmung(), $rahmen, 0, $ausrichtungTD);
        $this->activateFontBold();
        $this->Cell($th, $this->cellheight, 'Hauptstimmung:', $rahmen, 0, $ausrichtungTH);
        $this->activateFontNormal();
        $this->Cell($td, $this->cellheight, Constant::getIntervallHauptstimmung()[$oOrgel->getIntervallHauptstimmung()], $rahmen, 1, $ausrichtungTD);
        
        $this->activateFontBold();
        $this->Cell($th, $this->cellheight, 'Orig. Stimmton:', $rahmen, 0, $ausrichtungTH);
        $this->activateFontNormal();
        $this->Cell($td, $this->cellheight, $oOrgel->getStimmton(), $rahmen, 0, $ausrichtungTD);
        $this->activateFontBold();
        $this->Cell($th, $this->cellheight, '', $rahmen, 0, $ausrichtungTH);
        $this->activateFontNormal();
        $this->Cell($td, $this->cellheight,'', $rahmen, 1, $ausrichtungTD);
        
        $this->addWartungsBlock($oOrgel);
        
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

    /**
     * Fügt dem aktuellen PDF die Wartungsdaten der Orgel hinzu
     *
     * @param Orgel $oOrgel            
     */
    private function addWartungsBlock(Orgel $oOrgel)
    {
        $th = 30;
        $td = 23;
        $thRamen = 0;
        
        $this->Ln(3);
        $this->activateFontTextHeadline();
        $this->Cell($th + $th + $td + $td, $this->cellheight, 'Letzte Wartungen:', 0, 1, "L");
        $this->activateFontBold();
        
        $c = WartungUtilities::getOrgelWartungen($oOrgel->getID(), " ORDER BY w_datum DESC LIMIT 3");
        $stimmungen = Constant::getStimmung();
        if($c->getSize() > 0) {
            
            $this->Cell(21, $this->cellheight, 'Datum', $thRamen, 0, "L");
            $this->Cell(21, $this->cellheight, 'Mitarbeiter', $thRamen, 0, "L");
            $this->Cell(23, $this->cellheight, 'Temperatur', $thRamen, 0, "L");
            $this->Cell(23, $this->cellheight, 'Luftfeuchte', $thRamen, 0, "L");
            $this->Cell(18, $this->cellheight, 'Stimmton', $thRamen, 0, "L");
            $this->Cell(30, $this->cellheight, 'Stimmung', $thRamen, 1, "L");
            
            $this->activateFontNormal();
        
            foreach ($c as $oWartung) {
                $b = new Benutzer($oWartung->getMitarbeiterId1());
                $benutzername = ($b->getBenutzername() != "" ? $b->getBenutzername() : "Unbekannt");
                $temperatur = ($oWartung->getTemperatur() != "" ? $oWartung->getTemperatur() . " °C" : "");
                $luftfeuchte = ($oWartung->getLuftfeuchtigkeit() != "" ? $oWartung->getLuftfeuchtigkeit() . " %" : "");
                $stimmton = ($oWartung->getStimmtonHoehe() != "" ? $oWartung->getStimmtonHoehe() . " Hz" : "");
                $stimmung = $stimmungen[$oWartung->getStimmung()];
                
                $this->Cell(21, $this->cellheight, $oWartung->getDatum(true), 1, 0, "L");
                $this->Cell(21, $this->cellheight, substr($benutzername, 0, 10), 1, 0, "L");
                $this->Cell(23, $this->cellheight, $temperatur, 1, 0, "R");
                $this->Cell(23, $this->cellheight, $luftfeuchte, 1, 0, "R");
                $this->Cell(18, $this->cellheight, $stimmton, 1, 0, "R");
                $this->Cell(30, $this->cellheight, $stimmung, 1, 1, "R");
            }
        } else {
            
            $this->Cell(21, $this->cellheight, "Keine Wartungen bisher", 0, 0, "L");
            $this->Ln(3);
        }
    }

    private function addCheckliste(Orgel $oOrgel)
    {
        $rahmen = 0;
        $platzhalter = 0;
        $iBreiteBeschreibung = 51;
        $iBreiteWert = 40;
        
        // Spalten etwas hoeher machen, da ja manuell etwas eingetragen werden muss
        $iHeight = $this->cellheight + 3;
        
        $this->Ln(3);
        $this->activateFontTextHeadline();
        $this->Cell(0, $this->cellheight, 'Checkliste:', 0, 1, "L");
        $this->activateFontNormal();
        $this->Ln(1);
        $this->Cell($iBreiteBeschreibung, $iHeight, "Traktur reguliert:", $rahmen, 0);
        $this->Cell($iBreiteWert, $iHeight, '[  ]', 0, 0);
        $this->Cell($iBreiteBeschreibung, $iHeight, "Temperatur:", $rahmen, 0);
        $this->Cell($iBreiteWert, $iHeight, "_____________ °C", $rahmen, 1);
        
        $this->Cell($iBreiteBeschreibung, $iHeight, "Winddruck gemessen:", $rahmen, 0);
        $this->Cell($iBreiteWert, $iHeight, "[  ] ", $rahmen, 0);
        $this->Cell($iBreiteBeschreibung, $iHeight, "Luftfeuchte:", $rahmen, 0);
        $this->Cell($iBreiteWert, $iHeight, "_____________ %", $rahmen, 1);
        
        $this->Cell($iBreiteBeschreibung, $iHeight, "Pedal/Spieltisch gereinigt:", $rahmen, 0);
        $this->Cell($iBreiteWert, $iHeight, "[  ] ", $rahmen, 0);
        $this->Cell($iBreiteBeschreibung, $iHeight, "Stimmtonhöhe:", $rahmen, 0);
        $this->Cell($iBreiteWert, $iHeight, "_____________ Hz", $rahmen, 1);
        
        $this->Cell($iBreiteBeschreibung, $iHeight, "Schimmelbefall:", $rahmen, 0);
        $this->Cell($iBreiteWert, $iHeight, '[ Ja ]  [ Nein ]', 0, 0);
        $this->Cell($iBreiteBeschreibung, $iHeight, "Datum der Pflege:", $rahmen, 0);
        $this->Cell($iBreiteWert, $iHeight, "___________ ." . date("Y"), $rahmen, 1);
        
        $this->Cell($iBreiteBeschreibung, $iHeight, "Holzwurmbefall:", $rahmen, 0);
        $this->Cell($iBreiteWert, $iHeight, '[ Ja ]  [ Nein ]', 0, 0);
        $this->Cell($iBreiteBeschreibung, $iHeight, "Ausführender Mitarbeiter:", $rahmen, 0);
        $this->Cell($iBreiteWert, $iHeight, "_____________ ", $rahmen, 1);
        
        $this->Cell($iBreiteBeschreibung, $iHeight, "Reisezeit:", $rahmen, 0);
        $this->Cell($iBreiteWert, $iHeight, '___ Std. ___ Min.', 0, 0);
        $this->Cell($iBreiteBeschreibung, $iHeight, "Gefahrene KM:", $rahmen, 0);
        $this->Cell($iBreiteWert, $iHeight, "_____________ km", $rahmen, 1);
        
        $this->Cell($iBreiteBeschreibung, $iHeight, "Arbeitszeit:", $rahmen, 0);
        $this->Cell($iBreiteWert, $iHeight, '___ Std. ___ Min.', 0, 0);
        $this->Cell($iBreiteBeschreibung, $iHeight, "", $rahmen, 0);
        $this->Cell($iBreiteWert, $iHeight, " ", $rahmen, 1);
        
        $this->Cell(1, $iHeight, '', 0, 1);
    }
    
    private function addKompletteKontaktdaten(DatabaseStorageObjektCollection $pAnsprechpartner)
    {
        $rahmen = 0;
        $platzhalter = 0;
        $iBreiteBeschreibung = 51;
        $iBreiteWert = 40;
        
        // Spalten etwas hoeher machen, da ja manuell etwas eingetragen werden muss
        $iHeight = $this->cellheight + 3;
        
        $this->Ln(3);
        $this->activateFontTextHeadline();
        $this->Cell(0, $this->cellheight, 'Kontakte:', 0, 1, "L");
        $this->activateFontNormal();
        $this->Ln(1);
        
        $rahmen = 0;
        $breiteBezeichnung = 0;
        $counter = 0;
        foreach ($pAnsprechpartner as $oAnsprechpartner) {
            $counter++;
            
            if($counter % 2 == 0) {
                $breiteBezeichnung = $breiteBezeichnung +80;
                $this->SetXY(10, $this->getY() - 24);
            } else {
                $breiteBezeichnung = 20;
            }
            
            $this->Cell($breiteBezeichnung, $this->cellheight, "Funktion: ", $rahmen, 0, "R");
            $this->Cell(60, $this->cellheight, $oAnsprechpartner->getFunktion(), $rahmen, 1);
            
            $this->Cell($breiteBezeichnung, $this->cellheight, "Name: ", $rahmen, 0, "R");
            $this->Cell(60, $this->cellheight, $oAnsprechpartner->getAnzeigename(), $rahmen, 1);
            
            $this->Cell($breiteBezeichnung, $this->cellheight, "Telefon: ", $rahmen, 0, "R");
            $this->Cell(60, $this->cellheight, $oAnsprechpartner->getTelefon(), $rahmen, 1);
            
            $this->Cell($breiteBezeichnung, $this->cellheight, "Mobil: ", $rahmen, 0, "R");
            $this->Cell(60, $this->cellheight, $oAnsprechpartner->getMobil(), $rahmen, 1);
            
            $this->Cell($breiteBezeichnung, $this->cellheight, "Mail: ", $rahmen, 0, "R");
            $this->Cell(60, $this->cellheight, $oAnsprechpartner->getEmail(), $rahmen, 1);
            
             // Abstandszeile
            $this->Cell($breiteBezeichnung, $this->cellheight, "", $rahmen, 0);
            $this->Cell(60, $this->cellheight, "", $rahmen, 1);
        }
        
        $this->Cell(1, $iHeight, '', 0, 1);
    }
    
}