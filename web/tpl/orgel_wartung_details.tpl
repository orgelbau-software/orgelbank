<h3>Wartung&uuml;bersicht f&uuml;r die Orgel in <!-- Kirche --></h3>
<form method="post" action="index.php?page=2&do=28&oid=<!--OrgelId-->">
<input type="hidden" name="wartungId" value="<!--WartungId-->" />
<input type="hidden" name="orgelId" value="<!--OrgelId-->" />
<div id="orgeldetailswartungengesamt">
	<table class="liste" style="width: 100%; border: 0px solid black;">
		<tr>
			<th>Datum</th>
			<th>Mitarbeiter</th>
			<th>Bemerkung</th>
			<th class="alignRight">Temperatur</th>
			<th class="alignRight">Luftfeuchte</th>
			<th class="alignRight">Stimmtonh&ouml;he</th>
			<th>Art</th>
			<th colspan="2">Aktionen</th>
		</tr>
		<!--Wartungen-->
	</table>
</div>
<hr/>
<h3>Wartungsdaten</h3>
<table class="liste" style="width: 100%">
	<tr>
		<th>Datum:</th>
		<td><div class="inputDatum"><input class="datePicker" maxlength="10" type="text" name="datum" value="<!--Datum-->" tabindex="1"/></div></td>
		<th>Temperatur:</th>
		<td><input type="text" maxlength="5" name="temperatur" value="<!--Temperatur-->" class="int50" tabindex="4" /> °C</td>
		<th>Bemerkung:</th>
	</tr>
	<tr>
		<th>Abrechnungsart</th>
		<td>
			<select name="abrechnung">
				<option value="0" >---</option>
				<option value="1" <!--AbrVertrag-->>nach Pflegevertrag</option>
				<option value="2" <!--AbrAufwand-->>nach Aufwand</option>
				<option value="3" <!--AbrGarantie-->>Garantie</option>
			</select>
		</td>
		<th>Luftfeuchte:</th>
		<td><input class="int50" type="text" maxlength="5" name="luftfeuchtigkeit" value="<!--Luftfeuchtigkeit-->" tabindex="5" /> %</td>
		<th rowspan="2"><textarea name="bemerkung" rows="2" class="txt400" tabindex="7"><!--Bemerkung--></textarea></th>
	</tr>
	<tr>
		<th>Art:</th>
		<td>
			<select name="stimmung" tabindex="3" class="txt150">
				<option value="0" <!--NichtDurchgefuehrt-->>nur Wartung</option>
				<option value="5" <!--Reparatur-->>Reparatur</option>
				<option value="1" <!--Nebenstimmung-->>Nebenstimmung</option>
				<option value="2" <!--Hauptstimmung-->>Hauptstimmung</option>
				<option value="3" <!--Zungenstimmung-->>Zungenstimmung</option>
			</select>
		</td>
		<th>Stimmtonh&ouml;he</th>
		<td><input class="int50" type="text" maxlength="6" name="stimmtonhoehe" value="<!--Stimmtonhoehe-->" tabindex="6"/> HZ</td>
	</tr>
</table>
<hr/>
<table>
	<tr>
		<td style="padding-right: 20px;">
			<h3>Personal- &amp; Materialeinsatz</h3>
			<table class="liste">
				<tr>
					<th></th>
					<th>Mitarbeiter</th>
					<th>Ist-Stunden</th>
					<th>Fakt.-Stunden</th>
				</tr>
				<tr>
					<th>Mitarbeiter I</th>
					<td>
						<select name="mitarbeiter_1" tabindex="2" class="txt150">
							<option value="0">---</option>
							<!--MitarbeiterListe1-->
						</select>
					</td>
					<td><input type="text" name="ma1_stunden_ist" class="txt40 alignRight" value="<!-- Ma1IstStd -->"/> Std.</td>
					<td><input type="text" name="ma1_stunden_fakt"  class="txt40 alignRight" value="<!-- Ma1FaktStd -->"/> Std.</td>
				</tr>
				<tr>
					<th>Mitarbeiter II</th>
					<td>
						<select name="mitarbeiter_2" tabindex="2" class="txt150">
							<option value="0">---</option>
							<!--MitarbeiterListe2-->
						</select>
					</td>
					<td><input type="text" name="ma2_stunden_ist" class="txt40 alignRight" value="<!-- Ma2IstStd -->"/> Std.</td>
					<td><input type="text" name="ma2_stunden_fakt" class="txt40 alignRight" value="<!-- Ma2FaktStd -->" /> Std.</td>
				</tr>
				<tr>
					<th>Mitarbeiter III</th>
					<td>
						<select name="mitarbeiter_3" tabindex="2" class="txt150">
							<option value="0">---</option>
							<!--MitarbeiterListe3-->
						</select>
					</td>
					<td><input type="text" name="ma3_stunden_ist" class="txt40 alignRight" value="<!-- Ma3IstStd -->"/> Std.</td>
					<td><input type="text" name="ma3_stunden_fakt" class="txt40 alignRight" value="<!-- Ma3FaktStd -->"/> Std.</td>
				</tr>
				<tr>
					<th>Tastenhalter:</th>
					<td colspan="3"><input type="checkbox" name="tastenhalter" id="tastenhalter" <!--Tastenhalter-->/><label for="tastenhalter">von Gemeinde gestellt</label></td>
				</tr>
				<tr>
					<th>Material:</th>
					<td colspan="3"><textarea name="material" rows="2" class="txt300" tabindex="7"><!--Material--></textarea></td>
				</tr>
			</table>
		</td>
		<td>
			<h3>Notwendige Ma&szlig;nahmen</h3>
			<textarea name="massnahmen" class="txtarea435"><!--NotwendigeMassnahmen--></textarea>
			
			<h3>Allgemeine Anmerkungen</h3>
			<textarea name="anmerkungen" class="txtarea435"><!--Anmerkungen--></textarea>
		</td>
	</tr>
</table>
<br/>
<hr/>
<h3>Gemeinde Ansprechpartner</h3>
<table class="liste" style="width: 420px">
	<tr>
		<th>Name</th>
		<th>Funktion</th>
		<th>Aktionen</th>
	</tr>
	<!--Ansprechpartner-->
</table>
			
<span class="<!--CSSSpanHide-->">
	<a href="index.php?page=3&do=40&gid=<!--GemeindeId-->&oid=<!--OrgelId-->">Neuen Ansprechpartner Anlegen</a>
</span>
<!--HTMLStatus-->
<hr/>
<br/>
<div style="float: right;">
  <input type="submit" name="submit" tabindex="8" value="<!--SubmitValue-->" class="button iconButton saveButton" />
  <a href="index.php?page=2&do=28&oid=<!--OrgelId-->&action=edit" title="Abbrechen" class="buttonLink iconButton cancelButton">Abbrechen</a>
</div>
<a href="index.php?page=2&do=21&oid=<!--OrgelId-->" title="zur&uuml;ck zur Orgel" tabindex="9" class="buttonLink iconButton backButton">zur&uuml;ck zur Orgel</a>
<a href="index.php?page=2&do=28&oid=<!--OrgelId-->" class="buttonLink iconButton createButton dispositionAdd" title="Neue Wartung erstellen">Neue Wartung</a>

<span class="buttonLink button iconButton forwardButton">
	<input type="submit" name="submit" tabindex="99" value="Gehe Zu:" class="button iconButton" style="border: 0px;"/>
	<input type="text" name="goto" tabindex="100" value="" id="jsGoToValue" style="width: 30px; border: 0px; border:1px dashed black;" maxlength="3"/>
</span>
</form>