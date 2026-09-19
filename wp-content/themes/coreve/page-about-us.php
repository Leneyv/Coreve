<?php
/**
 * About Us page — adds a responsive "behind the design" visual banner
 * above the standard text content. Two different crops of the same
 * story (desktop vs mobile) swap via <picture>, not just a scaled-down
 * single image, since the mobile version is a different composition.
 *
 * @package Coreve
 */

get_header();
?>
<main class="page-content-wrap">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<h1><?php the_title(); ?></h1>

		<div class="about-process-banner">
			<picture>
				<source media="(max-width: 768px)" srcset="<?php echo esc_url( coreve_asset_image( 'about-process-mobile.jpg' ) ); ?>">
				<img src="<?php echo esc_url( coreve_asset_image( 'about-process-desktop.jpg' ) ); ?>" alt="Coreve's design process: a hand-drawn sketch, hands-on material and leather selection, and finished soles lined up for testing." loading="lazy">
			</picture>
		</div>

		<div class="page-body"><?php the_content(); ?></div>
		<?php
	endwhile;
	?>
</main>
<?php
get_footer();
