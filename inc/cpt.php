<?php
if (!defined('ABSPATH')) {
	exit;
}

add_action('init', function () {
	register_post_type('project', [
		'labels' => [
			'name'               => 'Projects',
			'singular_name'      => 'Project',
			'add_new_item'       => 'Add New Project',
			'edit_item'          => 'Edit Project',
			'new_item'           => 'New Project',
			'view_item'          => 'View Project',
			'search_items'       => 'Search Projects',
			'not_found'          => 'No projects found',
			'all_items'          => 'All Projects',
		],
		'public'        => true,
		'has_archive'   => false, // /projects/ is a real Page (Projects template) so its ACF fields work.
		'rewrite'       => ['slug' => 'projects'],
		'menu_icon'     => 'dashicons-hammer',
		'menu_position' => 21,
		'supports'      => ['title', 'editor', 'thumbnail', 'excerpt'],
		'show_in_rest'  => true,
	]);
});
