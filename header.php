<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<div class="site-container">
	<header class="site-header">
			
		<div class="row logo-area">
			<div class="inside">
				<?php
				// Logo
				if ( $logo_id = get_field( 'logo', 'options', false ) ) {
					if ( $img_tag = wp_get_attachment_image( $logo_id, 'full' ) ) {
						printf( '<a href="%s" title="%s" class="logo">%s</a>', esc_attr( home_url() ), esc_attr( get_bloginfo( 'title' ) ), $img_tag );
					}
				}
				?>
				
				<a href="#" class="mobile-button"><span class="bar"></span><span class="bar"></span><span class="bar"></span></a>
				
				<?php
				/*
				if ( is_user_logged_in() ) {
					$user = get_userdata(get_current_user_id());
					?>
					<nav class="nav-menu nav-user bloodsplat-1 logged-in">
						<ul class="nav-list">
							<?php if ( shortcode_exists('woocommerce_my_account') ) { ?>
							<li class="menu-item account">
								<a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>">My Account</a>
							</li>
							<?php } ?>
							<li class="menu-item logout">
								<a href="<?php echo esc_attr(wp_logout_url(get_permalink())); ?>" class="nav-button">Log Out</a>
							</li>
						</ul>
					</nav>
					<?php
				}else{
					?>
					<nav class="nav-menu nav-user bloodsplat-1 not-logged-in">
						<ul class="nav-list">
							<li class="menu-item login">
								<a href="<?php echo esc_attr(wp_login_url(get_permalink())); ?>">Log In</a>
							</li>
							<li class="menu-item signup">
								<a href="<?php echo esc_attr(add_query_arg( array('action' => 'register'), wp_login_url(get_permalink()))); ?>" class="nav-button">Sign Up</a>
							</li>
						</ul>
					</nav>
					<?php
				}
				*/
				?>
			</div>
		</div>
		
		<div class="mobile-navigation">
			<nav class="nav-mobile">
				<?php
				if ( has_nav_menu('header') ) {
					$args = array(
						'theme_location' => 'header',
						'menu'           => 'Header',
						'container'      => '',
						'container_id'   => '',
						'menu_class'     => '',
						'items_wrap'     => '<ul class="nav-list">%3$s</ul>',
					);
					
					echo '<div class="mobile-nav-menu mobile-nav-header">';
					wp_nav_menu($args);
					echo '</div>';
				}
				
				if ( has_nav_menu('legal') ) {
					$args = array(
						'theme_location' => 'legal',
						'menu'           => 'Legal',
						'container'      => '',
						'container_id'   => '',
						'menu_class'     => '',
						'items_wrap'     => '<ul class="nav-list">%3$s</ul>',
					);
					
					echo '<div class="mobile-nav-menu mobile-nav-legal">';
					wp_nav_menu($args);
					echo '</div>';
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
							echo '<div class="nav-menu nav-social">';
							wp_nav_menu($args);
							echo '</div>';
						echo '</div>';
					}
					?>
			</nav>
		</div>
		
		<div class="row nav-area">
			<div class="inside">
				<?php
				// Header navigation
				if ( has_nav_menu('header') ) {
					$args = array(
						'theme_location' => 'header',
						'menu'           => 'Header',
						'container'      => '',
						'container_id'   => '',
						'menu_class'     => '',
						'items_wrap'     => '<ul class="nav-list">%3$s</ul>',
					);
					
					echo '<nav class="nav-menu nav-primary">';
					wp_nav_menu($args);
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
		</div>
	</header>


	<div id="content"<?php if ( apply_filters( "sidebar_enabled", true ) ) {echo ' class=" has-sidebar"';} ?>>