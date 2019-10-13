	<h3>Neue Pflegerechnung <!--KopfzeilenZusatz--></h3>
	<form action="index.php?page=5&do=82" method="post">
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
				<td><div class="inputDatum"><input <!--disableForm--> id="jsDatePickerRechnungsdatum" class="datePickerRechnung" type="text" name="datum" value="<!--Rechnungsdatum-->" size="9"/></div></td>
				<td class="tdbez">Zahlungsziel:</td>
				<td><div class="inputDatum"><input <!--disableForm--> id="jsDatePickerZieldatum" class="datePicker"type="text" name="zahlungsziel" value="<!--Zahlungsziel-->" size="9"/></div></td>
				<td class="tdbez">RE-Nr:</td>
				<td><input <!--disableForm--> type="text" name="rechnungsnummer" value="<!--Rechnungsnummer-->/<!--Rechnungsjahr-->" size="8"/></td>
			</tr>
			<tr>
				<td class="tdbez">Rechnungsart:</td>
				<td colspan="5">
					<input id="bem1" style="border: 0px" type="radio" value="1" name="text" onclick="rechnungEinleitung('pflege', 'bemerkung1')" <!--disableForm-->/> 
						<label for="bem1">Pflegevertrag</label>
					<input id="bem2" style="border: 0px" type="radio" value="2" name="text" onclick="rechnungEinleitung('auftrag', 'bemerkung1')" <!--disableForm-->/>
						<label for="bem2">Aufrag</label>
					<input id="bem3" style="border: 0px" type="radio" value="3" name="text" onclick="rechnungEinleitung('angebot', 'bemerkung1')" <!--disableForm-->/>
						<label for="bem3">Angebot</label>
				</td>
			</tr>
			<tr>
				<td class="tdbez" colspan="6">Einleitungstext:</td>
			</tr>
			<tr>
				<td colspan="6" colspan="6">
					<textarea <!--disableForm--> id="bemerkung1" name="bemerkung1" class="txtarea580"><!--Bemerkung1--></textarea>
				</td>
			</tr>
			<tr>
				<td class="tdbez" colspan="6">Schlusstext:</td>
			</tr>
			<tr>
				<td colspan="6"><textarea <!--disableForm--> id="bemerkung2" name="bemerkung2" class="txtarea580"><!--Bemerkung2--></textarea></td>
			</tr>
		</table>
		<br/>
		<table class="rechnung">
			<tr>
				<th colspan="2">Rechnungspositionen</th>
			</tr>
			<tr>
				<td class="tdbez">Position 1:</td>
				<td><input <!--disableForm--> type="text" name="position_1" value="<!--Standardposition1-->" class="txt490 jsRechnungsPositionsSuggestion"/></td>
			</tr>
			<tr>
				<td class="tdbez">Position 2:</td>
				<td><input <!--disableForm--> type="text" name="position_2" value="<!--Standardposition2-->" class="txt490 jsRechnungsPositionsSuggestion"/></td>
			</tr>
			<tr>
				<td class="tdbez">Position 3:</td>
				<td><input <!--disableForm--> type="text" name="position_3" value="<!--Standardposition3-->" class="txt490 jsRechnungsPositionsSuggestion"/></td>
			</tr>
			<tr>
				<td class="tdbez">Position 4:</td>
				<td><input <!--disableForm--> type="text" name="position_4" value="<!--Standardposition4-->" class="txt490 jsRechnungsPositionsSuggestion"></td>
			</tr>
			<tr>
				<td class="tdbez">Position 5:</td>
				<td><input <!--disableForm--> type="text" name="position_5" value="<!--Standardposition5-->" class="txt490 jsRechnungsPositionsSuggestion"/></td>
			</tr>
			<tr>
				<td class="tdbez">Position 6:</td>
				<td><input <!--disableForm--> type="text" name="position_6" value="<!--Standardposition6-->" class="txt490 jsRechnungsPositionsSuggestion"/></td>
			</tr>
			<tr>
				<td class="tdbez">Position 7:</td>
				<td><input <!--disableForm--> type="text" name="position_7" value="<!--Standardposition7-->" class="txt490 jsRechnungsPositionsSuggestion"/></td>
			</tr>
			<tr>
				<td class="tdbez">Position 8:</td>
				<td><input <!--disableForm--> type="text" name="position_8" value="<!--Standardposition8-->" class="txt490 jsRechnungsPositionsSuggestion"/></td>
			</tr>
			<tr>
				<td class="tdbez">Position 9:</td>
				<td><input <!--disableForm--> type="text" name="position_9" value="<!--Standardposition9-->" class="txt490 jsRechnungsPositionsSuggestion"/></td>
			</tr>
			<tr>
				<td class="tdbez">Position 10:</td>
				<td><input <!--disableForm--> type="text" name="position_10" value="<!--Standardposition10-->" class="txt490 jsRechnungsPositionsSuggestion"/></td>
			</tr>
		</table>
		<br/>
		<table class="rechnung">
			<tr>
				<th colspan="4">Summeneingabe</th>
			</tr>
			<tr>
				<td class="tdbez" colspan="2">Aktuelle Rechnung:</td>
				<td class="tdbez">Letzte Rechnung vom:</td>
				<td class="tdbez" id="letztedatum"><!--LetzteRechnung--></td>
			</tr>
			<tr>
				<td class="tdbez">Pflegekosten:</td>
				<td>&nbsp;&nbsp;&nbsp;<input id="pflegekosten" onchange="summierePflegerechnung()" <!--disableForm--> type="text" name="pflegebetrag" value="<!--Pflegebetrag-->" size="8" class="inputzahl"/> EUR</td>
				<td colspan="2"><input onkeyup="summierePflegerechnung()" disabled id="letzterpflegebtrag" type="text" name="js_pflege" value="<!--LetztePflege-->" size="8" class="disabled inputzahl"/> EUR</td>
			</tr>
			<tr>
				<td class="tdbez">Fahrtkosten:</td>
				<td>+ <input onkeyup="summierePflegerechnung()" id="fahrtkosten" type="text" name="fahrtkosten" value="<!--Fahrtkosten-->" size="8" class="inputzahl" <!--disableForm--> /> EUR</td>
				<td colspan="2"><input disabled id="letztefahrtkosten" type="text" name="js_fahrt" value="<!--LetzteFahrt-->" size="8" class="disabled inputzahl"/> EUR</td>
			</tr>
			<tr>
				<td class="tdbez">Gesamtbetrag (Netto):</td>
				<td>= <input id="summe" disabled type="text" name="rechnungsbetrag" value="<!--Betrag-->" size="8" class="disabled inputzahl"/> EUR</td>
				<td colspan="2"><input disabled id="letztenetto" type="text" name="js_netto" value="<!--LetzteNetto-->" size="8" class="disabled inputzahl"/> EUR</td>
			</tr>
			<tr>
				<td class="tdbez">MwSt. 19%</td>
				<td>+ <input id="mwst" disabled type="text" name="mwst" value="<!--MwSt-->" size="8" class="disabled inputzahl"/> EUR</td>
				<td colspan="2"><input disabled id="letztemwst" type="text" name="js_mwst" value="<!--LetzteMwSt-->" size="8" class="disabled inputzahl"/> EUR</td>
			</tr>
				<tr>
				<td class="tdbez">Gesamtbetrag (Brutto):</td>
				<td>= <input id="jsBruttoBetrag" disabled type="text" name="brutto" value="<!--BruttoBetrag-->" size="8" class="disabled inputzahl"/> EUR</td>
				<td colspan="2"><input disabled id="letztebrutto" type="text" name="js_brutto" value="<!--LetzteBrutto-->" size="8" class="disabled inputzahl"/> EUR</td>
			</tr>
		</table>
    
    <hr class="bottomMenuHR" />
    <div class="bottomMenu">
      <input <!--disableForm--> type="submit" class="button iconButton saveButton" value="<!--SubmitValue-->"/>
      <!-- <a class="buttonlink iconButton editButton" href="index.php?page=5&do=XX" title="Bearbeiten">Bearbeiten</a>-->
      <a class="buttonlink iconButton cancelButton" href="index.php?page=5&do=89" title="Abbrechen">Abbrechen</a>
    </div>
	</form>