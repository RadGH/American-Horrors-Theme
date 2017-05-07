<?php
/**
 * Adds combined category/date filters for post archives
 * Could be made into a widget:)
 * Changing a category clears month selection; but changing the month just filters within the current category
 * Associated JS should look something like this:

function init_news_filters() {
	var $dropdown = jQuery("#news-filters select");
	if ( !$dropdown.length ) return;

	$dropdown.change(function () {
		if ( jQuery(this).val() ) {
			location.href = jQuery(this).val();
		}
	});
}

 **/
?>

<div id="news-filters">
	<div class="inside">

		<?php
		global $wpdb;
		$year = intval( get_query_var( "year" ) ) ?: null;
		$monthnum = intval( get_query_var( "monthnum" ) ) ?: null;
		$cat = intval( get_query_var( "cat" ) ) ?: null;
		$blogURL = get_permalink( get_option( 'page_for_posts' ) );

		if ( $categories = get_categories() ):
			?>
			<select name="category-filter">
				<option value="<?php echo $blogURL; ?>">Category</option>
				<?php
				foreach ( $categories as $category ) {
					$url = esc_url( add_query_arg( array(
						"cat"      => $category->term_id,
					), get_home_url() ) );
					echo '<option value="', $url, '"', selected( $category->term_id, $cat, false ), '>', $category->name, '</option>';
				}
				?>
			</select>
		<?php endif; ?>

		<?php
		$sql = "
    SELECT
        YEAR( p.post_date )  AS year,
        MONTH( p.post_date ) AS month,
        count( p.ID )        AS posts
    FROM {$wpdb->posts} p
    %s
    WHERE
        p.post_type = 'post'
        AND p.post_status = 'publish'
        %s
    GROUP BY
        YEAR( p.post_date ),
        MONTH( p.post_date )
    ORDER BY p.post_date
    ASC
";
		if ( $cat ) {
			$sql = sprintf( $sql, "
		LEFT JOIN {$wpdb->term_relationships} rel ON rel.object_id = p.ID
		LEFT JOIN {$wpdb->term_taxonomy} tax ON tax.term_taxonomy_id = rel.term_taxonomy_id
		LEFT JOIN {$wpdb->terms} t ON t.term_id = tax.term_id", "AND t.term_id = {$cat}" );
		}else{
			$sql = sprintf( $sql, "", "" );
		}

		if ( $total_page_dates = $wpdb->get_results( $sql ) ) :
			?>
			<select name="date-filter">
				<option value="<?php echo esc_url( add_query_arg( "cat", $cat, $blogURL ) ); ?>">Date Published</option>
				<?php
				foreach ( $total_page_dates as $date ) {
					$url = esc_url( add_query_arg( array(
						"year"     => $date->year,
						"monthnum" => $date->month,
						"cat"      => $cat,
					), get_home_url() ) );
					$monthname = date( 'F', mktime( 0, 0, 0, $date->month, 10 ) );
					echo '<option value="', $url, '"', selected( $date->year . $date->month, $year . $monthnum, false ), '>', $monthname, ' ', $date->year, ' (', $date->posts, ')</option>';
				}
				?>
			</select>
		<?php endif; ?>

	</div>
</div>