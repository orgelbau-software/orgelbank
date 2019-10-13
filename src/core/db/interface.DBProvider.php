<?php

interface DBProvider
{

    /**
     * Baut eine Verbindung zur MySQL Datenbank auf
     *
     * @access public
     * @return boolean
     */
    public function connect();

    /**
     * Schliesst die Verbindung zur MySQL Datenbank
     *
     * @access public
     * @return boolean
     */
    public function disconnect();

    /**
     * Sendet einen Select Query an die Datenbank
     *
     * @access public
     * @param string $sqlstring            
     * @return false oder ResultSet
     */
    public function SelectQuery($sqlstring);

    /**
     * Sendet einen Nicht-Select-Query an die Datenbank
     *
     * @access public
     * @param string $sqlstring            
     * @return boolean
     */
    public function NonSelectQuery($sqlstring);

    /**
     * Gibt die Anzahl der Datensätze zurück, die die Abfrage liefert
     *
     * @access static
     * @param string $strSQL            
     * @return int - Anzahl betroffener Reihen
     */
    public function getMysqlNumRows($strSQL);

    /**
     * Wrapper for the real database function to escape strings
     *
     * @param string $pString
     *            to escape
     */
    public function real_escape_string($pString);
}

?>