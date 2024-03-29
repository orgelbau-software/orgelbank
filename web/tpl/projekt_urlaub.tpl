<h3>Urlaubsverwaltung</h3>
<!--Statusmeldung-->

<h3>Urlaubsbuchung & Korrektur</h3>
<form method="post" action="index.php?page=6&do=115">
<table class="liste">
	<tr>
		<th>Datum Von:</th>
		<td><input type="date" name="datumvon" value="<!--DatumVon-->" tabindex="1"/></td>
		<th>Mitarbeiter:</th>
		<td>
		<select name="benutzerId" tabindex="3">
  			<!--Mitarbeiter-->
		</select>
		</td>
		<th>Bemerkung:</th>
		<td><input type="text" maxlength="100" name="bemerkung" value="<!--Bemerkung-->" class="txt100" tabindex="5" /></td>
	</tr>
	<tr>
		<th>Datum Bis:</th>
		<td><input type="date" name="datumbis" value="<!--DatumBis-->" tabindex="2"/></td>
		<th>Stunden</th>
		<td>
			<input type="number" min="-240" max="240" step="0.25" name="tage" value="<!--Stunden-->" class="int50" tabindex="4" required />
			<select name="urlaubstyp">
				<option value="U">Urlaub</option>
				<option value="Z">Zusatz</option>
				<option value="K">Korrektur</option>
			</select>
		</td>
		<th></th>
		<td><input type="submit" name="submit" tabindex="6" value="<!--SubmitValue-->" class="button iconButton saveButton" /></td>
	</tr>
</table>
</form>

<h3>Urlaubsliste</h3>
<form method="post" action="index.php?page=6&do=115">

<select name="quickswitchJahr" onchange="this.form.submit()">
  <option value="0">Gesamt</option>
  <!--Jahresauswahl-->
</select>


<select name="quickswitchBenutzerId" onchange="this.form.submit()">
  <option value="0">Alle</option>
  <!--Mitarbeiter-->
</select>

</form>
<br/>

<table class="liste">
	<tr>
		<th>Datum Von</th>
		<th>Datum Bis</th>
		<th>Mitarbeiter</th>
		<th>Stunden (Tage)</th>
		<th>Verbleibend</th>
		<th>Resturlaub</th>
		<th>Total</th>
		<th>Status</th>
		<th>Bemerkung</th>
		<th colspan="3">Aktionen</th>
	</tr>
	<!--UrlaubsListe-->
</table>

<hr class="bottomMenuHR"/>

<a class="buttonlink iconButton createButton" href="index.php?page=6&do=116" title="Neuen Jahresurlaub anlegen">Neuen Jahresurlaub Anlegen</a>