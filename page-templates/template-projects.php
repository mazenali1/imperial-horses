<?php
/**
 * Template Name: Projects
 */
get_header();

$ih_paged    = max(1, (int) get_query_var('paged'), (int) get_query_var('page'));
$ih_per_page = (int) ih_f('per_page') ?: 12;
$ih_intro_title = ih_f('intro_title'); // نقراه قبل اللوب، لأن the_post() بيغير البوست الحالي.
$ih_projects = new WP_Query([
	'post_type'      => 'project',
	'post_status'    => 'publish',
	'posts_per_page' => $ih_per_page,
	'paged'          => $ih_paged,
]);
?>
<main id="main" class="site-main">

	<?php get_template_part('template-parts/hero', null, [
		'eyebrow' => ih_f('hero_eyebrow'),
		'title'   => ih_f('hero_title'),
		'bg'      => ih_f('hero_bg'),
	]); ?>

	<section class="projects-page">
		<div class="container">
			<header class="section-head">
				<h2><?php echo esc_html($ih_intro_title); ?></h2>
				<p><?php echo esc_html(ih_f('intro_text')); ?></p>
			</header>

			<?php if ($ih_projects->have_posts()) : ?>
				<div class="project-grid">
					<?php while ($ih_projects->have_posts()) : $ih_projects->the_post();
						$ih_meta = function_exists('get_field') ? get_field('location') : '';
						?>
						<article class="project-card">
							<a class="project-card__link" href="<?php the_permalink(); ?>">
								<?php echo ih_media(get_post_thumbnail_id(), 'large'); ?>
								<div class="project-card__body">
									<h3 class="project-card__title"><?php the_title(); ?></h3>
									<?php if ($ih_meta) : ?><span class="project-card__meta"><?php echo esc_html($ih_meta); ?></span><?php endif; ?>
								</div>
							</a>
						</article>
					<?php endwhile; ?>
				</div>

				<nav class="pagination" aria-label="<?php echo esc_attr($ih_intro_title); ?>">
					<?php echo paginate_links([
						'total'     => $ih_projects->max_num_pages,
						'current'   => $ih_paged,
						'prev_text' => '&lsaquo;',
						'next_text' => '&rsaquo;',
						'type'      => 'list',
					]); ?>
				</nav>
			<?php else : ?>
				<p class="empty"><?php echo esc_html(ih_f('empty_text')); ?></p>
			<?php endif; wp_reset_postdata(); ?>
		</div>
	</section>

	<div class="container cta-wrap"><?php get_template_part('template-parts/cta-banner'); ?></div>

</main>
<?php get_footer();