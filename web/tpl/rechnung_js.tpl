<script type="text/javascript">
    var mwstSatz = <!--MwStSatz-->;
    
	function rechnungEinleitung(art, zielFeldId) {
		var titel;
		titel = document.getElementById("gemeindebezeichnung").value;
			
		if(art == 'pflege') {
			document.getElementById(zielFeldId).value = "<!--ScriptPflegeText-->";
		}
		if(art == 'auftrag') {
			document.getElementById(zielFeldId).value = "<!--ScriptAuftragText-->";
		}
		if(art == 'angebot') {
			document.getElementById(zielFeldId).value = "<!--ScriptAngebotText-->";
		}
		if(art == 'pflegehaupt') {
			document.getElementById(zielFeldId).value = "<!--ScriptPflegeHaupt-->";
		}
		if(art == 'pflegeneben') {
			document.getElementById(zielFeldId).value = "<!--ScriptPflegeNeben-->";
		}
		
	}
</script>
<script type="text/javascript" src="<!--InstanceUrl-->lib/javascript/dropdown.js"></script>
