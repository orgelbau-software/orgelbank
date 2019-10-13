var mapdetail = null;
var geocoderdetail = null;

function initialize() {
	if (GBrowserIsCompatible()) {
		mapdetail = new GMap2(document.getElementById("map_detail"));
		mapdetail.setCenter(new GLatLng(51.135416, 9.152985), 9);
		mapdetail.addControl(new GSmallMapControl());
		geocoderdetail = new GClientGeocoder();

	}
}

function showAddress(address) {
	if (geocoderdetail) {
		geocoderdetail.getLatLng(address, function(point) {
			if (point) {
				mapdetail.setCenter(point, 9);
				var marker = new GMarker(point);
				mapdetail.addOverlay(marker);
			}
		});
	}
}

function showAddressNurOrt(address) {
	var retVal = false;
	if (geocoderdetail) {
		geocoderdetail.getLatLng(address, function(point) {
			if (point) {
				mapdetail.setCenter(point, 15);
				var marker = new GMarker(point);
				mapdetail.addOverlay(marker);
			}
		});
	}
}