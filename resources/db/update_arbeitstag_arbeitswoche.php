<pre>
<?php
include "../../conf/config.inc.php";

$db = DB::getInstance();
$db->connect();

$sql = "SELECT 
            *
        FROM 
            arbeitstag
        WHERE
            aw_id IS NULL
        ;";
$oDSOC = new DatabaseStorageObjektCollection();
if (($res = DB::getInstance()->SelectQuery($sql)) !== false) {
    foreach ($res as $objekt) {
        $tmp = new Arbeitstag();
        $tmp->doLoadFromArray($objekt);
        
        if (isset($objekt['selected'])) {
            $tmp->setSelected($objekt['selected']);
        }
        $tmp->setPersistent(true);
        $tmp->setChanged(false);
        $oDSOC->add($tmp);
    }
}

foreach ($oDSOC as $current) {
    echo $current->getDatum() . "\n";
    echo $current->getBenutzerId() . "\n";
    $wochenstart = strtotime($current->getDatum());
    if (date("D", $wochenstart) != "Mon") {
        echo "must calculate\n";
        $wochenstart = Date::berechneArbeitswocheTimestamp(strtotime($current->getDatum()))[0];
    }
    echo date("D YW d.m.Y", $wochenstart) . "\n";
    $kw = date("W", $wochenstart);
    $jahr = date("Y", $wochenstart);
    
    // $sql = "UPDATE arbeitstag at SET aw_id = (SELECT aw_id FROM arbeitswoche aw WHERE aw.aw_wochenstart = '".date("Y-m-d", $wochenstart)."' AND at.be_id = aw.be_id) WHERE at.at_id = ".$current->getID().";";
    $sql = "UPDATE arbeitstag at SET aw_id = (SELECT aw_id FROM arbeitswoche aw WHERE aw.aw_kw = '" . $kw . "' AND aw.aw_jahr = " . $jahr . " AND at.be_id = aw.be_id) WHERE at.at_id = " . $current->getID() . ";";
    echo $sql . "\n";
    $db->NonSelectQuery($sql);
    echo "---\n";
}