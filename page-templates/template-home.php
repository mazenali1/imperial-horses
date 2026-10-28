<?php
/**
 * Template Name: Home
 */
get_header();

$services_url = ih_page_url('services');
$hero_bg      = ih_img_url(ih_f('hero_bg'), 'ih-hero');
?>
<main id="main" class="site-main">

	<!-- Hero -->
	<section class="hero hero--home"<?php echo $hero_bg ? ' style="--hero-bg:url(' . esc_url($hero_bg) . ')"' : ''; ?>>
		<div class="hero__overlay"></div>
		<div class="container hero__inner">
			<p class="hero__kicker"><?php echo esc_html(ih_f('hero_eyebrow', 'BUILDING STRONGER FOUNDATIONS')); ?></p>
			<h1 class="hero__title"><?php echo ih_lines(ih_f('hero_title', 'GEOTECHNICAL SOLUTIONS FOR STRONGER FOUNDATIONS')); ?></h1>
			<p class="hero__text"><?php echo ih_lines(ih_f('hero_text', 'Precision-driven geotechnical solutions across the UAE.')); ?></p>
			<?php ih_btn(ih_f('hero_button'), 'outline', 'Our services', $services_url, ih_f('hero_button_text')); ?>
		</div>
	</section>

	<!-- About -->
	<section class="about-home">
		<div class="container">
			<div class="about-home__panel">
				<div class="about-home__media"><?php echo ih_media(ih_f('about_image'), 'large'); ?></div>
				<div class="about-home__body">
					<h2 class="title-sm"><?php echo esc_html(ih_f('about_title', 'ABOUT IMPERIAL HORSES')); ?></h2>
					<div class="prose prose--sm"><?php echo wp_kses_post(ih_f('about_text', ih_def('about_text'))); ?></div>
					<?php ih_btn(ih_f('about_button'), 'light', 'Know more', ih_page_url('about-us'), ih_f('about_button_text')); ?>
				</div>
			</div>
		</div>
	</section>

	<!-- Services -->
	<section class="home-services">
		<div class="container">
			<header class="section-head">
				<h2><?php echo esc_html(ih_f('services_title', 'OUR SERVICES')); ?></h2>
				<p><?php echo esc_html(ih_f('services_text', 'From deep foundations to ground improvement, we deliver specialized solutions that meet the needs of modern construction projects across the UAE.')); ?></p>
			</header>
			<div class="tiles">
				<?php foreach (ih_f('services', ih_def('home_services')) as $tile) :
					$link = !empty($tile['link']['url']) ? $tile['link']['url'] : $services_url; ?>
					<a class="tile" href="<?php echo esc_url($link); ?>">
						<?php echo ih_media($tile['image'] ?? 0, 'ih-tile'); ?>
						<span class="tile__label"><?php echo esc_html($tile['title'] ?? ''); ?></span>
					</a>
				<?php endforeach; ?>
			</div>
			<div class="center"><?php ih_btn(ih_f('services_button'), 'light', 'Explore Our Services', $services_url, ih_f('services_button_text')); ?></div>
		</div>
	</section>

	<!-- Projects -->
	<section class="home-projects">
		<div class="container">
			<header class="section-head section-head--dark">
				<h2 class="yellow"><?php echo esc_html(ih_f('projects_title', 'OUR PROJECTS')); ?></h2>
				<p class="section-head__sub"><?php echo esc_html(ih_f('projects_subtitle', 'Building Excellence Through Every Project')); ?></p>
				<p><?php echo esc_html(ih_f('projects_text', 'Explore our foundation and geotechnical projects, delivered with precision and expertise across the UAE.')); ?></p>
			</header>
			<?php
			$projects = new WP_Query([
				'post_type'      => 'project',
				'posts_per_page' => max(1, (int) ih_f('projects_count', 4)),
				'no_found_rows'  => true,
			]);
			if ($projects->have_posts()) : ?>
				<div class="project-grid">
					<?php while ($projects->have_posts()) : $projects->the_post(); get_template_part('template-parts/project-card'); endwhile; wp_reset_postdata(); ?>
				</div>
			<?php elseif (current_user_can('edit_posts')) : ?>
				<div class="project-grid project-grid--empty">
					<?php for ($i = 0; $i < 4; $i++) : ?><div class="project-card"><span class="ph"></span></div><?php endfor; ?>
				</div>
				<p class="admin-hint">Only you can see these placeholders. Add items from <strong>Projects &rarr; Add New</strong> in the dashboard.</p>
			<?php endif; ?>
			<div class="center"><?php ih_btn(ih_f('projects_button'), 'outline', 'View All Projects', ih_page_url('projects'), ih_f('projects_button_text')); ?></div>
		</div>
	</section>

	<!-- Clients -->
	<?php $clients = ih_f('clients', []); ?>
	<?php if ($clients) : ?>
	<section class="clients">
		<div class="container">
			<header class="section-head">
				<h2><?php echo esc_html(ih_f('clients_title', 'OUR CLIENTS')); ?></h2>
				<p><?php echo esc_html(ih_f('clients_text', 'Trusted by leading companies across the UAE.')); ?></p>
			</header>
			<ul class="clients__grid">
				<?php foreach ($clients as $c) :
					$img = ih_img($c['logo'] ?? 0, 'medium', ['alt' => $c['name'] ?? '', 'loading' => 'lazy']);
					if (!$img) { continue; } ?>
					<li>
						<?php if (!empty($c['url'])) : ?><a href="<?php echo esc_url($c['url']); ?>" target="_blank" rel="noopener"><?php echo $img; ?></a><?php else : echo $img; endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>
	<?php endif; ?>

	<!-- Blogs -->
	<section class="home-blogs">
		<div class="container">
			<header class="section-head">
				<h2><?php echo esc_html(ih_f('blogs_title', 'OUR BLOGS')); ?></h2>
				<p><?php echo esc_html(ih_f('blogs_text', 'Latest insights and trends in piling and geotechnical engineering across the UAE.')); ?></p>
			</header>
			<?php
			$posts = new WP_Query([
				'post_type'           => 'post',
				'posts_per_page'      => max(1, (int) ih_f('blogs_count', 3)),
				'ignore_sticky_posts' => true,
				'no_found_rows'       => true,
			]);
			if ($posts->have_posts()) : ?>
				<div class="post-grid">
					<?php while ($posts->have_posts()) : $posts->the_post(); get_template_part('template-parts/post-card', null, ['layout' => 'col']); endwhile; wp_reset_postdata(); ?>
				</div>
			<?php endif; ?>
		</div>
	</section>

<!-- FAQ -->
<?php $faq_bg = ih_img_url(ih_f('faq_bg'), 'full'); ?>
<section class="faq"<?php echo $faq_bg ? ' style="--faq-bg:url(' . esc_url($faq_bg) . ')"' : ''; ?>>
		<div class="container faq__inner">
			<div class="faq__intro">
				<h2><?php echo esc_html(ih_f('faq_title', 'HAVE QUESTIONS?')); ?></h2>
				<p><?php echo esc_html(ih_f('faq_text', 'Find answers to the most common questions about our services and processes.')); ?></p>
			</div>
			<div class="faq__list">
				<?php foreach (ih_f('faq', ih_def('faq')) as $i => $item) : ?>
					<div class="faq__item<?php echo $i === 0 ? ' is-open' : ''; ?>">
						<button class="faq__q" type="button" aria-expanded="<?php echo $i === 0 ? 'true' : 'false'; ?>">
							<span class="faq__mark" aria-hidden="true">?</span>
							<span class="faq__q-text"><?php echo esc_html($item['question'] ?? ''); ?></span>
							<span class="faq__icon" aria-hidden="true"></span>
						</button>
						<div class="faq__a"><p><?php echo esc_html($item['answer'] ?? ''); ?></p></div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<!-- Ready to start -->
	<section class="ready">
		<div class="container center">
			<h2 class="ready__title"><?php echo esc_html(ih_f('ready_title_1', 'READY TO START')); ?> <span><?php echo esc_html(ih_f('ready_title_2', 'A PROJECT?')); ?></span></h2>
			<p><?php echo esc_html(ih_f('ready_text', "Let's build a strong foundation for your next project.")); ?></p>
			<?php ih_btn(ih_f('ready_button'), 'light', 'Get a Quote', ih_contact_url(), ih_f('ready_button_text')); ?>
		</div>
	</section>

</main>
<?php get_footer();
