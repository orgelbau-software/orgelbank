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
</form>
<!--HTMLStatus-->
<form method="post" action="<!--FormTarget-->">
<div id="zeiterfassungScrollContainer">
<table class="liste size100">
	<!--Datensaetze-->
</table>
</div>
<table class="liste size100">
<tr>
    <td style="width: 200px;">&nbsp;</td>
    <td style="font-weight: bold;">Summen (IST):</td>
    <td><input disabled class="disabled" style="font-weight: bold;" type="text" name="summe_summe" value="<!--Summe7-->" size="3" /></td>
    <td><input disabled class="disabled" type="text" name="summe_montag" value="<!--Summe1-->" size="3" /></td>
    <td><input disabled class="disabled" type="text" name="summe_dienstag" value="<!--Summe2-->" size="3" /></td>
    <td><input disabled class="disabled" type="text" name="summe_mittwoch" value="<!--Summe3-->" size="3" /></td>
    <td><input disabled class="disabled" type="text" name="summe_donnerstag" value="<!--Summe4-->" size="3" /></td>
    <td><input disabled class="disabled" type="text" name="summe_freitag" value="<!--Summe5-->" size="3" /></td>
    <td><input disabled class="disabled" type="text" name="summe_samstag" value="<!--Summe6-->" size="3" /></td>
    <td><input disabled class="disabled" type="text" name="summe_sonntag" value="<!--Summe0-->" size="3" /></td>
  </tr>
  <tr>
    <td colspan="10"><hr/></td>
  </tr>
  <tr>
    <td style="width: 200px;">&nbsp;</td>
    <td class="tdLabel alignRight">Spesen:</td>
    <td><input type="text" name="spesen" value="<!--Spesen-->" class="int50 jsFormatNumber reisekosten rkvalue"/></td>
    <td class="tdLabel alignRight">Hotel:</td>
    <td><input type="text" name="hotel" value="<!--Hotel-->" class="int50 jsFormatNumber reisekosten rkvalue"/></td>
    <td class="tdLabel alignRight">Kilometer</td>
    <td><input type="text" id="km" name="km" value="<!--KM-->" class="int50 reisekosten"/></td>
    <td><input disabled type="text" id="kmkosten" name="kmkosten" value="<!--KMKosten-->" class="disabled int50 jsFormatNumber reisekosten rkvalue"/></td>
    <td class="tdLabel alignRight">Gesamt</td>
    <td><input disabled type="text" id="rk" name="rk" value="<!--RK-->" class="disabled int60 jsFormatNumber"/></td>
  </tr>
  <tr>
    <th colspan="3"><input type="checkbox" name="woche_komplett" <!--WocheKomplettChecked--> <!--WocheKomplettDisabled--> id="woche_komplett"/><label for="woche_komplett">Eingabe vollst&auml;ndig</label> </th>
    <th>&nbsp;</td>
    <th colspan="4"></th>
    <th>&nbsp;</td>
    <th style="text-align: right" colspan="3"><input class="button iconButton saveButton"  type="submit" name="submit" value="Speichern" <!--SpeichernDisabled-->/></th>
  </tr>
  <tr>
    <th colspan="3"><input class="button iconButton backButton" type="submit" name="submit" value="Vorherige Woche" /></th>
    <th>&nbsp;</td>
    <th colspan="3"><input class="button iconButton refreshButton"  type="submit" name="submit" value="Aktuelle Woche" /></th>
    <th>&nbsp;</td>
    <th style="text-align: right" colspan="3"><input class="button iconButton forwardButton"  type="submit" name="submit" value="N&auml;chste Woche" /></th>
  </tr>
  </table>

</form>