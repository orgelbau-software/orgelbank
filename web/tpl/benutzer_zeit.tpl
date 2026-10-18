<h3>Zeiterfassung - <!--Benutzername--></h3>
<!--Statusmeldung-->
<form method="post" action="<!--FormTarget-->">
<input type="hidden" name="formName" value="projektswitch" />
<select name="m" onchange="this.form.submit()" <!--MitarbeiterSwitchDisabled-->>
  <!--Mitarbeiter-->
</select>
<select name="p" onchange="this.form.submit()">
  <!--ProjektSelectbox-->
</select>

<select name="w" onchange="this.form.submit()">
<!-- KWSelect -->
</select>
<input type="submit" value="Anzeigen" class="button iconButton refreshButton"/>
<!--<input type="checkbox" class="jsColumn" id="jsZeiterfassungWochenende" value="zeiterfassungWochenende" /><label for="jsZeiterfassungWochenende">Sa/So</label>-->
</form>
<!--HTMLStatus-->
<form method="post" action="<!--FormTarget-->">
<table class="liste size100">
	<!--Datensaetze-->
<tr>
    <td style="width: 200px;">&nbsp;</td>
    <td style="font-weight: bold;">Summe Projekt (IST):</td>
    <td><input disabled class="disabled zeiteingabe" style="font-weight: bold;" type="number" name="summe_summe" value="<!--Summe7-->" size="3" /></td>
    <td><input disabled class="disabled zeiteingabe" type="number" name="summe_montag" value="<!--Summe1-->" size="3" /></td>
    <td><input disabled class="disabled zeiteingabe" type="number" name="summe_dienstag" value="<!--Summe2-->" size="3" /></td>
    <td><input disabled class="disabled zeiteingabe" type="number" name="summe_mittwoch" value="<!--Summe3-->" size="3" /></td>
    <td><input disabled class="disabled zeiteingabe" type="number" name="summe_donnerstag" value="<!--Summe4-->" size="3" /></td>
    <td><input disabled class="disabled zeiteingabe" type="number" name="summe_freitag" value="<!--Summe5-->" size="3" /></td>
    <td class="zeiterfassungWochenende"><input disabled class="disabled zeiteingabe" type="number" name="summe_samstag" value="<!--Summe6-->" size="3" /></td>
    <td class="zeiterfassungWochenende"><input disabled class="disabled zeiteingabe" type="number" name="summe_sonntag" value="<!--Summe0-->" size="3" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
  	<td style="width: 200px;">&nbsp;</td>
    <td style="font-weight: bold;">Summe Gesamt:</td>
  	<td><input readonly class="disabled zeiteingabe" style="font-weight: bold;" type="number" name="summe_alleprojekte" value="<!--SummeAlleProjekte-->" />
  	<td><input readonly class="disabled zeiteingabe" style="font-weight: bold;" type="number" name="summe_alleprojekte" value="<!--MontagAlleProjekte-->"  />
  	<td><input readonly class="disabled zeiteingabe" style="font-weight: bold;" type="number" name="summe_alleprojekte" value="<!--DienstagAlleProjekte-->"  />
  	<td><input readonly class="disabled zeiteingabe" style="font-weight: bold;" type="number" name="summe_alleprojekte" value="<!--MittwochAlleProjekte-->" />
  	<td><input readonly class="disabled zeiteingabe" style="font-weight: bold;" type="number" name="summe_alleprojekte" value="<!--DonnerstagAlleProjekte-->"  />
  	<td><input readonly class="disabled zeiteingabe" style="font-weight: bold;" type="number" name="summe_alleprojekte" value="<!--FreitagAlleProjekte-->"  />
  	<td class="zeiterfassungWochenende" ><input readonly class="disabled zeiteingabe" style="font-weight: bold;" type="number" name="summe_alleprojekte" value="<!--SamstagAlleProjekte-->"  />
  	<td class="zeiterfassungWochenende" ><input readonly class="disabled zeiteingabe" style="font-weight: bold;" type="number" name="summe_alleprojekte" value="<!--SonntagAlleProjekte-->"  />
  	<td>&nbsp;</td>
  </td>
</table>
<table class="liste size100">
  <tr>
    <th colspan="6">Reisekosten pro Arbeitstag</th>
  </tr>
  <tr>
    <td colspan="6"><hr/></td>
  </tr>
  <tr>
    <th>Arbeitstag</th>
    <th>Spesen</th>
    <th>Hotel</th>
    <th>Kilometer</th>
    <th>Kilometerkosten</th>
    <th>Gesamt</th>
  </tr>
  <!--ReisekostenTage-->
  <tr class="reisekostenWochensumme">
    <th>Wochensumme</th>
    <th><!--SummeReisekostenSpesen--></th>
    <th><!--SummeReisekostenHotel--></th>
    <th><!--SummeReisekostenKM--></th>
    <th></th>
    <th><!--SummeReisekostenGesamt--></th>
  </tr>
  <tr>
    <th colspan="2"><input type="checkbox" name="woche_komplett" <!--WocheKomplettChecked--> <!--WocheKomplettDisabled--> id="woche_komplett"/><label for="woche_komplett">Eingabe vollst&auml;ndig</label></th>
    <th colspan="2"></th>
    <th colspan="2" style="text-align: right"><input class="button iconButton saveButton" type="submit" name="submit" value="Speichern" <!--SpeichernDisabled-->/></th>
  </tr>
  <tr>
    <th colspan="2"><input class="button iconButton backButton" type="submit" name="submit" value="Vorherige Woche" /></th>
    <th colspan="2" style="text-align: center"><input class="button iconButton refreshButton" type="submit" name="submit" value="Aktuelle Woche" /></th>
    <th style="text-align: right" colspan="2"><input class="button iconButton forwardButton" type="submit" name="submit" value="N&auml;chste Woche" /></th>
  </tr>
  </table>

</form>