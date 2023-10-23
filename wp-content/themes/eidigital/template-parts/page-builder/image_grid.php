<?php $data = $args['data']; ?>
<section class="container max-w-screen-lg mx-auto my-10 <?php the_sub_field('class_name'); ?> <?php echo get_row_layout(); ?>">
	<div class="row row__wrapper">
		<div class="col-xs section-- section__content">
			<div class="">
				<?php
				$gallery = $data['image_gallery'];
				$gallery_layout = $data['layout'];
				// Set Image Grid class
				switch ($gallery_layout):
					case '1_center':
						$max = 1;
						break;
					case '2_columns':
						$max = 2;
						break;
					case '3_columns':
						$max = 3;
						break;
					case '2_top_1_below':
						$max = 3;
						break;
					case '1_top_2_below':
						$max = 3;
						break;
					case '4_left_1_right':
						$max = 5;
						break;
					default:
						$max = 1;
						break;
				endswitch;
				?>

				<div class="image__grid <?php echo 'grid_' . $gallery_layout; ?>">
					<?php for ($i = 0; $i < $max; $i++): ?>
						<div class="animate__item image__grid--image image__grid--<?php echo $i; ?>">
							<?php if($gallery[$i]['mime_type'] == 'image/gif'): ?>
								<img src="<?php echo $gallery[$i]['url']; ?>" >
							<?php elseif($gallery[$i]['mime_type'] == 'video/mp4'): ?>
								<video muted autoplay loop playsinline >
									<source src="<?php echo esc_url($gallery[$i]['url']); ?>" type="video/mp4" />
									Your browser does not support the video tag.
								</video>
							<?php else: ?>
								<img src="<?php echo $gallery[$i]['url']; ?>" >
							<?php endif; ?>
						</div>
					<?php endfor; ?>
				</div>
			</div>
		</div>
	</div>
</section>