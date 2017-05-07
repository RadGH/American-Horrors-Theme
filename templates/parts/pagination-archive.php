<?php if($wp_query->max_num_pages>1): ?>
	<div class="loop-pagination">
		<div class="loop-prev">
			<?php previous_posts_link( '« Back' ); ?>
		</div>
		<div class="loop-current">
			<?php
			$current = get_query_var( 'paged' ) ? : 1;
			$total = $wp_query->max_num_pages;
			echo 'Page ', $current, ' of ', $total;
			?>
		</div>
		<div class="loop-next">
			<?php next_posts_link( 'Next »' ); ?>
		</div>
	</div>
<?php endif; ?>