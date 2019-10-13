	</div>
	<div id="help">
		<form method="post" action="index.php?page=8&do=200">
			<input type="hidden" name="help_post" value="<!--PostValue-->" />
			<input type="hidden" name="help_addr" value="<!--GetValue-->" />
			<input id="help_nachricht" style="width: 79%" type="text" name="nachricht" value="<!--AdminHoverText-->" onclick="clickclear(this, '<!--AdminHoverText-->')" onblur="clickrecall(this,'<!--AdminHoverText-->')"/>
			<input style="width: 20%; font-weight: bold;" class="button iconButton mailButton" type="submit" name="submit" value="Kurzmitteilung senden" />
		</form>
  <br/>
  <!--<span style="font-size: 7pt;">Queries: <!--NumQuery--></span>-->
	</div>
</div>
</body>
</html>