<?php
get_header();
?>
	
	<div class="site-content  <?php echo apply_filters( "sidebar_enabled", true ) ? 'with-sidebar' : 'no-sidebar'; ?>">
		<div class="inside">
			<main class="main-content">
				<?php
	
				// Single post/pages and non-empty archives
				if ( have_posts() ) :
					if ( is_singular() ) : the_post();
						get_template_part( 'templates/loop/single', get_post_type() );
					else:
						get_template_part( 'templates/parts/header-archive', get_post_type() );
						while ( have_posts() ) : the_post();
							get_template_part( 'templates/loop/archive', get_post_type() );
						endwhile;
						get_template_part( 'templates/parts/pagination-archive', get_post_type() );
					endif;
	
				// Empty archives
				else: the_post();
					get_template_part( 'templates/parts/header-archive', get_post_type() );
					get_template_part( 'templates/parts/empty-archive', get_post_type() );
				endif;
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