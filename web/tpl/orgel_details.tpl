<form action="index.php?page=2&do=22" method="post">
<input type="hidden" name="o_id" value="<!--OID-->"/>
<table>
	<tr>
		<td style="padding-right: 20px;">
		<h3>Orgel Angaben</h3>
		<table class="liste txt450">
			<tr>
				<th colspan="6">Sachliche Angaben</th>
			</tr>
			<tr>
				<td class="tdLabel">Baujahr:</td>
				<td><input type="number" name="baujahr" step="1" min="0" max="2100" value="<!--Baujahr-->" class="int60 jsJahr"></td>
				<td class="tdLabel">Erbauer:</td>
				<td colspan="3"><input type="text" name="erbauer" maxlength="50" value="<!--Erbauer-->" class="txt200"></td>
			</tr>
			<tr>
				<td class="tdLabel">Status:</th>
				<td>
					<select name="status" class="txt100">
						<!--Orgelstatus-->
					</select>
				</td>
				<td class="tdLabel">Jahr:</th>
				<td><input type="number" name="renoviert" step="1" min="0" max="2100" value="<!--Renoviert-->" class="int60 jsJahr"></td>
				<td class="tdLabel">von:</th>
				<td><input type="text" name="renovierer" maxlength="50" value="<!--Renovierer-->" class="txt106"></td>
			</tr>
		</table>
		<br/>
		<table class="liste txt450">
				<tr>
					<th colspan="4">Technische Angaben</th>
				</tr>
			<tr>
				<td class="tdLabel">Windlade:</td>
				<td>
					<select name="windlade" class="txt110">
						<!--Windlade-->
					</select>
				</td>
				<td class="tdLabel">Spieltraktur:</td>
				<td>
					<select name="spieltraktur" class="txt110">
						<!--Spieltraktur-->
					</select>
				</td>
			</tr>
			<tr>
				<td class="tdLabel">Koppeln:</td>
				<td>
					<select name="koppel" class="txt110">
						<!--Koppel-->
					</select>
				</td>
				<td class="tdLabel">Registertraktur:</td>
				<td>
					<select name="registertraktur" class="txt110">
						<!--Registertraktur-->
					</select>
				</td>
			</tr>
			<tr>
				<td class="tdLabel">Manuale:</td>
				<td>
					<!--AnzahlManualeUndRegister-->
				</td>
				<td class="tdLabel">&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
		</table>
		<br />
		<table class="liste txt450">
			<tr>
				<th colspan="6">Manuale:</th>
			</tr>
			<tr>
				<td><input type="checkbox" name="manual1" id="manual1" <!--m1-->> <label for="manual1">M I</label></td>
				<td><input type="text" name="m1groesse" value="<!--m1groesse-->" class="txt45"></td>
				<td><input type="text" name="m1wd" value="<!--m1wd-->" class="txt30" > mm/WS</td>
				<td><input type="checkbox" name="manual3" id="manual3" <!--m3-->> <label for="manual3">M III</label></td>
				<td><input type="text" name="m3groesse" value="<!--m3groesse-->" class="txt45"></td>
				<td><input type="text" name="m3wd" value="<!--m3wd-->" class="txt30"> mm/WS</td>
			</tr>
			<tr>
				<td><input type="checkbox" name="manual2" id="manual2" <!--m2-->> <label for="manual2">M II</label></td>
				<td><input type="text" name="m2groesse" value="<!--m2groesse-->" class="txt45"></td>
				<td><input type="text" name="m2wd" value="<!--m2wd-->" class="txt30"> mm/WS</td>
				<td><input type="checkbox" name="manual4" id="manual4" <!--m4-->> <label for="manual4">M IV</label></td>
				<td><input type="text" name="m4groesse" value="<!--m4groesse-->"class="txt45"></td>
				<td><input type="text" name="m4wd" value="<!--m4wd-->" class="txt30"> mm/WS</td>
			</tr>
			<tr>
				<td><input type="checkbox" name="pedal" id="pedal" <!--m6-->> <label for="pedal">Pedal</label></td>
				<td><input type="text" name="m6groesse" value="<!--m6groesse-->" class="txt45"></td>
				<td><input type="text" name="m6wd" value="<!--m6wd-->" class="txt30"> mm/WS</td>
				<td><input type="checkbox" name="manual5" id="manual5" <!--m5-->> <label for="manual5">M V</label></td>
				<td><input type="text" name="m5groesse" value="<!--m5groesse-->"class="txt45"></td>
				<td><input type="text" name="m5wd" value="<!--m5wd-->" class="txt30"> mm/WS</td>
			</tr>
		</table>
		<br/>
		<table class="liste txt450">
			<tr>
				<th colspan="4">Allgemeine Anmerkungen:</th>
			</tr>
			<tr>
				<td colspan="4">
					<textarea name="anmerkung" class="txtarea435"><!--Anmerkung--></textarea>
				</td>
			</tr>
		</table>
		<br/>
		<table class="liste txt450">
			<tr>
				<th colspan="4">
					Pflegedaten:
				</th>
			</tr>
			<tr>
				<td class="tdLabel">Pflegevertrag:</td>
				<td>
					<select name="pflegevertrag" class="">
						<option value="1" <!--SelectedPflege1-->>Ja</option>
						<option value="0" <!--SelectedPflege0-->>Nein</option>
						<option value="2" <!--SelectedPflege2-->>Nicht Mehr</option>
					</select>
				</td>
				<td class="tdLabel">Zyklus:</td>
				<td>
					<select name="zyklus" class="width110">
						<!--ZyklusSelect-->
					</select>
				</td>
			</tr>
			<tr>
				<td class="tdLabel">Kircheschlüssel:</td>
				<td>
					<input type="text" name="kirchenschluessel" list="kirchenschluessel" class="width110" value="<!--Kirchenschluessel-->">
					<datalist id="kirchenschluessel">
      					<option value="Nein">
      					<option value="Ja">
    				</datalist>
				</td>
				<td class="tdLabel">Orgamat:</td>
				<td>
					<input type="text" name="orgamat" list="orgamat" class="width110" value="<!--Orgamat-->">
    				<datalist id="orgamat">
      					<option value="Nein">
      					<option value="Ja">
    				</datalist>
				</td>
			</tr>
			<tr>
				<td class="tdLabel"><label for="registeranzahl">Manuale:</label></td>
				<td><!--AnzahlManuale--></td>
				<td class="tdLabel">Hauptstimmung:</td>
				<td>
					<select name="intervall_hauptstimmung" class="width110">
						<!--IntervallHaupstimmungSelect-->
					</select>
				</td>
			</tr>
			<tr>
				<td class="tdLabel">Register:</td>
				<td><input type="number" name="registeranzahl" id="registeranzahl" value="<!--Register-->" min="0" max="100" step="1" class="int50"/></td>
				<td class="tdLabel">Stimmung nach:</td>
				<td><input type="text" name="stimmung" maxlength="50" class="width110" value="<!--StimmungNach-->"></td>
			</tr>
			<tr>
				<td class="tdLabel">&nbsp;</td>
				<td>&nbsp;</td>
				<td class="tdLabel">Stimmton:</td>
				<td><input type="number" name="stimmton" maxlength="50" class="width110" value="<!--Stimmton-->"  min="0" max="1000" step="0.1"></td>
			</tr>
            <tr>
				<td class="tdLabel">Hauptstimmung:</td>
				<td><input type="text" min="1" step="any"  name="kostenhauptstimmung" maxlength="50" class="width80" value="<!--KostenHauptstimmung-->"> &euro;</td>
				<td class="tdLabel">Nebenstimmung:</td>
				<td><input type="text" min="1" step="any" name="kostenteilstimmung" maxlength="50" class="width90" value="<!--KostenTeilstimmung-->">  &euro;</td>
			</tr>
			<tr>
				<td class="tdLabel" colspan="4">Notwendige Maßnahmen:</td>
			</tr>
			<tr>
				<td colspan="4">
					<textarea name="massnahmen" maxlength="500" class="txtarea435"><!--NotwendigeMassnahmen--></textarea>
				</td>
			</tr>
		</table>
	</td>
	<td>
		<h3>Gemeinde</h3>
		<table>
			<tr>
				<td>
					<table class="liste txt430">
						<tr>
							<th>
							<select name="gemeindeid" style="width: 100%;">
								<option value="0">keine</option>
								<!--Gemeinden-->
							</select>
							</th>
							<th style="padding-top: 5px; text-align: right; width: 130px;">
								<a class="buttonLink iconButton forwardButton" href="index.php?page=1&do=2&gid=<!--GID-->" title="Zur Gemeinde">Zur Gemeinde</a>
							</th>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<h3>Disposition - <!--AnzahlManualeUndRegister--></h3>
		<div id="orgeldetailsdisposition" class="txt430">
			<table class="liste" style="width:100%; border: 0px solid black;">
				<!--DispositionContent-->
			</table>
		</div>
		<span class="<!--CSSSpanHide-->">
		<a href="index.php?page=4&do=61&oid=<!--OID-->">Disposition bearbeiten</a>
		</span>
		<br/>
		<h3>Wartungsprotokoll</h3>
		<select name="wartungsprotokollId">
			<option value="0">Kein spezifisches</option>
		<!--Wartungsprotokolle--></select>
		<br/>
		<h3>Wartungen</h3>
		<div id="orgeldetailswartungen" class="txt430">
			<table class="liste" style="width:100%; border: 0px solid black;">
				<tr>
					<th>Datum</th>
					<th>Mitarbeiter</th>
					<th>Temp.</th>
					<th>Luftfeuchte</th>
					<th>Stimmton</th>
					<th>Stimmung</th>
				</tr>
				<!--Wartungen-->
			</table>
		</div>
		<a href="index.php?page=2&do=28&oid=<!--OID-->" title="Wartung erfassen, Wartungdetails ansehen">Wartungsdetails</a>
		<br/>
		<h3>Bilder</h3>
		<table class="liste txt430">
			<tr>
				<th colspan="<!--AnzahlOrgelBilder-->">Bilder&uuml;bersicht</th>
			</tr>
			<tr>
			 <!--OrgelBilder-->
			</tr>
		</table>
		<a href="index.php?page=2&do=23&action=show&oid=<!--OID-->" title="Bilder der Orgel verwalten">Bilder verwalten</a>
		
		<!--Status-->
	</td>
</tr>
</table>
<hr class="bottomMenuHR"/>
<div class="bottomMenu">
<input class="button iconButton saveButton" type="submit" name="submit" value="Speichern"/>
</form>
<a class="buttonLink iconButton cancelButton" href="index.php?page=2&do=21&oid=<!--OID-->">Abbrechen</a>
</div>
<a class="buttonLink iconButton printButton" target="_blank" href="src/orgel/pdf.php?oid=<!--OID-->&action=wartungsbogen">Wartungsbogen drucken</a>
<a class="buttonLink iconButton openDeckblattButton" target="_blank" href="src/orgel/pdf.php?oid=<!--OID-->&action=deckblatt">Deckblatt drucken</a>
<a class="buttonLink iconButton openAuftragsbogenButton" target="_blank" href="src/orgel/pdf.php?oid=<!--OID-->&action=auftragsbogen">Auftragsbogen drucken</a>