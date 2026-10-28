<?php
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Inline SVG icons. 's' = stroke icon, 'f' = filled icon, 'fe' = filled with even-odd rule.
 */
function ih_icon($name, $class = '') {
	static $icons = null;
	if ($icons === null) {
		$icons = [
			'arrow'        => ['s', '<path d="M5 12h14"/><path d="m13 6 6 6-6 6"/>'],
			'arrow-left'   => ['s', '<path d="M19 12H5"/><path d="m11 6-6 6 6 6"/>'],
			'search'       => ['s', '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>'],
			'phone'        => ['s', '<path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2z"/>'],
			'mail'         => ['s', '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/>'],
			'pin'          => ['s', '<path d="M12 21s-7-6.2-7-11a7 7 0 0 1 14 0c0 4.8-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/>'],
			'user'         => ['s', '<circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/>'],
			'building'     => ['s', '<rect x="5" y="3" width="14" height="18" rx="1"/><path d="M9 7h2M13 7h2M9 11h2M13 11h2M9 15h2M13 15h2"/>'],
			'list'         => ['s', '<path d="M8 6h13M8 12h13M8 18h13"/><circle cx="4" cy="6" r="1"/><circle cx="4" cy="12" r="1"/><circle cx="4" cy="18" r="1"/>'],
			'message'      => ['s', '<path d="M21 12a8 8 0 0 1-11.8 7L3 21l2-5.6A8 8 0 1 1 21 12z"/>'],
			'send'         => ['s', '<path d="m22 2-11 11"/><path d="M22 2 15 22l-4-9-9-4z"/>'],
			'chevron-down' => ['s', '<path d="m6 9 6 6 6-6"/>'],
			'award'        => ['s', '<circle cx="12" cy="9" r="6"/><path d="m8.5 14-1.5 8 5-3 5 3-1.5-8"/>'],
			'target'       => ['s', '<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="1.5"/>'],
			'users'        => ['s', '<circle cx="9" cy="8" r="3.5"/><path d="M2 21a7 7 0 0 1 14 0"/><circle cx="17" cy="9" r="2.5"/><path d="M17 14a5 5 0 0 1 5 5"/>'],
			'facebook'     => ['f', '<path d="M13.5 21v-7.5h2.6l.4-3h-3V8.6c0-.9.3-1.5 1.6-1.5h1.6V4.4c-.3 0-1.2-.1-2.3-.1-2.3 0-3.8 1.4-3.8 3.9v2.3H8v3h2.5V21h3z"/>'],
			'instagram'    => ['s', '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor"/>'],
			'whatsapp'     => ['fe', '<path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2z"/><path d="M16.6 14.4c-.2-.1-1.3-.6-1.5-.7-.2-.1-.3-.1-.5.1l-.7.9c-.1.2-.3.2-.5.1-.9-.4-1.6-.9-2.3-1.6-.6-.6-1-1.2-1.3-1.8-.1-.2 0-.3.1-.5l.4-.5.2-.3c.1-.1 0-.3 0-.4l-.7-1.6c-.2-.4-.4-.4-.5-.4h-.4c-.2 0-.4.1-.6.3-.2.2-.8.8-.8 1.9s.8 2.2.9 2.3c.1.2 1.6 2.5 3.9 3.5 1.4.6 1.9.6 2.6.5.4-.1 1.3-.5 1.5-1.1.2-.5.2-1 .1-1.1 0-.1-.2-.2-.4-.3z"/>'],
		];
	}
	if (!isset($icons[$name])) {
		return '';
	}
	list($type, $inner) = $icons[$name];
	if ($type === 's') {
		$attrs = 'fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"';
	} elseif ($type === 'fe') {
		$attrs = 'fill="currentColor" fill-rule="evenodd"';
	} else {
		$attrs = 'fill="currentColor"';
	}
	return sprintf(
		'<svg class="icon icon--%s %s" viewBox="0 0 24 24" width="1em" height="1em" aria-hidden="true" focusable="false" %s>%s</svg>',
		esc_attr($name),
		esc_attr($class),
		$attrs,
		$inner
	);
}
