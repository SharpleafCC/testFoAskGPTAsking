<?php
// Check if there is a URL param to set the default selected item. 
if(!empty($_GET['find'])):
    $default_selected = urldecode($_GET['find']);
else : 
    $default_selected = '';
endif;

if(get_field('locator_flavors', 'ei-locator')):
	$locator_flavors = get_field('locator_flavors', 'ei-locator');
	$all_flavor_ids = array_column($locator_flavors, 'ID'); // works with array of objects since php 7.0
endif;

// Will contain strings that are the title of our opt groups, if any
$all_terms = [];

// Get any associated term with the current posts so we can group them
if ( !empty($all_flavor_ids) && is_array($all_flavor_ids) ) {
    foreach ( $all_flavor_ids as $id ) {
        $term = get_the_terms($id, 'locator_flavor_groups');

        if ( $term !== false ) {
            if ( !in_array($term[0]->name, $all_terms) ) {
                array_push($all_terms, $term[0]->name);
            }
        }
    }
}

$locator_flavor_groups = []; // an array of strings containing the html for any flavors that are grouped
$locator_flavor_not_group = ''; // a string containing the HTML with all flavors NOT grouped

// Contains all the flavor group taxonomies in menu order
$locator_groups = get_terms(['taxonomy' => 'locator_flavor_groups', 'hide_empty' => false]);

// Create the opt group html in order from which they appear in the menu, only creating opt groups
// if the locator flavors are associated with the term
if ( !empty($all_terms) && is_array($all_terms) ) {
    if ( !empty($locator_groups) ) {
        foreach ($locator_groups as $category) {
            if ( in_array($category->name, $all_terms) ) {
                $locator_flavor_groups[$category->name] = '<optgroup label="' . $category->name . '">';
            }
        }
    }
}

// Loop through the flavors and create the HTML for each option
if ( !empty($locator_flavors) ) {
    foreach ($locator_flavors as $flavor) {

        $post_id = $flavor->ID;
        // If we want to display a different name for the flavor, use the acf field. otherwise use the post title
        $product_name = get_field('locator_flavor_display_name', $post_id) ? get_field('locator_flavor_display_name', $post_id) : $flavor->post_title;

        $term = get_the_terms($post_id, 'locator_flavor_groups');

        if ( $term !== false ) {

            $term_name = $term[0]->name;

			if($default_selected == $flavor->post_title):
				$selected = "selected";
			else:
				$selected = "";
			endif;

            if ( array_key_exists($term_name, $locator_flavor_groups) ) {
                $locator_flavor_groups[$term_name] .= '<option ' . $selected . ' data-category="' . get_field('flavor_category', $post_id) . '" name="' . $flavor->post_name . '" value="' . $flavor->post_title . '">' . $product_name . '</option>';
            }
        } else {
            $locator_flavor_not_group .= '<option ' . $selected . ' data-category="' . get_field('flavor_category', $post_id) . '" name="' . $flavor->post_name . '" value="' . $flavor->post_title . '">' . $product_name . '</option>';
        }
    }
}
?>
<div id="store-locator-wrapper" class="locator__container">
	<div class="col__left">
		<h2>FIND NEAR YOU</h2>
		<form action="https://locator.eidigital.com" class="margin-bottom" id="form--locator">
			<?php
			$locator_flavors = get_field('locator_flavors', 'ei-locator');
			if ($locator_flavors) : ?>
				<select name="flavor">
					<option name="all_flavors" value="all_flavors">Select A Flavor</option>
					<?php
                    // Switch the order as needed. Opt groups first, not grouped options last
                    if ( !empty($locator_flavor_groups) && is_array($locator_flavor_groups) ) {
                        foreach ( $locator_flavor_groups as $key => $value ) {
                            echo $value . '</optgroup>';
                        }
                    }

                    if ( !empty($locator_flavor_not_group) ) {
                        echo $locator_flavor_not_group;
                    }
                    ?>
				</select>
			<?php endif; ?>
			<div class="locator__search">
				<input id="address" class="input__submit" name="address" type="text" placeholder="Enter Address, City, or Zip...">
				<input type="hidden" id="storeType" name="storeType" value="off">
				<input type="hidden" id="proximo-search-lat" name="proximo-search-lat" value="42.83165136456606">
				<input type="hidden" id="proximo-search-lng" name="proximo-search-lng" value="-77.69189911865588">
				<input type="hidden" id="proximo-search-from-center" name="proximo-search-from-center" value="0">
				<input type="hidden" id="page" name="page" value="0">
				<button class="button button__submit" name="submit" type="submit"><?php include(locate_template("template-parts/svg/search.svg", false, false)); ?></button>
			</div>
		</form>
		<div class="locator__filters">
			<p>Filter</p>
			<div class="locator__locations--buttons margin-bottom">
				<span class="filter filter--offsite active" data-storetype="off" id="store__type-offsite">TO BUY</span>
				<span>|</span>
				<span class="filter filter--onsite" data-storetype="on" id="store__type-onsite">TO DRINK</span>
			</div>
		</div>
		<div class="locator__locations"></div>
		<div class="locator__pagination">
			<button class="button button-secondary-color pagination__prev fade--out" data-page="0"><span>Prev</span></button>
			<button class="button button-secondary-color pagination__next fade--out" data-page="1"><span>Next</span></button>
		</div>
	</div>
	<div class="col__right">
		<div id="map"></div>
		<button id="redo__search" class="button button-secondary-color"><span>Redo Search in This Area</span></button>
	</div>
</div>