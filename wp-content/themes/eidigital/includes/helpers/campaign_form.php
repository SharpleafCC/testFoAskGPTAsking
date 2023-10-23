<?php
/**
 * A function we declare the gets passed the $formData parameter when instantiating the FormHandler class (parameter 3),
 * allowing us to perform custom validation on fields that are not already being validated (or if a field needs more validation)
 * 
 * @param array $any - An array of the $_POST data
 * 
 * @return array - Must contain the following key/value pairs: 'error' => boolean, 'invalid_fields' => array of invalid field names
 */
function customFormValidation($data) {
	$returnData = [
		'error' => false,
		'invalid_fields' => []
	];

	// Simple example of preventing any zip codes other than 11354 from being submitted
	if ( isset($data['zip']) ) {
		if ( $data['zip'] != '11354' ) {
			$returnData['error'] = true;
			array_push($returnData['invalid_fields'], 'zip');
		}
	}

	return $returnData;
}

function customKlavyioData($data) {
	if (!empty($data)) {

	}

	return [
		'test' => 'test'
	];
}
?>