var mapdetail = null;
var geocoder = null;

function initialize(pLat, pLng) {
	geocoder = new google.maps.Geocoder();
	var mapOptions = {
		center : new google.maps.LatLng(pLat, pLng),
		zoom : 12,
		mapTypeId : google.maps.MapTypeId.ROADMAP
	};
	mapdetail = new google.maps.Map(document.getElementById("map_detail"), mapOptions);
}

function showAddress(address, pLat, pLng) {
	if (pLat && pLat != null) {
		var pos = new google.maps.LatLng(pLat,pLng);
		mapdetail.setCenter(pos);
		var marker = new google.maps.Marker({
			map : mapdetail,
			position : pos
		});
	} else {
		geocoder.geocode({
			'address' : address
		}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				mapdetail.setCenter(results[0].geometry.location);
				var marker = new google.maps.Marker({
					map : mapdetail,
					position : results[0].geometry.location
				});
			} else {
//				alert('Geocode was not successful for the following reason: '
//						+ status);
			}
		});
	}
}

function showAddressNurOrt(address) {
	geocoder.geocode({
		'address' : address
	}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			mapdetail.setCenter(results[0].geometry.location);
			var marker = new google.maps.Marker({
				map : mapdetail,
				position : results[0].geometry.location
			});
		} else {
//			alert('Geocode was not successful for the following reason: '
//					+ status);
		}
	});
}