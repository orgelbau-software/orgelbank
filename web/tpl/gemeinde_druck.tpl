<html>
<head>
	<title>Gemeinde Druckansicht</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<style type="text/css">
	body { font-family: Arial, Verdana;}
	a { color: #000000; text-decoration: none;}
	table{border:1px solid black; border-collapse: separate;}
	th{text-align: left;}
	td{border-bottom: 1px solid black; padding: 0px 10px 3px 0;font-size: 14px;}
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
<h3>Gemeinden - Druckansicht</h3>
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
	<li><input type="checkbox" class="jsColumn" id="jsPLZ" value="jsPLZ" checked="checked" /><label for="jsPLZ">PLZ</label></li>
	<li><input type="checkbox" class="jsColumn" id="jsOrt" value="jsOrt" checked="checked" /><label for="jsOrt">Ort</label></li>
	<li><input type="checkbox" class="jsColumn" id="jsKM" value="jsKM" /><label for="jsKM">KM</label></li>
	<li><input type="checkbox" class="jsColumn" id="jsKonfession" value="jsKonfession" /><label for="jsKonfession">Konfession</label></li>
	<li><input type="checkbox" class="jsColumn" id="jsKonfessionKurz" value="jsKonfessionKurz" checked="checked"/><label for="jsKonfessionKurz">Konfession (Kurzform)</label></li>
	<li><input type="checkbox" class="jsColumn" id="jsBezirk" value="jsBezirk" checked="checked" /><label for="jsBezirk">Bezirk</label></li>
	<li><input type="checkbox" class="jsColumn" id="jsAFunktion" value="jsAFunktion" checked="checked"/><label for="jsAFunktion">Funktion</label></li>
	<li><input type="checkbox" class="jsColumn" id="jsNachname" value="jsNachname" checked="checked"/><label for="jsNachname">Name</label></li>
	<li><input type="checkbox" class="jsColumn" id="jsATelefon" value="jsATelefon" checked="checked" /><label for="jsATelefon">Telefon</label></li>
</ul>
</div>
<!--GemeindeAnzahl--> Gemeinden in der Datenbank.<br /><br />
<table>
	<tr>
		<th>Nr.</th>
		<th class="jsGemeinde">
			<a href="gemeinden.php?action=druck&order=gemeinde&dir=<!--Dir-->&sid=<!--SessionID-->">Gemeindenamen</a>
		</th>
		<th class="jsPLZ">
			<a href="gemeinden.php?action=druck&order=plz&dir=<!--Dir-->&sid=<!--SessionID-->">PLZ</a>
		</th>
		<th class="jsOrt">
			<a href="gemeinden.php?action=druck&order=ort&dir=<!--Dir-->&sid=<!--SessionID-->">Ort</a>
		</th>
		<th class="jsKM">
			<a href="#">KM</a>
		</th>
		<th class="jsKonfession">
			<a href="gemeinden.php?action=druck&order=konfession&dir=<!--Dir-->&sid=<!--SessionID-->">Konfession</a>
		</th>
		<th class="jsKonfessionKurz">
			<a href="gemeinden.php?action=druck&order=konfession&dir=<!--Dir-->&sid=<!--SessionID-->">Konf.</a>
		</th>
		<th class="jsBezirk">
			<a href="gemeinden.php?action=druck&order=bezirk&dir=<!--Dir-->&sid=<!--SessionID-->">Bezirk</a>
		</th>
		
		<th class="jsAFunktion" >Funktion</th>
		<th class="jsNachname">Name</th>
		<th  class="jsATelefon">Telefon</th>
	</tr>
	<!--Gemeinden-->
</table>
</body>
</html>