<?php
$message = "No matching posts found.";
if ( is_home() )         $message = "No posts have been published yet.";
elseif ( is_category() ) $message = "No posts have been published in this category.";
elseif ( is_search() )   $message = "No posts match your search criteria.";
elseif ( is_day() )      $message = "No posts have been published on this day.";
elseif ( is_month() )    $message = "No posts have been published in this month.";
elseif ( is_year() )     $message = "No posts have been published in this year.";
?>

<article <?php post_class('loop-archive loop-empty'); ?>>

	<div class="loop-body">

		<div class="loop-content">
			<?php echo wpautop( $message ); ?>
		</div>

	</div>

</article>