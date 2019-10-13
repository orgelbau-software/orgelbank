<form method="post" action="index.php?page=7&do=120">
<h3>Allgemeine Rechnungsdaten</h3>
<table class="liste size100">
<tr>
	<th class="txt150">Standard Zahlungsziel</th>
	<td>
		<select name="zahlungsziel">
			<!--Zahlungsziele-->
		</select>
	</td>
</tr>
</table>
<h3>Pflegerechnung Standardpositionen</h3>

<table class="liste size100">
	<tr>
		<th class="txt150">Position 1:</th>
		<td><input type="text" name="standardposition1" value="<!--Standardposition1-->" size="60"/></td>
	</tr>
	<tr>
		<th class="txt150">Position 2:</th>
		<td><input type="text" name="standardposition2" value="<!--Standardposition2-->" size="60"/></td>
	</tr>
	<tr>
		<th class="txt150">Position 3:</th>
		<td><input type="text" name="standardposition3" value="<!--Standardposition3-->" size="60"/></td>
	</tr>
	<tr>
		<th class="txt150">Position 4:</th>
		<td><input type="text" name="standardposition4" value="<!--Standardposition4-->" size="60"/></td>
	</tr>
	<tr>
		<th class="txt150">Position 5:</th>
		<td><input type="text" name="standardposition5" value="<!--Standardposition5-->" size="60"/></td>
	</tr>
	<tr>
		<th class="txt150">Position 6:</th>
		<td><input type="text" name="standardposition6" value="<!--Standardposition6-->" size="60"/></td>
	</tr>
	<tr>
		<th class="txt150">Position 7:</th>
		<td><input type="text" name="standardposition7" value="<!--Standardposition7-->" size="60"/></td>
	</tr>
	<tr>
		<th class="txt150">Position 8:</th>
		<td><input type="text" name="standardposition8" value="<!--Standardposition8-->" size="60"/></td>
	</tr>
	<tr>
		<th class="txt150">Position 9:</th>
		<td><input type="text" name="standardposition9" value="<!--Standardposition9-->" size="60"/></td>
	</tr>
	<tr>
		<th class="txt150">Position 10:</th>
		<td><input type="text" name="standardposition10" value="<!--Standardposition10-->" size="60"/></td>
	</tr>
</table>

<h3>Pflegerechnung Vorgaben:</h3>
<table class="liste size100">
	<tr>
		<th class="txt150">Pflegevertrag:</th>
		<td><textarea name="pflegetext" class="settingsTxtArea"><!--PflegeText--></textarea></td>
	</tr>
	<tr>
		<th class="txt150">Auftrag:</th>
		<td><textarea name="auftragtext" class="settingsTxtArea"><!--AuftragText--></textarea></td>
	</tr>
	<tr>
		<th class="txt150">Angebot:</th>
		<td><textarea name="angebottext" class="settingsTxtArea"><!--AngebotText--></textarea></td>
	</tr>
</table>

<h3>Abschlagsrechnung Vorgaben:</h3>
<table class="liste size100">
	<tr>
		<th class="txt150">Abschlagrechnung I:</th>
		<td><textarea name="abschlag1text" class="settingsTxtArea"><!--Abschlag1Text--></textarea></td>
	</tr>
	<tr>
		<th class="txt150">&nbsp;</th>
		<td style="text-align: right;"><input class="inputzahl" type="text" name="abschlag1prozent" value="<!--Abschlag1Prozent-->" size="3" /> Prozent</td>
	</tr>
	<tr>
		<th class="txt150">Abschlagrechnung II:</th>
		<td><textarea name="abschlag2text" class="settingsTxtArea"><!--Abschlag2Text--></textarea></td>
	</tr>
	<tr>
		<th class="txt150">&nbsp;</th>
		<td style="text-align: right;"><input class="inputzahl" type="text" name="abschlag2prozent" value="<!--Abschlag2Prozent-->" size="3" /> Prozent</td>
	</tr>
	<tr>
		<th>Abschlagrechnung III:</th>
		<td><textarea name="abschlag3text" class="settingsTxtArea"><!--Abschlag3Text--></textarea></td>
	</tr>
	<tr>
		<th>&nbsp;</th>
		<td style="text-align: right;"><input class="inputzahl" type="text" name="abschlag3prozent" value="<!--Abschlag3Prozent-->" size="3" /> Prozent</td>
	</tr>
</table>
<hr class="bottomMenuHR" />
<div class="bottomMenu">
  <input type="submit" name="submit" class="button iconButton saveButton" value="Speichern"/>
</div>
</form>