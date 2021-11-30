<?php

class ProjektRequestHandler
{

    public function prepareProjektDetails()
    {
        $projektRechnung = $this->handleProjektRechnung();
        $nebenkostenRechnung = $this->handleNebenkostenRechnung();
        
        return array("PR" => $projektRechnung, "NK" => $nebenkostenRechnung);
    }
    
    private function handleProjektRechnung() {
        $retVal = array();
        $retVal['TPLORDER'] = '';
        $retVal['SQLORDER'] = '';
        $retVal['SQLADD'] = '';
        
        if (! isset($_GET['prdir']) || $_GET['prdir'] == "asc") {
            $retVal['SQLDIR'] = "asc";
            $retVal['TPLDIR'] = "desc";
        } else {
            $retVal['SQLDIR'] = "desc";
            $retVal['TPLDIR'] = "asc";
        }
        
        if (! isset($_GET['prorder'])) {
            $retVal['SQLORDER'] = "pr_datum";
        } elseif ($_GET['prorder'] == "datum") {
            $retVal['SQLORDER'] = "pr_datum";
        } elseif ($_GET['prorder'] == "lieferant") {
            $retVal['SQLORDER'] = "pr_lieferant";
        } elseif ($_GET['prorder'] == "nummer") {
            $retVal['SQLORDER'] = "pr_nummer";
        } elseif ($_GET['prorder'] == "betrag") {
            $retVal['SQLORDER'] = "pr_betrag";
        } elseif ($_GET['prorder'] == "aufgabe") {
            $retVal['SQLORDER'] = "au_id";
        } else {
            $retVal['SQLORDER'] = "pr_datum";
        }
        $retVal['TPLORDER'] = isset($_GET['prorder']) ? $_GET['prorder'] : "gemeinde";
        $retVal['SQLADD'] = ' ORDER BY ' . $retVal['SQLORDER'] . " " . $retVal['SQLDIR'];
        
        return $retVal;
    }
    
    private function handleNebenkostenRechnung() {
        $retVal = array();
        $retVal['TPLORDER'] = '';
        $retVal['SQLORDER'] = '';
        $retVal['SQLADD'] = '';
        
        if (! isset($_GET['nkdir']) || $_GET['nkdir'] == "asc") {
            $retVal['SQLDIR'] = "asc";
            $retVal['TPLDIR'] = "desc";
        } else {
            $retVal['SQLDIR'] = "desc";
            $retVal['TPLDIR'] = "asc";
        }
        
        if (! isset($_GET['nkorder'])) {
            $retVal['SQLORDER'] = "nk_datum";
        } elseif ($_GET['nkorder'] == "datum") {
            $retVal['SQLORDER'] = "nk_datum";
        } elseif ($_GET['nkorder'] == "lieferant") {
            $retVal['SQLORDER'] = "nk_lieferant";
        } elseif ($_GET['nkorder'] == "nummer") {
            $retVal['SQLORDER'] = "nk_nummer";
        } elseif ($_GET['nkorder'] == "betrag") {
            $retVal['SQLORDER'] = "nk_betrag";
        } else {
            $retVal['SQLORDER'] = "nk_datum";
        }
        $retVal['TPLORDER'] = isset($_GET['nkorder']) ? $_GET['nkorder'] : "gemeinde";
        $retVal['SQLADD'] = ' ORDER BY ' . $retVal['SQLORDER'] . " " . $retVal['SQLDIR'];
        
        return $retVal;
    }
}
?>