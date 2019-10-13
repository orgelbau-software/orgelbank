<tr id="<!--JSRechnungsID-->">
	<td class="<!--CSS-->"><!--RechnungsNr--></td>
	<td class="<!--CSS-->"><!--Typ--></td>
	<td class="<!--CSS-->"><!--Datum--></td>
	<td class="<!--CSS-->"><!--Gemeinde--></td>
	<td class="<!--CSS-->" style="text-align: right; width: 100px;"><!--Netto--> EUR</td>
	<td class="<!--CSS-->" style="text-align: right; width: 100px;"><!--Brutto--> EUR</td>
	<td class="<!--CSS-->" style="width: 20px;">
		<a href="#" onclick="return onClickShowContext('<!--JSRechnungsID-->', '<!--RID-->', '<!--TypID-->', '<!--CSS-->');" title="Rechnungs Details" class="jsRechnungToolTip">
			<img  id="jsIcon_<!--JSRechnungsID-->" style="border: 0px solid black;" src="web/images/icons/<!-- IconImageName -->" title="Bezahlstatus"/>
		</a>
	</td>
	<td class="<!--CSS-->">
		<a href="index.php?page=5&do=90&typid=<!--TypID-->&id=<!--RID-->" title="Details anzeigen">
			<img style="border: 0px solid black;" src="web/images/icons/document_a4_locked.png" /></a>
		<a href="index.php?page=5&do=91&typid=<!--TypID-->&id=<!--RID-->" title="Rechnung l&ouml;schen">
			<img style="border: 0px solid black;" src="web/images/icons/document_a4_remove.png" /></a>
			<input type="hidden" id="jsBruttoBetrag_<!--JSRechnungsID-->" value="<!--Brutto-->"/>	
			<input type="hidden" id="jsEingangsAnmerkung_<!--JSRechnungsID-->" value="<!--EingangsAnmerkung-->"/>	
			<input type="hidden" id="jsEingangsBetrag_<!--JSRechnungsID-->" value="<!--EingangsBetrag-->"/>	
			<input type="hidden" id="jsEingangsDatum_<!--JSRechnungsID-->" value="<!--EingangsDatum-->"/>	
	</td>
</tr>
