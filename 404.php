<?php
get_header();
?>

	<div class="site-content  <?php echo apply_filters( "sidebar_enabled", true ) ? 'with-sidebar' : 'no-sidebar'; ?>">
		<div class="inside">
			
			<main class="main-content">
				<?php
				// load custom 404 template matching first part of the request URL (note: have_posts() is still true when is_404() is true)
				// *** based on post type's slug, NOT the post type itself, e.g. /blog/* uses 404-blog.php, not 404-post.php
				$slug = '';
				$url = parse_url( $_SERVER['REQUEST_URI'] );
				if ( isset( $url['path'] ) ) {
					$slug = sanitize_title_with_dashes( explode( "/", $url['path'] )[1] );
				}
				
				get_template_part( 'templates/loop/404', $slug );
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