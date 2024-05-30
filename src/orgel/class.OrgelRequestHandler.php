<?php

class OrgelRequestHandler
{

    public function prepareOrgelListe() {
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
        if (isset($_POST['suchstring'], $_POST['submit']) && $_POST['submit'] == "Anzeigen") {
            $retVal->setValueOf("SUCHBEGRIFF", $_POST['suchstring']);
        } else if(isset($_SESSION['suchstring'])) {
            $retVal->setValueOf("SUCHBEGRIFF", $_SESSION['suchstring']);
        }

        $orgelStatus = array();
        if($_POST && $_POST['submit'] == "Anzeigen") {
            if(isset($_POST['neubauten'])   || (empty($_POST) && isset($_SESSION['suchbegriff']['neubau'])))      { $orgelStatus[] = Orgel::ORGEL_STATUS_ID_NEUBAU; }
            if(isset($_POST['renoviert'])   || (empty($_POST) && isset($_SESSION['suchbegriff']['renoviert'])))   { $orgelStatus[] = Orgel::ORGEL_STATUS_ID_RENOVIERT; }
            if(isset($_POST['restauriert']) || (empty($_POST) && isset($_SESSION['suchbegriff']['restauriert']))) { $orgelStatus[] = Orgel::ORGEL_STATUS_ID_RESTAURIERT; }
        }
        $retVal['ORGELSTATUS'] = $orgelStatus;

        if (isset($_GET['order'])) {
            $retVal->put("TPLORDER", $_GET['order']);
            switch ($_GET['order']) {
                case "erbauer":
                    $retVal->put("SQLORDER", "o_erbauer");
                    $retVal->put("GETTER", "getErbauer");
                    $retVal->put("SKALA", "ALPHA");
                    break;
                case "baujahr":
                    $retVal->put("SQLORDER", "o_baujahr");
                    $retVal->put("GETTER", "getBaujahr");
                    $retVal->put("SKALA", "FREE");
                    break;
                case "wartung":
                    $retVal->put("SQLORDER", "o_letztepflege");
                    $retVal->put("GETTER", "getGemeindeOrt");
                    $retVal->put("SKALA", "FREE");
                    break;
                case "gemeinde":
                    $retVal->put("SQLORDER", "g_kirche");
                    $retVal->put("GETTER", "getGemeindeNamen");
                    $retVal->put("SKALA", "ALPHA");
                    break;
                case "ort":
                    $retVal->put("SQLORDER", "ad_ort");
                    $retVal->put("GETTER", "getGemeindeOrt");
                    $retVal->put("SKALA", "ALPHA");
                    break;
                case "plz":
                    $retVal->put("SQLORDER", "ad_plz");
                    $retVal->put("GETTER", "getGemeindePLZ");
                    $retVal->put("SKALA", "PLZ");
                    break;
                case "bezirk":
                    $retVal->put("SQLORDER", "b_id");
                    $retVal->put("GETTER", "getGemeindeBezirk");
                    $retVal->put("SKALA", "NUMERIC");
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
                $retVal->put("GETTER", "getGemeindeNamen");
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
