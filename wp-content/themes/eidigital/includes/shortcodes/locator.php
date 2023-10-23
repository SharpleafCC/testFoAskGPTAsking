<?php
add_action( 'init', function() {
	add_shortcode('proximo-store-locator', function() {
		ob_start();
		get_template_part('template-parts/components/locator');
		return ob_get_clean();
	});
} );
?>