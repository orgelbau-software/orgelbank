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
				<td><input type="text" name="baujahr" maxlength="10" value="" class="int40 jsJahr"></td>
				<td class="tdLabel">Erbauer:</td>
				<td colspan="3"><input type="text" name="erbauer" maxlength="50" value="" class="txt200"></td>
			</tr>
			<tr>
				<td class="tdLabel">Status:</td>
				<td>
					<select name="status" class="txt100">
						<!--Orgelstatus-->
					</select>
				</td>
				<td class="tdLabel">Jahr:</td>
				<td><input type="text" name="renoviert" maxlength="10" value="" class="int40 jsJahr"></td>
				<td class="tdLabel">von:</td>
				<td><input type="text" name="renovierer" maxlength="50" value="" class="txt106"></td>
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
		</table>
		<br />
		<table class="liste txt450">
			<tr>
				<th colspan="6">Manuale:</th>
			</tr>
			<tr>
				<td><input type="checkbox" name="manual1"> M I</td>
				<td><input type="text" name="m1groesse" value="" class="txt45"></td>
				<td><input type="text" name="m1wd" value="" class="txt30" > mm/WS</td>
				<td><input type="checkbox" name="manual3"> M III</td>
				<td><input type="text" name="m3groesse" value="" class="txt45"></td>
				<td><input type="text" name="m3wd" value="" class="txt30"> mm/WS</td>
			</tr>
			<tr>
				<td><input type="checkbox" name="manual2"> M II</td>
				<td><input type="text" name="m2groesse" value="" class="txt45"></td>
				<td><input type="text" name="m2wd" value="" class="txt30"> mm/WS</td>
				<td><input type="checkbox" name="manual4"> M IV</td>
				<td><input type="text" name="m4groesse" value=""class="txt45"></td>
				<td><input type="text" name="m4wd" value="" class="txt30"> mm/WS</td>
			</tr>
			<tr>
				<td><input type="checkbox" name="pedal"> Pedal</td>
				<td><input type="text" name="m6groesse" value="" class="txt45"></td>
				<td><input type="text" name="m6wd" value="" class="txt30"> mm/WS</td>
				<td><input type="checkbox" name="manual5" id="manual5"> <label for="manual5">M V</label></td>
				<td><input type="text" name="m5groesse" value=""class="txt45"></td>
				<td><input type="text" name="m5wd" value="" class="txt30"> mm/WS</td>
			</tr>
		</table>
		<br/>
		<table class="liste txt450">
			<tr>
				<th colspan="4">Allgemeine Anmerkungen:</th>
			</tr>
			<tr>
				<td colspan="4">
					<textarea name="anmerkung" class="txtarea435"></textarea>
				</td>
			</tr>
		</table>
		<br/>
		<table class="liste txt450">
			<tr>
				<th colspan="4">
					Pflegedaten:
				</th>
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
				<td class="tdLabel">Register:</td>
				<td><input type="number" name="registeranzahl" id="registeranzahl" value="<!--Register-->" min="0" max="100" step="1" class="int40"/></td>
				<td class="tdLabel">Hauptstimmung:</td>
				<td>
					<select name="intervall_hauptstimmung" class="width110">
						<!--IntervallHaupstimmungSelect-->
					</select>
				</td>
			</tr>
			<tr>
				<td class="tdLabel">&nbsp;</td>
				<td>&nbsp;</td>
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
				<td class="tdLabel">Teilstimmung:</td>
				<td><input type="text" min="1" step="any" name="kostenteilstimmung" maxlength="50" class="width90" value="<!--KostenTeilstimmung-->">  &euro;</td>
			</tr>
			<tr>
				<td class="tdLabel" colspan="4">Notwendige Maßnahmen:</td>
			</tr>
			<tr>
				<td colspan="4">
					<textarea name="massnahmen" class="txtarea435"></textarea>
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
							<select name="gemeindeid">
								<option value="0">keine</option>
								<!--Gemeinden-->
							</select>
							</th>
							<th style="padding-top: 5px;">
								&nbsp;
							</th>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<h3>Disposition</h3>
		Die Disposition kann erst nach dem Anlegen der Orgel eingetragen werden! 
		<br/>
		<h3>Wartungen</h3>
		Die Wartungen können erst nach dem Anlegen der Orgel eingetragen werden! 
		<br/>
		<h3>Bild</h3>
		Dieser Orgel kann erst nach dem Anlegen ein Bild gegeben werden! 
	</td>
</tr>
</table>

<hr class="bottomMenuHR"/>
<div class="bottomMenu">
<input class="button iconButton saveButton" type="submit" name="submit" value="Speichern">
<a class="buttonLink iconButton cancelButton" href="index.php?page=2&do=24">Abbrechen</a>
</div>
</form>