<?php
/**
 * "Have a project in mind?" banner. Content comes from Site Settings > CTA Banner.
 * Args: variant ('' wide banner | 'card' vertical card).
 */
$variant = $args['variant'] ?? '';
$title   = ih_opt('cta_title', 'Have a project in mind ?');
$text    = ih_opt('cta_text', "Let's build a strong foundation for your next project.");
$bg      = ih_img_url(ih_opt('cta_image'), 'large');
?>
<section class="cta-banner<?php echo $variant ? ' cta-banner--' . esc_attr($variant) : ''; ?>"<?php echo $bg ? ' style="--cta-bg:url(' . esc_url($bg) . ')"' : ''; ?>>
	<div class="cta-banner__inner">
		<h2 class="cta-banner__title"><?php echo esc_html($title); ?></h2>
		<p class="cta-banner__text"><?php echo esc_html($text); ?></p>
		<?php ih_btn(ih_opt('cta_button'), 'white', 'Get a Quote', ih_contact_url(), ih_opt('cta_button_text')); ?>
	</div>
</section>
