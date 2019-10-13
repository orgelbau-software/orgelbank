<?php

class Adresse extends SimpleDatabaseStorageObjekt
{

    const TYPE_ANSPRECHPARTNER = 1;

    const TYPE_KIRCHE = 2;

    const TYPE_RECHNUNG = 3;

    private $strasse;

    private $hausnummer;

    private $plz;

    private $ort;

    private $land;

    private $lat;

    private $lng;

    private $geoStatus;

    private $type;

    /**
     *
     * Enter description here ...
     *
     * @param int $iID            
     * @param String $primaryKey            
     * @param String $tableName            
     * @param String $tablePrefix            
     */
    public function __construct($iID = 0, $primaryKey = "ad_id", $tableName = "adresse", $tablePrefix = "ad_")
    {
        parent::__construct($iID, $primaryKey, $tableName, $tablePrefix);
    }

    protected function generateHashtable()
    {
        $ht = new HashTable();
        
        $ht->add($this->tablePrefix . "strasse", $this->getStrasse());
        $ht->add($this->tablePrefix . "hsnr", $this->getHausnummer());
        $ht->add($this->tablePrefix . "plz", $this->getPlz());
        $ht->add($this->tablePrefix . "ort", $this->getOrt());
        $ht->add($this->tablePrefix . "land", $this->getLand());
        $ht->add($this->tablePrefix . "lat", $this->getLat());
        $ht->add($this->tablePrefix . "lng", $this->getLng());
        $ht->add($this->tablePrefix . "geostatus", $this->getGeoStatus());
        $ht->add($this->tablePrefix . "type", $this->getType());
        
        return $ht;
    }

    protected function laden()
    {
        $rs = $this->result;
        $this->setID($rs['ad_id']);
        $this->setStrasse($rs['ad_strasse']);
        $this->setHausnummer($rs['ad_hsnr']);
        $this->setPlz($rs['ad_plz']);
        $this->setOrt($rs['ad_ort']);
        $this->setLand($rs['ad_land']);
        $this->setLat($rs['ad_lat']);
        $this->setLng($rs['ad_lng']);
        $this->setGeoStatus($rs['ad_geostatus']);
        $this->setType($rs['ad_type']);
    }

    public function getFormattedAdress($pWithCountry = false)
    {
        $retVal = "";
        if ($this->strasse != null && $this->strasse != "") {
            $retVal .= $this->strasse;
            $retVal .= " ";
        }
        
        if ($this->hausnummer != null && $this->hausnummer != "") {
            $retVal .= $this->hausnummer;
        }
        
        if ($this->plz != null && $this->plz != "") {
            if ($retVal != "") {
                $retVal .= ", ";
            }
            $retVal .= $this->plz;
            $retVal .= " ";
        }
        
        if ($this->ort != null && $this->ort != "") {
            $retVal .= $this->ort;
        }
        
        if ($pWithCountry && $this->land != null) {
            if ($retVal != "") {
                $retVal .= ", ";
            }
            $retVal .= $this->land;
        }
        
        return $retVal;
    }

    public function getHausnummer()
    {
        return $this->hausnummer;
    }

    public function getOrt()
    {
        return $this->ort;
    }

    public function getPlz()
    {
        return $this->plz;
    }

    public function getStrasse()
    {
        return $this->strasse;
    }

    public function setHausnummer($hausnummer)
    {
        if ($this->hausnummer != $hausnummer) {
            $this->hausnummer = $hausnummer;
            $this->setChanged(true);
        }
    }

    public function setOrt($ort)
    {
        if ($this->ort != $ort) {
            $this->ort = $ort;
            $this->setChanged(true);
        }
    }

    public function setPlz($plz)
    {
        if ($this->plz != $plz) {
            $this->plz = $plz;
            $this->setChanged(true);
        }
    }

    public function setStrasse($strasse)
    {
        if ($this->strasse != $strasse) {
            $this->strasse = $strasse;
            $this->setChanged(true);
        }
    }

    public function getLand()
    {
        return $this->land;
    }

    public function setLand($land)
    {
        if ($this->land != $land) {
            $this->land = $land;
            $this->setChanged(true);
        }
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        if ($this->type != $type) {
            $this->type = $type;
            $this->setChanged(true);
        }
    }

    /**
     *
     * @return the $lat
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     *
     * @return the $lng
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     *
     * @param field_type $lat            
     */
    public function setLat($lat)
    {
        if ($this->lat != $lat) {
            $this->lat = $lat;
            $this->setChanged(true);
        }
    }

    /**
     *
     * @param field_type $lng            
     */
    public function setLng($lng)
    {
        if ($this->lng != $lng) {
            $this->lng = $lng;
            $this->setChanged(true);
        }
    }

    /**
     *
     * @return the $geoStatus
     */
    public function getGeoStatus()
    {
        return $this->geoStatus;
    }

    /**
     *
     * @param field_type $geoStatus            
     */
    public function setGeoStatus($geoStatus)
    {
        if ($this->geoStatus != $geoStatus) {
            $this->geoStatus = $geoStatus;
            $this->setChanged(true);
        }
    }
}
?>
