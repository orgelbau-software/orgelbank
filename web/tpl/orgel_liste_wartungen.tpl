	<div class="export">
		<ul>
			<li><a title="Gemeindelist im reinen Druckformat" target="_blank" href="#"><img src="web/images/icon_print.png" /></a></li>
			<li><a title="Aktuelle Liste nach Excel exportieren" target="_blank" href="#"><img src="web/images/icon_excel.png" /></a></li>
		</ul>
	</div>
	
	<form name="orgeloption" action="index.php?page=2&do=29" method="post">
		Zeige <b><!-- AnzahlWartungen --> offene Wartungen</b>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<select id="zyklus" name="zyklus">
			<option value="0" <!-- Zyklus0 -->>Alle</option>
			<option value="1" <!-- Zyklus1 -->>1 Jahr</option>
			<option value="2" <!-- Zyklus2 -->>2 Jahre</option>
			<option value="3" <!-- Zyklus3 -->>3 Jahre</option>
			<!--ZyklusListe-->
		</select> 
		<label for="zyklus">Zyklus</label>
		<input type="checkbox" class="checkbox" name="hideunknown" value="1" id="hideunknown" <!--hideunknown-->/><label for="hideunknown">Unbekannte Pflegen ausblenden</label>
		<input type="submit" class="button iconButton searchButton" name="submit" value="Anzeigen" />
		<input type="submit" class="button iconButton resetButton" name="submit" value="Zur&uuml;cksetzen" />
	</form>
	<hr/>
	
	<form name="orgelliste" action="src/orgel/pdf.php?sid=<!--SessionID-->" method="post">
	<input type="hidden" name="orgelliste" value="orgelliste">
	<table class="liste">
		<!--Content-->
	</table>
	<a name="drucken"></a>
	<input class="button" type="submit" name="submit" value="Pflegeb&ouml;gen anzeigen">
	<input class="button" type="reset" name="reset" value="Marker löschen">
	</form>
