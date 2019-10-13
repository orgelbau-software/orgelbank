<h3>Neue Endrechnug <!--KopfzeilenZusatz--></h3>
	<form action="index.php?page=5&do=88" method="post">
<!--RechnungsKopf-->
<br/>
<table class="rechnung" id="rechnungen">
	<!--Rechnungen-->
</table>
<br/>
<table class="rechnung">
		<tr>
			<th colspan="6">Rechnungsdetails:</th>
		</tr>
			<tr>
				<td class="tdbez">RE-Datum:</td>
				<td><div class="inputDatum"><input <!--disableForm--> class="datePicker" type="text" name="datum" value="<!--Rechnungsdatum-->" size="9"/></div></td>
				<td class="tdbez">Zahlungsziel:</td>
				<td><div class="inputDatum"><input <!--disableForm--> class="datePicker" type="text" name="zahlungsziel" value="<!--Zahlungsziel-->" size="9"/></div></td>
				<td class="tdbez">RE-Nr:</td>
				<td><input <!--disableForm--> type="text" name="rechnungsnummer" value="<!--Rechnungsnummer-->/<!--Rechnungsjahr-->" size="8"/></td>
			</tr>
			<tr>
				<td class="tdbez">Titel:</td>
				<td colspan="5"><input <!--disableForm--> type="text" name="titel" value="" class="txt500"/></td>
			</tr>
			<tr>
				<td class="tdbez" colspan="6">Einleitung:</td>
			</tr>
			<tr>
				<td colspan="6">
					<textarea id="bemerkung1" name="text" class="txtarea580"><!--Einleitung--></textarea>
				</td>
			</tr>
		</table>
		<br/>
		<table class="rechnung">
			<tr>
				<th colspan="3">Rechnungsbetrag</th>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td class="tdbez">Gesamt:</td>
				<td class="tdbez">Rest:</td>
			</tr>
			<tr>
				<td class="tdbez" style="width: 120px">Gesamt (Netto):</td>
				<td><input type="text" name="gnetto" id="gnetto" value="<!--GesamtNetto-->" size="8" onChange="berechneAbschlag2()" class="inputzahl" <!--disableForm--> /> EUR</td>
				<td><input type="text" name="rnetto" id="rnetto" value="<!--NettoBetrag-->" size="8" onChange="berechneAbschlag2()" class="inputzahl" <!--disableForm--> /> EUR</td>
				</td>
			</tr>
			<tr>
				<td class="tdbez" style="width: 120px">19% MwSt.:</td>
				<td><input type="text" name="gsteuer" id="gsteuer" value="<!--GesamtMwSt-->" size="8" disabled class="disabled"/> EUR</td>
				<td><input type="text" name="rsteuer" id="rsteuer" value="<!--MwSt-->" size="8" disabled class="disabled"/> EUR</td>
			</tr>
			<tr>
				<td class="tdbez" style="width: 120px">Summe (Brutto):</td>
				<td><input type="text" name="gsumme" id="gsumme" value="<!--GesamtBrutto-->" size="8" disabled class="disabled"/> EUR</td>
				<td><input type="text" name="rsumme" id="rsumme" value="<!--BruttoBetrag-->" size="8" disabled class="disabled"/> EUR</td>
			</tr>
			<tr>
				<td class="tdbez" style="width: 120px">Abschlag  (Netto):</td>
				<td>
					<input type="text" name="anetto" id="anetto" value="<!--AbschlaegeBisher-->" size="8" disabled class="disabled"/> EUR
				</td>
				<td>
					&nbsp;
				</td>
			</tr>
		</table>
		<br/>
		<input type="submit" class="button iconButton saveButton" value="<!--SubmitValue-->" id="drucken" disabled>
	</form>