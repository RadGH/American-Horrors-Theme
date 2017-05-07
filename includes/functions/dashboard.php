<?php

// ADMIN: custom css
function theme_enqueue_admin_scripts() {
	wp_enqueue_style( 'theme-admin', get_template_directory_uri() . '/includes/assets/admin.css', array(), false );
}

add_action( 'admin_enqueue_scripts', 'theme_enqueue_admin_scripts', 30 );

// LOGIN: custom css
function theme_enqueue_login_scripts() {
	wp_enqueue_style( 'theme-login', get_template_directory_uri() . '/includes/assets/login.css', array(), false );
}

add_action( 'login_enqueue_scripts', 'theme_enqueue_login_scripts', 30 );


// ADMIN: custom admin bar css on front end
function custom_admin_bar_css() { ?>
	<style type="text/css" media="screen">
		html {
			margin-top: 32px;
			height: calc(100% - 32px);
		}
		
		@media ( max-width: 782px ) {
			html {
				margin-top: 46px;
				height: calc(100% - 46px);
			}
		}
	</style>
	<?php
}

add_theme_support( 'admin-bar', array( 'callback' => 'custom_admin_bar_css' ) );


// DASHBOARD: Hide default welcome dashboard message
remove_action( 'welcome_panel', 'wp_welcome_panel' );


// DASHBOARD: Hide widgets from the dashboard screen
function rs_tweaks_clean_dashboard() {
	remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_primary', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_secondary', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
	
	// Allowed
	// remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
	// remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
	// remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
}

add_action( 'admin_init', 'rs_tweaks_clean_dashboard' );


// ADMIN FOOTER: Customize wp footer text
function rs_custom_wp_footer() {
	?>
	<strong>&copy; <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?></strong> &ndash; Built by <a href="http://radleysustaire.com/">Radley Sustaire</a>, proudly powered by <a href="http://wordpress.org/">WordPress</a>.
	<?php
}

add_filter( 'admin_footer_text', 'rs_custom_wp_footer' );


// ADMIN BAR: removes unnecessary links from the admin bar: WordPress logo, comments, themes, customize
function rs_simplify_admin_bar() {
	global $wp_admin_bar;
	
	if ( !$wp_admin_bar || !method_exists( $wp_admin_bar, 'remove_node' ) ) {
		return;
	}
	
	$wp_admin_bar->remove_node( 'wp-logo' );
	$wp_admin_bar->remove_node( 'comments' );
	$wp_admin_bar->remove_node( 'themes' );
	$wp_admin_bar->remove_node( 'customize' );
}

add_action( 'wp_before_admin_bar_render', 'rs_simplify_admin_bar' );


// EDITOR: Hides editor for Front Page template (see: http://snipplr.com/view.php?codeview&id=61412)
function hide_editor() {
	if ( !isset( $_GET['post'] ) && !isset( $_POST['post_ID'] ) ) {
		return;
	}
	
	$post_id = isset( $_GET['post'] ) ? $_GET['post'] : $_POST['post_ID'];
	
	// Get the name of the Page Template file.
	$template_file = get_post_meta( $post_id, '_wp_page_template', true );
	
	if ( $template_file == 'page-templates/front-page.php' ) { // edit the template name
		remove_post_type_support( 'page', 'editor' );
	}
}

add_action( 'admin_init', 'hide_editor' );


// EDITOR: adds style.css to the wysiwyg editor so it gets custom theme styles
function rs_add_editor_styles() {
	add_editor_style( get_template_directory_uri() . '/style.css' );
}

add_action( 'admin_init', 'rs_add_editor_styles' );


// EDITOR: styleselect is the "Formats" dropdown, which has the button option seen below
function rs_custom_visual_editor_styles( $buttons ) {
	$first = array_shift( $buttons );
	array_unshift( $buttons, 'styleselect' );
	array_unshift( $buttons, $first );
	
	return $buttons;
}

add_filter( 'mce_buttons_2', 'rs_custom_visual_editor_styles' );


// EDITOR: Adds a button option under "Formats", which must be assigned to a link
function rs_custom_visual_editor_style_formats( $init_array ) {
	// Define the style_formats array
	$style_formats = array(
		
		array(
			'title'    => 'Button',
			'selector' => 'a',
			'classes'  => 'button',
		),
	
	);
	
	// Insert the array, JSON ENCODED, into 'style_formats'
	$init_array['style_formats'] = json_encode( $style_formats );
	
	return $init_array;
	
}

add_filter( 'tiny_mce_before_init', 'rs_custom_visual_editor_style_formats' );


// LOGIN: custom css
function login_page_custom_css() {
	if ( $logo_id = get_field( 'logo', 'options', false ) ) {
		if ( $src = wp_get_attachment_image_src( $logo_id, 'full' ) ) {
			?>
			<style type="text/css">
				body.login #login h1 a {
					background: url(<?php echo $src[0]; ?>) no-repeat center;
					width: <?php echo $src[1]; ?>px;
					height: <?php echo $src[2]; ?>px;
					background-size: contain;
					
					position: relative;
					transform: translateX(-50%);
					left: 50%;
				}
			</style>
			<?php
		}
	}
}

add_action( 'login_head', 'login_page_custom_css' );

// LOGIN: custom url for logo
function custom_logo_url() {
	return get_bloginfo( 'url' );
}

add_filter( 'login_headerurl', 'custom_logo_url' );

// LOGIN: page title
function custom_login_title() {
	return get_bloginfo( 'title' );
}

add_filter( 'login_headertitle', 'custom_login_title' );