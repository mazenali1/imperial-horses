<?php
if (!defined('ABSPATH')) {
	exit;
}

add_action('after_setup_theme', function () {
	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
	add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);
	add_theme_support('responsive-embeds');
	register_nav_menus([
		'primary' => 'Primary Menu',
		'footer'  => 'Footer Menu (falls back to Primary)',
	]);
	add_image_size('ih-card', 760, 520, true);
	add_image_size('ih-hero', 1920, 1000, true);
	add_image_size('ih-tile', 640, 520, true);
	add_image_size('ih-portrait', 640, 860, true);
});

/**
 * Performance: open early connections to Google Fonts.
 */
add_action('wp_head', function () {
	echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
	echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}, 1);

add_action('wp_enqueue_scripts', function () {
	wp_enqueue_style(
		'ih-fonts',
		'https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&family=IBM+Plex+Mono:wght@600&family=Kanit:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap',
		[],
		null
	);
	wp_enqueue_style('ih-main', IH_URI . '/assets/css/main.css', ['ih-fonts'], filemtime(IH_DIR . '/assets/css/main.css'));
	wp_enqueue_script('ih-main', IH_URI . '/assets/js/main.js', [], filemtime(IH_DIR . '/assets/js/main.js'), true);
	// Load the theme script with defer (WordPress 6.3+).
	wp_script_add_data('ih-main', 'strategy', 'defer');
});

/**
 * Performance: load the Google Fonts stylesheet without blocking the first
 * render (print-media trick), with a <noscript> fallback.
 */
add_filter('style_loader_tag', function ($html, $handle) {
	if ($handle === 'ih-fonts') {
		$noscript = '<noscript>' . $html . '</noscript>';
		$html = str_replace("media='all'", "media='print' onload=\"this.media='all'\"", $html);
		$html .= $noscript;
	}
	return $html;
}, 10, 2);

/**
 * Performance: Contact Form 7 stylesheets are only needed on the contact page.
 * (If a form is added to another page, add that page's template to the check.)
 */
add_action('wp_enqueue_scripts', function () {
	if (!is_page_template('page-templates/template-contact.php')) {
		wp_dequeue_style('contact-form-7');
		wp_dequeue_style('contact-form-7-rtl');
	}
}, 100);

/** Site search only looks at blog posts. */
add_action('pre_get_posts', function ($q) {
	if (!is_admin() && $q->is_main_query() && $q->is_search()) {
		$q->set('post_type', 'post');
	}
});

add_filter('excerpt_length', function () {
	return 28;
});

add_action('admin_notices', function () {
	if (!current_user_can('activate_plugins') || function_exists('acf_add_options_page')) {
		return;
	}
	echo '<div class="notice notice-warning"><p><strong>Imperial Horses theme:</strong> please install and activate <strong>Advanced Custom Fields PRO</strong>. The theme uses Repeater fields and the "Site Settings" options page, which are PRO features.</p></div>';
});

/**
 * On activation: create the pages, assign their templates, set the front page
 * and build the main menu (only when nothing like that exists yet).
 */
add_action('after_switch_theme', function () {
	$pages = [
		'home'       => ['Home', 'page-templates/template-home.php'],
		'about-us'   => ['About us', 'page-templates/template-about.php'],
		'services'   => ['Services', 'page-templates/template-services.php'],
		'projects'   => ['Projects', 'page-templates/template-projects.php'],
		'blogs'      => ['Blogs', 'page-templates/template-blogs.php'],
		'contact-us' => ['Contact us', 'page-templates/template-contact.php'],
	];
	$ids = [];
	foreach ($pages as $slug => $def) {
		$existing = get_page_by_path($slug);
		if ($existing) {
			$id = $existing->ID;
		} else {
			$id = wp_insert_post([
				'post_title'  => $def[0],
				'post_name'   => $slug,
				'post_status' => 'publish',
				'post_type'   => 'page',
			]);
		}
		if ($id && !is_wp_error($id)) {
			$tpl = get_post_meta($id, '_wp_page_template', true);
			if (!$tpl || $tpl === 'default') {
				update_post_meta($id, '_wp_page_template', $def[1]);
			}
			$ids[$slug] = $id;
		}
	}

	if (get_option('show_on_front') !== 'page' && !empty($ids['home'])) {
		update_option('show_on_front', 'page');
		update_option('page_on_front', $ids['home']);
	}

	$locations = get_nav_menu_locations();
	if (empty($locations['primary'])) {
		$menu = wp_get_nav_menu_object('Main Menu');
		$menu_id = $menu ? $menu->term_id : wp_create_nav_menu('Main Menu');
		if ($menu_id && !is_wp_error($menu_id) && !wp_get_nav_menu_items($menu_id)) {
			$order = [
				['page', 'home', 'Home'],
				['page', 'about-us', 'About us'],
				['page', 'services', 'Services'],
				['page', 'projects', 'Projects'],
				['page', 'blogs', 'Blogs'],
				['page', 'contact-us', 'Contact us'],
			];
			foreach ($order as $o) {
				if ($o[0] === 'page' && !empty($ids[$o[1]])) {
					wp_update_nav_menu_item($menu_id, 0, [
						'menu-item-title'     => $o[2],
						'menu-item-object'    => 'page',
						'menu-item-object-id' => $ids[$o[1]],
						'menu-item-type'      => 'post_type',
						'menu-item-status'    => 'publish',
					]);
				} elseif ($o[0] === 'custom') {
					wp_update_nav_menu_item($menu_id, 0, [
						'menu-item-title'  => $o[2],
						'menu-item-url'    => home_url('/projects/'),
						'menu-item-type'   => 'custom',
						'menu-item-status' => 'publish',
					]);
				}
			}
			set_theme_mod('nav_menu_locations', ['primary' => $menu_id, 'footer' => $menu_id]);
		}
	}

	flush_rewrite_rules();
});


/**
 * Sites that already had the theme active: create the Projects page once, and
 * make sure it uses the Projects template (so its ACF fields appear).
 */
add_action('admin_init', function () {
	if (get_option('ih_projects_page_v1')) {
		return;
	}
	$page = get_page_by_path('projects');
	if (!$page) {
		$id = wp_insert_post([
			'post_title'  => 'Projects',
			'post_name'   => 'projects',
			'post_status' => 'publish',
			'post_type'   => 'page',
		]);
	} else {
		$id = $page->ID;
	}
	if ($id && !is_wp_error($id)) {
		$tpl = get_post_meta($id, '_wp_page_template', true);
		if (!$tpl || $tpl === 'default') {
			update_post_meta($id, '_wp_page_template', 'page-templates/template-projects.php');
		}
		update_option('ih_projects_page_v1', 1);
		flush_rewrite_rules();
	}
});