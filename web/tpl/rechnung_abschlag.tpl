<script type="text/javascript">
	var abschlag1text = '<!--Abschlag1Text-->';
	var abschlag2text = '<!--Abschlag2Text-->';
	var abschlag3text = '<!--Abschlag3Text-->';
	
	var abschlag1prozent = '<!--Abschlag1Prozent-->';
	var abschlag2prozent = '<!--Abschlag2Prozent-->';
	var abschlag3prozent = '<!--Abschlag3Prozent-->';
</script>

<h3>Neue Abschlagsrechnung <!--KopfzeilenZusatz--></h3>
	<form action="index.php?page=5&do=87" method="post">
		<input type="hidden" name="gemeindebezeichnung" id="gemeindebezeichnung" value="<!--GemeindeBezeichnung-->" />
		<input type="hidden" name="standardzahlungsziel" id="jsZahlungsziel" value="<!--StandardZahlungsziel-->" />
<!--RechnungsKopf-->
<br/>
<table class="rechnung" id="rechnungen">
	<!--Rechnungen-->
</table>
</br>
<table class="rechnung">
		<tr>
			<th colspan="6" >Rechnungsdetails:</th>
		</tr>
			<tr>
				<td class="tdbez">RE-Datum:</td>
				<td><input <!--disableForm--> id="jsDatePickerRechnungsdatum" class="datePickerRechnung" type="date" name="datum" value="<!--Rechnungsdatum-->" /></td>
				<td class="tdbez">Zahlungsziel:</td>
				<td><input <!--disableForm--> id="jsDatePickerZieldatum" type="date" name="zahlungsziel" value="<!--Zahlungsziel-->" /></td>
				<td class="tdbez">RE-Nr:</td>
				<td><input <!--disableForm--> type="text" name="rechnungsnummer" value="<!--Rechnungsnummer-->/<!--Rechnungsjahr-->" size="8"/></td>
			</tr>
			<tr>
				<td class="tdbez">Nummer:</td>
				<td colspan="5">
					<select name="anr" id="jsAbschlagNr">
						<option value="1">1. Abschlag</option>
						<option value="2">2. Abschlag</option>
						<option value="3">3. Abschlag</option>
						<option value="4">4. Abschlag</option>
						<option value="5">5. Abschlag</option>
						<option value="6">6. Abschlag</option>
						<option value="7">7. Abschlag</option>
						<option value="8">8. Abschlag</option>
						<option value="9">9. Abschlag</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="tdbez">Titel:</td>
				<td colspan="5"><input <!--disableForm--> type="text" name="titel" value="<!--Titel-->" class="txt500"/></td>
			</tr>
			<tr>
				<td class="tdbez" colspan="6">Einleitung:</td>
			<tr>
				<td colspan="6">
					<textarea id="bemerkung1" name="einleitung" class="txtarea580"><!--Einleitung--></textarea>
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
				<td class="tdbez">Abschlag:</td>
			</tr>
			<tr>
				<td class="tdbez" class="width120">Nettobetrag:</td>
				<td>
					<input type="number" min="0" step="0.01"  name="gnetto" id="gnetto" value="<!--GesamtNetto-->" size="8" onChange="berechneAbschlag()" class="inputzahl rechnungseingabe"/> EUR
				</td>
				<td>
					<input type="number" min="0" step="0.01"  name="anetto" id="anetto" value="<!--AbschlagNetto-->" size="8" onChange="berechneAbschlag()" class="inputzahl rechnungseingabe"/> EUR
				</td>
			</tr>
			<tr>
				<td class="tdbez" class="width120"><!--MwStSatz-->% MwSt.:</td>
				<td>
					<input type="number" min="0" step="0.01"  name="gsteuer" id="gsteuer" value="<!--GesamtMwSt-->" size="8" disabled class="disabled rechnungseingabe"/> EUR
				</td>
				<td>
					<input type="number" min="0" step="0.01"  name="asteuer" id="asteuer" value="<!--AbschlagMwSt-->" size="8" disabled class="disabled rechnungseingabe"/> EUR
				</td>
			</tr>
			<tr>
				<td class="tdbez" class="width120">Rechnungsbetrag:</td>
				<td>
					<input type="number" min="0" step="0.01" name="gsumme" id="gsumme" value="<!--GesamtSumme-->" size="8" disabled class="disabled rechnungseingabe"/> EUR
				</td>
				<td>
					<input type="number" min="0" step="0.01"  name="asumme" id="asumme" value="<!--AbschlagSumme-->" size="8" disabled class="disabled rechnungseingabe"/> EUR
				</td>
			</tr>
			<tr>
				<td class="tdbez" class="width120">Abschlagssatz:</td>
				<td colspan="2">
					<input <!--disableForm--> type="number" min="0" step="0.01" name="satz" id="satz" value="<!--AbschlagSatz-->" size="1" onChange="berechneAbschlag()" class="inputzahl rechnungseingabe"/> Prozent
				</td>
			</tr>
		</table>
       <hr class="bottomMenuHR"/>
       <div class="bottomMenu">
		<input <!--disableForm--> type="submit" class="button iconButton saveButton" value="<!--SubmitValue-->">
    </div>
	</form>