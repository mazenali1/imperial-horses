<?php
/**
 * Template Name: Services
 */
get_header();
?>
<main id="main" class="site-main">

	<?php get_template_part('template-parts/hero', null, [
		'eyebrow' => ih_f('hero_eyebrow', 'OUR SERVICES'),
		'title'   => ih_f('hero_title', "Comprehensive\nGeotechnical Solutions"),
		'bg'      => ih_f('hero_bg'),
	]); ?>

	<section class="services-page">
		<div class="container">
			<header class="section-head">
				<h2><?php echo esc_html(ih_f('intro_title', 'BUILDING ON STRONGER GROUND')); ?></h2>
				<p><?php echo esc_html(ih_f('intro_text', 'We deliver a full range of geotechnical services using advanced techniques and modern equipment to support various construction and infrastructure projects.')); ?></p>
			</header>

			<?php foreach (ih_f('items', ih_def('services')) as $i => $s) : ?>
				<article class="svc-row<?php echo $i % 2 ? ' svc-row--flip' : ''; ?>">
					<div class="svc-row__media"><?php echo ih_media($s['image'] ?? 0, 'large'); ?></div>
					<div class="svc-row__body">
						<p class="svc-row__num"><?php echo esc_html(str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT)); ?><span class="line"></span></p>
						<h3><?php echo esc_html($s['title'] ?? ''); ?></h3>
						<?php if (!empty($s['subtitle'])) : ?><p class="svc-row__sub"><?php echo esc_html($s['subtitle']); ?></p><?php endif; ?>
						<p><?php echo esc_html($s['text'] ?? ''); ?></p>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</section>

	<div class="container cta-wrap"><?php get_template_part('template-parts/cta-banner'); ?></div>

</main>
<?php get_footer();
