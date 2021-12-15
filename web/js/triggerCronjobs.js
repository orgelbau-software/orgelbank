$( function() {	
	$.ajax({
		type : 'POST',
		url : './src/cronjobs/cronjob.php',
		data : {
			key : apiKey
		}
	});
});