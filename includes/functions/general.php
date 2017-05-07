<?php

// Add charset and viewport tags to <head>
function rs_meta_tags() {
	?>
	<meta charset="<?php echo esc_attr( get_bloginfo( 'charset' ) ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php
}

add_action( 'wp_head', 'rs_meta_tags', 1 );


// Customize the title of the page
function rs_custom_archive_title( $title ) {
	// For taxonomies, show the term name instead of "Archive: {Term Name}"
	if ( is_tax() || is_category() ) {
		$title = single_term_title();
	}

	return $title;
}

add_filter( 'get_the_archive_title', 'rs_custom_archive_title', 10, 2 );


// Clean up <head>
function rs_optimize_head() {
	if ( has_action( 'wp_head', 'feed_links_extra' ) ) {
		remove_action( 'wp_head', 'feed_links_extra', 3 );
		add_action( 'wp_head', 'feed_links_extra', 30 );
	}
	
	if ( has_action( 'wp_head', 'feed_links' ) ) {
		remove_action( 'wp_head', 'feed_links', 2 );
		add_action( 'wp_head', 'feed_links', 30 );
	}
	
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'index_rel_link' );
	remove_action( 'wp_head', 'wp_generator' );
}
add_action( 'after_setup_theme', 'rs_optimize_head' );


// Disable emoji
function rs_disable_emoji() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'tiny_mce_plugins', 'rs_disable_emoji_in_tinymce' );
}
function rs_disable_emoji_in_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) return array_diff( $plugins, array( 'wpemoji' ) );
	else return array();
}
add_action( 'init', 'rs_disable_emoji' );


// Render shortcodes in widget content
function rs_allow_shortcodes_in_widgets() {
	add_filter( 'widget_text', 'shortcode_unautop' );
	add_filter( 'widget_text', 'do_shortcode' );
}
add_action( 'init', 'rs_allow_shortcodes_in_widgets' );


// Add classes to the body tag
function rs_more_body_classes( $classes ) {
	if ( is_front_page() ) $classes[] = 'front-page';
	
	// Display some classes regarding the user's role
	$user = wp_get_current_user();
	
	if ( $user && !empty($user->roles) ) {
		foreach( $user->roles as $role ) {
			$classes[] = 'user-role-'. $role;
		}
		$classes[] = 'logged-in';
	}else{
		$classes[] = 'user-role-none not-logged-in';
	}
	
	return $classes;
}
add_filter( 'body_class', 'rs_more_body_classes' );