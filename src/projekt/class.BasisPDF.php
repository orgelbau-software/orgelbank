<?php
// include_once ROOTDIR . 'lib/tFPDF/tfpdf.php';
// include_once ROOTDIR . '/lib/tFPDF/tfpdfbookmark.php';
use setasign\Fpdi\Tfpdf\Fpdi;

abstract class BasisPDF  extends Fpdi 
{

    private $utf8 = true;

    protected $iRandLinks = 10;

    protected $cellheight = 4;

    protected $font = "DejaVu";

    protected $fontBold = "DejaVu B";

    protected $iTrennstrichLaenge = 185;

    protected $arHeaderMeta;
    
    protected $fontSizeNormal = 10;

    /**
     * @var DB
     */
    protected $mDBInstance;

    function __construct()
    {
        parent::__construct();
        $this->mDBInstance = DB::getInstance();
        
        $this->AddFont($this->font, '', 'DejaVuSansCondensed.ttf', true);
        $this->AddFont($this->fontBold, '', 'DejaVuSansCondensed-Bold.ttf', true);
    }

    function Header()
    {
        $a = AnsprechpartnerUtilities::getKunde();
        
        $meta = new ArrayList();
        $meta->add(htmlspecialchars_decode($a->getFunktion()));
        $meta->add($a->getAdresse()
            ->getStrasse() . " " . $a->getAdresse()
            ->getHausnummer());
        $meta->add(ConstantLoader::getPDFUntertext1());
        $meta->add($a->getAdresse()
            ->getPlz() . " " . $a->getAdresse()
            ->getOrt());
        $meta->add(ConstantLoader::getPDFUntertext2());
        $meta->add("Tel. " . $a->getTelefon() . ", Fax " . $a->getFax());
        $this->arHeaderMeta = $meta;
        
        foreach ($meta as $key => $val) {
            $meta[$key] = $val;
        }
        
        $cellsize = 70;
        
        // Rand setzen
        $this->SetMargins($this->iRandLinks, 10, 10);
        $this->ln(1);
        
        $this->activateFontNormal();
        ;
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

    public abstract function getDocumentFilename();

    public function anzeigen()
    {
        echo $this->Output($this->getDocumentFilename(), "I");
    }

    private function zeichneTrennstrich()
    {
        $this->Cell($this->iTrennstrichLaenge, 0, '', 1, 0, "C");
        $this->ln(3);
    }

    function Footer()
    {
        $this->SetSubject("Stundenzettel", $this->utf8);
        $this->SetTitle("Stundenzettel", $this->utf8);
        $this->SetCreator("Stundenzettel von watermeyer IT", $this->utf8);
    }

    protected function activateFontNormal()
    {
        $this->SetFont($this->font, '', $this->fontSizeNormal);
    }

    protected function activateFontBold()
    {
        $this->SetFont($this->fontBold, '', $this->fontSizeNormal);
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
}