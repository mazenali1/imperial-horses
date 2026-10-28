<?php
/**
 * Shared site footer (commitment band, contact details, legal links, WhatsApp button).
 */
$ih_email   = ih_opt('email', 'info@imphorsespiling.com');
$ih_phone   = ih_opt('phone', '+971 50 116 8152');
$ih_wa      = ih_opt('whatsapp', '+971501168152');
$ih_address = ih_opt('address', 'SALIM ALI AL MAZROUA BUILDING - OFFICE 213 - AL NAHDA 2 - DUBAI');
$ih_logo    = ih_opt('footer_logo') ?: ih_opt('logo');
$ih_fb      = ih_opt('facebook');
$ih_ig      = ih_opt('instagram');
$ih_copy    = str_replace('{year}', date_i18n('Y'), ih_opt('copyright', '© {year} Imperial Horses. All Rights Reserved.'));
$ih_privacy = ih_opt('privacy_link');
$ih_terms   = ih_opt('terms_link');

// Legal links: label + URL come from the page itself (translated version when WPML is active).
$ih_legal = function ($acf, $slug, $fallback) {
	if (is_array($acf) && !empty($acf['url'])) {
		$id = url_to_postid($acf['url']);
		if ($id) {
			$id = (int) apply_filters('wpml_object_id', $id, 'page', true);
			return ['url' => get_permalink($id), 'title' => get_the_title($id)];
		}
		return ['url' => $acf['url'], 'title' => !empty($acf['title']) ? $acf['title'] : $fallback];
	}
	$p = get_page_by_path($slug);
	if ($p) {
		$id = (int) apply_filters('wpml_object_id', $p->ID, 'page', true);
		return ['url' => get_permalink($id), 'title' => get_the_title($id)];
	}
	return ['url' => '#', 'title' => $fallback];
};
$ih_privacy_l = $ih_legal($ih_privacy, 'privacy-policy', 'Privacy Policy');
$ih_terms_l   = $ih_legal($ih_terms, 'terms-conditions', 'Terms & Conditions');
?>
<footer class="site-footer">
	<div class="container">
		<section class="commit">
			<div class="commit__media"><?php echo ih_media(ih_opt('commit_image'), 'large'); ?></div>
			<div class="commit__text">
				<p class="commit__label"><span class="eyebrow__line"></span><?php echo esc_html(ih_opt('commit_label', 'OUR COMMITMENT')); ?></p>
				<p class="commit__big commit__big--yellow"><?php echo esc_html(ih_opt('commit_line_1', 'Solid Foundations')); ?></p>
				<p class="commit__big"><?php echo esc_html(ih_opt('commit_line_2', 'For a Stronger Future')); ?></p>
			</div>
		</section>

		<div class="footer__top">
			<a class="brand brand--footer" href="<?php echo esc_url(home_url('/')); ?>">
				<?php if ($ih_logo) : ?>
					<?php echo ih_img($ih_logo, 'medium', ['class' => 'brand__img', 'alt' => get_bloginfo('name')]); ?>
				<?php else : ?>
					<span class="brand__text"><b>IMPERIAL HORSES</b><small>Piling &amp; Foundation Contracting LLC</small></span>
				<?php endif; ?>
			</a>
			<nav class="footer-nav" aria-label="Footer"><?php ih_menu('footer', 'footer-nav__list'); ?></nav>
		</div>

		<div class="footer__mid">
			<ul class="footer-contact">
				<li><?php echo ih_icon('phone'); ?><a href="<?php echo esc_url(ih_tel($ih_phone)); ?>"><?php echo esc_html($ih_phone); ?></a></li>
				<li><?php echo ih_icon('mail'); ?><a href="mailto:<?php echo esc_attr($ih_email); ?>"><?php echo esc_html($ih_email); ?></a></li>
				<li><?php echo ih_icon('pin'); ?><span><?php echo esc_html($ih_address); ?></span></li>
			</ul>
			<ul class="socials">
				<?php if ($ih_fb) : ?><li><a href="<?php echo esc_url($ih_fb); ?>" target="_blank" rel="noopener" aria-label="Facebook"><?php echo ih_icon('facebook'); ?></a></li><?php endif; ?>
				<?php if ($ih_ig) : ?><li><a href="<?php echo esc_url($ih_ig); ?>" target="_blank" rel="noopener" aria-label="Instagram"><?php echo ih_icon('instagram'); ?></a></li><?php endif; ?>
			</ul>
		</div>

		<div class="footer__bottom">
			<p><?php echo esc_html($ih_copy); ?></p>
			<p class="footer__legal"><a href="<?php echo esc_url($ih_privacy_l['url']); ?>"><?php echo esc_html($ih_privacy_l['title']); ?></a><span aria-hidden="true">|</span><a href="<?php echo esc_url($ih_terms_l['url']); ?>"><?php echo esc_html($ih_terms_l['title']); ?></a></p>
		</div>
	</div>
</footer>

<?php if ($ih_wa) : ?>
	<a class="whatsapp-fab" href="<?php echo esc_url(ih_wa_url($ih_wa)); ?>" target="_blank" rel="noopener" aria-label="WhatsApp">
		<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
			<path d="M21 11.5a8.4 8.4 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.4 8.4 0 0 1-3.8-.9L3 21l1.9-5.7a8.4 8.4 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.4 8.4 0 0 1 3.8-.9h.5a8.5 8.5 0 0 1 8 8z" fill="none" stroke="#fff" stroke-width="1.9" stroke-linejoin="round"/>
			<path transform="translate(6.8 6.8) scale(.45)" fill="#fff" d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2z"/>
		</svg>
	</a>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>