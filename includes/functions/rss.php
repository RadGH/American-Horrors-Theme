<?php

// Add featured image to rss items as a media tag
function rs_rss_feature_image_to_rss( $content ) {
	$image_id = get_post_thumbnail_id();
	$image = wp_get_attachment_image_src( $image_id, 'rssfeed' );

	if ( $image ) {
		$type = 'image/jpg';
		if ( substr($image[0], -3) == 'png' ) $type = 'image/png';

		echo "\t";
		echo sprintf(
			'<media:content url="%s" medium="image" width="%s" height="%s" type="%s" />',
			esc_attr($image[0]),
			esc_attr($image[1]),
			esc_attr($image[2]),
			esc_attr($type)
		);
		echo "\n";
	}
}
add_action( 'rss2_item', 'rs_rss_feature_image_to_rss' );

// Add media namespace to RSS
function rs_rss_image_ns_to_rss() {
	echo 'xmlns:media="http://search.yahoo.com/mrss/"' . "\n\t";
}
add_action( 'rss2_ns', 'rs_rss_image_ns_to_rss' );