<table>
	<tr>
		<td>
			<table class="liste" style="width:285px;">
				<tr>
					<th>Gemeinde:</th>
				</tr>
				<tr>
					<td>
					<select <!--disableForm--> name="gemeindeid" id="gemeindeid" onKeyUp="doAdresse()" onchange="doAdresse()">
						<option value="0"></option>
						<!--Gemeinden-->
					</select>
					</td>
				</tr>
			</table>
		</td>
		<td>
			<table class="liste" style="width:307px">
				<tr>
					<th>Anschrift:</th>
				</tr>
				<tr>
					<td id="kundennr"><!--KundenNr--></td>
				</tr>
				<tr>
					<td id="anschrift"><!--Anschrift--></td>
				</tr>
				<tr>
					<td id="gemeinde"><!--Gemeinde--></td>
				</tr>
				<tr>
					<td id="strasse"><!--Strasse--></td>
				</tr>
				<tr>
					<td id="plzort"><!--PLZ--> <!--Ort--></td>
				</tr>
				<tr>
					<td id="land"><!--Land--></td>
				</tr>
			</table>
		</td>
	</tr>
</table>