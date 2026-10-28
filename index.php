<?php
/**
 * Fallback template.
 */
get_header();

get_template_part('template-parts/hero', null, ['title' => get_bloginfo('name')]);
?>
<main id="main" class="site-main">
	<section class="blogs-page">
		<div class="container">
			<?php if (have_posts()) : ?>
				<div class="post-list">
					<?php while (have_posts()) : the_post(); get_template_part('template-parts/post-card', null, ['layout' => 'row']); endwhile; ?>
				</div>
				<?php the_posts_pagination(['prev_text' => '&lsaquo;', 'next_text' => '&rsaquo;']); ?>
			<?php else : ?>
				<p class="empty">Nothing found.</p>
			<?php endif; ?>
		</div>
	</section>
</main>
<?php get_footer();
