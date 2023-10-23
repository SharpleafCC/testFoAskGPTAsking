<?php
function ei_init() {
    if (!is_admin()) {
        wp_deregister_script('jquery');
        wp_register_script('jquery', false);
    }
}
add_action('init', 'ei_init');

/**************** ADD SUPPORT FOR POST THUMBS AND CUSTOM IMAGE SIZES ***************/
add_theme_support( 'post-thumbnails' );
add_theme_support( 'title-tag' );
add_image_size( 'full-width', 2000, 2000, false );
add_image_size( '960w', 960, false );
add_image_size( 'card', 767, 560, true );

define('THEME_PATH', get_template_directory_uri());

//Show custom image sizes on WP image embed page
function eidigital_insert_custom_image_sizes( $sizes ) {
	global $_wp_additional_image_sizes;
	if ( empty($_wp_additional_image_sizes) )
		return $sizes;

	foreach ( $_wp_additional_image_sizes as $id => $data ) {
		if ( !isset($sizes[$id]) )
			$sizes[$id] = ucfirst( str_replace( '-', ' ', $id ) );
	}

	return $sizes;
}
add_filter( 'image_size_names_choose', 'eidigital_insert_custom_image_sizes' );

// Add suppor for custom logo via editor
add_theme_support( 'custom-logo' );

function eidigital_custom_logo_setup() {
	$defaults = array(
		'height'      => 200,
		'width'       => 400,
		'flex-height' => true,
		'flex-width'  => true,
		'header-text' => array( 'site-title', 'site-description' ),
	);
	add_theme_support( 'custom-logo', $defaults );
}
add_action( 'after_setup_theme', 'eidigital_custom_logo_setup' );

// Register Menus
function eidigital_menus() {
	register_nav_menu('header-menu',__( 'Header Menu' ));
}
add_action( 'init', 'eidigital_menus' );

/* Disable Theme Editor */
function remove_editor_menu() {
	remove_action('admin_menu', '_add_themes_utility_last', 101);
}
add_action('_admin_menu', 'remove_editor_menu', 1);

// Add Page Slug to Body Class
function add_slug_body_class( $classes ) {
	global $post;
	$classes[] = "eidigital";
	if ( isset( $post ) ) {
		// Add a few CPT class name options
		$classes[] = $post->post_type;
        $classes[] = $post->post_type . '-' . $post->post_name;
		$classes[] = $post->post_name;

		// Page class let's us load unqiue css files per page if requried.
		if(get_field('page_class', $post->ID)):
			$classes[] = get_field('page_class', $post->ID);
		endif;

		// Page JS let's us load unqiue js files per page if required.
		if(get_field('page_js', $post->ID)):
			$classes[] = get_field('page_js', $post->ID);
		endif;
	}

	return $classes;
}
add_filter( 'body_class', 'add_slug_body_class' );

/**
 * Disable the emoji's
 */
function disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

	// Remove from TinyMCE
	add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
}
add_action( 'init', 'disable_emojis' );

/**
 * Filter out the tinymce emoji plugin.
 */
function disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
		return array();
	}
}

// remove dashicons in frontend to non-admin
function wpdocs_dequeue_dashicon() {
	if ( ! is_user_logged_in() ) {
		wp_deregister_style('dashicons');
	}
}
add_action( 'wp_enqueue_scripts', 'wpdocs_dequeue_dashicon' );

// Disable Gutenberg editor - all content should be entered via ACF editor fields
add_filter('use_block_editor_for_post', '__return_false', 10);

//Remove Gutenberg Block Library CSS from loading on the frontend
function smartwp_remove_wp_block_library_css(){
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );
    wp_dequeue_style( 'wc-block-style' ); // Remove WooCommerce block CSS
}
add_action( 'wp_enqueue_scripts', 'smartwp_remove_wp_block_library_css', 100 );

// Remove main editor from the admin
function remove_editor() {
	remove_post_type_support('post', 'editor');
	remove_post_type_support('page', 'editor');
}
add_action('admin_init', 'remove_editor');

//Remove JQuery migrate
function remove_jquery_migrate($scripts)
{
    if (!is_admin() && isset($scripts->registered['jquery'])) {
        $script = $scripts->registered['jquery'];

        if ($script->deps) { // Check whether the script has any dependencies
            $script->deps = array_diff($script->deps, array(
                'jquery-migrate'
            ));
        }
    }
}

add_action('wp_default_scripts', 'remove_jquery_migrate');

// Disable wp-embed.min.js the file is used to add options to embed other wordpress posts
function disable_embeds_code_init() {

	// Remove the REST API endpoint.
	remove_action( 'rest_api_init', 'wp_oembed_register_route' );

	// Turn off oEmbed auto discovery.
	add_filter( 'embed_oembed_discover', '__return_false' );

	// Don't filter oEmbed results.
	remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );

	// Remove oEmbed discovery links.
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );

	// Remove oEmbed-specific JavaScript from the front-end and back-end.
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	add_filter( 'tiny_mce_plugins', 'disable_embeds_tiny_mce_plugin' );

	// Remove all embeds rewrite rules.
	add_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );

	// Remove filter of the oEmbed result before any HTTP requests are made.
	remove_filter( 'pre_oembed_result', 'wp_filter_pre_oembed_result', 10 );
}

add_action( 'init', 'disable_embeds_code_init', 9999 );

function disable_embeds_tiny_mce_plugin($plugins) {
	return array_diff($plugins, array('wpembed'));
}

function disable_embeds_rewrites($rules) {
	foreach($rules as $rule => $rewrite) {
		if(false !== strpos($rewrite, 'embed=true')) {
			unset($rules[$rule]);
		}
	}
	return $rules;
}


// Callback function to filter the MCE settings
function my_mce_before_init_insert_formats($init_array)
{
	// Define the style_formats array
	$style_formats = [
		// Each array child is a format with it's own settings
		[
			'title' 	=> 'Visible Mobile and Tablet',
			'selector' 	=> 'img,h1,h2,h3,h4,h5,h6,p,a,button,ul,li,hr',
			'classes' 	=> 'hide-lg-up',
			'wrapper' 	=> false
		],
		[
			'title' 	=> 'Visible Desktop Only',
			'selector' 	=> 'img,h1,h2,h3,h4,h5,h6,p,a,button,ul,li,hr',
			'classes' 	=> 'hide-md-down',
			'wrapper' 	=> false
		],
		[
			'title' 	=> 'Visible Tablet and Up',
			'selector' 	=> 'img,h1,h2,h3,h4,h5,h6,p,a,button,ul,li,hr',
			'classes' 	=> 'hide-sm-down',
			'wrapper' 	=> false
		],
		[
			'title' 	=> 'Visible Tablet Only',
			'selector' 	=> 'img,h1,h2,h3,h4,h5,h6,p,a,button,ul,li,hr',
			'classes' 	=> 'visible-md',
			'wrapper' 	=> false
		],
		[
			'title' 	=> 'Visible Mobile Only',
			'selector' 	=> 'img,h1,h2,h3,h4,h5,h6,p,a,button,ul,li,hr',
			'classes' 	=> 'visible-sm',
			'wrapper' 	=> false
		],
		[
			'title' 	=> 'Form Success Hide',
			'selector' 	=> 'img,h1,h2,h3,h4,h5,h6,p,a,button,ul,li,hr',
			'classes' 	=> 'form-success-hide',
			'wrapper' 	=> false
		],
		[
			'title' 	=> 'Form Success Show',
			'selector' 	=> 'img,h1,h2,h3,h4,h5,h6,p,a,button,ul,li,hr',
			'classes' 	=> 'form-success-show',
			'wrapper' 	=> false
		],
	]
	;
	// Insert the array, JSON ENCODED, into 'style_formats'
	$init_array['style_formats'] = json_encode($style_formats);

	$custom_colours = '
	"000000", "Black",
	"ffffff", "White",
	"C7C7C7", "Gray",
	';

	// build colour grid default+custom colors
	$init_array['textcolor_map'] = '[' . $custom_colours . ']';
	// change the number of rows in the grid if the number of colors changes
	// 8 swatches per row
	$init_array['textcolor_rows'] = 1;

	return $init_array;
}
// Attach callback to 'tiny_mce_before_init'
add_filter('tiny_mce_before_init', 'my_mce_before_init_insert_formats');

function myplugin_tinymce_buttons($buttons)
{
	//Add style selector to the beginning of the toolbar
	array_unshift($buttons, 'styleselect');

	return $buttons;
}
add_filter('mce_buttons_2', 'myplugin_tinymce_buttons');

function deregister_media_elements(){
	wp_deregister_script('wp-mediaelement');
	wp_deregister_style('wp-mediaelement');
 }
add_action('wp_enqueue_scripts','deregister_media_elements');

add_filter( 'jetpack_implode_frontend_css', '__return_false', 99 );
add_filter( 'jetpack_sharing_counts', '__return_false', 99 );

//make sure the search engine visibility is set to 0 on non-production environments
function set_search_engine_visibility() {
    // Check if WP_ENVIRONMENT_TYPE is set and if it's not "production"
    if (defined('WP_ENVIRONMENT_TYPE') && WP_ENVIRONMENT_TYPE !== 'production') {
        // If the blog_public option isn't already set to '0', update it
        if (get_option('blog_public') !== '0') {
            update_option('blog_public', '0');
        }
    } else {
        // If the blog_public option isn't already set to '1', update it
        if (get_option('blog_public') !== '1') {
            update_option('blog_public', '1');
        }
    }
}
add_action('init', 'set_search_engine_visibility');


// Add password strength checker to admin registration forms
function wp_check_password_strength( $password, $user ) {
   
    if ( strlen( $password ) < 10 ) {
        return 'weak';
    }
    if ( ! preg_match( '/\d/', $password ) || ! preg_match( '/[^a-zA-Z\d]/', $password ) || ! preg_match( '/[A-Z]/', $password ) ) {
        return 'weak';
    }
    if ( preg_match( '/' . preg_quote( strtolower($user->user_login) ) . '/i', strtolower($password) ) ||
    (!empty($user->first_name) && preg_match( '/' . preg_quote( strtolower($user->first_name) ) . '/i', strtolower($password) )) ||
    (!empty($user->last_name) && preg_match( '/' . preg_quote( strtolower($user->last_name) ) . '/i', strtolower($password) )) ) {
            return __( '<strong>ERROR</strong>: Your password cannot contain your username, first name, or last name.' );
    }
    return 'strong';
}

add_action( 'user_profile_update_errors', 'wp_enforce_password_strength', 10, 3 );
function wp_enforce_password_strength( $errors, $update, $user ) {
    if ( ! empty( $_POST['pass1'] ) ) {
        $password_strength = wp_check_password_strength( $_POST['pass1'], $user );
        if ( 'strong' !== $password_strength ) {
            if ( 'weak' === $password_strength ) {
                $errors->add( 'password_strength', __( '<strong>ERROR</strong>: Your password is too weak. Please choose a stronger password (at least 10 characters, including one number, one uppercase character, and one special character).' ) );
            } elseif ( is_string( $password_strength ) ) {
                $errors->add( 'password_strength', $password_strength );
            }
        }
    }
}

add_action( 'validate_password_reset', 'wp_enforce_password_strength_on_reset', 10, 2 );
function wp_enforce_password_strength_on_reset( $errors, $user ) {
    if ( isset( $_POST['pass1'] ) ) {
        $new_password = $_POST['pass1'];
        $password_strength = wp_check_password_strength( $new_password, $user );
        if ( 'strong' !== $password_strength ) {
            if ( 'weak' === $password_strength ) {
                $errors->add( 'password_reset_error', __( '<strong>ERROR</strong>: Your password is too weak. Please choose a stronger password (at least 10 characters, including one number, one uppercase character, and one special character).' ) );
            } elseif ( is_string( $password_strength ) ) {
                $errors->add( 'password_reset_error', $password_strength );
            }
        }
    }
    return $errors;
}

?>