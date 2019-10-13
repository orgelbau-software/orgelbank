function setDirections(pGemeindeId) {
	var elem = $('#routeerror');
	elem.hide();
	
	$.get("src/gemeinde/gemeinden.php?action=geocode&gid="+pGemeindeId, function(data) {
		document.getElementById("routedistance").value = data.distance;
		document.getElementById("routeduration").value = data.duration;
		if(data.rc != "OK" && data.rc != 0) {
			elem.addClass('statuserror');
			elem.show();
			document.getElementById("routeerror").innerHTML = data.message;
		}
		});
  }