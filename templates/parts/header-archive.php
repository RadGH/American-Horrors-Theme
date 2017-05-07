<header class="loop-header">
	<?php if ( function_exists( 'yoast_breadcrumb' ) ) {
		yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );
	} ?>
	<h1 class="loop-title"><?php the_archive_title(); ?></h1>

	<?php
	// On author archives, output the author bio
	if ( is_author() && $bio = get_the_author_meta( 'description' ) ) {
		echo '<div class="author-bio">';
		echo wpautop( $bio );
		echo '</div>';
	}
	?>
</header>