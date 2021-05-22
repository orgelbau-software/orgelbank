<?php

class ProjektMaterialRechnungenAction implements GetRequestHandler, PostRequestHandler

{

    private $suchbegriff = "";

    /**
     *
     * {@inheritdoc}
     *
     * @see GetRequestHandler::validateGetRequest()
     */
    public function validateGetRequest()
    {
        return true;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see GetRequestHandler::handleInvalidGet()
     */
    public function handleInvalidGet()
    {
        // TODO Auto-generated method stub
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see GetRequestHandler::prepareGet()
     */
    public function prepareGet()
    {
        $this->suchbegriff = "";
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see GetRequestHandler::executeGet()
     */
    public function executeGet()
    {
        $tpl = new Template("projekt_material_rechnungen.tpl");
        $tpl->replace("Suchbegriff", "");
        
        $sqlClause = "";
        if($this->suchbegriff != "") {
            $sqlClause .= " AND ( ";
            $sqlClause .=" proj_bezeichnung LIKE '%".$this->suchbegriff."%'"; 
            $sqlClause .=" OR au_bezeichnung LIKE '%".$this->suchbegriff."%'"; 
            $sqlClause .=" OR pr_lieferant LIKE '%".$this->suchbegriff."%'"; 
            $sqlClause .=" OR pr_nummer LIKE '%".$this->suchbegriff."%'"; 
            $sqlClause .=" OR pr_kommentar LIKE '%".$this->suchbegriff."%'"; 
                
            $sqlClause .= ") ";
        }
        
        $sqlClause .= "ORDER BY pr_datum DESC";
        $all = ProjektRechnungUtilities::getAlleProjektRechnungen($sqlClause);
        
        $tplDS = new BufferedTemplate("projekt_material_rechnungen_ds.tpl", "CSS", "td1", "td2");
        foreach ($all as $eintrag) {
            $tplDS->replace("Datum", $eintrag->getDatum(true));
            $tplDS->replace("Nummer", $eintrag->getNummer());
            $tplDS->replace("Lieferant", $eintrag->getLieferant());
            $tplDS->replace("Betrag", WaehrungUtil::formatDoubleToWaehrung($eintrag->getBetrag()));
            $tplDS->replace("Kommentar", $eintrag->getKommentar());
            $tplDS->replace("Kostenstelle", $eintrag->getAufgabenBezeichnung());
            $tplDS->replace("Projekt", $eintrag->getProjektBezeichnung());
            $tplDS->replace("ProjektID", $eintrag->getProjektId());
            $tplDS->next();
        }
        $tpl->replace("Content", $tplDS->getOutput());
        
        return $tpl;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see PostRequestHandler::preparePost()
     */
    public function preparePost()
    {
        if (isset($_POST['suchbegriff'])) {
            $this->suchbegriff = trim($_POST['suchbegriff']);
        }
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see PostRequestHandler::executePost()
     */
    public function executePost()
    {
        return $this->executeGet();
    }
}
