<?php
/**
 * Product page — "behaves like a premium salesperson" (Part 7).
 *
 * Custom template for the 5 sneaker products (real variable products with
 * EU 37-41 sizes). The made-to-order bridal item doesn't fit this
 * sneaker/wedge narrative (Phase 0 notes), so it falls back to
 * WooCommerce's own default product template rather than being forced
 * into a structure that doesn't apply to it.
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
		// Not one of the 5 sneakers (e.g. the made-to-order bridal item) —
		// use WooCommerce's own default single-product content rather than
		// force it into a structure built around confirmed sneaker specs.
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

	$rating = $product->get_average_rating();
	$review_count = $product->get_review_count();
	?>

	<nav class="breadcrumb-nav container">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> / <a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>">Collection</a> / <?php echo esc_html( $product->get_name() ); ?>
	</nav>

	<div class="product-hero container">
		<div class="product-hero-gallery">
			<?php woocommerce_show_product_images(); ?>
		</div>

		<div class="product-hero-info">
			<h1 class="product-hero-title"><?php echo esc_html( $product->get_name() ); ?></h1>

			<?php if ( $review_count > 0 ) : ?>
				<div class="product-hero-rating">
					<span class="testimonial-stars" role="img" aria-label="Rated <?php echo esc_attr( $rating ); ?> out of 5 stars">
						<?php for ( $i = 0; $i < 5; $i++ ) : ?><i class="ph ph-fill ph-star" aria-hidden="true"></i><?php endfor; ?>
					</span>
					<span class="review-count">(<?php echo esc_html( $review_count ); ?> review<?php echo 1 === $review_count ? '' : 's'; ?>)</span>
				</div>
			<?php endif; ?>

			<div class="product-hero-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>

			<?php if ( $product->get_short_description() ) : ?>
				<p class="product-hero-benefit"><?php echo esc_html( wp_strip_all_tags( $product->get_short_description() ) ); ?></p>
			<?php endif; ?>

			<div class="collection-card" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
				<div class="size-pills" role="group" aria-label="Select size">
					<?php foreach ( $sizes as $s ) : ?>
						<button type="button" class="size-pill" data-variation-id="<?php echo esc_attr( $s['variation_id'] ); ?>" aria-pressed="false">EU <?php echo esc_html( $s['size'] ); ?></button>
					<?php endforeach; ?>
				</div>
				<p class="size-guide-link"><a href="<?php echo esc_url( home_url( '/size-guide/' ) ); ?>">Size Guide</a></p>
				<p class="size-error" role="alert" hidden>Choose your size first.</p>

				<div class="product-hero-ctas">
					<button type="button" class="btn add-to-bag-btn" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">Add to Bag</button>
					<button type="button" class="btn-secondary buy-now-btn" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">Buy Now</button>
				</div>
			</div>
		</div>
	</div>

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

		<div class="product-reviews">
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
		</div>
		<button type="button" class="btn sticky-buy-bar-btn">Add to Bag</button>
	</div>

	<?php
endwhile;

get_footer();
