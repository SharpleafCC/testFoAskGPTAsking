<?php extract($args); ?>
<section class="section section--contained scroll__reveal form__vertical form--white form__success--hide margin-top">
	<div class="campaign-form">
		<div class="campaign-form__form-container success-hide-contact">
			<form action="https://eidigital.com" class="form-success-hide text-center" method="post" name="Contact Page">
				<div class="form__fields">
					<fieldset>
						<label for="first_name" class="campaign-form__label-error">First name cannot be empty.</label>
						<input class="campaign-form__field" name="first_name" type="text" placeholder="First Name*" value="" />
					</fieldset>
					<fieldset>
						<label for="last_name" class="campaign-form__label-error">Last name cannot be empty.</label>
						<input class="campaign-form__field" name="last_name" type="text" placeholder="Last Name*" value="" />
					</fieldset>

					<fieldset>
						<label for="email" class="campaign-form__label-error">Invalid email.</label>
						<input class="campaign-form__field" name="email" type="email" placeholder="Email*" value="" />
					</fieldset>
					<fieldset>
						<label for="zip" class="campaign-form__label-error" data-invalid-usps="Please enter a valid 5-digit zip code" data-invalid-default="Please enter a valid 5-digit zip code."></label>
						<input class="campaign-form__field" type="text" class="campaign-form__field campaign-form__zip" placeholder="Zip Code*" name="zip" >
					</fieldset>
				</div>
				<div class="form__fields form__fields--fullwidth">
					<fieldset>
						<label for="message" class="campaign-form__label-error">Please enter a message.</label>
						<textarea class="campaign-form__field" name="message" rows="5" cols="auto" placeholder="Message*"></textarea>
					</fieldset>
				</div>
				<div class="form__button">
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
					<input type="hidden" name="success_show_container_selector" value=".success-show-contact">
					<input type="hidden" name="success_hide_container_selector" value=".success-hide-contact">

					<button class="button button-primary-color" name="submit" type="submit"><span><?php echo $args['submit_button_copy']; ?></span></button>
				</div>
			</form>
		</div>
		<div class="campaign-form__success-show success-show-contact text-center">
			<h2 class="form-success-show text-center"><?php echo $args['success_header']; ?></h2>
			<p class="form-success-show"><?php echo $args['success_message']; ?></p>
			<p class="form-success-show"><a class="button button-primary-color" href="<?php bloginfo('url'); ?>"><span>Home</span></a></p>
		</div>
		<p class="campaign-form__message"></p>
	</div>
</section>