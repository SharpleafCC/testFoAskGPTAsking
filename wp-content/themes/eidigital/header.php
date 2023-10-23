<?php
/**
 * The template for displaying the header
 *
 * This is the template that displays all of the <head> section, opens the <body> tag and adds the site's header.
 *
 * @package EiDigital
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=1" />
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<?php
	$post_id = $post->ID;
	$additionBodyClasses="";
	if(get_field('age_gate_dont_display_on', 'options')):
		$show_on_ids = get_field('age_gate_dont_display_on', 'options'); 
		if (in_array($post_id, $show_on_ids)):
			$additionBodyClasses .= " skip-age-gate";
		endif;
	endif;

	// Age Gate is turned off in admin
	if(get_field('age_gate_status', 'option') != 1):
		$additionBodyClasses .= " skip-age-gate";
	endif;
?>
<body <?php body_class($additionBodyClasses); ?>>
<main class="body__content">

	<?php
	$args = array('header' => get_field( 'header', 'option' ));

	get_template_part( 'template-parts/header', null, $args );
	?>
