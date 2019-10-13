<?php

abstract class AdvancedDatabaseStorageObjekt extends DatabaseStorageObjekt
{

    protected $tableName;

    protected $tablePrefix;

    protected $primaryKeyValues;

    protected $result;

    public function __construct($primaryValues = array(), $primaryKeys, $tableName, $tablePrefix)
    {
        if (! is_array($primaryKeys))
            throw new InvalidArgumentException("PrimaryKey must be an array");
        if (! is_array($primaryValues))
            throw new InvalidArgumentException("PrimaryKey must be an array");
        
        $this->dbInstance = DB::getInstance();
        $this->tableName = $tableName;
        $this->tablePrefix = $tablePrefix;
        
        $iCounter = 0;
        $this->primaryKeyValues = array();
        $proceedLoad = true;
        foreach ($primaryKeys as $key) {
            $this->primaryKeyValues[$key] = $primaryValues[$iCounter];
            $proceedLoad &= ($primaryValues[$iCounter] > 0);
            $iCounter ++;
        }
        
        if ($proceedLoad) {
            $this->load();
        }
    }

    protected function loadLastSavedObjekt()
    {
        $this->load();
    }

    protected function doNew()
    {}

    protected function doSave()
    {
        $ht = $this->generateHashtable();
        $dbFelder = "";
        $dbWerte = "";
        $iCounter = 0;
        
        foreach ($ht as $key => $val) {
            $iCounter ++;
            $dbFelder .= ", " . $key;
            $dbWerte .= ", '" . $val . "'";
        }
        
        $sql = "INSERT INTO " . $this->tableName . " (
					" . $this->tablePrefix . "lastchange,
					" . $this->tablePrefix . "createdate,";
        $hasVorgaenger = false;
        foreach ($this->primaryKeyValues as $key => $val) {
            if ($hasVorgaenger == true)
                $sql .= ", ";
            $sql .= $key;
            $hasVorgaenger = true;
        }
        $sql .= $dbFelder;
        $sql .= ") VALUES (
					'" . $this->getChangeAt() . "',
					NOW(),";
        
        $hasVorgaenger = false;
        foreach ($this->primaryKeyValues as $key => $val) {
            if ($hasVorgaenger == true)
                $sql .= ", ";
            $sql .= $val;
            $hasVorgaenger = true;
        }
        $sql .= $dbWerte;
        $sql .= ");";
        $this->dbInstance->NonSelectQuery($sql);
    }

    protected function doUpdate()
    {
        $ht = $this->generateHashtable();
        $stmt = "";
        $iCounter = 0;
        
        foreach ($ht as $key => $val) {
            $iCounter ++;
            $stmt .= $key . " = '" . addslashes($val) . "'";
            
            if ($iCounter <= $ht->getLength() - 1) {
                $stmt .= ", ";
            }
        }
        
        $sql = "UPDATE " . $this->tableName . " SET 
					" . $this->tablePrefix . "lastchange = NOW(),";
        $sql .= $stmt;
        $sql .= "WHERE ";
        
        $hasVorgaenger = false;
        foreach ($this->primaryKeyValues as $key => $val) {
            if ($hasVorgaenger)
                $sql .= " AND ";
            $sql .= " " . $key . " = " . $val;
            $hasVorgaenger = true;
        }
        $this->dbInstance->NonSelectQuery($sql);
    }

    protected function doExport()
    {
        $ht = $this->generateHashtable();
        $iCounter = 0;
        
        $keys = "";
        $values = "";
        foreach ($ht as $key => $val) {
            $iCounter ++;
            $values .= "'" . addslashes($val) . "' ";
            $keys .= $key . " ";
            
            if ($iCounter <= $ht->getLength() - 1) {
                $values .= ", ";
                $keys .= ", ";
            }
        }
        
        return "INSERT INTO " . $this->tableName . "(" . $keys . ") VALUES(" . $values . ");";
    }

    public function loeschen()
    {
        $sql = "DELETE FROM " . $this->tableName . " WHERE ";
        $hasVorgaenger = false;
        foreach ($this->primaryKeyValues as $key => $val) {
            if ($hasVorgaenger)
                $sql .= " AND ";
            $sql .= " " . $key . " = " . $val;
            $hasVorgaenger = true;
        }
        DB::getInstance()->NonSelectQuery($sql);
    }

    public function doLoad()
    {
        $sql = "SELECT
					x.*
				FROM
					" . $this->tableName . " x
				WHERE ";
        $hasVorgaenger = false;
        foreach ($this->primaryKeyValues as $key => $val) {
            if ($hasVorgaenger)
                $sql .= " AND ";
            $sql .= " " . $key . " = " . $val;
            $hasVorgaenger = true;
        }
        
        if (($rs = $this->dbInstance->SelectQuery($sql)) != false) {
            $rs = $rs[0];
            $this->doLoadFromArray($rs);
        } else {
            // What else?
        }
    }

    public function doLoadFromArray($rs)
    {
        $this->result = $rs;
        
        foreach ($this->primaryKeyValues as $key => $val) {
            $this->primaryKeyValues[$key] = $rs[$key];
        }
        
        $this->setChangeAt($rs[$this->tablePrefix . "lastchange"]);
        $this->setCreatedAt($rs[$this->tablePrefix . "createdate"]);
        $this->laden();
    }

    protected abstract function generateHashtable();

    protected abstract function laden();

    public function getTablename()
    {
        return $this->tableName;
    }

    public function getTableprefix()
    {
        return $this->tablePrefix;
    }
}
?>