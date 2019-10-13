<?php

class ProjektRequestHandler
{

    public function prepareProjektDetails()
    {
        $retVal = array();
        $retVal['TPLORDER'] = '';
        $retVal['SQLORDER'] = '';
        $retVal['SQLADD'] = '';
        
        if (! isset($_GET['dir']) || $_GET['dir'] == "asc") {
            $retVal['SQLDIR'] = "asc";
            $retVal['TPLDIR'] = "desc";
        } else {
            $retVal['SQLDIR'] = "desc";
            $retVal['TPLDIR'] = "asc";
        }
        
        if (! isset($_GET['order'])) {
            $retVal['SQLORDER'] = "pr_datum";
        } elseif ($_GET['order'] == "datum") {
            $retVal['SQLORDER'] = "pr_datum";
        } elseif ($_GET['order'] == "lieferant") {
            $retVal['SQLORDER'] = "pr_lieferant";
        } elseif ($_GET['order'] == "nummer") {
            $retVal['SQLORDER'] = "pr_nummer";
        } elseif ($_GET['order'] == "betrag") {
            $retVal['SQLORDER'] = "pr_betrag";
        } elseif ($_GET['order'] == "aufgabe") {
            $retVal['SQLORDER'] = "au_id";
        } else {
            $retVal['SQLORDER'] = "pr_datum";
        }
        $retVal['TPLORDER'] = isset($_GET['order']) ? $_GET['order'] : "gemeinde";
        $retVal['SQLADD'] = ' ORDER BY ' . $retVal['SQLORDER'] . " " . $retVal['SQLDIR'];
        
        return $retVal;
    }
}
?>