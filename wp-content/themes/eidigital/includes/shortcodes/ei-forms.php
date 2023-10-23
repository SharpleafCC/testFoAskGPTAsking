<?php
// Default form
add_shortcode('default_form', function() {
    ob_start();
    get_template_part('template-parts/forms/default-form');
    return ob_get_clean();
});
?>
<?php
add_action( 'init', function() {
	add_shortcode('ei_form', function($att) {

        $atts = shortcode_atts( [
            'form_name' => '',
            'submit_button_copy' => 'SIGN UP',
            'success_header'    => 'Thank You!',
		    'success_message'	=> 'We’ll be in touch.',
            'grid_item_template' => '',
            'classes' => '',
            'item_classes' => '',
        ], $att);

        if ( !empty($atts['form_name']) ) {
            ob_start();
            get_template_part('template-parts/forms/' . $atts['form_name'], null, $atts);
            return ob_get_clean();
        }
	});
} );
?>