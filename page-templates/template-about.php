<?php
/**
 * Template Name: About
 */
get_header();

$fallback_icons = ['award', 'target', 'users'];
?>
<main id="main" class="site-main">

	<?php get_template_part('template-parts/hero', null, [
		'eyebrow' => ih_f('hero_eyebrow', 'ABOUT US'),
		'title'   => ih_f('hero_title', 'Building Strong Foundations'),
		'bg'      => ih_f('hero_bg'),
	]); ?>

	<!-- Who we are -->
	<section class="who">
		<div class="container who__grid">
			<div class="who__media about-home__media"><?php echo ih_media(ih_f('who_image'), 'large'); ?></div>
			<div class="who__body">
				<p class="eyebrow eyebrow--dark"><?php echo esc_html(ih_f('who_eyebrow', 'WHO WE ARE')); ?><span class="eyebrow__line"></span></p>
				<h2 class="title-sm"><?php echo ih_lines(ih_f('who_title', "ABOUT\nIMPERIAL HORSES")); ?></h2>
				<div class="prose prose--sm"><?php echo wp_kses_post(ih_f('who_text', ih_def('about_text'))); ?></div>
			</div>
		</div>
	</section>

	<!-- Goal -->
	<section class="goal">
		<div class="container goal__inner">
			<span class="goal__icon">
				<?php $gi = ih_img(ih_f('goal_icon'), 'thumbnail'); echo $gi ? $gi : ih_icon('target'); ?>
			</span>
			<div>
				<h2 class="goal__title"><?php echo esc_html(ih_f('goal_title', 'OUR GOAL')); ?></h2>
				<p><?php echo esc_html(ih_f('goal_text', 'To provide dependable foundation and shoring solutions that ensure the safety and stability of every project.')); ?></p>
			</div>
		</div>
	</section>

	<!-- Experience / Approach / Team -->
	<section class="cards-section">
		<div class="container cards">
			<?php foreach (ih_f('cards', ih_def('about_cards')) as $i => $card) : ?>
				<article class="info-card">
					<span class="info-card__icon">
						<?php $ci = ih_img($card['icon'] ?? 0, 'thumbnail'); echo $ci ? $ci : ih_icon($fallback_icons[$i % 3]); ?>
					</span>
					<div class="info-card__body">
						<p class="info-card__num"><?php echo esc_html(str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT)); ?><span class="line"></span></p>
						<h3><?php echo esc_html($card['title'] ?? ''); ?></h3>
						<p><?php echo esc_html($card['text'] ?? ''); ?></p>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</section>

	<div class="container cta-wrap"><?php get_template_part('template-parts/cta-banner'); ?></div>

</main>
<?php get_footer();
