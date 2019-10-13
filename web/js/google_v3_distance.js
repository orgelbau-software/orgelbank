var gdir;
var error;
var directionsService = new google.maps.DirectionsService();

function setDirections(fromAddress, toAddress) {
	var service = new google.maps.DistanceMatrixService();
    service.getDistanceMatrix(
      {
        origins: [fromAddress],
        destinations: [toAddress],
        travelMode: google.maps.TravelMode.DRIVING,
        unitSystem: google.maps.UnitSystem.METRIC,
        avoidHighways: false,
        avoidTolls: false
      }, callback);
  }

  function callback(response, status) {
    if (status != google.maps.DistanceMatrixStatus.OK) {
      alert('Error was: ' + status);
    } else {
      var origins = response.originAddresses;
      var destinations = response.destinationAddresses;

      for (var i = 0; i < origins.length; i++) {
        var results = response.rows[i].elements;
        for (var j = 0; j < results.length; j++) {
          setDistances(results[j].duration.value, results[j].distance.value);
        }
      }
    }
  }

function setDistances(duration, distance) {
	distance = distance / 1000;
	distance = round(distance, 2);

	duration = duration / 60;
	duration = duration / 60;
	duration = round(duration, 2);

	document.getElementById("routedistance").value = distance;
	document.getElementById("routeduration").value = duration;
}

function handleErrors() {
	if (gdir.getStatus().code == G_GEO_UNKNOWN_ADDRESS)
		error = "Adresse nicht gefunden";
	else if (gdir.getStatus().code == G_GEO_SERVER_ERROR)
		error = "Dienst nicht verfügbar";
	else if (gdir.getStatus().code == G_GEO_MISSING_QUERY)
		error = "Programmfehler";
	else if (gdir.getStatus().code == G_GEO_BAD_KEY)
		error = "Konfigurationsfehler";
	else if (gdir.getStatus().code == G_GEO_BAD_REQUEST)
		error = "Kann Adresse nicht verarbeiten";
	else
		error = "Unbekannter Fehler";
	$('#routeerror').addClass('statuserror');
	document.getElementById("routeerror").innerHTML = error;
}

function round(x) {
	var a = Math.pow(10, 2);
	return (Math.round(x * a) / a).toFixed(2);
}