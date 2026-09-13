<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
	<header id="masthead" class="site-header">
		<div class="header-inner">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo-link">
				<?php if ( has_custom_logo() ) : ?>
					<?php the_custom_logo(); ?>
				<?php else : ?>
					<span class="site-logo-text"><?php bloginfo( 'name' ); ?></span>
				<?php endif; ?>
			</a>

			<nav id="site-navigation" class="main-navigation">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'menu_id'        => 'primary-menu',
						'fallback_cb'    => 'coreve_fallback_menu',
					)
				);
				?>
			</nav>

			<div class="header-icons">
				<a href="#" aria-label="Search"><i class="fa-solid fa-magnifying-glass"></i></a>
				<a href="<?php echo class_exists( 'WooCommerce' ) ? esc_url( wc_get_page_permalink( 'myaccount' ) ) : '#'; ?>" aria-label="Account"><i class="fa-regular fa-user"></i></a>
				<a href="<?php echo class_exists( 'WooCommerce' ) ? esc_url( wc_get_cart_url() ) : '#'; ?>" aria-label="Cart">
					<i class="fa-solid fa-bag-shopping"></i>
					<?php if ( class_exists( 'WooCommerce' ) ) : ?>
						<span class="cart-count"><?php echo esc_html( WC()->cart ? WC()->cart->get_cart_contents_count() : 0 ); ?></span>
					<?php endif; ?>
				</a>
				<button class="mobile-menu-toggle" aria-label="Menu"><i class="fa-solid fa-bars"></i></button>
			</div>
		</div>
	</header>
