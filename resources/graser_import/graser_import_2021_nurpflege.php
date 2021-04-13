<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8"/>
</head>
<pre>
<?php

include "../../conf/config.inc.php";

$db = DB::getInstance();
$db->connect();

error_reporting(E_ALL);

$c = OrgelUtilities::getOrgeln();

foreach($c as $current) {
    echo $current->getID() ." - ".$current->getPflegevertrag(). " - ".$current->getZyklus() . " - ".$current->getAnmerkung()."<br/>";
    if(strpos($current->getAnmerkung(), "STV: ") && strpos($current->getAnmerkung(), "###")) {
        $pflegevertrag = substr($current->getAnmerkung(), strpos($current->getAnmerkung(), "STV: ") + 5);
        $pflegevertrag = substr($pflegevertrag, 0, strpos($pflegevertrag, "###"));
        echo $pflegevertrag."<br/>";
        if($pflegevertrag == "n") {
            $current->setPflegeVertrag(0);
        } else {
            $current->setPflegeVertrag(1);
            $current->setZyklus($pflegevertrag);
        }
        
        $current->speichern(false);
    }
}

?>
</pre>
</html>