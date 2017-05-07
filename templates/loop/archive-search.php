<?php

$templates = array(
	'templates/loop/search-' . get_post_type() . '.php',
	'templates/loop/search.php',
	'templates/loop/archive-' . get_post_type() . '.php',
	'templates/loop/archive.php',
);

// Pick the first template from the above array and load it.
// This allows us to fall back to an archive template if no search template is available.
if ( $locate_search = locate_template( $templates ) ) {
	include( $locate_search );
}