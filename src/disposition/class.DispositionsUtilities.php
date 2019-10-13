<?php

class DispositionsUtilities
{

    public static function updateDispositionsOrder($pDispositionsID, $pReihenfolge)
    {
        $sql = "UPDATE disposition SET d_reihenfolge = " . $pReihenfolge . " WHERE d_id = " . $pDispositionsID;
        $db = DB::getInstance();
        $db->connect();
        $db->NonSelectQuery($sql);
        $db->disconnect();
    }
}

?>