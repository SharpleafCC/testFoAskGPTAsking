<?php
$data = $args['data'];
$theme_color = $data['theme_color'];
?>
<section id="<?= $data['section_id'] ?>" class="container w-full max-w-screen-xl mx-auto mt-10 <?= $data['class_name'] ?> <?= $data['visibility'] ?> <?= $data['acf_fc_layout'] ?>">
	<?php if ($data['title']): ?>
		<p class=""><?= $data['title'] ?></p>
	<?php endif; ?>

	<div class="related__posts--container <?= $data['wrapper_class'] ?>">
		<?php
		if($data['query_type'] == 'simple'):
			// Query args
			$args = array(
				'fields'            => 'ids',
				'post_type' 		=> $data['query_filteren_instances'],
				'post_status' 		=> 'publish',
				'posts_per_page'	=> $data['posts_per_page'],
				'orderby'			=> 'date',
				'order'				=> 'DESC',
				//'offset'			=> $data['offset'],
			);

			// Get the posts.
			$posts_query = new WP_Query($args);

			// Get only the IDs
			$related_posts = $posts_query->posts;

		else:
			$related_posts = $data['manual_posts'];
		endif;
		$template_part = 'template-parts/loop/' . $data['template_loop'];
		foreach ($related_posts as $post_id) :
			// Pass post ID to the template.
			$args = array(
				'post_id' => $post_id,
				'taxonomy'			=> 'category',
				'theme_color'		=> $theme_color,
			);				
			echo '<article id="post-' . $post_id . '" class="' . $data['item_classes'] . '">';
			get_template_part($template_part, null, $args);
			echo '</article>';
		endforeach;
		?>
	</div>
	<?php if ( !empty($data['cta']['cta__link']) ): ?>
		<div class="flex items-center justify-center my-10">
			<?php get_template_part( 'template-parts/page-builder/cta', '', ['data' => $data['cta']] ); ?>
		</div>
	<?php endif; ?>	
</section>