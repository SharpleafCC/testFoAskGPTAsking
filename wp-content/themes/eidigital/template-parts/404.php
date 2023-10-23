<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package EiDigital
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<?php
// All code for Content Blocks can be found under the template-parts/content_blocks folder. They are called based on the name of their ACF layout name ID.
if (have_rows('content_blocks', 'option')) :			
	while (have_rows('content_blocks', 'option')):
		the_row();
		$GLOBALS['section_counter']++;
		include(locate_template('template-parts/content_blocks/block-parts/block-fields.php'));
		include(locate_template('template-parts/content_blocks/block-parts/block-styles.php'));
		
		
		$partial = 'template-parts/content_blocks/' . get_row_layout() . '.php';

		if(!$template = locate_template($partial, false, false)) {
			$message = `${partial} file not found`;
			error_log($message);
			printf('<h4>%s</h4>', $partial .' file not found');
			return false;
		}

		include($template);
		
	endwhile;
endif;
?>