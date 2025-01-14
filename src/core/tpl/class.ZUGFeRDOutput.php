<?php
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;

use horstoeko\zugferd\ZugferdDocumentBuilder;
use horstoeko\zugferd\ZugferdProfiles;


class ZUGFeRDOutput extends MSWordOutput
{

    public function __construct()
    {
        // nothing to do with the template
    }

    public function save($pPfad) : string
    {
        $this->template->saveAs($pPfad);
    


        // Make sure you have `dompdf/dompdf` in your composer dependencies.
        Settings::setPdfRendererName(Settings::PDF_RENDERER_DOMPDF);
        // Any writable directory here. It will be ignored.
        Settings::setPdfRendererPath('.');

        $pdfPfad = $pPfad.".pdf";
        $xmlPfad = $pPfad.".xml";
        $phpWord = IOFactory::load($pPfad, 'Word2007');
        $phpWord->save($pdfPfad, 'PDF');

        $document = ZugferdDocumentBuilder::CreateNew(ZugferdProfiles::PROFILE_EN16931);
        $document->setDocumentInformation("471102", "380", \DateTime::createFromFormat("Ymd", "20180305"), "EUR");
        $document->setDocumentSeller("Lieferant GmbH", "549910");
        $document->addDocumentSellerGlobalId("4000001123452", "0088");
        $document->addDocumentSellerTaxRegistration("FC", "201/113/40209");
        $document->addDocumentSellerTaxRegistration("VA", "DE123456789");
        $document->setDocumentSellerAddress("Lieferantenstraße 20", "", "", "80333", "München", "DE");
        $document->setDocumentSellerContact("Heinz Müller", "Buchhaltung", "+49-111-2222222", "+49-111-3333333","info@lieferant.de");

        $document->setDocumentBuyer("Kunden AG Mitte", "GE2020211");
        $document->setDocumentBuyerReference("34676-342323");
        $document->setDocumentBuyerAddress("Kundenstraße 15", "", "", "69876", "Frankfurt", "DE");

        $document->setDocumentSummation(529.87, 529.87, 473.00, 0.0, 0.0, 473.00, 56.87, null, 0.0);

        $document->writeFile($xmlPfad);
        return $pPfad;
    }

}