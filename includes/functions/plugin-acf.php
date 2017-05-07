<?php

// Check that ACF is installed & active
function theme_alert_no_acf() {
	?>
	<div class="error">
		<p><strong>Theme Notice:</strong> Advanced Custom Fields PRO is not active. Theme options will not be available. Please install Advanced Custom Fields PRO.</p>
	</div>
	<?php
}

if( !function_exists('acf_add_options_page') ) {
	add_action( 'admin_notices', 'theme_alert_no_acf' );
	return;
}


// ===========================
// Register ACF Options Pages

$theme_settings = acf_add_options_page(array(
	'page_title' => 'Theme Options',
	'menu_title' => 'Theme Options',
	'menu_slug'  => 'theme-options',
	'icon_url'   => 'dashicons-desktop',
	'capability' => 'manage_options',
	'autoload'   => true
));


// ===========================
// Print out tracking codes in header/body that were set in the tracking section
function rs_display_tracking_code_header() {
	echo get_field( 'tracking_scripts_before_head', 'options', false );

	/*
	if ( is_singular() ) {
		$codes = get_field( 'page-tracking-codes', get_the_ID(), true );
		if ( $codes && !empty($codes[0]['head']) ) echo $codes[0]['head'] . "\n";
	}
	*/
}
add_action( 'wp_head', 'rs_display_tracking_code_header', 30 );


function rs_display_tracking_code_body() {
	echo get_field( 'tracking_scripts_before_body', 'options', false );

	
	/*
	if ( is_singular() ) {
		$codes = get_field( 'page-tracking-codes', get_the_ID(), true );
		if ( $codes && !empty($codes[0]['body']) ) echo $codes[0]['body'] . "\n";
	}
	*/
}
add_action( 'wp_footer', 'rs_display_tracking_code_body', 30 );


function acf_fields_do_shortcode($value, $post_id, $field) {
	if ( is_admin() ) return $value;

	return do_shortcode($value);
}
add_filter('acf/load_value/type=textarea', 'acf_fields_do_shortcode', 15, 3);
add_filter('acf/load_value/type=text', 'acf_fields_do_shortcode', 15, 3);