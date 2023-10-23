<?php extract($args); ?>
<?php
if($args['row']):
	$form_name = "ROW PDP Sign UP";
	$form_show_class = "success-show-pdp-signup--row";
	$form_hide_class = "success-hide-pdp-signup--row";
else:
	$form_name = "PDP Sign UP";
	$form_show_class = "success-show-pdp-signup";
	$form_hide_class = "success-hide-pdp-signup";
endif;
?>
<section class="section section--contained scroll__reveal form__signup form__success--hide">
	<div class="campaign-form">
		<div class="campaign-form__form-container <?php echo $form_hide_class; ?>">
			<form action="https://eidigital.com" class="form-success-hide text-center" method="post" name="<?php echo $form_name; ?>">
				<div class="form__fields">
					<div>
						<fieldset class="campaign-form__fieldset">
							<label for="first_name" class="campaign-form__label-error">First name cannot be empty.</label>
							<input class="campaign-form__field" name="first_name" type="text" placeholder="First Name*" value="" />
						</fieldset>
					</div>
					<div>
						<fieldset class="campaign-form__fieldset">
							<label for="last_name" class="campaign-form__label-error">Last name cannot be empty.</label>
							<input class="campaign-form__field" name="last_name" type="text" placeholder="Last Name*" value="" />
						</fieldset>
					</div>
					<?php if($args['row']): ?>
						<div>
							<fieldset class="campaign-form__fieldset">
								<label for="country" class="campaign-form__label-error" data-invalid-usps="Please select a country." data-invalid-default="Please select a country."></label>
								<div class="select__custom">
									<select title="country" type="text" class="campaign-form__field campaign-form__country" name="country" required>
										<option>Country</option>
										<option value="AF">Afghanistan</option>
										<option value="AX">Aland Islands</option>
										<option value="AL">Albania</option>
										<option value="DZ">Algeria</option>
										<option value="AS">American Samoa</option>
										<option value="AD">Andorra</option>
										<option value="AO">Angola</option>
										<option value="AI">Anguilla</option>
										<option value="AQ">Antarctica</option>
										<option value="AG">Antigua and Barbuda</option>
										<option value="AR">Argentina</option>
										<option value="AM">Armenia</option>
										<option value="AW">Aruba</option>
										<option value="AU">Australia</option>
										<option value="AT">Austria</option>
										<option value="AZ">Azerbaijan</option>
										<option value="BS">Bahamas</option>
										<option value="BH">Bahrain</option>
										<option value="BD">Bangladesh</option>
										<option value="BB">Barbados</option>
										<option value="BY">Belarus</option>
										<option value="BE">Belgium</option>
										<option value="BZ">Belize</option>
										<option value="BJ">Benin</option>
										<option value="BM">Bermuda</option>
										<option value="BT">Bhutan</option>
										<option value="BO">Bolivia</option>
										<option value="BA">Bosnia and Herzegovina</option>
										<option value="BW">Botswana</option>
										<option value="BV">Bouvet Island</option>
										<option value="BR">Brazil</option>
										<option value="BN">Brunei Darussalam</option>
										<option value="BG">Bulgaria</option>
										<option value="BF">Burkina Faso</option>
										<option value="BI">Burundi</option>
										<option value="KH">Cambodia</option>
										<option value="CM">Cameroon</option>
										<option value="CA">Canada</option>
										<option value="CV">Cape Verde</option>
										<option value="KY">Cayman Islands</option>
										<option value="CF">Central African Republic</option>
										<option value="TD">Chad</option>
										<option value="CL">Chile</option>
										<option value="CN">China</option>
										<option value="CO">Colombia</option>
										<option value="KM">Comoros</option>
										<option value="CG">Congo</option>
										<option value="CK">Cook Islands</option>
										<option value="CR">Costa Rica</option>
										<option value="CI">Cote D'Ivoire</option>
										<option value="HR">Croatia</option>
										<option value="CU">Cuba</option>
										<option value="CW">Curacao</option>
										<option value="CY">Cyprus</option>
										<option value="CZ">Czech Republic</option>
										<option value="DK">Denmark</option>
										<option value="DJ">Djibouti</option>
										<option value="DM">Dominica</option>
										<option value="DO">Dominican Republic</option>
										<option value="EC">Ecuador</option>
										<option value="EG">Egypt</option>
										<option value="SV">El Salvador</option>
										<option value="GQ">Equatorial Guinea</option>
										<option value="ER">Eritrea</option>
										<option value="EE">Estonia</option>
										<option value="ET">Ethiopia</option>
										<option value="FO">Faroe Islands</option>
										<option value="FJ">Fiji</option>
										<option value="FI">Finland</option>
										<option value="FR">France</option>
										<option value="GF">French Guiana</option>
										<option value="PF">French Polynesia</option>
										<option value="TF">French Southern Territories</option>
										<option value="GA">Gabon</option>
										<option value="GM">Gambia</option>
										<option value="GE">Georgia</option>
										<option value="DE">Germany</option>
										<option value="GH">Ghana</option>
										<option value="GI">Gibraltar</option>
										<option value="GR">Greece</option>
										<option value="GL">Greenland</option>
										<option value="GD">Grenada</option>
										<option value="GP">Guadeloupe</option>
										<option value="GU">Guam</option>
										<option value="GT">Guatemala</option>
										<option value="GG">Guernsey</option>
										<option value="GN">Guinea</option>
										<option value="GW">Guinea-Bissau</option>
										<option value="GY">Guyana</option>
										<option value="HT">Haiti</option>
										<option value="HN">Honduras</option>
										<option value="HK">Hong Kong</option>
										<option value="HU">Hungary</option>
										<option value="IS">Iceland</option>
										<option value="IN">India</option>
										<option value="ID">Indonesia</option>
										<option value="IR">Iran</option>
										<option value="IQ">Iraq</option>
										<option value="IE">Ireland</option>
										<option value="IM">Isle of Man</option>
										<option value="IL">Israel</option>
										<option value="IT">Italy</option>
										<option value="JM">Jamaica</option>
										<option value="JP">Japan</option>
										<option value="JE">Jersey</option>
										<option value="JO">Jordan</option>
										<option value="KZ">Kazakhstan</option>
										<option value="KE">Kenya</option>
										<option value="KI">Kiribati</option>
										<option value="KP">Korea</option>
										<option value="XK">Kosovo</option>
										<option value="KW">Kuwait</option>
										<option value="KG">Kyrgyzstan</option>
										<option value="LA">Lao People's Democratic Republic</option>
										<option value="LV">Latvia</option>
										<option value="LB">Lebanon</option>
										<option value="LS">Lesotho</option>
										<option value="LR">Liberia</option>
										<option value="LY">Libyan Arab Jamahiriya</option>
										<option value="LI">Liechtenstein</option>
										<option value="LT">Lithuania</option>
										<option value="LU">Luxembourg</option>
										<option value="MO">Macao</option>
										<option value="MK">Macedonia</option>
										<option value="MG">Madagascar</option>
										<option value="MW">Malawi</option>
										<option value="MY">Malaysia</option>
										<option value="MV">Maldives</option>
										<option value="ML">Mali</option>
										<option value="MT">Malta</option>
										<option value="MH">Marshall Islands</option>
										<option value="MQ">Martinique</option>
										<option value="MR">Mauritania</option>
										<option value="MU">Mauritius</option>
										<option value="YT">Mayotte</option>
										<option value="MX">Mexico</option>
										<option value="FM">Micronesia</option>
										<option value="MD">Moldova</option>
										<option value="MC">Monaco</option>
										<option value="MN">Mongolia</option>
										<option value="ME">Montenegro</option>
										<option value="MS">Montserrat</option>
										<option value="MA">Morocco</option>
										<option value="MZ">Mozambique</option>
										<option value="MM">Myanmar</option>
										<option value="NA">Namibia</option>
										<option value="NR">Nauru</option>
										<option value="NP">Nepal</option>
										<option value="NL">Netherlands</option>
										<option value="AN">Netherlands Antilles</option>
										<option value="NC">New Caledonia</option>
										<option value="NZ">New Zealand</option>
										<option value="NI">Nicaragua</option>
										<option value="NE">Niger</option>
										<option value="NG">Nigeria</option>
										<option value="NU">Niue</option>
										<option value="NF">Norfolk Island</option>
										<option value="NO">Norway</option>
										<option value="OM">Oman</option>
										<option value="PK">Pakistan</option>
										<option value="PW">Palau</option>
										<option value="PS">Palestinian</option>
										<option value="PA">Panama</option>
										<option value="PG">Papua New Guinea</option>
										<option value="PY">Paraguay</option>
										<option value="PE">Peru</option>
										<option value="PH">Philippines</option>
										<option value="PN">Pitcairn</option>
										<option value="PL">Poland</option>
										<option value="PT">Portugal</option>
										<option value="PR">Puerto Rico</option>
										<option value="QA">Qatar</option>
										<option value="RE">Reunion</option>
										<option value="RO">Romania</option>
										<option value="RU">Russian Federation</option>
										<option value="RW">Rwanda</option>
										<option value="BL">Saint Barthelemy</option>
										<option value="SH">Saint Helena</option>
										<option value="KN">Saint Kitts and Nevis</option>
										<option value="LC">Saint Lucia</option>
										<option value="MF">Saint Martin</option>
										<option value="PM">Saint Pierre and Miquelon</option>
										<option value="VC">Saint Vincent and the Grenadines</option>
										<option value="WS">Samoa</option>
										<option value="SM">San Marino</option>
										<option value="ST">Sao Tome and Principe</option>
										<option value="SA">Saudi Arabia</option>
										<option value="SN">Senegal</option>
										<option value="RS">Serbia</option>
										<option value="CS">Serbia and Montenegro</option>
										<option value="SC">Seychelles</option>
										<option value="SL">Sierra Leone</option>
										<option value="SG">Singapore</option>
										<option value="SX">Sint Maarten</option>
										<option value="SK">Slovakia</option>
										<option value="SI">Slovenia</option>
										<option value="SB">Solomon Islands</option>
										<option value="SO">Somalia</option>
										<option value="ZA">South Africa</option>
										<option value="SS">South Sudan</option>
										<option value="ES">Spain</option>
										<option value="LK">Sri Lanka</option>
										<option value="SD">Sudan</option>
										<option value="SR">Suriname</option>
										<option value="SJ">Svalbard and Jan Mayen</option>
										<option value="SZ">Swaziland</option>
										<option value="SE">Sweden</option>
										<option value="CH">Switzerland</option>
										<option value="SY">Syrian Arab Republic</option>
										<option value="TW">Taiwan</option>
										<option value="TJ">Tajikistan</option>
										<option value="TZ">Tanzania</option>
										<option value="TH">Thailand</option>
										<option value="TL">Timor-Leste</option>
										<option value="TG">Togo</option>
										<option value="TK">Tokelau</option>
										<option value="TO">Tonga</option>
										<option value="TT">Trinidad and Tobago</option>
										<option value="TN">Tunisia</option>
										<option value="TR">Turkey</option>
										<option value="TM">Turkmenistan</option>
										<option value="TV">Tuvalu</option>
										<option value="UG">Uganda</option>
										<option value="UA">Ukraine</option>
										<option value="AE">United Arab Emirates</option>
										<option value="GB">United Kingdom</option>
										<option value="UY">Uruguay</option>
										<option value="UZ">Uzbekistan</option>
										<option value="VU">Vanuatu</option>
										<option value="VE">Venezuela</option>
										<option value="VN">Viet Nam</option>
										<option value="VG">Virgin Islands, British</option>
										<option value="VI">Virgin Islands, U.s.</option>
										<option value="WF">Wallis and Futuna</option>
										<option value="EH">Western Sahara</option>
										<option value="YE">Yemen</option>
										<option value="ZM">Zambia</option>
										<option value="ZW">Zimbabwe</option>
									</select>
								</div>
							</fieldset>
						</div>
					<?php else: ?>
						<div>
							<fieldset class="campaign-form__fieldset">
								<label for="zip" class="campaign-form__label-error" data-invalid-usps="Please enter a valid 5-digit zip code." data-invalid-default="Please enter a valid 5-digit zip code."></label>
								<input class="campaign-form__field" name="zip" type="text" placeholder="Zip Code*" value="" />
							</fieldset>
						</div>
					<?php endif; ?>
				</div>
				<div class="form__fields form__fields--fullwidth">
					<fieldset class="text-left">
						<div>
							<label for="email" class="campaign-form__label-error">Invalid email.</label>
						</div>
						<div class="form__fields--submit">
							<input class="campaign-form__field" name="email" type="email" placeholder="Email*" value="" />
							<button class="button button-secondary-color" name="submit" type="submit"><span>Sign up</span></button>
						</div>
					</fieldset>
				</div>

				<div class="form__button text-center">
					<input type="hidden" name="page_location" value="<?php the_title(); ?>">
					<input type="hidden" name="klaviyo_subscribe" value="1">
					<input type="hidden" name="validate_zip_code" value="1">
					<input type="hidden" name="check_address" value="0">
					<input type="hidden" name="record_submission" value="0">
					<input type="hidden" name="max_entry" value="0">
					<input type="hidden" name="single_entry_check" value="0">
					<input type="hidden" name="recommend_address" value="0">
					<input type="hidden" name="recommend_address_count" value="0">
					<input type="hidden" name="check_age" value="0">
					<input type="hidden" name="success_show_container_selector" value=".<?php echo $form_show_class; ?>">
					<input type="hidden" name="success_hide_container_selector" value=".<?php echo $form_hide_class; ?>">
				</div>
				<div class="form__error"></div>
			</form>
		</div>
	</div>
</section>

<div class="campaign-form__success-show <?php echo $form_show_class; ?> text-center">
	<div class="form-success-show">
		<section class="grid section section--contained form__vertical form--white">
			<?php
			$custom_logo_id = get_theme_mod('custom_logo');
			$logo = wp_get_attachment_image_src($custom_logo_id, 'full');
			?>
			<a href="<?php echo esc_url(home_url('/')); ?>" title="<?php esc_attr_e(' Home', bloginfo('name')); ?>" rel="home">
				<?php if (has_custom_logo()) : ?>
					<img class="header-logo margin-bottom" src="<?php echo esc_url($logo[0]); ?>" alt="<?php bloginfo('name'); ?>">
				<?php else : ?>
					<h1><?php bloginfo('name'); ?></h1>
				<?php endif; ?>
			</a>
			<h1 class="text-center"><?php echo $args['success_header']; ?></h1>
			<p><?php echo $args['success_message']; ?></p>
		</section>
	</div>
</div>