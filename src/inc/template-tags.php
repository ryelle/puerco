<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Puerco
 */

if ( ! function_exists( 'the_posts_navigation' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 */
function the_posts_navigation() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}
	?>
	<nav class="navigation posts-navigation" role="navigation">
		<h2 class="screen-reader-text"><?php _e( 'Posts navigation', 'puerco' ); ?></h2>
		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous"><?php next_posts_link( __( 'Older posts', 'puerco' ) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts', 'puerco' ) ); ?></div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'the_post_navigation' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 */
function the_post_navigation() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation" role="navigation">
		<h2 class="screen-reader-text"><?php _e( 'Post navigation', 'puerco' ); ?></h2>
		<div class="nav-links">
			<?php
				previous_post_link( '<div class="nav-previous">%link</div>', '%title' );
				next_post_link( '<div class="nav-next">%link</div>', '%title' );
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'puerco_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function puerco_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time> <time class="updated" datetime="%3$s">(%4$s)</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = sprintf(
		_x( 'Published %s', 'post date', 'puerco' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	$byline = sprintf(
		_x( 'By %s', 'post author', 'puerco' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	echo '<div class="posted-on meta-item">' . $posted_on . '</div><div class="byline meta-item"> ' . $byline . '</div>';

}
endif;

if ( ! function_exists( 'puerco_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function puerco_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' == get_post_type() ) {
		$full_list = '';

		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( __( ', ', 'puerco' ) );

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', __( ', ', 'puerco' ) );

		if ( $categories_list && puerco_categorized_blog() ) {
			if ( $tags_list ) {
				$tags_list .= __( ', ', 'puerco' );
			}
			$full_list = $tags_list . $categories_list;
		}

		if ( $full_list ) {
			printf(
				'<div class="tax-links meta-item">' . __( 'Tags and Categories %1$s', 'puerco' ) . '</div>',
				'<br />' . $full_list
			);
		}

		if ( has_post_format() ) {
			$format = sprintf( '<a href="%1$s">%2$s</a>', get_post_format_link( get_post_format() ), get_post_format_string( get_post_format() ) );
		} else {
			$format = sprintf( '<span>%s</span>', get_post_format_string( false ) );
		}
		printf(
			'<div class="format-link meta-item">' . __( 'Format %1$s', 'puerco' ) . '</div>',
			'<br />' . $format
		);
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<div class="comments-link meta-item">';
		comments_popup_link( __( 'Leave a comment', 'puerco' ), __( '1 Comment', 'puerco' ), __( '% Comments', 'puerco' ) );
		echo '</div>';
	}

	edit_post_link( __( 'Edit', 'puerco' ), '<div class="edit-link meta-item">', '</div>' );
}
endif;

/**
 * Display the menu item's price
 * @param  string  $before  Optional. Content to prepend to the price.
 * @param  string  $after   Optional. Content to append to the price.
 */
function puerco_the_price( $before = '', $after = '' ){
	global $post;
	$price = get_post_meta( $post->ID, 'nova_price', true );
	if ( $price ) {
		if ( is_numeric( $price ) ) {
			$price = '$' . $price;
		}
		echo $before . esc_html( $price ) . $after;
	}
}

/**
 * Add classes to the footer widget area based on number of active widgets.
 * @param  array|string  $classes  One or more classes to add to the class list
 */
function puerco_widget_class( $class = array() ) {
	$classes = array( 'widget-area' );

	// Default is 3 colums, but if we have 1 or 2, let's flex the spacing.
	$widgets = wp_get_sidebars_widgets();
	if ( isset( $widgets['sidebar-1'] ) ) {
		$count = count( $widgets['sidebar-1'] );
		if ( $count == 1 ){
			$classes[] = 'one-widget';
		} elseif ( $count == 2 ) {
			$classes[] = 'two-widgets';
		}
	}

	$classes = array_merge( $classes, (array) $class );
	$classes = apply_filters( 'puerco_widget_class', $classes );

	printf( ' class="%s"', join( ' ', $classes ) );
}

if ( ! function_exists( 'the_archive_title' ) ) :
/**
 * Shim for `the_archive_title()`.
 *
 * Display the archive title based on the queried object.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the title. Default empty.
 * @param string $after  Optional. Content to append to the title. Default empty.
 */
function the_archive_title( $before = '', $after = '' ) {
	if ( is_category() ) {
		$title = sprintf( __( 'Category: %s', 'puerco' ), single_cat_title( '', false ) );
	} elseif ( is_tag() ) {
		$title = sprintf( __( 'Tag: %s', 'puerco' ), single_tag_title( '', false ) );
	} elseif ( is_author() ) {
		$title = sprintf( __( 'Author: %s', 'puerco' ), '<span class="vcard">' . get_the_author() . '</span>' );
	} elseif ( is_year() ) {
		$title = sprintf( __( 'Year: %s', 'puerco' ), get_the_date( _x( 'Y', 'yearly archives date format', 'puerco' ) ) );
	} elseif ( is_month() ) {
		$title = sprintf( __( 'Month: %s', 'puerco' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'puerco' ) ) );
	} elseif ( is_day() ) {
		$title = sprintf( __( 'Day: %s', 'puerco' ), get_the_date( _x( 'F j, Y', 'daily archives date format', 'puerco' ) ) );
	} elseif ( is_tax( 'post_format' ) ) {
		if ( is_tax( 'post_format', 'post-format-aside' ) ) {
			$title = _x( 'Asides', 'post format archive title', 'puerco' );
		} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
			$title = _x( 'Galleries', 'post format archive title', 'puerco' );
		} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
			$title = _x( 'Images', 'post format archive title', 'puerco' );
		} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
			$title = _x( 'Videos', 'post format archive title', 'puerco' );
		} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
			$title = _x( 'Quotes', 'post format archive title', 'puerco' );
		} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
			$title = _x( 'Links', 'post format archive title', 'puerco' );
		} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
			$title = _x( 'Statuses', 'post format archive title', 'puerco' );
		} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
			$title = _x( 'Audio', 'post format archive title', 'puerco' );
		} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
			$title = _x( 'Chats', 'post format archive title', 'puerco' );
		}
	} elseif ( is_post_type_archive() ) {
		$title = sprintf( __( 'Archives: %s', 'puerco' ), post_type_archive_title( '', false ) );
	} elseif ( is_tax() ) {
		$tax = get_taxonomy( get_queried_object()->taxonomy );
		/* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
		$title = sprintf( __( '%1$s: %2$s', 'puerco' ), $tax->labels->singular_name, single_term_title( '', false ) );
	} else {
		$title = __( 'Archives', 'puerco' );
	}

	/**
	 * Filter the archive title.
	 *
	 * @param string $title Archive title to be displayed.
	 */
	$title = apply_filters( 'get_the_archive_title', $title );

	if ( ! empty( $title ) ) {
		echo $before . $title . $after;
	}
}
endif;

if ( ! function_exists( 'the_archive_description' ) ) :
/**
 * Shim for `the_archive_description()`.
 *
 * Display category, tag, or term description.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the description. Default empty.
 * @param string $after  Optional. Content to append to the description. Default empty.
 */
function the_archive_description( $before = '', $after = '' ) {
	$description = apply_filters( 'get_the_archive_description', term_description() );

	if ( ! empty( $description ) ) {
		/**
		 * Filter the archive description.
		 *
		 * @see term_description()
		 *
		 * @param string $description Archive description to be displayed.
		 */
		echo $before . $description . $after;
	}
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function puerco_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'puerco_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'puerco_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so puerco_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so puerco_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in puerco_categorized_blog.
 */
function puerco_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'puerco_categories' );
}
add_action( 'edit_category', 'puerco_category_transient_flusher' );
add_action( 'save_post',     'puerco_category_transient_flusher' );
