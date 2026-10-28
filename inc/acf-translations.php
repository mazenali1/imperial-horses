<?php
/**
 * Dashboard-only translation of ACF field labels / instructions.
 *
 * Arabic content (or Arabic admin locale) -> Arabic labels.
 * English content                          -> English labels (as saved in acf-json).
 *
 * Nothing here touches field names/keys or saved data, so the front end is unaffected.
 * Put this file in /inc/ and add 'acf-translations' to the require_once list in functions.php.
 */
if (!defined('ABSPATH')) {
	exit;
}

/* --------------------------------------------------------------------------
   Language detection
   -------------------------------------------------------------------------- */

/** ID of the post being edited (also works for ACF ajax calls, e.g. adding a repeater row). */
function ih_acf_current_post_id() {
	// phpcs:disable WordPress.Security.NonceVerification
	foreach (['post', 'post_ID', 'post_id'] as $k) {
		$v = $_GET[$k] ?? $_POST[$k] ?? null;
		if ($v !== null && is_numeric($v) && (int) $v > 0) {
			return (int) $v;
		}
	}
	// phpcs:enable
	return 0;
}

/** True when the dashboard should show Arabic labels for the current screen. */
function ih_acf_is_arabic() {
	static $cache = [];
	$post_id = ih_acf_current_post_id();
	if (isset($cache[$post_id])) {
		return $cache[$post_id];
	}

	// Manual override: add_filter('ih_acf_is_arabic', fn($v, $id) => ..., 10, 2);
	$override = apply_filters('ih_acf_is_arabic', null, $post_id);
	if ($override !== null) {
		return $cache[$post_id] = (bool) $override;
	}

	$lang = '';

	// Polylang / WPML (if installed).
	if ($post_id && function_exists('pll_get_post_language')) {
		$lang = (string) pll_get_post_language($post_id, 'slug');
	} elseif ($post_id && has_filter('wpml_post_language_details')) {
		$d = apply_filters('wpml_post_language_details', null, $post_id);
		if (is_array($d) && !empty($d['language_code'])) {
			$lang = (string) $d['language_code'];
		}
	}
	// phpcs:disable WordPress.Security.NonceVerification
	if (!$lang && !empty($_GET['new_lang'])) {
		$lang = sanitize_key($_GET['new_lang']);
	}
	if (!$lang && !empty($_GET['lang']) && $_GET['lang'] !== 'all') {
		$lang = sanitize_key($_GET['lang']);
	}
	// phpcs:enable
	if (!$lang && function_exists('pll_current_language')) {
		$lang = (string) pll_current_language('slug');
	}
	if (!$lang && defined('ICL_LANGUAGE_CODE') && ICL_LANGUAGE_CODE !== 'all') {
		$lang = (string) ICL_LANGUAGE_CODE;
	}

	if ($lang) {
		return $cache[$post_id] = (strpos($lang, 'ar') === 0);
	}

	// No language plugin: judge by the page title (Arabic letters = Arabic page).
	if ($post_id) {
		$title = (string) get_post_field('post_title', $post_id);
		if ($title !== '' && $title !== 'Auto Draft') {
			return $cache[$post_id] = (bool) preg_match('/\p{Arabic}/u', $title);
		}
	}

	// Options page / new posts: follow the dashboard language.
	return $cache[$post_id] = (strpos(determine_locale(), 'ar') === 0);
}

/** Don't translate while building field groups in Custom Fields. */
function ih_acf_is_builder_screen() {
	$screen = function_exists('get_current_screen') ? get_current_screen() : null;
	if ($screen && !empty($screen->post_type) && strpos($screen->post_type, 'acf-') === 0) {
		return true;
	}
	return false;
}

/* --------------------------------------------------------------------------
   Arabic strings, keyed by ACF field key
   -------------------------------------------------------------------------- */

function ih_acf_ar_map() {
	$btn_instr = 'اكتب النص الذي يظهر على الزر. إذا تركته فارغًا سيأخذ عنوان الرابط أو النص الافتراضي.';

	return [
		/* ---------- About ---------- */
		'field_ih_about_tab_hero'      => ['label' => 'القسم العلوي'],
		'field_ih_about_hero_bg'       => ['label' => 'صورة الخلفية'],
		'field_ih_about_hero_eyebrow'  => ['label' => 'العنوان الصغير الأصفر'],
		'field_ih_about_hero_title'    => ['label' => 'العنوان الرئيسي'],
		'field_ih_about_tab_who_we_are'=> ['label' => 'من نحن'],
		'field_ih_about_who_image'     => ['label' => 'الصورة'],
		'field_ih_about_who_eyebrow'   => ['label' => 'العنوان الصغير'],
		'field_ih_about_who_title'     => ['label' => 'العنوان'],
		'field_ih_about_who_text'      => ['label' => 'النص'],
		'field_ih_about_tab_our_goal'  => ['label' => 'هدفنا'],
		'field_ih_about_goal_icon'     => ['label' => 'الأيقونة (اختياري)'],
		'field_ih_about_goal_title'    => ['label' => 'العنوان'],
		'field_ih_about_goal_text'     => ['label' => 'النص'],
		'field_ih_about_tab_cards'     => ['label' => 'الكروت'],
		'field_ih_about_cards'         => [
			'label'        => 'كروت الخبرة / المنهج / الفريق',
			'instructions' => 'تُرقَّم تلقائيًا (01، 02، 03...). إذا كان عدد الكروت فرديًا يأخذ آخر كارت العرض الكامل.',
			'button_label' => 'إضافة كارت',
		],
		'field_ih_about_cards__icon'   => ['label' => 'الأيقونة (اختياري)', 'instructions' => 'اتركه فارغًا لاستخدام الأيقونة الافتراضية.'],
		'field_ih_about_cards__title'  => ['label' => 'العنوان'],
		'field_ih_about_cards__text'   => ['label' => 'النص'],

		/* ---------- Blogs page ---------- */
		'field_ih_blogs_tab_hero'      => ['label' => 'القسم العلوي'],
		'field_blog_default_banner'    => ['label' => 'بانر المقال الافتراضي', 'instructions' => 'البانر المستخدم لأي مقال ليس له صورة بانر خاصة.'],
		'field_ih_blogs_hero_bg'       => ['label' => 'صورة الخلفية'],
		'field_ih_blogs_hero_eyebrow'  => ['label' => 'العنوان الصغير الأصفر'],
		'field_ih_blogs_hero_title'    => ['label' => 'العنوان الرئيسي'],
		'field_ih_blogs_tab_list'      => ['label' => 'القائمة'],
		'field_ih_blogs_list_message'  => ['message' => 'يتم جلب المقالات تلقائيًا من قسم <strong>المقالات</strong>.'],
		'field_ih_blogs_search_placeholder' => ['label' => 'نص مربع البحث'],
		'field_ih_blogs_per_page'      => ['label' => 'عدد المقالات في الصفحة'],
		'field_ih_blogs_empty_text'    => ['label' => 'النص عند عدم وجود مقالات'],

		/* ---------- Contact ---------- */
		'field_ih_contact_tab_hero'     => ['label' => 'القسم العلوي'],
		'field_ih_contact_hero_bg'      => ['label' => 'صورة الخلفية'],
		'field_ih_contact_hero_eyebrow' => ['label' => 'العنوان الصغير الأصفر'],
		'field_ih_contact_hero_title'   => ['label' => 'العنوان الرئيسي'],
		'field_ih_contact_tab_details'  => ['label' => 'التفاصيل'],
		'field_ih_contact_info_message' => ['message' => 'البريد الإلكتروني والهاتف والعنوان وروابط التواصل الاجتماعي تأتي من <strong>إعدادات الموقع</strong> لتبقى موحّدة في كل الموقع.'],
		'field_ih_contact_intro_title'  => ['label' => 'العنوان'],
		'field_ih_contact_tab_form'     => ['label' => 'النموذج'],
		'field_ih_contact_form_to'      => ['label' => 'إرسال الطلبات إلى', 'instructions' => 'اتركه فارغًا لاستخدام البريد الموجود في إعدادات الموقع.'],
		'field_ih_contact_services_list'=> ['label' => 'الخدمات المطلوبة (خدمة في كل سطر)'],
		'field_ih_contact_submit_label' => ['label' => 'نص زر الإرسال'],
		'field_ih_contact_success_text' => ['label' => 'رسالة النجاح'],
		'field_ih_contact_form_shortcode' => ['label' => 'الشورت كود للنموذج (اختياري)', 'instructions' => 'لاستخدام Contact Form 7 أو WPForms بدل النموذج المدمج، الصق الشورت كود هنا.'],
		'field_ih_contact_tab_map'      => ['label' => 'الخريطة'],
		'field_ih_contact_map_embed'    => ['label' => 'تضمين خريطة جوجل', 'instructions' => 'خرائط جوجل ← مشاركة ← تضمين خريطة ← الصق كود الـ iframe أو رابط src فقط.'],

		/* ---------- Blog post options ---------- */
		'field_ih_post_hero_image'      => ['label' => 'صورة خلفية القسم العلوي', 'instructions' => 'اختياري. الافتراضي هو الصورة البارزة.'],
		'field_ih_post_hide_featured'   => ['label' => 'إخفاء الصورة البارزة أعلى المقال'],

		/* ---------- Project ---------- */
		'field_ih_project_location'     => ['label' => 'الموقع'],
		'field_ih_project_client'       => ['label' => 'العميل'],
		'field_ih_project_scope'        => ['label' => 'نطاق العمل'],
		'field_ih_project_year'         => ['label' => 'السنة'],
		'field_ih_project_gallery'      => ['label' => 'المعرض'],

		/* ---------- Projects page ---------- */
		'field_ih_pp_tab_hero'          => ['label' => 'القسم العلوي'],
		'field_ih_pp_hero_eyebrow'      => ['label' => 'العنوان الصغير'],
		'field_ih_pp_hero_title'        => ['label' => 'العنوان الرئيسي', 'instructions' => 'سطر جديد = سطر جديد في العنوان.'],
		'field_ih_pp_hero_bg'           => ['label' => 'صورة الخلفية'],
		'field_ih_pp_tab_intro'         => ['label' => 'المقدمة'],
		'field_ih_pp_intro_title'       => ['label' => 'عنوان المقدمة'],
		'field_ih_pp_intro_text'        => ['label' => 'نص المقدمة'],
		'field_ih_pp_tab_list'          => ['label' => 'القائمة'],
		'field_ih_pp_per_page'          => ['label' => 'عدد المشاريع في الصفحة'],
		'field_ih_pp_empty_text'        => ['label' => 'النص عند عدم وجود مشاريع', 'instructions' => 'يظهر إذا لم توجد مشاريع.'],

		/* ---------- Services ---------- */
		'field_ih_services_tab_hero'    => ['label' => 'القسم العلوي'],
		'field_ih_services_hero_bg'     => ['label' => 'صورة الخلفية'],
		'field_ih_services_hero_eyebrow'=> ['label' => 'العنوان الصغير الأصفر'],
		'field_ih_services_hero_title'  => ['label' => 'العنوان الرئيسي'],
		'field_ih_services_tab_intro'   => ['label' => 'المقدمة'],
		'field_ih_services_intro_title' => ['label' => 'العنوان'],
		'field_ih_services_intro_text'  => ['label' => 'النص'],
		'field_ih_services_tab_services'=> ['label' => 'الخدمات'],
		'field_ih_services_items'       => [
			'label'        => 'الخدمات',
			'instructions' => 'تُرقَّم تلقائيًا. جهة الصورة تتبدّل بين اليمين واليسار.',
			'button_label' => 'إضافة خدمة',
		],
		'field_ih_services_items__image'    => ['label' => 'الصورة'],
		'field_ih_services_items__title'    => ['label' => 'العنوان'],
		'field_ih_services_items__subtitle' => ['label' => 'العنوان الفرعي'],
		'field_ih_services_items__text'     => ['label' => 'الوصف'],

		/* ---------- Home ---------- */
		'field_ih_home_tab_hero'        => ['label' => 'القسم العلوي'],
		'field_ih_home_hero_bg'         => ['label' => 'صورة الخلفية'],
		'field_ih_home_hero_eyebrow'    => ['label' => 'العنوان الصغير الأصفر'],
		'field_ih_home_hero_title'      => ['label' => 'العنوان الرئيسي'],
		'field_ih_home_hero_text'       => ['label' => 'النص'],
		'field_ih_home_hero_button'     => ['label' => 'الزر', 'instructions' => 'الافتراضي: "خدماتنا".'],
		'field_ih_home_hero_button_text'=> ['label' => 'نص الزر', 'instructions' => $btn_instr],

		'field_ih_home_tab_about'       => ['label' => 'من نحن'],
		'field_ih_home_about_image'    => ['label' => 'الصورة'],
		'field_ih_home_about_eyebrow'  => ['label' => 'العنوان الصغير'],
		'field_ih_home_about_title'    => ['label' => 'العنوان'],
		'field_ih_home_about_text'     => ['label' => 'النص'],
		'field_ih_home_about_button'   => ['label' => 'الزر', 'instructions' => 'الافتراضي: "اعرف المزيد" ← صفحة من نحن.'],
		'field_ih_home_about_button_text' => ['label' => 'نص الزر', 'instructions' => $btn_instr],

		'field_ih_home_tab_services'   => ['label' => 'الخدمات'],
		'field_ih_home_services_title' => ['label' => 'العنوان'],
		'field_ih_home_services_text'  => ['label' => 'النص'],
		'field_ih_home_services'       => ['label' => 'مربعات الخدمات', 'button_label' => 'إضافة مربع'],
		'field_ih_home_services__image'=> ['label' => 'الصورة'],
		'field_ih_home_services__title'=> ['label' => 'العنوان'],
		'field_ih_home_services__link' => ['label' => 'الرابط'],
		'field_ih_home_services_button'=> ['label' => 'الزر', 'instructions' => 'الافتراضي: "استكشف خدماتنا".'],
		'field_ih_home_services_button_text' => ['label' => 'نص الزر', 'instructions' => $btn_instr],

		'field_ih_home_tab_projects'      => ['label' => 'المشاريع'],
		'field_ih_home_projects_message'  => ['message' => 'يتم جلب المشاريع تلقائيًا من قائمة <strong>المشاريع</strong> في لوحة التحكم.'],
		'field_ih_home_projects_title'    => ['label' => 'العنوان'],
		'field_ih_home_projects_subtitle' => ['label' => 'العنوان الفرعي'],
		'field_ih_home_projects_text'     => ['label' => 'النص'],
		'field_ih_home_projects_count'    => ['label' => 'عدد المشاريع المعروضة'],
		'field_ih_home_projects_button'   => ['label' => 'الزر', 'instructions' => 'الافتراضي: "عرض كل المشاريع".'],
		'field_ih_home_projects_button_text' => ['label' => 'نص الزر', 'instructions' => $btn_instr],

		'field_ih_home_tab_clients'    => ['label' => 'العملاء'],
		'field_ih_home_clients_title'  => ['label' => 'العنوان'],
		'field_ih_home_clients_text'   => ['label' => 'النص'],
		'field_ih_home_clients'        => ['label' => 'شعارات العملاء', 'button_label' => 'إضافة عميل'],
		'field_ih_home_clients__logo'  => ['label' => 'الشعار'],
		'field_ih_home_clients__name'  => ['label' => 'اسم الشركة'],
		'field_ih_home_clients__url'   => ['label' => 'الموقع الإلكتروني (اختياري)'],

		'field_ih_home_tab_blogs'      => ['label' => 'المدونة'],
		'field_ih_home_blogs_message'  => ['message' => 'يتم جلب أحدث المقالات تلقائيًا من قسم <strong>المقالات</strong>.'],
		'field_ih_home_blogs_title'    => ['label' => 'العنوان'],
		'field_ih_home_blogs_text'     => ['label' => 'النص'],
		'field_ih_home_blogs_count'    => ['label' => 'عدد المقالات المعروضة'],

		'field_ih_home_tab_faq'        => ['label' => 'الأسئلة الشائعة'],
		'field_ih_home_faq_bg'         => ['label' => 'صورة خلفية القسم', 'instructions' => 'اختياري. تظهر خلف قسم الأسئلة بالكامل.'],
		'field_ih_home_faq_title'      => ['label' => 'العنوان'],
		'field_ih_home_faq_text'       => ['label' => 'النص'],
		'field_ih_home_faq'            => ['label' => 'الأسئلة', 'button_label' => 'إضافة سؤال'],
		'field_ih_home_faq__question'  => ['label' => 'السؤال'],
		'field_ih_home_faq__answer'    => ['label' => 'الإجابة'],

		'field_ih_home_tab_ready_to_start' => ['label' => 'جاهز للبدء'],
		'field_ih_home_ready_title_1'  => ['label' => 'العنوان (الجزء الداكن)'],
		'field_ih_home_ready_title_2'  => ['label' => 'العنوان (الجزء الأصفر)'],
		'field_ih_home_ready_text'     => ['label' => 'النص'],
		'field_ih_home_ready_button'   => ['label' => 'الزر', 'instructions' => 'الافتراضي: "احصل على عرض سعر" ← صفحة التواصل.'],
		'field_ih_home_ready_button_text' => ['label' => 'نص الزر', 'instructions' => $btn_instr],

		/* ---------- Site settings ---------- */
		'field_ih_settings_tab_header' => ['label' => 'الهيدر'],
		'field_ih_settings_logo'       => ['label' => 'الشعار', 'instructions' => 'يُستخدم في الهيدر (وفي الفوتر ما لم يتم تحديد شعار للفوتر).'],
		'field_ih_settings_lang_link'  => ['label' => 'رابط تبديل اللغة', 'instructions' => 'زر تبديل اللغة في الهيدر. العنوان = نص الزر، الرابط = رابط الموقع أو الصفحة باللغة الأخرى.'],
		'field_ih_settings_tab_contact_info' => ['label' => 'بيانات التواصل'],
		'field_ih_settings_email'      => ['label' => 'البريد الإلكتروني'],
		'field_ih_settings_phone'      => ['label' => 'الهاتف'],
		'field_ih_settings_whatsapp'   => ['label' => 'رقم واتساب', 'instructions' => 'بالصيغة الدولية. يشغّل زر الواتساب العائم. اتركه فارغًا لإخفائه.'],
		'field_ih_settings_address'    => ['label' => 'العنوان'],
		'field_ih_settings_tab_social' => ['label' => 'التواصل الاجتماعي'],
		'field_ih_settings_facebook'   => ['label' => 'رابط فيسبوك'],
		'field_ih_settings_instagram'  => ['label' => 'رابط إنستجرام'],
		'field_ih_settings_tab_footer' => ['label' => 'الفوتر'],
		'field_ih_settings_footer_logo'=> ['label' => 'شعار الفوتر', 'instructions' => 'نسخة فاتحة اختيارية من الشعار للفوتر الداكن.'],
		'field_ih_settings_commit_image' => ['label' => 'صورة شريط الالتزام'],
		'field_ih_settings_commit_label' => ['label' => 'عنوان شريط الالتزام'],
		'field_ih_settings_commit_line_1'=> ['label' => 'السطر 1 (أصفر)'],
		'field_ih_settings_commit_line_2'=> ['label' => 'السطر 2 (أبيض)'],
		'field_ih_settings_copyright'  => ['label' => 'نص حقوق النشر', 'instructions' => 'يتم استبدال {year} بالسنة الحالية تلقائيًا.'],
		'field_ih_settings_privacy_link' => ['label' => 'رابط سياسة الخصوصية'],
		'field_ih_settings_terms_link' => ['label' => 'رابط الشروط والأحكام'],
		'field_ih_settings_tab_cta_banner' => ['label' => 'بانر الدعوة لاتخاذ إجراء'],
		'field_ih_settings_cta_message'=> ['message' => 'يظهر هذا البانر في صفحات من نحن والخدمات والمدونة والتواصل، وفي الشريط الجانبي للمقال.'],
		'field_ih_settings_cta_title'  => ['label' => 'العنوان'],
		'field_ih_settings_cta_text'   => ['label' => 'النص'],
		'field_ih_settings_cta_button' => ['label' => 'الزر', 'instructions' => 'الافتراضي: "احصل على عرض سعر" ← صفحة التواصل.'],
		'field_ih_settings_cta_button_text' => ['label' => 'نص الزر', 'instructions' => $btn_instr],
		'field_ih_settings_cta_image'  => ['label' => 'صورة الخلفية'],
		'field_ih_settings_read_more_text' => [
			'label'        => 'نص "اقرأ المزيد" للمقالات',
			'instructions' => 'النص الذي يظهر على زر "Read More" في كروت المقالات. الافتراضي: Read More.',
		],
	];
}

/** Group (meta box) titles. */
function ih_acf_ar_group_titles() {
	return [
		'group_ih_about'         => 'صفحة من نحن',
		'group_ih_blogs'         => 'صفحة المدونة',
		'group_ih_contact'       => 'صفحة التواصل',
		'group_ih_post'          => 'خيارات المقال',
		'group_ih_project'       => 'تفاصيل المشروع',
		'group_ih_projects_page' => 'صفحة المشاريع',
		'group_ih_services'      => 'صفحة الخدمات',
		'group_ih_home'          => 'الصفحة الرئيسية',
		'group_ih_settings'      => 'إعدادات الموقع (الهيدر والفوتر وبانر الدعوة)',
	];
}

/**
 * Some instructions in the JSON were written in Arabic. In English mode
 * they should read in English, so they are replaced here.
 */
function ih_acf_en_instruction_fixes() {
	$btn = 'Type the text shown on the button. If left empty it uses the link title or the default text.';
	return [
		'field_ih_pp_hero_title'             => 'New line = new line in the title.',
		'field_ih_pp_empty_text'             => 'Shown when there are no projects.',
		'field_ih_home_hero_button_text'     => $btn,
		'field_ih_home_about_button_text'    => $btn,
		'field_ih_home_services_button_text' => $btn,
		'field_ih_home_projects_button_text' => $btn,
		'field_ih_home_ready_button_text'    => $btn,
		'field_ih_settings_cta_button_text'  => $btn,
		'field_ih_settings_read_more_text'   => 'The text shown on the "Read More" button on blog cards. Default: Read More.',
	];
}

/* --------------------------------------------------------------------------
   Hooks
   -------------------------------------------------------------------------- */

add_filter('acf/load_field', function ($field) {
	if (!is_admin() || empty($field['key']) || ih_acf_is_builder_screen()) {
		return $field;
	}

	if (ih_acf_is_arabic()) {
		static $ar = null;
		$ar = $ar ?? ih_acf_ar_map();
		if (isset($ar[$field['key']])) {
			foreach ($ar[$field['key']] as $prop => $value) {
				$field[$prop] = $value;
			}
		}
	} else {
		static $en = null;
		$en = $en ?? ih_acf_en_instruction_fixes();
		if (isset($en[$field['key']])) {
			$field['instructions'] = $en[$field['key']];
		}
	}

	return $field;
});

add_filter('acf/load_field_group', function ($group) {
	if (!is_admin() || empty($group['key']) || ih_acf_is_builder_screen() || !ih_acf_is_arabic()) {
		return $group;
	}
	$titles = ih_acf_ar_group_titles();
	if (isset($titles[$group['key']])) {
		$group['title'] = $titles[$group['key']];
	}
	return $group;
});