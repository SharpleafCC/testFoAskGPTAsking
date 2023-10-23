<?php
// Load global post object. 
global $post;

if(is_404()):
	$current_id = '';
elseif (is_tax()) :
	$queried_object = get_queried_object();
	$current_id = $queried_object->term_id;
else:
	// get current post id
	$current_id = $post->ID;
endif; 

// Need empty modal array in case we don't have any set. 
$query_modals = array();

// Check if this post/page has any modals
if (get_field('modals', $current_id)) :
	// Returns all the IDs of all modals on the page. 
	$query_modals = get_field('modals', $current_id);
endif;

// Check if we want to override the popup on the single page view option. 
if (get_field('override_global', $current_id)) :
	$override_global = get_field('popup', $current_id);
	// Query args
	$args = array(
		'fields'            => 'ids',
		'post_type' 		=> 'popup',
		'post_status' 		=> 'publish',
		'posts_per_page'	=> 10,
		'p'					=> $override_global[0]
	);
else :
	// Query args
	$args = array(
		'fields'            => 'ids',
		'post_type' 		=> 'popup',
		'post_status' 		=> 'publish',
		'posts_per_page'	=> -1,
		'orderby'			=> 'menu_order',
		'order'				=> 'ASC',
		'meta_query' 		=> array(
			'relation' => 'AND',
			// array(
			// 	'key' 		=> 'hide_on',
			// 	'value' 	=> $current_id ,
			// 	'compare' 	=> 'NOT LIKE'
			// ),
			array(
				'key' 		=> 'multiple_pages',
				'value' 	=> '1',
			)
		)
	);	
endif;

// Query popups
$query_popups = new WP_Query($args);

// Merge all popup ids into one array. 
$allTheIDs = array_merge($query_modals,$query_popups->posts);

// Run a final query to get all the popups in one result set
$final_args = array(
	'post__in' => $allTheIDs,
	'post_type' 		=> 'popup',
	'post_status' 		=> 'publish',
);

//create new empty query and populate it with the other two
$query_final = new WP_Query($final_args);

// ID counter
$counter = 0;

// Loop over all the posts
if ($query_final->have_posts()) :
	while ($query_final->have_posts()) : $query_final->the_post(); ?>
		<?php
		$post_id = get_the_ID();
		if(get_field('hide_on',$post_id)):
			$hide_on = get_field('hide_on',$post_id);
		else:
			$hide_on = array();
		endif;
		$cookie_id = 'popup-seen-' . $post_id; 
		$animation = get_field("animation");
		$limit_opening_after_close = get_field("limit_opening_after_close");
		$display_timing =  get_field("display_timing");
		?>
		<?php if(!in_array($current_id, $hide_on)): ?>
			<?php if (!isset($_COOKIE[$cookie_id]) || !$limit_opening_after_close) : ?>
				<section id="popup-<?php echo $counter; ?>" 
					class="popup popup-<?php echo $post->post_name; ?>" data-popup-trigger="<?= get_field('trigger_class'); ?>" 
					data-cookieid="<?php echo $cookie_id;?>"
					<?php echo( $limit_opening_after_close ? "data-expiration-days='". get_field('cookie_expiration_time')."'" : '') ?>
					<?php echo( $display_timing['display_method'] == 'onLoad' ? "data-display-on-load='true'" : ''); 
					if( $display_timing['display_method'] == 'rules'){
						echo(" data-display-by-rules='true' " );

						if( $display_timing['show_on_page_exit']){
							echo(" data-display-on-exit " );
						}
						if( $display_timing['time_based']){
							echo(" data-display-time-amount='".$display_timing['delay_time']."' " );
						}
						if( $display_timing['scroll_based']){
							echo(" data-display-scroll-amount='".$display_timing['scroll_amount']."' " );
						}
						//if more than one of the rules based options are chekced at a time, check to see if the "Show popup only when all conditions are met" rule is true.
						// we have to do this becuae the "Show popup only when all conditions are met" can be checked, even if only one rule is set
						if(($display_timing['show_on_page_exit'] && $display_timing['time_based']) || ($display_timing['show_on_page_exit'] && $display_timing['scroll_based']) ||  ($display_timing['time_based'] && $display_timing['scroll_based']) ){
							if($display_timing['all_conditions']){
								echo(" data-all-conditions='true' " );
							}
						}
					}
					
					?>
					<?php echo($animation['fade-in'] ? "data-fade-in-duration='".$animation['fade-in_duration']."'" : '') ?>
					<?php echo($animation['fly-in'] ? "data-fly-in-duration='".$animation['fly-in_duration']."'" : '') ?>
					>
					<div class="popup__content 
						<?php echo( $animation['fly-in'] ? "fly-in-".$animation['fly-in_direction'] : '') ?> 
						<?php echo($animation['fade-in'] ? "popup-fade-in" : '' )?> 
					" 
					<?php echo($animation['fade-in'] ? "data-fade-in-duration='".$animation['fade-in_duration']."'" : '') ?>
					<?php echo($animation['fly-in'] ? "data-fly-in-duration='".$animation['fly-in_duration']."'" : '') ?>
					>
						<span class="close close--popup">
							<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
								<path class="stroke-black cursor-pointer" stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
							</svg>
						</span>
						<?php
						$pageBuilder = new PageBuilder;
						$pageBuilder->renderModules($post_id);
						?>
					</div>
				</section>
			<?php endif; ?>
		<?php endif; ?>
<?php
		$counter++;
	endwhile;
	wp_reset_postdata();
endif;
?>