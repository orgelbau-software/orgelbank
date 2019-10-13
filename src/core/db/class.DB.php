<?php

/**
 * Stellt Verbindung zu einer  MySQL DB her, Sendet Querys, Returnt Results
 * 
 * @author swatermeyer
 * @since 09.05.2006
 * @version $Revision: 1.5 $
 *
 */
class DB implements DBProvider
{

    public static $num_queries;

    // zaehlt die abgesendeten Queries ueber die DB-Klasse
    
    /**
     *
     * @var DB
     */
    private static $dbInstance;

    /**
     *
     * @var DBProvider
     */
    private $mInstance;

    /**
     * Standardkonstruktor
     *
     * @access public
     */
    public function __construct()
    {
        // $this->mInstance = new MySQLDBProvider();
        $this->mInstance = new MySQLiDBProvider();
        $this->connect();
    }

    public function __destruct()
    {
        $this->disconnect();
    }

    /**
     * Baut eine Verbindung zur MySQL Datenbank auf
     *
     * @access public
     * @return boolean
     */
    public function connect()
    {
        return $this->mInstance->connect();
    }

    /**
     * Schliesst die letzte Verbindung zur MySQL Datenbank
     *
     * @access public
     * @return boolean
     */
    public function disconnect()
    {
        return $this->mInstance->disconnect();
    }

    /**
     * Sendet einen Select Query an die Datenbank
     *
     * @access public
     * @param string $sqlstring            
     * @return false oder ResultSet
     */
    public function SelectQuery($sqlstring)
    {
        return $this->mInstance->SelectQuery($sqlstring);
    }

    /**
     * Sendet einen Nicht-Select-Query an die Datenbank
     *
     * @access public
     * @param string $sqlstring            
     * @return boolean
     */
    public function NonSelectQuery($sqlstring)
    {
        return $this->mInstance->NonSelectQuery($sqlstring);
    }

    /**
     * Gibt die Anzahl der Datensätze zurück, die die Abfrage liefert
     *
     * @access static
     * @param string $strSQL            
     * @return int - Anzahl betroffener Reihen
     */
    public function getMysqlNumRows($strSQL)
    {
        return $this->mInstance->getMysqlNumRows($strSQL);
    }

    /**
     * Gibt ein Datenbankobjekt zurück
     *
     * @return DB
     */
    public static function getInstance()
    {
        if (DB::$dbInstance == null) {
            DB::$dbInstance = new DB();
        }
        return DB::$dbInstance;
    }

    public function real_escape_string($pString)
    {
        return $this->mInstance->real_escape_string($pString);
    }
}
?>