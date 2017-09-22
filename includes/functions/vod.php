<?php

if ( !defined('ABSPATH') ) exit;

function register_vod_post_type() {
	
	$labels = array(
		'name'                  => 'Videos On Demand',
		'singular_name'         => 'Video On Demand',
		'menu_name'             => 'VODs',
		'name_admin_bar'        => 'Video On Demand',
		'archives'              => 'VOD Archives',
		'attributes'            => 'VOD Attributes',
		'parent_item_colon'     => 'Parent VOD:',
		'all_items'             => 'All Videos',
		'add_new_item'          => 'Add New VOD',
		'add_new'               => 'Add New',
		'new_item'              => 'New VOD',
		'edit_item'             => 'Edit VOD',
		'update_item'           => 'Update VOD',
		'view_item'             => 'View VOD',
		'view_items'            => 'View VODs',
		'search_items'          => 'Search VOD',
		'not_found'             => 'Not found',
		'not_found_in_trash'    => 'Not found in Trash',
		'featured_image'        => 'Featured Image',
		'set_featured_image'    => 'Set featured image',
		'remove_featured_image' => 'Remove featured image',
		'use_featured_image'    => 'Use as featured image',
		'insert_into_item'      => 'Insert into item',
		'uploaded_to_this_item' => 'Uploaded to this item',
		'items_list'            => 'Items list',
		'items_list_navigation' => 'Items list navigation',
		'filter_items_list'     => 'Filter items list',
	);
	
	$args = array(
		'label'                 => 'VODs',
		'description'           => 'Video On Demand',
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'author', 'thumbnail', 'revisions', ),
		'taxonomies'            => array( 'vod_cat' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 20,
		'menu_icon'             => 'dashicons-video-alt2',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	
	register_post_type( 'vod', $args );

	$labels = array(
		'name'                       => 'Series',
		'singular_name'              => 'Series',
		'menu_name'                  => 'Series',
		'all_items'                  => 'All Series',
		'parent_item'                => 'Parent Series',
		'parent_item_colon'          => 'Parent Series:',
		'new_item_name'              => 'New Series Name',
		'add_new_item'               => 'Add Series',
		'edit_item'                  => 'Edit Series',
		'update_item'                => 'Update Series',
		'view_item'                  => 'View Series',
		'separate_items_with_commas' => 'Separate items with commas',
		'add_or_remove_items'        => 'Add or remove items',
		'choose_from_most_used'      => 'Choose from the most used',
		'popular_items'              => 'Popular Series',
		'search_items'               => 'Search Series',
		'not_found'                  => 'Not Found',
		'no_terms'                   => 'No items',
		'items_list'                 => 'Items list',
		'items_list_navigation'      => 'Items list navigation',
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'vod_series', array( 'vod' ), $args );
	
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Add from Youtube',
		'menu_title' 	=> 'Add from Youtube',
		'parent_slug' 	=> 'edit.php?post_type=vod',
	));
}
add_action( 'init', 'register_vod_post_type', 8 );

function ah_quick_add_youtube_video($post_id) {
	if ( $post_id != 'options' ) return;
	
	$title = get_field( 'vod_quick_add_title', 'options' );
	if ( !$title ) return;
	
	$description = get_field( 'vod_quick_add_description', 'options' );
	$image_url = get_field( 'vod_quick_add_image_url', 'options' );
	$embed_code = get_field( 'vod_quick_add_embed_code', 'options' );
	
	$free_video_term_id = 37;
	
	$args = array(
		'post_title' => $title,
	    'post_content' => $description,
	    'post_status' => 'publish',
	    'post_type' => 'vod',
	);
	
	$post_id = wp_insert_post( $args );
	
	// Remove everything that was saved so this can run again in the future.
	delete_field( 'vod_quick_add_video_url', 'options' );
	delete_field( 'vod_quick_add_title', 'options' );
	delete_field( 'vod_quick_add_description', 'options' );
	delete_field( 'vod_quick_add_image_url', 'options' );
	delete_field( 'vod_quick_add_embed_code', 'options' );
	
	if ( $post_id && !is_wp_error($post_id) ) {
		// success
		
		// custom fields
		update_field( 'free_or_paid', 'Free', $post_id );
		update_field( 'trailer_embed_code', '', $post_id );
		update_field( 'embed_code', $embed_code, $post_id );
		
		// add the "Free Videos" term
		wp_set_object_terms( $post_id, $free_video_term_id, 'vod_series' );
		
		// add the image
		if ( $image_url ) {
			rs_upload_image_url_to_media( $image_url, $post_id, $title );
		}
		
		wp_redirect( admin_url('edit.php?post_type=vod&page=acf-options-add-from-youtube&added_from_youtube=' . $post_id) );
		exit;
	}else{
		if ( is_wp_error($post_id) ) {
			wp_die( $post_id );
			exit;
		}else{
			wp_die( 'Failed to insert new post' );
			exit;
		}
	}
}
add_action( 'acf/save_post', 'ah_quick_add_youtube_video', 20 );


function ah_notify_quick_added_video() {
	$video_id = isset($_GET['added_from_youtube']) ? (int) $_GET['added_from_youtube'] : false;
	if ( !$video_id ) return;
	if ( !get_post($video_id) ) return;
	
	?>
	<div class="notice notice-success">
		<p><strong>Video added successfully</strong></p>
		<p>The video &ldquo;<?php echo esc_html( get_the_title( $video_id) ); ?>&rdquo; has been added. <a href="<?php echo esc_attr( get_edit_post_link($video_id) ); ?>" target="_blank">Go to video</a></p>
	</div>
	<?php
}
add_action( 'admin_notices', 'ah_notify_quick_added_video' );