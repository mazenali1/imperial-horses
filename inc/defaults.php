<?php
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Fallback content used when an ACF field has not been filled in yet.
 * Matches the approved design text.
 */
function ih_def($key) {
	static $d = null;
	if ($d === null) {
		$d = [
			'about_text' => '<p>Imperial Horses Piling &amp; Foundation Contracting LLC is a specialized UAE-based contracting company providing professional piling, shoring, and foundation solutions for residential, commercial, and infrastructure projects.</p><p>We are committed to delivering safe, reliable, and high-quality solutions through experienced professionals, modern construction techniques, and strict compliance with approved designs and authority requirements.</p>',
			'home_services' => [
				['title' => 'Piling Work'],
				['title' => 'Shoring Work'],
				['title' => 'Soil Improvement'],
				['title' => 'Soil Grouting'],
			],
			'faq' => [
				['question' => 'What piling services do you provide?', 'answer' => 'We provide professional piling and deep foundation solutions for a wide range of construction projects, including bored piles, driven piles, micropiles, and other specialized foundation systems.'],
				['question' => 'How experienced is Imperial Horses?', 'answer' => 'Our team brings hands-on experience delivering piling and foundation projects across residential, commercial, and infrastructure sectors in the UAE.'],
				['question' => 'Where do you provide your services?', 'answer' => 'We provide our services across the UAE, supporting projects in all emirates.'],
				['question' => 'What other geotechnical services do you offer?', 'answer' => 'We also offer shoring, soil improvement, and soil grouting solutions to support safe and stable construction.'],
			],
			'about_cards' => [
				['title' => 'Experience', 'text' => 'Imperial Horses has experience in executing geotechnical support and foundation works across a range of residential, commercial, and infrastructure projects in the UAE.'],
				['title' => 'Our Approach', 'text' => 'We focus on delivering technically sound, safe, and efficient solutions while maintaining close coordination with consultants, contractors, clients, and relevant authorities.'],
				['title' => 'Our Team', 'text' => 'Our team consists of experienced engineers, technical professionals, and skilled site personnel with practical expertise in complex excavation support and foundation works. We have successfully worked on projects involving deep excavations, neighboring structures, groundwater challenges, secant piling, steel beam shoring, and ground support systems.'],
			],
			'services' => [
				['title' => 'Piling Work', 'subtitle' => 'Deep Foundation Solutions', 'text' => 'We provide professional piling solutions designed to transfer structural loads to deeper, stronger soil layers, ensuring stability and long-term performance for buildings and infrastructure projects.'],
				['title' => 'Shoring Work', 'subtitle' => 'Safe & Stable Excavation Solutions', 'text' => 'We provide reliable excavation shoring systems to support excavation sides, protect surrounding structures, and ensure safe working conditions throughout the construction process.'],
				['title' => 'Soil Improvement', 'subtitle' => 'Enhanced Ground Performance', 'text' => 'Our soil improvement solutions enhance the strength and load-bearing capacity of weak or unstable ground, creating safer and more reliable foundations for construction.'],
				['title' => 'Soil Grouting', 'subtitle' => 'Ground Stabilization Solutions', 'text' => 'We use specialized grouting techniques to strengthen soil, reduce permeability, fill voids, and improve ground conditions for demanding construction projects.'],
			],
			'contact_services' => "Piling Work\nShoring Work\nSoil Improvement\nSoil Grouting",
		];
	}
	return $d[$key] ?? '';
}
