<?php
/**
 * The site's entry point.
 *
 * Loads the relevant template part,
 * the loop is executed (when needed) by the relevant template part.
 *
 * @package EiDigital
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();

if ( is_home() || is_front_page() ) {
	get_template_part( 'template-parts/page' );
}
elseif(is_tax() || is_tag() || is_category()) {
	get_template_part( 'template-parts/taxonomy' );
}
elseif(is_page()) {
	get_template_part( 'template-parts/page' );
}
elseif ( is_archive() ) {
	get_template_part( 'template-parts/archive' );
}
elseif ( is_search() ) {
	get_template_part( 'template-parts/search' );
}
elseif(is_singular()) {
	get_template_part( 'template-parts/single' );
} 
else {
	get_template_part( 'template-parts/404' );
}

get_footer();
