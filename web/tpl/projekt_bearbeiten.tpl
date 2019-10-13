<form method="post" action="index.php?page=6&do=105">
<input type="hidden" name="pid" value="<!--ProjektID-->" id="jsProjektId"/>
<h3>Projekt <!--Titel--></h3>
<table>
  <tr>
    <td style="padding-right: 40px;">
    <table class="liste">
      <tr>
        <th>Bezeichnung:</th>
        <td><input type="text" name="bezeichnung" value="<!--Bezeichnung-->" class="txt300"/></td>
      </tr>
      <tr>
        <th>Gemeinde:</th>
        <td><select name="gemeinde"  class="txt300">
          <!--Gemeinden-->
        </select></td>
      </tr>
      <tr>
        <th>Beschreibung:</th>
        <td><textarea name="beschreibung" cols="35" rows="5" class="txt300"><!--Beschreibung--></textarea></td>
      </tr>
      <tr>
        <th>Startermin:</th>
        <td>
        <div class="inputDatum"><input type="text" class="datePicker" name="start" value="<!--Start-->"
        size="8" /></div>
        </td>
      </tr>
      <tr>
        <th>Endtermin:</th>
        <td>
        <div class="inputDatum"><input type="text" class="datePicker" name="ende" value="<!--Ende-->"
        size="8"/></div>
        </td>
      </tr>
       <tr>
        <th>Angebotspreis:</th>
        <td>
        <input type="text" name="angebotspreis" value="<!--Angebotspreis-->" class="int70 jsFormatNumber" /> EUR
        </td>
      </tr>
    </table>
    <br />
    <h3>Keine Zeitberechnungen f&uuml;r</h3>
    <span>F&uuml;r ausgew&auml;hlte Mitarbeiter, <b>keine Ber&uuml;cksichtigung</b> der erfassten Arbeitsstunden.</span>
    <table class="liste size100">
     <tr>
      <th colspan="6">Keine Zeitberechnung f&uuml;r</th>
     </tr>
      <tr>
    <!--Mitarbeiter-->
      </tr>
    </table>
    <!--Statusmeldung--></td>
    <td>
    <table class="liste" id="jsAufgabenListe">
    	<thead>
      		<tr>
	        	<th colspan="2">Projektaufgaben:</th>
	    	    <th colspan="2">Plankosten:</th>
      		</tr>
      	</thead>
      	<tbody>
      		<!--Projektaufgaben-->
      	</tbody>
    </table>
    </td>
  </tr>
</table>
<hr style="padding-bottom: 10px;"/>
<div style="float: right;">
  <input type="submit" name="submit" class="button iconButton saveButton" value="Speichern" />
  <a href="index.php?page=6&do=105&a=d&pid=<!--ProjektID-->" title="Abbrechen" class="buttonLink iconButton cancelButton">Abbrechen</a>
</div>
<a href="index.php?page=6&do=106&pid=<!--ProjektID-->" title="Projekt im Archiv ablegen" class="buttonLink iconButton archiveButton <!--Disabled-->" >Archivieren</a>
<a href="index.php?page=6&do=110&pid=<!--ProjektID-->" title="Zu der Detailansicht" class="buttonLink iconButton forwardButton <!--Disabled-->" >Detailansicht</a>
</form>