<?php

class Arbeitswoche extends SimpleDatabaseStorageObjekt
{

    private $benutzerID;

    private $wochenStart;

    private $kalenderWoche;

    private $jahr;

    private $wochenStundenIst;

    private $wochenStundenSoll;

    private $wochenStundenDif;

    private $wochenStundenUrlaub;

    private $eingabeKomplett;

    private $eingabeMoeglich;

    private $eingabeGebucht;

    private $istStunden = array(
        0,
        0,
        0,
        0,
        0,
        0,
        0
    );

    private $sollStunden = array(
        0,
        0,
        0,
        0,
        0,
        0,
        0
    );

    public function __construct($iArbeitstagID = 0)
    {
        parent::__construct($iArbeitstagID, "aw_id", "arbeitswoche", "aw_");
    }

    protected function generateHashtable()
    {
        $ht = new HashTable();
        
        $ht->add("be_id", $this->getBenutzerID());
        $ht->add("aw_jahr", $this->getJahr());
        $ht->add("aw_kw", $this->getKalenderWoche());
        $ht->add("aw_stunden_ist", $this->getWochenStundenIst());
        $ht->add("aw_stunden_soll", $this->getWochenStundenSoll());
        $ht->add("aw_stunden_dif", $this->getWochenStundenDif());
        $ht->add("aw_stunden_urlaub", $this->getWochenStundenUrlaub());
        $ht->add("aw_eingabe_komplett", $this->getEingabeKomplett());
        $ht->add("aw_eingabe_gebucht", $this->getEingabeGebucht());
        $ht->add("aw_eingabe_moeglich", $this->getEingabeMoeglich());
        $ht->add("aw_wochenstart", $this->getWochenStart());
        
        return $ht;
    }

    protected function laden()
    {
        $rs = $this->result;
        $this->setBenutzerID($rs['be_id']);
        $this->setJahr($rs['aw_jahr']);
        $this->setKalenderWoche($rs['aw_kw']);
        $this->setWochenStundenIst($rs['aw_stunden_ist']);
        $this->setWochenStundenSoll($rs['aw_stunden_soll']);
        $this->setWochenStundenDif($rs['aw_stunden_dif']);
        $this->setWochenStundenUrlaub($rs['aw_stunden_urlaub']);
        $this->setEingabeGebucht($rs['aw_eingabe_gebucht']);
        $this->setEingabeMoeglich($rs['aw_eingabe_moeglich']);
        $this->setEingabeKomplett($rs['aw_eingabe_komplett']);
        $this->setWochenStart($rs['aw_wochenstart']);
    }

    public function addArbeitstag(Arbeitstag $at)
    {
        $iTagDerWoche = Date::getTagDerWoche(strtotime($at->getDatum()));
        if ($this->sollStunden[$iTagDerWoche] > 0 && $this->sollStunden[$iTagDerWoche] != $at->getSollStunden()) {
            throw new InvalidArgumentException("Soll-Stunden koennen nicht unterschiedlich sein fuer einen Werktag");
        }
        $this->sollStunden[$iTagDerWoche] = $at->getSollStunden();
        $this->istStunden[$iTagDerWoche] += $at->getIstStunden();
        
        if(ProjektUtilities::isUrlaubsAufgabe($at->getAufgabeID())) {
            // FIXME: Hier ist noch ein Fehler drin
            // Es muss im Anschluss wieder fuer alle Arbeitstage der Woche der Urlaub errechnet werden.
            // So funktioniert es nur, wenn der gesamte Urlaub der Woche auf einen Arbeitstag eingetragen wird

            // 2019-07-30: Warum nicht einfach so?
            $this->setWochenStundenUrlaub($at->getIstStunden() + $this->getWochenStundenUrlaub());
        }
    }

    public function summieren()
    {
        // Stunden werden aus Benutzer-Objekt und nicht aus den übergebenen Arbeitstagen errechnet, da sonst nicht alle
        // Tage der Woche berücksichtigt werden, wenn der Benutzer z.B. keine Zeiten für Donnerstags eingegeben hat
        // $this->wochenStundenSoll = BenutzerUtilities::getBenutzerSollStunden($this->getBenutzerID());
        
        // liefer die Stundenanzahl bereits um Feiertage bereinigt
        // $std = BenutzerUtilities::getBenutzerSollWochenStunden($this->getBenutzerID(), strtotime($this->getWochenStart()));
        // $gesamt = 0;
        // foreach($std as $key => $val) {
        // $gesamt += $val;
        // }
        // $this->wochenStundenSoll = $gesamt;
        foreach ($this->istStunden as $std) {
            $this->wochenStundenIst += $std;
        }
        
        $this->wochenStundenDif = $this->wochenStundenIst - $this->wochenStundenSoll;
    }

    public function getBenutzerID()
    {
        return $this->benutzerID;
    }

    public function getJahr()
    {
        return $this->jahr;
    }

    public function getKalenderWoche()
    {
        return $this->kalenderWoche;
    }

    public function getWochenStundenDif()
    {
        return $this->wochenStundenDif;
    }

    public function getWochenStundenIst()
    {
        return $this->wochenStundenIst;
    }

    public function getWochenStundenSoll()
    {
        return $this->wochenStundenSoll;
    }

    public function setBenutzerID($benutzerID)
    {
        $this->benutzerID = $benutzerID;
    }

    public function setJahr($jahr)
    {
        $this->jahr = $jahr;
    }

    public function setKalenderWoche($kalenderWoche)
    {
        $this->kalenderWoche = $kalenderWoche;
    }

    public function setWochenStundenDif($wochenStundenDif)
    {
        $this->wochenStundenDif = $wochenStundenDif;
    }

    public function setWochenStundenIst($wochenStundenIst)
    {
        $this->wochenStundenIst = $wochenStundenIst;
    }

    public function setWochenStundenSoll($wochenStundenSoll)
    {
        $this->wochenStundenSoll = $wochenStundenSoll;
    }

    public function getEingabeGebucht()
    {
        return $this->eingabeGebucht;
    }

    public function getEingabeKomplett()
    {
        return $this->eingabeKomplett;
    }

    public function getEingabeMoeglich()
    {
        return $this->eingabeMoeglich;
    }

    public function setEingabeGebucht($eingabeGebucht)
    {
        $this->eingabeGebucht = $eingabeGebucht;
    }

    public function setEingabeKomplett($eingabeKomplett)
    {
        $this->eingabeKomplett = $eingabeKomplett;
    }

    public function setEingabeMoeglich($eingabeMoeglich)
    {
        $this->eingabeMoeglich = $eingabeMoeglich;
    }

    public function getWochenStart($formatiert = false)
    {
        if ($formatiert)
            return date("d.m.Y", strtotime($this->wochenStart));
        return $this->wochenStart;
    }

    public function setWochenStart($wochenStart)
    {
        $this->wochenStart = $wochenStart;
    }

    public function isEditable()
    {
        $retVal = false;
        $retVal &= $this->eingabeMoeglich == 1;
        $retVal &= $this->eingabeKomplett == 0;
        $retVal &= $this->eingabeGebucht == 0;
        return $retVal;
    }

    /**
     *
     * @return the $wochenStundenUrlaub
     */
    public function getWochenStundenUrlaub()
    {
        return $this->wochenStundenUrlaub;
    }

    /**
     *
     * @param field_type $wochenStundenUrlaub            
     */
    public function setWochenStundenUrlaub($wochenStundenUrlaub)
    {
        $this->wochenStundenUrlaub = $wochenStundenUrlaub;
    }
}
?>
