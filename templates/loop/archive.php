<article <?php post_class('loop-archive'); ?>>

	<header class="loop-header">
		<?php the_title( '<h2 class="loop-title"><a href="' . get_permalink() . '" rel="bookmark">', '</a></h2>' ); ?>

		<div class="loop-meta">
			<?php theme_posted_by_line(); ?>
		</div>
	</header>

	<div class="loop-body">

		<?php if ( has_post_thumbnail() ) { ?>
			<div class="loop-thumbnail">
				<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'thumbnail' ); ?></a>
			</div>
		<?php } ?>

		<div class="loop-summary">
			<?php the_excerpt(); ?>
		</div>

	</div>

</article>
