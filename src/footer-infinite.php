<div id="infinite-footer">
	<div class="container">
		<div class="blog-info">
			<a id="infinity-blog-title" href="<?php echo home_url( '/' ); ?>" rel="home">
				<?php bloginfo( 'name' ); ?>
			</a>
		</div>
		<div class="blog-credits">
			<a href="<?php echo esc_url( __( 'http://wordpress.org/', 'puerco' ) ); ?>"><?php printf( __( 'Proudly powered by %s', 'puerco' ), 'WordPress' ); ?></a>
			<span class="sep"> | </span>
			<?php printf( __( 'Theme: %1$s by %2$s.', 'puerco' ), 'Puerco', '<a href="http://themes.redradar.net" rel="designer">Kelly Dwan & Mel Choyce</a>' ); ?>
		</div>
	</div>
</div><!-- #infinite-footer -->
