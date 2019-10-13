jQuery(function() {
	$('a#nummerieren').click(function() {
		var i = 1;
		$('.liste tr td input').attr('value', function() {
			return i++;
		});
	});
	
	$("#dispositionsTable").tableDnD({
		onDrop: function(table, row) {
            var rows = table.tBodies[0].rows;
            var debugStr = "";
            for (var i=0; i<rows.length; i++) {
                debugStr += rows[i].id+",";
            }
//            alert(debugStr);
            $.get("src/orgel/disposition.php?action=dispositionssort&order="+debugStr);
        }, 
        dragHandle: ".dragHandle",
        onDragClass: "onDrag"
	});
	
	$("#register").autocomplete({
		source: "src/orgel/disposition.php?action=ajax&limit=10", 
		minLength: 2
	});

});