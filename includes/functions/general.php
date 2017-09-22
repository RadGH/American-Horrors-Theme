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



/**
 * Upload an image from the internet to the media section. Optionally assigns as featured image to a post.
 * Based on example from: https://codex.wordpress.org/Function_Reference/wp_handle_sideload
 *
 * @param $image_url
 * @param integer $post_id
 * @param null $custom_filename
 *
 * @return bool
 */
function rs_upload_image_url_to_media( $image_url, $post_id = 0, $custom_filename = null ) {
	// Gives us access to the download_url() and wp_handle_sideload() functions
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	
	$timeout_seconds = 5;
	
	// Download file to temp dir
	$temp_file = download_url( $image_url, $timeout_seconds );
	
	if ( !is_wp_error( $temp_file ) ) {
		
		$extension = pathinfo( $image_url, PATHINFO_EXTENSION );
		if ( !$extension ) $extension = 'jpg';
		
		// Array based on $_FILE as seen in PHP file uploads
		$file = array(
			'name'     => basename($image_url), // ex: wp-header-logo.png
			'type'     => 'image/' . strtolower($extension),
			'tmp_name' => $temp_file,
			'error'    => 0,
			'size'     => filesize($temp_file),
		);
		
		if ( $custom_filename !== null ) {
			$file['name'] = sanitize_title_with_dashes( $custom_filename ) . '.' . $extension;
		}
		
		$overrides = array(
			// Tells WordPress to not look for the POST form
			// fields that would normally be present as
			// we downloaded the file from a remote server, so there
			// will be no form fields
			// Default is true
			'test_form' => false,
			
			// Setting this to false lets WordPress allow empty files, not recommended
			// Default is true
			'test_size' => true,
		);
		
		// Move the temporary file into the uploads directory
		$results = wp_handle_sideload( $file, $overrides );
		
		if ( !empty( $results['error'] ) ) {
			// Insert any error handling here
			ob_start();
			var_dump($results['error']);
			$err = ob_get_clean();
			wp_die('<h2>Error uploading remote file:</h2><pre>' . esc_html($err) . '</pre>');
			exit;
		} else {
			// Upload success, attach to media library
			
			// Full path to file
			$filename = $results['file'];
			
			// Check the type of file. We'll use this as the 'post_mime_type'.
			$filetype = wp_check_filetype( basename( $filename ), null );
			
			// Get the path to the upload directory.
			$wp_upload_dir = wp_upload_dir();
			
			// Prepare an array of post data for the attachment.
			$attachment = array(
				'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ),
				'post_mime_type' => $filetype['type'],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
				'post_content'   => '',
				'post_status'    => 'inherit'
			);
			
			// Insert the attachment.
			$attachment_id = wp_insert_attachment( $attachment, $filename, $post_id );
			
			// Generate the metadata for the attachment, and update the database record.
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
			$attach_data = wp_generate_attachment_metadata( $attachment_id, $filename );
			wp_update_attachment_metadata( $attachment_id, $attach_data );
			
			// Set the featured image for the post, if set
			if ( $post_id ) set_post_thumbnail( $post_id, $attachment_id );
			
			return $attachment_id;
		}
		
	}
	
	return false;
	
}