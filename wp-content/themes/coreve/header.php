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
	<div class="trust-bar">
		<span>Women-first sneakers</span>
		<span>7-day size exchange</span>
		<span>Cash on delivery available</span>
	</div>
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
				<a href="<?php echo class_exists( 'WooCommerce' ) ? esc_url( wc_get_page_permalink( 'shop' ) ) : esc_url( home_url( '/shop/' ) ); ?>" aria-label="Search products"><i class="ph ph-magnifying-glass" aria-hidden="true"></i></a>
				<a href="<?php echo class_exists( 'WooCommerce' ) ? esc_url( wc_get_page_permalink( 'myaccount' ) ) : '#'; ?>" aria-label="Account"><i class="ph ph-user" aria-hidden="true"></i></a>
				<a href="<?php echo class_exists( 'WooCommerce' ) ? esc_url( wc_get_cart_url() ) : '#'; ?>" id="cart-toggle" aria-label="Cart, <?php echo class_exists( 'WooCommerce' ) && WC()->cart ? esc_attr( WC()->cart->get_cart_contents_count() ) : 0; ?> items" aria-haspopup="dialog">
					<i class="ph ph-shopping-bag" aria-hidden="true"></i>
					<?php if ( class_exists( 'WooCommerce' ) ) : ?>
						<span class="cart-count" aria-hidden="true"><?php echo esc_html( WC()->cart ? WC()->cart->get_cart_contents_count() : 0 ); ?></span>
					<?php endif; ?>
				</a>
				<button class="mobile-menu-toggle" aria-label="Menu" aria-controls="site-navigation" aria-expanded="false"><i class="ph ph-list" aria-hidden="true"></i></button>
			</div>
		</div>
	</header>
