<form name="orgeloption" action="index.php?page=2&do=20" method="post">
<div class="export">
<ul>
  <li><a title="Gemeindelist im reinen Druckformat" target="_blank" href="src/orgel/orgeln.php?action=druck&sid=<!--SessionID-->"><img
    src="web/images/icons/printer.png" /></a></li>
  <li><a title="Aktuelle Liste nach Excel exportieren" target="_blank"
    href="src/orgel/orgeln.php?action=export&format=xls&order=<!--Order-->&dir=<!--Dir-->&index=<!--Index-->&sid=<!--SessionID-->"><img
    src="web/images/icon_excel.png" /></a></li>
</ul>
</div>
Zeige <strong><!--OrgelAnzahlAnzeige--> von <!--OrgelAnzahlGesamt--></strong> Orgeln.
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="checkbox" class="checkbox" name="neubauten" value="1" id="chkbox1" <!--checked1-->/><label for="chkbox1">Neubauten</label> 
<input type="checkbox" class="checkbox" name="renoviert" value="2" id="chkbox2" <!--checked2-->/><label for="chkbox2">Renovierte</label> 
<input type="checkbox" class="checkbox" name="restauriert" value="3" id="chkbox3" <!--checked3-->/><label for="chkbox3">Restaurierte</label>
<input type="checkbox" class="checkbox" name="nichtzugeordnet" value="4" id="chkbox4" <!--checked4-->/><label for="chkbox4">Nicht-zugeordnete</label> 
<input type="search" name="suchstring" size="25" value="<!--Suchbegriff-->" placeholder="Suchbegriff...">
<input type="submit" class="button iconButton searchButton" name="submit" value="Anzeigen" />
<hr />
<form name="orgelliste" action="src/orgel/pdf.php?sid=<!--SessionID-->" method="post"><input type="hidden" name="orgelliste"
  value="orgelliste">
<div id="quickjump">
<ul>
  <li class="desc">Direktzugriff:</li>
  <!--Quickjump-->
</ul>
</div>
<hr />
<table class="liste size100">
  <!--Content-->
</table>
<hr class="bottomMenuHR" />
<a name="drucken"></a> <input class="button iconButton openManyButton" type="submit" name="submit"
  value="Pflegeb&ouml;gen anzeigen"> <input class="button iconButton openDeckblattButton" type="submit" name="submit"
  value="Deckbl&auml;tter anzeigen">  <input class="button iconButton resetButton" type="reset" name="reset" value="Marker l&ouml;schen">
</form>