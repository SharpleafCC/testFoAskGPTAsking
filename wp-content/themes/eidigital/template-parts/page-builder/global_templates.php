<section id="<?php echo get_row_layout() . '-' . $GLOBALS['section_counter']; ?>" class="global__template">
	<div class="row row__wrapper">
		<div class="col-xs">
			<?php
			$template = get_sub_field('template');
			$post_id = $template[0];
			?>
			<?php
			if (have_rows('content_blocks', $post_id)) :
				while (have_rows('content_blocks', $post_id)) :
					the_row();
					$GLOBALS['section_counter']++;
					include(locate_template('template-parts/content_blocks/block-parts/block-fields.php'));
					include(locate_template('template-parts/content_blocks/block-parts/block-styles.php'));


					$partial = 'template-parts/content_blocks/' . get_row_layout() . '.php';

					if (!$template = locate_template($partial, false, false)) {
						$message = `${partial} file not found`;
						error_log($message);
						printf('<h4>%s</h4>', $partial . ' file not found');
						return false;
					}

					include($template);
				endwhile;
			endif;
			?>
		</div>
	</div>
</section>