<form action="index.php?page=1&do=3" method="post">
	<input type="hidden" name="gid" value="<!--GemeindeID-->">
<table>
	<tr>
		<td style="padding-right: 30px;">
			<h3>Anschrift:</h3>
		<table class="liste txt370">
				<tr>
					<th>Konfession:</th>
					<td>
						<select id="konfession" name="konfession" class="txt270">
							<!--Konfessionen-->
						</select>
					</td>
				</tr>
				<tr>
					<th>Kirche:</th>
					<td><input type="text" name="kirche" id="kirche" maxlength="50" value="<!--Kirche-->" class="txt270"></td>
				</tr>
				<tr>
					<th>Straße:</th>
					<td>
						<input type="text" name="strasse" id="strasse" maxlength="50" value="<!--Strasse-->" class="txt215">
						<input type="text" name="hausnummer" id="hsnr" maxlength="50" value="<!--Hausnummer-->" class="int50">
					</td>
				</tr>
				<tr>
					<th>PLZ/Ort:</th>
					<td>
						<input type="text" name="plz" id="plz" maxlength="7" value="<!--PLZ-->" class="txt50 jsNumber">
						<input type="text" name="ort" id="ort" maxlength="50" value="<!--Ort-->" class="txt215">
					</td>
				</tr>
				<tr>
					<th>Land:</th>
					<td>
						<select name="land" class="txt270" id="land">
							<!-- Land -->
						</select>
					</td>
				</tr>
			</table>
			<h3>Rechnungsanschrift:</h3>
			<table class="liste txt370">
				<tr>
					<th>KundenNr:</th>
					<td><input type="text" name="rkundennr" id="kundennr2" maxlength="50" value="<!--RKundenNr-->" class="txt270"></td>
				</tr>
				<tr>
					<th>Kirchenamt:</th>
					<td><input type="text" name="ranschrift" id="kirchenamt2" maxlength="50" value="<!--RKirchenamt-->" class="txt270"></td>
				</tr>
				<tr>
					<th>Gemeinde:</th>
					<td><input type="text" name="rgemeinde" id="gemeinde2" maxlength="50" value="<!--RGemeinde-->" class="txt270"></td>
				</tr>
				<tr>
					<th>Straße:</th>
					<td>
						<input type="text" name="rstrasse" id="strasse2" maxlength="50" value="<!--RStrasse-->" class="txt215">
						<input type="text" name="rhausnummer" id="hsnr2" class="int50" maxlength="10" value="<!--RHausnummer-->">
					</td>
				</tr>
				<tr>
					<th>PLZ/Ort:</th>
					<td>
						<input type="text" name="rplz" id="plz2" maxlength="7" value="<!--RPLZ-->" class="txt50 jsNumber">
						<input type="text" name="rort" id="ort2" maxlength="50" value="<!--ROrt-->" class="txt215">
					</td>
				</tr>
				<tr>
					<th>Land:</th>
					<td>
						<select name="rland" class="txt270" id="rland">
							<!-- RLand -->
						</select>
					</td>
				</tr>
			</table>
			<span style="cursor: pointer; text-decoration: underline" onclick="copy()">Anschrift ist Rechnungsanschrift</span>
			<h3>Ansprechpartner:</h3>
			<table class="liste txt370">
				<tr>
					<th>HAP</th>
					<th>Funktion</th>
					<th>Name</th>
					<th>Telefon</th>
					<th>&nbsp;</th>
				</tr>
				<!--Ansprechpartner-->
			</table>
			<a title="Zur Ansprechpartnerverwaltung" href="index.php?page=3&do=40&gid=<!--GemeindeID-->">Neuen Ansprechpartner anlegen</a>
		</td>
		<td>
			<h3>Bezirk:</h3>
			<table>
				<tr>
					<td>
						<table class="liste">
							<tr>
								<th>Bezirk:</th>
								<td><input type="text" name="bezirk" class="int60" maxlength="4" value="<!--Bezirk-->"></td>
							</tr>
							<tr>
								<th>Entfernung:</th>
								<td><input id="routedistance" type="text" name="distanz" class="int60" maxlength="7" value="<!--KM-->"> km</td>
							</tr>
							<tr>
								<th>Fahrzeit:</th>
								<td><input id="routeduration" type="text" name="fahrzeit" class="int60" maxlength="7" value="<!--Fahrzeit-->"> Stunden</td>
							</tr>
							<tr>
								<th>&nbsp;</th>
								<td><div class="buttonlink iconButton refreshButton txt70" onClick="javascript:setDirections(<!--GemeindeID-->)" >Berechnen</div></td>
							</tr>
						</table>
						<br/>
						<div id="routeerror"></div><div id="routestatus"></div>
					</td>
					<td>
						<div id="map_detail" style="border: 1px solid black; width: 315px; height: 104px"></div>
						<div id="maptext"></div>
						<script type="text/javascript">
							$(function() {
								initialize('<!--Lat-->', '<!--Lng-->');
								showAddress('<!--Adresse-->', '<!--Lat-->', '<!--Lng-->');
							});
						</script>
					</td>
				</tr>
			</table>
 
			<h3>Rechnungen</h3>
			<div id="gemeindetailsrechnungen" class="txt530">
				<table class="liste" style="border: 0px; width: 100%">
					<tr>
						<th>Datum</th>
						<th>Typ</th>
						<th>RechnungsNr.</th>
						<th>Betrag</th>
						<th>&nbsp;</th>
					</tr>
					<!--Rechnungen-->
				</table>
			</div>
			<a title="Eine neue Rechnung f&uuml;r die Gemeinde <!--GemeindeNamen--> erstellen" href="index.php?page=5&do=80&gid=<!--GemeindeID-->">Neue Rechnung erstellen</a>
			<br />
			<h3>Orgeln:</h3>
			<table class="liste txt530">
				<tr>
					<th>Manuale</th>
					<th>Register</th>
					<th>Letzte Pflege</th>
					<th>Erbauer</th>
					<th>Baujahr</th>
					<th>&nbsp;</th>
				</tr>
				<!--Orgeln-->
			</table>
			<a href="index.php?page=2&do=24&gid=<!--GemeindeID-->" title="Eine neue Orgel f&uuml;r die Gemeinde <!--GemeindeNamen--> erstellen">Neue Orgel hinzufügen</a><br />
			<br />
			Um eine bestehende Orgel einer Gemeinde zuzuordnen, in den Orgeldetails auf  "Gemeinde hinzufügen" klicken.<br />
			
			<!--Status-->
		</td>
	</tr>
</table>
<hr class="bottomMenuHR"/>
<div class="bottomMenu">
	<input class="button iconButton saveButton" type="submit" name="submit" value="Speichern">
	<a class="buttonlink iconButton cancelButton" href="index.php?page=1&do=2&gid=<!--GemeindeID-->" title="Abbrechen">Abbrechen</a>
</div>
<a class="buttonlink iconButton deleteButton" href="index.php?page=1&do=5&gid=<!--GemeindeID-->" title="Diese
Gemeinde l&ouml;schen">Gemeinde l&ouml;schen</a>
</form>