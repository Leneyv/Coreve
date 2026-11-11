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

/**
 * SEO Title Optimization (Google & Search Intent).
 */
function coreve_seo_document_title( $title ) {
	if ( is_front_page() ) {
		return "Women’s Leather Wedge Sneakers in India | Coreve";
	}
	if ( is_shop() || is_post_type_archive( 'product' ) ) {
		return "Shop Women’s Leather Wedge Sneakers | Coreve";
	}
	if ( is_page( 'why-coreve' ) ) {
		return "Why Coreve | The Women-First Footwear Engineering Philosophy";
	}
	if ( is_page( 'about-us' ) ) {
		return "About Us | Italian Design & Indian Craftsmanship | Coreve";
	}
	if ( is_page( 'size-guide' ) ) {
		return "Women's Sneaker Size Guide (EU 37–41 to UK & IN) | Coreve";
	}
	if ( is_page( 'faq' ) ) {
		return "Frequently Asked Questions | Fit, Orders & Warranty | Coreve";
	}
	if ( is_page( 'coreve-refab-warranty' ) ) {
		return "1-Year Refab Warranty | Coreve Queens Club";
	}
	if ( is_page( 'contact' ) ) {
		return "Contact Us | Customer Care & Support | Coreve";
	}
	if ( is_page( 'queens-club' ) ) {
		return "Coreve Queens Club | VIP Loyalty, Rewards & 1-Year Refab";
	}
	if ( is_page( 'global-vision' ) ) {
		return "Global Vision | Bringing Women-First Footwear to the World | Coreve";
	}
	if ( is_page( 'shipping-policy' ) ) {
		return "Shipping & Delivery Policy | Free Pan-India Delivery | Coreve";
	}
	if ( is_page( 'refund-policy' ) ) {
		return "Return & 7-Day Size Exchange Policy | Coreve";
	}
	if ( is_page( 'terms-and-condition' ) ) {
		return "Terms & Conditions | Coreve Lifestyle Pvt Ltd";
	}
	if ( is_page( 'privacy-policy' ) ) {
		return "Privacy Policy | Coreve Lifestyle Pvt Ltd";
	}
	if ( is_singular( 'product' ) ) {
		global $post;
		$product = wc_get_product( $post->ID );
		if ( $product ) {
			return coreve_product_search_title( $product );
		}
	}
	return $title;
}
add_filter( "pre_get_document_title", "coreve_seo_document_title", 20 );

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
	// All fonts and icons hosted locally — eliminates render-blocking external CDN requests.
	wp_enqueue_style( 'coreve-local-fonts', get_template_directory_uri() . '/assets/fonts/local-fonts.css', array(), '1.0.0' );
	wp_enqueue_style( 'coreve-phosphor', get_template_directory_uri() . '/assets/phosphor-regular/style.css', array(), '2.1.1' );
	wp_enqueue_style( 'coreve-phosphor-fill', get_template_directory_uri() . '/assets/phosphor-fill/style.css', array(), '2.1.2' );
	wp_enqueue_style( 'coreve-style', get_stylesheet_uri(), array(), '3.0.4' );

	wp_enqueue_script( 'coreve-store-api-cart', get_template_directory_uri() . '/assets/js/store-api-cart.js', array(), '1.0.0', true );
	wp_enqueue_script( 'coreve-cart-drawer', get_template_directory_uri() . '/assets/js/cart-drawer.js', array( 'coreve-store-api-cart' ), '1.0.0', true );
	wp_enqueue_script( 'coreve-size-guide-modal', get_template_directory_uri() . '/assets/js/size-guide-modal.js', array(), '1.0.0', true );
	wp_enqueue_script( 'coreve-analytics', get_template_directory_uri() . '/assets/js/analytics.js', array(), '1.0.0', true );
	wp_enqueue_script( 'coreve-theme', get_template_directory_uri() . '/assets/js/theme.js', array( 'coreve-store-api-cart', 'coreve-cart-drawer', 'coreve-size-guide-modal', 'coreve-analytics' ), '2.1.0', true );
}
add_action( 'wp_enqueue_scripts', 'coreve_scripts' );

// Preload critical fonts to reduce LCP
function coreve_preload_fonts() {
	$theme_uri = get_template_directory_uri();
	echo '<link rel="preload" href="' . $theme_uri . '/assets/fonts/' . coreve_get_rubik_font() . '" as="font" type="font/woff2" crossorigin>' . "\n";
	echo '<link rel="preload" href="' . $theme_uri . '/assets/phosphor-regular/Phosphor.woff2" as="font" type="font/woff2" crossorigin>' . "\n";
		if ( is_front_page() ) {
		echo '<link rel="preload" href="' . esc_url( coreve_asset_image( 'cip_1.webp' ) ) . '" as="image" type="image/webp" fetchpriority="high">' . "
";
	}
	echo '<link rel="dns-prefetch" href="//www.googletagmanager.com">' . "\n";
}
add_action( 'wp_head', 'coreve_preload_fonts', 1 );

function coreve_get_rubik_font() {
	$fonts = glob( get_template_directory() . '/assets/fonts/*.woff2' );
	foreach ( $fonts as $f ) {
		if ( strpos( file_get_contents( dirname( $f ) . '/rubik.css' ), basename( $f ) ) !== false ) {
			return basename( $f );
		}
	}
	return '';
}

// Defer all non-critical scripts including jQuery
function coreve_defer_scripts( $tag, $handle, $src ) {
	$defer = array(
		'jquery-core',
		'jquery-migrate',
		'coreve-store-api-cart',
		'coreve-cart-drawer',
		'coreve-size-guide-modal',
		'coreve-analytics',
		'coreve-theme',
		'sourcebuster-js',
		'wc-order-attribution',
	);
	if ( in_array( $handle, $defer, true ) ) {
		if ( strpos( $tag, 'defer' ) === false ) {
			return str_replace( '<script ', '<script defer ', $tag );
		}
	}
	return $tag;
}
add_filter( 'script_loader_tag', 'coreve_defer_scripts', 10, 3 );

// Canonical tag on every page
function coreve_canonical_tag() {
	if ( is_singular() ) {
		echo '<link rel="canonical" href="' . esc_url( get_permalink() ) . '">' . "\n";
	} elseif ( is_front_page() ) {
		echo '<link rel="canonical" href="' . esc_url( home_url( '/' ) ) . '">' . "\n";
	} elseif ( is_shop() ) {
		echo '<link rel="canonical" href="' . esc_url( get_pagenum_link( max( 1, get_query_var( 'paged' ) ) ) ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'coreve_canonical_tag', 2 );
remove_action( 'wp_head', 'rel_canonical' );

// Remove WooCommerce CSS from non-shop pages
function coreve_dequeue_woo_css() {
	if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() && ! is_account_page() ) {
		wp_dequeue_style( 'woocommerce-general' );
		wp_dequeue_style( 'woocommerce-layout' );
		wp_dequeue_style( 'woocommerce-smallscreen' );
	}
}
add_action( 'wp_enqueue_scripts', 'coreve_dequeue_woo_css', 99 );

/**
 * purchase event (Part 23) — fired server-side with the real completed
 * order's data on the order-received/thank-you page, the one point where
 * a purchase is actually confirmed. Pushed into the same dataLayer the
 * rest of the event scaffold uses.
 */
function coreve_purchase_event( $order_id ) {
	$order = wc_get_order( $order_id );
	if ( ! $order ) {
		return;
	}
	$items = array();
	foreach ( $order->get_items() as $item ) {
		$items[] = array(
			'item_name' => $item->get_name(),
			'quantity'  => $item->get_quantity(),
			'price'     => $item->get_total(),
		);
	}
	?>
	<script>
	window.dataLayer = window.dataLayer || [];
	window.dataLayer.push({
		event: 'purchase',
		transaction_id: '<?php echo esc_js( $order->get_order_number() ); ?>',
		value: <?php echo wp_json_encode( (float) $order->get_total() ); ?>,
		currency: '<?php echo esc_js( $order->get_currency() ); ?>',
		items: <?php echo wp_json_encode( $items ); ?>
	});
	</script>
	<?php
}
add_action( 'woocommerce_thankyou', 'coreve_purchase_event' );

/**
 * Open Graph / Twitter Card meta tags. No SEO plugin is installed, so the
 * theme provides its own minimal tags for correct link-preview images.
 */
function coreve_social_meta() {
	$image = coreve_asset_image( 'website_hero_banner_2.webp' );
	$title = is_front_page() ? 'Women’s Leather Wedge Sneakers in India | Coreve' : wp_get_document_title();
	$desc  = 'Coreve designs luxury sneakers from a women-first perspective — handcrafted in India with Italian design, genuine leather, and ergonomic 65mm wedge comfort.';

	if ( is_front_page() ) {
		$desc = "Discover luxury sneakers engineered for women. Handcrafted in India with genuine leather, Italian design, and 65mm comfort wedge soles. Free shipping & 7-day exchange.";
	} elseif ( is_shop() || is_post_type_archive( 'product' ) ) {
		$desc = "Explore Coreve's exclusive collection of luxury women's sneakers in sizes EU 37–41. Premium Italian design, ergonomic wedge comfort, and 1-Year Refab Warranty.";
	} elseif ( is_page( 'why-coreve' ) ) {
		$desc = "Why do standard sneakers fail women? Learn how Coreve re-engineered footwear from female biomechanics, eliminating the compromises of unisex and downsized men's shoes.";
	} elseif ( is_page( 'about-us' ) ) {
		$desc = "The story behind Coreve: Italian design sensibility fused with Indian craftsmanship. Built on the belief that her fit is the standard, not an afterthought.";
	} elseif ( is_page( 'size-guide' ) ) {
		$desc = "Find your perfect sneaker fit with Coreve's women's shoe size conversion chart (EU 37–41 to UK/India sizes). Includes step-by-step foot measurement instructions.";
	} elseif ( is_page( 'coreve-refab-warranty' ) ) {
		$desc = "Learn about the exclusive 1-Year Refab Warranty for Coreve Queens Club members. Complimentary sneaker restoration between 6–12 months from purchase.";
	} elseif ( is_page( 'faq' ) ) {
		$desc = "Find answers to questions about Coreve women's sneakers, size exchange within 7 days, pan-India free delivery, and Queens Club 1-Year Refab Warranty.";
	} elseif ( is_page( 'contact' ) ) {
		$desc = "Get in touch with Coreve customer support via WhatsApp (+91 93639 36665) or email (hello@coreve.in). Based in Indiranagar, Bengaluru, Karnataka, India.";
	} elseif ( is_page( 'queens-club' ) ) {
		$desc = "Join the Coreve Queens Club for exclusive member benefits, sneak peeks, and our signature 1-Year Refab sneaker refurbishment guarantee.";
	} elseif ( is_page( 'shipping-policy' ) ) {
		$desc = "Coreve delivers across India with free standard shipping. Orders dispatched in 1–2 days; delivery in 2–5 business days for metro areas.";
	} elseif ( is_page( 'refund-policy' ) ) {
		$desc = "Coreve provides a 7-day size exchange and return policy for unworn sneakers in their original packaging. Learn about our simple return process.";
	} elseif ( is_page( 'terms-and-condition' ) ) {
		$desc = "Review the official terms and conditions for ordering and shopping at Coreve Lifestyle Pvt Ltd.";
	} elseif ( is_page( 'privacy-policy' ) ) {
		$desc = "Learn how Coreve Lifestyle Pvt Ltd collects, uses, and protects customer privacy and transactional data.";
	} elseif ( is_singular( 'product' ) ) {
		global $post;
		$product = wc_get_product( $post->ID );
		if ( $product ) {
			$short = html_entity_decode( wp_strip_all_tags( $product->get_short_description() ), ENT_QUOTES );
			if ( $short ) {
				$desc = $short . ( 'custom' === $product->get_slug() ? ' Made to order in India. Contact Coreve for sizing, production time and final-sale terms.' : ' Leather wedge sneakers, EU 37–41. Free India shipping; 7-day returns on eligible unworn pairs.' );
			}
			$image_id  = $product->get_image_id();
			$image_src = $image_id ? wp_get_attachment_image_url( $image_id, 'large' ) : '';
			if ( $image_src ) {
				$image = $image_src;
			}
		}
	} elseif ( is_page() ) {
		global $post;
		if ( $post && $post->post_content ) {
			$excerpt = wp_trim_words( wp_strip_all_tags( $post->post_content ), 30 );
			if ( $excerpt ) {
				$desc = $excerpt;
			}
		}
	}
	$page_url = coreve_public_page_url();
	if ( ! is_front_page() && substr( $page_url, -1 ) !== '/' && ! strpos( $page_url, '?' ) ) {
		$page_url .= '/';
	}
	?>
	<meta name="description" content="<?php echo esc_attr( $desc ); ?>">
	<meta property="og:type" content="<?php echo is_singular( 'product' ) ? 'product' : 'website'; ?>">
	<meta property="og:site_name" content="<?php bloginfo( 'name' ); ?>">
	<meta property="og:locale" content="en_IN">
	<meta property="og:title" content="<?php echo esc_attr( $title ); ?>">
	<meta property="og:description" content="<?php echo esc_attr( $desc ); ?>">
	<meta property="og:image" content="<?php echo esc_url( $image ); ?>">
	<meta property="og:url" content="<?php echo esc_url( $page_url ); ?>">
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:title" content="<?php echo esc_attr( $title ); ?>">
	<meta name="twitter:description" content="<?php echo esc_attr( $desc ); ?>">
	<meta name="twitter:image" content="<?php echo esc_url( $image ); ?>">
	<?php
}
add_action( 'wp_head', 'coreve_social_meta', 1 );

/**
 * Structured data (Part 16). Organization schema sitewide; Product +
 * BreadcrumbList on the 5 real sneaker product pages; FAQPage schema only
 * where the page actually has real, matching visible FAQ content (never
 * detached from what a visitor can see — that would risk a Google Search
 * Console structured-data mismatch penalty, and isn't honest either).
 */
function coreve_structured_data() {
	if ( is_404() || is_search() || is_cart() || is_checkout() || is_account_page() ) { return; }
	$graphs = array();
	$sneaker_ids = array( 117, 118, 119, 120, 121 );
	$current_url = coreve_public_page_url();
	if ( ! is_front_page() && substr( $current_url, -1 ) !== '/' && ! strpos( $current_url, '?' ) ) {
		$current_url .= '/';
	}
	$page_title = wp_get_document_title();

	// 1. WebSite Schema (Sitewide)
	$graphs[] = array(
		'@type'           => 'WebSite',
		'@id'             => home_url( '/#website' ),
		'url'             => home_url( '/' ),
		'name'            => 'Coreve',
		'description'     => 'Women-first sneakers designed for her fit, comfort, and everyday life. Handcrafted in India.',
		'inLanguage'      => 'en-IN',
		'publisher'       => array( '@id' => home_url( '/#organization' ) ),
		'potentialAction' => array(
			'@type'       => 'SearchAction',
			'target'      => array(
				'@type'       => 'EntryPoint',
				'urlTemplate' => home_url( '/?s={search_term_string}&post_type=product' ),
			),
			'query-input' => 'required name=search_term_string',
		),
	);

	// 2. Organization & ShoeStore Schema (GEO Entity Definition)
	$graphs[] = array(
		'@type'                     => array( 'Organization', 'ShoeStore', 'OnlineStore' ),
		'@id'                       => home_url( '/#organization' ),
		'name'                      => 'Coreve',
		'legalName'                 => 'Coreve Lifestyle Pvt Ltd',
		'url'                       => home_url( '/' ),
		'logo'                      => coreve_asset_image( 'coreve-footer-logo.webp' ),
		'image'                     => coreve_asset_image( 'website_hero_banner_2.webp' ),
		'description'               => 'Coreve is an Indian luxury footwear brand designing sneakers exclusively from a women-first perspective — featuring Italian design, bespoke 65mm wedge ergonomics, and premium genuine leather.',
		'disambiguatingDescription' => 'Indian luxury women-first footwear brand based in Bengaluru, specializing in elevated wedge sneakers engineered around female foot biomechanics.',
		'taxID'                     => '29AAMCC4007A1Z1',
		'priceRange'                => '₹₹₹',
		'currenciesAccepted'        => 'INR',
		'paymentAccepted'           => array( 'Credit Card', 'Debit Card', 'Net Banking', 'UPI' ),
		'foundingLocation'          => array(
			'@type' => 'Place',
			'name'  => 'Bengaluru, India',
		),
		'address'                   => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => '17, 2nd Floor, 7th Main Road, 2 Stage Indiranagar',
			'addressLocality' => 'Bengaluru',
			'addressRegion'   => 'Karnataka',
			'postalCode'      => '560038',
			'addressCountry'  => 'IN',
		),
		'geo'                       => array(
			'@type'     => 'GeoCoordinates',
			'latitude'  => 12.9719,
			'longitude' => 77.6412,
		),
		'contactPoint'              => array(
			'@type'             => 'ContactPoint',
			'telephone'         => '+91-93639-36665',
			'contactType'       => 'customer service',
			'email'             => 'hello@coreve.in',
			'areaServed'        => 'IN',
			'availableLanguage' => array( 'en', 'hi' ),
			'hoursAvailable'    => array( '@type' => 'OpeningHoursSpecification', 'dayOfWeek' => array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday' ), 'opens' => '10:00', 'closes' => '18:00' ),
		),
		'sameAs'                    => array(
			'https://www.instagram.com/coreve.in',
		),
		'knowsAbout'                => array(
			"Women's Sneakers",
			'Footwear Biomechanics',
			'Platform Sneakers',
			'Wedge Sneakers',
			'Ortholite Cushioning',
			'Italian Footwear Design',
			'Indian Craftsmanship',
			'Luxury Leather Footwear',
		),
	);

	// 3. Public WebPage identity
	if ( ! is_cart() && ! is_checkout() && ! is_account_page() ) {
		$graphs[] = array(
			'@type'      => 'WebPage',
			'@id'        => $current_url . '#webpage',
			'url'        => $current_url,
			'name'       => $page_title,
			'isPartOf'   => array( '@id' => home_url( '/#website' ) ),
			'inLanguage' => 'en-IN',

		);
	}

	// 4. Universal Breadcrumbs (Sitewide BreadcrumbList)
	$breadcrumbs = array(
		array( '@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => home_url( '/' ) ),
	);

	if ( is_shop() || is_post_type_archive( 'product' ) ) {
		$breadcrumbs[] = array( '@type' => 'ListItem', 'position' => 2, 'name' => 'Collection', 'item' => home_url( '/shop/' ) );
	} elseif ( is_singular( 'product' ) ) {
		global $post;
		$breadcrumbs[] = array( '@type' => 'ListItem', 'position' => 2, 'name' => 'Collection', 'item' => home_url( '/shop/' ) );
		$breadcrumbs[] = array( '@type' => 'ListItem', 'position' => 3, 'name' => get_the_title( $post ), 'item' => get_permalink( $post ) );
	} elseif ( is_page() && ! is_front_page() ) {
		global $post;
		$breadcrumbs[] = array( '@type' => 'ListItem', 'position' => 2, 'name' => get_the_title( $post ), 'item' => get_permalink( $post ) );
	}

	if ( count( $breadcrumbs ) > 1 ) {
		$graphs[] = array(
			'@type'           => 'BreadcrumbList',
			'@id'             => $current_url . '#breadcrumb',
			'itemListElement' => $breadcrumbs,
		);
	}

	// 5. CollectionPage & ItemList Schema on /shop/
	if ( is_shop() || is_post_type_archive( 'product' ) ) {
		$item_list = array();
		$pos = 1;
		foreach ( $GLOBALS['wp_query']->posts as $listed_post ) {
			$sid = $listed_post->ID;
			$sp = wc_get_product( $sid );
			if ( ! $sp ) continue;
			$item_list[] = array(
				'@type'    => 'ListItem',
				'position' => $pos++,
				'name'     => $sp->get_name(),
				'url'      => get_permalink( $sid ),
				'image'    => wp_get_attachment_image_url( $sp->get_image_id(), 'medium_large' ),
			);
		}
		$graphs[] = array(
			'@type'       => 'CollectionPage',
			'@id'         => home_url( '/shop/#collectionpage' ),
			'url'         => home_url( '/shop/' ),
			'name'        => "Coreve Women's Sneaker Collection",
			'description' => "Explore Coreve's luxury collection of women-first leather sneakers featuring 65mm wedge comfort and EU 37–41 sizing.",
			'isPartOf'    => array( '@id' => home_url( '/#website' ) ),
			'mainEntity'  => array(
				'@type'           => 'ItemList',
				'itemListElement' => $item_list,
			),
		);
	}

	// 6. AboutPage Schema on /about-us/
	if ( is_page( 'about-us' ) ) {
		$graphs[] = array(
			'@type'      => 'AboutPage',
			'@id'        => home_url( '/about-us/#aboutpage' ),
			'url'        => home_url( '/about-us/' ),
			'name'       => 'About Coreve | Women-First Footwear Story',
			'isPartOf'   => array( '@id' => home_url( '/#website' ) ),
			'mainEntity' => array( '@id' => home_url( '/#organization' ) ),
		);
	}

	// 7. ContactPage Schema on /contact/
	if ( is_page( 'contact' ) ) {
		$graphs[] = array(
			'@type'      => 'ContactPage',
			'@id'        => home_url( '/contact/#contactpage' ),
			'url'        => home_url( '/contact/' ),
			'name'       => 'Contact Coreve Customer Support',
			'isPartOf'   => array( '@id' => home_url( '/#website' ) ),
			'mainEntity' => array( '@id' => home_url( '/#organization' ) ),
		);
	}

	// Additional FAQs are only emitted when matching questions are visible.
	if ( is_page( array( 'size-guide', 'coreve-refab-warranty' ) ) ) {
		$faq_entities = coreve_faq_html_to_schema( get_post_field( 'post_content', get_queried_object_id() ) );
		if ( $faq_entities ) {
			$graphs[] = array( '@type' => 'FAQPage', '@id' => $current_url . '#faq', 'mainEntity' => $faq_entities );
		}
	}

	// 10. Single Product Schema (Rich Results & Merchant Listings)
	if ( is_singular( 'product' ) ) {
		global $post;
		$product = wc_get_product( $post->ID );

		if ( $product ) {
			$image_id = $product->get_image_id();
			$rating   = $product->get_average_rating();
			$reviews  = $product->get_review_count();

			$offer_availability = $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock';

			$product_schema = array(
				'@type'        => 'Product',
				'@id'          => get_permalink( $product->get_id() ) . '#product',
				'name'         => $product->get_name(),
				'image'        => $image_id ? wp_get_attachment_image_url( $image_id, 'large' ) : '',
				'description'  => html_entity_decode( wp_strip_all_tags( $product->get_short_description() ), ENT_QUOTES ),
				'sku'          => $product->get_sku() ?: (string) $product->get_id(),
				'category'     => 'Apparel & Accessories > Shoes > Sneakers',
				'audience'     => array(
					'@type'           => 'PeopleAudience',
					'suggestedGender' => 'female',
				),
				'brand'        => array(
					'@type' => 'Brand',
					'name'  => 'Coreve',
				),
				'manufacturer' => array(
					'@type'   => 'Organization',
					'name'    => 'Coreve Lifestyle Pvt Ltd',
					'address' => array(
						'@type'           => 'PostalAddress',
						'streetAddress'   => '17, 7th Main Road, 2 Stage',
						'addressLocality' => 'Bengaluru',
						'addressRegion'   => 'Karnataka',
						'postalCode'      => '560038',
						'addressCountry'  => 'IN',
					),
				),
				'offers'       => array(
					'@type'                   => 'Offer',
					'url'                     => get_permalink( $product->get_id() ),
					'priceCurrency'           => 'INR',
					'price'                   => $product->get_price(),
					'availability'            => $offer_availability,
					'itemCondition'           => 'https://schema.org/NewCondition',
					'hasMerchantReturnPolicy' => array(
						'@type'                 => 'MerchantReturnPolicy',
						'applicableCountry'     => 'IN',
						'returnPolicyCategory' => 'https://schema.org/MerchantReturnFiniteReturnWindow',
						'merchantReturnDays'     => 7,
						'returnMethod'           => 'https://schema.org/ReturnByMail',
					),
					'shippingDetails'         => array(
						'@type'               => 'OfferShippingDetails',
						'shippingRate'        => array(
							'@type'    => 'MonetaryAmount',
							'value'    => '0',
							'currency' => 'INR',
						),
						'shippingDestination' => array(
							'@type'          => 'DefinedRegion',
							'addressCountry' => 'IN',
						),
						'deliveryTime'        => array(
							'@type'        => 'ShippingDeliveryTime',
							'handlingTime' => array(
								'@type'    => 'QuantitativeValue',
								'minValue' => 1,
								'maxValue' => 2,
								'unitCode' => 'DAY',
							),
							'transitTime'  => array(
								'@type'    => 'QuantitativeValue',
								'minValue' => 2,
								'maxValue' => 10,
								'unitCode' => 'DAY',
							),
						),
					),
				),
			);


			// Custom orders have no confirmed production deadline and are generally final sale.
			if ( 'custom' === $product->get_slug() ) {
				$product_schema['manufacturer'] = array( '@type' => 'Organization', 'name' => 'Alpha', 'address' => array( '@type' => 'PostalAddress', 'addressLocality' => 'Amboor', 'addressRegion' => 'Tamil Nadu', 'addressCountry' => 'IN' ) );
				unset( $product_schema['offers']['availability'], $product_schema['offers']['hasMerchantReturnPolicy'], $product_schema['offers']['shippingDetails']['deliveryTime'] );
			}
			$gallery_ids = array_unique( array_merge( array( $image_id ), $product->get_gallery_image_ids() ) );
			$product_schema['image'] = array_values( array_filter( array_map( function( $id ) { return wp_get_attachment_image_url( $id, 'full' ); }, $gallery_ids ) ) );
			$product_schema['url'] = get_permalink( $product->get_id() );
			$product_schema['offers']['seller'] = array( '@id' => home_url( '/#organization' ) );
			if ( in_array( $product->get_id(), $sneaker_ids, true ) ) {
				$product_schema['material'] = 'Leather upper; TPR sole';
				$product_schema['size'] = array( 'EU 37', 'EU 38', 'EU 39', 'EU 40', 'EU 41' );
			}

			if ( $reviews > 0 ) {
				$product_schema['aggregateRating'] = array(
					'@type'       => 'AggregateRating',
					'ratingValue' => (string) $rating,
					'reviewCount' => (string) $reviews,
				);
			}
			$graphs[] = $product_schema;
		}

		if ( $product && in_array( $product->get_id(), $sneaker_ids, true ) ) {
			foreach ( coreve_product_accordion_sections() as $section ) {
				if ( 'FAQ' === $section['title'] ) {
					$faq_entities = coreve_faq_html_to_schema( $section['content'] );
					if ( $faq_entities ) {
						$graphs[] = array( '@type' => 'FAQPage', '@id' => get_permalink( $product->get_id() ) . '#faq', 'mainEntity' => $faq_entities );
					}
				}
			}
		}
	}

	// 11. Homepage FAQPage Schema
	if ( is_front_page() ) {
		$faq_entities = array();
		foreach ( coreve_home_faqs() as $faq ) {
			$faq_entities[] = array(
				'@type'          => 'Question',
				'name'           => $faq['q'],
				'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $faq['a'] ),
			);
		}
		if ( $faq_entities ) {
			$graphs[] = array( '@type' => 'FAQPage', '@id' => home_url( '/#faq' ), 'mainEntity' => $faq_entities );
		}
	}

	// 12. /faq/ Page FAQPage Schema
	if ( is_page( 'faq' ) ) {
		global $post;
		$faq_entities = coreve_faq_html_to_schema( $post->post_content );
		if ( $faq_entities ) {
			$graphs[] = array( '@type' => 'FAQPage', '@id' => home_url( '/faq/#faq' ), 'mainEntity' => $faq_entities );
		}
	}

	echo '<script type="application/ld+json">' . wp_json_encode( array(
		'@context' => 'https://schema.org',
		'@graph'   => $graphs,
	), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "
";
}
add_action( 'wp_head', 'coreve_structured_data', 2 );

/**
 * Parses "<p><strong>Question?</strong><br>Answer text</p>" pairs (the
 * format used by the FAQ page and product accordion FAQ section) into
 * schema.org Question/Answer entities, so FAQPage schema always matches
 * what's actually visible on the page rather than being written by hand
 * a second time and risking drift.
 */
function coreve_faq_html_to_schema( $html ) {
	$entities = array();
	if ( ! preg_match_all( '/<strong>(.*?)<\/strong>\s*(?:<br\s*\/?>)?(.*?)(?=<p>|<h[1-6]|$)/s', $html, $matches, PREG_SET_ORDER ) ) {
		return $entities;
	}
	foreach ( $matches as $m ) {
		$question = html_entity_decode( trim( wp_strip_all_tags( $m[1] ) ), ENT_QUOTES );
		$answer   = html_entity_decode( trim( wp_strip_all_tags( $m[2] ) ), ENT_QUOTES );
		if ( $question && $answer ) {
			$entities[] = array(
				'@type'          => 'Question',
				'name'           => $question,
				'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $answer ),
			);
		}
	}
	return $entities;
}

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
 * Real Instagram customer/creator posts (Part 14 social proof) — permalinks
 * re-fetched directly from the live coreve.in site and paired to their
 * captions by verifying the caption text lives inside the same <blockquote>
 * as each permalink (not fabricated, not guessed by position alone).
 * Rendered via Instagram's own official oEmbed widget so the content stays
 * live and accurate rather than being screenshotted/copied.
 */
function coreve_instagram_posts() {
	return array(
		array( 'url' => 'https://www.instagram.com/reel/DPYErt9ib81/', 'name' => 'Ammu Nair', 'handle' => '@ammunair_' ),
		array( 'url' => 'https://www.instagram.com/reel/DPYEZTaEU2R/', 'name' => 'Chandru Kaustuba', 'handle' => '@chandru_kaustuba' ),
		array( 'url' => 'https://www.instagram.com/reel/DPX_swHE6G1/', 'name' => 'Durga Surendran', 'handle' => '@durga_surendran' ),
		array( 'url' => 'https://www.instagram.com/reel/DPX_5d2gQbZ/', 'name' => 'Soumya S Thomas', 'handle' => '@soumya_thomas__' ),
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
			'a' => 'Ready-made sneakers can be returned or exchanged within 7 days of delivery if unused and in original packaging with tags intact. Made-to-order custom pairs have separate final-sale terms; contact us before ordering.',
		),
		array(
			'q' => 'How does delivery work?',
			'a' => 'Orders are processed in 1–2 business days. Delivery then takes 2–5 business days in metro cities, 4–7 days in other cities and towns, and 7–10 days in remote areas. Standard shipping is free across India.',
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
			'image'         => $image_id ? coreve_maybe_webp_url( wp_get_attachment_image_url( $image_id, 'medium_large' ) ) : wc_placeholder_img_src(),
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
			'title'   => 'Manufacturer & Marketer Details',
			'content' => '<p><strong>Manufacturer:</strong><br>
Coreve Lifestyle Pvt Ltd<br>
Building No./Flat No.: 17, Road/Street: 7th Main Road, 2 Stage<br>
City/Town: Bengaluru, District: Bengaluru Urban<br>
State: Karnataka, PIN Code: 560038, India.</p>
<p><strong>Marketer:</strong><br>
Coreve Lifestyle Pvt Ltd<br>
Building No./Flat No.: 17, Road/Street: 7th Main Road, 2 Stage<br>
City/Town: Bengaluru, District: Bengaluru Urban<br>
State: Karnataka, PIN Code: 560038, India.</p>
<p><strong>Country of Origin:</strong> India<br>
<strong>Customer Care Contact:</strong> hello@coreve.in | +91-93639-36665</p>',
		),
		array(
			'title'   => 'Care',
			'content' => '<p>A mild soap and water solution works well for most materials; a protectant spray can help extend the finish.</p>',
		),
		array(
			'title'   => 'Delivery',
			'content' => '<p>Orders are processed in 1–2 business days. Delivery then takes 2–5 business days in metro cities, 4–7 days in other cities and towns, and 7–10 days in remote areas. Standard shipping is free across India.</p>',
		),
		array(
			'title'   => 'Size Exchange',
			'content' => '<p>Return or exchange within 7 days of delivery, provided the shoes are unused, unworn, unwashed, and in original packaging with tags intact.</p>
				<p><strong>1-Year Refab (Coreve Queens Club members):</strong> register your sneaker\'s serial number after purchase, and between 6–12 months from your purchase date you can request a free Refab — restoring the upper and sole. Registration is required; without it, the Refab benefit can\'t be claimed.</p>',
		),
		array(
			'title'   => 'FAQ',
			'content' => '<p><strong>Can I wear these for physical activity?</strong> Yes, for light activity like walking, casual outings, or travel — they\'re designed as premium fashion sneakers, not sports trainers.</p>
				<p><strong>Do you offer wide or narrow widths?</strong> Check the size chart for length. Separate width options are not listed for this collection. Contact us before ordering if you need help with a wide or narrow fit.</p>
				<p><strong>Need help choosing?</strong> Contact us on WhatsApp at +91 93639 36665 or hello@coreve.in.</p>',
		),
	);
}

// Remove WooCommerce block styles on non-WooCommerce pages to reduce CSS load
function coreve_dequeue_wc_block_styles() {
	if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() && ! is_account_page() ) {
		wp_dequeue_style( 'wc-blocks-style' );
		wp_dequeue_style( 'wc-blocks-vendors-style' );
	}
}
add_action( 'wp_enqueue_scripts', 'coreve_dequeue_wc_block_styles', 99 );

// Add CORS headers for local fonts
function coreve_font_cors_headers() {
	if ( isset( $_SERVER['REQUEST_URI'] ) ) {
		$uri = $_SERVER['REQUEST_URI'];
		if ( preg_match( '/\.(woff2?|ttf|eot|svg)(\?.*)?$/', $uri ) ) {
			header( 'Access-Control-Allow-Origin: *' );
		}
	}
}
add_action( 'init', 'coreve_font_cors_headers' );

function coreve_maybe_webp_url( $url ) {
	if ( ! $url ) {
		return $url;
	}
	if ( preg_match( "/\.(png|jpe?g)$/i", $url ) ) {
		$webp_url = preg_replace( "/\.(png|jpe?g)$/i", ".webp", $url );
		$upload_dir = wp_upload_dir();
		$rel = str_replace( $upload_dir["baseurl"], "", $webp_url );
		$file = $upload_dir["basedir"] . $rel;
		if ( file_exists( $file ) ) {
			return $webp_url;
		}
	}
	return $url;
}


/**
 * XML Sitemap Optimization (Technical SEO).
 * Excludes cart/checkout/account endpoints and removes empty category taxonomies.
 */
function coreve_filter_sitemap_posts( $args, $post_type ) {
	if ( 'page' === $post_type ) {
		$cart_id     = wc_get_page_id( 'cart' );
		$checkout_id = wc_get_page_id( 'checkout' );
		$account_id  = wc_get_page_id( 'myaccount' );
		$exclude_ids = array_filter( array( $cart_id, $checkout_id, $account_id, 6, 7, 8 ) );
		if ( ! empty( $exclude_ids ) ) {
			$args['post__not_in'] = isset( $args['post__not_in'] )
				? array_unique( array_merge( $args['post__not_in'], $exclude_ids ) )
				: array_unique( $exclude_ids );
		}
	}
	return $args;
}
add_filter( 'wp_sitemaps_posts_query_args', 'coreve_filter_sitemap_posts', 10, 2 );

function coreve_filter_sitemap_taxonomies( $taxonomies ) {
	unset( $taxonomies['category'] );
	unset( $taxonomies['post_tag'] );
	return $taxonomies;
}
add_filter( 'wp_sitemaps_taxonomies', 'coreve_filter_sitemap_taxonomies' );

/** Search presentation and index controls, shared with WordPress. */
function coreve_public_page_url() {
	if ( is_front_page() ) { return home_url( '/' ); }
	if ( is_singular() ) { return get_permalink(); }
	if ( is_shop() ) { return get_pagenum_link( max( 1, get_query_var( 'paged' ) ) ); }
	return home_url( user_trailingslashit( $GLOBALS['wp']->request ) );
}
function coreve_product_search_title( $product ) {
	if ( 'custom' === $product->get_slug() ) { return 'Made-to-Order Bridal Wedge Sneakers | Coreve Her Day'; }
	$name = preg_split( '/\s+[–—-]\s+/u', $product->get_name() )[0];
	return $name . ' Leather Wedge Sneakers | Coreve';
}
add_filter( 'wp_robots', function( $robots ) {
	if ( is_search() || is_404() || is_cart() || is_checkout() || is_account_page() ) {
		$robots['noindex'] = true;
		unset( $robots['index'] );
	} elseif ( get_option( 'blog_public' ) ) {
		$robots['max-image-preview'] = 'large';
		$robots['max-snippet'] = '-1';
		$robots['max-video-preview'] = '-1';
	}
	return $robots;
} );
add_filter( 'woocommerce_page_title', function( $title ) {
	return is_shop() ? 'Women’s Leather Wedge Sneakers' : $title;
} );
add_action( 'woocommerce_archive_description', function() {
	if ( ! is_shop() || is_paged() ) { return; }
	echo '<p>Discover Coreve’s women-first leather wedge sneakers, made in India with a 65mm heel and 25mm forefoot. Choose your style in EU sizes 37–41 for everyday outfits, work and travel.</p><p>Compare styles below, check the <a href="' . esc_url( home_url( '/size-guide/' ) ) . '">size guide</a>, or read our <a href="' . esc_url( home_url( '/faq/' ) ) . '">fit and delivery answers</a>. Looking for a wedding pair? Explore the <a href="' . esc_url( home_url( '/product/custom/' ) ) . '">made-to-order bridal edition</a>.</p>';
}, 15 );
// The theme owns its product and breadcrumb graphs; avoid parallel WooCommerce copies.
add_filter( 'woocommerce_structured_data_product', '__return_empty_array', 99 );
add_filter( 'woocommerce_structured_data_breadcrumblist', '__return_empty_array', 99 );

/**
 * Configure recipient for standard WooCommerce New Order emails.
 */
add_filter( "woocommerce_email_recipient_new_order", function( $recipient, $order ) {
	return "lenee@coreve.in";
}, 10, 2 );

/**
 * Send dedicated comprehensive order notification email to lenee@coreve.in.
 */
function coreve_send_order_notification_email( $order_id ) {
	if ( ! $order_id ) {
		return false;
	}
	$order = wc_get_order( $order_id );
	if ( ! $order ) {
		return false;
	}

	// Prevent duplicate emails for the same order
	if ( $order->get_meta( "_coreve_order_notification_sent" ) ) {
		return false;
	}

	$to = "lenee@coreve.in";
	$order_number = $order->get_order_number();
	$order_date = $order->get_date_created() ? $order->get_date_created()->date_i18n( "d M Y, h:i A" ) : current_time( "d M Y, h:i A" );
	$customer_name = trim( $order->get_formatted_billing_full_name() );
	if ( empty( $customer_name ) ) {
		$customer_name = trim( $order->get_formatted_shipping_full_name() );
	}
	if ( empty( $customer_name ) ) {
		$customer_name = "Customer";
	}

	$customer_email = $order->get_billing_email();
	$customer_phone = $order->get_billing_phone();
	$total = $order->get_formatted_order_total();
	$payment_method = $order->get_payment_method_title();
	$transaction_id = $order->get_transaction_id();

	$shipping_address = $order->get_formatted_shipping_address();
	if ( empty( $shipping_address ) ) {
		$shipping_address = $order->get_formatted_billing_address();
	}
	if ( empty( $shipping_address ) ) {
		$shipping_address = "No shipping address provided";
	}

	// Build items HTML
	$items_html = "";
	foreach ( $order->get_items() as $item_id => $item ) {
		$item_name = $item->get_name();
		$qty = $item->get_quantity();
		$item_total = wc_price( $item->get_total(), array( "currency" => $order->get_currency() ) );

		$meta_data = "";
		$formatted_meta = $item->get_formatted_meta_data();
		if ( ! empty( $formatted_meta ) ) {
			$meta_parts = array();
			foreach ( $formatted_meta as $meta ) {
				$meta_parts[] = "<strong>" . esc_html( $meta->display_key ) . ":</strong> " . esc_html( wp_strip_all_tags( $meta->display_value ) );
			}
			$meta_data = "<br><span style=\"font-size:12px;color:#6b7280;\">" . implode( " | ", $meta_parts ) . "</span>";
		}

		$items_html .= "<tr>
			<td style=\"padding:12px 16px;border-bottom:1px solid #e5e7eb;font-size:14px;color:#111827;\">" . esc_html( $item_name ) . $meta_data . "</td>
			<td style=\"padding:12px 16px;border-bottom:1px solid #e5e7eb;font-size:14px;color:#111827;text-align:center;\">" . esc_html( $qty ) . "</td>
			<td style=\"padding:12px 16px;border-bottom:1px solid #e5e7eb;font-size:14px;color:#111827;text-align:right;\">" . $item_total . "</td>
		</tr>";
	}

	$admin_order_url = admin_url( "post.php?post=" . $order_id . "&action=edit" );
	$subject = sprintf( "🛍️ New Order #%s from %s (%s)", $order_number, $customer_name, html_entity_decode( wp_strip_all_tags( $total ) ) );

	$body = "<!DOCTYPE html>
<html>
<head>
<meta charset=\"utf-8\">
<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
</head>
<body style=\"margin:0;padding:24px 0;background-color:#f4f5f7;font-family:-apple-system,BlinkMacSystemFont,\x27Segoe UI\x27,Roboto,Helvetica,Arial,sans-serif;color:#1f2937;\">
<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
<tr><td align=\"center\">
<table width=\"600\" style=\"max-width:600px;background-color:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 12px rgba(0,0,0,0.06);border:1px solid #e5e7eb;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
	<tr>
		<td style=\"background-color:#111827;padding:24px 32px;text-align:left;\">
			<div style=\"font-size:11px;font-weight:700;letter-spacing:1.5px;color:#9ca3af;text-transform:uppercase;\">Coreve Lifestyle</div>
			<h1 style=\"margin:6px 0 0;font-size:20px;font-weight:600;color:#ffffff;\">New Order Received</h1>
		</td>
	</tr>
	<tr>
		<td style=\"padding:24px 32px 16px;background-color:#f9fafb;border-bottom:1px solid #e5e7eb;\">
			<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
				<tr>
					<td>
						<div style=\"font-size:12px;color:#6b7280;text-transform:uppercase;letter-spacing:0.5px;\">Order Number</div>
						<div style=\"font-size:18px;font-weight:700;color:#111827;margin-top:2px;\">#" . esc_html( $order_number ) . "</div>
					</td>
					<td>
						<div style=\"font-size:12px;color:#6b7280;text-transform:uppercase;letter-spacing:0.5px;\">Order Date</div>
						<div style=\"font-size:14px;font-weight:600;color:#111827;margin-top:4px;\">" . esc_html( $order_date ) . "</div>
					</td>
					<td align=\"right\">
						<div style=\"font-size:12px;color:#6b7280;text-transform:uppercase;letter-spacing:0.5px;\">Total Amount</div>
						<div style=\"font-size:20px;font-weight:800;color:#059669;margin-top:2px;\">" . $total . "</div>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td style=\"padding:24px 32px;border-bottom:1px solid #e5e7eb;\">
			<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
				<tr>
					<td width=\"48%\" valign=\"top\">
						<h3 style=\"margin:0 0 10px;font-size:13px;text-transform:uppercase;letter-spacing:0.8px;color:#6b7280;\">Customer Information</h3>
						<div style=\"font-size:15px;font-weight:600;color:#111827;margin-bottom:4px;\">" . esc_html( $customer_name ) . "</div>
						<div style=\"font-size:14px;color:#4b5563;margin-bottom:3px;\"><a href=\"mailto:" . esc_attr( $customer_email ) . "\" style=\"color:#2563eb;text-decoration:none;\">" . esc_html( $customer_email ) . "</a></div>
						<div style=\"font-size:14px;color:#4b5563;\"><a href=\"tel:" . esc_attr( $customer_phone ) . "\" style=\"color:#2563eb;text-decoration:none;\">" . esc_html( $customer_phone ) . "</a></div>
					</td>
					<td width=\"4%\"></td>
					<td width=\"48%\" valign=\"top\">
						<h3 style=\"margin:0 0 10px;font-size:13px;text-transform:uppercase;letter-spacing:0.8px;color:#6b7280;\">Shipping Address</h3>
						<div style=\"font-size:14px;line-height:1.5;color:#374151;\">" . nl2br( esc_html( wp_strip_all_tags( $shipping_address ) ) ) . "</div>
					</td>
				</tr>
			</table>
			<div style=\"margin-top:16px;padding-top:16px;border-top:1px dashed #e5e7eb;\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
					<tr>
						<td>
							<span style=\"font-size:13px;color:#6b7280;\">Payment Method: </span>
							<span style=\"font-size:13px;font-weight:600;color:#111827;\">" . esc_html( $payment_method ) . "</span>
							" . ( $transaction_id ? "<span style=\"font-size:12px;color:#9ca3af;margin-left:6px;\">(Txn: " . esc_html( $transaction_id ) . ")</span>" : "" ) . "
						</td>
						<td align=\"right\">
							<span style=\"font-size:13px;color:#6b7280;\">Status: </span>
							<span style=\"font-size:13px;font-weight:600;color:#059669;background-color:#d1fae5;padding:2px 8px;border-radius:4px;\">" . esc_html( ucfirst( $order->get_status() ) ) . "</span>
						</td>
					</tr>
				</table>
			</div>
		</td>
	</tr>
	<tr>
		<td style=\"padding:24px 32px;\">
			<h3 style=\"margin:0 0 14px;font-size:13px;text-transform:uppercase;letter-spacing:0.8px;color:#6b7280;\">Items Ordered</h3>
			<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" style=\"border-collapse:collapse;border:1px solid #e5e7eb;\">
				<thead>
					<tr style=\"background-color:#f9fafb;\">
						<th align=\"left\" style=\"padding:10px 16px;font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;color:#6b7280;border-bottom:1px solid #e5e7eb;\">Product</th>
						<th align=\"center\" style=\"padding:10px 16px;font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;color:#6b7280;border-bottom:1px solid #e5e7eb;\">Qty</th>
						<th align=\"right\" style=\"padding:10px 16px;font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;color:#6b7280;border-bottom:1px solid #e5e7eb;\">Price</th>
					</tr>
				</thead>
				<tbody>
					" . $items_html . "
				</tbody>
				<tfoot>
					<tr>
						<td colspan=\"2\" align=\"right\" style=\"padding:10px 16px;font-size:13px;color:#6b7280;border-bottom:1px solid #e5e7eb;\">Subtotal:</td>
						<td align=\"right\" style=\"padding:10px 16px;font-size:13px;font-weight:600;color:#111827;border-bottom:1px solid #e5e7eb;\">" . $order->get_subtotal_to_display() . "</td>
					</tr>
					<tr>
						<td colspan=\"2\" align=\"right\" style=\"padding:12px 16px;font-size:15px;font-weight:700;color:#111827;\">Grand Total:</td>
						<td align=\"right\" style=\"padding:12px 16px;font-size:16px;font-weight:800;color:#059669;\">" . $total . "</td>
					</tr>
				</tfoot>
			</table>
		</td>
	</tr>
	<tr>
		<td style=\"padding:0 32px 32px;\" align=\"center\">
			<a href=\"" . esc_url( $admin_order_url ) . "\" style=\"display:inline-block;padding:12px 28px;background-color:#111827;color:#ffffff;text-decoration:none;font-size:14px;font-weight:600;border-radius:6px;\">View Order in WP Admin &rarr;</a>
		</td>
	</tr>
	<tr>
		<td style=\"background-color:#f9fafb;padding:16px 32px;text-align:center;border-top:1px solid #e5e7eb;font-size:12px;color:#9ca3af;\">
			Coreve Lifestyle Pvt Ltd &bull; 17, 7th Main Road, 2 Stage, Indiranagar, Bengaluru 560038
		</td>
	</tr>
</table>
</td></tr>
</table>
</body>
</html>";

	$headers = array(
		"Content-Type: text/html; charset=UTF-8",
		"From: Coreve Orders <lenee@coreve.in>",
		"Reply-To: " . $customer_name . " <" . $customer_email . ">",
	);

	$sent = wp_mail( $to, $subject, $body, $headers );
	if ( $sent ) {
		$order->update_meta_data( "_coreve_order_notification_sent", time() );
		$order->save();
	}
	return $sent;
}

add_action( "woocommerce_order_status_processing", "coreve_send_order_notification_email", 10, 1 );
add_action( "woocommerce_order_status_completed", "coreve_send_order_notification_email", 10, 1 );
add_action( "woocommerce_checkout_order_processed", "coreve_send_order_notification_email", 10, 1 );

/**
 * Sequential Order Numbers starting from KLE99817
 */
function coreve_assign_order_number( $order_id, $order = null ) {
	if ( ! $order_id ) {
		return "";
	}
	// Preserve legacy test orders
	if ( (int) $order_id <= 213 ) {
		return (string) $order_id;
	}

	if ( ! $order instanceof WC_Order ) {
		$order = wc_get_order( $order_id );
	}
	if ( ! $order ) {
		return (string) $order_id;
	}

	$existing = $order->get_meta( "_coreve_order_number" );
	if ( ! empty( $existing ) ) {
		return $existing;
	}

	global $wpdb;
	$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->options} SET option_value = LAST_INSERT_ID(CAST(option_value AS UNSIGNED) + 1) WHERE option_name = %s", 'coreve_next_order_seq' ) );
	$seq = (int) $wpdb->get_var( "SELECT LAST_INSERT_ID()" );

	if ( ! $seq || $seq < 99817 ) {
		$seq = 99817;
		update_option( "coreve_next_order_seq", "99817" );
	}

	$custom_number = "KLE" . $seq;
	$order->update_meta_data( "_coreve_order_number", $custom_number );
	$order->save_meta_data();

	return $custom_number;
}
add_action( "woocommerce_new_order", "coreve_assign_order_number", 10, 2 );

/**
 * Filter WooCommerce order number to use the KLE sequential format.
 */
function coreve_custom_order_number( $order_number, $order ) {
	if ( ! $order instanceof WC_Order ) {
		$order = wc_get_order( $order );
	}
	if ( ! $order ) {
		return $order_number;
	}

	// Legacy test orders remain with their numeric IDs
	if ( (int) $order->get_id() <= 213 ) {
		return $order_number;
	}

	$assigned = $order->get_meta( "_coreve_order_number" );
	if ( ! empty( $assigned ) ) {
		return $assigned;
	}

	return coreve_assign_order_number( $order->get_id(), $order );
}
add_filter( "woocommerce_order_number", "coreve_custom_order_number", 10, 2 );

/**
 * Enable order search by custom order number (KLExxxxx) in WooCommerce Admin.
 */
add_filter( "woocommerce_order_table_search_query_meta_keys", function( $meta_keys ) {
	$meta_keys[] = "_coreve_order_number";
	return $meta_keys;
} );
add_filter( "woocommerce_shop_order_search_fields", function( $fields ) {
	$fields[] = "_coreve_order_number";
	return $fields;
} );
