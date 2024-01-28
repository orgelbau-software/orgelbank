
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
        <td><input type="text" name="vorname" class="txt140" value="<!--Vorname-->" /></td>
      </tr>
      <tr>
        <th>Nachname:</th>
        <td><input type="text" name="nachname" class="txt140" value="<!--Nachname-->" /></td>
      </tr>
      <tr>
        <th>Benutzername:</th>
        <td><input type="text" name="benutzername" class="txt140" value="<!--Benutzername-->" /></td>
      </tr>
        <tr>
      <th>Email:</th>
        <td><input type="text" name="email" class="txt140" value="<!--Email-->" /></td>
      </tr>
      <tr>
        <th>Passwort:</th>
        <td><input type="text" name="passwort" class="txt140" value="" /></td>
      </tr>
      <tr>
        <th>Eintrittsdatum:</th>
        <td><input type="date" name="eintrittsdatum" value="<!--Eintrittsdatum-->" /></td>
      </tr>
      <tr>
        <th>Stundenlohn:</th>
        <td><input type="number" min="0" step="0.01" name="lohn" class="int70" value="<!--Lohn-->" /> EUR/Std.</td>
      </tr>
      <tr>
        <th>Verrechnungssatz:</th>
        <td><input type="number" min="0" step="0.01" name="verrechnungssatz" class="int70" value="<!--VerrechnungsSatz-->" /> EUR/Std.</td>
      </tr>
      <tr>
        <th>Urlaubstage pro Jahr:</th>
        <td><input type="number" min="0" step="0.5" name="urlaubstage" class="int70" size="4" value="<!--Urlaubstage-->" /></td>
      </tr>
      <tr>
        <th>Status:</th>
        <td><select name="aktiviert" class="txt140">
          <option value="0"<!--Deaktiviert-->>Deaktiviert</option>
          <option value="1"<!--Aktiviert-->>Aktiviert</option>
        </select></td>
      </tr>
      <tr>
        <th>Rolle:</th>
        <td><select name="benutzerlevel" class="txt100">
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
        <td style="padding-bottom: 15px;"><input type="number" min="0" max="12" step="0.25" class="int60" name="stunden_0" value="<!--Stunden0-->" /></td>

        <td><input type="number" name="stunden_1" min="0" max="12" step="0.25" class="int60" value="<!--Stunden1-->" /></td>

        <td><input type="number" name="stunden_2" min="0" max="12" step="0.25" class="int60" value="<!--Stunden2-->" /></td>

        <td><input type="number" name="stunden_3" min="0" max="12" step="0.25" class="int60" value="<!--Stunden3-->" /></td>
      </tr>
      <tr>
        <th class="alignCenter">Freitag</th>
        <th class="alignCenter">Samstag</th>
        <th class="alignCenter">Sonntag</th>
        <th class="alignCenter">Summe</th>
      </tr>
      <tr>
        <td style="padding-bottom: 15px;"><input type="number" name="stunden_4" min="0" max="12" step="0.25" class="int60" value="<!--Stunden4-->" /></td>

        <td><input type="number" name="stunden_5" min="0" max="12" step="0.25" class="int60" value="<!--Stunden5-->" /></td>

        <td><input type="number" name="stunden_6" min="0" max="12" step="0.25" class="int60" value="<!--Stunden6-->" /></td>

        <td><input type="number" class="disabled int60" name="summe" value="<!--Summe-->" /></td>
      </tr>
    </table>
    
    <h3>(Rest-) Urlaub und &Uuml;berstunden</h3>
    <table class="liste txt310">
   		<tr>
        	<th>&Uuml;berstunden (Aktuell):</th>
        	<td><!--UeberstundenAktuell--></td>
       	</tr>
       	<!--<tr>
        	<th>&Uuml;berstunden (Vorjahr):</th>
        	<td><!--UeberstundenVorjahr--></td>
       	</tr>
       	<tr>
        	<th>&Uuml;berstunden (Gesamt):</th>
        	<td><!--UeberstundenGesamt--></td>
       	</tr>-->
       	<tr>
        	<th>Aktueller Urlaub:</th>
        	<td><!--AktuellerUrlaub--></td>
        </tr>
        <tr>
        	<th>Resturlaub:</th>
        	<td><!--Resturlaub--></td>
      	</tr>
    </table>
    <a target="_blank" href="src/projekt/projekt.php?action=stundenzettel&bid=<!--BenID-->">Stundenzettel anzeigen</a>
    
    <h3>&Uuml;berstunden</h3>
    <table class="liste txt310">
    	<tr>
    		<th>KW</th>
    		<!--<th>Datum</th>-->
    		<th>Soll</th>
    		<th>Ist</th>
    		<th>Soll-Ist</th>
    		<th>DifVW</th>
    		<th>Total</th>
    	</tr>
   		<!--UeberstundenData-->
    </table>
    
    <!--Status--><br/>
    <!--Status2-->
    
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
    </td>
  </tr>
</table>
<hr class="bottomMenuHR" />
<div class="bottomMenu"><input type="submit" name="submit" class="button iconButton contactAdd" value="<!--Submit-->"/>
<a href="index.php?page=6&do=103" title="Abbrechen" class="buttonLink iconButton cancelButton">Abbrechen</a></div>
<br/>
</form>