<?php
/**
 * Coreve theme functions
 *
 * @package Coreve
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Cash on Delivery handling fee — confirmed real policy (Shipping Policy
 * page): "Cash on Delivery (COD): Available at an additional handling
 * fee of ₹99 per order." WooCommerce's core COD gateway has no built-in
 * fee field, so it's added here based on the customer's selected
 * payment method at checkout.
 */
function coreve_cod_handling_fee( $cart ) {
	if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
		return;
	}
	if ( 'cod' === WC()->session->get( 'chosen_payment_method' ) ) {
		$cart->add_fee( __( 'COD Handling Fee', 'coreve' ), 99 );
	}
}
add_action( 'woocommerce_cart_calculate_fees', 'coreve_cod_handling_fee' );

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

	wp_enqueue_script( 'coreve-store-api-cart', get_template_directory_uri() . '/assets/js/store-api-cart.js', array(), '1.0.0', true );
	wp_enqueue_script( 'coreve-cart-drawer', get_template_directory_uri() . '/assets/js/cart-drawer.js', array( 'coreve-store-api-cart' ), '1.0.0', true );
	wp_enqueue_script( 'coreve-theme', get_template_directory_uri() . '/assets/js/theme.js', array( 'coreve-store-api-cart', 'coreve-cart-drawer' ), '2.1.0', true );
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
	echo '<li><a href="' . esc_url( home_url( '/shop/' ) ) . '">Collection</a></li>';
	echo '<li><a href="' . esc_url( home_url( '/why-coreve/' ) ) . '">Why Coreve</a></li>';
	echo '<li><a href="' . esc_url( home_url( '/about-us/' ) ) . '">Our Story</a></li>';
	echo '<li><a href="' . esc_url( home_url( '/size-guide/' ) ) . '">Size Guide</a></li>';
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
 * FAQ items — Part 9/15 "Still Wondering" content. Every answer here is
 * sourced from confirmed real Coreve policy (see PROGRESS.md Phase 0
 * facts registry); nothing here is invented.
 */
function coreve_home_faqs() {
	return array(
		array(
			'q' => 'Which size should I choose?',
			'a' => 'Coreve sneakers run true to size in EU 37–41. Check the Size Guide on any product page, and if you\'re between sizes, we recommend sizing up.',
		),
		array(
			'q' => 'Can I exchange my size if it doesn\'t fit?',
			'a' => 'Yes. You can return or exchange within 7 days of delivery, provided the shoes are unused, unworn, unwashed, and in their original packaging with tags intact.',
		),
		array(
			'q' => 'How does delivery work?',
			'a' => 'Orders are processed in 1–2 business days. Delivery then takes 2–5 business days in metro cities, 4–7 days in other cities and towns, and 7–10 days in remote areas. Standard shipping is free across India.',
		),
		array(
			'q' => 'Can I pay by Cash on Delivery?',
			'a' => 'Yes — COD is available with a ₹99 handling fee per order.',
		),
		array(
			'q' => 'What is the 1-Year Refab Warranty?',
			'a' => 'It\'s an exclusive perk for Coreve Queens Club members: register your sneaker\'s serial number after purchase, and between 6–12 months from your purchase date you can request a free Refab — restoring the upper and sole to look and feel new again.',
		),
		array(
			'q' => 'How do I care for my sneakers?',
			'a' => 'A mild soap and water solution works well for most materials; a protectant spray can help too. Cleaning method varies slightly by material — check your product page for specifics.',
		),
	);
}

/**
 * Section 6 — Product Collection data. Queries the 5 variable sneaker
 * products (excludes the made-to-order bridal item, which isn't part of
 * the core sneaker/wedge narrative) with real per-size availability and
 * pricing pulled live from WooCommerce, for the tappable size-pill grid.
 */
function coreve_collection_products() {
	$product_ids = array( 117, 118, 119, 120, 121 );
	$products    = array();

	foreach ( $product_ids as $id ) {
		$product = wc_get_product( $id );
		if ( ! $product || ! $product->is_type( 'variable' ) ) {
			continue;
		}

		$sizes = array();
		foreach ( $product->get_available_variations() as $variation_data ) {
			$size = isset( $variation_data['attributes']['attribute_pa_size'] ) ? $variation_data['attributes']['attribute_pa_size'] : '';
			if ( '' === $size || ! $variation_data['is_in_stock'] ) {
				continue; // only show sizes actually available, per Part 6
			}
			$sizes[] = array(
				'size'         => $size,
				'variation_id' => $variation_data['variation_id'],
			);
		}
		usort( $sizes, function ( $a, $b ) { return (float) $a['size'] <=> (float) $b['size']; } );

		$image_id = $product->get_image_id();

		$products[] = array(
			'id'            => $product->get_id(),
			'name'          => $product->get_name(),
			'permalink'     => get_permalink( $product->get_id() ),
			'image'         => $image_id ? wp_get_attachment_image_url( $image_id, 'medium_large' ) : wc_placeholder_img_src(),
			'regular_price' => $product->get_variation_regular_price( 'min' ),
			'sale_price'    => $product->get_variation_sale_price( 'min' ),
			'on_sale'       => $product->is_on_sale(),
			'sizes'         => $sizes,
		);
	}

	return $products;
}

/**
 * Image helper: theme asset image URL by filename.
 */
function coreve_asset_image( $filename ) {
	return get_template_directory_uri() . '/assets/images/' . $filename;
}

/**
 * Product page accordion sections (Part 7). Shared across the 5 sneakers
 * since materials/sizing/policies are identical across the line — only
 * name/price/gallery/reviews differ per product (handled in the template).
 * Every claim here matches PROGRESS.md's confirmed-facts registry;
 * the Refab section in particular corrects the earlier unconditional
 * phrasing to the real Queens-Club-gated terms.
 */
function coreve_product_accordion_sections() {
	return array(
		array(
			'title'   => "Why She'll Love It",
			'content' => '<ul>
				<li><strong>Perfect Heel Shape</strong> — no slip, no drag, a lock-in fit that moves with you.</li>
				<li><strong>Balanced Arch Support</strong> — cushions every step, reducing fatigue during long days.</li>
				<li><strong>Shorter Heel-Ball Ratio</strong> — bends naturally with your foot.</li>
				<li><strong>65mm Lift</strong> — the poise of height, with the comfort to wear all day.</li>
				<li><strong>Ortholite&reg; Cushion</strong> — an insole that adapts to your footprint.</li>
				<li><strong>Feather-Lite Build</strong> — lightweight materials designed to reduce foot fatigue.</li>
			</ul>',
		),
		array(
			'title'   => 'Product Design',
			'content' => '<p>Designed from a women-first perspective, not adapted from a men\'s or unisex last. The elevated wedge silhouette pairs a 65mm heel with a 25mm toe, built as a design philosophy around how women move — not a medical claim.</p>',
		),
		array(
			'title'   => 'Fit & Comfort',
			'content' => '<table><tr><th>EU Size</th><th>IN Size</th><th>UK Size</th><th>Foot Length</th></tr>
				<tr><td>37</td><td>4</td><td>4</td><td>23.5 cm</td></tr>
				<tr><td>38</td><td>5</td><td>5</td><td>24.2 cm</td></tr>
				<tr><td>39</td><td>6</td><td>6</td><td>24.8 cm</td></tr>
				<tr><td>40</td><td>7</td><td>7</td><td>25.5 cm</td></tr>
				<tr><td>41</td><td>8</td><td>8</td><td>26.2 cm</td></tr></table>
				<p>If you\'re between sizes, we recommend sizing up.</p>',
		),
		array(
			'title'   => 'Materials',
			'content' => '<p>Upper: EU standard leather. Sole: TPR. Closure: lace-up. Country of origin: India.</p>',
		),
		array(
			'title'   => 'Care',
			'content' => '<p>A mild soap and water solution works well for most materials; a protectant spray can help extend the finish.</p>',
		),
		array(
			'title'   => 'Delivery',
			'content' => '<p>Orders are processed in 1–2 business days. Delivery then takes 2–5 business days in metro cities, 4–7 days in other cities and towns, and 7–10 days in remote areas. Standard shipping is free across India. Cash on Delivery is available with a ₹99 handling fee.</p>',
		),
		array(
			'title'   => 'Size Exchange',
			'content' => '<p>Return or exchange within 7 days of delivery, provided the shoes are unused, unworn, unwashed, and in original packaging with tags intact.</p>
				<p><strong>1-Year Refab (Coreve Queens Club members):</strong> register your sneaker\'s serial number after purchase, and between 6–12 months from your purchase date you can request a free Refab — restoring the upper and sole. Registration is required; without it, the Refab benefit can\'t be claimed.</p>',
		),
		array(
			'title'   => 'FAQ',
			'content' => '<p><strong>Can I wear these for physical activity?</strong> Yes, for light activity like walking, casual outings, or travel — they\'re designed as premium fashion sneakers, not sports trainers.</p>
				<p><strong>Do you offer wide or narrow widths?</strong> Sizing follows the chart above; if you\'re between sizes, size up.</p>
				<p><strong>Need help choosing?</strong> Contact us on WhatsApp at +91 93639 36665 or hello@coreve.in.</p>',
		),
	);
}
