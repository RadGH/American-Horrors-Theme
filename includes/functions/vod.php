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
}
add_action( 'init', 'register_vod_post_type', 8 );