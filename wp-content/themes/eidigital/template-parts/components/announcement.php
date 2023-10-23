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

$override_global_announcement = null;
$selected_announcement_bar = null;

if ( is_tax() ) {
	$override_global_announcement = get_field('override_global_announcement_bar', $queried_object);
	$selected_announcement_bar = get_field('announcement_bar_select', $queried_object);
} else {
	$override_global_announcement = get_field('override_global_announcement_bar', $current_id);
	$selected_announcement_bar = get_field('announcement_bar_select', $current_id);
}

$announcement_bar_ids = [];

// If there's an override announcement bar on the page, use the ids provided instead of querying the announcement bar CPT
if ( $override_global_announcement ) {
	$announcement_bar_ids = $selected_announcement_bar;
} else {
	// Query args
	$args = array(
		'post_type' 			=> 'announcement_bar',
		'post_status' 		=> 'publish',
		'posts_per_page'	=> 1,
		'orderby'					=> 'menu_order',
		'order'						=> 'ASC',
		'meta_query' 		=> array(
			'relation' => 'AND',
			array(
				'key' 		=> 'active',
				'value' 	=> '1',
			),
			array(
				'key' 		=> 'hide_on',
				'value' 	=> '"' . $current_id . '"',
				'compare' => 'NOT LIKE'
			),
			array(
				'key' 		=> 'show_globally',
				'value' 	=> '1',
			)
		)
	);

	// Query announcements
	$query_bars = new WP_Query($args);

	if ( $query_bars->have_posts() ) {
		foreach ( $query_bars->posts as $post ) {
			array_push($announcement_bar_ids, $post->ID);
		}
	}

	wp_reset_postdata();
}

// Loop through our array of announcement bar ids and build the announcement bar
if ( !empty($announcement_bar_ids) ) :
	foreach ($announcement_bar_ids as $post_id) :
		//get the modified time of the post so that if it's updated, the cookie will be reset and then users will see the updated announcement bar (cookie busting)
		$post_modified = get_the_modified_time('U', $post_id);
		if (!isset($_COOKIE['announcement_bar_'.$post_id.'_'.$post_modified])) :

			// Get Sticky setting
			$make_it_sticky = get_field( 'make_it_sticky', $post_id );
			$data_sticky_top = '';
			$data_sticky = '';
			if ($make_it_sticky) {
				$position = get_field( 'sticky_position', $post_id );

				switch ($position) {
					case 'bottom':
						$pos = 'bottom is-sticky';
						break;

					case 'above_header':
						$pos = 'top above_header is-sticky';
						break;

					case 'below_header':
						$pos = 'top below_header is-sticky';
						break;
				}

				// Get background for on scroll sticky
				if(get_field( 'sticky_background_color', $post_id )):
					$data_sticky_top = get_field( 'background_color', $post_id );
					$data_sticky = get_field( 'sticky_background_color', $post_id );
				else: 
					$data_sticky_top = '';
					$data_sticky = '';
				endif;

			}

			// Get cookie duration else set to 1 day
			if(get_field( 'cookie_duration', $post_id )):
				$cookie_duration = get_field( 'cookie_duration', $post_id );
			else:
				$cookie_duration = 1;
			endif;
		?>

		<section data-cookie-name="announcement_bar_<?=$post_id?>_<?php echo $post_modified; ?>" data-cookie-duration="<?php echo $cookie_duration; ?>" id="announcement-<?php echo $GLOBALS['section_counter']; ?>" class="announcement announcement-<?php echo $post->post_name; ?> <?php echo $make_it_sticky ? $pos : 'not-sticky'; ?> z-30" data-top-color="<?php echo $data_sticky_top; ?>" data-sticky-color="<?php echo $data_sticky; ?>">
			<div class="annoucement-content">
				<span class="close cursor-pointer">
					<?php if(get_field( 'close_button_svg', $post_id )): ?>
						<?php 
							//if the close button is set to svg use the svg file, if that's not set, use the svg markup. Otherwise use the default x
						?>
							<?php if(get_field("svg_file", $post_id) ): ?>
								<img src="<?php echo get_field("svg_file", $post_id)['url']; ?>" alt="close button" />
							<?php else: ?>
								<?php echo get_field("svg_markup", $post_id); ?>
							<?php endif; ?>
					<?php else: ?>
						<span style="color:<?= get_field("close_x_color", $post_id) ?>;">X</span>
					<?php endif; ?>
				</span>
				<?php
				$pageBuilder = new PageBuilder;

				$pageBuilder->renderModules($post_id);
				?>
			</div>
		</section>
	<?php endif; ?>
<?php
	endforeach;
endif;
?>