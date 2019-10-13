<?php
include_once '../../conf/config.inc.php';
$db = DB::getInstance();
$db->connect();

$retVal = array();
$retVal['select'] = "SELECT * FROM http_session WHERE expire < " . time() . ";";
$retVal['delete'] = "DELETE   FROM http_session WHERE expire < " . time() . ";";

$res = $db->SelectQuery($retVal['select']);
$retVal['select_count_before'] = ($res ? count($res) : 0);

$db->NonSelectQuery($retVal['delete']);

$res = $db->SelectQuery($retVal['select']);
$retVal['select_count_after'] = ($res ? count($res) : 0);

$db->disconnect();

header('Content-Type: application/json');
echo json_encode($retVal);