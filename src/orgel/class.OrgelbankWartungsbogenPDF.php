<?php

class OrgelbankWartungsbogenPDF extends WartungsbogenPDF
{

    public function __construct()
    {
        parent::__construct();
    }

    public function header()
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
        
        $text = "Tel. " . $a->getTelefon();
        if($a->getFax() != "") {
            $text .= ", Fax " . $a->getFax();
        }
        
        $meta->add($text);
        $this->arHeaderMeta = $meta;
        
        foreach ($meta as $key => $val) {
            $meta[$key] = ($val == null ? "" : $val);
        }
        parent::Header();
    }

    public function footer()
    {
        parent::Footer();
        $a = AnsprechpartnerUtilities::getKunde();
        $this->SetAuthor($a->getVorname() . " " . $a->getNachname());
        
        $this->SetXY(10, 280);
        $this->SetFont('Arial', '', 7);
        
        // http://www.php-einfach.de/php_code_verschluesseln.php
        $this->Cell(190, 10, "Installation: " . INSTALLATION_NAME, 0, "L", "C");
    }
}
