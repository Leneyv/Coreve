<?php
/**
 * Fallback template.
 *
 * @package Coreve
 */

get_header();
?>
<main class="page-content-wrap">
	<?php
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			?>
			<h1><?php the_title(); ?></h1>
			<div class="page-body"><?php the_content(); ?></div>
			<?php
		endwhile;
	else :
		?>
		<p><?php esc_html_e( 'Nothing found.', 'coreve' ); ?></p>
		<?php
	endif;
	?>
</main>
<?php
get_footer();
