<?php
if (!defined('ABSPATH')) {
	exit;
}

/** Get an ACF field with a fallback when ACF is missing or the value is empty. */
function ih_f($name, $default = '', $post_id = false) {
	if (!function_exists('get_field')) {
		return $default;
	}
	$v = get_field($name, $post_id);
	if ($v === null || $v === false || $v === '' || $v === []) {
		return $default;
	}
	return $v;
}

/** Get a value from the "Site Settings" options page. */
function ih_opt($name, $default = '') {
	return ih_f($name, $default, 'option');
}

function ih_id($v) {
	if (is_array($v)) {
		return (int) ($v['ID'] ?? $v['id'] ?? 0);
	}
	return (int) $v;
}

function ih_img_url($v, $size = 'full') {
	$id = ih_id($v);
	return $id ? (string) wp_get_attachment_image_url($id, $size) : '';
}

function ih_img($v, $size = 'large', $attr = []) {
	$id = ih_id($v);
	return $id ? wp_get_attachment_image($id, $size, false, $attr) : '';
}

/** Image or a grey placeholder block. */
function ih_media($v, $size = 'large', $attr = []) {
	$html = ih_img($v, $size, $attr);
	return $html ? $html : '<span class="ph" aria-hidden="true"></span>';
}

function ih_lines($text) {
	return nl2br(esc_html($text));
}

function ih_page_url($slug) {
	$p = get_page_by_path($slug);
	return $p ? get_permalink($p) : home_url('/' . $slug . '/');
}

function ih_contact_url() {
	return ih_page_url('contact-us');
}

function ih_tel($phone) {
	return 'tel:' . preg_replace('/[^\d+]/', '', $phone);
}

function ih_wa_url($number) {
	return 'https://wa.me/' . preg_replace('/\D/', '', $number);
}

/**
 * Print a pill button. $link is an ACF link array (url/title/target) or empty.
 */
function ih_btn($link, $variant = 'light', $label = '', $url = '', $custom_text = '') {
	$href   = $url;
	$text   = $label;
	$target = '';
	if (is_array($link) && !empty($link['url'])) {
		$href = $link['url'];
		if (!empty($link['title'])) {
			$text = $link['title'];
		}
		if (!empty($link['target'])) {
			$target = $link['target'];
		}
	}
	// A text typed in the "Button Text" field wins over the link title / default.
	if (is_string($custom_text) && trim($custom_text) !== '') {
		$text = trim($custom_text);
	}
	if ($text === '') {
		return;
	}
	if ($href === '') {
		$href = '#';
	}
	printf(
		'<a class="btn btn--%1$s" href="%2$s"%3$s><span class="btn__label">%4$s</span><span class="btn__icon">%5$s</span></a>',
		esc_attr($variant),
		esc_url($href),
		$target ? ' target="' . esc_attr($target) . '" rel="noopener"' : '',
		esc_html($text),
		ih_icon('arrow')
	);
}

function ih_search_form($placeholder = 'Search articles...', $class = '') {
	$uid = wp_unique_id('ih-s-');
	printf(
		'<form class="search-pill %1$s" role="search" method="get" action="%2$s"><label class="screen-reader-text" for="%3$s">%4$s</label><input id="%3$s" type="search" name="s" value="%5$s" placeholder="%6$s"><input type="hidden" name="post_type" value="post"><button type="submit" aria-label="%4$s">%7$s</button></form>',
		esc_attr($class),
		esc_url(home_url('/')),
		esc_attr($uid),
		esc_attr__('Search', 'imperial-horses'),
		esc_attr(get_search_query()),
		esc_attr($placeholder),
		ih_icon('search')
	);
}

function ih_fallback_items() {
	return [
		['Home', home_url('/'), is_front_page()],
		['About us', ih_page_url('about-us'), is_page('about-us')],
		['Services', ih_page_url('services'), is_page('services')],
		['Projects', ih_page_url('projects'), is_page('projects') || is_singular('project')],
		['Blogs', ih_page_url('blogs'), is_page('blogs') || is_singular('post') || is_search()],
		['Contact us', ih_page_url('contact-us'), is_page('contact-us')],
	];
}

/** Print a menu for a location, falling back to the primary menu and then to a built-in list. */
function ih_menu($location, $class = 'nav__list') {
	if (!has_nav_menu($location) && $location !== 'primary' && has_nav_menu('primary')) {
		$location = 'primary';
	}
	if (has_nav_menu($location)) {
		wp_nav_menu([
			'theme_location' => $location,
			'container'      => false,
			'menu_class'     => $class,
			'depth'          => 1,
			'fallback_cb'    => false,
		]);
		return;
	}
	echo '<ul class="' . esc_attr($class) . '">';
	foreach (ih_fallback_items() as $item) {
		printf(
			'<li class="menu-item%s"><a href="%s">%s</a></li>',
			$item[2] ? ' current-menu-item' : '',
			esc_url($item[1]),
			esc_html($item[0])
		);
	}
	echo '</ul>';
}

/** Keep "Blogs" / "Projects" highlighted on single posts and project pages. */
add_filter('nav_menu_css_class', function ($classes, $item) {
	if ($item->object === 'page' && is_singular('post') && get_page_template_slug($item->object_id) === 'page-templates/template-blogs.php') {
		$classes[] = 'current-menu-item';
	}
	if ((is_singular('project') || is_page('projects')) && strpos((string) $item->url, '/projects') !== false) {
		$classes[] = 'current-menu-item';
	}
	return $classes;
}, 10, 2);
