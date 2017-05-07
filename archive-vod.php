<?php
get_header();
?>
	
	<div class="site-content no-sidebar">
		<div class="inside">
			<main class="main-content">
				
				<header class="loop-header">
					<?php
					if ( function_exists( 'yoast_breadcrumb' ) ) {
						yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );
					}
					?>
					<h1 class="loop-title">Videos On Demand</h1>
					<h2 class="loop-subtitle">Feature Films, Shorts &amp; Original TV Series</h2>
				</header>
				
				<div class="grid grid-3col vod-item-grid">
					<?php
					while( have_posts() ): the_post();
						theme_vod_grid_item( get_the_ID() );
					endwhile;
					?>
				</div>
				
			</main>
	
		</div>
	</div>

<?php
get_footer();