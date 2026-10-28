<?php
/**
 * Blog card (inside the loop). Args: layout ('col' | 'row').
 */
$layout = $args['layout'] ?? 'col';

$read_more = ih_opt('read_more_text', 'Read More');

if ( is_rtl() && $read_more === 'Read More' ) {
	$read_more = 'اقرأ المزيد';
}

$post_title = get_the_title();
$post_url   = get_permalink();
?>

<article class="post-card post-card--<?php echo esc_attr($layout); ?>">

	<a
		class="post-card__media"
		href="<?php echo esc_url($post_url); ?>"
		tabindex="-1"
		aria-hidden="true"
	>
		<?php
		echo has_post_thumbnail()
			? get_the_post_thumbnail(null, 'ih-card')
			: '<span class="ph"></span>';
		?>
	</a>

	<div class="post-card__body">

		<time
			class="post-card__date"
			datetime="<?php echo esc_attr(get_the_date('c')); ?>"
		>
			<?php echo esc_html(get_the_date('F d, Y')); ?>
		</time>

		<h3 class="post-card__title">
			<a href="<?php echo esc_url($post_url); ?>">
				<?php echo esc_html($post_title); ?>
			</a>
		</h3>

		<a class="post-card__more" href="<?php echo esc_url($post_url); ?>">
			<?php echo esc_html($read_more); ?>
			<span class="screen-reader-text">: <?php echo esc_html($post_title); ?></span>
			<span aria-hidden="true">
				<?php echo ih_icon('arrow'); ?>
			</span>
		</a>

	</div>
</article>