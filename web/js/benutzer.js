$( function() {
	jQuery(".jsColumn").click(function() {
		var columnName = "."+jQuery(this).attr('value');
		var displayStatus = jQuery(columnName).css('display');
		var newDisplayStatus = 'none';
		if(displayStatus == null || displayStatus == 'table-cell') {
			newDisplayStatus = 'none';
		} else {
			newDisplayStatus = 'table-cell';
		}
		jQuery(columnName).css('display', newDisplayStatus);
	});
});
