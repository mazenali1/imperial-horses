<?php
if (!defined('ABSPATH')) {
	exit;
}

/* Save and load field groups as Local JSON inside the theme, so they show up
   in the ACF dashboard (Custom Fields) and travel with the theme. */
add_filter('acf/settings/save_json', function () {
	return IH_DIR . '/acf-json';
});
add_filter('acf/settings/load_json', function ($paths) {
	$paths[] = IH_DIR . '/acf-json';
	return array_values(array_unique($paths));
});

/* Shared header / footer content. */
add_action('acf/init', function () {
	if (!function_exists('acf_add_options_page')) {
		return;
	}
	acf_add_options_page([
		'page_title'  => 'Site Settings',
		'menu_title'  => 'Site Settings',
		'menu_slug'   => 'ih-settings',
		'capability'  => 'edit_posts',
		'icon_url'    => 'dashicons-admin-generic',
		'position'    => 59,
		'redirect'    => false,
		'update_button' => 'Save Settings',
	]);
});
