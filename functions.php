<?php

// Include Files
include_once 'includes/functions/general.php'; // general functions, customizations
include_once 'includes/functions/dashboard.php'; // logged-in customizations; includes login screen, dashboard, editor, etc
include_once 'includes/functions/dashboard-settings.php'; // adds fields to Settings pages, incl new image sizes and email addresses
include_once 'includes/functions/rss.php'; // improves RSS feeds, adds featured images and image size, cleanup defaults
include_once 'includes/functions/template-functions.php'; // Functions to be used in various page templates

include_once 'includes/functions/plugin-acf.php'; // ACF extensions
include_once 'includes/functions/plugin-yoast.php'; // Yoast extensions
include_once 'includes/functions/plugin-woocommerce.php'; // WooCommerce extensions

include_once 'includes/functions/vod.php'; // Video On Demand post type
include_once 'includes/widgets/recent-vods.php'; // Recent VODs Widget

// Disable sidebar
add_filter( 'sidebar_enabled', '__return_false' );

// Theme Configuration
function theme_scripts() {
	$theme = wp_get_theme();
	$theme_version = $theme->get( 'Version' );

	wp_enqueue_style( get_stylesheet(), get_stylesheet_uri(), array(), $theme_version );
	wp_enqueue_script( 'main', get_template_directory_uri() . '/includes/assets/main.js' , array('jquery'), $theme_version );
	
	wp_enqueue_script( 'flowplayer', get_template_directory_uri() . '/includes/assets/flowplayer/flowplayer-3.2.13.min.js', array(), '3.2.13' );
}
add_action( 'wp_enqueue_scripts', 'theme_scripts' );
function theme_print_scripts() {
	?>
	<script type="text/javascript">
		var flowplayer_swf = "<?php echo get_template_directory_uri(); ?>/includes/assets/flowplayer/flowplayer-3.2.18.swf";
	</script>
	<?php
}
add_action( 'wp_print_scripts', 'theme_print_scripts' );

function theme_setup() {

	// 1. Theme Features
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );
	
	// 2. Menus
	$menus = array(
		'header' => 'Header',
		'footer' => 'Footer',
		'legal' => 'Legal',
		'social' => 'Social' // Be sure to use the "social-icon" class for menu items, as well as a class of the social network (eg: "facebook")
	);
	register_nav_menus($menus);
	
	// 3. Sidebars
	$sidebars = array(
		'sidebar'    => array(
			'Sidebar',
			'Default sidebar.',
		),
		'store'      => array(
			'Store',
			'WooCommerce store sidebar.',
		),
		'checkout'   => array(
			'Checkout',
			'WooCommerce checkout page sidebar.',
		),
	);

	foreach ( $sidebars as $key => $bar ) {
		register_sidebar( array(
			'id'          => $key,
			'name'        => $bar[0],
			'description' => $bar[1],

			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>'
		));
	}
	
	// 4. Shortcodes
	add_shortcode( 'year', function () { return date('Y'); } );
}
add_action( 'after_setup_theme', 'theme_setup' );