<?php

class Ansprechpartner extends SimpleDatabaseStorageObjekt
{

    private $funktion;

    private $stelle;

    private $anrede;

    private $titel;

    private $vorname;

    private $nachname;

    private $telefon;

    private $fax;

    private $mobil;

    private $email;

    private $bemerkung;

    private $aktiv;

    private $andere;

    private $adressId;

    private $adresse = null;
 // not persistent
    
    /**
     *
     * Enter description here ...
     *
     * @param int $iID            
     * @param String $primaryKey            
     * @param String $tableName            
     * @param String $tablePrefix            
     */
    public function __construct($iID = 0, $primaryKey = "a_id", $tableName = "ansprechpartner", $tablePrefix = "a_")
    {
        parent::__construct($iID, $primaryKey, $tableName, $tablePrefix);
    }

    protected function generateHashtable()
    {
        $ht = new HashTable();
        
        $ht->add($this->tablePrefix . "funktion", $this->getFunktion());
        $ht->add($this->tablePrefix . "anrede", $this->getAnrede());
        $ht->add($this->tablePrefix . "titel", $this->getTitel());
        $ht->add($this->tablePrefix . "stelle", $this->getStelle());
        $ht->add($this->tablePrefix . "vorname", $this->getVorname());
        $ht->add($this->tablePrefix . "name", $this->getNachname());
        $ht->add($this->tablePrefix . "telefon", $this->getTelefon());
        $ht->add($this->tablePrefix . "fax", $this->getFax());
        $ht->add($this->tablePrefix . "mobil", $this->getMobil());
        $ht->add($this->tablePrefix . "email", $this->getEmail());
        $ht->add($this->tablePrefix . "bemerkung", $this->getBemerkung());
        $ht->add($this->tablePrefix . "aktiv", $this->getAktiv());
        $ht->add($this->tablePrefix . "andere", $this->getAndere());
        $ht->add("ad_id", $this->getAdressId());
        
        return $ht;
    }

    protected function laden()
    {
        $rs = $this->result;
        $this->setID($rs['a_id']);
        $this->setFunktion($rs['a_funktion']);
        $this->setAnrede($rs['a_anrede']);
        $this->setTitel($rs['a_titel']);
        $this->setStelle($rs['a_stelle']);
        $this->setVorname($rs['a_vorname']);
        $this->setNachname($rs['a_name']);
        $this->setTelefon($rs['a_telefon']);
        $this->setFax($rs['a_fax']);
        $this->setMobil($rs['a_mobil']);
        $this->setEmail($rs['a_email']);
        $this->setBemerkung($rs['a_bemerkung']);
        $this->setAktiv($rs['a_aktiv']);
        $this->setAndere($rs['a_andere']);
        $this->setAdressId($rs['ad_id']);
    }

    public function speichern($boolean = false)
    {
        if ($this->adresse != null) {
            // Adresse immer neu laden, damit man die ID hat und den Ansprechpartner mit der Adresse verknuepfen kann
            $this->adresse->speichern(true);
            $this->setAdressId($this->adresse->getID());
        }
        parent::speichern($boolean);
    }

    public function loeschen()
    {
        Trace::addStart(__METHOD__);
        $sql = "UPDATE ansprechpartner SET a_aktiv = 0 WHERE a_id = " . $this->getID();
        $db = DB::getInstance();
        $db->NonSelectQuery($sql);
        Trace::addExit(__METHOD__);
    }

    public function getAktiv()
    {
        return $this->aktiv;
    }

    public function getAnrede()
    {
        return $this->anrede;
    }

    public function getTitel()
    {
        return $this->titel;
    }

    public function getBemerkung()
    {
        return $this->bemerkung;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getFax()
    {
        return $this->fax;
    }

    public function getFunktion()
    {
        return $this->funktion;
    }

    public function getMobil()
    {
        return $this->mobil;
    }

    public function getNachname()
    {
        return $this->nachname;
    }

    public function getStelle()
    {
        return $this->stelle;
    }

    public function getTelefon()
    {
        return $this->telefon;
    }

    public function getVorname()
    {
        return $this->vorname;
    }

    public function getAnzeigeName()
    {
        $retVal = "";
//         $retVal .= $this->getAnrede();
//         if ($this->getTitel() != "") {
//             $retVal .= " ";
//             $retVal .= $this->getTitel();
//         }
        $retVal .= " " . $this->getNachname();
        if ($this->getVorname() != "") {
            $retVal .= ", " . $this->getVorname();
        }
        return trim($retVal);
    }

    public function setAktiv($aktiv)
    {
        if ($this->aktiv != $aktiv) {
            $this->aktiv = $aktiv;
            $this->setChanged(true);
        }
    }

    public function setAnrede($anrede)
    {
        if ($this->anrede != $anrede) {
            $this->anrede = $anrede;
            $this->setChanged(true);
        }
    }

    public function setTitel($titel)
    {
        if ($this->titel != $titel) {
            $this->titel = $titel;
            $this->setChanged(true);
        }
    }

    public function setBemerkung($bemerkung)
    {
        if ($this->bemerkung != $bemerkung) {
            $this->bemerkung = $bemerkung;
            $this->setChanged(true);
        }
    }

    public function setEmail($email)
    {
        if ($this->email != $email) {
            $this->email = $email;
            $this->setChanged(true);
        }
    }

    public function setFax($fax)
    {
        if ($this->fax != $fax) {
            $this->fax = $fax;
            $this->setChanged(true);
        }
    }

    public function setFunktion($funktion)
    {
        if ($this->funktion != $funktion) {
            $this->funktion = $funktion;
            $this->setChanged(true);
        }
    }

    public function setMobil($mobil)
    {
        if ($this->mobil != $mobil) {
            $this->mobil = $mobil;
            $this->setChanged(true);
        }
    }

    public function setNachname($nachname)
    {
        if ($this->nachname != $nachname) {
            $this->nachname = $nachname;
            $this->setChanged(true);
        }
    }

    public function setStelle($stelle)
    {
        if ($this->stelle != $stelle) {
            $this->stelle = $stelle;
            $this->setChanged(true);
        }
    }

    public function setTelefon($telefon)
    {
        if ($this->telefon != $telefon) {
            $this->telefon = $telefon;
            $this->setChanged(true);
        }
    }

    public function setVorname($vorname)
    {
        if ($this->vorname != $vorname) {
            $this->vorname = $vorname;
            $this->setChanged(true);
        }
    }

    public function getAndere()
    {
        return $this->andere;
    }

    public function setAndere($andere)
    {
        if ($this->andere != $andere) {
            $this->andere = $andere;
            $this->setChanged(true);
        }
    }

    public function getAdressId()
    {
        return $this->adressId;
    }

    public function getAdresse()
    {
        if ($this->adresse == null) {
            $this->adresse = new Adresse($this->getAdressId());
        }
        return $this->adresse;
    }

    public function setAdressId($adressId)
    {
        if ($this->adressId != $adressId) {
            $this->adressId = $adressId;
            $this->setChanged(true);
        }
    }
}
?>
