<?php 
extract( $args ); 
// Load the ACF Field name from the admin. 
$acf_field = $args['acf_field'];
?>
<?php if(get_field($acf_field)): ?>
	<?php 
	// $field now holds all of your ACF fields that you set up in the admin. This could be one field or a group of fields depending on how it was configured. 
	$field = get_field($acf_field); 
	?>
<?php endif; ?>