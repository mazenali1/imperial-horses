<?php
/**
 * Project card (inside the loop).
 */
$loc = ih_f('location');
?>
<article class="project-card">
	<a class="project-card__link" href="<?php the_permalink(); ?>">
		<?php echo has_post_thumbnail() ? get_the_post_thumbnail(null, 'ih-portrait') : '<span class="ph"></span>'; ?>
		<span class="project-card__body">
			<span class="project-card__title"><?php the_title(); ?></span>
			<?php if ($loc) : ?><span class="project-card__meta"><?php echo esc_html($loc); ?></span><?php endif; ?>
		</span>
	</a>
</article>
