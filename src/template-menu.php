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
			<?php
				global $nova;
				$loop = new WP_Query( array( 'post_type' => Nova_Restaurant::MENU_ITEM_POST_TYPE ) );
				$items = wp_list_pluck( $loop->get_posts(), 'ID' );
				$terms = array();
				foreach ($items as $item ) {
					$term = $nova->get_menu_item_menu_leaf( $item );
					if ( ! in_array( $term, $terms ) ){
						$terms[] = $term;
					}
				}
			?>

			<header class="menu-header">
			<?php
				foreach ( $terms as $term ){
					printf( '<h1 class="menu-group-title" data-id="%2$s">%1$s</h1>',
						esc_html( $term->name ),
						absint( $term->term_id )
					);
				}
				$description_text = '';
				foreach ( $terms as $term ){
					if ( empty( $term->description ) ) {
						continue;
					}
					$description_text .= sprintf( '<p class="menu-group-description" data-id="%2$s">%1$s</p>',
						strip_tags( $term->description ),
						absint( $term->term_id )
					);
				}
				if ( $description_text ) {
					printf( '<div class="menu-description-container">%s</div>', $description_text );
				}
			?>
			</header>

			<!-- Menu Items -->
			<div class="menu-items-container">
			<?php $last_term = false; ?>
			<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
				<?php
				$term = $nova->get_menu_item_menu_leaf( get_the_ID() );
				if ( false === $last_term ) {
					printf( '<section class="menu-items %s" data-id="%s">', esc_attr( $term->slug ), absint( $term->term_id ) );
				} elseif ( $term != $last_term ) {
					printf( '</section><section class="menu-items %s" data-id="%s">', esc_attr( $term->slug ), absint( $term->term_id ) );
				} $last_term = $term;
				?>

				<?php get_template_part( 'content', 'menu' ); ?>

			<?php endwhile; ?>
			</section>
			</div><!-- .menu-items-container -->
			</div><!-- #menu-container -->

		</main><!-- #main -->
	</div><!-- #primary -->

<?php wp_reset_postdata(); ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
