/**
 * menu.js
 *
 * Handles display/interaction on the menu page
 */
( function( $, undefined ) {

	var $div,
		container    = document.getElementById('menu-container'),
		sections     = container.querySelectorAll('.menu-items'),
		descriptions = [],
		headers      = [],
		functions    = {};

	functions.show = function( index ) {
		$( sections ).hide();
		$( sections[ index ] ).show();
		$( descriptions ).hide();
		$( descriptions[ index ] ).show();

		$( ".selected" ).removeClass('selected');
		$( headers[index] ).addClass('selected');
	}

	$( sections ).each(function( index, element ) {
		var header  = element.querySelector('.menu-group-header'),
			descrip = element.querySelector('.menu-group-description');
		$( element ).data( 'header', index );
		$( header ).data( 'section', index );
		$( descrip ).attr( 'data-section', index );
		headers.push( header );
		descriptions.push( descrip );
	});

	$( headers ).on( 'click', function(event){
		event.preventDefault();
		functions.show( $( this ).data('section') );
	})

	$div = $( '<div />' ).addClass('description-container');
	$div.html( $( descriptions ).detach() ).prependTo( container );

	$div = $( '<div />' ).addClass('header-container');
	$div.html( $( headers ).detach() ).prependTo( container );
	functions.show(0);

} )( jQuery );
