<?php
$post_id = $args['post_id'];
$post_image = get_post_thumbnail_id($post_id);
?>
<a href="<?= get_the_permalink($post_id) ?>" class="grid-item rounded-lg">
	<picture class="grid-item__picture">
		<source srcset="<?= wp_get_attachment_image_srcset($post_image) ?>">
		<img class="grid-item__image" alt="<?= get_post_meta($post_image, '_wp_attachment_image_alt', TRUE); ?>">
	</picture>
	<div class="grid-item__info">
		<h2 class="grid-item__name">
			<?php echo get_the_title($post_id); ?>
		</h2>
		<button class="button rounded-lg grid-item__button">Learn More</button>
	</div>
</a>