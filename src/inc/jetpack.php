<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package Puerco
 */

/**
 * Add theme support for Infinite Scroll.
 * See: http://jetpack.me/support/infinite-scroll/
 */
function puerco_jetpack_setup() {
	add_theme_support( 'infinite-scroll', array(
		'container' => 'main',
		'footer'    => 'page',
	) );
}
add_action( 'after_setup_theme', 'puerco_jetpack_setup' );

function puerco_nova_setup(){
	// Just grab the Nova_Restaurant instance, don't set up the variables.
	global $nova;
	$nova = Nova_Restaurant::init( false );
	remove_filter( 'template_include', array( $nova, 'setup_menu_item_loop_markup__in_filter' ) );
}
add_action( 'init', 'puerco_nova_setup' );
