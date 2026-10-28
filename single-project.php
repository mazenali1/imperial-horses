<?php
/**
 * Single project.
 */
get_header();

while (have_posts()) :
	the_post();

	get_template_part('template-parts/hero', null, [
		'eyebrow' => 'PROJECT',
		'title'   => get_the_title(),
		'bg'      => has_post_thumbnail() ? get_post_thumbnail_id() : 0,
	]);

	$details = array_filter([
		'Location' => ih_f('location'),
		'Client'   => ih_f('client'),
		'Scope'    => ih_f('scope'),
		'Year'     => ih_f('year'),
	]);
	$gallery = ih_f('gallery', []);
	?>
	<main id="main" class="site-main">
		<section class="generic">
			<div class="container project-page">
				<div class="prose entry-content"><?php the_content(); ?></div>
				<?php if ($details) : ?>
					<dl class="project-page__facts">
						<?php foreach ($details as $label => $value) : ?>
							<dt><?php echo esc_html($label); ?></dt>
							<dd><?php echo esc_html($value); ?></dd>
						<?php endforeach; ?>
					</dl>
				<?php endif; ?>
			</div>
			<?php if ($gallery) : ?>
				<div class="container">
					<div class="gallery-grid">
						<?php foreach ($gallery as $g) { echo '<figure>' . ih_img($g, 'large') . '</figure>'; } ?>
					</div>
				</div>
			<?php endif; ?>
		</section>
		<div class="container cta-wrap"><?php get_template_part('template-parts/cta-banner'); ?></div>
	</main>
<?php
endwhile;

get_footer();
