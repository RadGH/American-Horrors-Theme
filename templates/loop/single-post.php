<article <?php post_class( 'loop-single' ); ?>>
	
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
		
		<?php theme_posted_by_line(); ?>
	</header>

	<div class="loop-body">

		<?php
		/*
		?>
		<?php if ( has_post_thumbnail() ) { ?>
			<div class="loop-image">
				<?php the_post_thumbnail( 'large' ); ?>
			</div>
		<?php } ?>
		*/
		?>

		<div class="loop-content">
			<?php the_content(); ?>
		</div>

	</div>
	
	<footer class="loop-footer">
		<div class="loop-meta">
			<?php if(get_the_category()) { ?><p>Categorized under: <?php the_category( ', ' ); ?></p><?php } ?>
			<?php echo get_the_tag_list('<p>Tags: ',', ','</p>'); ?>
		</div>
	</footer>

</article>