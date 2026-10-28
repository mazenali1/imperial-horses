<?php
/**
 * Projects archive. (No design was supplied for this page yet, so it reuses the site's dark project-card style.)
 */
get_header();

get_template_part('template-parts/hero', null, ['eyebrow' => 'OUR PROJECTS', 'title' => 'Building Excellence Through Every Project']);
?>
<main id="main" class="site-main">
	<section class="home-projects home-projects--archive">
		<div class="container">
			<?php if (have_posts()) : ?>
				<div class="project-grid">
					<?php while (have_posts()) : the_post(); get_template_part('template-parts/project-card'); endwhile; ?>
				</div>
				<?php the_posts_pagination(['prev_text' => '&lsaquo;', 'next_text' => '&rsaquo;']); ?>
			<?php else : ?>
				<p class="empty empty--dark">Projects will appear here soon.</p>
			<?php endif; ?>
		</div>
	</section>
	<div class="container cta-wrap"><?php get_template_part('template-parts/cta-banner'); ?></div>
</main>
<?php get_footer();
