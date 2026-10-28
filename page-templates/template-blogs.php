<?php
/**
 * Template Name: Blogs
 */
get_header();

$paged = max(1, (int) get_query_var('paged'), (int) get_query_var('page'));
$q     = new WP_Query([
	'post_type'           => 'post',
	'posts_per_page'      => max(1, (int) ih_f('per_page', 6)),
	'paged'               => $paged,
	'ignore_sticky_posts' => true,
]);
?>
<main id="main" class="site-main">

	<?php get_template_part('template-parts/hero', null, [
		'eyebrow' => ih_f('hero_eyebrow', 'OUR BLOGS'),
		'title'   => ih_f('hero_title', "INSIGHTS &\nINDUSTRY UPDATES"),
		'bg'      => ih_f('hero_bg'),
	]); ?>

	<section class="blogs-page">
		<div class="container">
			<div class="blogs-page__search"><?php ih_search_form(ih_f('search_placeholder', 'Search articles...')); ?></div>

			<?php if ($q->have_posts()) : ?>
				<div class="post-list">
					<?php while ($q->have_posts()) : $q->the_post(); get_template_part('template-parts/post-card', null, ['layout' => 'row']); endwhile; wp_reset_postdata(); ?>
				</div>
				<?php
				$links = paginate_links([
					'total'     => $q->max_num_pages,
					'current'   => $paged,
					'prev_text' => '&lsaquo;',
					'next_text' => '&rsaquo;',
					'type'      => 'list',
				]);
				if ($links) : ?><nav class="pagination" aria-label="Pagination"><?php echo $links; ?></nav><?php endif; ?>
			<?php else : ?>
				<p class="empty"><?php echo esc_html(ih_f('empty_text', 'No articles yet. Please check back soon.')); ?></p>
			<?php endif; ?>
		</div>
	</section>

	<div class="container cta-wrap"><?php get_template_part('template-parts/cta-banner'); ?></div>

</main>
<?php get_footer();
