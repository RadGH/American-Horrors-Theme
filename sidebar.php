<?php
$area = 'sidebar';

if ( is_home() || is_post_type_archive('post')|| is_category() || is_tax() || get_post_type() == 'post' ) $area = 'blog';
elseif ( is_front_page() ) $area = 'front-page';
elseif ( is_search() ) $area = 'search';
elseif ( class_exists('WooCommerce') ) {
	if ( is_woocommerce() ) $area = 'store';

	// Checkout and cart page
	if ( get_the_ID() == get_option('woocommerce_cart_page_id')  || get_the_ID() == get_option('woocommerce_checkout_page_id') )
		$area = 'checkout';

}
// If a specific sidebar is empty, fall back to the default
if ( $area !== 'sidebar' && !is_active_sidebar($area) ) $area = 'sidebar';

// ----------------------------------------------------------------

if ( is_active_sidebar( $area ) ) {
	$classes = array( 'side-content sidebar' );
	if ( $area == 'sidebar' ) $classes[] = 'sidebar-default';
	else $classes[] = 'sidebar-' . $area;
	?>
	<aside class="<?php echo esc_attr(implode(' ', $classes)); ?>">
		<?php dynamic_sidebar( $area ); ?>
	</aside>
	<?php
}
