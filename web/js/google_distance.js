var gdir;
var error;

function setDirections(fromAddress, toAddress) {
	if (GBrowserIsCompatible()) {
		if (gdir == null) {
			gdir = new GDirections();
			GEvent.addListener(gdir, "load", onGDirectionsLoad);
			GEvent.addListener(gdir, "error", handleErrors);
		}
		document.getElementById("routedistance").value = 0;
		document.getElementById("routeduration").value = 0;
		gdir.load("from: " + fromAddress + " to: " + toAddress, {
			"locale" :"de_DE"
		});
	} else {
		document.getElementById("routeerror").innerHTML = "Browser wird nicht unterstützt";
	}
}

function onGDirectionsLoad() {
	distance = gdir.getDistance().meters;
	distance = distance / 1000;
	distance = round(distance, 2);

	duration = gdir.getDuration().seconds;
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