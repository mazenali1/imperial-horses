<?php
if (!defined('ABSPATH')) {
	exit;
}

add_action('admin_post_nopriv_ih_contact', 'ih_handle_contact');
add_action('admin_post_ih_contact', 'ih_handle_contact');

function ih_handle_contact() {
	$ref  = wp_get_referer();
	$back = $ref ? remove_query_arg(['ih_sent', 'ih_err'], $ref) : home_url('/');

	$fail = function ($code) use ($back) {
		wp_safe_redirect(add_query_arg('ih_err', $code, $back) . '#contact-form');
		exit;
	};

	$nonce = isset($_POST['ih_nonce']) ? sanitize_text_field(wp_unslash($_POST['ih_nonce'])) : '';
	if (!$nonce || !wp_verify_nonce($nonce, 'ih_contact')) {
		$fail('nonce');
	}

	// Honeypot: bots fill this in, humans never see it.
	if (!empty($_POST['ih_website'])) {
		wp_safe_redirect(add_query_arg('ih_sent', '1', $back) . '#contact-form');
		exit;
	}

	$name    = sanitize_text_field(wp_unslash($_POST['ih_name'] ?? ''));
	$phone   = sanitize_text_field(wp_unslash($_POST['ih_phone'] ?? ''));
	$email   = sanitize_email(wp_unslash($_POST['ih_email'] ?? ''));
	$company = sanitize_text_field(wp_unslash($_POST['ih_company'] ?? ''));
	$service = sanitize_text_field(wp_unslash($_POST['ih_service'] ?? ''));
	$message = sanitize_textarea_field(wp_unslash($_POST['ih_message'] ?? ''));

	if ($name === '' || !is_email($email) || $message === '') {
		$fail('missing');
	}

	$rate_key = 'ih_cf_' . md5($_SERVER['REMOTE_ADDR'] ?? 'x');
	if (get_transient($rate_key)) {
		$fail('rate');
	}

	$page_id = (int) ($_POST['ih_page'] ?? 0);
	$to      = ($page_id && function_exists('get_field')) ? get_field('form_to', $page_id) : '';
	if (!is_email($to)) {
		$to = ih_opt('email');
	}
	if (!is_email($to)) {
		$to = get_option('admin_email');
	}

	$subject = sprintf('[%s] New enquiry from %s', wp_specialchars_decode(get_bloginfo('name'), ENT_QUOTES), $name);
	$body    = "Name: {$name}\nPhone: {$phone}\nEmail: {$email}\nCompany: {$company}\nInterested service: {$service}\n\nMessage:\n{$message}\n";
	$headers = ['Content-Type: text/plain; charset=UTF-8', 'Reply-To: ' . $name . ' <' . $email . '>'];

	if (wp_mail($to, $subject, $body, $headers)) {
		set_transient($rate_key, 1, 30);
		wp_safe_redirect(add_query_arg('ih_sent', '1', $back) . '#contact-form');
		exit;
	}
	$fail('mail');
}

/** Print the built-in contact form for page $page_id. */
function ih_contact_form($page_id) {
	$services = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) ih_f('services_list', ih_def('contact_services'), $page_id))));
	$errors   = [
		'nonce'   => 'Your session expired. Please try again.',
		'missing' => 'Please fill in your name, a valid email and your message.',
		'rate'    => 'Please wait a few seconds before sending another message.',
		'mail'    => 'Sorry, the message could not be sent. Please try again or call us.',
	];
	$err = isset($_GET['ih_err']) ? sanitize_key(wp_unslash($_GET['ih_err'])) : '';
	?>
	<?php if (isset($_GET['ih_sent'])) : ?>
		<div class="notice notice--ok" role="status"><?php echo esc_html(ih_f('success_text', 'Thank you! Your message has been sent. We will get back to you shortly.', $page_id)); ?></div>
	<?php elseif ($err && isset($errors[$err])) : ?>
		<div class="notice notice--err" role="alert"><?php echo esc_html($errors[$err]); ?></div>
	<?php endif; ?>

	<form class="contact-form" id="contact-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
		<input type="hidden" name="action" value="ih_contact">
		<input type="hidden" name="ih_page" value="<?php echo (int) $page_id; ?>">
		<?php wp_nonce_field('ih_contact', 'ih_nonce'); ?>
		<div class="hp" aria-hidden="true"><label>Website<input type="text" name="ih_website" tabindex="-1" autocomplete="off"></label></div>

		<div class="contact-form__grid">
			<label class="field"><span class="field__icon"><?php echo ih_icon('user'); ?></span><input type="text" name="ih_name" placeholder="الاسم بالكامل..." required></label>
			<label class="field"><span class="field__icon"><?php echo ih_icon('phone'); ?></span><input type="tel" name="ih_phone" placeholder="رقم الهاتف"></label>
			<label class="field"><span class="field__icon"><?php echo ih_icon('mail'); ?></span><input type="email" name="ih_email" placeholder="البريد الإلكتروني" required></label>
			<label class="field"><span class="field__icon"><?php echo ih_icon('building'); ?></span><input type="text" name="ih_company" placeholder="اسم الشركة"></label>
			<label class="field field--select field--full"><span class="field__icon"><?php echo ih_icon('list'); ?></span>
				<select name="ih_service">
					<option value="" selected disabled>الخدمة المطلوبة</option>
					<?php foreach ($services as $s) : ?>
						<option value="<?php echo esc_attr($s); ?>"><?php echo esc_html($s); ?></option>
					<?php endforeach; ?>
				</select>
				<span class="field__chev"><?php echo ih_icon('chevron-down'); ?></span>
			</label>
			<label class="field field--area field--full"><span class="field__icon"><?php echo ih_icon('message'); ?></span><textarea name="ih_message" rows="7" placeholder="رسالتك" required></textarea></label>
		</div>
		<button class="contact-form__submit" type="submit"><?php echo esc_html(ih_f('submit_label', 'Send Message', $page_id)); ?> <?php echo ih_icon('send'); ?></button>
	</form>
	<?php
}


add_filter('wpcf7_autop_or_not', '__return_false');

add_filter('wpcf7_form_elements', function ($html) {
	return preg_replace_callback('/\{\{icon:([a-z-]+)\}\}/', function ($m) {
		return ih_icon($m[1]);
	}, $html);
});