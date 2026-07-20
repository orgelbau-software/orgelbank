<?php
use horstoeko\zugferd\ZugferdDocumentBuilder;
use horstoeko\zugferd\ZugferdProfiles;
use horstoeko\zugferd\codelists\ZugferdUnitCodes;

class XRechnungsAction  implements GetRequestHandler
{

    /**
     * 
     * @var int
     */
    private $iRechnungsID;

    public function __construct() {}


    public function validateGetRequest()
    {
        // return isset($_GET['gid']);
        return true;
    }

    public function handleInvalidGet()
    {
        $htmlStatus = new HTMLRedirect();
        $htmlStatus->setLink("index.php?page=1&do=1");
        return $htmlStatus;
    }

    public function prepareGet()
    {
        $this->iRechnungsID = intval($_GET['id']);
    }

    public function executeGet()
    {
        $tpl = new Template("rechnung_xrechnung_anzeige.tpl");

        $rechnung = new PflegeRechnung($this->iRechnungsID);
        $rechnungsDatum = DateTime::createFromFormat("d.m.Y", $rechnung->getDatum(true));
        $gemeinde = new Gemeinde($rechnung->getGemeindeID());
        $gemeindeRechnungsAnschrift = $gemeinde->getRechnungAdresse();

        $mwstSatz = MWST_SATZ * 100;

        $unsereFirma = new Ansprechpartner(1);
        $unsereSteuerNummer = $unsereFirma->getAndere();
        $unsereUmsatzsteuerIdentifikationsnummer = "DE123456789";
        $unsereSteuerNummer = "123/456/78901";
        $unsereIBAN = "DE12500105170648489890";
        $unserKontoinhaber = $unsereFirma->getAnzeigeName();

        // TODO
        $laenderCodeISO_3166_1_Alpha_2  = "DE";
        $waehrung = "EUR";
        $leitwegId = "KundeLeitwegID"; // Pro Rechnung oder pro Kunde?
        $lieferantenNummer = ""; // Leer? Nummer pro Kunde?
        
        $kundeAnsprechpartnerName = "Heinz Mueller";
        $kundeAnsprechpartnerEMail ="heinz.mueller@kirche.com";
        $kundeAnsprechpartnerAbteilung = "Buchhaltung"; // Eingeben?
        $kundeAnsprechpartnerTelefon = "0173/7556922";
        $kundeAnsprechpartnerFax = "0190/1990";
        
        
        $document = ZugferdDocumentBuilder::createNew(ZugferdProfiles::PROFILE_XRECHNUNG_3);

        // Allgemein Daten
        $document->setDocumentInformation($rechnung->getNummer(), "380", $rechnungsDatum, $waehrung);
        $document->addDocumentPaymentTerm("Zahlbar innerhalb von 14 Tagen ohne Abzuege.", new DateTime($rechnung->getZieldatum(false)));
        $document->addDocumentPaymentMean(58, null, null, null, null, null, $unsereIBAN, $unserKontoinhaber, null, null);


        // Verkaeufer - Wir
        $document->setDocumentSeller($unsereFirma->getAnzeigeName(), $lieferantenNummer);
        //$document->addDocumentSellerGlobalId("4000001123452", "0088");
        $document->addDocumentSellerTaxRegistration("FC", $unsereSteuerNummer);
        $document->addDocumentSellerTaxRegistration("VA", $unsereUmsatzsteuerIdentifikationsnummer);
        $document->setDocumentSellerAddress($unsereFirma->getAdresse()->getStrasse(), "", "", $unsereFirma->getAdresse()->getPlz(), $unsereFirma->getAdresse()->getOrt(), $laenderCodeISO_3166_1_Alpha_2);
        $document->setDocumentSellerContact($unsereFirma->getAnzeigeName(), "Buchhaltung", $unsereFirma->getTelefon(), $unsereFirma->getMobil(), $unsereFirma->getEmail());
        
        // Kaeufer - Kunde
        $document->setDocumentBuyer($gemeinde->getRGemeinde(), $gemeinde->getKundenNr());
        $document->setDocumentBuyerReference($leitwegId);
        $document->setDocumentBuyerAddress($gemeindeRechnungsAnschrift->getStrasse(), "", "", $gemeindeRechnungsAnschrift->getPlz(), $gemeindeRechnungsAnschrift->getOrt(), $laenderCodeISO_3166_1_Alpha_2);
        $document->setDocumentBuyerContact($kundeAnsprechpartnerName, $kundeAnsprechpartnerAbteilung, $kundeAnsprechpartnerTelefon, $kundeAnsprechpartnerFax, $kundeAnsprechpartnerEMail);

        // Einleitungstext
        $document->addDocumentNote($rechnung->getText1());
        $document->addDocumentNote($rechnung->getText2());

      

        // Positionen
      //  $col = RechnungsPositionUtilities::getRechnungsPositionen($rechnung->getID(), $rechnung->getType());
     //   $iPos = 1;
    //    foreach ($col as $currentPos) {
   //         if($currentPos->getText() != "") {
  //              $document->addNewPosition($iPos ++);
 //               $document->setDocumentPositionNote($currentPos->getText());
//            }
//        }
        
        // Summen
        $nettobetrag = $rechnung->getNettoBetrag();
        $bruttobetrag = $rechnung->getBruttoBetrag();
        $mwst = $rechnung->getMwSt();
        $document->addDocumentTax("S", "VAT", $nettobetrag, $mwst, $mwstSatz);
        $document->setDocumentSummation($bruttobetrag, $bruttobetrag, $nettobetrag, 0.0, 0.0, $nettobetrag, $mwst, null, 0.0);

        // Dummy Position
        $document->addNewPosition(1);
        $document->setDocumentPositionProductDetails('Allgemeine Dienstleistung', 'Dienstleistung gemäß Auftrag', null);
        $document->setDocumentPositionQuantity(1, ZugferdUnitCodes::REC20_PIECE);
        $document->setDocumentPositionNetPrice($nettobetrag);
        $document->addDocumentPositionTax("S", "VAT", $mwstSatz);
        $document->setDocumentPositionLineSummation($nettobetrag);

        // Ausgabe
        $tpl->replace("RechnungsInhalt", htmlspecialchars($document->getContent(), ENT_QUOTES | ENT_XML1, 'UTF-8'));
        return $tpl;
    }
}
