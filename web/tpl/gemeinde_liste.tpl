	<form action="index.php?page=1&do=1&index=all" method="post">
	<div class="export">
		<ul>
			<li><a title="Gemeindelist im reinen Druckformat" target="_blank" href="src/gemeinde/gemeinden.php?action=druck&sid=<!--SessionID-->"><img src="web/images/icons/printer.png" /></a></li>
			<li><a title="Aktuelle Liste nach Excel exportieren" target="_blank" href="src/gemeinde/gemeinden.php?action=export&format=xls&index=all&order=<!--Order-->&dir=<!--Dir-->&suchbegriff=<!--Suchbegriff-->&sid=<!--SessionID-->"><img src="web/images/icon_excel.png" /></a></li>
		</ul>
	</div>
	Es werden <strong><!--AnzahlGemeindenAnzeige--> von <!--AnzahlGemeindenGesamt--></strong> Gemeinden in der Liste angezeigt.
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="text" name="suchstring" size="20" value="<!--Suchbegriff-->" onclick="clickclear(this, 'Suchbegriff...')" onblur="clickrecall(this,'Suchbegriff...')">
	<input class="button iconButton searchButton" type="submit" name="submit" value="Suchen">
	<input class="button iconButton resetButton" type="submit" name="submit" value="Zur&uuml;cksetzen">
	</form>
	<hr/>
	<div id="quickjump">
		<ul>
			<li class="desc">Direktzugriff:</li>
			<!--Quickjump-->
		</ul>
	</div>
	<hr/>
	<table class="liste size100">
		<!--GemeindeListe-->
	</table>