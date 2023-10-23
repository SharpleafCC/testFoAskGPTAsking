<?php
/**
 * The template for displaying the site header.
 *
 * @package EiDigital
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
extract($args);
?>
<div class="ei-form">
    <div class="ei-form__form-container success-hide-test">
        <form action="https://eidigital.com" class="form-success-hide" method="post" name="default-form">
            <fieldset class="ei-form__fieldset">
                <input type="text" class="ei-form__field ei-form__first-name" placeholder="First Name" name="first_name" required>
                <label for="first_name" class="ei-form__label-error">First name cannot be empty!</label>
            </fieldset>

            <fieldset class="ei-form__fieldset">
                <input type="text" class="ei-form__field ei-form__last-name" placeholder="Last Name" name="last_name" required>
                <label for="last_name" class="ei-form__label-error">Last name cannot be empty!</label>
            </fieldset>

            <fieldset class="ei-form__fieldset">
                <input type="email" class="ei-form__field ei-form__email" placeholder="Email" name="email" required>
                <label for="email" class="ei-form__label-error">Invalid email!</label>
            </fieldset>

            <fieldset class="ei-form__fieldset">
                <input type="tel" class="ei-form__field ei-form__phone" placeholder="Phone" name="phone" required>
                <label for="phone" class="ei-form__label-error">Phone numbers must be 10 digits with no hyphens, spaces, etc. Example: ##########</label>
            </fieldset>

            <fieldset class="ei-form__fieldset">
                <input type="text" class="ei-form__field ei-form__dob" placeholder="MM/DD/YYYY" name="dob" required>
                <label for="dob" class="ei-form__label-error">DOB must follow MM/DD/YYYY format</label>
            </fieldset>
            <fieldset class="ei-form__fieldset">
                <input type="text" class="ei-form__field ei-form__address-1" placeholder="Address 1" name="address_1" required>
                <label for="address_1" class="ei-form__label-error">Unable to find address!</label>
            </fieldset>

            <fieldset class="ei-form__fieldset">
                <input type="text" class="ei-form__field ei-form__address-2" placeholder="Address 2" name="address_2">
                <label for="address_2" class="ei-form__label-error">Invalid address 2</label>
            </fieldset>

            <fieldset class="ei-form__fieldset">
                <input type="text" class="ei-form__field ei-form__city" placeholder="City" name="city" required>
                <label for="city" class="ei-form__label-error">Invalid city!</label>
            </fieldset>

            <fieldset class="ei-form__fieldset">
                <select type="text" class="ei-form__field ei-form__state" name="state" required>
                    <option value="STATE">STATE</option>
                    <option value="AL">Alabama</option>
                    <option value="AK">Alaska</option>
                    <option value="AZ">Arizona</option>
                    <option value="AR">Arkansas</option>
                    <option value="CA">California</option>
                    <option value="CO">Colorado</option>
                    <option value="CT">Connecticut</option>
                    <option value="DE">Delaware</option>
                    <option value="FL">Florida</option>
                    <option value="GA">Georgia</option>
                    <option value="HI">Hawaii</option>
                    <option value="ID">Idaho</option>
                    <option value="IL">Illinois</option>
                    <option value="IN">Indiana</option>
                    <option value="IA">Iowa</option>
                    <option value="KS">Kansas</option>
                    <option value="KY">Kentucky</option>
                    <option value="LA">Louisiana</option>
                    <option value="ME">Maine</option>
                    <option value="MD">Maryland</option>
                    <option value="MA">Massachusetts</option>
                    <option value="MI">Michigan</option>
                    <option value="MN">Minnesota</option>
                    <option value="MS">Mississippi</option>
                    <option value="MO">Missouri</option>
                    <option value="MT">Montana</option>
                    <option value="NE">Nebraska</option>
                    <option value="NV">Nevada</option>
                    <option value="NH">New Hampshire</option>
                    <option value="NJ">New Jersey</option>
                    <option value="NM">New Mexico</option>
                    <option value="NY">New York</option>
                    <option value="NC">North Carolina</option>
                    <option value="ND">North Dakota</option>
                    <option value="OH">Ohio</option>
                    <option value="OK">Oklahoma</option>
                    <option value="OR">Oregon</option>
                    <option value="PA">Pennsylvania</option>
                    <option value="RI">Rhode Island</option>
                    <option value="SC">South Carolina</option>
                    <option value="SD">South Dakota</option>
                    <option value="TN">Tennessee</option>
                    <option value="TX">Texas</option>
                    <option value="UT">Utah</option>
                    <option value="VT">Vermont</option>
                    <option value="VA">Virginia</option>
                    <option value="WA">Washington</option>
                    <option value="DC">Washington DC</option>
                    <option value="WV">West Virginia</option>
                    <option value="WI">Wisconsin</option>
                    <option value="WY">Wyoming</option>
                </select>
                <label for="state" class="ei-form__label-error">State is required!</label>
            </fieldset>

            <fieldset class="ei-form__fieldset">
                <input type="text" class="ei-form__field ei-form__zip" placeholder="Zip" name="zip" required>
                <label for="zip" class="ei-form__label-error" data-invalid-usps="Unable to find zip code! Please enter valid zip code." data-invalid-default="Zip code must follow ##### format!"></label>
            </fieldset>

            <fieldset class="ei-form__fieldset">
                <label for="opt_in">You will give us your info</label>   
                <input type="checkbox" name="opt_in" class="ei-form__field ei-form__opt_in">
            </fieldset>

            <fieldset class="ei-form__fieldset">
                <label for="radio_check_box">You will give us your info</label>
                <input type="radio" name="radio_check_box" value="1" class="ei-form__field ei-form__radio">
                <label for="radio_check_box">You will not give us your info</label>
                <input type="radio" name="radio_check_box" value="0" class="ei-form__field ei-form__radio">
            </fieldset>

            <fieldset class="ei-form__fieldset">
                <label for="multi_check_box">Multi checkbox 1</label>   
                <input type="checkbox" name="multi_check_box" value="1" class="ei-form__field ei-form__opt_in">
                <label for="multi_check_box">Multi checkbox 2</label>   
                <input type="checkbox" name="multi_check_box" value="2" class="ei-form__field ei-form__opt_in">
                <label for="multi_check_box">Multi checkbox 3</label>   
                <input type="checkbox" name="multi_check_box" value="3" class="ei-form__field ei-form__opt_in">
                <label for="multi_check_box">Multi checkbox 4</label>   
                <input type="checkbox" name="multi_check_box" value="4" class="ei-form__field ei-form__opt_in">
            </fieldset>

            <input type="hidden" name="check_address" value="1">
            <input type="hidden" name="record_submission" value="1">
            <input type="hidden" name="max_entry" value="0">
            <input type="hidden" name="single_entry_check" value="1">
            <input type="hidden" name="validate_zip_code" value="1">
            <input type="hidden" name="recommend_address" value="1">
            <input type="hidden" name="recommend_address_count" value="0">
            <input type="hidden" name="check_age" value="21">
            <input type="hidden" name="success_show_container_selector" value=".success-show-test">
            <input type="hidden" name="success_hide_container_selector" value=".success-hide-test">
            <input type="hidden" name="page_location" value="<?php the_title(); ?>">
            <input type="hidden" name="klaviyo_subscribe" value="1">

            <button type="submit">Submit</button>
        </form>
    </div>
    <div class="ei-form__success-show success-show-test">
        <h1 class="form-success-show">Thank you! Your entry was successful</h1>
    </div>
    <p class="ei-form__message"></p>
</div>