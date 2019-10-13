
<table width="100%">
	<tr>
		<td>
			<h3>Aufgaben verwalten</h3>
			<a href="index.php?page=6&do=102" title="Neue Aufgabe anlegen">Neue Aufgabe anlegen</a>
			<br/>
			<br/>
			<form method="post" action="index.php?page=6&do=102">
			<input type="hidden" name="paid" value="<!--PaID-->" />
			<table class="liste">
				<tr>
					<th colspan="2">Aufgabendetails</th>
				</tr>
				<tr>
					<th>Bezeichnung:</th>
					<td>
						<input type="text" name="bezeichnung" size="35" value="<!--Bezeichnung-->" />
					</td>
				</tr>
				<tr>
					<th>Beschreibung:</th>
					<td>
						<textarea name="beschreibung" rows="5" cols="35"><!--Beschreibung--></textarea>
					</td>
				</tr>
				<tr>
					<th>&nbsp;</th>
					<td style="text-align: right">
						<input type="submit" name="submit" value="<!--Submit-->" />
					</td>
			</table>
			<br/>
			<!--Statusmeldung-->	
			</form>
		</td>
		<td>
			<h3>Aufgabenliste</h3>
			<br/><br/>
			<table class="liste">
				<tr>
					<th>Bezeichnung</th>
					<th>Beschreibung</th>
					<th colspan="2">Aktionen</th>
				</tr>
				<!--Aufgabenliste-->
			</table>
		</td>
	</tr>
</table>
