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
        <th>Nebenkosten:</th>
        <td>+</td>
        <td class="alignRight"><!--GesamtNebenkosten--> EUR</td>
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
        <input type="date" name="datum" value="<!--Datum-->" />
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
        <td><input type="number" step="0.01" name="betrag" class="int70" value="<!--Betrag-->"/> EUR</td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <td>
        	<input type="submit" name="submit" class="button iconButton saveButton" value="<!--SubmitButtonPR-->" />
        	<a class="buttonLink iconButton cancelButton" href="index.php?page=6&do=110&pid=<!--ProjektID-->"
        title="Abbrechen">Abbrechen</a></td>
      </tr>
    </table>
    </form>
    
    <h3>Nebenkosten</h3>
    <form method="post" action="index.php?page=6&do=110&pid=<!--ProjektID-->"> 
    	<input type="hidden" name="nk_id" value="<!--NKID-->" />
    <table class="liste txt320">
      <tr>
        <th>Datum:</th>
        <td><input type="date" name="nk_datum" value="<!--NKDatum-->" />
        </td>
      </tr>
      <tr>
        <th>Dienstleistung:</th>
        <td>
        <input type="text" name="nk_leistung" class="txt210" list="nebenkostenarten" value="<!--NKLeistung-->"/>
			<datalist id="nebenkostenarten">
  				<!--NebenkostenDatalist-->
			</datalist>
        </td>
      </tr>
      <tr>
        <th>Dienstleister:</th>
        <td><input type="text" name="nk_lieferant" value="<!--NKLieferant-->" class="txt210"/></td>
      </tr>
      <tr>
        <th>Mitarbeiter:</th>
        <td><select name="nk_mitarbeiter" class="txt210">
          <!--SelectMitarbeiter-->
        </select></td>
      </tr>
      <tr>
        <th>Nummer:</th>
        <td><input type="text" name="nk_nummer" value="<!--NKNummer-->" class="txt210"/></td>
      </tr>
      <tr>
        <th>Kommentar:</th>
        <td><textarea name="nk_kommentar" class="txt210"><!--NKKommentar--></textarea></td>
      </tr>
      <tr>
        <th>Betrag:</th>
        <td><input type="number" step="0.01" name="nk_betrag" class="int70" value="<!--NKBetrag-->"/> EUR</td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <td>
        	<input type="submit" name="submit" class="button iconButton saveButton" value="<!--SubmitButtonNK-->" /> 
        	<a class="buttonLink iconButton cancelButton" href="index.php?page=6&do=110&pid=<!--ProjektID-->" title="Abbrechen">Abbrechen</a>
        </td>
      </tr>
    </table>
    
    </form>
    <!--StatusMeldung-->
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
        <th><a href="index.php?page=6&do=110&pid=<!--ProjektID-->&prorder=datum&prdir=<!--TPLPRDIR-->">Datum</a></th>
        <th><a href="index.php?page=6&do=110&pid=<!--ProjektID-->&prorder=lieferant&prdir=<!--TPLPRDIR-->">Lieferant</a></th>
        <th><a href="index.php?page=6&do=110&pid=<!--ProjektID-->&prorder=nummer&prdir=<!--TPLPRDIR-->">Nummer</a></th>
        <th><a href="index.php?page=6&do=110&pid=<!--ProjektID-->&prorder=betrag&prdir=<!--TPLPRDIR-->">Betrag</a></th>
        <th><a href="index.php?page=6&do=110&pid=<!--ProjektID-->&prorder=aufgab&prdir=<!--TPLPRDIR-->">Kostenstelle</a></th>
        <th colspan="2">&nbsp;</th>
      </tr>
      <!--ProjektRechnungen-->
    </table>

    <h3>Alle Nebenkosten</h3>
    <table class="liste size100">
      <tr>
        <th><a href="index.php?page=6&do=110&pid=<!--ProjektID-->&nkorder=datum&nkdir=<!--TPLNKDIR-->">Datum</a></th>
        <th><a href="index.php?page=6&do=110&pid=<!--ProjektID-->&nkorder=lieferant&nkdir=<!--TPLNKDIR-->">Dienstleister</a></th>
        <th><a href="index.php?page=6&do=110&pid=<!--ProjektID-->&nkorder=nummer&nkdir=<!--TPLNKDIR-->">Nummer</a></th>
        <th><a href="index.php?page=6&do=110&pid=<!--ProjektID-->&nkorder=betrag&nkdir=<!--TPLNKDIR-->">Betrag</a></th>
        <th><a href="index.php?page=6&do=110&pid=<!--ProjektID-->&nkorder=leistung&nkdir=<!--TPLNKDIR-->">Leistung</a></th>
        <th colspan="2">&nbsp;</th>
      </tr>
      <!--NebenkostenRechnungen-->
    </table>
    </td>
  </tr>
</table>
<hr class="bottomMenuHR" />
<div class="bottomMenu"><a href="index.php?page=6&do=105&a=d&pid=<!--ProjektID-->" title="Projekt bearbeiten"
class="buttonlink iconButton editButton">Bearbeiten</a></div>
<a href="index.php?page=6&do=101&p=<!--ProjektID-->" title="Zeiten eintragen" class="buttonlink iconButton
calendarButton">Zeiten eintragen</a>
