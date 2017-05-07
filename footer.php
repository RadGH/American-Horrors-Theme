</div>

<footer class="site-footer">
	<div class="inside">

		<div class="nav-area">
			<?php
			// Primary Menu
			if ( has_nav_menu( 'footer' ) ) {
				$args = array(
					'theme_location' => 'footer',
					'menu'           => 'Footer',
					'container'      => '',
					'container_id'   => '',
					'menu_class'     => '',
					'items_wrap'     => '<ul class="nav-list">%3$s</ul>',
				);

				echo '<nav class="nav-menu nav-primary">';
				wp_nav_menu( $args );
				echo '</nav>';
			}
			?>
			
			<?php
			// Secondary Menu
			if ( has_nav_menu( 'legal' ) ) {
				$args = array(
					'theme_location' => 'legal',
					'menu'           => 'Legal',
					'container'      => '',
					'container_id'   => '',
					'menu_class'     => '',
					'items_wrap'     => '<ul class="nav-list">%3$s</ul>',
				);

				echo '<nav class="nav-menu nav-legal">';
				wp_nav_menu( $args );
				echo '</nav>';
			}
			?>
			
			<?php
			// Header social links
			if ( has_nav_menu('social') ) {
				$args = array(
					'theme_location' => 'social',
					'menu'           => 'Social',
					'container'      => '',
					'container_id'   => '',
					'menu_class'     => '',
					'items_wrap'     => '<ul class="nav-list">%3$s</ul>',
				);
				
				echo '<div class="social-icons">';
				echo '<nav class="nav-menu nav-social">';
				wp_nav_menu($args);
				echo '</nav>';
				echo '</div>';
			}
			?>
		</div>

		<div class="logo-area">
			
			<?php
			// Logo
			if ( $logo_id = get_field( 'logo_footer', 'options', false ) ) {
				if ( $img_tag = wp_get_attachment_image( $logo_id, 'full' ) ) {
					printf( '<a href="%s" title="%s" class="logo">%s</a>', esc_attr( home_url() ), esc_attr( get_bloginfo( 'title' ) ), $img_tag );
				}
			}
			?>

			<div class="copyright">
				<p>Copyright &copy; <?php echo date('Y'); ?> Hart D. Fisher<br>
				All rights reserved.</p>
			</div>
			
		</div>
	</div>
</footer>

</div>


<?php
// Mobile Nav Menu
if ( has_nav_menu( 'mobile_primary' ) || has_nav_menu( 'mobile_secondary' ) ) {

	?>

	<div id="mobile-nav">

		<div class="inside">
			<div class="mobile-menu">
				<?php
				// Mobile - Primary Menu
				if ( has_nav_menu( 'mobile_primary' ) ) {
					$args = array(
						'theme_location' => 'mobile_primary',
						'menu'           => 'Mobile - Primary',
						'container'      => '',
						'container_id'   => '',
						'menu_class'     => '',
						'items_wrap'     => '<ul class="nav-list">%3$s</ul>',
					);

					echo '<nav class="nav-menu nav-mobile nav-primary">';
					wp_nav_menu( $args );
					echo '</nav>';
				}

				// Mobile - Secondary Menu
				if ( has_nav_menu( 'mobile_secondary' ) ) {
					$args = array(
						'theme_location' => 'mobile_secondary',
						'menu'           => 'Mobile - Secondary',
						'container'      => '',
						'container_id'   => '',
						'menu_class'     => '',
						'items_wrap'     => '<ul class="nav-list">%3$s</ul>',
					);

					echo '<nav class="nav-menu nav-mobile nav-secondary">';
					wp_nav_menu( $args );
					echo '</nav>';
				}
				?>
			</div>
		</div>
	</div>
	<?php
}

?>

<?php wp_footer(); ?>

</body>
</html>