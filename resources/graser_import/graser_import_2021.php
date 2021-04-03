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
//$sql = array();
//$sql[] = "TRUNCATE TABLE benutzer;";
////$sql[] = "TRUNCATE TABLE adresse;";
//$sql[] = "TRUNCATE TABLE ansprechpartner;";
//$sql[] = "TRUNCATE TABLE disposition;";
////$sql[] = "TRUNCATE TABLE gemeinde;";
////$sql[] = "TRUNCATE TABLE orgel;";
//$sql[] = "TRUNCATE TABLE wartung;";
//$sql[] = "TRUNCATE TABLE gemeindeansprechpartner;";
//foreach ($sql as $key => $val) {
//    echo $val . "<br>";
//    $db->NonSelectQuery($val);
//}

error_reporting(E_ALL);

// Mapping
$row = 1;

$mapping = array();
if (($handle = fopen("mapping.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        $num = count($data);
        $item = array();
        $item['nummer'] = $data[3];
        $item['ansprechpartnerId'] = $data[2];
        $item['orgelId'] = $data[1];
        $item['gemeindeId'] = $data[0];
        $mapping[$item['nummer']] = $item;
    }
    fclose($handle);
}
//print_r($mapping);

// Eigentlicher Import
$row = 1;

if (($handle = fopen("Graser2021_v1.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        $num = count($data);
        echo "<p> $num Felder in Zeile $row: <br /></p>\n";
        $row ++;
        for ($c = 0; $c < $num; $c ++) {
            echo $c . ":" . $data[$c] . "<br />\n";
        }
        
        if($row == 2) {
            continue;
        }
        
        $nummer = $data[0];        
        
        if(isset($mapping[$nummer])) {
            $item = $mapping[$nummer];
            $id = $item['gemeindeId'];
        
            echo "Update " .$id;
            $gemeinde = new Gemeinde($item['gemeindeId']);
            $orgel = new Orgel($item['orgelId']);

            
        } else {
            echo "New " .$nummer;
            $orgel = new Orgel();
            $orgel->setAktiv(1);
            $gemeinde = new Gemeinde();
            $gemeinde->setAktiv(1);
            
        }
        
        $orgel->setErbauer($data[2]);
        $orgel->setBaujahr($data[3]);
        
        $letztePflege = $data[4];
        $letztePflege = str_replace(" ", "", $letztePflege);
        if(strpos($letztePflege, "/")) {
            $letztePflege = str_replace("/",".", $letztePflege);
            $letztePflege = "01.".$letztePflege;
        }
        $orgel->setLetztePflege($letztePflege);
        
        $anmerkung = $orgel->getAnmerkung();
        if(strpos($anmerkung, "###") > 0) {
            $anmerkung = substr($anmerkung, strpos($anmerkung, "###") + 3);
        }
        
        $anmerkung = "Manuale: ".$data[5].", Register: " .$data[6].", STV: ".$data[13]."###";
        $orgel->setAnmerkung($anmerkung);
        
        $orgel->setRegisterAnzahl($data[6]);
        
        $betrag1 = str_replace("€", "", $data[14]);
        $betrag1 = str_replace(".", ",", $betrag1);
        $betrag2 = str_replace("€", "", $data[15]);
        $betrag2 = str_replace(".", ",", $betrag2);
        $orgel->setKostenHauptstimmung($betrag1);
        $orgel->setKostenTeilstimmung($betrag2);
        $orgel->speichern(true);
        
        $gemeinde->setKirche($data[1]);
        $gemeinde->getKircheAdresse()->setPLZ($data[7]);
        $gemeinde->getKircheAdresse()->setOrt($data[8]);
        $gemeinde->speichern(true);
        
        if(isset($mapping[$nummer])) {
            unset($mapping[$nummer]);
        } else {
            if(strpos($data[1], "Kath.") >= 0) {
                $gemeinde->setKID(2);
            } else if (strpos($data[1], "Evang.") >= 0) {
                $gemeinde->setKID(1);
            } else {
                $gemeinde->setKID(3);
            }
            $orgel->setGemeindeId($gemeinde->getID());
            $orgel->speichern(false);
            $gemeinde->speichern(false);
            unset($mapping[$nummer]);
        }
            
    }
    fclose($handle);
}

// Uebrige Mappings loeschen
foreach($mapping as $key => $val) {
    if(isset($val['gemeindeId']) && $val['gemeindeId'] >= 0) {
        $gemeinde = new Gemeinde($val['gemeindeId']);
        $gemeinde->setAktiv(0);
        $gemeinde->speichern(false);
    }
    
    if(isset($val['orgelId']) && $val['orgelId'] >= 0) {
        $orgel = new Orgel($val['orgelId']);
        $orgel->setAktiv(0);
        $orgel->speichern(false);
    }
}

print_r($mapping);
?>
</pre>
</html>