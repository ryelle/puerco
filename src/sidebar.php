<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package Puerco
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>

<div id="secondary" <?php puerco_widget_class(); ?> role="complementary">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</div><!-- #secondary -->
