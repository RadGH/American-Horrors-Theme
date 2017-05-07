<?php
/* Template Name: Search */
get_header();
?>
	
	<div class="site-content  <?php echo apply_filters( "sidebar_enabled", true ) ? 'with-sidebar' : 'no-sidebar'; ?>">
		<div class="inside">
			<main class="main-content">
				<?php
	
				get_template_part( 'templates/parts/header-archive', 'search' );
				if ( have_posts() ) :
					if ( get_search_query() != '' ) :
	
						// priority-ordered list of templates (so we can fall back to an archive template if no search template found)
						$templates = array(
							'templates/loop/search-' . get_post_type() . '.php',
							'templates/loop/search.php',
							'templates/loop/archive-' . get_post_type() . '.php',
							'templates/loop/archive.php',
						);
						// get the best available template
						if ( $template = locate_template( $templates ) ) :
							while ( have_posts() ) : the_post();
								include( $template );
							endwhile;
							get_template_part( 'templates/parts/pagination-archive', 'search' );
						else:
							echo '<p>No valid template found to display output.</p>';
						endif;
					endif;
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