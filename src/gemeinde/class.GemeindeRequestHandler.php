<?php

class GemeindeRequestHandler
{

    /**
     * Verarbeitet die GET und POST Parameter eines Requests.<br/>
     * Wertet den Request aus und versucht Sortierreihenfolge für SQL und das TPL herauszufinden.<br/>
     * Ermittelt außerdem einen Suchbegriff und führt dann anhand der Parameter den Query aus <br/>
     *
     * @return array
     */
    public function prepareGemeindeListRequest()
    {
        $retVal = new HashTable();
        // Sortierüberschriften ausgeben
        if (! isset($_GET['dir']) || $_GET['dir'] == "asc") {
            $retVal->put("SQLDIR", "ASC");
            $retVal->put("TPLDIR", "desc");
        } else {
            $retVal->put("SQLDIR", "DESC");
            $retVal->put("TPLDIR", "asc");
        }
        
        // Suchzusatz
        $retVal->put("SUCHBEGRIFF", "");
        if (isset($_POST['suchstring'], $_POST['submit']) && $_POST['submit'] == "Suchen" && $_POST['suchstring'] != "Suchbegriff...") {
            $retVal->setValueOf("SUCHBEGRIFF", $_POST['suchstring']);
        }
        
        if (isset($_GET['order'])) {
            $retVal->put("TPLORDER", $_GET['order']);
            switch ($_GET['order']) {
                case "gemeinde":
                    $retVal->put("SQLORDER", "g_kirche");
                    $retVal->put("GETTER", "getKirche");
                    $retVal->put("SKALA", "ALPHA");
                    break;
                case "plz":
                    $retVal->put("SQLORDER", "ad_plz");
                    $retVal->put("SKALA", "PLZ");
                    $retVal->put("GETTER", "getGemeindePLZ");
                    break;
                case "ort":
                    $retVal->put("SQLORDER", "ad_ort");
                    $retVal->put("SKALA", "ALPHA");
                    $retVal->put("GETTER", "getGemeindeOrt");
                    break;
                case "konfession":
                    $retVal->put("SQLORDER", "k_id");
                    $retVal->put("SKALA", "PLZ");
                    $retVal->put("GETTER", "getKID");
                    break;
                default:
                    $retVal->put("SQLORDER", "b_id");
                    $retVal->put("SKALA", "NUMERIC");
                    $retVal->put("GETTER", "getGemeindeBezirk");
                    break;
            }
        } else {
            $standardSortierung = ConstantLoader::getGemeindeListeStandardSortierung();
            if($standardSortierung == "ort") {
                $retVal->put("SQLORDER", "ad_ort");
                $retVal->put("SKALA", "ALPHA");
                $retVal->put("GETTER", "getGemeindeOrt");
                $retVal->put("TPLORDER", "ort");
            } else {
                $retVal->put("SQLORDER", "g_kirche");
                $retVal->put("GETTER", "getKirche");
                $retVal->put("SKALA", "ALPHA");
                $retVal->put("TPLORDER", "gemeinde");
            }

        }
        
        if (isset($_GET['index'])) {
            if (! is_numeric($_GET['index']) && $_GET['index'] != "all" && ($retVal->getValueOf("SKALA") == "PLZ" || $retVal->getValueOf("SKALA") == "NUMERIC")) {
                $retVal->put("INDEX", "1");
            } elseif (is_numeric($_GET['index']) && $retVal->getValueOf("SKALA") == "ALPHA") {
                $retVal->put("INDEX", "a");
            } else {
                $retVal->put("INDEX", $_GET['index']);
            }
        } else {
            if ($retVal->getValueOf("SKALA") == "PLZ" || $retVal->getValueOf("SKALA") == "NUMERIC") {
                $retVal->put("INDEX", "1");
            } else {
                $retVal->put("INDEX", "a");
            }
        }
        
        $sql = "";
        if ($retVal->getValueOf("INDEX") != "all") {
            $sql .= " AND " . $retVal->getValueOf("SQLORDER") . " ";
            if ($retVal->getValueOf("SKALA") == "NUMERIC") {
                $sql .= "= " . $retVal->getValueOf("INDEX") . " ";
            } else {
                $sql .= "LIKE '" . $retVal->getValueOf("INDEX") . "%'";
            }
        }
        $sql .= " ORDER BY " . $retVal->getValueOf("SQLORDER") . " " . $retVal->getValueOf("SQLDIR");
        
        $retVal->put("RESULT", $sql);
        
        return $retVal;
    }
}
?>