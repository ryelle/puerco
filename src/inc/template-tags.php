<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Puerco
 */

if ( ! function_exists( 'puerco_paging_nav' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 */
function puerco_paging_nav() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}
	?>
	<nav class="navigation paging-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Posts navigation', 'puerco' ); ?></h1>
		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'puerco' ) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'puerco' ) ); ?></div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'puerco_post_nav' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 */
function puerco_post_nav() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'puerco' ); ?></h1>
		<div class="nav-links">
			<?php
				previous_post_link( '<div class="nav-previous">%link</div>', _x( '<span class="meta-nav">&larr;</span>&nbsp;%title', 'Previous post link', 'puerco' ) );
				next_post_link(     '<div class="nav-next">%link</div>',     _x( '%title&nbsp;<span class="meta-nav">&rarr;</span>', 'Next post link',     'puerco' ) );
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
	// Like, beat it. Dig?
	delete_transient( 'puerco_categories' );
}
add_action( 'edit_category', 'puerco_category_transient_flusher' );
add_action( 'save_post',     'puerco_category_transient_flusher' );
