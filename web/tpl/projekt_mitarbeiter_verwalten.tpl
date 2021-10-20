
<table width="100%">
  <tr>
    <td>
    <h3>Mitarbeiter verwalten</h3>
    <a href="index.php?page=6&do=103" title="Neuen Mitarbeiter anlegen">Neuen Mitarbeiter anlegen</a> <br />
    <br />
    <form method="post" action="index.php?page=6&do=103"><input type="hidden" name="ben_id" value="<!--BenID-->" />
    <table class="liste txt310">
      <tr>
        <th>Vorname:</th>
        <td><input type="text" name="vorname" size="20" value="<!--Vorname-->" /></td>
      </tr>
      <tr>
        <th>Nachname:</th>
        <td><input type="text" name="nachname" size="20" value="<!--Nachname-->" /></td>
      </tr>
      <tr>
        <th>Benutzername:</th>
        <td><input type="text" name="benutzername" size="20" value="<!--Benutzername-->" /></td>
      </tr>
      <tr>
        <th>Passwort:</th>
        <td><input type="text" name="passwort" size="20" value="" /></td>
      </tr>
      <tr>
        <th>Eintrittsdatum:</th>
        <td>
        <div class="inputDatum"><input class="datePicker" type="text" name="eintrittsdatum" value="<!--Eintrittsdatum-->"
        /></div>
        </td>
      </tr>
      <tr>
        <th>Stundenlohn:</th>
        <td><input type="text" name="lohn" class="int50 jsFormatNumber" value="<!--Lohn-->" /> EUR/Std.</td>
      </tr>
      <tr>
        <th>Verrechnungssatz:</th>
        <td><input type="text" name="verrechnungssatz" class="int50 jsFormatNumber" value="<!--VerrechnungsSatz-->" /> EUR/Std.</td>
      </tr>
      <tr>
        <th>Urlaubstage pro Jahr:</th>
        <td><input type="text" name="urlaubstage" size="4" value="<!--Urlaubstage-->" /></td>
      </tr>
      <tr>
        <th>Status:</th>
        <td><select name="aktiviert">
          <option value="0"<!--Deaktiviert-->>Deaktiviert</option>
          <option value="1"<!--Aktiviert-->>Aktiviert</option>
        </select></td>
      </tr>
      <tr>
        <th>Rolle:</th>
        <td><select name="benutzerlevel">
          <option value="0"<!--Mitarbeiter-->>Mitarbeiter</option>
          <option value="5"<!--Monteur-->>Monteur</option>
          <option value="10"<!--Admin-->>Administrator</option>
        </select></td>
      </tr>
      <tr>
        <th>Zeiterfassung:</th>
        <td><input type="checkbox" name="zeiterfassung" <!--ZeiterfassungCheck--> /></td>
      </tr>
    </table>
    <h3>Wochenarbeitsstunden</h3>
    <table class="liste txt310">
      <tr>
        <th class="alignCenter">Montag</th>
        <th class="alignCenter">Dienstag</th>
        <th class="alignCenter">Mittwoch</th>
        <th class="alignCenter">Donnerstag</th>
      </tr>
      <tr>
        <td style="padding-bottom: 15px;"><input type="text" name="stunden_0" value="<!--Stunden0-->"
        size="2"/></td>

        <td><input type="text" name="stunden_1" value="<!--Stunden1-->" size="2" /></td>

        <td><input type="text" name="stunden_2" value="<!--Stunden2-->"size="2" /></td>

        <td><input type="text" name="stunden_3" value="<!--Stunden3-->" size="2" /></td>
      </tr>
      <tr>
        <th class="alignCenter">Freitag</th>
        <th class="alignCenter">Samstag</th>
        <th class="alignCenter">Sonntag</th>
        <th class="alignCenter">Summe</th>
      </tr>
      <tr>
        <td style="padding-bottom: 15px;"><input type="text" name="stunden_4" value="<!--Stunden4-->"
        size="2"/></td>

        <td><input type="text" name="stunden_5" value="<!--Stunden5-->" size="2"/></td>

        <td><input type="text" name="stunden_6" value="<!--Stunden6-->" size="2"/></td>

        <td><input type="text" class="disabled" name="summe" value="<!--Summe-->" size="2"/></td>
      </tr>
    </table>
    
    <h3>Resturlaub und &Uuml;berstunden</h3>
    <table class="liste txt310">
   		<tr>
        	<th>&Uuml;berstunden:</th>
        	<td><!--Ueberstunden--></td>
        	<th>Resturlaub:</th>
        	<td><!--Resturlaub--></td>
      	</tr>
    </table>
    <a target="_blank" href="src/projekt/projekt.php?action=stundenzettel&bid=<!--BenID-->">Stundenzettel anzeigen</a>
    
    </td>
    <td>
    <h3>Mitarbeiterliste</h3>
    <br />
    <br />
    <table class="liste" id="mitarbeiterTable">
    	<thead>
            <tr>
              <th>Benutzernamen</th>
              <th>Vorname</th>
              <th>Nachname</th>
              <th>Benutzerlevel</th>
              <th>Status</th>
              <th colspan="2">Aktionen</th>
            </tr>
      	</thead>
     	<tbody>
      <!--Aufgabenliste-->
      	</tbody>
    </table>
    
    <h3>Aufgaben pro Mitarbeiter</h3>
    <table class="liste" id="aufgabenTable">
    	<tr>
    		<th></th>
    		<th>Bezeichnung</th>
    	</tr>
    	<!--Aufgaben-->
    </table>
    <!--Status--><br/>
    <!--Status2-->
    </td>
  </tr>
</table>
<hr class="bottomMenuHR" />
<div class="bottomMenu"><input type="submit" name="submit" class="button iconButton contactAdd" value="<!--Submit-->"/>
<a href="index.php?page=6&do=103" title="Abbrechen" class="buttonLink iconButton cancelButton">Abbrechen</a></div>
<br/>
</form>