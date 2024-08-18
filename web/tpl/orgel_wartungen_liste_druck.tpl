<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<style type="text/css">
	body { font-family: Arial, Verdana;}
	a { color: #000000; text-decoration: none;}
	table{border:1px solid black; border-collapse: separate;}
	th{text-align: left;}
	td{border-bottom: 1px solid black; padding: 0px 10px 3px 0;}
	tr{border-bottom: 1px solid black;}
	.right{text-align:right;border-bottom: 1px solid black; padding-bottom: 3px;}
		ul li{
			list-style: none;
		display: inline;
			}
	</style>

	<script type="text/javascript" src="../../lib/jquery/jquery-1.5.1.min.js"></script>
	<script type="text/javascript">
	 $.noConflict();
	jQuery( function() {
		jQuery(".jsFontsize").click(function() {
			var size = jQuery(this).attr('value');
			jQuery("table td").css("font-size", size);
			jQuery("table th").css("font-size", size);
		});
		jQuery(".jsColumn").click(function() {
			var columnName = "."+jQuery(this).attr('value');
			var displayStatus = jQuery(columnName).css('display');
			var newDisplayStatus = 'none';
			if(displayStatus == null || displayStatus == 'table-cell') {
				newDisplayStatus = 'none';
			} else {
				newDisplayStatus = 'table-cell';
			}
			jQuery(columnName).css('display', newDisplayStatus);
		});
		jQuery('#triggerDruckMenu').click(function() {
			var displayStatus = jQuery('#druckmenu').css('display');
			if(displayStatus == null || displayStatus == 'block') {
				newDisplayStatus = 'none';
			} else {
				newDisplayStatus = 'block';
			}
			jQuery('#druckmenu').css('display', newDisplayStatus);
		});

		jQuery('.jsKonfession').css('display', 'none');
		jQuery('.jsKM').css('display', 'none');
		
	});
	
	</script>
</head>
<body>

<div style="float: right;">
	<a href="#" id="triggerDruckMenu">Druckmenu Anzeigen / Ausblenden</a>
</div>
<h3>Offene Wartungen - Druckansicht vom <!--Datum--></h3>

<div id="druckmenu" style="border: 1px solid black; background-color: c9c9c9; clear: both; padding: 5px; margin: 5px;">
<ul style="list-style-type: none;">
	<li>Schriftgr&ouml;&szlig;e</li>
	<li><input id="jsFontSmall" class="jsFontsize" type="radio" name="fontsize" value="10" /><label for="jsFontSmall">Klein</label></li>
	<li><input id="jsFontNormal" class="jsFontsize" type="radio" name="fontsize" value="14" /><label for="jsFontNormal">Normal</label></li>
	<li><input id="jsFontLarge" class="jsFontsize" type="radio" name="fontsize" value="16" /><label for="jsFontLarge">Gro&szlig;</label></li>
</ul>
<ul style="list-style-type: none;">
	<li>Spalten</li>
	<li><input type="checkbox" class="jsColumn" id="jsGemeinde" value="jsGemeinde" checked="checked"/><label for="jsGemeinde">Gemeindenamen</label></li>
	<li><input type="checkbox" class="jsColumn" id="jsErbauer" value="jsErbauer" checked="checked"/><label for="jsErbauer">Erbauer</label></li>
	<li><input type="checkbox" class="jsColumn" id="jsBaujahr" value="jsBaujahr" checked="checked"/><label for="jsBaujahr">Baujahr</label></li>
	<li><input type="checkbox" class="jsColumn" id="jsLetztePflege" value="jsLetztePflege" checked="checked"/><label for="jsLetztePflege">Letzte Pflege</label></li>
	<li><input type="checkbox" class="jsColumn" id="jsNaechstePflege" value="jsNaechstePflege" checked="checked"/><label for="jsNaechstePflege">Naechste Pflege</label></li>
	<li><input type="checkbox" class="jsColumn" id="jsPflegevertrag" value="jsPflegevertrag" checked="checked"/><label for="jsPflegevertrag">Pflegevetrag</label></li>
	<li><input type="checkbox" class="jsColumn" id="jsZyklus" value="jsZyklus" checked="checked"/><label for="jsZyklus">Zyklus</label></li>
	<li><input type="checkbox" class="jsColumn" id="jsManuale" value="jsManuale" checked="checked"/><label for="jsManuale">Manuale</label></li>
	<li><input type="checkbox" class="jsColumn" id="jsRegister" value="jsRegister" checked="checked"/><label for="jsRegister">Register</label></li>
	<li><input type="checkbox" class="jsColumn" id="jsPLZ" value="jsPLZ" checked="checked" /><label for="jsPLZ">PLZ</label></li>
	<li><input type="checkbox" class="jsColumn" id="jsOrt" value="jsOrt" checked="checked" /><label for="jsOrt">Ort</label></li>
	<li><input type="checkbox" class="jsColumn" id="jsBezirk" value="jsBezirk" checked="checked" /><label for="jsBezirk">Bezirk</label></li>
	<li><input type="checkbox" class="jsColumn" id="jsAFunktion" value="jsAFunktion" checked="checked"/><label for="jsAFunktion">Funktion</label></li>
	<li><input type="checkbox" class="jsColumn" id="jsNachname" value="jsNachname" checked="checked"/><label for="jsNachname">Name</label></li>
	<li><input type="checkbox" class="jsColumn" id="jsATelefon" value="jsATelefon" checked="checked" /><label for="jsATelefon">Telefon</label></li>
</ul>
</div>

<!--OrgelAnzahl--> Offene Wartungen in der Datenbank gefunden.
<br /><br />
<table>
	<tr>
		<th>Nr.</th>
		<th class="jsGemeinde"><a href="orgeln.php?action=druckwartungen&order=gemeinde&dir=<!--Dir-->&zyklus=<!--Zyklus-->">Gemeinde</a></th>
		<th class="jsErbauer"><a href="orgeln.php?action=druckwartungen&order=erbauer&dir=<!--Dir-->&zyklus=<!--Zyklus-->">Erbauer</a></th>
		<th class="jsBaujahr" ><a href="orgeln.php?action=druckwartungen&order=baujahr&dir=<!--Dir-->&zyklus=<!--Zyklus-->">Baujahr</a></th>
		<th class="jsLetztePflege"><a href="orgeln.php?action=druckwartungen&order=wartung&dir=<!--Dir-->&zyklus=<!--Zyklus-->">Letzte Pflege</a></th>
		<th class="jsNaechstePflege"><a href="orgeln.php?action=druckwartungen&order=naechstepflege&dir=<!--Dir-->&zyklus=<!--Zyklus-->">Naechste Pflege</a></th>
		<th class="jsPflegevertrag"><a href="orgeln.php?action=druckwartungen&order=pflegevertrag&dir=<!--Dir-->&zyklus=<!--Zyklus-->">Pflegevertrag</a></th>
		<th class="jsZyklus"><a href="orgeln.php?action=druckwartungen&order=zyklus&dir=<!--Dir-->&zyklus=<!--Zyklus-->">Zyklus</a></th>
		<th class="jsManuale">Manuale</th>
		<th class="jsRegister"><a href="orgeln.php?action=druckwartungen&order=register&dir=<!--Dir-->&zyklus=<!--Zyklus-->">Register</a></th>
		<th class="jsPLZ"><a href="orgeln.php?action=druckwartungen&order=plz&dir=<!--Dir-->&zyklus=<!--Zyklus-->">PLZ</a></th>
		<th class="jsOrt"><a href="orgeln.php?action=druckwartungen&order=ort&dir=<!--Dir-->&zyklus=<!--Zyklus-->">Ort</a></th>
		<th class="jsBezirk"><a href="orgeln.php?action=druckwartungen&order=bezirk&dir=<!--Dir-->&zyklus=<!--Zyklus-->">Bezirk</a></th>
		<th class="jsAFunktion">Funktion</th>
		<th class="jsNachname">Name</th>
		<th class="jsATelefon">Telefon</th>
	</tr>
	<!--Content-->
</table>