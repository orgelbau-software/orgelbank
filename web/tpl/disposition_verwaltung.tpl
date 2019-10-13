<form action="index.php?page=4&do=61&oid=<!--OID-->" method="post"> <input type="hidden" name="oid" value="<!--OID-->"/>
<input type="hidden" name="did" value="<!--DID-->"/> <input type="hidden" name="action" value="<!--Action-->"/>

<table class="dispositionmenu">
  <tr>
    <td>Manual:</td>
    <td><select name="manual"
      <!--disabled-->
      >
      <!--Manuale-->
    </select></td>
    <td>Register:</td>
    <td><input type="text" id="register" name="register" size="30" maxlength="50" value="<!--Register-->" <!--disabled-->/></td>
    <td>Fu&szlig;:</td>
    <td><select name="fuss"
      <!--disabled-->
      >
      <!--Fuss-->
    </select></td>
    <td><input class="button iconButton <!--ButtonClass-->" type="submit" value="<!--Submit-->" <!--disabled-->></td>
  </tr>
</table>
</form>
<form action="index.php?page=4&do=63&oid=<!--OID-->" method="post">
<table>
  <tr>
    <td>
	    <table class="liste" id="dispositionsTable">
	      <!--Disposition-->
	    </table>
	    <!--HTMLStatus-->
    </td>
    <td style="width: 170px; padding-left: 30px;">
      <table class="liste registerAuswahl">
        <!--DispositionTOP1-->
      </table>
    </td>
    <td style="width:170px; padding-left: 10px;">
      <table class="liste registerAuswahl">
        <!--DispositionTOP2-->
      </table>
    </td>
    <td style="width: 170px; padding-left: 10px;">
      <table class="liste registerAuswahl">
        <!--DispositionTOP3-->
      </table>
    </td>
  </tr>
</table>
<hr class="bottomMenuHR" />
<div class="bottomMenu">
<!--
<input type="submit" class="button iconButton saveButton" name="submit" value="Reihenfolge speichern"<!--disabled-->/>
    <a href="#" class="buttonLink iconButton refreshButton" id="nummerieren">Nummerieren</a>-->
</div>

<a class="buttonLink iconButton backButton" href="index.php?page=2&do=21&oid=<!--OID-->">zurück zu den Orgeldetails</a>
</form>
