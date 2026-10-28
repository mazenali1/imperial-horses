<?php
/**
 * Search results (blog posts only).
 */
get_header();

/**
 * Default banner for blog posts, set in the Blog page settings (ACF field: blog_default_banner).
 * Returns an attachment ID, or 0 if none is set. (Same helper as in single.php.)
 */
if (!function_exists('ih_single_default_banner_id')) {
	function ih_single_default_banner_id() {
		$blog_page_id = (int) url_to_postid(ih_page_url('blogs'));
		if (!$blog_page_id) {
			$blog_page_id = (int) get_option('page_for_posts');
		}
		if (!$blog_page_id || !function_exists('get_field')) {
			return 0;
		}
		$img = get_field('blog_default_banner', $blog_page_id);
		if (is_array($img)) {
			return (int) ($img['ID'] ?? 0);
		}
		return (int) $img;
	}
}
?>
<main id="main" class="site-main">

	<?php get_template_part('template-parts/hero', null, [
		'eyebrow' => is_rtl() ? 'بحث' : 'SEARCH',
		'title'   => is_rtl()
			? sprintf('نتائج البحث عن "%s"', get_search_query())
			: sprintf('Results for "%s"', get_search_query()),
		'bg'      => ih_single_default_banner_id(),
	]); ?>

	<section class="blogs-page">
		<div class="container">
			<div class="blogs-page__search"><?php ih_search_form(is_rtl() ? 'ابحث في المقالات...' : 'Search articles...'); ?></div>

			<?php if (have_posts()) : ?>
				<div class="post-list">
					<?php while (have_posts()) : the_post(); get_template_part('template-parts/post-card', null, ['layout' => 'row']); endwhile; ?>
				</div>
				<?php the_posts_pagination(['mid_size' => 1, 'prev_text' => '&lsaquo;', 'next_text' => '&rsaquo;']); ?>
			<?php else : ?>
				<p class="blogs-page__empty"><?php echo esc_html(is_rtl() ? 'لم نجد نتائج مطابقة لبحثك. جرّب كلمات مختلفة.' : 'Nothing matched your search. Try different keywords.'); ?></p>
			<?php endif; ?>
		</div>
	</section>

	<div class="container cta-wrap"><?php get_template_part('template-parts/cta-banner'); ?></div>

</main>
<?php get_footer();