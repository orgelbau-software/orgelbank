<table>
	<tr>
		<td class="txt500">
			<h3>Ansprechpartnerliste</h3>
			<form action="index.php?page=3&do=40" method="post">
				<input type="hidden" name="formid" value="suche" />
				<input type="search" name="suchbegriff" size="25" placeholder="Suchbegriff..." value="<!--Suchbegriff-->">
				<input class="button iconButton searchButton" type="submit" value="Suchen">
			</form>
			<hr />
			<div id="quickjump">
				<!--Quickjump-->
			</div>
			<hr />
			<table class="liste">
				<!--Ansprechpartnerliste-->
			</table>
		</td>
		<td style="padding: 0 20px 0 20px;">
			<h3>Ansprechpartnerdetails</h3>
			<form action="index.php?page=3&do=41" method="post">
			<input type="hidden" name="aid" value="<!--AID-->">
			<input type="hidden" name="gid" value="<!--GID-->">
			<table class="liste">
				<tr>
					<th>Funktion:</th>
					<td><input type="text" name="funktion" maxlength="50" value="<!--Funktion-->" class="txt240"></td>
				</tr>
				<tr>
					<th>Anrede:</th>
					<td>
						<input type="text" name="anrede" class="txt170" list="anrededatalist" value="<!--Anrede-->"/>
						<datalist id="anrededatalist">
  							<!--AnredeDatalist-->
						</datalist>
					</td>
				</tr>
				<tr>
					<th>Titel:</th>
					<td>
						<input type="text" name="titel" class="txt170" list="titeldatalist" value="<!--Titel-->"/>
						<datalist id="titeldatalist">
  							<!--TitelDatalist-->
						</datalist>
					</td>
				</tr>
				<tr>
					<th>Vorname:</th>
					<td><input type="text" name="vorname" maxlength="50" value="<!--Vorname-->" class="txt240"></td>
				</tr>
				<tr>
					<th>Name:</th>
					<td><input type="text" name="name" maxlength="50" value="<!--Nachname-->" class="txt195">
					*Pflicht</td>
				</tr>
				<tr>
					<th>Firma:</th>
					<td><input type="text" name="firma" maxlength="50" value="<!--Firma-->" class="txt240"></td>
				</tr>
				<tr>
					<th>Straße:</th>
					<td>
						<input type="text" name="strasse" maxlength="50" value="<!--Strasse-->" class="txt195">
						<input type="text" name="hausnummer" maxlength="5" value="<!--Hsnr-->" class="int40">
					</td>
				</tr>
				<tr>
					<th>PLZ/Ort:</th>
					<td>
						<input type="text" name="plz" maxlength="8" value="<!--PLZ-->" class="int50">
						<input type="text" name="ort" maxlength="50" value="<!--Ort-->" class="txt186">
					</td>
				</tr>
					<tr>
					<th>Land:</th>
					<td>
						<select name="land" class="txt240">
							<!-- Laender -->
						</select>
					</td>
				</tr>
				<tr>
					<th>Telefon:</th>
					<td><input type="text" name="telefon" maxlength="50" value="<!--Telefon-->" class="txt170"></td>
				</tr>
				<tr>
					<th>Fax:</th>
					<td><input type="text" name="fax" maxlength="50" value="<!--Fax-->" class="txt170"></td>
				</tr>
				<tr>
					<th>Mobil:</th>
					<td><input type="text" name="mobil" maxlength="50" value="<!--Mobil-->" class="txt170"></td>
				</tr>
				<tr>
					<th>Andere:</th>
					<td><input type="text" name="andere" maxlength="50" value="<!--Andere-->" class="txt170"></td>
				</tr>
				<tr>
					<th>EMail:</th>
					<td>
						<input type="email" name="email" maxlength="50" value="<!--EMail-->" class="txt215">
						<a href="mailto:<!--EMail-->" title="Neue Email an <!--EMail-->">
							<img src="web/images/icons/mail_light_new_1.png" alt="Neue Email"/>
						</a>
					</td>
				</tr>
				<tr>
					<th>Webseite:</th>
					<td>
						<input type="url" name="webseite" maxlength="50" value="<!--AnsprechpartnerWebseite-->" class="txt215">
						<a href="<!--AnsprechpartnerWebseite-->" target="_blank" title="Gehe zu <!--AnsprechpartnerWebseite-->">
							<img src="web/images/icons/application_osx_right.png" alt="Gehe zu Webseite"/>
						</a>
					</td>
				</tr>
				<tr>
					<th>Bemerkung:</th>
					<td><textarea class="textarea txtareaBemerkungAnsprechpartner" name="bemerkung"><!--Bemerkung--></textarea></td>
				</tr>
				<tr>
				  	<th>&nbsp;</th>
				  	<td style="text-align: right;">
				  		<input type="submit" class="button iconButton contactAdd txt120" name="submit" value="<!--ButtonTitle-->"/>
				  		<div style="float: left; padding-top:3px;">
				  		<a class="buttonlink iconButton contactRemove " href="index.php?page=3&do=42&aid=<!--AID-->" title="Anspechpartner l&ouml;schen">L&ouml;schen</a>
				  		</div>
				  	</td>
			  	</tr>
			</table>
			
			</form>
		
			<!--Subtemplate-->
		</td>
	</tr>
</table>
