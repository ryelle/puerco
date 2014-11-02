<?php
/**
 * @package Puerco
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<?php if ( has_post_thumbnail() ) : ?>
	<div class="entry-image">
		<?php the_post_thumbnail(); ?>
	</div>
	<?php endif; ?>

	<div class="entry-container">
		<div class="entry-content">
			<?php the_content(); ?>
			<?php
				wp_link_pages( array(
					'before' => '<div class="page-links">' . __( 'Pages:', 'puerco' ),
					'after'  => '</div>',
				) );
			?>
		</div><!-- .entry-content -->
	</div><!-- .entry-container -->

	<footer class="entry-meta">
		<?php puerco_posted_on(); ?>
		<?php puerco_entry_footer(); ?>
	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
