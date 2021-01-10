<?php

class Orgel extends SimpleDatabaseStorageObjekt
{

    private $ostID;

    private $gemeindeId;

    private $baujahr;

    private $erbauer;

    private $renoviert;

    private $renovierer;

    private $windladeID;

    private $koppelID;

    private $registertrakturID;

    private $spieltrakturID;

    private $anmerkung;

    private $pflegevertrag;

    private $hauptstimmung;

    private $zyklus;

    private $massnahmen;

    private $manual1;

    private $manual2;

    private $manual3;

    private $manual4;

    private $manual5;

    private $winddruckM1;

    private $winddruckM2;

    private $winddruckM3;

    private $winddruckM4;

    private $winddruckM5;

    private $winddruckM6;

    private $groesseM1;

    private $groesseM2;

    private $groesseM3;

    private $groesseM4;

    private $groesseM5;

    private $groesseM6;

    private $pedal;

    private $stimmung;

    private $aktiv;

    private $registerAnzahl;

    private $letztePflege;
    
    private $kostenHauptstimmung;
    
    private $kostenTeilstimmung;

    // Konstruktor
    public function __construct($iID = 0, $primaryKey = "o_id", $tableName = "orgel", $tablePrefix = "o_")
    {
        parent::__construct($iID, $primaryKey, $tableName, $tablePrefix);
    }

    protected function generateHashtable()
    {
        $ht = new HashTable();
        
        $ht->add("ost_id", $this->getOstID());
        $ht->add("o_baujahr", $this->getBaujahr());
        $ht->add("o_erbauer", $this->getErbauer());
        $ht->add("o_renoviert", $this->getRenoviert());
        $ht->add("o_renovierer", $this->getRenovierer());
        $ht->add("ow_id", $this->getWindladeID());
        $ht->add("os_id", $this->getSpieltrakturID());
        $ht->add("or_id", $this->getRegistertrakturID());
        $ht->add("ok_id", $this->getKoppelID());
        $ht->add("o_anmerkung", $this->getAnmerkung());
        $ht->add("o_pflegevertrag", $this->getPflegevertrag());
        $ht->add("o_hauptstimmung", $this->getHauptstimmung());
        $ht->add("o_zyklus", $this->getZyklus());
        $ht->add("o_massnahmen", $this->getMassnahmen());
        $ht->add("o_manual1", $this->getManual1());
        $ht->add("o_manual2", $this->getManual2());
        $ht->add("o_manual3", $this->getManual3());
        $ht->add("o_manual4", $this->getManual4());
        $ht->add("o_manual5", $this->getManual5());
        $ht->add("o_m1wd", $this->getWinddruckM1());
        $ht->add("o_m2wd", $this->getWinddruckM2());
        $ht->add("o_m3wd", $this->getWinddruckM3());
        $ht->add("o_m4wd", $this->getWinddruckM4());
        $ht->add("o_m5wd", $this->getWinddruckM5());
        $ht->add("o_m6wd", $this->getWinddruckM6());
        $ht->add("o_m1groesse", $this->getGroesseM1());
        $ht->add("o_m2groesse", $this->getGroesseM2());
        $ht->add("o_m3groesse", $this->getGroesseM3());
        $ht->add("o_m4groesse", $this->getGroesseM4());
        $ht->add("o_m5groesse", $this->getGroesseM5());
        $ht->add("o_m6groesse", $this->getGroesseM6());
        $ht->add("o_pedal", $this->getPedal());
        $ht->add("o_stimmung", $this->getStimmung());
        $ht->add("o_aktiv", $this->getAktiv());
        $ht->add("o_anzahlregister", $this->getRegisterAnzahl());
        $ht->add("g_id", $this->getGemeindeId());
        $ht->add("o_letztepflege", $this->getLetztePflege());
        $ht->add("o_kostenhauptstimmung", $this->getKostenHauptstimmung());
        $ht->add("o_kostenteilstimmung", $this->getKostenTeilstimmung());
        
        return $ht;
    }

    protected function laden()
    {
        $rs = $this->result;
        $this->setID($rs['o_id']);
        $this->setGemeindeId($rs['g_id']);
        $this->setOstID($rs['ost_id']);
        $this->setBaujahr($rs['o_baujahr']);
        $this->setErbauer($rs['o_erbauer']);
        $this->setRenovierer($rs['o_renovierer']);
        $this->setRenoviert($rs['o_renoviert']);
        $this->setWindladeID($rs['ow_id']);
        $this->setSpieltrakturID($rs['os_id']);
        $this->setRegistertrakturID($rs['or_id']);
        $this->setKoppelID($rs['ok_id']);
        $this->setAnmerkung($rs['o_anmerkung']);
        $this->setPflegevertrag($rs['o_pflegevertrag']);
        $this->setHauptstimmung($rs['o_hauptstimmung']);
        $this->setZyklus($rs['o_zyklus']);
        $this->setMassnahmen($rs['o_massnahmen']);
        $this->setManual1($rs['o_manual1']);
        $this->setManual2($rs['o_manual2']);
        $this->setManual3($rs['o_manual3']);
        $this->setManual4($rs['o_manual4']);
        $this->setManual5($rs['o_manual5']);
        $this->setWinddruckM1($rs['o_m1wd']);
        $this->setWinddruckM2($rs['o_m2wd']);
        $this->setWinddruckM3($rs['o_m3wd']);
        $this->setWinddruckM4($rs['o_m4wd']);
        $this->setWinddruckM5($rs['o_m5wd']);
        $this->setWinddruckM6($rs['o_m6wd']);
        $this->setGroesseM1($rs['o_m1groesse']);
        $this->setGroesseM2($rs['o_m2groesse']);
        $this->setGroesseM3($rs['o_m3groesse']);
        $this->setGroesseM4($rs['o_m4groesse']);
        $this->setGroesseM5($rs['o_m5groesse']);
        $this->setGroesseM6($rs['o_m6groesse']);
        $this->setPedal($rs['o_pedal']);
        $this->setStimmung($rs['o_stimmung']);
        $this->setAktiv($rs['o_aktiv']);
        $this->setRegisterAnzahl($rs['o_anzahlregister']);
        $this->setLetztePflege($rs['o_letztepflege']);
        $this->setKostenHauptstimmung($rs['o_kostenhauptstimmung']);
        $this->setKostenTeilstimmung($rs['o_kostenteilstimmung']);
        
        $this->isPersistent(true);
    }

    public function loeschen()
    {
        Trace::addStart(__METHOD__);
        $sql = "UPDATE orgel SET o_aktiv = 0 WHERE o_id = " . $this->getID();
        $db = DB::getInstance();
        $db->NonSelectQuery($sql);
        Trace::addExit(__METHOD__);
    }

    public function getAnmerkung()
    {
        return $this->anmerkung;
    }

    public function getBaujahr()
    {
        return $this->baujahr;
    }

    public function getErbauer()
    {
        return $this->erbauer;
    }

    public function getGroesseM1()
    {
        return $this->groesseM1;
    }

    public function getGroesseM2()
    {
        return $this->groesseM2;
    }

    public function getGroesseM3()
    {
        return $this->groesseM3;
    }

    public function getGroesseM4()
    {
        return $this->groesseM4;
    }

    public function getGroesseM5()
    {
        return $this->groesseM5;
    }

    public function getGroesseM6()
    {
        return $this->groesseM6;
    }

    public function getHauptstimmung()
    {
        return $this->hauptstimmung;
    }

    public function getKoppelID()
    {
        return $this->koppelID;
    }

    public function getManual1()
    {
        return $this->manual1;
    }

    public function getManual2()
    {
        return $this->manual2;
    }

    public function getManual3()
    {
        return $this->manual3;
    }

    public function getManual4()
    {
        return $this->manual4;
    }

    public function getManual5()
    {
        return $this->manual5;
    }

    public function getMassnahmen()
    {
        return $this->massnahmen;
    }

    public function getOstID()
    {
        return $this->ostID;
    }

    public function getPedal()
    {
        return $this->pedal;
    }

    public function getPflegevertrag()
    {
        return $this->pflegevertrag;
    }

    public function getRegistertrakturID()
    {
        return $this->registertrakturID;
    }

    public function getRenovierer()
    {
        return $this->renovierer;
    }

    public function getRenoviert()
    {
        return $this->renoviert;
    }

    public function getSpieltrakturID()
    {
        return $this->spieltrakturID;
    }

    public function getStimmung()
    {
        return $this->stimmung;
    }

    public function getWinddruckM1()
    {
        return $this->winddruckM1;
    }

    public function getWinddruckM2()
    {
        return $this->winddruckM2;
    }

    public function getWinddruckM3()
    {
        return $this->winddruckM3;
    }

    public function getWinddruckM4()
    {
        return $this->winddruckM4;
    }

    public function getWinddruckM5()
    {
        return $this->winddruckM5;
    }

    public function getWinddruckM6()
    {
        return $this->winddruckM6;
    }

    public function getWindladeID()
    {
        return $this->windladeID;
    }

    public function getZyklus()
    {
        return $this->zyklus;
    }

    public function setAnmerkung($anmerkung)
    {
        if ($this->anmerkung != $anmerkung) {
            $this->anmerkung = $anmerkung;
            $this->setChanged(true);
        }
    }

    public function setBaujahr($baujahr)
    {
        if ($this->baujahr != $baujahr) {
            $this->baujahr = $baujahr;
            $this->setChanged(true);
        }
    }

    public function setErbauer($erbauer)
    {
        if ($this->erbauer != $erbauer) {
            $this->erbauer = $erbauer;
            $this->setChanged(true);
        }
    }

    public function setGroesseM1($groesseM1)
    {
        if ($this->groesseM1 != $groesseM1) {
            $this->groesseM1 = $groesseM1;
            $this->setChanged(true);
        }
    }

    public function setGroesseM2($groesseM2)
    {
        if ($this->groesseM2 != $groesseM2) {
            $this->groesseM2 = $groesseM2;
            $this->setChanged(true);
        }
    }

    public function setGroesseM3($groesseM3)
    {
        if ($this->groesseM3 != $groesseM3) {
            $this->groesseM3 = $groesseM3;
            $this->setChanged(true);
        }
    }

    public function setGroesseM4($groesseM4)
    {
        if ($this->groesseM4 != $groesseM4) {
            $this->groesseM4 = $groesseM4;
            $this->setChanged(true);
        }
    }

    public function setGroesseM5($groesseM5)
    {
        if ($this->groesseM5 != $groesseM5) {
            $this->groesseM5 = $groesseM5;
            $this->setChanged(true);
        }
    }

    public function setGroesseM6($groesseM6)
    {
        if ($this->groesseM6 != $groesseM6) {
            $this->groesseM6 = $groesseM6;
            $this->setChanged(true);
        }
    }

    public function setHauptstimmung($hauptstimmung)
    {
        if ($this->hauptstimmung != $hauptstimmung) {
            $this->hauptstimmung = $hauptstimmung;
            $this->setChanged(true);
        }
    }

    public function setKoppelID($koppelID)
    {
        if ($this->koppelID != $koppelID) {
            $this->koppelID = $koppelID;
            $this->setChanged(true);
        }
    }

    public function setManual1($manual1)
    {
        if ($this->manual1 != $manual1) {
            $this->manual1 = $manual1;
            $this->setChanged(true);
        }
    }

    public function setManual2($manual2)
    {
        if ($this->manual2 != $manual2) {
            $this->manual2 = $manual2;
            $this->setChanged(true);
        }
    }

    public function setManual3($manual3)
    {
        if ($this->manual3 != $manual3) {
            $this->manual3 = $manual3;
            $this->setChanged(true);
        }
    }

    public function setManual4($manual4)
    {
        if ($this->manual4 != $manual4) {
            $this->manual4 = $manual4;
            $this->setChanged(true);
        }
    }

    public function setManual5($manual5)
    {
        if ($this->manual5 != $manual5) {
            $this->manual5 = $manual5;
            $this->setChanged(true);
        }
    }

    public function setMassnahmen($massnahmen)
    {
        if ($this->massnahmen != $massnahmen) {
            $this->massnahmen = $massnahmen;
            $this->setChanged(true);
        }
    }

    public function setOstID($ostID)
    {
        if ($this->ostID != $ostID) {
            $this->ostID = $ostID;
            $this->setChanged(true);
        }
    }

    public function setPedal($pedal)
    {
        if ($this->pedal != $pedal) {
            $this->pedal = $pedal;
            $this->setChanged(true);
        }
    }

    public function setPflegevertrag($pflegevertrag)
    {
        if ($this->pflegevertrag != $pflegevertrag) {
            $this->pflegevertrag = $pflegevertrag;
            $this->setChanged(true);
        }
    }

    public function setRegister($register)
    {
        if ($this->register != $register) {
            $this->register = $register;
            $this->setChanged(true);
        }
    }

    public function setRegistertrakturID($registertrakturID)
    {
        if ($this->registertrakturID != $registertrakturID) {
            $this->registertrakturID = $registertrakturID;
            $this->setChanged(true);
        }
    }

    public function setRenovierer($renovierer)
    {
        if ($this->renovierer != $renovierer) {
            $this->renovierer = $renovierer;
            $this->setChanged(true);
        }
    }

    public function setRenoviert($renoviert)
    {
        if ($this->renoviert != $renoviert) {
            $this->renoviert = $renoviert;
            $this->setChanged(true);
        }
    }

    public function setSpieltrakturID($spieltrakturID)
    {
        if ($this->spieltrakturID != $spieltrakturID) {
            $this->spieltrakturID = $spieltrakturID;
            $this->setChanged(true);
        }
    }

    public function setStimmung($stimmung)
    {
        if ($this->stimmung != $stimmung) {
            $this->stimmung = $stimmung;
            $this->setChanged(true);
        }
        $this->stimmung = $stimmung;
    }

    public function setTemperatur($temperatur)
    {
        if ($this->temperatur != $temperatur) {
            $this->temperatur = $temperatur;
            $this->setChanged(true);
        }
    }

    public function setWinddruckM1($winddruckM1)
    {
        if ($this->winddruckM1 != $winddruckM1) {
            $this->winddruckM1 = $winddruckM1;
            $this->setChanged(true);
        }
    }

    public function setWinddruckM2($winddruckM2)
    {
        if ($this->winddruckM2 != $winddruckM2) {
            $this->winddruckM2 = $winddruckM2;
            $this->setChanged(true);
        }
    }

    public function setWinddruckM3($winddruckM3)
    {
        if ($this->winddruckM3 != $winddruckM3) {
            $this->winddruckM3 = $winddruckM3;
            $this->setChanged(true);
        }
    }

    public function setWinddruckM4($winddruckM4)
    {
        if ($this->winddruckM4 != $winddruckM4) {
            $this->winddruckM4 = $winddruckM4;
            $this->setChanged(true);
        }
    }

    public function setWinddruckM5($winddruckM5)
    {
        if ($this->winddruckM5 != $winddruckM5) {
            $this->winddruckM5 = $winddruckM5;
            $this->setChanged(true);
        }
    }

    public function setWinddruckM6($winddruckM6)
    {
        if ($this->winddruckM6 != $winddruckM6) {
            $this->winddruckM6 = $winddruckM6;
            $this->setChanged(true);
        }
    }

    public function setWindladeID($windladeID)
    {
        if ($this->windladeID != $windladeID) {
            $this->windladeID = $windladeID;
            $this->setChanged(true);
        }
    }

    public function setZyklus($zyklus)
    {
        if ($this->zyklus != $zyklus) {
            $this->zyklus = $zyklus;
            $this->setChanged(true);
        }
    }

    public function getAktiv()
    {
        return $this->aktiv;
    }

    public function setAktiv($aktiv)
    {
        if ($this->aktiv != $aktiv) {
            $this->aktiv = $aktiv;
            $this->setChanged(true);
        }
    }

    public function getGemeindeId()
    {
        return $this->gemeindeId;
    }

    public function setGemeindeId($gemeindeId)
    {
        $this->gemeindeId = $gemeindeId;
    }

    public function getRegisterAnzahl()
    {
        return $this->registerAnzahl;
    }

    public function setRegisterAnzahl($registerAnzahl)
    {
        $this->registerAnzahl = $registerAnzahl;
    }

    public function getLetztePflege($formatiert = false)
    {
        if ($formatiert) {
            $x = date("d.m.Y", strtotime($this->letztePflege));
            if ($x == "01.01.1970")
                $x = "unbekannt";
            return $x;
        }
        return $this->letztePflege;
    }

    public function setLetztePflege($letztePflege)
    {
        $this->letztePflege = $letztePflege;
    }

    public function getAnzahlManuale()
    {
        $retVal = 0;
        if ($this->getManual1() == 1)
            $retVal ++;
        if ($this->getManual2() == 1)
            $retVal ++;
        if ($this->getManual3() == 1)
            $retVal ++;
        if ($this->getManual4() == 1)
            $retVal ++;
        if ($this->getManual5() == 1)
            $retVal ++;
        if ($this->getPedal() == 1)
            $retVal ++;
        return $retVal;
    }
    
    public function getKostenHauptstimmung()
    {
        return $this->kostenHauptstimmung;
    }

    public function setKostenHauptstimmung($pKostenHauptstimmung)
    {
        $this->kostenHauptstimmung = $pKostenHauptstimmung;
    }
    
    public function getKostenTeilstimmung()
    {
        return $this->kostenTeilstimmung;
    }

    public function setKostenTeilstimmung($pKostenTeilstimmung)
    {
        $this->kostenTeilstimmung = $pKostenTeilstimmung;
    }
    
    
}
?>
