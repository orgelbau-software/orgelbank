<?php

abstract class WartungsbogenPDF extends tFPDFWithBookmark
{

    protected $iRandLinks = 10;

    protected $iTrennstrichLaenge = 185;

    protected $arHeaderMeta;

    protected $cellheight = 4;

    protected $mDBInstance;

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

    function __construct()
    {
        parent::__construct();
        $this->mDBInstance = DB::getInstance();
        
        $this->AddFont($this->font, '', 'DejaVuSansCondensed.ttf', true);
        $this->AddFont($this->fontBold, '', 'DejaVuSansCondensed-Bold.ttf', true);
    }

    function Header()
    {
        $cellsize = 70;
        
        
        
        // Rand setzen
        $this->SetMargins($this->iRandLinks, 10, 10);
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
        $this->SetFont($this->fontBold, "", 8);
    }

    /**
     */
    protected function activateFontTextHeadline()
    {
        $this->SetFont($this->fontBold, '', 11);
    }

    /**
     */
    protected function activateFontBold()
    {
        $this->SetFont($this->fontBold, '', 10);
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
        
        if(ConstantLoader::getWartungsBogenKompletteAnsprechpartner() == "true") {
            $this->addKompletteKontaktdaten($alleAnsprechpartner);
        }
        
    }
    
    

    private function addGemeindeDaten(Gemeinde $oGemeinde, Orgel $oOrgel, $pAnsprechpartner)
    {
        if ($oGemeinde == null || $oGemeinde->getID() == "" || $oGemeinde->getID() <= 0)
            return;
        
        // Lokale Variablen
        $cellsize = 70;
        $counter = 0;
        
        // Rand setzen
        $this->SetMargins($this->iRandLinks, 10, 0);
        
        $k = KonfessionUtilities::getKonfessionenAsArray();
        
        // Bookmark Kapitel
        $this->Bookmark($oGemeinde->getKirche());

        //$this->SetXY($this->iRandLinks, 30);
        $this->SetTextColor(0, 0, 0);
        $this->activateFontNormal();
        $this->Cell($cellsize, 8, $k[$oGemeinde->getKID()] . "e Gemeinde", 0, 0, "L");
        $this->ln($this->cellheight);
        $this->SetTextColor(255, 0, 0);
        $this->Cell($cellsize, 8, $oGemeinde->getKirche(), 0, 0, "L", 0);
        $this->SetTextColor(0, 0, 0);
        $this->ln($this->cellheight);
        $this->Cell($cellsize, 8, $oGemeinde->getKircheAdresse()
            ->getStrasse() . " " . $oGemeinde->getKircheAdresse()
            ->getHausnummer(), 0, 0, "L");
        $this->ln($this->cellheight);
        $this->Cell($cellsize, 8, $oGemeinde->getKircheAdresse()
            ->getPLZ() . " " . $oGemeinde->getKircheAdresse()
            ->getOrt(), 0, 0, "L");
        $this->ln($this->cellheight);
        
        // Allgemein PDF Daten erstellen
        if ($this->getKeywords() == "") {
            $this->SetKeywords("Wartungsbogen für: ", $this->utf8);
        }
        $this->SetKeywords($this->getKeywords() . $oGemeinde->getKirche() . ", ", $this->utf8);
        
        // Ansprechpartner
        $this->SetXY(83, 30);
        $this->activateFontNormal();
        $this->Cell(50, 6, 'Ansprechpartner:', 0, 0, "L");
        $this->ln(3);
        
        // Tabellenkopf
        $this->SetXY(85, 36);
        $this->SetFont("DejaVu B", '', 10);
        $rahmen = 0;
        $this->Cell(22, $this->cellheight, 'Funktion', $rahmen);
        $this->Cell(35, $this->cellheight, 'Name', $rahmen);
        $this->Cell(28, $this->cellheight, 'Telefon', $rahmen);
        $this->Cell(28, $this->cellheight, 'Mobil', $rahmen);
        $this->activateFontNormal();
        
        foreach ($pAnsprechpartner as $oAnsprechpartner) {
            // Laufvariablen
            $starthoehe = 36;
            $counter = $counter + 1;
            
            $this->SetXY(85, $starthoehe + $this->cellheight * $counter);
            $this->Cell(22, $this->cellheight, substr($oAnsprechpartner->getFunktion(), 0, 10), 1);
            $this->Cell(35, $this->cellheight, substr($oAnsprechpartner->getAnzeigename(), 0, 18), 1);
            $this->Cell(28, $this->cellheight, $oAnsprechpartner->getTelefon(), 1);
            $this->Cell(28, $this->cellheight, $oAnsprechpartner->getMobil(), 1);
        }
        
        // Header Bezirk & OrgelId
        $this->SetXY(170, 9);
        $this->activateFontNormal();
        $this->SetTextColor(255, 0, 0);
        $this->Cell($cellsize, 8, "Bezirk: " . $oGemeinde->getBID(), 0, 0, "L");
        $this->SetXY(170, 16);
        $this->SetTextColor(0, 0, 255);
        $this->Cell($cellsize, 10, "Orgel:  " . $oOrgel->getID(), 0, 0, "L");
        $this->activateFontNormal();
        $this->SetTextColor(0, 0, 0);
        
        // Trennstrich
        $this->SetXY($this->iRandLinks, 60);
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
        $this->Cell($td, $this->cellheight, $p[$oOrgel->getPflegevertrag()], $rahmen, 0, $ausrichtungTD);
        $this->activateFontBold();
        $this->Cell($th, $this->cellheight, 'Zyklus:', $rahmen, 0, $ausrichtungTH);
        $this->activateFontNormal();
        $this->Cell($td, $this->cellheight, $z[$oOrgel->getZyklus()], $rahmen, 1, $ausrichtungTD);
        
        $this->activateFontBold();
        $this->Cell($th, $this->cellheight, 'Stimmung nach:', $rahmen, 0, $ausrichtungTH);
        $this->activateFontNormal();
        $this->Cell($td + $td + $th, $this->cellheight, $oOrgel->getStimmung(), $rahmen, 1, $ausrichtungTD);
        
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
        if($c->getSize() > 0) {
            
            $this->Cell(21, $this->cellheight, 'Datum', $thRamen, 0, "L");
            $this->Cell(21, $this->cellheight, 'Mitarbeiter', $thRamen, 0, "L");
            $this->Cell(23, $this->cellheight, 'Temperatur', $thRamen, 0, "L");
            $this->Cell(23, $this->cellheight, 'Luftfeuchte', $thRamen, 0, "L");
            $this->Cell(18, $this->cellheight, 'Stimmton', $thRamen, 1, "L");
            
            $this->activateFontNormal();
        
            foreach ($c as $oWartung) {
                $b = new Benutzer($oWartung->getMitarbeiterId1());
                $temperatur = ($oWartung->getTemperatur() != "" ? $oWartung->getTemperatur() . " °C" : "");
                $luftfeuchte = ($oWartung->getLuftfeuchtigkeit() != "" ? $oWartung->getLuftfeuchtigkeit() . " %" : "");
                $stimmton = ($oWartung->getStimmtonHoehe() != "" ? $oWartung->getStimmtonHoehe() . " Hz" : "");
                
                $this->Cell(21, $this->cellheight, $oWartung->getDatum(true), 1, 0, "L");
                $this->Cell(21, $this->cellheight, substr($b->getBenutzername(), 0, 10), 1, 0, "L");
                $this->Cell(23, $this->cellheight, $temperatur, 1, 0, "R");
                $this->Cell(23, $this->cellheight, $luftfeuchte, 1, 0, "R");
                $this->Cell(18, $this->cellheight, $stimmton, 1, 1, "R");
            }
        } else {
            
            $this->Cell(21, $this->cellheight, "Keine Wartungen bisher", 0, 0, "L");
            $this->Ln(3);
        }
    }

    /**
     * Neue Funktion zum ausprobieren
     *
     * @param Orgel $oOrgel            
     */
    private function addDisposition(Orgel $oOrgel)
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
        $cellsize = 70;
        
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
        $this->SetTextColor(0, 200, 0);
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
        $this->SetTextColor(0, 0, 0);
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
        $this->Cell($iBreiteWert, $iHeight, "_____________ ", $rahmen, 0);
        
        $this->Cell(1, $iHeight, '', 0, 1);
    }

    private function handleManual($pOID, $pManualID)
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
    

    /**
     * Zeichnet einen Trennstrich ins aktuelle Dokument an die aktuelle Stelle des Cursors.
     *
     * Die Länge kann in der Objektvariablen bestimmt werden
     */
    private function zeichneTrennstrich()
    {
        $this->Cell($this->iTrennstrichLaenge, 0, '', 1, 0, "C");
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
}
?>