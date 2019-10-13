<?php
/**
 * Script legt für jeden Mitarbeiter einen Datensatz für die nächste Arbeitswoche an.
 * 
 * Ausführungszeit: Jeden Montag um 0:00 Uhr
 */

// Config einbinden
include "../../conf/config.inc.php";
// SQL ausführen
$db = DB::getInstance();
$db->connect();

$sql = "SELECT * FROM arbeitswoche";
$r = $db->SelectQuery($sql);

?>
<pre>
<?php
foreach ($r as $obj) {
    $berechnet = ArbeitstagUtilities::berechneSummeWochenIstStunden(strtotime($obj['aw_wochenstart']), $obj['be_id']);
    $gespeichert = $obj['aw_stunden_ist'];
    if ($berechnet != $gespeichert && ! ($berechnet == "" && $gespeichert == 0)) {
        $sql = "UPDATE arbeitswoche SET aw_stunden_ist = " . $berechnet . ", aw_stunden_dif = aw_stunden_soll - " . $berechnet . " WHERE aw_id = " . $obj['aw_id'] . ";";
        
        echo "Gespeichert: " . $gespeichert . ", Berechnet: " . $berechnet . ", BenutzerId: " . $obj['be_id'] . ", Wochenstart: " . $obj['aw_wochenstart'] . "<br/>";
        echo $sql . "<br>";
        
        $db->NonSelectQuery($sql);
    }
}

$db->disconnect();

?>
</pre>