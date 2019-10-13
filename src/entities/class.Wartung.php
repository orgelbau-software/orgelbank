<?php

class Wartung extends SimpleDatabaseStorageObjekt
{

    protected $mitarbeiterId1;

    protected $mitarbeiterId2;

    protected $mitarbeiterId3;

    protected $mitarbeiterIstStd1;

    protected $mitarbeiterIstStd2;

    protected $mitarbeiterIstStd3;

    protected $mitarbeiterFaktStd1;

    protected $mitarbeiterFaktStd2;

    protected $mitarbeiterFaktStd3;

    protected $tastenhalter;

    protected $material;

    protected $orgelId;

    protected $datum;

    protected $bemerkung;

    protected $temperatur;

    protected $luftfeuchtigkeit;

    protected $stimmtonHoehe;

    protected $stimmung;

    protected $abrechnungsArtId;

    public function __construct($iID = 0, $primaryKey = "w_id", $tableName = "wartung", $tablePrefix = "w_")
    {
        parent::__construct($iID, $primaryKey, $tableName, $tablePrefix);
    }

    protected function generateHashtable()
    {
        $ht = new HashTable();
        
        $ht->add("o_id", $this->getOrgelId());
        $ht->add("m_id_1", $this->getMitarbeiterId1());
        $ht->add("m_id_2", $this->getMitarbeiterId2());
        $ht->add("m_id_3", $this->getMitarbeiterId3());
        $ht->add($this->tablePrefix . "ma1_iststd", $this->getMitarbeiterIstStd1());
        $ht->add($this->tablePrefix . "ma2_iststd", $this->getMitarbeiterIstStd2());
        $ht->add($this->tablePrefix . "ma3_iststd", $this->getMitarbeiterIstStd3());
        $ht->add($this->tablePrefix . "ma1_faktstd", $this->getMitarbeiterFaktStd1());
        $ht->add($this->tablePrefix . "ma2_faktstd", $this->getMitarbeiterFaktStd2());
        $ht->add($this->tablePrefix . "ma3_faktstd", $this->getMitarbeiterFaktStd3());
        $ht->add($this->tablePrefix . "tastenhalter", $this->getTastenhalter());
        $ht->add($this->tablePrefix . "abrechnungsart", $this->getAbrechnungsArtId());
        $ht->add($this->tablePrefix . "material", $this->getMaterial());
        $ht->add($this->tablePrefix . "bemerkung", $this->getBemerkung());
        $ht->add($this->tablePrefix . "datum", $this->getDatum());
        $ht->add($this->tablePrefix . "temperatur", $this->getTemperatur());
        $ht->add($this->tablePrefix . "luftfeuchtigkeit", $this->getLuftfeuchtigkeit());
        $ht->add($this->tablePrefix . "stimmton", $this->getStimmtonHoehe());
        $ht->add($this->tablePrefix . "stimmung", $this->getStimmung());
        $ht->add($this->tablePrefix . "changeby", $this->getChangeBy());
        $this->setChanged(true);
        return $ht;
    }

    protected function laden()
    {
        $rs = $this->result;
        $this->setMitarbeiterId1($rs["m_id_1"]);
        $this->setMitarbeiterId2($rs["m_id_2"]);
        $this->setMitarbeiterId3($rs["m_id_3"]);
        $this->setMitarbeiterIstStd1($rs[$this->tablePrefix . "ma1_iststd"]);
        $this->setMitarbeiterIstStd2($rs[$this->tablePrefix . "ma2_iststd"]);
        $this->setMitarbeiterIstStd3($rs[$this->tablePrefix . "ma3_iststd"]);
        $this->setMitarbeiterFaktStd1($rs[$this->tablePrefix . "ma1_faktstd"]);
        $this->setMitarbeiterFaktStd2($rs[$this->tablePrefix . "ma2_faktstd"]);
        $this->setMitarbeiterFaktStd3($rs[$this->tablePrefix . "ma3_faktstd"]);
        $this->setTastenhalter($rs[$this->tablePrefix . "tastenhalter"]);
        $this->setAbrechnungsArtId($rs[$this->tablePrefix . "abrechnungsart"]);
        $this->setMaterial($rs[$this->tablePrefix . "material"]);
        $this->setBemerkung($rs[$this->tablePrefix . "bemerkung"]);
        $this->setDatum($rs[$this->tablePrefix . "datum"]);
        $this->setOrgelId($rs['o_id']);
        $this->setTemperatur($rs[$this->tablePrefix . "temperatur"]);
        $this->setLuftfeuchtigkeit($rs[$this->tablePrefix . "luftfeuchtigkeit"]);
        $this->setStimmtonHoehe($rs[$this->tablePrefix . "stimmton"]);
        $this->setStimmung($rs[$this->tablePrefix . "stimmung"]);
        $this->setChangeBy($rs[$this->tablePrefix . "changeby"]);
    }

    public function getDatum($formatiert = false)
    {
        if ($formatiert) {
            return date("d.m.Y", strtotime($this->datum));
        }
        return $this->datum;
    }

    public function getBemerkung()
    {
        return $this->bemerkung;
    }

    public function getOrgelId()
    {
        return $this->orgelId;
    }

    public function getStimmtonHoehe()
    {
        return $this->stimmtonHoehe;
    }

    public function getTemperatur()
    {
        return $this->temperatur;
    }

    public function setBemerkung($bemerkung)
    {
        $this->bemerkung = $bemerkung;
    }

    public function setDatum($datum)
    {
        $this->datum = $datum;
    }

    public function setOrgelId($orgelId)
    {
        $this->orgelId = $orgelId;
    }

    public function setStimmtonHoehe($stimmtonHoehe)
    {
        $this->stimmtonHoehe = $stimmtonHoehe;
    }

    public function setTemperatur($temperatur)
    {
        $this->temperatur = $temperatur;
    }

    public function getLuftfeuchtigkeit()
    {
        return $this->luftfeuchtigkeit;
    }

    public function setLuftfeuchtigkeit($luftfeuchtigkeit)
    {
        $this->luftfeuchtigkeit = $luftfeuchtigkeit;
    }

    public function getMitarbeiterId1()
    {
        return $this->mitarbeiterId1;
    }

    public function getMitarbeiterId2()
    {
        return $this->mitarbeiterId2;
    }

    public function getMitarbeiterId3()
    {
        return $this->mitarbeiterId3;
    }

    public function getMitarbeiterIstStd1()
    {
        return $this->mitarbeiterIstStd1;
    }

    public function getMitarbeiterIstStd2()
    {
        return $this->mitarbeiterIstStd2;
    }

    public function getMitarbeiterIstStd3()
    {
        return $this->mitarbeiterIstStd3;
    }

    public function getTastenhalter()
    {
        return $this->tastenhalter;
    }

    public function getMaterial()
    {
        return $this->material;
    }

    public function getStimmung()
    {
        return $this->stimmung;
    }

    public function getAbrechnungsArtId()
    {
        return $this->abrechnungsArtId;
    }

    public function setMitarbeiterId1($mitarbeiterId1)
    {
        $this->mitarbeiterId1 = $mitarbeiterId1;
    }

    public function setMitarbeiterId2($mitarbeiterId2)
    {
        $this->mitarbeiterId2 = $mitarbeiterId2;
    }

    public function setMitarbeiterId3($mitarbeiterId3)
    {
        $this->mitarbeiterId3 = $mitarbeiterId3;
    }

    public function setMitarbeiterIstStd1($mitarbeiterIstStd1)
    {
        $this->mitarbeiterIstStd1 = $mitarbeiterIstStd1;
    }

    public function setMitarbeiterIstStd2($mitarbeiterIstStd2)
    {
        $this->mitarbeiterIstStd2 = $mitarbeiterIstStd2;
    }

    public function setMitarbeiterIstStd3($mitarbeiterIstStd3)
    {
        $this->mitarbeiterIstStd3 = $mitarbeiterIstStd3;
    }

    public function setTastenhalter($tastenhalter)
    {
        $this->tastenhalter = $tastenhalter;
    }

    public function setMaterial($material)
    {
        $this->material = $material;
    }

    public function setStimmung($stimmung)
    {
        $this->stimmung = $stimmung;
    }

    public function setAbrechnungsArtId($abrechnungsArtId)
    {
        $this->abrechnungsArtId = $abrechnungsArtId;
    }

    public function getMitarbeiterFaktStd1()
    {
        return $this->mitarbeiterFaktStd1;
    }

    public function getMitarbeiterFaktStd2()
    {
        return $this->mitarbeiterFaktStd2;
    }

    public function getMitarbeiterFaktStd3()
    {
        return $this->mitarbeiterFaktStd3;
    }

    public function setMitarbeiterFaktStd1($mitarbeiterFaktStd1)
    {
        $this->mitarbeiterFaktStd1 = $mitarbeiterFaktStd1;
    }

    public function setMitarbeiterFaktStd2($mitarbeiterFaktStd2)
    {
        $this->mitarbeiterFaktStd2 = $mitarbeiterFaktStd2;
    }

    public function setMitarbeiterFaktStd3($mitarbeiterFaktStd3)
    {
        $this->mitarbeiterFaktStd3 = $mitarbeiterFaktStd3;
    }
}
?>