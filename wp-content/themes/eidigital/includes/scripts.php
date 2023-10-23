<?php
function load_scripts() 
{
	if (is_tax()) {
		$queried_object = get_queried_object();
		$current_type = 'taxonomies/' . $queried_object->taxonomy;
	} elseif (is_post_type_archive()) {
		$post_type = get_query_var('post_type');
		$current_type = 'cpts/' . $post_type . '-archive';
	} elseif (is_page()) {
		$current_type = 'pages/' . get_post_field('post_name', get_post());
	} elseif (is_singular()) {
		$singular_slug = get_post_field('post_name', get_post());
		$post_type = get_post_type();
	
		if (
			file_exists(get_stylesheet_directory() . "/assets/prod/js/cpts/{$post_type}/{$singular_slug}-min.js") ||
			file_exists(get_stylesheet_directory() . "/assets/prod/css/cpts/{$post_type}/{$singular_slug}-min.css")
		) {
			$current_type = 'cpts/' . $post_type . '/' . $singular_slug;
		} else {
			$current_type = 'cpts/' . $post_type . '-singular';
		}
	} elseif (is_404()) {
		$current_type = '404';
	}

	// Set defaults.
	$asset_name_js = 'main';
	$asset_name_css = 'app';

	// Check for existence of JS and CSS files named after the CPT/taxonomy slug.
	if (file_exists(get_stylesheet_directory() . "/assets/prod/js/{$current_type}-min.js")) {
		$asset_name_js = $current_type;
	}

	if (file_exists(get_stylesheet_directory() . "/assets/prod/css/{$current_type}-min.css")) {
		$asset_name_css = $current_type;
	}

	// Determine minification based on environment.
	$min = (in_array(wp_get_environment_type(), ['local', 'development', 'staging'])) ? '' : '-min';

	// override minification for local
	$min = '-min';

	// Enqueue JS.
	$jtime = filemtime(get_stylesheet_directory() . "/assets/prod/js/{$asset_name_js}{$min}.js");
	wp_enqueue_script("scripts-{$asset_name_js}", get_template_directory_uri() . "/assets/prod/js/{$asset_name_js}{$min}.js", array(), $jtime, false);

	// Enqueue CSS.
	$ctime = filemtime(get_stylesheet_directory() . "/assets/prod/css/{$asset_name_css}{$min}.css");
	wp_enqueue_style("styles-{$asset_name_css}", get_template_directory_uri() . "/assets/prod/css/{$asset_name_css}{$min}.css", array(), $ctime, 'all');
	

	//load slider on all pages
	//this is a temp fix until we can figure out how to load the slider js only if there's a block on the page that has the slider, and not JUST the page-builder

		// Get last modified timestamp of JS file in /js/main.js
		//$jtime = filemtime( get_stylesheet_directory() . '/assets/prod/js/components/slider-min.js' );

		// Regsiter JS
		//wp_register_script('scripts-slider', get_template_directory_uri() . '/assets/prod/js/components/slider-min.js', array(), $jtime, false);
		// Load Theme JS
		//wp_enqueue_script('scripts-slider', true);

		//$ctime = filemtime( get_stylesheet_directory() . '/assets/prod/css/modules/slider-min.css' );

		// Load block styles with version number
		//wp_enqueue_style('styles-slider', get_template_directory_uri() . '/assets/prod/css/modules/slider-min.css', array(),  $ctime, 'all');
	//end temporary solution


	// The core GSAP library
	wp_enqueue_script( 'gsap-js', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js', array(), false, true );
    wp_script_add_data( 'gsap-js', array( 'integrity', 'crossorigin' ) , array( 'sha384-d+vyQ0dYcymoP8ndq2hW7FGC50nqGdXUEgoOUGxbbkAJwZqL7h+jKN0GGgn9hFDS', 'anonymous' ) );


	// ScrollTrigger - with gsap.js passed as a dependency
	wp_enqueue_script( 'gsap-st', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js', array('gsap-js'), false, true );
	wp_script_add_data( 'gsap-st', array( 'integrity', 'crossorigin' ) , array( 'sha384-poC0r6usQOX2Ayt/VGA+t81H6V3iN9L+Irz9iO8o+s0X20tLpzc9DOOtnKxhaQSE', 'anonymous' ) );

	/*
	<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/Observer.min.js" integrity="sha384-4ehl6W7cPykcjJfkl2U3vix24fqm5paBrAFMniylwN6YgZ8wJHeFWja19f7cX+6Q" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollToPlugin.min.js" integrity="sha384-aO2rdje/BwxNeovaVnYCA1OUtEWaqumve5UCMUMEM9/kKJM/c9NyqDGmgPuJz8eQ" crossorigin="anonymous"></script>
	*/

}
add_action('wp_enqueue_scripts', 'load_scripts');

// Update CSS within in Admin
function admin_style() {
	wp_enqueue_style('admin-styles', get_template_directory_uri().'/ei-admin.css');
}
add_action('admin_enqueue_scripts', 'admin_style');