<?php

/**
 * 
 * @author stephan.watermeyer
 */
class MySQLiDBProvider implements DBProvider
{

    /**
     *
     * @var mysqli
     */
    private $mInstance;

    /**
     * zaehlt die abgesendeten Queries ueber die DB-Klasse
     *
     * @var int
     */
    public static $num_queries;

    public function __construct()
    {
        // $this->connect();
    }

    public function __destruct()
    {
        // $this->disconnect();
    }

    public function connect()
    {
        $retVal = true;
        try {
            Log::sql("connecting to mysql: host=" . MYSQL_HOST . ", user=" . MYSQL_USER);
            error_reporting(NULL);
            $this->mInstance = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB);
            if ($this->mInstance->connect_errno) {
                echo MYSQL_USER . "@" . MYSQL_HOST;
                echo "Error: " . $this->mInstance->connect_errno;
                throw new Exception("cannot connect to mysql, config=" . INSTALLATION_NAME);
            }
            
            Log::sql("connect successful");
            
            Log::sql("set charset to utf8");
            $this->mInstance->set_charset("utf8");
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
        // PHP will auto-close the connections
        // $boReturn = false;
        // if ($this->getInstance()->close()) {
        // $boReturn = true;
        // }
        // return $boReturn;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see DBProvider::SelectQuery()
     */
    public function SelectQuery($sqlstring)
    {
        $retVal = false;
        if (substr($sqlstring, 0, 6) == "SELECT") {
            // pre($sqlstring);
            DB::$num_queries ++;
            Log::sql($sqlstring);
            
            $result = $this->getInstance()->query($sqlstring);
            if ($result && $result->num_rows) {
                $retVal = array();
                while ($row = $result->fetch_assoc()) {
                    $retVal[] = $row;
                }
            } else if ($this->getInstance()->errno == 0) {
                // just no results
            } else {
                // $this->errormsg = $this->getInstance()->errno;
                throw new Exception("failed to query database: '" . $sqlstring . "', Error: " . $this->getInstance()->errno);
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
            
            if ($this->getInstance()->query($sqlstring)) {
                $retVal = true;
            } else if ($this->getInstance()->errno == 0) {
                // just no results
            } else {
                // $this->errormsg = $this->getInstance()->errno;
                throw new Exception("failed to insert into database: '" . $sqlstring . "', Error: " . $this->getInstance()->errno);
            }
        } else {
            throw new Exception("Kein NON-SELECT-Query (Update, Insert, Delete): " . $sqlstring . "', Error: " . $this->getInstance()->errno());
        }
        return $retVal;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see DBProvider::getMysqlNumRows()
     */
    public function getMysqlNumRows($strSQL)
    {
        $oDB = new MySQLiDBProvider();
        $res = $oDB->SelectQuery($strSQL);
        return ($res ? count($res) : false);
    }

    public function real_escape_string($pString)
    {
        return mysqli_real_escape_string($this->getInstance(), $pString);
    }

    public function getInstance()
    {
        if (null == $this->mInstance) {
            $this->connect();
        }
        return $this->mInstance;
    }
}

?>