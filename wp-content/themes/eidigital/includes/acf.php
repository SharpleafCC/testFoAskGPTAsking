<?php
// Add ACF Options Page
if (function_exists('acf_add_options_page')) {

	acf_add_options_page(array(
		'page_title' 	=> 'Theme Settings',
		'menu_title'	=> 'Theme Settings',
		'menu_slug' 	=> 'theme-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
}

function my_acf_flexible_content_layout_title($title, $field, $layout, $i)
{
	// load text field from the styles tab to label blocks in the admin
	if ($text = get_sub_field('layout_title')) {

		$title .= ' - <strong>' . $text . '</strong>';
	}
	return $title;
}

// name acf fields in admin
add_filter('acf/fields/flexible_content/layout_title', 'my_acf_flexible_content_layout_title', 10, 4);

/**
* Set custom colors for ACF color picker
*/
function ei_color_picker_colors() { ?>

	<script type="text/javascript">
	(function($) {
	
	acf.add_filter('color_picker_args', function( args, $field ){
	
	// add the hexadecimal codes here for the colors you want to appear as swatches
	args.palettes = ['#000000', '#ffffff']
	
	// return colors
	return args;
	
	});
	
	})(jQuery);
	</script>
	
	<?php }
	
add_action('acf/input/admin_footer', 'ei_color_picker_colors');

// Dynamic Populate Select For Loop Templates
function section_related_posts_loop_choices( $field ) {
	if(is_admin()):
		// reset choices
		$field['choices'] = array();

		// Get path to template parts loops
		$loop_dir = get_stylesheet_directory() . "/template-parts/loop/";

		// Get all loop files
		$loops = list_files( $loop_dir, 2 );

		// Loop over each part and add to array
		foreach($loops as $template):
			$loop_name = basename($template, '.php');
			$field['choices'][$loop_name] = $loop_name;
		endforeach;		
	endif;   
	 
	return $field;
}

add_filter('acf/load_field/name=template_loop', 'section_related_posts_loop_choices');


// Dynamic Populate Select For component Templates
function acf_fieldgroup( $field ) {
	if(is_admin()):
		// reset choices
		$field['choices'] = array();

		// Get path to components
		$loop_dir = get_stylesheet_directory() . "/template-parts/components/";

		// Get all loop files
		$loops = list_files( $loop_dir, 2 );

		// Loop over each part and add to array
		foreach($loops as $template):
			$loop_name = basename($template, '.php');
			$field['choices'][$loop_name] = $loop_name;
		endforeach;		
	endif;   
	 
	return $field;
}

add_filter('acf/load_field/name=acf_group_theme_template', 'acf_fieldgroup');

