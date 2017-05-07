<?php
get_header();

the_post();

$embed = theme_get_vod_embed( $post_id );
?>

	<div class="video-player-section video-player-single vod-player">
		<div class="inside small">
			<?php echo $embed; ?>
		</div>
	</div>
	
	<div class="site-content <?php echo apply_filters( "sidebar_enabled", true ) ? 'with-sidebar' : 'no-sidebar'; ?>">
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