<?php

/**
 * @author swatermeyer
 * @since 26.02.2009
 */
class UrlaubsBuchung extends SimpleDatabaseStorageObjekt
{

    private $tageVorher;

    private $tageGebucht;

    private $tageNachher;

    private $bemerkung;

    public function __construct($iID = 0)
    {
        parent::__construct($iID, "be_id", "benutzer", "be_");
    }

    protected function laden()
    {
        $rs = $this->result;
        $this->setTageVorher($rs['ub_tage_vorher']);
        $this->setTageGebucht($rs['ub_tage_gebucht']);
        $this->setTageNachher($rs['ub_tage_nachher']);
        $this->setBemerkung($rs['ub_bemekerung']);
    }

    protected function generateHashtable()
    {
        $ht = new HashTable();
        
        $ht->add("ub_tage_vorher", $this->getTageVorher());
        $ht->add("ub_tage_nachher", $this->getTageNachher());
        $ht->add("ub_tage_gebucht", $this->getTageGebucht());
        $ht->add("ub_bemerkung", $this->getBemerkung());
        
        return $ht;
    }

    public function getBemerkung()
    {
        return $this->bemerkung;
    }

    public function getTageGebucht()
    {
        return $this->tageGebucht;
    }

    public function getTageNachher()
    {
        return $this->tageNachher;
    }

    public function getTageVorher()
    {
        return $this->tageVorher;
    }

    public function setBemerkung($bemerkung)
    {
        $this->bemerkung = $bemerkung;
    }

    public function setTageGebucht($tageGebucht)
    {
        $this->tageGebucht = $tageGebucht;
    }

    public function setTageNachher($tageNachher)
    {
        $this->tageNachher = $tageNachher;
    }

    public function setTageVorher($tageVorher)
    {
        $this->tageVorher = $tageVorher;
    }
}
?>