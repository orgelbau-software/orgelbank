<table>
  <tr>
    <td>
    <h3>Projektdetails</h3>
    <table class="liste txt320" style="margin-right: 20px;">
      <tr>
        <th>Bezeichnung:</th>
        <td><!--Bezeichnung--></td>
      </tr>
      <tr>
        <th>Gemeinde:</th>
        <td><a title="Zur Gemeinde" href="index.php?page=1&do=2&gid=<!--GID-->"><!--Gemeinde--></a></td>
      </tr>
      <tr>
        <th>Beschreibung:</th>
        <td><!--Beschreibung--></td>
      </tr>
      <tr>
        <th>Startermin:</th>
        <td><!--Start--></td>
      </tr>
      <tr>
        <th>Endtermin:</th>
        <td><!--Ende--></td>
      </tr>
      <tr>
        <th>Angebotspreis:</th>
        <td><span class="jsFormatNumber"><!--Angebotspreis--></span> EUR</td>
      </tr>
    </table>

    <h3>Kostenübersicht</h3>
    <table class="liste txt320">
      <tr>
        <th>Lohnkosten:</th>
        <td>&nbsp;</td>
        <td class="alignRight"><!--GesamtLohnkosten--> EUR</td>
      </tr>
      <tr>
        <th>Materialkosten:</th>
        <td>+</td>
        <td class="alignRight"><!--GesamtMaterialkosten--> EUR</td>
      </tr>
      <tr>
        <th>Reisekosten:</th>
        <td>+</td>
        <td class="alignRight"><!--GesamtReisekosten--> EUR</td>
      </tr>
      <tr>
        <th>Gesamtkosten:</th>
        <td class="projekkostenrechnungsumme">=</td>
        <td class="alignRight projekkostenrechnungsumme zwischensumme"><!--Gesamtkosten--> EUR</td>
      </tr>
      <tr>
        <th>Plankosten:</th>
        <td>-</td>
        <td class="alignRight"><!--GesamtPlankosten--> EUR</td>
      </tr>
      <tr>
        <th>Plan - Gesamtkosten:</th>
        <td class="projekkostenrechnungsumme">=</td>
        <td class="alignRight projekkostenrechnungsumme zwischensumme <!--ZwischensummePlankosten-->"><b><!--Rest--> EUR</b></td>
      </tr>
      <tr>
        <th><small>Nicht beachtet:</small></th>
        <td></td>
        <td class="alignRight"><small><!--NichtBeachtet--> EUR</b></small></td>
      </tr>
      <tr>
        <th class="projektgewinnverlust">Gewinn/Verlust:</th>
        <td class="projektgewinnverlust">=</td>
        <td class="alignRight projektgewinnverlust <!--GewinnVerlustClass-->" style="vertical-align: bottom;"><b><!--GewinnVerlust--> EUR</b></td>
      </tr>
        
    </table>
    <h3>Materialrechnungen</h3>
    <form method="post" action="index.php?page=6&do=110&pid=<!--ProjektID-->"> <input type="hidden" name="pr_id"
      value="<!--PRID-->" />
    <table class="liste txt320">
      <tr>
        <th>Datum:</th>
        <td>
        <div class="inputDatum"><input type="text" class="datePicker" name="datum" value="<!--Datum-->"
        size="8" /></div>
        </td>
      </tr>
      <tr>
        <th>Kostenstelle:</th>
        <td><select name="kostenstelle" class="txt210">
          <!--Hauptaufgaben-->
        </select></td>
      </tr>
      <tr>
        <th>Lieferant:</th>
        <td><input type="text" name="lieferant" value="<!--Lieferant-->" class="txt210"/></td>
      </tr>
      <tr>
        <th>Nummer:</th>
        <td><input type="text" name="nummer" value="<!--Nummer-->" class="txt210"/></td>
      </tr>
      <tr>
        <th>Kommentar:</th>
        <td><textarea name="kommentar" class="txt210"><!--Kommentar--></textarea></td>
      </tr>
      <tr>
        <th>Betrag:</th>
        <td><input type="text" name="betrag" class="int70 jsFormatNumber" value="<!--Betrag-->"/> EUR</td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <td><input type="submit" name="submit" class="button iconButton saveButton" value="<!--SubmitButton-->"
        /> <a class="buttonLink iconButton cancelButton" href="index.php?page=6&do=110&pid=<!--ProjektID-->"
        title="Abbrechen">Abbrechen</a></td>
      </tr>
    </table>
    </form>
    </td>
    <td>
    <h3>Aufgaben</h3>
    <table class="liste">
      <tr>
        <th colspan="4">Aufgaben:</th>
        <th colspan="1">Lohnkosten</th>
        <th>Materialkosten</th>
        <th>Gesamtkosten</th>
        <th>Plankosten</th>
      </tr>
      <!--Projektaufgaben-->
      <tr class="summenzeile">
        <td colspan="4">Summen:
        </th>
        <td colspan="1" class="alignRight"><!--GesamtLohnkosten-->
        </th>
        <td class="alignRight"><!--GesamtMaterialkosten-->
        </th>
        <td class="alignRight"><!--Gesamtkosten-->
        </th>
        <td class="alignRight"><!--GesamtPlankosten-->
        </th>
      </tr>
    </table>

    <h3>Reisekosten</h3>
    <table class="liste size100">
      <!--Reisekosten-->
    </table>

    <h3>Alle Eingangsrechnungen</h3>
    <table class="liste size100">
      <tr>
        <th><a href="index.php?page=6&do=110&pid=<!--ProjektID-->&order=datum&dir=<!--TPLDIR-->">Datum</a></th>
        <th><a href="index.php?page=6&do=110&pid=<!--ProjektID-->&order=lieferant&dir=<!--TPLDIR-->">Lieferant</a></th>
        <th><a href="index.php?page=6&do=110&pid=<!--ProjektID-->&order=nummer&dir=<!--TPLDIR-->">Nummer</a></th>
        <th><a href="index.php?page=6&do=110&pid=<!--ProjektID-->&order=betrag&dir=<!--TPLDIR-->">Betrag</a></th>
        <th><a href="index.php?page=6&do=110&pid=<!--ProjektID-->&order=aufgab&dir=<!--TPLDIR-->">Kostenstelle</a></th>
        <th colspan="2">&nbsp;</th>
      </tr>
      <!--ProjektRechnungen-->
    </table>

    </td>
  </tr>
</table>
<hr class="bottomMenuHR" />
<div class="bottomMenu"><a href="index.php?page=6&do=105&a=d&pid=<!--ProjektID-->" title="Projekt bearbeiten"
class="buttonlink iconButton editButton">Bearbeiten</a></div>
<a href="index.php?page=6&do=101&p=<!--ProjektID-->" title="Zeiten eintragen" class="buttonlink iconButton
calendarButton">Zeiten eintragen</a>
