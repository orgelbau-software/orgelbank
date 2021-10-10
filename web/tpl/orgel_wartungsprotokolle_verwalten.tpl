<h3>Wartungsprotokolle</h3>
<form method="post" action="index.php?page=2&do=31&wpid=<!--ProtokollId-->"  method="post" enctype="multipart/form-data">
<input type="hidden" name="protokollId" value="<!--ProtokollIdId-->" />
<div id="orgeldetailswartungengesamt">
	<table class="liste" style="width: 100%; border: 0px solid black;">
		<tr>
			<th>Name</th>
			<th>Dateinamen</th>
			<th>Bemerkung</th>
			<th colspan="2">Aktionen</th>
		</tr>
		<!--Protokolle-->
	</table>
</div>
<hr/>
<h3>Wartungsprotokoll Daten</h3>
<table>
	<tr>
		<td style="padding-right: 20px;">
			<table class="liste" style="width: 100%">
				<tr>
					<th>Name:</th>
					<td><input type="text" maxlength="50" name="name" value="<!--Name-->" class="int100" tabindex="4" /></td>
				</tr>
				<tr>
					<th>Datei:</th>
					<td>
							<!--Dateiname-->
							<br/>
							<input type="file" name="protokoll"/>		
					</td>
				</tr>
				<tr>
					<th>Bemerkung:</th>
					<td><textarea name="bemerkung" class="txtarea435"><!--Bemerkung--></textarea></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<hr/>
<h3>Orgeln</h3>
...
<!--HTMLStatus-->
<hr/>
<br/>
<div style="float: right;">
  <input type="submit" name="submit" tabindex="8" value="<!--SubmitValue-->" class="button iconButton saveButton" />
  <a href="index.php?page=2&do=31&action=edit" title="Abbrechen" class="buttonLink iconButton cancelButton">Abbrechen</a>
</div>
<a href="index.php?page=2&do=31" class="buttonLink iconButton createButton dispositionAdd" title="Neues Protokoll anlegen">Neues Protokoll</a>

</form>