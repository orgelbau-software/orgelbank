<?php

/**
 * Stellt Verbindung zu einer  MySQL DB her, Sendet Querys, Returnt Results
 *
 * @author swatermeyer
 * @since 09.05.2006
 * @version $Revision: 1.5 $
 *
 */
class MySQLDBProvider implements DBProvider
{

    /**
     * zaehlt die abgesendeten Queries ueber die DB-Klasse
     *
     * @var int
     */
    public static $num_queries;

    public function __construct()
    {}

    public function connect()
    {
        $retVal = true;
        try {
            Log::sql("connecting to mysql: host=" . MYSQL_HOST . ", user=" . MYSQL_USER);
            error_reporting(NULL);
            if (! mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS)) {
                echo MYSQL_HOST;
                echo MYSQL_USER;
                echo mysql_error();
                throw new Exception("cannot connect to mysql, config=" . INSTALLATION_NAME);
            }
            Log::sql("selecting db=" . MYSQL_DB);
            if (! mysql_select_db(MYSQL_DB)) {
                throw new Exception("cannot select database, config=" . INSTALLATION_NAME);
            }
            Log::sql("connect successful");
            
            Log::sql("set charset to utf8");
            mysql_set_charset("utf8");
        } catch (Exception $catched) {
            Log::error("mysql connect failed");
            $retVal = false;
            error_reporting(ERROR_REPORTING_LVL);
            throw $catched;
        }
        error_reporting(ERROR_REPORTING_LVL);
        return $retVal;
    }

    public function disconnect()
    {
        $boReturn = false;
        if (mysql_close()) {
            $boReturn = true;
        }
        return $boReturn;
    }

    public function SelectQuery($sqlstring)
    {
        
        // Klassenvariable leeren
        $retVal = false;
        
        if (substr($sqlstring, 0, 6) == "SELECT") {
            // pre($sqlstring);
            DB::$num_queries ++;
            Log::sql($sqlstring);
            $result = mysql_query($sqlstring);
            $retVal = array();
            if ($result && mysql_num_rows($result)) {
                while ($row = mysql_fetch_assoc($result)) {
                    $retVal[] = $row;
                }
            } else {
                throw new Exception("failed to query database: '" . $sqlstring . "', Error: " . mysql_error());
            }
        }
        
        return $retVal;
    }

    public function NonSelectQuery($sqlstring)
    {
        $retVal = false;
        Log::sql($sqlstring);
        if (substr($sqlstring, 0, 6) != "SELECT" && trim($sqlstring) != "") {
            DB::$num_queries ++;
            if (mysql_query($sqlstring)) {
                $retVal = true;
            } else if (mysql_error() == 0) {
                // just no results
            } else {
                throw new Exception("failed to insert into database: '" . $sqlstring . "', Error: " . mysql_error());
            }
        } else {
            throw new Exception("Kein NON-SELECT-Query (Update, Insert, Delete): " . $sqlstring . "', Error: " . mysql_error());
        }
        return $retVal;
    }

    public function getMysqlNumRows($strSQL)
    {
        $oDB = new MySQLDBProvider();
        $oDB->SelectQuery($strSQL);
        $i = $oDB->getCountRows();
        return $i;
    }

    public function real_escape_string($pString)
    {
        return mysql_real_escape_string($pString);
    }
}
?>
