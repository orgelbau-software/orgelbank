<form action="index.php?page=2&do=23&oid=<!--OID-->" method="post" enctype="multipart/form-data">
<table class="liste">
	<tr>
		<th colspan="<!--AnzahlOrgelBilder-->">Bilder&uuml;bersicht</th>
	</tr>
	<tr>
	 <!--OrgelBilder-->
	</tr>
	<tr>
		<td colspan="<!--AnzahlOrgelBilder-->">
			<input type="file" name="probe"/>
			<input type="hidden" name="send"  />
			<input type="hidden" name="o_id" value="<!--OID-->">
			<input class="button iconButton imageButton" type="submit" value="Bild speichern" />
		</td>
	</tr>
</table>
</form>
<!--Status-->
<hr class="bottomMenuHR" />
<div class="bottomMenu"></div>
<a class="buttonLink iconButton backButton" href="index.php?page=2&do=21&oid=<!--OID-->">zurück zu den Orgeldetails</a>
