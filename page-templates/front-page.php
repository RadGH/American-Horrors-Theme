<?php
/*
Template Name: Front Page
*/

// Note: by default, the WYSIWYG editor is hidden on pages that use this template; see hide_editor() in includes/functions/dashboard.php

add_filter( "sidebar_enabled", "__return_false" ); // disable sidebar

$roku_section = array(
	'title' => theme_asterisks_to_strong_tag( get_field( 'roku_title' ) ),
	'content' => get_field( 'roku_content' ),
	'button_text' => get_field( 'roku_button_text' ),
	'button_url' => get_field( 'roku_button_url' ),
);

$news_section = array(
	'title' => theme_asterisks_to_strong_tag( get_field( 'news_title' ) ),
);

$vod_section = array(
	'title' => theme_asterisks_to_strong_tag( get_field( 'vod_title' ) ),
	'view_all' => get_field( 'vod_view_all' ),
);

$gfm_section = array(
	'title' => theme_asterisks_to_strong_tag( get_field( 'gfm_title' ) ),
	'content' => get_field( 'gfm_content' ),
	'button_text' => get_field( 'gfm_button_text' ),
	'button_url' => get_field( 'gfm_button_url' ),
);

$filmon_section = array(
	'title' => theme_asterisks_to_strong_tag( get_field( 'filmon_title' ) ),
	'content' => get_field( 'filmon_content' ),
	'button_text' => get_field( 'filmon_button_text' ),
	'button_url' => get_field( 'filmon_button_url' ),
);

get_header();
?>

<section class="fp-roku video-player-section">
	<div class="inside">
	
		<h1 class="page-title section-title roku-title"><?php echo $roku_section['title']; ?></h1>
		
		<div class="grid grid-video-cols">
			
			<div class="cell video">
				<?php get_template_part( 'templates/parts/embed-roku' ); ?>
			</div>
			
			<div class="cell content">
				<div class="post-content">
					<?php echo $roku_section['content']; ?>
				</div>
				
				<div class="post-button">
					 <?php echo theme_render_button( $roku_section['button_url'], $roku_section['button_text'] ); ?>
					 
					 <?php echo theme_render_button( 'https://channelstore.roku.com/details/70275/american-horrors', 'Add channel to ROKU', array( 'button', 'button-transparent' ), true ); ?>
				</div>
			</div>
			
		</div>
	
	</div>
</section>

<?php
$args = array(
	'post_type' => 'post',
	'posts_per_page' => 3,
);

$news_posts = new WP_Query($args);

if ( $news_posts->have_posts() ) {
	
	?>
	<section class="fp-news">
		<div class="inside">
		
			<h2 class="section-title news-title"><?php echo $news_section['title']; ?></h2>
			
			<div class="grid grid-3col">
				<?php
				while( $news_posts->have_posts() ): $news_posts->the_post();
					$month = get_the_time( 'F' ); // January
					$day = get_the_time( 'j' ); // 1
					$year = get_the_time( 'Y' ); // 2017
					
					if ( $image_id = get_post_thumbnail_id() ) {
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
					?>
					<div <?php post_class( array('cell') ); ?>>
						<div class="cell-inner">
						
							<h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							
							<div class="post-date">
								<div class="month"><?php echo $month; ?></div>
								
								<div class="day"><?php echo $day; ?></div>
								
								<?php if ( date('Y') != $year ) { ?>
									<div class="year"><?php echo $year; ?></div>
								<?php } ?>
							</div>
							
							<div class="post-image">
								<img src="<?php echo esc_attr($image[0]); ?>" alt="<?php echo esc_attr($alt); ?>" width="<?php echo esc_attr($image[1]); ?>" height="<?php echo esc_attr($image[2]); ?>">
							</div>
						
						</div>
					</div>
					<?php
				
				endwhile;
				?>
			</div>
			
			<div class="read-more">
				<a href="<?php echo esc_attr(get_post_type_archive_link('post')); ?>">Read more from <?php echo $news_section['title']; ?></a>
			</div>
		
		</div>
	</section>
	<?php
}
?>

<?php
$args = array(
	'post_type' => 'vod',
	'posts_per_page' => 12,
);

$vod_posts = new WP_Query($args);

if ( $vod_posts->have_posts() ) {
	?>
	<section class="fp-vod">
		<div class="inside">
		
			<h2 class="section-title vod-title">
				<?php echo $vod_section['title']; ?>
				<a href="<?php echo esc_attr( get_post_type_archive_link('vod') ); ?>" class="view-all"><?php echo $vod_section['view_all']; ?></a>
			</h2>
			
			<div class="grid grid-3col vod-item-grid">
				<?php
				while( $vod_posts->have_posts() ): $vod_posts->the_post();
					theme_vod_grid_item( get_the_ID() );
				endwhile;
				?>
			</div>
		
		</div>
	</section>
	<?php
}
?>

<section class="fp-gfm">
	<div class="inside">
		
		<h2 class="section-title gfm-title"><?php echo $gfm_section['title']; ?></h2>
		
		<div class="grid grid-2col">
			
			<div class="cell content">
				<div class="gfm-content">
					<?php echo $gfm_section['content']; ?>
				</div>
				
				<div class="gfm-button">
					<?php echo theme_render_button( $gfm_section['button_url'], $gfm_section['button_text'], array('button'), true ); ?>
				</div>
			</div>
		
			<div class="cell gfm-widget">
				<iframe class='gfm-media-widget' image='1' coinfo='0' width='100%' height='100%' frameborder='0' id='hart-wakas-er-medical-fund'></iframe>
				<script src='//funds.gofundme.com/js/5.0/media-widget.js'></script>
			</div>
			
		</div>
		
	</div>
</section>

<section class="fp-filmon video-player-section">
	<div class="inside">
			
		<h2 class="section-title filmon-title"><?php echo $filmon_section['title']; ?></h2>
		
		<div class="grid grid-video-cols">
			
			<div class="cell video">
				<?php get_template_part( 'templates/parts/embed-classic' ); ?>
			</div>
			
			<div class="cell content">
				<div class="post-content">
					<?php echo $filmon_section['content']; ?>
				</div>
				
				<div class="post-button">
					<?php echo theme_render_button( $filmon_section['button_url'], $filmon_section['button_text'] ); ?>
				</div>
			</div>
		
		</div>
	
	</div>
</section>

<?php
get_footer();