<?php
include_once "../../conf/config.inc.php";



$retVal = array();
$retVal['http_status'] = "200";
$retVal['message'] = "";
if (isset($_REQUEST['key']) && $_REQUEST['key'] == ORGELBANK_API_KEY) {
    
    $db = DB::getInstance();
    $res = $db->SelectQuery("SELECT option_value FROM option_meta WHERE option_name = 'cronjob_last_execution'");
    $lastExecutionTimestamp =  $res[0]['option_value'];
    
    $context = stream_context_create(array(
        'http' => array(
            'ignore_errors' => true
        )
    ));
    
    
    if($lastExecutionTimestamp < time() - (24 * 60 * 1000)) {
        $msg = "done";
        
        $statusOK = true;
        $httpSessionJob = json_decode(file_get_contents(INSTANCE_URL . "/src/cronjobs/cronjob_http_session.php", false, $context));
        $retVal['http_session'] = $httpSessionJob;
        $statusOK &= ((($httpSessionJob->http_status == "200" || $httpSessionJob->http_status == "201") ? true : false)); 
        
        $benutzerVerlauf = json_decode(file_get_contents(INSTANCE_URL . "/src/cronjobs/cronjob_benutzerverlauf.php", false, $context));
        $retVal['benutzerverlauf'] = $benutzerVerlauf;
        $statusOK &= ((($benutzerVerlauf->http_status == "200" || $benutzerVerlauf->http_status == "201") ? true : false));
        
        $arbeitswocheAnlegen = json_decode(file_get_contents(INSTANCE_URL . "/src/cronjobs/cronjob_arbeitswoche_anlegen.php", false, $context));
        $retVal['arbeitswoche_anlegen'] = $arbeitswocheAnlegen;
        $statusOK &= ((($arbeitswocheAnlegen->http_status == "200" || $arbeitswocheAnlegen->http_status == "201") ? true : false));
        
        $arbeitswoche_korrektur = json_decode(file_get_contents(INSTANCE_URL . "/src/cronjobs/cronjob_arbeitstag_korrektur.php", false, $context));
        $retVal['arbeitswoche_korrektur'] = $arbeitswoche_korrektur;
        $statusOK &= ((($arbeitswoche_korrektur->http_status == "200" || $arbeitswoche_korrektur->http_status == "201") ? true : false));
        
        $arbeitswoche_korrektur = json_decode(file_get_contents(INSTANCE_URL . "/src/cronjobs/cronjob_arbeitswoche_abschliessen.php", false, $context));
        $retVal['arbeitswoche_abschluss'] = $arbeitswoche_korrektur;
        $statusOK &= ((($arbeitswoche_korrektur->http_status == "200" || $arbeitswoche_korrektur->http_status == "201") ? true : false));
        
        $geostatus = json_decode(file_get_contents(INSTANCE_URL . "/src/cronjobs/cronjob_gemeinde_geostatus.php", false, $context));
        $retVal['geostatus'] = $geostatus;
        $statusOK &= ((($geostatus->http_status == "200" || $geostatus->http_status == "201") ? true : false));
        
        if($statusOK) {
            $retVal['http_status'] = "200";
        } else {
            $retVal['http_status'] = "501";
        }
        $db->NonSelectQuery("UPDATE option_meta SET option_value = ".time()." WHERE option_name = 'cronjob_last_execution';");
    } else {
        $msg = "nothing to do.";
        $retVal['http_status'] = "202"; // Accepted
        $retVal['last_execution_time'] =  $lastExecutionTimestamp;
    }
    
    $db->disconnect();
    
    $retVal['message'] = $msg;
} else {
    $retVal['message'] = "good bye";
    $retVal['http_status'] = "401";
}

header('Content-Type: application/json');
http_response_code($retVal['http_status']);
echo json_encode($retVal);
?>