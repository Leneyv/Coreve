<?php
/**
 * Product page — sales-first redesign.
 *
 * Custom template for the 5 sneaker products. The made-to-order bridal item
 * falls back to WooCommerce's default template (it doesn't fit the sneaker
 * feature narrative built into this layout).
 *
 * @package Coreve
 */

get_header();

while ( have_posts() ) :
	the_post();
	global $product;
	$product = wc_get_product( get_the_ID() );

	$sneaker_ids = array( 117, 118, 119, 120, 121 );

	if ( ! $product || ! in_array( $product->get_id(), $sneaker_ids, true ) ) {
		wc_get_template_part( 'content', 'single-product' );
		continue;
	}

	$sizes = array();
	foreach ( $product->get_available_variations() as $variation_data ) {
		$size = isset( $variation_data['attributes']['attribute_pa_size'] ) ? $variation_data['attributes']['attribute_pa_size'] : '';
		if ( '' === $size || ! $variation_data['is_in_stock'] ) {
			continue;
		}
		$sizes[] = array( 'size' => $size, 'variation_id' => $variation_data['variation_id'] );
	}
	usort( $sizes, function ( $a, $b ) { return (float) $a['size'] <=> (float) $b['size']; } );

	$rating       = $product->get_average_rating();
	$review_count = $product->get_review_count();
	?>

	<!-- Breadcrumb -->
	<nav class="breadcrumb-nav container" aria-label="Breadcrumb">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
		<span aria-hidden="true"> / </span>
		<a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>">Collection</a>
		<span aria-hidden="true"> / </span>
		<span aria-current="page"><?php echo esc_html( $product->get_name() ); ?></span>
	</nav>

	<!-- ===== HERO: Gallery + Info ===== -->
	<div class="product-hero container">

		<!-- Gallery column -->
		<div class="product-hero-gallery">
			<?php woocommerce_show_product_images(); ?>
		</div>

		<!-- Info column — sticky on desktop -->
		<div class="product-hero-info">

			<p class="product-brand-label">Coreve — Women-First Sneakers</p>

			<h1 class="product-hero-title"><?php echo esc_html( $product->get_name() ); ?></h1>

			<?php if ( $review_count > 0 ) : ?>
			<div class="product-hero-rating">
				<span class="product-stars" role="img" aria-label="Rated <?php echo esc_attr( $rating ); ?> out of 5 stars">
					<?php for ( $i = 0; $i < 5; $i++ ) : ?><i class="ph ph-fill ph-star" aria-hidden="true"></i><?php endfor; ?>
				</span>
				<a href="#product-reviews" class="review-count-link"><?php echo esc_html( $review_count ); ?> review<?php echo 1 === $review_count ? '' : 's'; ?></a>
			</div>
			<?php endif; ?>

			<div class="product-hero-price">
				<?php echo wp_kses_post( $product->get_price_html() ); ?>
				<span class="price-free-shipping">
					<i class="ph ph-truck" aria-hidden="true"></i>
					Free shipping across India
				</span>
			</div>
			<div class="product-price-emi">
				<i class="ph ph-credit-card" aria-hidden="true"></i>
				<span>Or 3 interest-free payments of <strong>₹2,331</strong> with UPI / Cards</span>
			</div>

			<?php if ( $product->get_short_description() ) : ?>
			<p class="product-hero-benefit"><?php echo esc_html( wp_strip_all_tags( $product->get_short_description() ) ); ?></p>
			<?php endif; ?>

			<div class="product-review-highlight">
				<div class="review-highlight-stars">
					<i class="ph ph-fill ph-star" aria-hidden="true"></i><i class="ph ph-fill ph-star" aria-hidden="true"></i><i class="ph ph-fill ph-star" aria-hidden="true"></i><i class="ph ph-fill ph-star" aria-hidden="true"></i><i class="ph ph-fill ph-star" aria-hidden="true"></i>
				</div>
				<p class="review-highlight-quote">“The wedge gives height without the pain of heels, and the fit feels made for my feet. Wore them all day and felt super comfy.”</p>
				<span class="review-highlight-author">— Celine, Verified Buyer</span>
			</div>

			<!-- Trust badges -->
			<div class="product-trust-badges" role="list">
				<div class="trust-badge" role="listitem">
					<i class="ph ph-truck" aria-hidden="true"></i>
					<span>Free Shipping</span>
				</div>
				<div class="trust-badge" role="listitem">
					<i class="ph ph-arrows-counter-clockwise" aria-hidden="true"></i>
					<span>7-Day Returns</span>
				</div>
				<div class="trust-badge" role="listitem">
					<i class="ph ph-shield-check" aria-hidden="true"></i>
					<span>1-Yr Warranty</span>
				</div>
			</div>

			<!-- Style / Colorway Switcher -->
			<?php
			$colorways = array(
				117 => array( 'name' => 'Cipher Mocha Mousse', 'label' => 'Mocha Mousse' ),
				118 => array( 'name' => 'Ventra Blue Granite',  'label' => 'Blue Granite' ),
				119 => array( 'name' => 'Zivana Winterberry',   'label' => 'Winterberry' ),
				120 => array( 'name' => 'Elara Tendril',        'label' => 'Tendril' ),
				121 => array( 'name' => 'Nyro Eclipse',         'label' => 'Eclipse' ),
			);
			?>
			<div class="product-colorway-selector">
				<span class="colorway-label">Style: <strong><?php echo esc_html( $colorways[ $product->get_id() ]['label'] ?? $product->get_name() ); ?></strong></span>
				<div class="colorway-swatches" role="radiogroup" aria-label="Explore other styles">
					<?php foreach ( $colorways as $cid => $cinfo ) :
						$cprod = wc_get_product( $cid );
						if ( ! $cprod ) continue;
						$cimg = $cprod->get_image_id() ? coreve_maybe_webp_url( wp_get_attachment_image_url( $cprod->get_image_id(), 'thumbnail' ) ) : '';
						$is_active = ( $cid === $product->get_id() );
					?>
					<a href="<?php echo esc_url( get_permalink( $cid ) ); ?>" class="colorway-swatch<?php echo $is_active ? ' is-active' : ''; ?>" aria-label="<?php echo esc_attr( $cinfo['name'] ); ?>" title="<?php echo esc_attr( $cinfo['name'] ); ?>">
						<?php if ( $cimg ) : ?>
							<img src="<?php echo esc_url( $cimg ); ?>" alt="<?php echo esc_attr( $cinfo['name'] ); ?>" class="colorway-thumb" />
						<?php endif; ?>
						<span class="colorway-name"><?php echo esc_html( $cinfo['label'] ); ?></span>
					</a>
					<?php endforeach; ?>
				</div>
			</div>

			<!-- Size selector + CTAs -->
			<div class="collection-card" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">

				<?php $uk_map = array( '37' => '4', '38' => '5', '39' => '6', '40' => '7', '41' => '8' ); ?>
				<div class="size-selector-header">
					<span class="size-label">Select Size</span>
					<a href="<?php echo esc_url( home_url( '/size-guide/' ) ); ?>" class="size-guide-link">
						<i class="ph ph-ruler" aria-hidden="true"></i> Size Guide
					</a>
				</div>

				<div class="size-pills" role="group" aria-label="Select size">
					<?php foreach ( $sizes as $s ) :
						$uk_size = isset( $uk_map[ (string) $s['size'] ] ) ? $uk_map[ (string) $s['size'] ] : '';
					?>
					<button type="button" class="size-pill" data-variation-id="<?php echo esc_attr( $s['variation_id'] ); ?>" aria-pressed="false">
						<span class="size-eu">EU <?php echo esc_html( $s['size'] ); ?></span>
						<?php if ( $uk_size ) : ?>
							<span class="size-uk">UK <?php echo esc_html( $uk_size ); ?></span>
						<?php endif; ?>
					</button>
					<?php endforeach; ?>
				</div>

				<p class="size-fit-hint"><i class="ph ph-check-circle" aria-hidden="true"></i> True to size · Fits UK/India sizes 4–8 · If between sizes, recommend sizing up</p>

				<p class="size-error" role="alert" hidden>Please select a size first.</p>

				<div class="product-hero-ctas">
					<button type="button" class="btn-add-to-cart" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
						<i class="ph ph-handbag" aria-hidden="true"></i>
						Add to Bag
					</button>
					<button type="button" class="btn-buy-now add-to-bag-btn" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
						Buy Now
						<i class="ph ph-arrow-right" aria-hidden="true"></i>
					</button>
				</div>

				<div class="product-delivery-promise">
					<i class="ph ph-clock-countdown" aria-hidden="true"></i>
					<span><strong>Order today</strong> — Dispatched in 24–48 hrs · Free express delivery across India</span>
				</div>

				<a href="https://wa.me/919363936665?text=Hi%2C+I%27m+interested+in+<?php echo rawurlencode( $product->get_name() ); ?>" class="product-whatsapp-cta" target="_blank" rel="noopener noreferrer">
					<i class="ph ph-whatsapp-logo" aria-hidden="true"></i>
					Ask us on WhatsApp
				</a>

			</div>

			<!-- Queens Club loyalty callout -->
			<div class="product-queens-callout">
				<i class="ph ph-crown-simple" aria-hidden="true"></i>
				<div class="queens-callout-text">
					<strong>Coreve Queens Club</strong>
					<span>Register your pair to unlock the 1-Year Refab — free upper &amp; sole restoration at 6–12 months.</span>
				</div>
				<a href="<?php echo esc_url( home_url( '/queens-club/' ) ); ?>" class="queens-callout-link" aria-label="Learn more about Coreve Queens Club">Learn more</a>
			</div>

		</div><!-- .product-hero-info -->
	</div><!-- .product-hero -->

	<!-- ===== FEATURE STRIP ===== -->
	<div class="product-feature-strip" aria-label="Product features">
		<div class="container">
			<div class="feature-strip-grid">
				<div class="feature-strip-item">
					<i class="ph ph-ruler" aria-hidden="true"></i>
					<strong>Women-First Design</strong>
					<span>Designed for women from the first sketch — not adapted from a men's last</span>
				</div>
				<div class="feature-strip-item">
					<i class="ph ph-heartbeat" aria-hidden="true"></i>
					<strong>OrthoLite® Cushion</strong>
					<span>Adaptive insole that moulds to your footprint for all-day comfort</span>
				</div>
				<div class="feature-strip-item">
					<i class="ph ph-stairs" aria-hidden="true"></i>
					<strong>65mm Elevated Lift</strong>
					<span>The poise of height with the comfort to wear all day, every day</span>
				</div>
				<div class="feature-strip-item">
					<i class="ph ph-truck" aria-hidden="true"></i>
					<strong>Free Shipping India</strong>
					<span>Fast delivery everywhere — metro in 2–5 days, towns in 4–7 days</span>
				</div>
				<div class="feature-strip-item">
					<i class="ph ph-shield-check" aria-hidden="true"></i>
					<strong>1-Year Refab</strong>
					<span>Queens Club members get a free upper &amp; sole restoration at 6–12 months</span>
				</div>
			</div>
		</div>
	</div>

	<!-- ===== ACCORDION + REVIEWS ===== -->
	<div class="container">

		<div class="product-accordion">
			<?php foreach ( coreve_product_accordion_sections() as $i => $section ) : ?>
			<div class="faq-item product-accordion-item<?php echo 0 === $i ? ' is-open' : ''; ?>">
				<button class="faq-question" type="button" aria-expanded="<?php echo 0 === $i ? 'true' : 'false'; ?>" aria-controls="product-tab-<?php echo esc_attr( $i ); ?>">
					<?php echo esc_html( $section['title'] ); ?>
					<i class="ph ph-caret-down faq-icon" aria-hidden="true"></i>
				</button>
				<div class="faq-answer product-accordion-body" id="product-tab-<?php echo esc_attr( $i ); ?>">
					<?php echo wp_kses_post( $section['content'] ); ?>
				</div>
			</div>
			<?php endforeach; ?>
		</div>

		<div class="product-reviews" id="product-reviews">
			<h2 class="section-title" style="text-align:left; font-size:1.5rem;">Reviews</h2>
			<?php comments_template(); ?>
		</div>

	</div>

	<!-- Sticky mobile buy bar -->
	<div class="sticky-buy-bar" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>" hidden>
		<img src="<?php echo esc_url( wp_get_attachment_image_url( $product->get_image_id(), 'thumbnail' ) ); ?>" alt="" class="sticky-buy-bar-image">
		<div class="sticky-buy-bar-info">
			<span class="sticky-buy-bar-name"><?php echo esc_html( $product->get_name() ); ?></span>
			<span class="sticky-buy-bar-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
			<a href="<?php echo esc_url( home_url( '/size-guide/' ) ); ?>" class="sticky-buy-bar-size-guide">Size Guide</a>
		</div>
		<button type="button" class="btn sticky-buy-bar-btn">Buy Now</button>
	</div>

	<?php
endwhile;

get_footer();
