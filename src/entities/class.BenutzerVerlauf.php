<?php

class BenutzerVerlauf extends SimpleDatabaseStorageObjekt
{

    private $benutzerID;

    private $benutzerName;

    private $requestURI;

    private $referer;

    private $post;

    private $get;

    private $duration;

    // Transient, Häufigkeit des Registers
    private $anzahl;

    public function __construct($iID = 0, $primaryKey = "bv_id", $tableName = "benutzerverlauf", $tablePrefix = "bv_")
    {
        parent::__construct($iID, $primaryKey, $tableName, $tablePrefix);
    }

    protected function generateHashtable()
    {
        $ht = new HashTable();
        
        $ht->add($this->tablePrefix . "benutzerID", $this->getBenutzerID());
        $ht->add($this->tablePrefix . "benutzerName", $this->getBenutzerName());
        $ht->add($this->tablePrefix . "requestURI", $this->getRequestURI());
        $ht->add($this->tablePrefix . "referer", $this->getReferer());
        $ht->add($this->tablePrefix . "post", $this->getPost());
        $ht->add($this->tablePrefix . "get", $this->getGet());
        $ht->add($this->tablePrefix . "duration", $this->getDuration());
        
        return $ht;
    }

    protected function laden()
    {
        $rs = $this->result;
        $this->setBenutzerID($rs[$this->tablePrefix . 'benutzerID']);
        $this->setBenutzerName($rs[$this->tablePrefix . 'benutzerName']);
        $this->setRequestURI($rs[$this->tablePrefix . 'requestURI']);
        $this->setReferer($rs[$this->tablePrefix . 'referer']);
        $this->setPost($rs[$this->tablePrefix . 'post']);
        $this->setGet($rs[$this->tablePrefix . 'get']);
        $this->setDuration($rs[$this->tablePrefix . 'duration']);
    }

    public function getBenutzerID()
    {
        return $this->benutzerID;
    }

    public function getBenutzerName()
    {
        return $this->benutzerName;
    }

    public function getDuration()
    {
        return $this->duration;
    }

    public function getGet()
    {
        return $this->get;
    }

    public function getPost()
    {
        return $this->post;
    }

    public function getReferer()
    {
        return $this->referer;
    }

    public function getRequestURI()
    {
        return $this->requestURI;
    }

    public function setBenutzerID($benutzerID)
    {
        $this->benutzerID = $benutzerID;
    }

    public function setBenutzerName($benutzerName)
    {
        $this->benutzerName = $benutzerName;
    }

    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    public function setGet($get)
    {
        $this->get = $get;
    }

    public function setPost($post)
    {
        $this->post = $post;
    }

    public function setReferer($referer)
    {
        $this->referer = $referer;
    }

    public function setRequestURI($requestURI)
    {
        $this->requestURI = $requestURI;
    }
}
?>
