<?php

/********************* AJAX STUFF ***************************/
if (is_admin()) {
	add_action('wp_ajax_js_fetch_query', 'js_fetch_query'); // For logged-in users
	add_action('wp_ajax_nopriv_js_fetch_query', 'js_fetch_query'); // For non-logged-in users
}

// JS Fetch is used for the Quiz results
function js_fetch_query() {
	// Get the form input into an array
	$_POST = json_decode(file_get_contents('php://input'), true);

	// save to data
	$data = $_POST['data'];

	// Get just the results IDs
	$results = explode(',', $data['results'][0]['results']);

	// query args for the results
	$args = array(
		// consider the use of 'any' (outside array) here, save for anything that needs to be hidden from search
		'post_type' => array('post', 'products', 'quiz-results', 'cocktails'),
		'post_status' => 'publish',
		'post__in' => $results,
		//'tax_query' => $taxQuery[0]
	);

	// Query it
	$query = new WP_Query($args);

	// IF we have a product load the template part
	if ($query->have_posts()) {
		while ($query->have_posts()) {
			$query->the_post();
			// Output the post content
			get_template_part('template-parts/loop/quiz-results');
		}
	} else {
		echo 'No posts found';
		//  print_r($args);
	}
	wp_reset_postdata();

	// IF we have an email Post event to Klaviyo with results
	if(!empty($data['email'])):

		// Setup the profile array and its data. 
		$profile_array = array(
			'data' => array(
				'type' => 'profile'
			)
		);
		$profile_array['data']['attributes']['email'] = $data['email'];
		//$profile_array['$consent'] = array('sms, email, web, mobile');

		// Set empty properties array
		$properties_array = array();
		
		// Loop over each quesiton and add them to both the profile and the event metric
		foreach($data['questions'] as $key => $question):
			//$profile_array['data']['attributes'][$question['question_number'] . '-' . $key . ' ' . $question['question']] = $question['answer'];
			$profile_array['data']['attributes']['properties'][$question['question_number'] . '-' . $key . ' ' . $question['question']] = $question['answer'];
			$properties_array[$question['question_number'] . '-' . $key . ' ' . $question['question']] = $question['answer'];
		endforeach;

		// Populate more property fields. 
		$properties_array['$event_id'] = time();
		$properties_array['Results'] = $results;
		$properties_array['Branch'] = $data['results'][0]['branch'];
		$properties_array['data'] = $data;

		// Set up the data array to send to Klaviyo 
		$klaviyo_data = array('data' => array(
			"type" => "event",
			"attributes" => array(
				"profile" => $profile_array,
				"metric" => array(
					'data' => array(
						'type' => 'metric',
						'attributes' => array(
							'name' => 'Product Quiz Submission'
						)
					)
				),
				"properties" => $properties_array,
				"time" => '',
			),
		));

		// error_log( print_r($klaviyo_data, true) );

		// Send data to post function
		$response = post_event_to_klaviyo($klaviyo_data, KLAVIYO_API_KEY);

		// error_log( print_r($response,true) );
	endif;

	die();
}


// Post to Klaviyo using Events API
function post_event_to_klaviyo($data, $KLAVIYO_API_KEY) {
	// Set up URL to send for checking SMS consent.   
	$url = "https://a.klaviyo.com/api/events/";

	// Make a post request to klaviyo api and add headers needed and send the data to the body args.
	$response = wp_remote_post($url, array(
		'body' 		=> wp_json_encode($data),
		'headers'	=> array(
			'Accept' 				=> 'application/json',
			'Content-Type'	=> 'application/json',
			'Authorization' => 'Klaviyo-API-Key ' . $KLAVIYO_API_KEY,
			'revision' => '2023-09-15',
		)
	));

	return $response;
}
