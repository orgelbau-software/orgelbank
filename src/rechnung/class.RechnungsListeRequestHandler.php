<?php

class RechnungsListeRequestHandler
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
            $retVal->put("SQLDIR", "DESC");
            $retVal->put("TPLDIR", "asc");
        } else {
            $retVal->put("SQLDIR", "ASC");
            $retVal->put("TPLDIR", "desc");
        }
        
        if (isset($_GET['order'])) {
            $retVal->put("TPLORDER", $_GET['order']);
            switch ($_GET['order']) {
                case "rechnungsnr":
                    $retVal->put("SQLORDER", "r_nummer");
                    $retVal->put("GETTER", "getNummer");
                    break;
                case "gemeinde":
                    $retVal->put("SQLORDER", "g_kirche");
                    $retVal->put("GETTER", "getGemeindeName");
                    break;
                case "nettobetrag":
                    $retVal->put("SQLORDER", "r_nettobetrag");
                    $retVal->put("GETTER", "getNettoBetrag");
                    break;
                case "bruttobetrag":
                    $retVal->put("SQLORDER", "r_bruttobetrag");
                    $retVal->put("GETTER", "getBruttoBetrag");
                    break;
                case "datum":
                    $retVal->put("SQLORDER", "r_datum");
                    $retVal->put("GETTER", "getDatum");
                    break;
            }
        } else {
            $retVal->put("SQLORDER", "r_datum");
            $retVal->put("TPLORDER", "rechnungsnr");
            $retVal->put("GETTER", "getDatum");
        }
        
        $sql = " ORDER BY " . $retVal->getValueOf("SQLORDER") . " " . $retVal->getValueOf("SQLDIR");
        
        $retVal->put("RESULT", $sql);
        
        return $retVal;
    }
}
?>