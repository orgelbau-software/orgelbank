<?php
include "../../conf/config.inc.php";
if(isset($_GET['action']) && $_GET['action'] == "stundenzettel") {    
    
    session_start();
    $webUser = new WebBenutzer();
    $webUser->setBenutzername($_SESSION['user']['benutzername']);
    $webUser->setPasswort($_SESSION['user']['passwort']);
    
    ConstantLoader::performAutoload();
    if($webUser->login()) {
        
        if(isset($_GET['jahr']) && $_GET['jahr'] == "vorheriges") {
            $jahr = date("Y") - 1;
        } else {
            $jahr = date("Y");
        }
        RequestHandler::handle(new BenutzerStundenzettelAction($webUser->getBenutzer()->getID(), $jahr));
    } else {
        echo "fehler";
    }
} else {
    echo "unbekannt";
}
?>