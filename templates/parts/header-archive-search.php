<?php

if ( is_page() ) {
	// We're on a static page using the search template
	$search_title = get_the_title();
	$search_content = get_the_content();
}elseif ( !isset( $_REQUEST['s'] ) || $_REQUEST['s'] == '' ){
	// User was not searching or user did not provide a searchable query

	// Get the default search page if one is using the search template (this file)
	$args = array(
		'post_type'      => 'page',
		'meta_key'       => '_wp_page_template',
		'meta_value'     => 'search.php',
		'posts_per_page' => '1',
	);

	$page_query = new WP_Query( $args );

	if ( $page_query->have_posts() ) {
		$search_title = $page_query->posts[0]->post_title;
		$search_content = $page_query->posts[0]->post_content;
	}else{
		$search_title = "Search";
		$search_content = "Enter your search terms below:" . get_search_form( false );
	}
	wp_reset_postdata();
}elseif ( !have_posts() ){
	// The user was searching for something but no results were found
	$search_title = 'No results found';
	$search_content = 'Your search for <em>"' . get_search_query() . '"</em> returned no results.';
}else{
	// The user found something in their search
	$search_title = sprintf( 'Found %s result%s', $wp_query->found_posts, ( $wp_query->found_posts == 1 ) ? '' : 's' );
	$search_content = sprintf( 'You searched for "%s".', get_search_query() );
}

?>

<header class="loop-header">
	<?php if ( function_exists( 'yoast_breadcrumb' ) ) {
		yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );
	} ?>
	<h1 class="loop-title"><?php echo $search_title; ?></h1>
	<?php echo wpautop( do_shortcode( $search_content ) ); ?>
</header>