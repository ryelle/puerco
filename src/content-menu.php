<?php
/**
 * The template used for displaying menu content in template-menu.php
 *
 * @package Puerco
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header clear">
		<?php the_title( '<h2 class="entry-title">', '</h2>' ); ?>
		<?php puerco_the_price( '<span class="menu-price">', '</span>' ); ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'puerco' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->
</article><!-- #post-## -->
