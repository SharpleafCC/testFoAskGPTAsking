<?php 
// Load global post object. 
global $post;

// Only load the Buy Now Button if there is a link set in the Theme Settings. 
if(get_field('buy_now_button', 'option')):
	// get current post id
	$current_id = $post->ID;

	// Get list of page IDs to not show the button on. 
	if(get_field('buy_now_hide_on', 'option')):
		$hide_on = get_field('buy_now_hide_on', 'option');
	else: 
		$hide_on = array();
	endif;

	// If the current id is not in the array show the buy now sticky button on mobile only. 
	if(!in_array($current_id, $hide_on)):
		$button_link = get_field('buy_now_button', 'option');
	?>	
		<div class="button__buynow">
			<?php $button_link = get_field('buy_now_button', 'option'); ?>
			<a class="buy-now-button button button-accent" href="<?php echo esc_url($button_link['url']); ?>" target="<?php echo esc_attr($button_link['target']); ?>" aria-label="<?php echo esc_html($button_link['title']); ?>"><span><?php echo esc_html($button_link['title']); ?></span></a>
		</div>
	<?php endif; ?>
<?php endif; ?>