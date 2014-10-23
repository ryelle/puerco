<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package Puerco
 */

$css = '';
if ( has_post_thumbnail() ){
	$image_id = get_post_thumbnail_id();
	$image = wp_get_attachment_image_src( $image_id, 'full' );
	if ( is_array( $image ) ) {
		$css = sprintf( ' style="background-image:url(%s);"', esc_url( $image[0] ) );
	}
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php echo $css; ?>>
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

<?php edit_post_link( __( 'Edit', 'puerco' ), '<span class="edit-link">', '</span>' ); ?>
