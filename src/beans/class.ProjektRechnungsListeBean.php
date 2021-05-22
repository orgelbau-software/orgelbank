<?php

class ProjektRechnungsListeBean extends ProjektRechnung
{

    private $mProjektBezeichnung;

    private $mAufgabenBezeichnung;

    /**
     *
     * @return the $mProjektBezeichnung
     */
    public function getProjektBezeichnung()
    {
        return $this->mProjektBezeichnung;
    }

    /**
     *
     * @return the $mAufgabenBezeichnung
     */
    public function getAufgabenBezeichnung()
    {
        return $this->mAufgabenBezeichnung;
    }

    /**
     *
     * @param field_type $mProjektBezeichnung            
     */
    public function setProjektBezeichnung($mProjektBezeichnung)
    {
        $this->mProjektBezeichnung = $mProjektBezeichnung;
    }

    /**
     *
     * @param field_type $mAufgabenBezeichnung            
     */
    public function setAufgabenBezeichnung($mAufgabenBezeichnung)
    {
        $this->mAufgabenBezeichnung = $mAufgabenBezeichnung;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see SimpleDatabaseStorageObjekt::doLoadFromArray()
     */
    public function doLoadFromArray($rs)
    {
        parent::doLoadFromArray($rs);
        $this->setProjektBezeichnung($rs['proj_bezeichnung']);
        $this->setAufgabenBezeichnung($rs['au_bezeichnung']);
    }
}
?>