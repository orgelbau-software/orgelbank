<h3>Firmendaten &auml;ndern</h3>
<form action="index.php?page=7&do=122" method="post">
<input type="hidden" name="bemerkung" value= "" />
<input type="hidden" name="aid" value="<!--AID-->" />
<table class="liste">
	<tr>
		<th>Firma:</th>
		<td><input type="text" name="funktion" size="20" maxlength="50" value="<!--Funktion-->"></td>
	</tr>
	<tr>
		<th>Anrede:</th>
		<td>
			<input type="text" name="anrede" class="txt170" list="anrededatalist" value="<!--Anrede-->"/>
			<datalist id="anrededatalist">
  				<!--AnredeDatalist-->
			</datalist>
		</td>
	</tr>
	<tr>
		<th>Titel:</th>
		<td>
			<input type="text" name="titel" class="txt170" list="titeldatalist" value="<!--Titel-->"/>
			<datalist id="titeldatalist">
  				<!--TitelDatalist-->
			</datalist>
		</td>
	</tr>
	<tr>
		<th>Inhaber Vorname:</th>
		<td><input type="text" name="vorname" size="20" maxlength="50" value="<!--Vorname-->"></td>
	</tr>
	<tr>
		<th>Inhaber Name:</th>
		<td><input type="text" name="name" size="20" maxlength="50" value="<!--Nachname-->">
		</td>
	</tr>
	<tr>
		<th>Straße:</th>
		<td>
			<input type="text" name="strasse" size="18" maxlength="50" value="<!--Strasse-->">
			<input type="text" name="hausnummer" size="3" maxlength="5" value="<!--Hsnr-->">
		</td>
	</tr>
	<tr>
		<th>PLZ/Ort:</th>
		<td>
			<input type="text" name="plz" size="4" maxlength="8" value="<!--PLZ-->">
			<input type="text" name="ort" size="17" maxlength="50" value="<!--Ort-->">
		</td>
	</tr>
	<tr>
		<th>Telefon:</th>
		<td><input type="text" name="telefon" size="20" maxlength="50" value="<!--Telefon-->"></td>
	</tr>
	<tr>
		<th>Fax:</th>
		<td><input type="text" name="fax" size="20" maxlength="50" value="<!--Fax-->"></td>
	</tr>
	<tr>
		<th>Mobil:</th>
		<td><input type="text" name="mobil" size="20" maxlength="50" value="<!--Mobil-->"></td>
	</tr>
	<tr>
		<th>EMail:</th>
		<td><input type="text" name="email" size="27" maxlength="50" value="<!--EMail-->"></td>
	</tr>
	<tr>
		<th>SteuerNr:</th>
		<td><input type="text" name="andere" size="27" maxlength="50" value="<!--Andere-->"></td>
	</tr>
  	<tr>
	  	<th>&nbsp;</th>
	  	<th>
	  		<input type="submit" class="button" name="submit" value="Speichern">
	  	</th>
  	</tr>
</table>

</form>