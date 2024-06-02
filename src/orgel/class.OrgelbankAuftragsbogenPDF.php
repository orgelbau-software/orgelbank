<?php
use Dompdf\Dompdf;
use Dompdf\Options;

class OrgelbankAuftragsbogenPDF
{

    private $pdf = null;

    public function __construct()
    {
        $options = new Options();
        $options->set('defaultFont', 'Courier');

        $this->pdf = new Dompdf($options);
        $this->pdf->setPaper('A4', 'portrait');
    }


    public function addOrgel(Orgel $orgel) {

        $oGemeinde = new Gemeinde($orgel->getGemeindeId());
        $cAnsprechpartner = AnsprechpartnerUtilities::getGemeindeAnsprechpartner($oGemeinde->getID(), " LIMIT 4");
        $alleAnsprechpartner = AnsprechpartnerUtilities::getGemeindeAnsprechpartner($oGemeinde->getID());
        $alleWartungen = WartungUtilities::getOrgelWartungen($orgel->getID(), " ORDER BY w_datum DESC LIMIT 3");


        $tpl = new Template("auftragsbogen.tpl");

        $tpl->replace("Mitarbeiter", "");

        $tpl->replace("OrgelID", $orgel->getID());
        $tpl->replace("Bezirk", $oGemeinde->getBID());
        
        $tpl->replace("Kirche", $oGemeinde->getKirche());
        $tpl->replace("Strasse", $oGemeinde->getKircheAdresse()->getStrasse());
        $tpl->replace("Hsnr", $oGemeinde->getKircheAdresse()->getHausnummer());
        $tpl->replace("PLZ", $oGemeinde->getKircheAdresse()->getPLZ());
        $tpl->replace("Ort", $oGemeinde->getKircheAdresse()->getOrt());

        $tpl->replace("RKirche", $oGemeinde->getKirche());
        $tpl->replace("RStrasse", $oGemeinde->getRechnungAdresse()->getStrasse());
        $tpl->replace("RHsnr", $oGemeinde->getRechnungAdresse()->getHausnummer());
        $tpl->replace("RPLZ", $oGemeinde->getRechnungAdresse()->getPLZ());
        $tpl->replace("ROrt", $oGemeinde->getRechnungAdresse()->getOrt());

        $tplAnsprechpartnerDS = new BufferedTemplate("auftragsbogen_ansprechpartnerds.tpl");
        foreach($alleAnsprechpartner as $currentAnsprechpartner) {
            $tplAnsprechpartnerDS->replace("Funktion", $currentAnsprechpartner->getFunktion());
            $tplAnsprechpartnerDS->replace("Name", $currentAnsprechpartner->getAnzeigename());
            $tplAnsprechpartnerDS->replace("Telefon", $currentAnsprechpartner->getTelefon());
            $tplAnsprechpartnerDS->replace("Mobil", $currentAnsprechpartner->getMobil());
            $tplAnsprechpartnerDS->next();
        }
        $tpl->replace("AnsprechpartnerListe", $tplAnsprechpartnerDS->getOutput());

        // Orgel Daten
        $w = Constant::getWindladen();
        $r = Constant::getRegisterTrakturen();
        $s = Constant::getSpieltrakturen();
        $k = Constant::getKoppeln();
        $z = Constant::getZyklus();
        $p = Constant::getPflegevertrag();

        $pflegevertrag = (isset($p[$orgel->getPflegevertrag()]) ? $p[$orgel->getPflegevertrag()] : "N/A");
        $tpl->replace("Erbauer", $orgel->getErbauer());
        $tpl->replace("Baujahr", $orgel->getBaujahr());
        $tpl->replace("Revision", $orgel->getRenoviert());
        $tpl->replace("AnzahlManuale", $orgel->getAnzahlManuale());
        $tpl->replace("AnzahlRegister", $orgel->getRegisterAnzahl());
        $tpl->replace("Pflegevertrag", $pflegevertrag);
        $tpl->replace("Zyklus", $z[$orgel->getZyklus()]);
        $tpl->replace("Temperatur", "?");
        $tpl->replace("Stimmtonhoehe", $orgel->getStimmton());
        $tpl->replace("StimmungNach", $orgel->getStimmung());
        $tpl->replace("Koppeln", $k[$orgel->getKoppelID()]);
        $tpl->replace("Windladen", $w[$orgel->getWindladeID()]);
        $tpl->replace("Registertraktur", $r[$orgel->getRegistertrakturID()]);
        $tpl->replace("Spieltraktur", $s[$orgel->getSpieltrakturID()]);

        $tpl->replace("Tonumfang", $orgel->getGroesseM1());
        $tpl->replace("Pedal", $orgel->getGroesseM6());

        $tpl->replace("WinddruckManual1", $orgel->getWinddruckM1());
        $tpl->replace("WinddruckManual2", $orgel->getWinddruckM2());
        $tpl->replace("WinddruckManual3", $orgel->getWinddruckM3());
        $tpl->replace("WinddruckManual4", $orgel->getWinddruckM4());
        $tpl->replace("WinddruckManual5", $orgel->getWinddruckM5());
        $tpl->replace("WinddruckPedal", $orgel->getWinddruckM6());

        $tpl->replace("AllgemeineAnmerkungen", $orgel->getAnmerkung());
        $tpl->replace("NotwendigeMassnahmen", $orgel->getMassnahmen());
        $tpl->replace("Bemerkung", "???"); // Wo kommt die her?

        // Wartungen
        $stimmungen = Constant::getStimmung();

        $tplWartungenDS = new BufferedTemplate("auftragsbogen_wartungends.tpl");
        foreach($alleWartungen as $currentWartung) {
            $b = new Benutzer($currentWartung->getMitarbeiterId1());
            $benutzername = ($b->getBenutzername() != "" ? $b->getBenutzername() : "Unbekannt");
            $temperatur = ($currentWartung->getTemperatur() != "" ? $currentWartung->getTemperatur() . " °C" : "");
            $luftfeuchte = ($currentWartung->getLuftfeuchtigkeit() != "" ? $currentWartung->getLuftfeuchtigkeit() . " %" : "");
            $stimmton = ($currentWartung->getStimmtonHoehe() != "" ? $currentWartung->getStimmtonHoehe() . " Hz" : "");
            $stimmung = $stimmungen[$currentWartung->getStimmung()];

            $tplWartungenDS->replace("Datum", $currentWartung->getDatum(true));
            $tplWartungenDS->replace("Benutzername", $benutzername);
            $tplWartungenDS->replace("Temperatur", $temperatur);
            $tplWartungenDS->replace("Luftfeuchte", $luftfeuchte);
            $tplWartungenDS->replace("Tonhoehe", $stimmton);
            $tplWartungenDS->replace("Stimmung", $stimmung);
            $tplWartungenDS->next();
        }
        $tpl->replace("WartungsListe", $tplWartungenDS->getOutput());

        // Vorzunehmende Arbeiten
        $tpl->replace("Werk", "");
        $tpl->replace("Ton", "");
        $tpl->anzeigen();
        $this->pdf->loadHtml($tpl->getOutput());
    }

    public function Output($dest = '', $name = '', $utf8 = true) {
        // Render the HTML as PDF
        $this->pdf->render();

        // Output the generated PDF to Browser
        //$this->pdf->stream($name);
    }
}
