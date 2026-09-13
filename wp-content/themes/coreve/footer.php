	<footer id="colophon" class="site-footer">
		<div class="container">
			<div class="footer-grid">
				<div>
					<h4>Coreve</h4>
					<p>Coreve designs sneakers from a women-first perspective — built around her fit, her comfort, and her everyday life, not adapted from a men's or unisex last.</p>
				</div>
				<div>
					<h4>Get In Touch</h4>
					<ul>
						<li>WhatsApp: +91 93639 36665</li>
						<li>Support: hello@coreve.in</li>
						<li>17, 2nd Floor, 7th Main Road, 2 Stage Indiranagar, Bengaluru, Karnataka, 560038</li>
						<li>GST: 29AAMCC4007A1Z1</li>
					</ul>
				</div>
				<div>
					<h4>Quick Links</h4>
					<ul>
						<li><a href="<?php echo esc_url( home_url( '/contact' ) ); ?>">Contact</a></li>
						<li><a href="<?php echo esc_url( home_url( '/about-us' ) ); ?>">About Us</a></li>
						<li><a href="<?php echo esc_url( home_url( '/terms-and-condition' ) ); ?>">Terms and Condition</a></li>
						<li><a href="<?php echo esc_url( home_url( '/privacy-policy' ) ); ?>">Privacy Policy</a></li>
						<li><a href="<?php echo esc_url( home_url( '/refund-policy' ) ); ?>">Refund Policy</a></li>
						<li><a href="<?php echo esc_url( home_url( '/shipping-policy' ) ); ?>">Shipping Policy</a></li>
						<li><a href="<?php echo esc_url( home_url( '/size-guide' ) ); ?>">Size Guide</a></li>
						<li><a href="<?php echo esc_url( home_url( '/faq' ) ); ?>">FAQ</a></li>
					</ul>
				</div>
				<div>
					<h4>Social Media</h4>
					<ul>
						<li><a href="#"><i class="fa-brands fa-facebook" aria-hidden="true"></i> Facebook</a></li>
						<li><a href="#"><i class="fa-brands fa-instagram" aria-hidden="true"></i> Instagram</a></li>
						<li><a href="#"><i class="fa-brands fa-youtube" aria-hidden="true"></i> YouTube</a></li>
						<li><a href="#"><i class="fa-brands fa-x-twitter" aria-hidden="true"></i> X (Twitter)</a></li>
					</ul>
				</div>
			</div>
			<div class="footer-bottom">
				&copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?> Coreve Lifestyle Pvt Ltd. All rights reserved.
			</div>
		</div>
	</footer>

	<!-- Cart drawer (Part 10) — opens on Add to Bag, no page reload -->
	<div class="cart-drawer" id="cart-drawer" role="dialog" aria-modal="true" aria-label="Your bag" hidden>
		<div class="cart-drawer-backdrop" tabindex="-1"></div>
		<div class="cart-drawer-panel">
			<div class="cart-drawer-header">
				<h2>Your Bag</h2>
				<button type="button" class="cart-drawer-close" aria-label="Close bag"><i class="ph ph-x" aria-hidden="true"></i></button>
			</div>
			<div class="cart-drawer-items"></div>
			<div class="cart-drawer-trust">
				<p><i class="ph ph-truck" aria-hidden="true"></i> Free shipping &middot; 2&ndash;10 business days depending on location</p>
				<p><i class="ph ph-arrow-counter-clockwise" aria-hidden="true"></i> 7-day size exchange</p>
				<p><i class="ph ph-shield-check" aria-hidden="true"></i> Cash on Delivery available (&#8377;99 fee)</p>
				<p><i class="ph ph-whatsapp-logo" aria-hidden="true"></i> Need help? <a href="https://wa.me/919363936665" target="_blank" rel="noopener">WhatsApp us</a></p>
			</div>
			<div class="cart-drawer-footer">
				<div class="cart-drawer-subtotal">
					<span>Subtotal</span>
					<span class="cart-drawer-subtotal-value">&#8377;0.00</span>
				</div>
				<a href="<?php echo esc_url( home_url( '/checkout/' ) ); ?>" class="btn cart-drawer-checkout">Checkout</a>
			</div>
		</div>
	</div>
</div>

<?php wp_footer(); ?>
</body>
</html>
