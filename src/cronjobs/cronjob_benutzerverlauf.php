<?php
include_once '../../conf/config.inc.php';
$db = DB::getInstance();
$db->connect();

$retVal = array();
$retVal['http_status'] = "200";

$retVal['select'] = "SELECT * FROM benutzerverlauf WHERE bv_createdate < DATE_SUB(NOW(), INTERVAL 90 DAY);";
$retVal['delete'] = "DELETE   FROM benutzerverlauf WHERE bv_createdate < DATE_SUB(NOW(), INTERVAL 90 DAY);";

$res = $db->SelectQuery($retVal['select']);
$retVal['select_count_before'] = ($res ? count($res) : 0);

$db->NonSelectQuery($retVal['delete']);

$res = $db->SelectQuery($retVal['select']);
$retVal['select_count_after'] = ($res ? count($res) : 0);

$db->disconnect();

header('Content-Type: application/json');
http_response_code($retVal['http_status']);
echo json_encode($retVal);