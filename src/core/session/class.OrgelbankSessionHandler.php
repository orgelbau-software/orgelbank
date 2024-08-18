<?php

class OrgelbankSessionHandler implements SessionHandlerInterface 
{

    /**
     * Open function; Opens/starts session
     * Opens a connection to the database and stays open until specifically closed
     * This function is called first and with each page load
     *
     * @param string $s
     *            Session Path
     * @param string $n            
     * @return bool
     */
    public function open(string $savePath, string $sessionName) : bool {
        return true;
    }

    /**
     * Read function; downloads data from repository to current session
     * Queries the mysql database, unencrypts data, and returns it.
     * This function is called after 'open' with each page load
     *
     * @param unknown_type  $id            
     * @return unknown
     */
    public function read(string $id) : false|string
    {
        $query = "SELECT expire, data FROM http_session WHERE id='" . $id . "'";
        $instance = DB::getInstance();
        $res = $instance->SelectQuery($query);
        if ($res === false) {
            return ""; // must return string, not 'false'
        }
        $session_read = $res[0];
        
        if ($session_read['expire'] < time()) {
            Log::debug("session has expired");
            OrgelbankSessionHandler::destroy($id);
            return "";
        } else {
            return base64_decode($session_read['data']);
        }
    }

    /**
     * Write function; uploads data from current session to repository
     * Inserts/updates mysql records of current session.
     * Called after 'read'
     * with each page load
     *
     * @param string $id            
     * @param string $data            
     * @return TRUE if successul
     */
    public function write(string $id, string $data) : bool
        {
            
        if (! $data) {
            return false;
        }
        
        try {
            $expire = time() + SESSION_DEFAULT_EXPIRE;
            
            $db = DB::getInstance();
            $data = $db->real_escape_string(base64_encode($data));
            
            $query = "SELECT * FROM http_session WHERE id = '" . $id . "'";
            
            if ($db->getMysqlNumRows($query) == 1) {
                $query = "UPDATE http_session SET data='" . $data . "', expire=" . $expire . " WHERE id='" . $id . "'";
            } else {
                $query = "INSERT INTO http_session SET id='" . $id . "', data='" . $data . "', expire='" . $expire . "', session_start='" . time() . "'";
            }
            $db->NonSelectQuery($query);
            return true;
        } catch(Exception $e) {
            ExceptionHandler::handle($e);
            return false;
        }
    }

    /**
     * Close function; closes session closes mysql connection
     *
     * @return TRUE
     */
    public function close() : bool
    {
        return true;
    }

    /**
     * destroy function; deletes session data deletes records of current session.
     * called ONLY when function 'session_destroy()' is called
     *
     * @param String $id            
     * @return TRUE
     */
    public function destroy($id) : bool
    {
        $db = DB::getInstance();
        $db->connect();
        $query = "DELETE FROM http_session WHERE id='" . $id . "'";
        $db->NonSelectQuery($query);
        $db->disconnect();
        return true;
    }

    /**
     * Enter description here...
     *
     * @param int $maxLifeTime            
     */
    public function gc(int $maxLifeTime) : int|false
    {
        $query = "DELETE FROM http_session WHERE expire < " . time();
        $db = DB::getInstance();
        $db->connect();
        $db->NonSelectQuery($query);
        $count = $db->getAffectedRows();
        $db->disconnect();
        return ( $count > 0 ? $count : false);
    }
}
?>