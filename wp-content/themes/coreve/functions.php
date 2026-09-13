<?php
/**
 * Coreve theme functions
 *
 * @package Coreve
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function coreve_setup() {
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'custom-logo', array( 'height' => 60, 'width' => 200, 'flex-width' => true, 'flex-height' => true ) );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'coreve' ),
			'footer'  => __( 'Footer Menu', 'coreve' ),
		)
	);
}
add_action( 'after_setup_theme', 'coreve_setup' );

/**
 * Declare WooCommerce support. Without this, WooCommerce's own template
 * routing is skipped entirely and every shop/product page falls back to
 * the theme's generic index.php.
 */
function coreve_woocommerce_support() {
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'coreve_woocommerce_support' );

/**
 * No sidebar anywhere in this design.
 */
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

function coreve_scripts() {
	// Rubik + Nunito Sans: rounded, free Google Fonts pairing (ui-ux-pro-max "E-commerce Clean" match).
	wp_enqueue_style( 'coreve-google-fonts', 'https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700;800&family=Nunito+Sans:wght@300;400;500;600;700&display=swap', array(), null );
	// Phosphor: structural UI icons (search, cart, account, menu). Font Awesome stays for footer brand/social logos only.
	wp_enqueue_style( 'coreve-phosphor', 'https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css', array(), '2.1.1' );
	// Separate bundle required for the "ph-fill" weight (e.g. filled star ratings) — the
	// regular bundle above does not include fill glyphs under the ph-fill class.
	wp_enqueue_style( 'coreve-phosphor-fill', 'https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/fill/style.css', array(), '2.1.1' );
	wp_enqueue_style( 'coreve-fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0' );
	wp_enqueue_style( 'coreve-style', get_stylesheet_uri(), array(), '3.0.0' );

	wp_enqueue_script( 'coreve-theme', get_template_directory_uri() . '/assets/js/theme.js', array(), '2.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'coreve_scripts' );

/**
 * Open Graph / Twitter Card meta tags. No SEO plugin is installed, so the
 * theme provides its own minimal tags for correct link-preview images.
 */
function coreve_social_meta() {
	$image = coreve_asset_image( 'website_hero_banner_2.webp' );
	$title = is_front_page() ? get_bloginfo( 'name' ) . ' — Sneakers Made for Women' : wp_get_document_title();
	$desc  = "India's first sneaker made for women. Designed for her natural stride, handcrafted for all-day comfort.";
	if ( is_singular( 'product' ) ) {
		global $post;
		$product = wc_get_product( $post->ID );
		if ( $product ) {
			$desc      = wp_strip_all_tags( $product->get_short_description() ) ?: $desc;
			$image_id  = $product->get_image_id();
			$image_src = $image_id ? wp_get_attachment_image_url( $image_id, 'large' ) : '';
			if ( $image_src ) {
				$image = $image_src;
			}
		}
	}
	?>
	<meta property="og:type" content="website">
	<meta property="og:site_name" content="<?php bloginfo( 'name' ); ?>">
	<meta property="og:title" content="<?php echo esc_attr( $title ); ?>">
	<meta property="og:description" content="<?php echo esc_attr( $desc ); ?>">
	<meta property="og:image" content="<?php echo esc_url( $image ); ?>">
	<meta property="og:url" content="<?php echo esc_url( home_url( add_query_arg( array(), $GLOBALS['wp']->request ) ) ); ?>">
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:title" content="<?php echo esc_attr( $title ); ?>">
	<meta name="twitter:description" content="<?php echo esc_attr( $desc ); ?>">
	<meta name="twitter:image" content="<?php echo esc_url( $image ); ?>">
	<?php
}
add_action( 'wp_head', 'coreve_social_meta', 1 );

function coreve_fallback_menu() {
	echo '<ul>';
	echo '<li><a href="' . esc_url( home_url( '/shop/' ) ) . '">Shop</a></li>';
	echo '<li><a href="' . esc_url( home_url( '/about-us' ) ) . '">About</a></li>';
	echo '<li><a href="' . esc_url( home_url( '/faq' ) ) . '">FAQ</a></li>';
	echo '<li><a href="' . esc_url( home_url( '/contact' ) ) . '">Contact</a></li>';
	echo '</ul>';
}

/**
 * Testimonials shown on the homepage and (optionally) product pages —
 * migrated verbatim from the live Shopify site's copy.
 */
function coreve_testimonials() {
	return array(
		array(
			'text' => "I honestly didn't know sneakers could feel this different. I've always worn unisex pairs and they never quite fit right—either too bulky or too flat. When I slipped into this wedges Sneaker, the first thing I noticed was the fit. It felt like it was actually designed for my foot",
			'name' => 'Keerti Singh',
			'role' => 'Marketing Executive',
		),
		array(
			'text' => 'These shoes are light, sleek, and straight-up stunning. I felt like a queen. Highly recommended.',
			'name' => 'Anjali Bhandari',
			'role' => 'Product Designer',
		),
		array(
			'text' => "Coreve is honestly a game-changer. The wedge gives me height without the pain of heels, and the fit feels made for my feet. I wore them all day at college and still felt super comfy. Plus, the Not For Men vibe? Love it—it feels like sneakers finally got a girls-only upgrade",
			'name' => 'Celine',
			'role' => 'Fitness Trainer',
		),
		array(
			'text' => "As someone who spends long hours in formal wear, I never thought sneakers could match my office vibe. Coreve changed that. The wedge makes me look sharp and gives me height, but it's so comfortable I can walk through meetings and post-work dinners without switching shoes.",
			'name' => 'Fathima Ibrahim',
			'role' => '',
		),
	);
}

/**
 * FAQ items shown in the homepage accordion — migrated from the live site.
 */
function coreve_home_faqs() {
	return array(
		array(
			'q' => 'What is 1 year free Refab',
			'a' => "More than a Warranty is an extra perk. It's about making your sneakers look and feel as good as new. After 6 months of use refer to our Refab Warranty page to know more.",
		),
		array(
			'q' => 'Can I return or exchange my sneakers?',
			'a' => 'You can return or exchange your Coreve Sneakers within 7 days of delivery, provided the product is unused, unworn, unwashed, and in its original packaging with all tags intact.',
		),
		array(
			'q' => 'Are these sneakers specifically designed for women?',
			'a' => "Yes — every Coreve sneaker is engineered around the natural walking pattern, arch shape, and posture of women, combining posture science, ergonomic shaping, and handcrafted precision.",
		),
	);
}

/**
 * Image helper: theme asset image URL by filename.
 */
function coreve_asset_image( $filename ) {
	return get_template_directory_uri() . '/assets/images/' . $filename;
}
