<?php
/**
 * Generic page template.
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
		<div class="page-body"><?php the_content(); ?></div>
		<?php
	endwhile;
	?>
</main>
<?php
get_footer();
