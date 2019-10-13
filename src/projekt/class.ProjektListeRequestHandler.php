<?php

class ProjektListeRequestHandler
{

    /**
     * Verarbeitet die GET und POST Parameter eines Requests.<br/>
     * Wertet den Request aus und versucht Sortierreihenfolge für SQL und das TPL herauszufinden.<br/>
     * Ermittelt außerdem einen Suchbegriff und führt dann anhand der Parameter den Query aus <br/>
     *
     * @return array
     */
    public function prepareRequest()
    {
        $retVal = new HashTable();
        // Sortierüberschriften ausgeben
        if (! isset($_GET['dir']) || $_GET['dir'] == "desc") {
            $retVal->put("SQLDIR", "ASC");
            $retVal->put("TPLDIR", "desc");
        } else {
            $retVal->put("SQLDIR", "DESC");
            $retVal->put("TPLDIR", "asc");
        }
        
        if (isset($_GET['order'])) {
            $retVal->put("TPLORDER", $_GET['order']);
            switch ($_GET['order']) {
                case "gemeinde":
                    $retVal->put("SQLORDER", "g_kirche");
                    $retVal->put("GETTER", "getGID");
                    break;
                case "bez":
                    $retVal->put("SQLORDER", "proj_bezeichnung");
                    $retVal->put("GETTER", "getBezeichnung");
                    break;
                case "start":
                    $retVal->put("SQLORDER", "proj_start");
                    $retVal->put("GETTER", "getStart");
                    break;
                case "ende":
                    $retVal->put("SQLORDER", "proj_ende");
                    $retVal->put("GETTER", "getEnde");
                    break;
            }
        } else {
            $retVal->put("SQLORDER", "proj_sortierung");
            $retVal->put("TPLORDER", "sortierung");
            $retVal->put("GETTER", "getSortierung");
        }
        
        $sql = " ORDER BY " . $retVal->getValueOf("SQLORDER") . " " . $retVal->getValueOf("SQLDIR");
        
        $retVal->put("RESULT", $sql);
        
        return $retVal;
    }
}
?>