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
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
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
					<select name="pflegevertrag">
						<option value="1">Ja</option>
						<option value="0">Nein</option>
					</select>
				</td>
				<td class="tdLabel">Zyklus:</td>
				<td>
					<select name="zyklus">
						<!--ZyklusSelect-->
					</select>
				</td>
			</tr>
			<tr>
				<td class="tdLabel">Register:</td>
				<td>0</td>
				<td class="tdLabel">Stimmung nach:</td>
				<td><input type="text" name="stimmung" size="16" maxlength="50" value=""></td>
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