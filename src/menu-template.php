<?php
/**
 * Template Name: Menu Template
 *
 * @package Puerco
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="entry-header">
						<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
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

			<?php endwhile; // end of the loop. ?>

			<div id="menu-container">
			<?php $loop = new WP_Query( array( 'post_type' => 'nova_menu_item' ) ); ?>
			<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>

				<?php get_template_part( 'content', 'menu' ); ?>

			<?php endwhile; ?>
			</div><!-- #menu-container -->

		</main><!-- #main -->
	</div><!-- #primary -->

<?php wp_reset_postdata(); ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
