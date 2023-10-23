<?php

/**
 * Template Name: Update Locator
 * MAKE SURE PAGE IS SET TO PRIVATE
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

get_header();

?>
<?php while (have_posts()) : the_post(); ?>
	<?php if (!post_password_required()) : ?>
		<?php
		// Create JSON file
		setup_locator();
		?>
		<h1>Locator Updated</h1>
	<?php else : ?>
		<?php echo get_the_password_form(); ?>
	<?php endif; ?>
<?php endwhile; ?>

<?php get_footer(); ?>