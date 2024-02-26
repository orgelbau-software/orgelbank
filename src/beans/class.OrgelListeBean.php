<?php

class OrgelListeBean
{

    private $gemeindeID;

    private $orgelId;

    private $gemeindeNamen;

    private $erbauer;

    private $baujahr;

    private $letztePflege;

    private $naechstePflege;

    private $pflegevertrag;

    private $manuale;

    private $register;

    private $gemeindePLZ;

    private $gemeindeOrt;

    private $gemeindeBezirk;

    private $pedal;

    private $manual1;

    private $manual2;

    private $manual3;

    private $manual4;

    private $manual5;

    private $registerAnzahl;

    private $ostID;

    private $zyklus;
    
    private $kostenHauptstimmung;
    
    private $kostenTeilstimmung;

    public function init($rs)
    {
        $this->setOrgelId($rs['o_id']);
        $this->setGemeindeId($rs['g_id']);
        $this->setBaujahr($rs['o_baujahr']);
        $this->setErbauer($rs['o_erbauer']);
        $this->setManual1($rs['o_manual1']);
        $this->setManual2($rs['o_manual2']);
        $this->setManual3($rs['o_manual3']);
        $this->setManual4($rs['o_manual4']);
        $this->setManual5($rs['o_manual5']);
        $this->setPedal($rs['o_pedal']);
        $this->setRegisterAnzahl($rs['o_anzahlregister']);
        $this->setLetztePflege($rs['o_letztepflege']);
        if(isset($rs['o_kostenhauptstimmung'])) {
            $this->setKostenHauptstimmung($rs['o_kostenhauptstimmung']);
        }
        if(isset($rs['o_kostenteilstimmung'])) {
            $this->setKostenTeilstimmung($rs['o_kostenteilstimmung']);
        }
        
        if ($rs['g_kirche'] != "") {
            $this->setGemeindeNamen($rs['g_kirche']);
            $this->setGemeindeID($rs['g_id']);
            $this->setGemeindeBezirk($rs['b_id']);
            $this->setGemeindeOrt($rs['ad_ort']);
            $this->setGemeindePLZ($rs['ad_plz']);
        } else {
            $this->setGemeindeNamen("");
            $this->setGemeindeID("");
            $this->setGemeindeBezirk("-");
            $this->setGemeindeOrt("Gemeinde unbestimmt");
            $this->setGemeindePLZ("-");
        }
        
        // Fuer Offene Wartungen
        if (isset($rs['naechstepflege']))
            $this->setNaechstePflege($rs['naechstepflege']);
        // Fuer Offene Wartungen
        if (isset($rs['o_zyklus']))
            $this->setZyklus($rs['o_zyklus']);
        
        // Fuer Orgel-Druckansicht
        if (isset($rs['o_pflegevertrag']))
            $this->setPflegevertrag($rs['o_pflegevertrag']);
    }

    public function setOstID($ostID)
    {
        $this->ostID = $ostID;
    }

    public function getGemeindeBezirk()
    {
        return $this->gemeindeBezirk;
    }

    public function setGemeindeBezirk($gemeindeBezirk)
    {
        $this->gemeindeBezirk = $gemeindeBezirk;
    }

    public function getRegisterAnzahl()
    {
        return $this->registerAnzahl;
    }

    public function getZyklus()
    {
        return $this->zyklus;
    }

    public function setRegisterAnzahl($registerAnzahl)
    {
        $this->registerAnzahl = $registerAnzahl;
    }

    public function getPedal()
    {
        return $this->pedal;
    }

    public function setPedal($pedal)
    {
        $this->pedal = $pedal;
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

    public function setManual1($manual1)
    {
        $this->manual1 = $manual1;
    }

    public function setManual2($manual2)
    {
        $this->manual2 = $manual2;
    }

    public function setManual3($manual3)
    {
        $this->manual3 = $manual3;
    }

    public function setManual4($manual4)
    {
        $this->manual4 = $manual4;
    }

    public function setManual5($manual5)
    {
        $this->manual5 = $manual5;
    }

    public function getGemeindeID()
    {
        return $this->gemeindeID;
    }

    public function getOrgelId()
    {
        return $this->orgelId;
    }

    public function getGemeindeNamen()
    {
        return $this->gemeindeNamen;
    }

    public function getErbauer()
    {
        return $this->erbauer;
    }

    public function getBaujahr()
    {
        return $this->baujahr;
    }

    public function getLetztePflege($formatiert = true)
    {
        if ($formatiert) {
            $x = date("d.m.Y", strtotime($this->letztePflege));
            if ($x == "01.01.1970")
                $x = "unbekannt";
            return $x;
        }
        return $this->letztePflege;
    }

    public function getNaechstePflege($formatiert = true)
    {
        if ($formatiert) {
            if($this->naechstePflege == "") {
                $x = "unbekannt";
            } else {
                $x = date("d.m.Y", strtotime($this->naechstePflege));
                if ($x == "01.01.1970")
                    $x = "unbekannt";
            }
            return $x;
        }
        return $this->naechstePflege;
    }

    public function getManuale()
    {
        return $this->manuale;
    }

    public function getRegister()
    {
        return $this->register;
    }

    public function getGemeindePLZ()
    {
        return $this->gemeindePLZ;
    }

    public function getGemeindeOrt()
    {
        return $this->gemeindeOrt;
    }

    public function getPflegevertrag()
    {
        return $this->pflegevertrag;
    }
    
    public function getKostenTeilstimmung()
    {
        return $this->kostenTeilstimmung;
    }
    
    public function getKostenHauptstimmung()
    {
        return $this->kostenHauptstimmung;
    }

    public function setGemeindeID($gemeindeID)
    {
        $this->gemeindeID = $gemeindeID;
    }

    public function setOrgelId($orgelId)
    {
        $this->orgelId = $orgelId;
    }

    public function setGemeindeNamen($gemeindeNamen)
    {
        $this->gemeindeNamen = $gemeindeNamen;
    }

    public function setErbauer($erbauer)
    {
        $this->erbauer = $erbauer;
    }

    public function setBaujahr($baujahr)
    {
        $this->baujahr = $baujahr;
    }

    public function setLetztePflege($letztePflege)
    {
        $this->letztePflege = $letztePflege;
    }

    public function setNaechstePflege($letztePflege)
    {
        $this->naechstePflege = $letztePflege;
    }

    public function setManuale($manuale)
    {
        $this->manuale = $manuale;
    }

    public function setRegister($register)
    {
        $this->register = $register;
    }

    public function setGemeindePLZ($gemeindePLZ)
    {
        $this->gemeindePLZ = $gemeindePLZ;
    }

    public function setGemeindeOrt($gemeindeOrt)
    {
        $this->gemeindeOrt = $gemeindeOrt;
    }

    public function setZyklus($zyklus)
    {
        $this->zyklus = $zyklus;
    }

    public function setPflegevertrag($pflegevertrag)
    {
        $this->pflegevertrag = $pflegevertrag;
    }
    
    public function setKostenHauptstimmung($pValue)
    {
        $this->kostenHauptstimmung = $pValue;
    }
    
    public function setKostenTeilstimmung($pValue)
    {
        $this->kostenTeilstimmung = $pValue;
    }
}
