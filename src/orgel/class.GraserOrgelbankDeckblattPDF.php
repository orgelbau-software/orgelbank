<?php

class GraserOrgelbankDeckblattPDF extends OrgelbankDeckblattPDF
{

    public function __construct()
    {
        parent::__construct();
    }
    
    protected function activateFontColorRed() 
    {
        $this->activateFontColorBlack();
    }
    
    protected function activateFontColorBlue() 
    {
        $this->activateFontColorBlack();
    }
    
    protected function activateFontColorGreen() 
    {
        $this->activateFontColorBlack();
    }
    
    
    protected function getFontBold()
    {
        return $this->font;
    }
    
    protected function getTrennstrichLaenge()
    {
        return 150;
    }
    
    protected function addBezirkUndOrgelID($pOrgelID, $pBezirk) 
    {
        $tmpX = $this->getX();
        $tmpY = $this->getY();
        $this->SetXY(170, 29);
        $this->activateFontNormal();
        $this->activateFontColorRed();
        $this->Cell($this->getDefaultCellSize(), 8, "Bezirk: " . $pBezirk, 0, 0, "L");
        $this->SetXY(170, 36);
        $this->activateFontColorBlue();
        $this->Cell($this->getDefaultCellSize(), 10, "Orgel:  " . $pOrgelID, 0, 0, "L");
        $this->activateFontNormal();
        $this->activateFontColorBlack();
        
        $this->setXY($tmpX, $tmpY);
    }
}
?>