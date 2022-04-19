<h3>Urlaubsverwaltung</h3>
<!--Statusmeldung-->

<h3>Urlaubsliste</h3>
<form method="post" action="index.php?page=6&do=115">
<select name="benutzerId" onchange="this.form.submit()">
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
		<th>Tage</th>
		<th>Verbleibend</th>
		<th>Resturlaub</th>
		<th>Total</th>
		<th>Status</th>
		<th colspan="3">Aktionen</th>
	</tr>
	<!--UrlaubsListe-->
</table>

<hr class="bottomMenuHR"/>

<a class="buttonlink iconButton createButton" href="index.php?page=6&do=116" title="Neuen Jahresurlaub anlegen">Neuen Jahrsurlaub Anlegen</a>