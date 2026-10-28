<?php
/**
 * Imperial Horses theme bootstrap.
 */
if (!defined('ABSPATH')) {
	exit;
}

define('IH_VERSION', '1.0.0');
define('IH_DIR', get_template_directory());
define('IH_URI', get_template_directory_uri());

foreach (['helpers', 'defaults', 'icons', 'setup', 'acf', 'acf-translations', 'cpt', 'contact-form'] as $ih_file) {
	require_once IH_DIR . '/inc/' . $ih_file . '.php';
}

/**
 * Image quality fixes.
 * - Larger size for the single-post featured image (default "large" is only 1024px wide).
 * - Stop WordPress from re-compressing JPEGs at 82% quality.
 */
add_action('after_setup_theme', function () {
	// 1600px wide, height auto, no hard crop. After adding this, regenerate old thumbnails once.
	add_image_size('ih-post-wide', 1600, 0, false);
});

add_filter('jpeg_quality', function () {
	return 100;
});
add_filter('wp_editor_set_quality', function () {
	return 100;
});


/**
 * Separate "poster" image field for single posts (independent from the Featured Image).
 */
add_action('acf/init', function () {
	if (!function_exists('acf_add_local_field_group')) {
		return;
	}
	acf_add_local_field_group([
		'key'      => 'group_ih_post_poster',
		'title'    => 'Post Poster',
		'fields'   => [
			[
				'key'           => 'field_ih_post_poster',
				'label'         => 'Poster Image (inside article)',
				'name'          => 'post_poster',
				'type'          => 'image',
				'instructions'  => 'Shown at the top of the article page. Independent from the Featured Image (which is used for blog cards).',
				'return_format' => 'id',
				'preview_size'  => 'medium',
				'library'       => 'all',
			],
		],
		'location' => [
			[
				[
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'post',
				],
			],
		],
		'position' => 'side',
	]);
});


add_action( 'customize_register', function ( $wp_customize ) {

    $wp_customize->add_section( 'ih_404', [
        'title'    => '404 Page',
        'priority' => 160,
    ] );

    $languages = [
        'en' => 'English',
        'ar' => 'العربية',
    ];

    foreach ( $languages as $lang => $label ) {

        $setting_id = 'ih_404_banner_' . $lang;

        $wp_customize->add_setting( $setting_id, [
            'default'           => '',
            'sanitize_callback' => 'absint',
        ] );

        $wp_customize->add_control(
            new WP_Customize_Media_Control(
                $wp_customize,
                $setting_id,
                [
                    'label'     => '404 Banner - ' . $label,
                    'section'   => 'ih_404',
                    'mime_type' => 'image',
                ]
            )
        );
    }
} );


add_filter('wp_editor_set_quality', function ($quality, $mime_type) {
	return $mime_type === 'image/webp' ? 78 : 80;
}, 10, 2);
