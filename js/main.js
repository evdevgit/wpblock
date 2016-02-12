(function($) {

	var url = 'http://localhost:9000/event/listings';
	$.getJSON( url, function( data ) {
		if (data == '') {
			console.log("no data");
		} else {
			$( data ).each(function( index, event ) {
				console.log(event);
				$('#content').append('<p>'+event.event_name+'</p>');
			});
		}
	});

})( jQuery );

setInterval(function(){
	console.log(document.cookie);
}, 1000);
