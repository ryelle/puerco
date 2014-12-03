<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package Puerco
 */

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @param array $args Configuration arguments.
 * @return array
 */
function puerco_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'puerco_page_menu_args' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function puerco_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}
	if ( is_singular() && ! comments_open() && ! get_comments_number() ) {
		$classes[] = 'comments-closed';
	}

	if ( is_page_template( 'template-menu.php' ) ) {
		// Remove "page" from class list
		if ( false !== ( $index = array_search( 'page', $classes ) ) ) {
			unset( $classes[ $index ] );
		}
		$classes[] = 'menu-page';
	}

	if ( is_page_template( 'template-two-col.php' ) ) {
		$classes[] = 'two-col-page';
	}

	return $classes;
}
add_filter( 'body_class', 'puerco_body_classes' );

/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function puerco_wp_title( $title, $sep ) {
	if ( is_feed() ) {
		return $title;
	}

	global $page, $paged;

	// Add the blog name
	$title .= get_bloginfo( 'name', 'display' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title .= " $sep $site_description";
	}

	// Add a page number if necessary:
	if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
		$title .= " $sep " . sprintf( __( 'Page %s', 'puerco' ), max( $paged, $page ) );
	}

	return $title;
}
add_filter( 'wp_title', 'puerco_wp_title', 10, 2 );

/**
 * Sets the authordata global when viewing an author archive.
 *
 * This provides backwards compatibility with
 * http://core.trac.wordpress.org/changeset/25574
 *
 * It removes the need to call the_post() and rewind_posts() in an author
 * template to print information about the author.
 *
 * @global WP_Query $wp_query WordPress Query object.
 * @return void
 */
function puerco_setup_author() {
	global $wp_query;

	if ( $wp_query->is_author() && isset( $wp_query->post ) ) {
		$GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
	}
}
add_action( 'wp', 'puerco_setup_author' );

/**
 * Return a "Read More" link for excerpts
 *
 * @return string The "Read More" HTML link, with a screen-reader'd post title.
 */
function puerco_continue_reading_link() {
	/* translators: %s: Name of current post */
	$link_text = sprintf(
		__( 'Read more %s', 'puerco' ),
		the_title( '<span class="screen-reader-text">"', '"</span>', false )
	);

	return ' <a class="read-more" href="'. esc_url( get_permalink() ) . '">' . $link_text . '</a>';
}

/**
 * Replace the "[...]" after generated excerpts with an ellipsis.
 *
 * The "[...]" is appended to automatically generated excerpts.
 *
 * @param string $more The Read More text.
 * @return The filtered Read More text.
 */
function puerco_auto_excerpt_more( $more ) {
	return ' &hellip; ';
}
add_filter( 'excerpt_more', 'puerco_auto_excerpt_more' );

/**
 * Add a pretty "Read More" link to post excerpts.
 *
 * @param   string  $output The post excerpt.
 * @return  string  The post excerpt with a "Read More" link.
 */
function puerco_custom_excerpt_more( $output ) {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>, ';
	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);
	$prefix = '<span class="posted-on">' . $time_string . '</span>';

	if ( empty( $output ) ){
		$prefix .= ' &hellip;';
	}
	return $prefix . $output . puerco_continue_reading_link();
}
add_filter( 'the_excerpt', 'puerco_custom_excerpt_more', 1 );

/**
 * Filter comment form fields to add a wrapper div around the name/email/url fields.
 */
function puerco_comment_form_fields( $fields ){
	$fields['author'] = '<div class="field-container">' . $fields['author'];
	$fields['url'] = $fields['url'] . '</div><!-- /.field-container -->';
	return $fields;
}
add_filter('comment_form_default_fields', 'puerco_comment_form_fields' );

/**
 * Force the default playlist style to be "dark" if the shortcode does not explicitly define a style.
 *
 * @param  array  $out   The output array of shortcode attributes.
 * @param  array  $pairs The supported attributes and their defaults.
 * @param  array  $atts  The user defined shortcode attributes.
 * @return  array  The output array of shortcode attributes, updated.
 */
function puerco_shortcode_atts_playlist( $out, $pairs, $atts ) {
	if ( ! array_key_exists( 'style', $atts) ) {
		$out['style'] = 'dark';
	}
	return $out;
}
add_filter( 'shortcode_atts_playlist', 'puerco_shortcode_atts_playlist', 10, 3 );
