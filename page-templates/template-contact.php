<?php
/**
 * Template Name: Contact
 */
get_header();

$ar = is_rtl();

$pid     = get_the_ID();
$email   = ih_opt('email', 'info@imphorsespiling.com');
$phone   = ih_opt('phone', '+971 50 116 8152');
$address = ih_opt('address', $ar ? 'مبنى سالم علي المزروعي - مكتب 213 - النهدة 2 - دبي' : 'SALIM ALI AL MAZROUA BUILDING - OFFICE 213 - AL NAHDA 2 - DUBAI');
$fb      = ih_opt('facebook');
$ig      = ih_opt('instagram');

// The map field accepts either a plain embed URL or the full <iframe> code from Google Maps.
$map = trim((string) ih_f('map_embed', ''));
if ($map && stripos($map, '<iframe') !== false && preg_match('/src=["\']([^"\']+)["\']/i', $map, $m)) {
	$map = html_entity_decode($m[1]);
}
?>
<main id="main" class="site-main">

	<?php get_template_part('template-parts/hero', null, [
		'eyebrow' => ih_f('hero_eyebrow', $ar ? 'تواصل معنا' : 'CONTACT US'),
		'title'   => ih_f('hero_title', $ar ? "هل أنت مستعد لبدء\nمشروع؟" : "Ready to start\na project?"),
		'bg'      => ih_f('hero_bg'),
	]); ?>

	<section class="contact">
		<div class="container contact__grid">
			<div class="contact__info">
				<h2><?php echo ih_lines(ih_f('intro_title', $ar ? "تواصل معنا،\nودعنا نعرف كيف نساعدك!" : "Get in touch,\nlet us know how we can help!")); ?></h2>
				<span class="contact__rule"></span>
				<dl>
					<dt><?php echo $ar ? 'البريد الإلكتروني' : 'Email'; ?>:</dt>
					<dd><a dir="ltr" href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></dd>
					<dt><?php echo $ar ? 'الهاتف \ واتساب' : 'Phone \ Whatsapp'; ?>:</dt>
					<dd><a dir="ltr" href="<?php echo esc_url(ih_tel($phone)); ?>"><?php echo esc_html($phone); ?></a></dd>
					<dt><?php echo $ar ? 'موقعنا' : 'Our location'; ?>:</dt>
					<dd><?php echo esc_html($address); ?></dd>
					<dt><?php echo $ar ? 'تابعنا' : 'Follow us'; ?>:</dt>
					<dd>
						<ul class="socials socials--dark">
							<?php if ($fb) : ?><li><a href="<?php echo esc_url($fb); ?>" target="_blank" rel="noopener" aria-label="<?php echo $ar ? 'فيسبوك' : 'Facebook'; ?>"><?php echo ih_icon('facebook'); ?></a></li><?php endif; ?>
							<?php if ($ig) : ?><li><a href="<?php echo esc_url($ig); ?>" target="_blank" rel="noopener" aria-label="<?php echo $ar ? 'إنستغرام' : 'Instagram'; ?>"><?php echo ih_icon('instagram'); ?></a></li><?php endif; ?>
						</ul>
					</dd>
				</dl>
			</div>

			<div class="contact__form">
				<?php
				$shortcode = trim((string) ih_f('form_shortcode', ''));
				if ($shortcode) {
					echo do_shortcode($shortcode);
				} else {
					ih_contact_form($pid);
				}
				?>
			</div>
		</div>

		<div class="container">
			<div class="map">
				<?php if ($map) : ?>
					<iframe src="<?php echo esc_url($map); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen title="<?php echo $ar ? 'الخريطة' : 'Map'; ?>"></iframe>
				<?php else : ?>
					<span class="ph"></span>
				<?php endif; ?>
			</div>
		</div>
	</section>

</main>
<?php get_footer();