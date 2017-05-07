<article <?php post_class('loop-single loop-404'); ?>>

	<header class="loop-header">
		<?php if ( function_exists( 'yoast_breadcrumb' ) ) {
			yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );
		} ?>
		<h1 class="loop-title">Page Not Found</h1>
	</header>

	<div class="loop-body">

		<div class="loop-content">
			<p>Sorry, the page you have requested could not be found.</p>
		</div>

		<div class="loop-search">
			<h2>Search</h2>
			<?php get_search_form(); ?>
		</div>

	</div>

</article>