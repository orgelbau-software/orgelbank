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
if (($handle = fopen("Mapping.csv", "r")) !== FALSE) {
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
print_r($mapping);

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
        
        $id = $data[0];
        if($id != "" && $id > 0) {
            echo "Update " .$id;
            
            $gemeinde = new Gemeinde($id);
        } else {
            echo "New " .$id;
        }
    }
    fclose($handle);
}
?>
</pre>
</html>