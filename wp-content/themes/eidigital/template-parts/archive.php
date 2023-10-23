<?php

/**
 * The template for displaying the cocktails archive page
 *
 * @package EiDigital
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}
?>
<?php
// Load wp query
global $wp_query;

// Get CPT var and add option value for ACF field calls. 
$cpt = $wp_query->query_vars['post_type'] . '_archive';

// Call ACF flexible field layouts
$pageBuilder = new PageBuilder;
$pageBuilder->renderModules($cpt);
?>