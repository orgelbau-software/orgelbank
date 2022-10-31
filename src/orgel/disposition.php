<?php
include_once '../../conf/config.inc.php';

// Ausgabe in der Orgeldetail ansicht
if (isset($_GET['ansicht']) && $_GET['ansicht'] == "liste") {
    DispositionController::zeigeDisposition();
} elseif (isset($_GET['action'], $_GET['term']) && $_GET['action'] == "ajax") {
    
    $db = DB::getInstance();
    $db->connect();
    
    $retVal = array();
    if ($_GET['term']) {
        $query = "SELECT DISTINCT 
	   					d_name
					FROM 
						disposition
					WHERE 
						d_name LIKE '" . $_GET['term'] . "%' AND
						d_name <> '" . $_GET['term'] . "'
					LIMIT 
						5";
        $results = $db->SelectQuery($query);
        
        $values = array();
        
        if ($results !== false) {
            foreach ($results as $current) {
                $retVal[] = $current['d_name'];
            }
        }
    }
    header('Content-Type: application/json;  charset=utf-8');
    echo json_encode($retVal);
} else if (isset($_GET['action'], $_GET['order']) && $_GET['action'] == "dispositionssort") {
    header('Content-Type: application/json');
    $tpl = DispositionController::ajaxSortiereDisposition();
    echo json_encode($tpl);
}
?>