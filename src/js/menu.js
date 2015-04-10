/**
 * menu.js
 *
 * Handles display/interaction on the menu page
 */
( function( $, undefined ) {

	var $div,
		container    = document.getElementById('menu-container'),
		sections     = container.querySelectorAll( '.menu-items' ),
		headers      = container.querySelectorAll( '.menu-group-title' ),
		descriptions = container.querySelectorAll( '.menu-group-description' ),
		functions    = {};

	functions.show = function( index ) {
		var section = $( sections ).filter( "[data-id='" + index + "']" ),
			description = $( descriptions ).filter( "[data-id='" + index + "']" );

		$( ".selected" ).removeClass('selected');
		$( headers ).filter( "[data-id='" + index + "']" ).addClass('selected')

		if ( description.length ) {
			description.addClass('selected');
			$(".menu-description-container").height( description.height() + 31 );
		} else {
			$(".menu-description-container").height( 0 );
		}

		section.addClass('selected');
		$(".menu-items-container").height( section.height() + 30 );
	}

	$( headers ).on( 'click', function(event){
		event.preventDefault();
		functions.show( $( this ).data('id') );
	})
	functions.show( $( headers[0] ).data('id') );

} )( jQuery );
