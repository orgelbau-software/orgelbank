TRUNCATE TABLE wartung;

<?php


$sql = "INSERT INTO `wartung` (`o_id`, `w_datum`, `w_bemerkung`, `w_temperatur`, `w_luftfeuchtigkeit`, `w_stimmton`, `w_stimmung`, `m_id_1`, `m_id_2`, `m_id_3`, `w_ma1_iststd`, `w_ma2_iststd`, `w_ma3_iststd`, `w_ma1_faktstd`, `w_ma2_faktstd`, `w_ma3_faktstd`, `w_tastenhalter`, `w_material`, `w_abrechnungsart`, `w_lastchange`, `w_changeby`, `w_createdate`) VALUES (<!--OrgelId-->, '<!--Datum-->', 'keine', '<!--Temperatur-->', '<!--Luft-->', '443', <!--Stimmung-->, 1, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 'Keins', 1, '2016-11-01 12:00:13', 'swatermeyer', '2016-11-01 12:00:13');";

$luft= array(55,54,53);
$temp = array(16.5, 17, 18);
$stimmung = array(0,1,2);

for($i = 0; $i < 34; $i++) {
    $theSQL = str_replace("<!--OrgelId-->", $i, $sql);
    for($j = 0;$j < 3; $j++) {
        $theSQL2 = $theSQL;
        $theSQL2 = str_replace("<!--Datum-->", date("Y-m-d",mktime(0,0,0,1,mt_rand(1,1*365),2014+$j)) , $theSQL2);
        $theSQL2 = str_replace("<!--Temperatur-->", $temp[$j], $theSQL2);
        $theSQL2 = str_replace("<!--Luft-->", $luft[$j], $theSQL2);
        $theSQL2 = str_replace("<!--Stimmung-->", $stimmung[$j], $theSQL2);
        echo $theSQL2."\r\n";
    }
    echo "\r\n";
}