<?php extract($args); ?>
<section class="section">
	<div class="campaign-form">
		<div class="campaign-form__form-container success-hide-test">
			<h2 class="form-success-hide"><?php the_field('footer_header', 'option'); ?></h2>
			<form action="https://eidigital.com" class="form-success-hide" method="post" name="Footer Signup">
				<div class="row between-xs form__fields">
					<fieldset class="col-xs">
						<label for="first_name" class="campaign-form__label-error">First name cannot be empty.</label>
						<input class="campaign-form__field" name="first_name" type="text" placeholder="First Name*" value="" />
					</fieldset>
					<fieldset class="col-xs">
						<label for="last_name" class="campaign-form__label-error">Last name cannot be empty.</label>
						<input class="campaign-form__field" name="last_name" type="text" placeholder="Last Name*" value="" />
					</fieldset>
					<fieldset class="col-xs">
						<label for="email" class="campaign-form__label-error">Invalid email.</label>
						<input class="campaign-form__field" name="email" type="email" placeholder="Email*" value="" />
					</fieldset>
					<fieldset class="col-xs">
						<label for="zip" class="campaign-form__label-error" data-invalid-usps="Please enter a valid 5-digit zip code" data-invalid-default="Please enter a valid 5-digit zip code."></label>
						<input class="campaign-form__field" type="text" class="campaign-form__field campaign-form__zip" placeholder="Zip Code*" name="zip" >
					</fieldset>
					<div class="col-xs form__button">
						<input type="hidden" name="page_location" value="<?php the_title(); ?>">
						<input type="hidden" name="klaviyo_subscribe" value="1">
						<input type="hidden" name="validate_zip_code" value="0">
						<input type="hidden" name="check_address" value="0">
						<input type="hidden" name="record_submission" value="0">
						<input type="hidden" name="max_entry" value="0">
						<input type="hidden" name="single_entry_check" value="0">
						<input type="hidden" name="recommend_address" value="0">
						<input type="hidden" name="recommend_address_count" value="0">
						<input type="hidden" name="check_age" value="0">
						<input type="hidden" name="success_show_container_selector" value=".success-show-footer-signup">
						<input type="hidden" name="success_hide_container_selector" value=".success-hide-footer-signup">

						<button class="button button-primary-yellow" name="submit" type="submit"><span><?php echo $args['submit_button_copy']; ?></span></button>
					</div>
				</div>
			</form>
		</div>
		<div class="campaign-form__success-show success-show-test">
			<h2 class="form-success-show text-center"><?php echo $args['success_header']; ?></h2>
			<p class="form-success-show"><?php echo $args['success_message']; ?></p>
		</div>
		<p class="campaign-form__message"></p>
	</div>
</section>