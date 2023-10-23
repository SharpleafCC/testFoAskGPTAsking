<?php if (have_rows('social', 'option')) : ?>
	<ul class="social py-10 list-none flex flex-row flex-nowrap gap-x-[10px] max-w-[90%] w-full">
		<?php while (have_rows('social', 'option')) : the_row();
			$image = get_sub_field('icon', 'option');
		?>
			<li class="border-none">
				<?php $link = get_sub_field('link', 'option'); ?>
				<a class="relative social__link no-underline p-0 block" href="<?php echo esc_url($link['url']); ?>" target="<?php echo esc_attr($link['target']); ?>" aria-label="<?php echo esc_html($link['title']); ?>">
					<?php
					if ( !empty($image) ) { ?>
						<img class="social__icon" alt="<?php echo $image['alt']; ?>" src="<?php echo $image['sizes']['960w']; ?>" /> <?php 
					} else {
						echo socialSvg( $link['title'] );
					}
					?>
				</a>
			</li>
		<?php endwhile; ?>
	</ul>
<?php endif; ?>