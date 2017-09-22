<?php

if ( !defined('ABSPATH') ) exit;

function register_paid_video_post_type() {
	
	$labels = array(
		'name'                  => 'Paid Videos',
		'singular_name'         => 'Paid Video',
		'menu_name'             => 'Paid Videos',
		'name_admin_bar'        => 'Paid Video',
		'archives'              => 'Paid Video Archives',
		'attributes'            => 'Paid Video Attributes',
		'parent_item_colon'     => 'Parent Paid Video:',
		'all_items'             => 'All Videos',
		'add_new_item'          => 'Add New Paid Video',
		'add_new'               => 'Add New',
		'new_item'              => 'New Paid Video',
		'edit_item'             => 'Edit Paid Video',
		'update_item'           => 'Update Paid Video',
		'view_item'             => 'View Paid Video',
		'view_items'            => 'View Paid Videos',
		'search_items'          => 'Search Paid Video',
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
		'label'                 => 'Paid Videos',
		'description'           => 'Paid Video',
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
	
	register_post_type( 'paid_video', $args );
}
add_action( 'init', 'register_paid_video_post_type', 8 );