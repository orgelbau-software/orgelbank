<?php

abstract class SimpleDatabaseStorageObjekt extends DatabaseStorageObjekt
{

    protected $tableName;

    protected $tablePrefix;

    protected $primaryKey;

    protected $result;

    public function __construct($iID = 0, $primaryKey, $tableName, $tablePrefix)
    {
        $this->tableName = $tableName;
        $this->tablePrefix = $tablePrefix;
        $this->primaryKey = $primaryKey;
        parent::__construct($iID);
    }

    protected function loadLastSavedObjekt()
    {
        $sql = "SELECT max(" . $this->primaryKey . ") AS maxid FROM " . $this->tableName;
        if (($rs = DB::getInstance()->SelectQuery($sql)) !== false) {
            $this->iID = $rs[0]['maxid'];
            $this->isPersistent = true;
        } else {
            // What else?
            $this->isPersistent = false;
            $this->iID = - 1;
        }
    }

    protected function doNew()
    {}

    protected function doSave()
    {
        $ht = $this->generateHashtable();
        if ($ht == null || count($ht) == 0) {
            throw new Exception("No Hashtable returned by generateHashtable() Method");
        }
        $dbFelder = "";
        $dbWerte = "";
        $iCounter = 0;
        
        foreach ($ht as $key => $val) {
            $iCounter ++;
            $dbFelder .= $key;
            $dbWerte .= "'" . addslashes($val) . "'";
            if ($iCounter <= $ht->getLength() - 1) {
                $dbFelder .= ", ";
                $dbWerte .= ", ";
            }
        }
        
        $sql = "INSERT INTO " . $this->tableName . " (
					" . $this->tablePrefix . "lastchange,
					" . $this->tablePrefix . "createdate,";
        $sql .= $dbFelder;
        $sql .= ") VALUES (
					'" . $this->getChangeAt() . "',
					NOW(),";
        $sql .= $dbWerte;
        $sql .= ");";
        // echo $sql;
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
        $sql .= " WHERE " . $this->primaryKey . " = " . $this->getID();
        // echo $sql;
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
        $sql = "DELETE FROM " . $this->tableName . " WHERE " . $this->primaryKey . " = " . $this->getID();
        DB::getInstance()->NonSelectQuery($sql);
    }

    public function doLoad()
    {
        $sql = "SELECT
					x.*
				FROM
					" . $this->tableName . " x
				WHERE
					x." . $this->primaryKey . " = " . $this->iID;
        $rs = $this->dbInstance->SelectQuery($sql);
        if ($rs != null && $rs !== false && isset($rs[0])) {
            $rs = $rs[0];
            
            foreach ($rs as $key => $val) {
                $rs[$key] = stripslashes($val);
            }
            $this->doLoadFromArray($rs);
        } else {
            $this->iID = 0;
            $this->newObjekt();
        }
    }

    public function doLoadFromArray($rs)
    {
        $this->result = $rs;
        $this->setID($rs[$this->primaryKey]);
        // echo $this->tablePrefix . "lastchange"."-->".$rs[$this->tablePrefix . "lastchange"]."<br/>";
        $this->setChangeAt($rs[$this->tablePrefix . "lastchange"]);
        // echo $this->getChangeAt()."<br>";
        $this->setCreatedAt($rs[$this->tablePrefix . "createdate"]);
        $this->setPersistent(true);
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

    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    public function getLastSavedObjectForSelftest()
    {
        return $this->loadLastSavedObjekt();
    }
}
?>