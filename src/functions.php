<?php
/**
 * Puerco functions and definitions
 *
 * @package Puerco
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 655; /* pixels */
}

if ( ! function_exists( 'puerco_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function puerco_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Puerco, use a find and replace
	 * to change 'puerco' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'puerco', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 1000, 9999, false );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'puerco' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link',
	) );

	add_editor_style( array( 'editor-style.css', puerco_fonts_url() ) );

	// Add support for the Nova Menu CPT from Jetpack
	add_theme_support( 'nova_menu_item' );
}
endif; // puerco_setup
add_action( 'after_setup_theme', 'puerco_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function puerco_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Footer', 'puerco' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'puerco_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function puerco_scripts() {
	wp_enqueue_style( 'puerco-style', get_stylesheet_uri() );

	wp_enqueue_script( 'puerco', get_template_directory_uri() . '/js/puerco.js', array('jquery'), '0.1.0', true );

	wp_enqueue_script( 'puerco-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_page_template( 'template-menu.php' ) ) {
		wp_enqueue_script( 'puerco-menu', get_template_directory_uri() . '/js/menu.js', array('jquery'), '0.1.0', true );
	}
}
add_action( 'wp_enqueue_scripts', 'puerco_scripts' );

/**
 * Returns the Google font stylesheet URL, if available.
 *
 * The use of Vollkorn and Playfair Display by default is localized.
 * For languages that use characters not supported by either font,
 * the font can be disabled.
 *
 * @since Puerco 1.0
 *
 * @return string Font stylesheet or empty string if disabled.
 */
function puerco_fonts_url() {
	$fonts_url = '';

	/* Translators: If there are characters in your language that are not
	 * supported by Vollkorn, translate this to 'off'. Do not translate
	 * into your own language.
	 */
	$vollkorn = _x( 'on', 'Vollkorn font: on or off', 'puerco' );

	/* Translators: If there are characters in your language that are not
	 * supported by Playfair Display, translate this to 'off'. Do not
	 * translate into your own language.
	 */
	$playfair_display = _x( 'on', 'Playfair Display font: on or off', 'puerco' );

	if ( 'off' !== $vollkorn || 'off' !== $playfair_display ) {
		$font_families = array();

		if ( 'off' !== $vollkorn )
			$font_families[] = urlencode( 'Vollkorn:400italic,700italic,400,700' );

		if ( 'off' !== $playfair_display )
			$font_families[] = urlencode( 'Playfair Display:900,900italic' );

		$protocol = is_ssl() ? 'https' : 'http';
		$fonts_url = add_query_arg( 'family', implode( '|', $font_families ), "$protocol://fonts.googleapis.com/css" );
	}

	return $fonts_url;
}

/**
 * Loads our special font CSS file.
 *
 * To disable in a child theme, use wp_dequeue_style()
 * function mytheme_dequeue_fonts() {
 *     wp_dequeue_style( 'puerco-fonts' );
 * }
 * add_action( 'wp_enqueue_scripts', 'mytheme_dequeue_fonts', 11 );
 *
 * @since Puerco 1.0
 *
 * @return void
 */
function puerco_fonts() {
	$fonts_url = puerco_fonts_url();
	if ( ! empty( $fonts_url ) )
		wp_enqueue_style( 'puerco-fonts', esc_url_raw( $fonts_url ), array(), null );
}
add_action( 'wp_enqueue_scripts', 'puerco_fonts' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';
