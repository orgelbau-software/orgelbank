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
if (isset($_GET['action'])) {
    if ($_GET['action'] == "druck") {
        OrgelController::zeigeOrgelDruckansicht();
    } else if ($_GET['action'] == "wartungsplanung") {
        OrgelController::insertOrgelWartung();
    } else {
        if ($_GET['action'] == "export" && isset($_GET['format'])) {
            if ($_GET['format'] == "xls") {
                OrgelController::exportOrgelListeExcel();
            }
        }
    }
}
?>