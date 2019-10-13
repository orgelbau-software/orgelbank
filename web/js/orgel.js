$( function() {
	$('.orgelimagelink').lightBox();
});

$checkedCache = new Array();
function switchSelectRubrik($rubrikID) {
	if ($checkedCache.indexOf($rubrikID) == -1) {
		$(".chkbx" + $rubrikID).attr('checked', true);
		$checkedCache.push($rubrikID);
	} else {
		$(".chkbx" + $rubrikID).attr('checked', false);
		var $ndx = $checkedCache.indexOf($rubrikID);
		$checkedCache[$ndx] = null;
	}
}
