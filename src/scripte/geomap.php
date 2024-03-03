<?php
include_once '../../conf/config.inc.php';
$db = DB::getInstance();
$db->connect();

$sql = "SELECT
			 o.o_id, o.o_anzahlregister, o.o_massnahmen, o.o_letztepflege, o.o_pflegevertrag, o.o_zyklus, g.g_kirche, g.b_id, a.*
		FROM 
			orgel o,
			gemeinde g,
			adresse a
		WHERE
			o.g_id = g.g_id AND
			g.g_kirche_aid = a.ad_id AND
			(a.ad_geostatus = 'OK' OR a.ad_geostatus = 'PARTIAL_OK') ";
if (isset($_GET['bid'])) {
    $sql .= " AND b_id = " . intval($_GET['bid']) . " ";
}

$sql .= " ORDER BY
		  ad_id
	   LIMIT 50;";
$q = mysql_query($sql);
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
<meta charset="utf-8">
<title>Simple markers</title>
<style>
html, body, #map-canvas {
	height: 100%;
	margin: 0px;
	padding: 0px
}

#infowindowContent th {
	text-align: right;
}

#infowindowContent th.textLeft {
	text-align: left;
}
</style>
<script
	src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=false"></script>
<script>


var infowindow = null;

function initialize() {
  var myLatlng = new google.maps.LatLng(51.711667,9.386389);
  var mapOptions = {
    zoom: 8,
    center: myLatlng
  }

  var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

  infowindow = new google.maps.InfoWindow({
    content: "...waiting"
  });
  

  
  var markers = [];
  <?php
while ($row = mysql_fetch_assoc($q)) {
    $hoverText = $row['g_kirche'] . ", " . $row['ad_ort'] . " - Bezirk: " . $row['b_id'] . " - Register: " . $row['o_anzahlregister'];
	$hoverText = mb_convert_encoding($hoverText, 'UTF-8', 'ISO-8859-1');
    ?>
  var marker = new google.maps.Marker({ position: new google.maps.LatLng(<?php echo $row['ad_lat'] ?>,<?php echo $row['ad_lng']?>), map: map, name : <?php echo $row['ad_id']; ?>, title: '<?php echo $hoverText; ?>'  });
     bindInfoWindow(marker, map, infowindow, createContent(<?php echo "'". mb_convert_encoding($row['g_kirche'], 'UTF-8', 'ISO-8859-1')."','". mb_convert_encoding($row['ad_ort'], 'UTF-8', 'ISO-8859-1')."','".$row['b_id']."','" .$row['o_anzahlregister']."','" .date("d.m.Y", strtotime($row['o_letztepflege']))."','" .$row['o_pflegevertrag']."','" .$row['o_zyklus']."','" .addslashes(str_replace("\r\n", " ", mb_convert_encoding($row['o_massnahmen'], 'UTF-8', 'ISO-8859-1')))."'"; ?>));
  <?php
}
?>
  
}
function createContent(pKirche, pOrt, pBezirk, pAnzahlRegister, pLetztePflege, pPflegevertrag, pZyklus, pMassnahmen) {
	var retVal = ""
	retVal += "<div id='infowindowContent'>";
	retVal += "<h1>"+pKirche+"</h1>"
	retVal += " <div id='infowindowBody'>";
	retVal += "  <table class='infowindowOrgeldetails'>";
	retVal += "   <tr>";
	retVal += "    <th>Ort:</th>";
	retVal += "    <td>"+pOrt+"</th>";
	retVal += "    <th>Bezirk:</th>";
	retVal += "    <td>"+pBezirk+"</th>";
	retVal += "   </tr>";
	retVal += "   <tr>";
	retVal += "    <th>Register:</th>";
	retVal += "    <td>"+pAnzahlRegister+"</th>";
	retVal += "    <th>Letzte Pflege:</th>";
	retVal += "    <td>"+pLetztePflege+"</th>";
	retVal += "   </tr>";
	retVal += "   <tr>";
	retVal += "    <th>Pflegevertrag:</th>";
	retVal += "    <td>"+pPflegevertrag+"</th>";
	retVal += "    <th>Zyklus:</th>";
	retVal += "    <td>"+pZyklus+" Jahr</th>";
	retVal += "   </tr>";
	retVal += "   <tr>";
	retVal += "    <th colspan='4' class='textLeft'>Notwendige Massnahmen:</th>";
	retVal += "   </tr>";
	retVal += "   <tr>";
	retVal += "    <td colspan='4'>"+pMassnahmen+"</th>";
	retVal += "   </tr>";
	retVal += "  </table>";
	retVal += " </div>"
	retVal += "</div>";
	return retVal;
}

function bindInfoWindow(marker, map, infowindow, description) {
    google.maps.event.addListener(marker, 'click', function() {
        infowindow.setContent(description);
      	  infowindow.open(map, marker);
    });
}

google.maps.event.addDomListener(window, 'load', initialize);

    </script>
</head>
<body>
	<div id="map-canvas"></div>
</body>
  <?php echo $sql;?>
</html>