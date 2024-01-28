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
    <td colspan="10"><hr/></td>
  </tr>
  <tr>
    <td style="width: 200px;">&nbsp;</td>
    <td class="tdLabel alignRight">Spesen:</td>
    <td><input type="number" name="spesen" value="<!--Spesen-->" class="int70 reisekosten rkvalue" min="0" step="0.01"/></td>
    <td class="tdLabel alignRight">Hotel:</td>
    <td><input type="number" name="hotel" value="<!--Hotel-->" class="int70 reisekosten rkvalue" min="0" step="0.01"/></td>
    <td class="tdLabel alignRight">Kilometer</td>
    <td><input type="number" id="km" name="km" value="<!--KM-->" class="int50 reisekosten" min="0" step="1"/></td>
    <td><input disabled type="text" id="kmkosten" name="kmkosten" value="<!--KMKosten-->" class="disabled int50 reisekosten rkvalue" min="0" step="0.01"/></td>
    <td class="tdLabel alignRight">Gesamt</td>
    <td><input disabled type="number" id="rk" name="rk" value="<!--RK-->" class="disabled int70" min="0" step="0.01"/></td>
  </tr>
  <tr>
    <th colspan="3"><input type="checkbox" name="woche_komplett" <!--WocheKomplettChecked--> <!--WocheKomplettDisabled--> id="woche_komplett"/><label for="woche_komplett">Eingabe vollst&auml;ndig</label> </th>
    <th>&nbsp;</td>
    <th colspan="4"></th>
    <th colspan="3"style="text-align: right"><input class="button iconButton saveButton"  type="submit" name="submit" value="Speichern" <!--SpeichernDisabled-->/></th>
  </tr>
  <tr>
    <th colspan="3"><input class="button iconButton backButton" type="submit" name="submit" value="Vorherige Woche" /></th>
    <th>&nbsp;</td>
    <th colspan="4"><input class="button iconButton refreshButton"  type="submit" name="submit" value="Aktuelle Woche" /></th>
    <th>&nbsp;</td>
    <th style="text-align: right" colspan="2"><input class="button iconButton forwardButton"  type="submit" name="submit" value="N&auml;chste Woche" /></th>
  </tr>
  </table>

</form>