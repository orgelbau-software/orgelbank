$( function() {
//	$(".awStatusGebuchtIMG").attr("src", "web/images/icons/document_a4_locked.png");
	
	
	$("#projektliste").tableDnD({
		onDrop: function(table, row) {
            var rows = table.tBodies[0].rows;
            var debugStr = "";
            for (var i=0; i<rows.length; i++) {
                debugStr += rows[i].id+",";
            }
//            alert(debugStr);
            $.get("src/projekt/projekt.php?action=ajax&request=projectsort&order="+debugStr);
        }, 
        dragHandle: ".dragHandle",
        onDragClass: "onDrag"
	});
	
	$("#mitarbeiterTable").tableDnD({
		onDrop: function(table, row) {
            var rows = table.tBodies[0].rows;
            var debugStr = "";
            for (var i=0; i<rows.length; i++) {
                debugStr += rows[i].id+",";
            }
//            alert(debugStr);
            $.get("src/projekt/projekt.php?action=ajax&request=mitarbeitersort&order="+debugStr);
        }, 
        dragHandle: ".dragHandle",
        onDragClass: "onDrag"
	});
	
	$("#jsAufgabenListe").tableDnD({
		onDrop: function(table, row) {
            var projektId = jQuery("#jsProjektId").val();
			var rows = table.tBodies[0].rows;
            var debugStr ="";
            for (var i=0; i<rows.length; i++) {
                debugStr += rows[i].id+",";
            }
            $.get("src/projekt/projekt.php?action=ajax&request=projektaufgabensortierung&pid="+projektId+"&order="+debugStr);
        }, 
        dragHandle: ".dragHandle",
        onDragClass: "onDrag"
	});	
	
	
});

function switchPlankostenInput($elementID) {
	$curr = $("#chkbx" + $elementID).attr("readonly");
	if ($curr == true) {
		$("#chkbx" + $elementID).attr("readonly", false)
		$("#chkbx" + $elementID).removeClass("readOnly");
	} else {
		$("#chkbx" + $elementID).attr("readonly", true);
		$("#chkbx" + $elementID).addClass("readOnly");
	}
}

var $loadedUnteraufgaben = new Array();

function loadUnteraufgaben($projektID, $aufgabeID) {
	if ($loadedUnteraufgaben.indexOf($aufgabeID) == -1) {
		$.get("src/projekt/projekt.php?action=ajax&request=unteraufgaben&p="
				+ $projektID + "&a=" + $aufgabeID,
				function(data) {
					$("#HA" + $aufgabeID).after(data);

					$parentCSSClass = $("#HA" + $aufgabeID + " td:first").attr(
							"class");
					$(".UA" + $aufgabeID + " .indent1").each( function() {
						$(this).addClass($parentCSSClass);
					});
				});
		$loadedUnteraufgaben.push($aufgabeID);
		$loadedUnteraufgaben[$aufgabeID] = new Array();
		$("#IMG" + $aufgabeID).attr("src",
				"web/images/icons/remove_minus_sign_small.png");
	} else {
		$(".UA" + $aufgabeID).remove();
		var $index = $loadedUnteraufgaben.indexOf($aufgabeID);
		$loadedUnteraufgaben[$index] = null;
		$("#IMG" + $aufgabeID).attr("src", "web/images/icons/add_small.png");
	}
}

function loadAufgabenMitarbeiterstunden($projektID, $aufgabeID, $parentID) {
	if ($loadedUnteraufgaben[$parentID] != -1
			&& $loadedUnteraufgaben[$parentID].indexOf($aufgabeID) == -1) {
		$.get("src/projekt/projekt.php?action=ajax&request=ams&p=" + $projektID
				+ "&a=" + $aufgabeID, function(data) {
			$("#UA" + $aufgabeID).after(data);

			$parentCSSClass = $("#HA" + $parentID + " td:first").attr("class");
			$(".AMS" + $aufgabeID + " .indent1").each( function() {
				$(this).addClass($parentCSSClass);
			});
			$parentCSSClass = $("#UA" + $aufgabeID + " td:last").attr("class");
			$(".AMS" + $aufgabeID + " .indent2").each( function() {
				$(this).addClass($parentCSSClass);
			});

		});
		$loadedUnteraufgaben[$parentID].push($aufgabeID);
		$("#IMG" + $aufgabeID).attr("src",
				"web/images/icons/remove_minus_sign_small.png");
	} else {
		$(".AMS" + $aufgabeID).remove();
		var $index = $loadedUnteraufgaben[$parentID].indexOf($aufgabeID);
		$loadedUnteraufgaben[$parentID][$index] = null;
		$("#IMG" + $aufgabeID).attr("src", "web/images/icons/add_small.png");
	}
}

$loadedMWS = new Array();
function loadMitarbeiterWochenStunden($wochenTag) {
	if ($loadedMWS.indexOf($wochenTag) == -1) {
		$.get(
				"src/projekt/projekt.php?action=ajax&request=mitarbeiterstunden&date="
						+ $wochenTag, function(data) {
					$("#AW" + $wochenTag).after(data);
				});
		$loadedMWS.push($wochenTag);
		$("#IMG" + $wochenTag).attr("src",
				"web/images/icons/remove_minus_sign_small.png");
	} else {
		$(".AW" + $wochenTag).remove();
		var $index = $loadedMWS.indexOf($wochenTag);
		$loadedMWS[$index] = null;
		$("#IMG" + $wochenTag).attr("src", "web/images/icons/add_small.png");
	}
}

$(function() {
	$("#km").change(function(){
		$("#kmkosten").attr("value", $("#km").attr("value") * kilometerPauschale);
		$("#kmkosten").attr("value", function() {
			return this.value.replace(".", ",");
		});
		$("#kmkosten").format({format:"#,###.00", locale:"de"});
	});
	$(".reisekosten").change(function(){
		var $summe = 0;
	   $(".rkvalue").each(function() {
		   tmp = $(this).attr("value");
		   tmp = tmp.replace((/,/g), ".");
		   tmp = round(tmp);
		   $summe += tmp * 1;
	   });
	   $("#rk").attr("value", $summe);
	   $("#rk").format({format:"#,###.00", locale:"de"});
	});
});

function summiereKosten(){
	var $summe = 0;
   $(".reisekosten").each(function() {
	   tmp = $(this).attr("value");
	   tmp = tmp.replace((/,/g), ".");
	   tmp = round(tmp);
	   $summe += tmp * 1;
   });
   $("#rk").attr("value", $summe);
   $("#rk").format({format:"#,###.00", locale:"de"});
}

function round(x) {
	var a = Math.pow(10, 2);
	return (Math.round(x * a) / a).toFixed(2);
}


$mitarbeiterSelected = false;
function switchSelectAlleMitarbeiter() {
	if ($mitarbeiterSelected == false) {
		$mitarbeiterSelected = true;
	} else {
		$mitarbeiterSelected = false;
	}
	$(".chkbx").prop('checked', $mitarbeiterSelected);
}
