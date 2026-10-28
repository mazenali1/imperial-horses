<?php
/**
 * Default page template (Privacy Policy, Terms, etc.).
 */
get_header();

while (have_posts()) :
	the_post();

	// Hero background = the page's featured image (falls back to the ACF hero_image field).
	$hero_bg = has_post_thumbnail() ? get_post_thumbnail_id() : (function_exists('ih_f') ? ih_f('hero_image') : '');
	?>
	<main id="main" class="site-main">

		<?php get_template_part('template-parts/hero', null, [
			'title' => get_the_title(),
			'bg'    => $hero_bg,
		]); ?>

		<section class="generic">
			<div class="container container--narrow">
				<div class="prose entry-content"><?php the_content(); ?></div>
			</div>
		</section>

	</main>
<?php
endwhile;

get_footer();