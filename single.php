<?php
/**
 * Single blog post.
 */
get_header();

/**
 * Default banner for single posts, set in the Blog page settings (ACF field: blog_default_banner).
 * Returns an attachment ID, or 0 if none is set.
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

while (have_posts()) :
	the_post();

	// 1) The post's own banner, if one is set. 2) Otherwise the fixed default from the Blog page settings.
	$hero_bg = ih_f('hero_image');
	if (!$hero_bg) {
		$hero_bg = ih_single_default_banner_id();
	}
	$pre = '<a class="hero__back" href="' . esc_url(ih_page_url('blogs')) . '">' . ih_icon('arrow-left') . '<time datetime="' . esc_attr(get_the_date('c')) . '">' . esc_html(get_the_date('j F Y')) . '</time></a>';

	$related_args = [
		'post_type'           => 'post',
		'posts_per_page'      => 2,
		'post__not_in'        => [get_the_ID()],
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	];
	$cats = wp_get_post_categories(get_the_ID());
	$related = new WP_Query($cats ? $related_args + ['category__in' => $cats] : $related_args);
	if (!$related->have_posts() && $cats) {
		$related = new WP_Query($related_args);
	}
	?>
	<main id="main" class="site-main">

		<?php get_template_part('template-parts/hero', null, [
			'title'  => get_the_title(),
			'bg'     => $hero_bg,
			'pre'    => $pre,
			'class'  => 'hero--post',
		]); ?>

		<section class="post-page">
			<div class="container post-page__grid">
				<article class="post-page__main">
					<?php
					// Poster comes from its own ACF field (post_poster), not from the Featured Image.
					$poster_id = function_exists('get_field') ? (int) get_field('post_poster') : 0;
					if ($poster_id) : ?>
						<figure class="post-page__figure"><?php
							echo wp_get_attachment_image($poster_id, 'ih-post-wide', false, ['loading' => 'eager', 'decoding' => 'async']);
						?></figure>
					<?php endif; ?>
					<div class="prose entry-content"><?php the_content(); ?></div>
				</article>

				<aside class="post-page__side">
					<?php ih_search_form(is_rtl() ? 'ابحث في المقالات...' : 'Search articles...'); ?>

					<?php if ($related->have_posts()) : ?>
						<h2 class="side-title"><?php echo esc_html(is_rtl() ? 'مقالات ذات صلة' : 'Related Articles'); ?></h2>
						<div class="related">
							<?php while ($related->have_posts()) : $related->the_post(); ?>
								<article class="related__item">
									<a class="related__thumb" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true"><?php echo has_post_thumbnail() ? get_the_post_thumbnail(null, 'thumbnail') : '<span class="ph"></span>'; ?></a>
									<div>
										<time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('j F Y')); ?></time>
										<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
										<a class="post-card__more" href="<?php the_permalink(); ?>"><?php echo esc_html(ih_opt('read_more_text', 'اقرأ المزيد')); ?><span class="screen-reader-text">: <?php echo esc_html(get_the_title()); ?></span> <?php echo ih_icon('arrow'); ?></a>
									</div>
								</article>
							<?php endwhile; wp_reset_postdata(); ?>
						</div>
					<?php endif; ?>

					<?php get_template_part('template-parts/cta-banner', null, ['variant' => 'card']); ?>
				</aside>
			</div>
		</section>

	</main>
<?php
endwhile;

get_footer();