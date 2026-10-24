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
  </tr>

  <!-- Start der Reisekosten -->
  <tr>
    <th colspan="2" class="projdesc">Reisekosten</th>
    <th class="projth">Summe</th>
    <th class="projth">Montag</th>
    <th class="projth">Dienstag</th>
    <th class="projth">Mittwoch</th>
    <th class="projth">Donnerstag</th>
    <th class="projth">Freitag</th>
    <th class="projth zeiterfassungWochenende">Samstag</th>
    <th class="projth zeiterfassungWochenende">Sonntag</th>
    <th class="projth">&nbsp;</th>
  </tr>
  <tr>
    <td class="td1" style="width: 200px;">&nbsp;</td>
    <td class="td1">Spesen</td>
    <td class="td1"><input type="number" disabled class="disabled zeiteingabe reisekosten reisekosten-wochensumme" data-rk-field="spesen" value="<!--RKSpesenSumme-->"/></td>
    <td class="td1"><!--Verpflegungskosten0--></td>
    <td class="td1"><!--Verpflegungskosten1--></td>
    <td class="td1"><!--Verpflegungskosten2--></td>
    <td class="td1"><!--Verpflegungskosten3--></td>
    <td class="td1"><!--Verpflegungskosten4--></td>
    <td class="zeiterfassungWochenende td1"><!--Verpflegungskosten5--></td>
    <td class="zeiterfassungWochenende td1"><!--Verpflegungskosten6--></td>
    <td class="td1">&nbsp;</td>
  </tr>
  <tr>
    <td class="td2" style="width: 200px;">&nbsp;</td>
    <td class="td2">Hotelkosten</td>
    <td class="td2"><input type="number" disabled class="disabled zeiteingabe reisekosten reisekosten-wochensumme" data-rk-field="hotel" value="<!--RKHotelSumme-->"/></td>
    <td class="td2"><input type="number" name="reisekosten[<!--RKDatum0-->][hotel]" value="<!--RKHotelTag0-->" class="zeiteingabe reisekosten<!--RKDisabledClass--> <!--RKTagDisabledClass0-->" data-rk-date="<!--RKDatum0-->" data-rk-field="hotel" min="0" step="0.01" <!--RKDisabled--> <!--RKTagDisabled0--> /></td>
    <td class="td2"><input type="number" name="reisekosten[<!--RKDatum1-->][hotel]" value="<!--RKHotelTag1-->" class="zeiteingabe reisekosten<!--RKDisabledClass--> <!--RKTagDisabledClass1-->" data-rk-date="<!--RKDatum1-->" data-rk-field="hotel" min="0" step="0.01" <!--RKDisabled--> <!--RKTagDisabled1--> /></td>
    <td class="td2"><input type="number" name="reisekosten[<!--RKDatum2-->][hotel]" value="<!--RKHotelTag2-->" class="zeiteingabe reisekosten<!--RKDisabledClass--> <!--RKTagDisabledClass2-->" data-rk-date="<!--RKDatum2-->" data-rk-field="hotel" min="0" step="0.01" <!--RKDisabled--> <!--RKTagDisabled2--> /></td>
    <td class="td2"><input type="number" name="reisekosten[<!--RKDatum3-->][hotel]" value="<!--RKHotelTag3-->" class="zeiteingabe reisekosten<!--RKDisabledClass--> <!--RKTagDisabledClass3-->" data-rk-date="<!--RKDatum3-->" data-rk-field="hotel" min="0" step="0.01" <!--RKDisabled--> <!--RKTagDisabled3--> /></td>
    <td class="td2"><input type="number" name="reisekosten[<!--RKDatum4-->][hotel]" value="<!--RKHotelTag4-->" class="zeiteingabe reisekosten<!--RKDisabledClass--> <!--RKTagDisabledClass4-->" data-rk-date="<!--RKDatum4-->" data-rk-field="hotel" min="0" step="0.01" <!--RKDisabled--> <!--RKTagDisabled4--> /></td>
    <td class="td2 zeiterfassungWochenende"><input type="number" name="reisekosten[<!--RKDatum5-->][hotel]" value="<!--RKHotelTag5-->" class="zeiteingabe reisekosten<!--RKDisabledClass--> <!--RKTagDisabledClass5-->" data-rk-date="<!--RKDatum5-->" data-rk-field="hotel" min="0" step="0.01" <!--RKDisabled--> /></td>
    <td class="td2 zeiterfassungWochenende"><input type="number" name="reisekosten[<!--RKDatum6-->][hotel]" value="<!--RKHotelTag6-->" class="zeiteingabe reisekosten<!--RKDisabledClass-->  <!--RKTagDisabledClass6-->" data-rk-date="<!--RKDatum6-->" data-rk-field="hotel" min="0" step="0.01" <!--RKDisabled--> /></td>
    <td class="td2">&nbsp;</td>
  </tr>
  <tr>
    <td class="td1" style="width: 200px;">&nbsp;</td>
    <td class="td1">Kilometer (Anzahl)</td>
    <td class="td1"><input type="number" disabled class="disabled zeiteingabe reisekosten reisekosten-wochensumme" data-rk-field="km" value="<!--RKKmSumme-->"/></td>
    <td class="td1"><input type="number" name="reisekosten[<!--RKDatum0-->][km]" value="<!--RKKmTag0-->" class="zeiteingabe reisekosten<!--RKDisabledClass--> <!--RKTagDisabledClass0-->" data-rk-date="<!--RKDatum0-->" data-rk-field="km" min="0" step="1" <!--RKDisabled--> <!--RKTagDisabled0--> /></td>
    <td class="td1"><input type="number" name="reisekosten[<!--RKDatum1-->][km]" value="<!--RKKmTag1-->" class="zeiteingabe reisekosten<!--RKDisabledClass--> <!--RKTagDisabledClass1-->" data-rk-date="<!--RKDatum1-->" data-rk-field="km" min="0" step="1" <!--RKDisabled--> <!--RKTagDisabled1--> /></td>
    <td class="td1"><input type="number" name="reisekosten[<!--RKDatum2-->][km]" value="<!--RKKmTag2-->" class="zeiteingabe reisekosten<!--RKDisabledClass--> <!--RKTagDisabledClass2-->" data-rk-date="<!--RKDatum2-->" data-rk-field="km" min="0" step="1" <!--RKDisabled--> <!--RKTagDisabled2--> /></td>
    <td class="td1"><input type="number" name="reisekosten[<!--RKDatum3-->][km]" value="<!--RKKmTag3-->" class="zeiteingabe reisekosten<!--RKDisabledClass--> <!--RKTagDisabledClass3-->" data-rk-date="<!--RKDatum3-->" data-rk-field="km" min="0" step="1" <!--RKDisabled--> <!--RKTagDisabled3--> /></td>
    <td class="td1"><input type="number" name="reisekosten[<!--RKDatum4-->][km]" value="<!--RKKmTag4-->" class="zeiteingabe reisekosten<!--RKDisabledClass--> <!--RKTagDisabledClass4-->" data-rk-date="<!--RKDatum4-->" data-rk-field="km" min="0" step="1" <!--RKDisabled--> <!--RKTagDisabled4--> /></td>
    <td class="td1 zeiterfassungWochenende"><input type="number" name="reisekosten[<!--RKDatum5-->][km]" value="<!--RKKmTag5-->" class="zeiteingabe reisekosten<!--RKDisabledClass--> <!--RKTagDisabledClass5-->" data-rk-date="<!--RKDatum5-->" data-rk-field="km" min="0" step="1" <!--RKDisabled--> /></td>
    <td class="td1 zeiterfassungWochenende"><input type="number" name="reisekosten[<!--RKDatum6-->][km]" value="<!--RKKmTag6-->" class="zeiteingabe reisekosten<!--RKDisabledClass--> <!--RKTagDisabledClass6-->" data-rk-date="<!--RKDatum6-->" data-rk-field="km" min="0" step="1" <!--RKDisabled--> /></td>
    <td class="td1">&nbsp;</td>
  </tr>
  <tr>
    <td class="td2" style="width: 200px;">&nbsp;</td>
    <td class="td2">Fahrtkosten (&euro;)</td>
    <td class="td2"><input type="number" disabled class="disabled zeiteingabe reisekosten reisekosten-wochensumme" data-rk-field="kmkosten" value="<!--RKKmKostenSumme-->"/></td>
    <td class="td2"><input type="number" disabled class="disabled zeiteingabe reisekosten" data-rk-date="<!--RKDatum0-->" data-rk-field="kmkosten" value="<!--RKKmKostenTag0-->"/></td>
    <td class="td2"><input type="number" disabled class="disabled zeiteingabe reisekosten" data-rk-date="<!--RKDatum1-->" data-rk-field="kmkosten" value="<!--RKKmKostenTag1-->"/></td>
    <td class="td2"><input type="number" disabled class="disabled zeiteingabe reisekosten" data-rk-date="<!--RKDatum2-->" data-rk-field="kmkosten" value="<!--RKKmKostenTag2-->"/></td>
    <td class="td2"><input type="number" disabled class="disabled zeiteingabe reisekosten" data-rk-date="<!--RKDatum3-->" data-rk-field="kmkosten" value="<!--RKKmKostenTag3-->"/></td>
    <td class="td2"><input type="number" disabled class="disabled zeiteingabe reisekosten" data-rk-date="<!--RKDatum4-->" data-rk-field="kmkosten" value="<!--RKKmKostenTag4-->"/></td>
    <td class="td2 zeiterfassungWochenende"><input type="number" disabled class="disabled zeiteingabe reisekosten" data-rk-date="<!--RKDatum5-->" data-rk-field="kmkosten" value="<!--RKKmKostenTag5-->"/></td>
    <td class="td2 zeiterfassungWochenende"><input type="number" disabled class="disabled zeiteingabe reisekosten" data-rk-date="<!--RKDatum6-->" data-rk-field="kmkosten" value="<!--RKKmKostenTag6-->"/></td>
    <td class="td2">&nbsp;</td>
  </tr>

  <!-- Reisekosten Summen -->
  <tr>
    <td style="width: 200px;">&nbsp;</td>
    <td style="font-weight: bold;">Reisekosten Gesamt</td>
    <td><input type="number" disabled style="font-weight: bold;" class="disabled zeiteingabe reisekosten reisekosten-wochensumme" data-rk-field="gesamt" value="<!--RKGesamtSumme-->"/></td>
    <td><input type="number" disabled style="font-weight: bold;" class="disabled zeiteingabe reisekosten" data-rk-date="<!--RKDatum0-->" data-rk-field="gesamt" value="<!--RKGesamtTag0-->"/></td>
    <td><input type="number" disabled style="font-weight: bold;" class="disabled zeiteingabe reisekosten" data-rk-date="<!--RKDatum1-->" data-rk-field="gesamt" value="<!--RKGesamtTag1-->"/></td>
    <td><input type="number" disabled style="font-weight: bold;" class="disabled zeiteingabe reisekosten" data-rk-date="<!--RKDatum2-->" data-rk-field="gesamt" value="<!--RKGesamtTag2-->"/></td>
    <td><input type="number" disabled style="font-weight: bold;" class="disabled zeiteingabe reisekosten" data-rk-date="<!--RKDatum3-->" data-rk-field="gesamt" value="<!--RKGesamtTag3-->"/></td>
    <td><input type="number" disabled style="font-weight: bold;" class="disabled zeiteingabe reisekosten" data-rk-date="<!--RKDatum4-->" data-rk-field="gesamt" value="<!--RKGesamtTag4-->"/></td>
    <td class="zeiterfassungWochenende"><input type="number" disabled style="font-weight: bold;" class="disabled zeiteingabe reisekosten" data-rk-date="<!--RKDatum5-->" data-rk-field="gesamt" value="<!--RKGesamtTag5-->"/></td>
    <td class="zeiterfassungWochenende"><input type="number" disabled style="font-weight: bold;" class="disabled zeiteingabe reisekosten" data-rk-date="<!--RKDatum6-->" data-rk-field="gesamt" value="<!--RKGesamtTag6-->"/></td>
    <td>&nbsp;</td>
  </tr>
</table>
<table class="liste size100">
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