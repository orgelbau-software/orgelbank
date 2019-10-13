var infowindow = null;

var directionsService;
var directionsDisplay;
var theMap;

function initMap() {
	var mapOptions = {
		zoom : 8,
		center : new google.maps.LatLng(firmenSitzLat, firmenSitzLng)
	}

	theMap = new google.maps.Map(document.getElementById('gemeinde-karte'),
			mapOptions);

	infowindow = new google.maps.InfoWindow({
		content : "...waiting"
	});

	google.maps.event.addListener(theMap, 'click', function(event) {
		if (infowindow && infowindow != null) {
			infowindow.close();
		}
	});

	directionsService = new google.maps.DirectionsService;
	directionsDisplay = new google.maps.DirectionsRenderer({
		draggable : true,
		suppressInfoWindows : false,
		hideRouteList : false,
		panel : directionsPanel
	});
	directionsDisplay.setMap(theMap);

	initMarkers();
	resetPolyLinesAndInfoWindows();
}

function calculateAndDisplayRoute(pStartLat, pStartLng, pEndLat, pEndLng,
		pStartOID, pEndOID) {
	directionsService.route({
		origin : new google.maps.LatLng(pStartLat, pStartLng),
		destination : new google.maps.LatLng(pEndLat, pEndLng),
		travelMode : google.maps.TravelMode.DRIVING,
		provideRouteAlternatives : true,
		drivingOptions : {
			departureTime : new Date(),
			trafficModel : google.maps.TrafficModel.PESSIMISTIC
		}
	}, function(response, status) {
		if (status === google.maps.DirectionsStatus.OK) {
			for ( var i = 0, len = response.routes.length; i < len; i++) {
				drawPath(response.routes[i], pStartOID, pEndOID);
			}
		} else {
			window.alert('Fehler beim Berechnen der Route ' + status);
		}
	});
}

var thePolyLines;
var theInfoWindows;
var infoWindowOffset;
var theRouteSteps;

function resetPolyLinesAndInfoWindows() {
	if (thePolyLines != null) {
		for (i = 0; i < thePolyLines.length; i++) {
			thePolyLines[i].setMap(null);
		}
	}
	if (theInfoWindows != null) {
		for (i = 0; i < theInfoWindows.length; i++) {
			theInfoWindows[i].close();
		}
	}
	thePolyLines = new Array();
	theInfoWindows = new Array();
	infoWindowOffset = 0;
	theRouteSteps = new Array();
}

function drawPath(pRoute, pStartOID, pEndOID) {
	var path = pRoute.overview_path;

	var colors = [ "#0094FF", "#7FC9FF", "#0094FF" ];
	// Display a polyline of the elevation path.
	var pathOptions = {
		path : path,
		strokeColor : colors[thePolyLines.length % colors.length],
		strokeWeight : 7,
		opacity : 0.7,
		map : theMap
	}
	routePolyline = new google.maps.Polyline(pathOptions);
	thePolyLines.push(routePolyline);

	theRouteSteps.push(pRoute.legs[0].steps);
	
	var currentInfoWindow = new google.maps.InfoWindow(
			{
				content : pRoute.legs[0].distance.text
						+ ", "
						+ pRoute.legs[0].duration.text
						+ " (<a target=\"_blank\" href=\"src/gemeinde/gemeinden.php?action=googlemaps&start="
						+ pStartOID + "&end=" + pEndOID + "\">Google Maps</a>)"
			});



	var targetElement = Math.round(pRoute.overview_path.length / 6 * (thePolyLines.length + 1));
	currentInfoWindow.setPosition(pRoute.overview_path[targetElement]);
	currentInfoWindow.open(theMap);
	theInfoWindows.push(currentInfoWindow);
}

function createContent(pOrgelId, pKirche, pOrt, pBezirk, pAnzahlRegister,
		pLetztePflege, pNaechstePflege, pPflegevertrag, pZyklus, pMassnahmen, pLat, pLng) {
	var retVal = ""
	retVal += "<div id='infowindowContent'>";
	retVal += "<h1>" + pKirche + "</h1>"
	retVal += " <div id='infowindowBody'>";
	retVal += "  <table class='infowindowOrgeldetails'>";
	retVal += "   <tr>";
	retVal += "    <th class='infowindowMassnahmeTh'>Ort:</th>";
	retVal += "    <td>" + pOrt + "</th>";
	retVal += "    <th class='infowindowMassnahmeTh'>Bezirk:</th>";
	retVal += "    <td>" + pBezirk + "</th>";
	retVal += "   </tr>";
	retVal += "   <tr>";
	retVal += "    <th class='infowindowMassnahmeTh'>Register:</th>";
	retVal += "    <td>" + pAnzahlRegister + "</th>";
	retVal += "    <th class='infowindowMassnahmeTh'>Letzte Pflege:</th>";
	retVal += "    <td>" + pLetztePflege + "</th>";
	retVal += "   </tr>";
	retVal += "   <tr>";
	retVal += "    <th class='infowindowMassnahmeTh'>Pflegevertrag:</th>";
	retVal += "    <td>" + pPflegevertrag + "</th>";
	retVal += "    <th class='infowindowMassnahmeTh'>Nächste Pflege:</th>";
	retVal += "    <td>" + pNaechstePflege + ", " + pZyklus + " Jahr(e)</th>";
	retVal += "   </tr>";
	retVal += "   <tr>";
	retVal += "    <th colspan='4' class='textLeft'>Notwendige Massnahmen:</th>";
	retVal += "   </tr>";
	retVal += "   <tr>";
	retVal += "    <td colspan='4' class='infowindowMassnahme'>" + pMassnahmen
			+ "</th>";
	retVal += "   </tr>";
	retVal += "  </table>";
	retVal += "  <br/>";
	retVal += "  <a class=\"buttonlink iconButton forwardButton\" href=\"index.php?page=2&do=21&oid="
			+ pOrgelId + "\" title=\"Orgeldetails\">Orgeldetails</a>";
	retVal += "  <a class=\"buttonlink iconButton forwardButton\" href=\"#\" title=\"Wartung eintragen\" id=\"jsWartungEintragenButton\" onclick=\"javascript:insertWartung("+pOrgelId+");return false;\">Wartung eintragen</a>";
	retVal += "  <br/>"
    retVal += "  <br/>"
	retVal += "  <a class=\"buttonlink iconButton routeStartButton\" href=\"javascript:setRouteStart( "
			+ pOrgelId + ", " + pLat + "," + pLng + ");\">Route Start</a>";
	retVal += "  <a class=\"buttonlink iconButton routeEndButton\" href=\"javascript:setRouteEnd( "
			+ pOrgelId + ", " + pLat + "," + pLng + ");\">Route Ende</a>";
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

function setRouteStart(pOid, pLat, pLng) {
	resetPolyLinesAndInfoWindows();
	document.getElementById('route_start_lat').value = pLat;
	document.getElementById('route_start_lng').value = pLng;
	document.getElementById('route_start_oid').value = pOid;
	infowindow.close();
}

function setRouteEnd(pOid, pLat, pLng) {
	infowindow.close();
	calculateAndDisplayRoute(document.getElementById('route_start_lat').value,
			document.getElementById('route_start_lng').value, pLat, pLng,
			document.getElementById('route_start_oid').value, pOid);
}

function insertWartung(pOid) {
	$.get("src/orgel/orgeln.php?action=wartungsplanung&oid=" + pOid,
			function(data) {
//				alert(data);
				jQuery("#jsWartungEintragenButton").html("Eingetragen!");
			});
}