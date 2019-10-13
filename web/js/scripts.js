$(function() {
	$(".jsFormatNumber").blur(function() {
		$(this).format({
			format : "#,###.00",
			locale : "de"
		});
	});

	$(".jsFormatNumber").numeric({
		allow : ","
	});
	$(".jsFormatNumber").format({
		format : "#,###.00",
		locale : "de"
	});
	$(".statusmsg:not(.jsNoFade)").delay(4000, function() {
		$(".statusmsg").fadeOut(1000);
	});
	$(".disabled").attr("href", "#");
	$(".jsJahr").numeric();
	$(".jsNumber").numeric();
	$(".jsCurrency").numeric({
		allow : ","
	});
	$(".jsCalendar").numeric({
		allow : "."
	});
});

function clickclear(thisfield, defaulttext) {
	if (thisfield.value == defaulttext) {
		thisfield.value = "";
	}
}

function clickrecall(thisfield, defaulttext) {
	if (thisfield.value == "") {
		thisfield.value = defaulttext;
	}
}

$(function() {
	var cal;
	var $this;

	var checkForMouseout = function(event) {
		var el = event.target;

		while (true) {
			if (el == cal) {
				return true;
			} else if (el == document) {
				$this.dpClose();
				return false;
			} else {
				el = $(el).parent()[0];
			}
		}
	};
	$('.datePicker').datepicker({
		showOn : "button",
		showWeek : true,
		buttonImage : "web/images/calendar.png",
		buttonImageOnly : true,
		buttonText : ""
	});

});
