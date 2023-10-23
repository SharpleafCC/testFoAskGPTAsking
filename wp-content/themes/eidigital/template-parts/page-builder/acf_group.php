<?php
// Template part is used to load components for sections that need custom layouts or features outside of our normal flexible field modules. 
$template_part = 'template-parts/components/' . get_sub_field('acf_group_theme_template');

// Pass the name of the ACF field or ACF field group that you want to work with in your template file. 
$args = array(
	'acf_field' => get_sub_field('acf_group_field_name'),
);

// Load template code and echo to the browser.
get_template_part($template_part, null, $args);
?>