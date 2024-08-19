<?php
include_once '../../conf/config.inc.php';
$oDB = DB::getInstance();
$oDB->connect();

session_start();

ConstantLoader::performAutoload();

$user = new WebBenutzer();
if ($user->validateSession() == false) {
    die("keine gueltige session");
}

// Ausgabe in der Orgeldetail ansicht
if (isset($_GET['action'], $_GET['term']) && $_GET['action'] == "ajax") {
    
    $retVal = array();
    if ($_GET['term'] && $_GET['term'] != "") {

        $term = htmlspecialchars($_GET['term']);

        $query = "SELECT DISTINCT 
	   					d_name
					FROM 
						disposition
					WHERE 
						d_name LIKE '" .$term . "%' AND
						d_name <> '" . $term . "'
					LIMIT 
						5";
        $results = $oDB->SelectQuery($query);
        
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

$oDB->disconnect();
?>