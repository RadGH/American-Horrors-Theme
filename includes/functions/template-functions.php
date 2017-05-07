<?php

if ( !defined( 'ABSPATH' ) ) return;

function theme_posted_by_line() {
	?>
	<p class="posted-date">Posted on <span class="loop-date"><?php the_time( get_option( 'date_format' ) ); ?></span></p>
	<?php
}

function theme_render_button( $button_url, $button_text = 'Learn More', $classes = array('button'), $external = false ) {
	if ( !$button_text ) $button_text = 'Learn More';
	if ( !$button_url ) return '';
	
	if ( !is_array($classes) ) $classes = array();
	
	$attrs = '';
	if ( $external ) $attrs.= ' target="_blank" rel="external"';
	
	return sprintf(
		'<a href="%s" class="%s" %s>%s</a>',
		esc_attr( $button_url ),
		esc_attr( implode( ' ', $classes ) ),
		$attrs,
		esc_html($button_text)
	);
}

function theme_asterisks_to_strong_tag( $string ) {
	if ( empty($string) || !is_string($string) ) return $string;
	
	$index = 1;
	
	while( ($pos = strpos($string, '*')) !== false ) {
		$replace = ($index % 2) ? '<strong>' : '</strong>';
		$string = substr_replace( $string, $replace, $pos, 1 );
		$index++;
	}
	
	// If we opened a tag without closing it, close it at the end.
	if ( $index % 2 === 0 ) $string .= '</strong>';
	
	return $string;
}

function theme_vod_grid_item( $post_id ) {
	$is_episode = get_field( 'episodic', $post_id );
	$excerpt = get_the_excerpt( $post_id );
	
	if ( $is_episode && $video_title = get_field( 'video_title', $post_id ) ) {
		$excerpt = '<span class="video-title">&ldquo;<span>' . esc_html($video_title) . '</span>&rdquo;</span> ' . $excerpt;
	}
	
	$excerpt = wp_trim_words( $excerpt, 20 );
	?>
	<div <?php post_class( array('cell vod-item') ); ?>>
		<div class="cell-inner">
			
			<?php echo theme_get_vod_thumbnail( $post_id ); ?>
			
			<div class="vod-title"><?php
			if ( $is_episode ) {
				$series = get_field( 'series', $post_id );
				$episode = get_field( 'episode_name', $post_id );
				
				$series_name = $series->name;
				if ( $series && $episode ) $series_name .= ':';
				
				if ( $series ) echo '<h2 class="series-title"><a href="', get_term_link( $series ), '">', $series_name, '</a></h2>';
				if ( $episode ) echo '<h3 class="episode-title"><a href="', get_permalink(), '">', $episode, '</a></h2>';
				
			}else{
				echo '<h2 class="full-title"><a href="', get_permalink(), '">', get_the_title(), '</a></h2>';
			}
			?></div>
			
			<?php
			if ( $subtitle = get_field( 'subtitle', get_the_ID() ) ) {
				printf( '<h2 class="loop-subtitle">%s</h2>', $subtitle );
			}
			?>
			
			<div class="post-excerpt"><?php echo $excerpt; ?></div>
		
		</div>
	</div>
	<?php
}

function theme_get_vod_embed( $post_id ) {
	$embed = get_field( 'embed_code', $post_id );
	
	if ( !$embed ) return false;
	
	return '<div class="vod-embed-code">' . $embed . '</div>';
}

function theme_get_vod_thumbnail( $post_id, $link_to_single = null ) {
	// Link to single page if not on the single page already
	if ( $link_to_single === null && (!is_singular('vod') || get_the_ID() !== $post_id) ) {
		$link_to_single = true;
	}
	
	// Make an image embed code using the featured image.
	if ( $image_id = get_post_thumbnail_id( $post_id ) ) {
		$image = wp_get_attachment_image_src( $image_id, 'thumbnail-cropped' );
		$alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
	}else{
		$image = array(
			get_template_directory_uri() . '/includes/images/default-cropped.jpg',
			350,
			300
		);
		$alt = '';
	}
	
	$code = '<div class="vod-thumbnail">';
	
	if ( $link_to_single ) $code .= '<a href="'. esc_attr(get_permalink($post_id)) .'" class="vod-image">';
	else $code .= '<div class="vod-image">';
	
	$code.= '<img src="'. esc_attr($image[0]) . '" alt="'. esc_attr($alt) . '" width="'. esc_attr($image[1]) . '" height="'. esc_attr($image[2]) . '">';
	
	if ( $link_to_single ) $code .= '</a>';
	else $code .= '</div>';
	
	$code .= '</div>';
	
	return $code;
}