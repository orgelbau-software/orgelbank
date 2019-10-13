<?php

class OrgelRequestHandler
{

    public function prepareOrgelListe()
    {
        $retVal = array();
        $retVal['suchbegriff'] = array();
        $retVal['suchbegriff']['ost_id-1'] = '';
        $retVal['suchbegriff']['ost_id-2'] = '';
        $retVal['suchbegriff']['ost_id-3'] = '';
        $retVal['suchbegriff']['nichtzugeordnet'] = '';
        $retVal['suchbegriff']['submit'] = '';
        $retVal['TPLORDER'] = '';
        $retVal['SQLORDER'] = '';
        $retVal['SQLADD'] = '';
        if ($_POST) {
            $retVal['suchbegriff']['submit'] = $_POST['submit'];
            if ($_POST['submit'] == "Anzeigen") {
                if (isset($_POST['neubauten'])) {
                    $retVal['suchbegriff']['ost_id-1'] = '1';
                }
                if (isset($_POST['renoviert'])) {
                    $retVal['suchbegriff']['ost_id-2'] = '2';
                }
                if (isset($_POST['restauriert'])) {
                    $retVal['suchbegriff']['ost_id-3'] = '3';
                }
                if (isset($_POST['nichtzugeordnet'])) {
                    $retVal['suchbegriff']['nichtzugeordnet'] = '4';
                }
            }
        } elseif (isset($_SESSION['suchbegriff'])) {
            if (isset($_SESSION['suchbegriff']['ost_id-1']) && trim($_SESSION['suchbegriff']['ost_id-1']) != "") {
                $retVal['suchbegriff']['ost_id-1'] = '1';
            }
            if (isset($_SESSION['suchbegriff']['ost_id-2']) && trim($_SESSION['suchbegriff']['ost_id-2']) != "") {
                $retVal['suchbegriff']['ost_id-2'] = '2';
            }
            if (isset($_SESSION['suchbegriff']['ost_id-3']) && trim($_SESSION['suchbegriff']['ost_id-3']) != "") {
                $retVal['suchbegriff']['ost_id-3'] = '3';
            }
            if (isset($_SESSION['suchbegriff']['nichtzugeordnet']) && trim($_SESSION['suchbegriff']['nichtzugeordnet']) != "") {
                $retVal['suchbegriff']['nichtzugeordnet'] = '4';
            }
        }
        
        if (! isset($_GET['dir']) || $_GET['dir'] == "asc") {
            $retVal['SQLDIR'] = "asc";
            $retVal['TPLDIR'] = "desc";
        } else {
            $retVal['SQLDIR'] = "desc";
            $retVal['TPLDIR'] = "asc";
        }
        
        if (! isset($_GET['order'])) {
            $retVal['SQLORDER'] = "g_kirche";
            $retVal['GETTER'] = "getGemeindeNamen";
            $retVal['SKALA'] = "ALPHA";
        } elseif ($_GET['order'] == "erbauer") {
            $retVal['SQLORDER'] = "o_erbauer";
            $retVal['GETTER'] = "getErbauer";
            $retVal['SKALA'] = "ALPHA";
        } elseif ($_GET['order'] == "baujahr") {
            $retVal['SQLORDER'] = "o_baujahr";
            $retVal['GETTER'] = "getBaujahr";
            $retVal['SKALA'] = "FREE";
        } elseif ($_GET['order'] == "wartung") {
            $retVal['SQLORDER'] = "o_letztepflege";
            $retVal['GETTER'] = "getLetztePflege";
            $retVal['SKALA'] = "FREE";
        } elseif ($_GET['order'] == "gemeinde") {
            $retVal['SQLORDER'] = "g_kirche";
            $retVal['GETTER'] = "getGemeindeNamen";
            $retVal['SKALA'] = "ALPHA";
        } elseif ($_GET['order'] == "plz") {
            $retVal['SQLORDER'] = "ad_plz";
            $retVal['GETTER'] = "getGemeindePLZ";
            $retVal['SKALA'] = "PLZ";
        } elseif ($_GET['order'] == "ort") {
            $retVal['SQLORDER'] = "ad_ort";
            $retVal['GETTER'] = "getGemeindeOrt";
            $retVal['SKALA'] = "ALPHA";
        } elseif ($_GET['order'] == "bezirk") {
            $retVal['SQLORDER'] = "b_id";
            $retVal['GETTER'] = "getGemeindeBezirk";
            $retVal['SKALA'] = "NUMERIC";
        }
        $retVal['TPLORDER'] = isset($_GET['order']) ? $_GET['order'] : "gemeinde";
        $retVal['SQLADD'] = ' ORDER BY ' . $retVal['SQLORDER'] . " " . $retVal['SQLDIR'];
        
        if (isset($_GET['index'])) {
            if (! is_numeric($_GET['index']) && $_GET['index'] != "all" && ($retVal['SKALA'] == "PLZ" || $retVal['SKALA'] == "NUMERIC")) {
                $retVal['INDEX'] = "1";
            } elseif (is_numeric($_GET['index']) && $retVal['SKALA'] == "ALPHA") {
                $retVal['INDEX'] = "a";
            } else {
                $retVal['INDEX'] = $_GET['index'];
            }
        } else {
            if ($retVal['SKALA'] == "PLZ" || $retVal['SKALA'] == "NUMERIC") {
                $retVal['INDEX'] = "1";
            } else {
                $retVal['INDEX'] = "a";
            }
        }
        
        // Suche einschränken
        if ($retVal['suchbegriff']['ost_id-1'] != '' || $retVal['suchbegriff']['ost_id-2'] != '' || $retVal['suchbegriff']['ost_id-3'] != '' || $retVal['suchbegriff']['nichtzugeordnet']) {
            $strWhere = " AND (";
            $isFirst = true;
            foreach ($retVal['suchbegriff'] as $key => $val) {
                if ($val != "") {
                    if ($key != "submit") {
                        if (! $isFirst) {
                            $strWhere .= " OR ";
                        }
                        if ($key == "nichtzugeordnet") {
                            $strWhere .= " o.g_id IS NULL ";
                        } else {
                            $strWhere .= " ost_id = " . $val;
                        }
                        $isFirst = false;
                    }
                }
            }
            $strWhere .= ")";
            $retVal['SQLADD'] = $strWhere . $retVal['SQLADD'];
        }
        $_SESSION['suchbegriff'] = $retVal['suchbegriff'];
        
        // Index hinzufuegen
        $sql = "";
        if ($retVal['INDEX'] != "all") {
            $sql .= " AND " . $retVal['SQLORDER'] . " ";
            if ($retVal['SKALA'] == "NUMERIC") {
                $sql .= " = " . $retVal['INDEX'];
            } else {
                $sql .= " LIKE '" . $retVal['INDEX'] . "%'";
            }
            $retVal['SQLADD'] = $sql . $retVal['SQLADD'];
        }
        return $retVal;
    }
}
?>