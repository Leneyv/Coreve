<?php
/**
 * Homepage template — recreated from the live coreve.in Shopify site.
 *
 * @package Coreve
 */

get_header();

function coreve_product_link( $slug ) {
	$posts = get_posts( array( 'name' => $slug, 'post_type' => 'product', 'post_status' => 'publish', 'numberposts' => 1 ) );
	return $posts ? get_permalink( $posts[0] ) : home_url( '/shop/' );
}

$featured_products = array(
	array( 'slug' => 'cipher-mocha-mousse-women-sneaker', 'title' => 'Cipher Mocha Mousse - Soft Power in Motion', 'image' => 'cip_1.webp' ),
	array( 'slug' => 'ventra_blue_granite_ladies_sneaker_blue_white', 'title' => 'Ventra Blue Granite – Calm Strength', 'image' => 'ven_1_4b57b6d0-0ca1-46f0-ad25-93b6d8fbd660.webp' ),
	array( 'slug' => 'zivana-winterberry-girl-sneaker-shoe', 'title' => 'Zivana Winterberry – Bold, Unapologetic Energy', 'image' => 'ziv_v2.webp' ),
	array( 'slug' => 'elara-tendril-ladies-sneaker', 'title' => 'Elara Tendril - Quiet Elegance, Fierce Core', 'image' => 'ela_1.webp' ),
	array( 'slug' => 'nyro-eclipse-women-sneaker-blue-white', 'title' => 'Nyro Eclipse - Understated Power', 'image' => 'Nyr_1.webp' ),
);
?>

<!-- Hero -->
<section class="hero">
	<div class="hero-inner">
		<div class="hero-content">
			<h1>Walk on a Cloud<br>Like a Boss</h1>
			<span class="hero-badge">India's First Sneaker made for Women</span>
		</div>
		<div class="hero-image">
			<img src="<?php echo esc_url( coreve_asset_image( 'cip_1.webp' ) ); ?>" alt="Coreve sneaker" fetchpriority="high">
		</div>
	</div>
</section>

<!-- Definitely Female -->
<section class="section">
	<div class="container">
		<h2 class="section-title">DEFINITELY FEMALE.</h2>
		<p class="section-subtitle">Current Drop Limited Edition</p>
		<div class="product-grid">
			<?php foreach ( $featured_products as $p ) : ?>
				<a class="product-card" href="<?php echo esc_url( coreve_product_link( $p['slug'] ) ); ?>">
					<span class="product-card-badge">22% off</span>
					<div class="product-card-image">
						<img src="<?php echo esc_url( coreve_asset_image( $p['image'] ) ); ?>" alt="<?php echo esc_attr( $p['title'] ); ?>" loading="lazy">
					</div>
					<div class="product-card-body">
						<span class="product-card-title"><?php echo esc_html( $p['title'] ); ?></span>
						<span class="product-card-price"><del>Rs. 8,995</del> <ins>Rs. 6,995</ins></span>
					</div>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- Why Coreve Exists -->
<section class="section section-cream">
	<div class="container">
		<h2 class="section-title">Why Coreve Exists?</h2>
		<p class="section-subtitle">Every Unisex Sneakers failed in same way</p>
		<div class="pill-image-block">
			<img src="<?php echo esc_url( coreve_asset_image( 'Design_to_MOve_like_you_do.webp' ) ); ?>" alt="Why Coreve exists" loading="lazy">
			<div class="pill-badges">
				<span class="pill-badge">Loose Fitting</span>
				<span class="pill-badge">Heavy Strides</span>
				<span class="pill-badge">Break-in Pain</span>
			</div>
		</div>
	</div>
</section>

<!-- Process -->
<section class="process-strip">
	<div class="container">
		<h2 class="section-title">When everyone ignored this</h2>
		<p class="section-subtitle">We went ahead to solve it</p>
		<div class="process-grid">
			<figure>
				<img src="<?php echo esc_url( coreve_asset_image( 'EU_Standard_Leather_2.webp' ) ); ?>" alt="Close-up of Coreve sneaker leather detailing" loading="lazy">
				<figcaption>After 13+ Prototypes</figcaption>
			</figure>
			<figure>
				<img src="<?php echo esc_url( coreve_asset_image( 'ortholite_Insole_1.webp' ) ); ?>" alt="Coreve sneakers with Ortholite insole detail" loading="lazy">
				<figcaption>After 20+ Leather options</figcaption>
			</figure>
			<figure>
				<img src="<?php echo esc_url( coreve_asset_image( 'Sole_Wedge_1_1.webp' ) ); ?>" alt="Coreve wedge sole close-up" loading="lazy">
				<figcaption>After Countless Trial steps</figcaption>
			</figure>
		</div>
	</div>
</section>

<!-- Perfected for Women (video) -->
<section class="section" style="background:var(--color-primary); color:var(--color-on-primary); padding-top:3rem; padding-bottom:0;">
	<div class="container">
		<h2 style="font-size:2rem; font-weight:800;">Coreve was Perfected<br>for Women</h2>
	</div>
	<video autoplay muted loop playsinline style="width:100%; display:block; margin-top:1.5rem;">
		<source src="<?php echo esc_url( coreve_asset_image( '4e220763c5d84a159b63a8277984ad94.mp4' ) ); ?>" type="video/mp4">
	</video>
	<p style="text-align:center; font-weight:700; color:var(--color-accent); padding:2rem 0; font-size:1.1rem; letter-spacing:0.05em; background:var(--color-primary);">ENGINEERED FOR REAL LIFE.</p>
</section>

<!-- Feature triptych -->
<section class="section" style="padding-top:0;">
	<div class="container">
		<p style="text-align:center; color:var(--text-muted); margin-bottom:1.5rem;">Crafted with precision. Designed for her. Enduring comfort, timeless style.</p>
	</div>
	<div class="feature-triptych">
		<figure>
			<img src="<?php echo esc_url( coreve_asset_image( 'cip_3.webp' ) ); ?>" alt="Premium real leather" loading="lazy">
			<figcaption>PREMIUM REAL LEATHER</figcaption>
		</figure>
		<figure>
			<img src="<?php echo esc_url( coreve_asset_image( 'ziv_6.webp' ) ); ?>" alt="All-day cushioned comfort" loading="lazy">
			<figcaption>ALL-DAY CUSHIONED COMFORT</figcaption>
		</figure>
		<figure>
			<img src="<?php echo esc_url( coreve_asset_image( 'ven_2_a73368a4-66bf-46ae-8332-be959cc8d03b.webp' ) ); ?>" alt="Biomechanically perfect fit" loading="lazy">
			<figcaption>BIOMECHANICALLY PERFECT FIT</figcaption>
		</figure>
	</div>
</section>

<!-- Lifestyle banner -->
<section class="lifestyle-banner">
	<img src="<?php echo esc_url( coreve_asset_image( 'loud_bold.webp' ) ); ?>" alt="Loud, Bold, Unapologetically Hers — Coreve lifestyle" loading="lazy">
</section>

<!-- Refab CTA -->
<section class="cta-band">
	<h2 class="section-title">1-Year Free Refab&reg;</h2>
	<p>Every pair gets one free refurbish polish, sole care, and renewal. So your sneakers stay as powerful as day one.</p>
	<a href="<?php echo esc_url( home_url( '/coreve-refab-warranty' ) ); ?>" class="btn">Know More</a>
</section>

<!-- Testimonials -->
<section class="section section-cream">
	<div class="container">
		<h2 class="section-title">WHAT'RE THEY SAYING?</h2>
		<div class="testimonial-grid">
			<?php foreach ( coreve_testimonials() as $t ) : ?>
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

<!-- FAQ -->
<section class="section">
	<div class="container">
		<h2 class="section-title">Incase You are Wondering</h2>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
	document.querySelectorAll('.faq-question').forEach(function (btn) {
		btn.addEventListener('click', function () {
			var item = btn.closest('.faq-item');
			var isOpen = item.classList.toggle('is-open');
			btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
		});
	});
});
</script>

<?php get_footer(); ?>
