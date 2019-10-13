var req;
var reqAbschlag;
var abschlagCount;

function abschlagsRechnungFreischalten(frm) {
	var zaehler = 0;
	var abetrag = 0;
	var gbetrag = 0;

	document.getElementById("gnetto").value = 0;
	document.getElementById("anetto").value = 0;
	document.getElementById("rnetto").value = 0;

	with (frm) {
		for ( var i = 0; i < elements.length; i++) {
			if ((elements[i].type == 'checkbox')
					&& (elements[i].checked == true)) {
				abetrag = abetrag
						+ (document.getElementById("abetrag_"
								+ elements[i].name).value * 1);
				gbetrag = document
						.getElementById("gbetrag_" + elements[i].name).value * 1;
				document.getElementById("gnetto").value = gbetrag.toFixed(2);
				document.getElementById("anetto").value = abetrag.toFixed(2);
				document.getElementById("rnetto").value = (gbetrag - abetrag)
						.toFixed(2);
				zaehler++;
			}
		}
	}
	if (zaehler >= 1) {
		document.getElementById("drucken").disabled = false
	} else {
		document.getElementById("drucken").disabled = true;
	}
	berechneAbschlag2();
}

function abschlagstext(text, prozent) {
	var titel = "";
	titel = document.getElementById("gemeindebezeichnung").value;

	document.getElementById("bemerkung1").value = text;
	document.getElementById("satz").value = prozent;
}

function berechneAbschlag() {
	var gn = document.getElementById("gnetto").value;
	var gs = document.getElementById("gsteuer").value;
	var gb = document.getElementById("gsumme").value;

	var an = document.getElementById("anetto").value;
	var as = document.getElementById("asteuer").value;
	var ab = document.getElementById("asumme").value;

	var satz = document.getElementById("satz").value;

	gn = gn.replace((/,/g), ".");

	if (satz == null || satz == "" || satz == 0) {
		document.getElementById("asteuer").value = round(an * 0.19);
		document.getElementById("asumme").value = round(an * 1.19);
	} else {
		document.getElementById("anetto").value = round(gn * (satz / 100));
		document.getElementById("asteuer").value = round((gn * (satz / 100)) * 0.19);
		document.getElementById("asumme").value = round(gn * (satz / 100)
				* 1.19);
	}

	document.getElementById("gsteuer").value = round(gn * 0.19);
	document.getElementById("gsumme").value = round(gn * 1.19);
}

function berechneAbschlag2() {
	var gn = document.getElementById("gnetto").value;
	var gs = document.getElementById("gsteuer").value;
	var gb = document.getElementById("gsumme").value;

	var an = document.getElementById("rnetto").value;
	var as = document.getElementById("rsteuer").value;
	var ab = document.getElementById("rsumme").value;

	gn = gn.replace((/,/g), ".");
	an = an.replace((/,/g), ".");

	document.getElementById("gsteuer").value = round(gn * 0.19);
	document.getElementById("gsumme").value = round(gn * 1.19);

	document.getElementById("rsteuer").value = round(an * 0.19);
	document.getElementById("rsumme").value = round(an * 1.19);
}

function round(x) {
	var a = Math.pow(10, 2);
	return (Math.round(x * a) / a).toFixed(2);
}

function doAdresse() {
	var gid = document.getElementById("gemeindeid");
	
	if(gid.value <= 0) {
		return;
	}
	
	
	
	jQuery.ajax({
		url : './src/rechnung/rechnungen.php',
		method : "GET",
		data : "action=ajax&target=gemeindeanschrift&gid=" + gid.value,
		success : function(x) {
			if(x != null) {
				jQuery("#anschrift").html(x.anschrift);
				jQuery("#gemeinde").html(x.kirche);
				jQuery("#strasse").html(x.strasse + ' ' + x.hausnummer);
				jQuery("#plzort").html(x.plz + ' ' + x.ort);
				jQuery("#land").html(x.land);
				jQuery("#gemeinde").html(x.titel);
				jQuery("#letzterpflegebtrag").attr('value', x.pflegekosten);
				jQuery("#letztefahrtkosten").attr('value', x.fahrtkosten);
				jQuery("#letztenetto").attr('value', x.nettobetrag);
				jQuery("#letztebrutto").attr('value', x.bruttobetrag);
				jQuery("#letztemwst").attr('value', x.mwst);
				jQuery("#letztemwst").attr('value', x.mwst);
				jQuery("#letztebrutto").attr('value', x.bruttobetrag);
				jQuery("#letztedatum").html(x.datum);
			} else {
				jQuery("#anschrift").html("Fehler beim Ermitteln der Daten");
			}
		}
	});
}

function summiere() {
	var gs = document.getElementById("geselle_std").value;
	var gl = document.getElementById("geselle_lohn").value;
	var as = document.getElementById("azubi_std").value;
	var al = document.getElementById("azubi_lohn").value;
	var m = document.getElementById("material").value;
	var f = document.getElementById("fahrtkosten").value;

	gs = gs.replace((/,/g), ".");
	gl = gl.replace((/,/g), ".");
	as = as.replace((/,/g), ".");
	al = al.replace((/,/g), ".");
	m = m.replace((/,/g), ".");
	f = f.replace((/,/g), ".");

	document.getElementById("sum_geselle").value = (gs * gl).toFixed(2);
	document.getElementById("sum_azubi").value = (as * al).toFixed(2);
	document.getElementById("sum_material").value = (m * 1).toFixed(2);
	document.getElementById("sum_fk").value = (f * 1).toFixed(2);
	document.getElementById("sum_ges").value = (gs * gl + as * al + m * 1 + f * 1)
			.toFixed(2);
}

function summierePflegerechnung() {
	var pk = document.getElementById("pflegekosten").value;
	var fk = document.getElementById("fahrtkosten").value;

	var pk = jQuery("#pflegekosten").val();
	var fk = jQuery("#fahrtkosten").val();

	pk = pk.replace((/,/g), ".");
	fk = fk.replace((/,/g), ".");

	var summe = (pk * 1 + fk * 1).toFixed(2);
	var mwst = (summe * 0.19).toFixed(2);
	var brutto = (summe * 1 + mwst * 1).toFixed(2);
	jQuery("#summe").attr('value', summe.replace(".", ","));
	jQuery("#jsBruttoBetrag").attr('value', brutto.replace(".", ","));
	jQuery("#mwst").attr('value', mwst.replace(".", ","));
}

function doAbschlag() {
	doAdresse();
	var gid = document.getElementById("gemeindeid");
	if(gid && gid.value != 0) {
		jQuery.ajax({
			url : "./src/rechnung/rechnungen.php",
			method : "GET",
			data : "action=ajax&target=abschlagsrechnungen&tpl=2&gid=" + gid.value,
			success : function(data) {
				var x = jQuery.parseJSON(data);
				jQuery("#rechnungen").html(x.content);
				jQuery("#gnetto").val(x.gesamtnetto.replace((/\./g), ","));
			}
		});
	} else {
		// kann 0 sein, wenn nichts aus der selectbox ausgewaehlt wird.
	}
}

function ajaxLoadAbschlagsrechnungenForNewAbschlag() {
	doAdresse();
	var gid = document.getElementById("gemeindeid");
	if(gid.value != 0) {
		jQuery.ajax({
			url : "./src/rechnung/rechnungen.php",
			method : "GET",
			data : "action=ajax&target=abschlagsrechnungen&tpl=1&gid=" + gid.value,
			success : function(data) {
				var x = jQuery.parseJSON(data);
				jQuery("#rechnungen").html(x.content);
				jQuery("#gnetto").val(x.gesamtnetto);
				jQuery("#jsAbschlagNr").val(x.naechster_abschlag);
			}
		});
	}
}

var lastRow = null;
var ctxRechnungMenu = null;
var lastRechnungsId;
var jsRechnungsBetrag;
var jsEingangsBetrag;
var jsEingangsDatum;
var jsAnmerkung;
var dataRechnungsId;
var dataTypId;
var dataCSSClass;

function onClickShowContext(jsRechnungsId, rechnungsId, typId, cssClass) {
	if (lastRow != null) {
		lastRow.removeClass("selectedRechnung");
	}
	if (lastRechnungsId == jsRechnungsId) {
		onClickResetLastElement();
	} else {
		lastRow = $('#' + jsRechnungsId);
		lastRow.addClass("selectedRechnung");
		jsRechnungsBetrag.attr('value', $('#jsBruttoBetrag_' + jsRechnungsId)
				.attr('value'));
		jsEingangsBetrag.attr('value', jQuery(
				'#jsEingangsBetrag_' + jsRechnungsId).attr('value'));
		jsEingangsDatum.attr('value', jQuery(
				'#jsEingangsDatum_' + jsRechnungsId).attr('value'));
		jsAnmerkung.html(jQuery('#jsEingangsAnmerkung_' + jsRechnungsId).attr(
				'value'));

		ShowContent('jsRechnungMenu', findPos(document.getElementById('jsIcon_'
				+ jsRechnungsId)));
		lastRechnungsId = jsRechnungsId;
		dataRechnungsId = rechnungsId;
		dataTypId = typId;
		dataCSSClass = cssClass;
	}
	return false;
}

function onClickResetLastElement() {
	lastRow.removeClass("selectedRechnung");
	HideContent('jsRechnungMenu');
	lastRechnungsId = null;

	jsRechnungsBetrag.attr('value', '');
	jsEingangsBetrag.attr('value', '');
	jsAnmerkung.attr('value', '');

	var heute = new Date();
	korryear = (heute.getYear() >= 2000) ? heute.getYear()
			: ((heute.getYear() < 80) ? heute.getYear() + 2000 : heute
					.getYear() + 1900)
	jsEingangsDatum.attr('value', heute.getDate() + "."
			+ (heute.getMonth() + 1) + "." + korryear);

	return false;
}

function onClickSubmitRechnungseingang() {
	$.ajax({
		type : 'POST',
		url : './src/rechnung/rechnungen.php?action=eingangsrechnung',
		data : {
			id : dataRechnungsId,
			typid : dataTypId,
			betrag : jsEingangsBetrag.attr('value'),
			datum : jsEingangsDatum.attr('value'),
			anmerkung : jsAnmerkung.attr('value'),
			css : dataCSSClass
		},
		success : function(data) {
			$('#' + lastRechnungsId).html(data);
			onClickResetLastElement();
		}
	});

}
function findPos(obj) {
	var x = y = 0;
	if (obj.offsetParent) {
		x = obj.offsetLeft
		y = obj.offsetTop
		while (obj = obj.offsetParent) {
			x += obj.offsetLeft
			y += obj.offsetTop
		}
	}
	return [ x, y ];
}

jQuery(function() {
	$('textarea').autoResize({
		animateDuration : 300,
		extraSpace : 20,
		limit : 120
	});

	ctxRechnungMenu = $('#jsRechnungMenu');
	jsRechnungsBetrag = $('#jsRechnungsBetrag');
	jsEingangsBetrag = $('#jsEingangsBetrag');
	jsEingangsDatum = $('#jsEingangsDatum');
	jsAnmerkung = $('#jsAnmerkung');

	$(".jsRechnungsPositionsSuggestion")
			.autocomplete({
					source : "src/rechnung/rechnungen.php?target=ajax&action=rechnungspositionen",
					minLength : 2
			});
	
	jQuery('.datePickerRechnung').datepicker({
		showOn : "button",
		showWeek : true,
		buttonImage : "web/images/calendar.png",
		buttonImageOnly : true,
		buttonText : "",
		onSelect: function(date) {
			var date2 = $('#jsDatePickerRechnungsdatum').datepicker('getDate');
			var zahlungsziel = parseInt(jQuery('#jsZahlungsziel').val());
            date2.setDate(date2.getDate()+zahlungsziel);
            $('#jsDatePickerZieldatum').datepicker('setDate', date2);            
		}
	});
});