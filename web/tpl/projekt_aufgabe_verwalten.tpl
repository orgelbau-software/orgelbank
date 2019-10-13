<table width="100%">
  <tr>
    <td>
    <h3>Aufgaben verwalten</h3>
    <a href="index.php?page=6&do=102" title="Neue Aufgabe anlegen">Neue Hauptaufgabe anlegen</a> <br />
    <br />
    <form method="post" action="index.php?page=6&do=102"><input type="hidden" name="paid" value="<!--PaID-->"
    /> <input type="hidden" name="form" value="hauptaufgabe" />
    <table class="liste txt380">
      <tr>
        <th colspan="2">Hauptaufgabendetails:</th>
      </tr>
      <tr>
        <th>Bezeichnung:</th>
        <td><input type="text" name="bezeichnung" value="<!--Bezeichnung-->" class="txt270"/></td>
      </tr>
      <tr>
        <th>Beschreibung:</th>
        <td><textarea name="beschreibung" rows="2" class="txt270"><!--Beschreibung--></textarea></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <td style="text-align: right"><input type="submit" name="submit" value="<!--Submit-->"
        class="button iconButton saveButton" /> <a href="index.php?page=6&do=102" title="Abbrechen"
          class="buttonLink smallIconButton cancelButton">&nbsp;</a></td>
    </table>
    </form>

    <h3>Unteraufgaben</h3>
    Sie m&uuml;ssen mindestens eine Unteraufgaben anlegen um Zeiten erfassen zu k&ouml;nnen.
    <form method="post" action="index.php?page=6&do=102&a=edit&paid=<!--PaID-->"> <input type="hidden"
      name="form" value="unteraufgabe" /> <input type="hidden" name="paid" value="<!--PaID-->" /> <input
      type="hidden" name="unteraufgabeid" value="<!--UPaID-->" />
    <table class="liste txt380">
      <tr>
        <th></th>
        <th><input type="text" name="unteraufgabe_bez" value="<!--UnteraufgabeBezeichnung-->" class="txt200"/></th>
        <th style="width: 140px;" colspan="1"><input type="submit" name="submit" value="<!--UnteraufgabeSubmit-->" <!--UnteraufgabeDisabled-->
        class="button iconButton saveButton"/> <a href="index.php?page=6&do=102" title="Abbrechen"
          class="button buttonLink smallIconButton cancelButton">&nbsp;</a></th>
      </tr>
      <!--UnteraufgabenListe-->
    </table>
    </form>

    <h3>Mitarbeiterliste</h3>
    Mitarbeiter die an der Hauptaufgabe samt Unteraufgaben arbeiten d&uuml;rfen. <br />
    <br />
    </form>

    <form method="post" action="index.php?page=6&do=102&a=edit&paid=<!--PaID-->"> <input type="hidden"
      name="form" value="mitarbeiter" /> <input type="hidden" name="paid" value="<!--PaID-->" />
    <table class="liste txt380">
      <tr>
        <th colspan="6">Mitarbeiter</th>
      </tr>
      <!--MitarbeiterAufgabenliste-->
      <tr>
      	<td style="text-align: left;">
        	<a href="#" onclick="javascript:switchSelectAlleMitarbeiter();return false;">Alle markieren</a>
      	</td>
        <td colspan="5" style="text-align: right;">
        	<input type="submit" name="submit" value="Berechtigungen speichern"<!--BenutzerDisabled--> class="button iconButton saveButton"/></td>
      </tr>
    </table>
    <br />
    <!--Statusmeldung--></form>
    </td>
    <td>
    <h3>Aufgabenliste</h3>
    <br />
    <br />
    <div id="iFrameAufgabenliste">
    <table class="liste">
      <!--Aufgabenliste-->
    </table>
    </div>
    </td>
  </tr>
</table>
