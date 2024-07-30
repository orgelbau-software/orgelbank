<h3>Benutzerdaten &auml;ndern</h3>

<form method="post" action="index.php?page=8&do=140">
<table class="liste">
	<tr>
		<th>Benutzername:</th>
		<td>
			<!--Benutzername-->
		</td>
	</tr>
	<tr>
		<th>Vorname:</th>
		<td>
			<input type="text" name="vorname" size="35" value="<!--Vorname-->" />
		</td>
	</tr>
	<tr>
		<th>Nachname:</th>
		<td>
			<input type="text" name="nachname" size="35" value="<!--Nachname-->" />
		</td>
	</tr>
	<tr>
		<th>Email:</th>
		<td>
			<input type="email" name="email" size="35" value="<!--Email-->" />
		</td>
	</tr>
	<tr>
		<th>&nbsp;</th>
		<td style="text-align: right"><input type="submit" name="submit" value="Speichern"/></td>
	</tr>
</table>

<h3>Passwort &auml;ndern</h3>
<table class="liste">
	<tr>
		<th>Passwort:</th>
		<td>
			<input type="password" name="passwort" size="20" value="" autocomplete="new-password" /> (Mindestens <!--MinPWLength--> Zeichen)
		</td>
	</tr>
	<tr>
		<th>Best&auml;tigung:</th>
		<td>
			<input type="password" name="bestaetigung" size="20" value="" autocomplete="new-password" />
		</td>
	</tr>
	<tr>
		<th>&nbsp;</th>
		<td style="text-align: right"><input type="submit" name="submit" value="Speichern"/></td>
	</tr>
</table>
</form>
<!--Statusmeldung-->