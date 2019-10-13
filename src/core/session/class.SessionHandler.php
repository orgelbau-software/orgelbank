<?php

class SessionHandler
{

    /**
     * Open function; Opens/starts session
     * Opens a connection to the database and stays open until specifically closed
     * This function is called first and with each page load
     *
     * @param String $s
     *            Session Path
     * @param unknown_type $n            
     * @return unknown
     */
    public static function open($s, $n)
    {
        return true;
    }

    /**
     * Read function; downloads data from repository to current session
     * Queries the mysql database, unencrypts data, and returns it.
     * This function is called after 'open' with each page load
     *
     * @param unknown_type $id            
     * @return unknown
     */
    public static function read($id)
    {
        $query = "SELECT expire, data FROM http_session WHERE id='" . $id . "'";
        $res = mysql_query($query);
        if (mysql_num_rows($res) != 1) {
            return ""; // must return string, not 'false'
        }
        $session_read = mysql_fetch_assoc($res);
        
        if ($session_read['expire'] < time()) {
            Log::debug("session has expired");
            SessionHandler::destroy($id);
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
     * @param String $id            
     * @param Array $data            
     * @return TRUE if successul
     */
    public static function write($id, $data)
    {
        if (! $data) {
            return false;
        }
        $expire = time() + SESSION_DEFAULT_EXPIRE;
        $data = mysql_real_escape_string(base64_encode($data));
        
        $query = "SELECT * FROM http_session WHERE id = '" . $id . "'";
        if (mysql_num_rows(mysql_query($query)) > 0) {
            $query = "UPDATE http_session SET data='" . $data . "', expire=" . $expire . " WHERE id='" . $id . "'";
        } else {
            $query = "INSERT INTO http_session SET id='" . $id . "', data='" . $data . "', expire='" . $expire . "', session_start='" . time() . "'";
        }
        mysql_query($query);
        return true;
    }

    /**
     * Close function; closes session closes mysql connection
     *
     * @return TRUE
     */
    public static function close()
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
    public static function destroy($id)
    {
        $query = "DELETE FROM http_session WHERE id='" . $id . "'";
        mysql_query($query);
        return true;
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $expire            
     */
    public static function gc($expire)
    {
        $query = "DELETE FROM http_session WHERE expire < " . time();
        mysql_query($query);
    }
}
?>