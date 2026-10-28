<?php
/**
 * Inner-page hero. Args: eyebrow, title, bg (attachment id), bg_url, pre (trusted html), class.
 */
$a  = wp_parse_args($args ?? [], ['eyebrow' => '', 'title' => '', 'bg' => 0, 'bg_url' => '', 'pre' => '', 'class' => '']);
$bg = $a['bg_url'] ? $a['bg_url'] : ih_img_url($a['bg'], 'ih-hero');
?>
<section class="hero <?php echo esc_attr($a['class']); ?>"<?php echo $bg ? ' style="--hero-bg:url(' . esc_url($bg) . ')"' : ''; ?>>
	<div class="hero__overlay"></div>
	<div class="container hero__inner">
		<?php echo $a['pre']; // phpcs:ignore WordPress.Security.EscapeOutput -- built with escaped parts by the caller ?>
		<?php if ($a['eyebrow']) : ?>
			<p class="eyebrow"><span class="eyebrow__line"></span><?php echo esc_html($a['eyebrow']); ?></p>
		<?php endif; ?>
		<h1 class="hero__title"><?php echo ih_lines($a['title']); ?></h1>
	</div>
</section>
