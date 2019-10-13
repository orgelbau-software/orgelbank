<script>

var infowindow = null;

var directionsService;
var directionsDisplay; 
var theMap;
var firmenSitzLat = <!--FirmensitzLat-->;
var firmenSitzLng = <!--FirmensitzLng-->;

function initMarkers() {
  var markers = [];
  
  <!-- Marker -->
}
</script>

<script type="text/javascript" src="<!--InstanceUrl-->web/js/gemeindekarte.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=<!--GoogleAPIKey-->&callback=initMap" async defer></script>

<form name="gemeindekartefilter" action="index.php?page=1&do=8" method="post">
	Zeige <strong><!--OrgelAnzahlAnzeige--> von <!--OrgelAnzahlGesamt--></strong> Orgeln.
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="checkbox" class="checkbox" name="pflegevertrag" value="1" id="chkbox1" <!--checked1-->/><label for="chkbox1">Mit Pflegevertrag</label> 
	<input type="checkbox" class="checkbox" name="offenewartungen" value="1" id="chkbox2" <!--checked2-->/><label for="chkbox2">Offene Wartungen</label> 
	<input type="submit" class="button iconButton searchButton" name="submit" value="Anzeigen" />
</form>

<div id="directionsPanel"></div>
<!--Nicht in der Form -->
<input type="hidden" id="route_start_lat" value="" />
<input type="hidden" id="route_start_lng" value="" />
<input type="hidden" id="route_start_oid" value="" />
<hr/>
<div id="gemeinde-karte"></div>