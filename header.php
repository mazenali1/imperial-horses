<?php
/**
 * Shared site header (top bar + main navigation).
 */
$ih_email = ih_opt('email', 'info@imphorsespiling.com');
$ih_phone = ih_opt('phone', '+971 50 116 8152');
$ih_logo  = ih_opt('logo');

// Language switcher: WPML first (يودّي لنفس الصفحة بلغتها التانية)، وفيلد ACF احتياطي لو WPML مش شغال.
$ih_lang       = ih_opt('lang_link');
$ih_lang_url   = (is_array($ih_lang) && !empty($ih_lang['url'])) ? $ih_lang['url'] : home_url('/ar/');
$ih_lang_label = (is_array($ih_lang) && !empty($ih_lang['title'])) ? $ih_lang['title'] : 'AR';
$ih_wpml = apply_filters('wpml_active_languages', null, ['skip_missing' => 0]);
if (!empty($ih_wpml)) {
	foreach ($ih_wpml as $ih_l) {
		if (empty($ih_l['active'])) {
			$ih_lang_url   = $ih_l['url'];
			$ih_lang_label = $ih_l['language_code'];
			break;
		}
	}
}
// نخلي النص كود اللغة بس (EN / AR) ونشيل أي حاجة جنبه زي "-us" أو " us".
$ih_lang_label = strtoupper(preg_split('/[\s_-]+/', trim($ih_lang_label))[0]);
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#main">Skip to content</a>

<div class="topbar">
	<div class="container topbar__inner">
		<a class="topbar__item" href="mailto:<?php echo esc_attr($ih_email); ?>"><?php echo ih_icon('mail'); ?><span><?php echo esc_html($ih_email); ?></span></a>
		<span class="topbar__sep" aria-hidden="true"></span>
		<a class="topbar__item" href="<?php echo esc_url(ih_tel($ih_phone)); ?>"><?php echo ih_icon('phone'); ?><span><?php echo esc_html($ih_phone); ?></span></a>
	</div>
</div>

<header class="site-header" id="site-header">
	<div class="container header__inner">
		<a class="brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php echo esc_attr(get_bloginfo('name')); ?>">
			<?php if ($ih_logo) : ?>
				<?php echo ih_img($ih_logo, 'medium', ['class' => 'brand__img', 'alt' => get_bloginfo('name')]); ?>
			<?php else : ?>
				<span class="brand__text"><b>IMPERIAL HORSES</b><small>Piling &amp; Foundation Contracting LLC</small></span>
			<?php endif; ?>
		</a>

		<nav class="nav" id="primary-nav" aria-label="Primary">
			<?php ih_menu('primary'); ?>
		</nav>

		<div class="header__tools">
			<button class="icon-btn" type="button" data-search-toggle aria-label="Search" aria-expanded="false"><?php echo ih_icon('search'); ?></button>
			<a class="lang" href="<?php echo esc_url($ih_lang_url); ?>"><?php echo esc_html($ih_lang_label); ?></a>
			<button class="burger" type="button" data-nav-toggle aria-label="Menu" aria-expanded="false" aria-controls="primary-nav"><span></span><span></span><span></span></button>
		</div>
	</div>
	<div class="header-search" data-search-panel hidden>
		<button class="header-search__close" type="button" data-search-close aria-label="<?php echo esc_attr(is_rtl() ? 'إغلاق' : 'Close'); ?>">&times;</button>
		<div class="container">
			<div class="header-search__card"><?php ih_search_form(is_rtl() ? 'ابحث في المقالات...' : 'Search articles...'); ?></div>
		</div>
	</div>
</header>