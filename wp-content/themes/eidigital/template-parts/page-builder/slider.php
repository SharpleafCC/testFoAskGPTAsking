<?php
$data = $args['data'];

// Get ID of the Slider Post
$slides = get_sub_field('slider');

$flex_field_array = get_post_meta($data['slider'][0], 'content_blocks', true);
$count = 0;
if (is_array($flex_field_array)) {
	$count = count($flex_field_array);
}

// Retrieve ACF fields values and store them in variables.
$arrow_color = get_field('arrow_color_theme_color', $data['slider'][0]);
$arrow_hover_color = get_field('arrow_hover_color_theme_color', $data['slider'][0]);
$arrow_size = get_field('arrow_size', $data['slider'][0]);
$dot_color = get_field('dot_color_theme_color', $data['slider'][0]);
$dot_active_color = get_field('dot_active_color_theme_color', $data['slider'][0]);
$dot_size = get_field('dot_size', $data['slider'][0]);
$prev_arrow_svg = get_field('prev_arrow_svg', $data['slider'][0]);
$next_arrow_svg = get_field('next_arrow_svg', $data['slider'][0]);

$slides_per_view = array();
$slides_per_view = get_field('slides_per_view', $data['slider'][0]);
$slides_per_view = implode(",", $slides_per_view);

$animation_duration = array();
$animation_duration = get_field('animation_duration', $data['slider'][0]);
$animation_duration = implode(",", $animation_duration);

$autoplay_enabled = array();
$autoplay_enabled = get_field('enable_auto_play', $data['slider'][0]);

//these are the "interval" values
$autoplay = array();
$autoplay = get_field('autoplay', $data['slider'][0]);
$autoplay = implode(",", $autoplay);

$autoscroll = array();	
$autoscroll = get_field('auto_scroll', $data['slider'][0]);

if($autoplay_enabled == 'true'){
	$autoscroll = false;
}

$autoscrollspeed = array();
$autoscrollspeed = get_field('auto_scroll_speed', $data['slider'][0]);

$unique_id = "slider-".wp_generate_uuid4();

// Check if we want to show arrows.
if (get_field('show_arrows', $data['slider'][0])):
	$arrows = "true";
else:
	$arrows = "false";
endif;

// Check if we want to show dots.
if (get_field('show_dots', $data['slider'][0])):
	$dots = "true";
else:
	$dots = "false";
endif;

// Split the values into arrays
$autoplay_values = explode(',', $autoplay);
//technically, you can't dynamically set auto play itself on or off based on breakpoints. So, we're going to set the transition time value to a really high number if it's set to 0 or empty for a given breakpoint. 
foreach ($autoplay_values as $key => $value) {
	if (empty($value) || $value == 0) {
		$autoplay_values[$key] = 99999999999;
	}
}
$slides_per_view_values = explode(',', $slides_per_view);
$animation_duration_values = explode(',', $animation_duration);

$splide_options = array(
	"autoplay" => $autoplay_enabled,
    "interval" => $autoplay_values[3],
    "perPage" => $slides_per_view_values[3],
    "speed" => $animation_duration_values[3],
	"type" => "loop",
    "easing" => get_field('animation_timing', $data['slider'][0]),
    "arrows" => $arrows,
    "pagination" => $dots,
    "breakpoints" => array(
        "768" => array(
            "interval" => $autoplay_values[0],
            "perPage" => $slides_per_view_values[0],
            "speed" => $animation_duration_values[0],
        ),
        "1024" => array(
            "interval" => $autoplay_values[1],
            "perPage" => $slides_per_view_values[1],
            "speed" => $animation_duration_values[1],
        ),
        "1440" => array(
            "interval" => $autoplay_values[2],
            "perPage" => $slides_per_view_values[2],
            "speed" => $animation_duration_values[2],
        )
    )
);
$additionalOptions = get_field('additional_splide_options', $data['slider'][0]);
if (!empty($additionalOptions)) {
    foreach ($additionalOptions as $option) {
		// print_r($option);
        $breakpoint = $option['options']['breakpoint'];
        $splideKey = $option['options']['option'];
        $splideValue = $option['options']['value'];
        if ($breakpoint !== 'default') {
            $splide_options['breakpoints'][$breakpoint][$splideKey] = $splideValue;
        } else {
            $splide_options[$splideKey] = $splideValue;
        }
    }
}

$splide_options_json = htmlspecialchars(json_encode($splide_options), ENT_QUOTES, 'UTF-8');
?>
<style>
	#<?= $unique_id ?> .splide__track {
		margin: 0;
	}
	#<?= $unique_id ?> .splide__slide {
		margin: 0;
	}
	#<?= $unique_id ?> .splide__pagination {
		margin: 0;
		padding: 0;
		list-style: none;
		text-align: center;
	}
	#<?= $unique_id ?> .splide__pagination__page {
		background: <?= $dot_color ?>;
		border: 0;
		border-radius: 50%;
		display: inline-block;
		height: <?= $dot_size ?>px;
		margin: 0 0.1rem;
		padding: 0;
		position: relative;
		transition: background-color 0.2s ease, color 0.2s ease;
		width: <?= $dot_size ?>px;
		display: inline-block;
		margin: 0 0.5rem;
		padding: 0;
		list-style: none;
		text-align: center;
		cursor: pointer;
	}
	#<?= $unique_id ?> .splide__pagination__page.is-active {
		background: <?= $dot_active_color ?>;
	}
	#<?= $unique_id ?> .splide__arrows {
		width: 100%;
		position: absolute;
		display: flex;
		justify-content: space-between;
		align-items: center;
		top: 50%;
		transform: translateY(-50%);
	}

	#<?= $unique_id ?> .splide__arrows .splide__arrow {
		background: <?= $arrow_color ?>;
		border: 0;
		border-radius: 50%;
		color: #ffffff;
		cursor: pointer;
		display: inline-block;
		font-size: 0;
		height: <?= $arrow_size ?>px;
		line-height: 0;
		margin: 0 0.5rem;
		padding: 0;
		position: relative;
		text-align: center;
		transition: background-color 0.2s ease, color 0.2s ease;
		width: <?= $arrow_size ?>px;
	}
	#<?= $unique_id ?> .splide__arrows .splide__arrow:hover {
		background: <?= $arrow_hover_color ?>;
	}
	#<?= $unique_id ?> .splide__arrows .splide__arrow--prev::before {
		content: "";
		background-image: url('<?= $prev_arrow_svg['url'] ?>');
		background-size: cover;
		width: 100%;
		height: 100%;
	}
	#<?= $unique_id ?> .splide__arrows .splide__arrow--next::before {
		content: "";
		background-image: url('<?= $next_arrow_svg['url'] ?>');
		background-size: cover;
		width: 100%;
		height: 100%;
	}
</style>

<section id="<?= $unique_id?>" class="splide__wrapper cursor-grab <?php echo esc_attr($data['class_name']); ?>">
	<div class="splide relative" data-splide="<?= $splide_options_json ?>" <?= $autoscroll ? 'data-autoscroll="true"' : '' ?> <?= $autoscroll ? 'data-autoscroll-speed="'.$autoscrollspeed.'"' : '' ?>>
		<div class="splide__track text-white" id="splide-track-<?= $unique_id ?>">
			<ul class="splide__list">
				<?php // need to add call to page builder modules here ?>
				<?php
				$wrapper_start = '<li class="splide__slide">';
				$wrapper_end = '</li>';
				$pageBuilder = new PageBuilder;
				$pageBuilder->renderModules($data['slider'][0], $wrapper_start, $wrapper_end);
				?>
			</ul>
			
		</div>
		<div class="splide__arrows splide__arrows--ltr">
				<button
					class="splide__arrow splide__arrow--prev"
					type="button"
					aria-label="Previous slide"
					aria-controls="splide-track-<?= $unique_id ?>"
				>
				<
				</button>
				<button
					class="splide__arrow splide__arrow--next"
					type="button"
					aria-label="Next slide"
					aria-controls="splide-track-><?= $unique_id ?>">
				>
				</button>
		</div>
		<div class="splide__dots">
			<div class="container w-full max-w-screen-xl mx-auto">
				<ul class="splide__pagination"></ul>
			</div>
		</div>
	</div>
</section>