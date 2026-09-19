<?php
/**
 * Homepage — Coreve CRO/brand rebuild.
 *
 * Built as a conversion narrative per the brand brief: establish the
 * category problem, introduce the women-first philosophy, then the
 * product, before resolving size/trust anxiety. Every factual claim here
 * is sourced from PROGRESS.md's confirmed-facts registry — nothing here
 * is invented (no fake reviews, no unverified delivery/COD/material claims).
 *
 * @package Coreve
 */

get_header();

$collection = coreve_collection_products();
?>

<!-- Section 4/5 anchor targets are used by the header's "Why Coreve" and hero's secondary CTA -->

<!-- Section 3 — Hero -->
<section class="hero">
	<div class="hero-inner">
		<div class="hero-content">
			<h1>She Was Never Meant to Fit Into His Shoe.</h1>
			<p class="hero-support">For years, women have adapted to sneakers designed around generic or men's footwear thinking. Coreve starts somewhere different — with her.</p>
			<div class="hero-ctas">
				<a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>" class="btn">Explore Coreve</a>
				<a href="#why-coreve" class="btn-secondary">Why Coreve?</a>
			</div>
		</div>
		<div class="hero-image">
			<img src="<?php echo esc_url( coreve_asset_image( 'cip_1.webp' ) ); ?>" alt="Coreve sneaker" fetchpriority="high">
		</div>
	</div>
</section>

<!-- Section 4 — The Cultural Truth (premium, atmospheric treatment) -->
<section class="cultural-truth-hero" id="why-coreve">
	<span class="cultural-truth-bgword" aria-hidden="true">HERS</span>
	<div class="container cultural-truth-content">
		<p class="cultural-truth-eyebrow">The Cultural Truth</p>
		<h2 class="cultural-truth-title">Why Is She Still Wearing His?</h2>
		<p class="cultural-truth-lede">Walk into almost any sneaker store and here's the truth: the choice is usually a men's sneaker in a smaller size, or a unisex pair built around a generic, gender-neutral last. Women often choose based on how a sneaker looks, fits, or trends — while the design underneath still follows the same old assumptions.</p>

		<?php
		// Drop a transparent-background product cutout at this path and it
		// appears automatically — no code change needed. Renders nothing
		// (just the background/text above) until the file exists, so there's
		// no broken-image state in the meantime.
		$cultural_truth_product = 'cultural-truth-product.png';
		if ( file_exists( get_template_directory() . '/assets/images/' . $cultural_truth_product ) ) :
			?>
			<div class="cultural-truth-product">
				<img src="<?php echo esc_url( coreve_asset_image( $cultural_truth_product ) ); ?>" alt="Coreve sneaker" class="cultural-truth-product-img">
				<img src="<?php echo esc_url( coreve_asset_image( $cultural_truth_product ) ); ?>" alt="" aria-hidden="true" class="cultural-truth-product-reflection">
			</div>
		<?php endif; ?>
	</div>
</section>

<!-- Section 5 — Coreve Reveal -->
<section class="section section-cream">
	<div class="container">
		<h2 class="section-title">Start With Her.</h2>
		<p class="reveal-copy">Coreve designs from the woman outward. Her foot. Her movement. Her proportions. Her everyday life. Her desire for comfort — and her desire to feel elevated, without giving up either.</p>
		<div style="text-align:center;">
			<a href="#collection" class="btn">Shop the Collection</a>
		</div>
	</div>
</section>

<!-- Section 6 — Product Collection (primary conversion section) -->
<section class="section" id="collection">
	<div class="container">
		<h2 class="section-title">The Collection</h2>
		<p class="section-subtitle">Current styles. Sizes EU 37–41.</p>

		<div class="collection-grid">
			<?php foreach ( $collection as $p ) : ?>
				<div class="collection-card" data-product-id="<?php echo esc_attr( $p['id'] ); ?>">
					<a href="<?php echo esc_url( $p['permalink'] ); ?>" class="collection-card-image">
						<?php if ( $p['on_sale'] ) : ?><span class="product-card-badge">Sale</span><?php endif; ?>
						<img src="<?php echo esc_url( $p['image'] ); ?>" alt="<?php echo esc_attr( $p['name'] ); ?>" loading="lazy">
					</a>
					<div class="collection-card-body">
						<a href="<?php echo esc_url( $p['permalink'] ); ?>" class="collection-card-title"><?php echo esc_html( $p['name'] ); ?></a>
						<div class="collection-card-price">
							<?php if ( $p['on_sale'] ) : ?>
								<del><?php echo wp_kses_post( wc_price( $p['regular_price'] ) ); ?></del>
								<ins><?php echo wp_kses_post( wc_price( $p['sale_price'] ) ); ?></ins>
							<?php else : ?>
								<span><?php echo wp_kses_post( wc_price( $p['regular_price'] ) ); ?></span>
							<?php endif; ?>
						</div>

						<div class="size-pills" role="group" aria-label="Select size">
							<?php foreach ( $p['sizes'] as $s ) : ?>
								<button type="button" class="size-pill" data-variation-id="<?php echo esc_attr( $s['variation_id'] ); ?>" aria-pressed="false">EU <?php echo esc_html( $s['size'] ); ?></button>
							<?php endforeach; ?>
						</div>
						<p class="size-guide-link"><a href="<?php echo esc_url( home_url( '/size-guide/' ) ); ?>">Size Guide</a></p>

						<button type="button" class="btn add-to-bag-btn" data-product-id="<?php echo esc_attr( $p['id'] ); ?>">Buy Now</button>
						<p class="size-error" role="alert" hidden>Choose your size first.</p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- Section 7 — The Wedge Story -->
<section class="section section-cream">
	<div class="container">
		<div class="wedge-story">
			<div class="wedge-story-text">
				<h2 class="section-title" style="text-align:left;">She Wanted Sneakers. She Didn't Want to Give Up Height.</h2>
				<p>Flats are easy, but flat. Heels give height, but not for a full day on your feet. Sneakers are comfortable, but they don't offer either. Coreve's elevated wedge silhouette is built around that exact tension — sneaker comfort, with an elevated stance, for everyday wear.</p>
				<p>Each pair uses a 65mm heel and 25mm toe construction — designed with women's biomechanics in mind, not as a medical claim, but as a design philosophy for how the sneaker sits and moves with her.</p>
			</div>
			<div class="wedge-story-image">
				<img src="<?php echo esc_url( coreve_asset_image( 'Sole_Wedge_1_1.webp' ) ); ?>" alt="Coreve wedge sole detail" loading="lazy">
			</div>
		</div>
	</div>
</section>

<!-- Section 8 — Why Coreve Feels Different -->
<section class="section">
	<div class="container">
		<h2 class="section-title">Why Coreve Feels Different</h2>
		<div class="pillar-grid">
			<div class="pillar-card">
				<span class="pillar-number">01</span>
				<h3>Women-First Design</h3>
				<p>Every Coreve sneaker is designed from a women-first perspective, not adapted from a men's or unisex last.</p>
			</div>
			<div class="pillar-card">
				<span class="pillar-number">02</span>
				<h3>Elevated Comfort</h3>
				<p>Sneaker comfort with an elevated silhouette — height and everyday wearability, without the trade-off.</p>
			</div>
			<div class="pillar-card">
				<span class="pillar-number">03</span>
				<h3>Premium Craft</h3>
				<p>Made with EU standard leather, with every pair going through multiple prototyping rounds before release.</p>
			</div>
			<div class="pillar-card">
				<span class="pillar-number">04</span>
				<h3>Made for Her Life</h3>
				<p>Work. Travel. Coffee runs. Airports. Shopping. One sneaker, built to move through all of it.</p>
			</div>
		</div>
	</div>
</section>

<!-- Genuine social proof (real, migrated verbatim — never fabricated) -->
<section class="section section-cream">
	<div class="container">
		<h2 class="section-title">What She's Saying</h2>
		<div class="testimonial-grid">
			<?php foreach ( array_slice( coreve_testimonials(), 0, 4 ) as $t ) : ?>
				<div class="testimonial-card">
					<p>&ldquo;<?php echo esc_html( $t['text'] ); ?>&rdquo;</p>
					<div class="testimonial-stars" role="img" aria-label="Rated 5 out of 5 stars">
						<i class="ph ph-fill ph-star" aria-hidden="true"></i><i class="ph ph-fill ph-star" aria-hidden="true"></i><i class="ph ph-fill ph-star" aria-hidden="true"></i><i class="ph ph-fill ph-star" aria-hidden="true"></i><i class="ph ph-fill ph-star" aria-hidden="true"></i>
					</div>
					<div class="testimonial-name"><?php echo esc_html( $t['name'] ); ?></div>
					<?php if ( $t['role'] ) : ?><div class="testimonial-role"><?php echo esc_html( $t['role'] ); ?></div><?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- Real Instagram social proof — links directly to the real posts rather
     than a heavy/unreliable third-party iframe embed (tested and found to
     throw internal errors and render blank in some contexts) -->
<section class="section">
	<div class="container">
		<h2 class="section-title">As Seen on Instagram</h2>
		<p class="section-subtitle">Real customers, sharing their own Coreve moments.</p>
		<div class="instagram-grid">
			<?php foreach ( coreve_instagram_posts() as $post ) : ?>
				<a class="instagram-card" href="<?php echo esc_url( $post['url'] ); ?>" target="_blank" rel="noopener">
					<i class="ph ph-instagram-logo" aria-hidden="true"></i>
					<span class="instagram-card-name"><?php echo esc_html( $post['name'] ); ?></span>
					<span class="instagram-card-handle"><?php echo esc_html( $post['handle'] ); ?></span>
					<span class="instagram-card-cta">View post <i class="ph ph-arrow-up-right" aria-hidden="true"></i></span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- Section 9 — Still Wondering (real-policy Q&A) -->
<section class="section">
	<div class="container">
		<h2 class="section-title">Still Wondering If Coreve Is For You?</h2>
		<p class="section-subtitle">Real answers, no surprises. <a href="<?php echo esc_url( home_url( '/size-guide/' ) ); ?>">See the full Size Guide →</a></p>
		<div class="faq-list">
			<?php foreach ( coreve_home_faqs() as $i => $faq ) : ?>
				<div class="faq-item">
					<button class="faq-question" type="button" aria-expanded="false" aria-controls="faq-answer-<?php echo esc_attr( $i ); ?>">
						<?php echo esc_html( $faq['q'] ); ?>
						<i class="ph ph-caret-down faq-icon" aria-hidden="true"></i>
					</button>
					<div class="faq-answer" id="faq-answer-<?php echo esc_attr( $i ); ?>"><p><?php echo esc_html( $faq['a'] ); ?></p></div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php get_footer(); ?>
