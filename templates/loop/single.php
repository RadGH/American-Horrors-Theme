<article <?php post_class('loop-single'); ?>>

	<header class="loop-header">
		<?php
		if ( function_exists( 'yoast_breadcrumb' ) ) {
			yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );
		}
		the_title( '<h1 class="loop-title">', '</h1>' );
		if ( $subtitle = get_field( 'subtitle', get_the_ID() ) ) {
			printf( '<h2 class="loop-subtitle">%s</h2>', $subtitle );
		}
		?>
	</header>

	<div class="loop-body">

		<?php if ( has_post_thumbnail() ) { ?>
			<div class="loop-image">
				<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( ); ?></a>
			</div>
		<?php } ?>

		<div class="loop-content">
			<?php the_content(); ?>
		</div>

	</div>

</article>