<?php

// Only continue if Yoast is active
$active_plugins = get_option("active_plugins");
if( !in_array("wordpress-seo/wp-seo.php", $active_plugins) ) return;


// fix breadcrumbs for w3c validator
function bybe_crumb_v_fix ($link_output) {
	$link_output = str_replace(' xmlns:v="http://rdf.data-vocabulary.org/#"', ' ', $link_output);
	return $link_output;
}
add_filter ('wpseo_breadcrumb_output','bybe_crumb_v_fix');