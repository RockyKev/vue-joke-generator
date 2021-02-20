jQuery(function($) {
	
	$.ajax({
		method: 'GET',
		url: APEX.api.url
		beforeSend: function ( xhr ) {
			xhr.setRequestHeader( 'X-WP-Nonce', APEX.api.nonce );
		}
	}).then( function ( r ) {
		if( r.hasOwnProperty( 'industry' ) ){
			$( '#industry' ).val( r.industry );
		}

		if( r.hasOwnProperty( 'amount' ) ){
			$( '#amount' ).val( r.amount );
		}
    })
    
    $( '#apex-form' ).on( 'submit', function (e) {
		e.preventDefault();
		var data = {
			amount: $( '#amount' ).val(),
			industry: $( '#industry' ).val()
		};

		$.ajax({
			method: 'POST',
			url: APEX.api.url,
			beforeSend: function ( xhr ) {
				xhr.setRequestHeader('X-WP-Nonce', APEX.api.nonce);
			},
			data:data
		}).then( function (r) {
			$( '#feedback' ).html( '<p>' + APEX.strings.saved + '</p>' );
		}).error( function (r) {
			var message = APEX.strings.error;
			if( r.hasOwnProperty( 'message' ) ){
				message = r.message;
			}
			$( '#feedback' ).html( '<p>' + message + '</p>' );

        })
    })

});