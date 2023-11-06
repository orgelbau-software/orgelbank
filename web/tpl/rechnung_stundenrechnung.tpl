<h3>Neue Stundenrechnung <!--KopfzeilenZusatz--></h3>
	<form action="index.php?page=5&do=86" method="post">
		<input type="hidden" name="gemeindebezeichnung" id="gemeindebezeichnung" value="<!--GemeindeBezeichnung-->" />
		<input type="hidden" name="standardzahlungsziel" id="jsZahlungsziel" value="<!--StandardZahlungsziel-->" />
<!--RechnungsKopf-->
<br/>
<table class="rechnung">
		<tr>
			<th colspan="6">Rechnungsdetails:</th>
		</tr>
			<tr>
				<td class="tdbez">RE-Datum:</td>
				<td><input <!--disableForm--> id="jsDatePickerRechnungsdatum" class="datePickerRechnung"  type="date" name="datum" value="<!--Rechnungsdatum-->" /></td>
				<td class="tdbez">Zahlungsziel:</td>
				<td><input <!--disableForm--> id="jsDatePickerZieldatum" type="date" name="zahlungsziel" value="<!--Zahlungsziel-->" /></td>
				<td class="tdbez">RE-Nr:</td>
				<td><input <!--disableForm--> type="text" name="rechnungsnummer" value="<!--Rechnungsnummer-->/<!--Rechnungsjahr-->" size="8"/></td>
			</tr>
			<tr>
				<td class="tdbez">Einleitungstext:</td>
				<td colspan="5">
					<input id="bem1" style="border: 0px" type="radio" value="2" name="text" onclick="rechnungEinleitung('auftrag', 'bemerkung1')" <!--disableForm--> />
						<label for="bem1">Aufrag</label>
					<input id="bem2" style="border: 0px" type="radio" value="3" name="text" onclick="rechnungEinleitung('angebot', 'bemerkung1')" <!--disableForm--> />
						<label for="bem2">Angebot</label>
				</td>
			</tr>
			<tr>
				<td colspan="6">
					<textarea id="bemerkung1" name="bemerkung1" class="txtarea580"><!--Bemerkung1--></textarea>
				</td>
			</tr>
			<tr>
				<td class="tdbez">Position 1:</td>
				<td colspan="5"><input <!--disableForm--> type="text" name="position_1" value="<!--Standardposition1-->" class="txt470 jsRechnungsPositionsSuggestion"/></td>
			</tr>
			<tr>
				<td class="tdbez">Position 2:</td>
				<td colspan="5"><input <!--disableForm--> type="text" name="position_2" value="<!--Standardposition2-->"  class="txt470 jsRechnungsPositionsSuggestion"/></td>
			</tr>
			<tr>
				<td class="tdbez">Position 3:</td>
				<td colspan="5"><input <!--disableForm--> type="text" name="position_3" value="<!--Standardposition3-->"  class="txt470 jsRechnungsPositionsSuggestion"/></td>
			</tr>
			<tr>
				<td class="tdbez">Position 4:</td>
				<td colspan="5"><input <!--disableForm--> type="text" name="position_4" value="<!--Standardposition4-->"  class="txt470 jsRechnungsPositionsSuggestion"/></td>
			</tr>
			<tr>
				<td class="tdbez">Position 5:</td>
				<td colspan="5"><input <!--disableForm--> type="text" name="position_5" value="<!--Standardposition5-->"  class="txt470 jsRechnungsPositionsSuggestion"/></td>
			</tr>
			<tr>
				<td class="tdbez">Position 6:</td>
				<td colspan="5"><input <!--disableForm--> type="text" name="position_6" value="<!--Standardposition6-->"  class="txt470 jsRechnungsPositionsSuggestion"/></td>
			</tr>
			<tr>
				<td class="tdbez" colspan="6">Schlusstext:</td>
			</tr>
			<tr>
				<td colspan="6"><textarea <!--disableForm--> id="bemerkung2" name="bemerkung2"  class="txtarea580"><!--Bemerkung2--></textarea></td>
			</tr>
		</table>
		<br/>
		<table class="rechnung">
			<tr>
				<th colspan="3">Rechnungsbetrag</th>
			</tr>
			<tr>
				<td class="tdbez" style="width: 120px">1 Geselle:</td>
				<td>
					<input <!--disableForm--> type="number" min="0" step="0.25" name="geselle_std" id="geselle_std" value="<!--GeselleStd-->" size="2" onChange="summiere()" class="werteingabe" /> Stunden á
					<input <!--disableForm--> type="number" min="0" step="0.01" name="geselle_lohn" id="geselle_lohn" value="<!--GeselleLohn-->" size="3" onChange="summiere()" class="werteingabe" /> Euro (Netto)
				</td>
				<td>&nbsp;&nbsp;&nbsp;<input type="number" min="0" step="0.25" size="5" name="sum_geselle" id="sum_geselle" disabled class="disabled rechnungseingabe" value="<!--SummeGeselle-->"/> Euro </td>
			</tr>
			<tr>
				<td class="tdbez" style="width: 120px">1 Auszubildende/r:</td>
				<td>
					<input <!--disableForm--> type="number" min="0" step="0.25" name="azubi_std" id="azubi_std" value="<!--AzubiStd-->" size="2" onChange="summiere()" class="werteingabe"/> Stunden á
					<input <!--disableForm--> type="number" min="0" step="0.01" name="azubi_lohn" id="azubi_lohn" value="<!--AzubiLohn-->" size="3" onChange="summiere()" class="werteingabe"/> Euro (Netto)
				</td>
				<td>+ <input type="number" size="5" name="sum_azubi" id="sum_azubi" disabled class="disabled rechnungseingabe" value="<!--SummeAzubi-->"/> Euro </td>
			</tr>
			<tr>
				<td class="tdbez" style="width: 120px">Material:</td>
				<td><input <!--disableForm--> type="number" min="0" step="0.01" name="material" id="material" value="<!--Material-->" size="5" onChange="summiere()" class="werteingabe"/> Euro (Netto)</td>
				<td>+ <input type="number" min="0" step="0.01" size="5" name="sum_material" id="sum_material" disabled class="disabled rechnungseingabe" value="<!--Material-->" onChange="summiere()"/> Euro </td>
			</tr>
			<tr>
				<td class="tdbez" style="width: 120px">Fahrtkosten:</td>
				<td><input <!--disableForm--> type="number" min="0" step="0.01" name="fahrtkosten" id="fahrtkosten" value="<!--Fahrtkosten-->" size="5" onChange="summiere()" class="werteingabe"/> Euro</td>
				<td>+ <input type="number" min="0" step="0.01" size="5" name="sum_fk" id="sum_fk" disabled class="disabled rechnungseingabe" value="<!--Fahrtkosten-->" onChange="summiere()" /> Euro </td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
				<td>= <input type="number" min="0" step="0.01" size="5" name="sum_ges" id="sum_ges" value="<!--Betrag-->" disabled class="disabled rechnungseingabe"/> Euro Gesamt</td>
			</tr>
		</table>
		<hr class="bottomMenuHR"/>
        <div class="bottomMenu">
		  <input <!--disableForm--> type="submit" class="button iconButton saveButton" value="<!--SubmitValue-->">
        </div>
	</form>