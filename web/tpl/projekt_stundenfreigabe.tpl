<h3>Stundenfreigabe - <!--Benutzername--> - KW <!--KW-->/<!--Jahr--> - <!--DatumVon--> - <!--DatumBis--></h3>
<!--Statusmeldung-->
<!--HTMLStatus-->
<form method="post" action="<!--FormTarget-->">
<table class="liste size100">
	<!--Datensaetze-->
<tr>
    <td style="width: 200px;">&nbsp;</td>
    <td style="font-weight: bold;">Summe Projekt (IST):</td>
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
  </table>
  
  	<div style="float: right; margin-top: 10px;">
  		<a class="buttonlink iconButton cancelButton" href="index.php?page=6&do=114&uid=<!--BenutzerID-->&date=<!--WocheTS-->&status=1" title="">Wieder freischalten</a>
  		<a class="buttonlink iconButton editButton" href="index.php?page=6&do=114&uid=<!--BenutzerID-->&date=<!--WocheTS-->&status=9" title="Bearbeiten">Zur Bearbeitung</a>
  		<a class="buttonlink iconButton saveButton" href="index.php?page=6&do=114&uid=<!--BenutzerID-->&date=<!--WocheTS-->&status=3" title="Bearbeiten">Freigabe & Buchung</a>
	</div>
</form>