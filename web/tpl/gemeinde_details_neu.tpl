<form action="index.php?page=1&do=3" method="post">
	<input type="hidden" name="gid" value="<!--GemeindeID-->">
<table class="zweispalten">
	<tr>
		<td class="zweispalten-links">
			
			<h3>Anschrift:</h3>
			<table class="liste">
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
					<td><input type="text" name="kirche" id="kirche" size="36" maxlength="50" value="<!--Kirche-->" class="txt270"></td>
				</tr>
				<tr>
					<th>Straße:</th>
					<td>
						<input type="text" name="strasse" id="strasse" size="28" maxlength="50" value="<!--Strasse-->" class="txt215">
						<input type="text" name="hausnummer" id="hsnr" class="int50" size="3" maxlength="50" value="<!--Hausnummer-->"  >
					</td>
				</tr>
				<tr>
					<th>PLZ/Ort:</th>
					<td>
						<input type="text" name="plz" id="plz" size="7" maxlength="50" value="<!--PLZ-->" class="txt50 jsNumber">
						<input type="text" name="ort" id="ort" size="24" maxlength="50" value="<!--Ort-->" class="txt215">
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
			<table class="liste">
				<tr>
					<th>KundenNr:</th>
					<td><input type="text" name="rkundennr" id="kundennr2" maxlength="50" value="<!--RKundenNr-->" class="txt270"></td>
				</tr>
				<tr>
					<th>Kirchenamt:</th>
					<td><input type="text" name="ranschrift" id="kirchenamt2"size="36" maxlength="50" value="<!--RKirchenamt-->" class="txt270"></td>
				</tr>
				<tr>
					<th>Gemeinde:</th>
					<td><input type="text" name="rgemeinde" id="gemeinde2" size="36" maxlength="50" value="<!--RGemeinde-->" class="txt270"></td>
				</tr>
				<tr>
					<th>Straße:</th>
					<td>
						<input type="text" name="rstrasse" id="strasse2" size="28" maxlength="50" value="<!--RStrasse-->" class="txt215">
						<input type="text" name="rhausnummer" id="hsnr2" class="int50" size="3" maxlength="50" value="<!--RHausnummer-->">
					</td>
				</tr>
				<tr>
					<th>PLZ/Ort:</th>
					<td>
						<input type="text" name="rplz" id="plz2" size="7" maxlength="50" value="<!--RPLZ-->" class="txt50 jsNumber">
						<input type="text" name="rort" id="ort2" size="24" maxlength="50" value="<!--ROrt-->" class="txt215">
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
		</td>
		<td class="zweispalten-rechts">
			<h3>Bezirk:</h3>
			<table class="liste">
				<tr>
					<th>Bezirk:</th>
					<td><input type="text" name="bezirk" class="int60" size="2" maxlength="50" value="<!--Bezirk-->"></td>
				</tr>
				<tr>
					<th>Kilometer:</th>
					<td><input type="text" name="distanz" class="int60" size="2" maxlength="50" value="<!--KM-->"> km</td>
				</tr>
				<tr>
					<th>Fahrzeit:</th>
					<td><input type="text" name="fahrzeit" class="int60" size="4" maxlength="50" value="<!--Fahrzeit-->"> Stunden</td>
				</tr>
			</table>
			<br/>
			<h4>Hinweis:</h4>
			Erst nach dem Speichern einer neuen Gemeinde werden die anderen Optionen wie Ansprechpartner-, Orgel-, und Rechnungsverwaltung freigeschalten.
		</td>
	</tr>
</table>
<hr class="bottomMenuHR"/>
<div class="bottomMenu">
	<input class="button iconButton saveButton" type="submit" name="submit" value="Speichern">
	<a class="buttonlink iconButton cancelButton" href="index.php?page=1&do=1" title="Abbrechen">Abbrechen</a>
</div>
</form>