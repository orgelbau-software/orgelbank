function copy() {
	document.getElementById("gemeinde2").value = document
			.getElementById("kirche").value;
	document.getElementById("strasse2").value = document
			.getElementById("strasse").value;
	document.getElementById("hsnr2").value = document.getElementById("hsnr").value;
	document.getElementById("plz2").value = document.getElementById("plz").value;
	document.getElementById("ort2").value = document.getElementById("ort").value;
	if (document.getElementById("konfession").value == 1) {
		document.getElementById("kirchenamt2").value = "Evangelische Kirchengemeinde";
	} else if (document.getElementById("konfession").value == 2) {
		document.getElementById("kirchenamt2").value = "Katholische Kirchengemeinde";
	} else if (document.getElementById("konfession").value == 4) {
		document.getElementById("kirchenamt2").value = "Evangelisch-Methodistischen Kirchengemeinde";
	} else if (document.getElementById("konfession").value == 5) {
		document.getElementById("kirchenamt2").value = "Evangelisch-Luthrischen Kirchengemeinde";
	} else {
		document.getElementById("kirchenamt2").value = "Kirchengemeinde";
	}
}