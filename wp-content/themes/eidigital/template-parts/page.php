<?php

/**
 * The template for displaying singular post-types: pages and user-defined custom post types.
 *
 * @package EiDigital
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}
?>
<?php while (have_posts()) : the_post(); ?>
	<?php if (!post_password_required()) : ?>
		<?php
		$pageBuilder = new PageBuilder;

		$pageBuilder->renderModules();
		?>
	<?php else : ?>
		<?php echo get_the_password_form(); ?>
	<?php endif; ?>
<?php endwhile; ?>