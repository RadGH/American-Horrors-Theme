<?php
get_header();

the_post();

$free = get_field('free_or_paid', $post_id) == 'free';

$trailer_embed = false;
if ( !$free ) $trailer_embed = get_field( 'trailer_embed_code', $post_id );

$embed = theme_get_vod_embed( $post_id );
?>

	<?php if ( $trailer_embed ) { ?>
	<div class="video-player-section video-player-single vod-player vod-trailer">
		<div class="inside small">
			<h2 class="trailer-embed-title full-video-title">Watch the trailer for FREE</h2>
			
			<div class="vod-embed-code"><?php echo $trailer_embed; ?></div>
		</div>
	</div>
	<?php } ?>

	<div class="video-player-section video-player-single vod-player vod-full-video">
		<div class="inside small">
			<?php if ( $trailer_embed ) { ?>
			<h2 class="trailer-embed-title full-video-title">Watch the full video online</h2>
			<?php } ?>
			
			<?php echo $embed; ?>
		</div>
	</div>
	
	<div class="site-content bloodsplat-top-right <?php echo apply_filters( "sidebar_enabled", true ) ? 'with-sidebar' : 'no-sidebar'; ?>">
		<div class="inside">
			
			<main class="main-content">
				<?php
					get_template_part( 'templates/loop/single', get_post_type() );
				?>
			</main>
	
			<?php
			if ( apply_filters( "sidebar_enabled", true ) ) {
				get_sidebar();
			}
			?>
	
		</div>
	</div>

<?php
get_footer();