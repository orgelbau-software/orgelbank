<script type="text/javascript">
function copy() {
	document.getElementById("gemeinde2").value = document.getElementById("kirche").value;
	document.getElementById("strasse2").value = document.getElementById("strasse").value;
	document.getElementById("hsnr2").value = document.getElementById("hsnr").value;
	document.getElementById("plz2").value = document.getElementById("plz").value;
	document.getElementById("ort2").value = document.getElementById("ort").value;
	document.getElementById("rland").value = document.getElementById("land").value;
	if(document.getElementById("konfession").value == 0) {
		document.getElementById("kirchenamt2").value = "Kirchengemeinde";
	} 
	 else { 
		document.getElementById("kirchenamt2").value = "Kirchengemeinde";
	}
}
</script>