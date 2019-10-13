<h3>Rechnungsliste</h3>
<br/>
<form method="post" action="index.php?page=5&do=89">
	<table class="liste">
		<tr>
			<td>Zeige</td>
			<td>
				<select name="typ" <!--disabled-->>
					<option value="0" <!--selected0-->>Alle Rechnungen</option>
					<option value="1" <!--selected1-->>Pflegerechnung</option>
					<option value="2" <!--selected2-->>Stundenrechnung</option>
					<option value="3" <!--selected3-->>Abschlagsrechnung</option>
					<option value="4" <!--selected4-->>Endrechnung</option>
				</select>
			</td>
			<td>von</td>
			<td>
				<select name="von" <!--disabled-->>
					<!--Von-->
				</select>	
			</td>
			<td>bis</td>
			<td>
				<select name="bis" <!--disabled-->>
					<!--Bis-->
				</select>	
			</td>
			<td>
				<select name="maxbetrag" <!--disabled-->>
					<!--MaxBetrag-->
				</select>	
			</td>
			<td><input type="submit" name="submit" value="Anzeigen" class="button iconButton searchButton" <!--disabled-->/></td>
			<td><input type="submit" name="submit" value="Zur&uuml;cksetzen" class="button iconButton resetButton" <!--disabled--> /></td>
		</tr>
	</table>
</form>
Es wurden <b><!--AnzahlRechnungen--> Rechnungen</b> im Zeitraum vom <b><!--ZeitraumVon--></b> bis <b><!--ZeitraumBis--></b> im Wert von <b><!--GesamtNetto--> EUR (Netto)</b> und <b><!--GesamtBrutto--> EUR (Brutto)</b> gefunden.
<br/>
<br/>
<table class="liste size100">
	<tr>
		<th><a href="index.php?page=5&do=89&order=rechnungsnr&dir=<!-- Dir -->">RechnungsNr.</a></th>
		<th>Typ</th>
		<th><a href="index.php?page=5&do=89&order=datum&dir=<!-- Dir -->">Datum</a></th>
		<th><a href="index.php?page=5&do=89&order=gemeinde&dir=<!-- Dir -->">Gemeinde</a></th>
		<th><a href="index.php?page=5&do=89&order=nettobetrag&dir=<!-- Dir -->">Nettobetrag</a></th>
		<th><a href="index.php?page=5&do=89&order=bruttobetrag&dir=<!-- Dir -->">Bruttobetrag</a></th>
		<th colspan="2">Aktionen</th>
	</tr>
	<!--RechnungsListe-->
</table>

<div id="jsRechnungMenu" class="rechnungContextMenu" style="display: none;">
<table>
	<tr>
		<th>Eingangs-Datum</th>
		<td><div class="inputDatum"><input  id="jsEingangsDatum" class="datePicker jsCalendar"  type="text" name="eingangsdatum" value="" maxlength="10" /></div></td>
	</tr>
	<tr>
		<th>Rechnungs-Betrag:</th>
		<td><input type="text" id="jsRechnungsBetrag" name="rechnungsbetrag" value="" class="int70 disabled" disabled="disabled"/> EUR</td>
	</tr>
	<tr>
		<th>Eingangs-Betrag:</th>
		<td><input type="text" id="jsEingangsBetrag"  name="eingagsbetrag" value="" class="int70 jsCurrency" /> EUR</td>
	</tr>
	<tr>
		<th>Anmerkung:</th>
		<td><textarea id="jsAnmerkung" class="width170"></textarea></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<input type="submit" name="speichern" value="OK" class="button iconButton saveButton" onclick="return onClickSubmitRechnungseingang();" />
			<input type="submit" name="abbrechen" value="Abbrechen" class="button iconButton cancelRechnungButton" onclick="return onClickResetLastElement();"/>
		</td>
	</tr>
</table>
</div>