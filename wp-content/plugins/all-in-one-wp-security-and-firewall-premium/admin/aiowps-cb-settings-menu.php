<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

class AIOWPS_CB_Settings_Menu extends AIOWPSecurity_Admin_Menu {

	/**
	 * Country Blocking menu slug
	 *
	 * @var string
	 */
	protected $menu_page_slug = AIOWPS_CB_SETTINGS_MENU_SLUG;
	
	/**
	 * Constructor adds menu for Country Blocking
	 */
	public function __construct() {
		parent::__construct(__('Country Blocking', 'all-in-one-wp-security-and-firewall'));
	}

	/**
	 * This function will setup the menus tabs by setting the array $menu_tabs
	 *
	 * @return void
	 */
	protected function setup_menu_tabs() {
		$menu_tabs = array(
			'tab1' => array(
				'title' => __('General Settings', 'all-in-one-wp-security-and-firewall'),
				'render_callback' => array($this, 'render_tab1'),
			),
			'tab2' => array(
				'title' => __('Secondary Settings', 'all-in-one-wp-security-and-firewall'),
				'render_callback' => array($this, 'render_tab2'),
			),
			'tab3' => array(
				'title' => __('Whitelist', 'all-in-one-wp-security-and-firewall'),
				'render_callback' => array($this, 'render_tab3'),
			),
			'tab4' => array(
				'title' => __('Advanced Settings', 'all-in-one-wp-security-and-firewall'),
				'render_callback' => array($this, 'render_tab4'),
			),
		);

		$this->menu_tabs = array_filter($menu_tabs, array($this, 'should_display_tab'));
	}
	
	/**
	 * Renders the submenu's general settings tab
	 *
	 * @return void
	 */
	public function render_tab1() {
		global $aiowps_feature_mgr;
		global $aio_wp_security, $aio_wp_security_premium;
		$countries_array = $aio_wp_security_premium->country_tasks_obj->country_codes;
		
		if (isset($_POST['aiowps_save_country_blocking_settings'])) { // Do form submission tasks
			$post_array = $_POST;
			
			$error = '';

			$nonce = $_REQUEST['_wpnonce'];
			if (!wp_verify_nonce($nonce, 'aiowpsec-country-blocking-settings-nonce')) {
				$aio_wp_security->debug_logger->log_debug("Nonce check failed for country blocking options save!",4);
				die("Nonce check failed for country blocking options save!");
			}

			$redirect_url = trim(stripslashes($_POST['aiowps_cb_redirect_url']));
			$redirect_url = esc_url($redirect_url, array('http', 'https'));
			if ($redirect_url == '') {
				$error .= '<br />' . __('You entered an incorrect format for the "Redirect URL" field, it has been set to the default value.','all-in-one-wp-security-and-firewall-premium');
				$redirect_url = 'http://127.0.0.1';
			}
			
			$blocked_countries = array();
			foreach ($post_array as $key => $val) {
				if (strpos($key, 'aiowps_country_checkbox') === false){
					continue;
				} else {
					if (!array_key_exists($val, $countries_array)) continue;
					$blocked_countries[] = $val;
				}
			}
			
			if ($error) {
				$this->show_msg_error(__('Attention!','all-in-one-wp-security-and-firewall-premium').$error);
			}

			//Save all the form values to the options
			$aio_wp_security_premium->configs->set_value('aiowps_cb_enable_country_blocking', isset($_POST['aiowps_cb_enable_country_blocking']) ? '1' : '');
			$aio_wp_security_premium->configs->set_value('aiowps_cb_blocking_action', $_POST['aiowps_cb_blocking_action']);
			$aio_wp_security_premium->configs->set_value('aiowps_cb_blocked_countries', $blocked_countries);
			$aio_wp_security_premium->configs->set_value('aiowps_cb_redirect_url', $redirect_url);
			$aio_wp_security_premium->configs->save_config();

			// Recalculate points after the feature status/options have been altered
			// $aiowps_feature_mgr->check_feature_status_and_recalculate_points();
			$aio_wp_security_premium->show_msg_settings_updated();
		}
	 ?>
	 
		<h2><?php _e('Country Blocking Settings', 'all-in-one-wp-security-and-firewall-premium')?></h2>
		<form action="" method="POST">
		<?php wp_nonce_field('aiowpsec-country-blocking-settings-nonce'); ?>            

		<div class="postbox">
		<h3 class="hndle"><label for="title"><?php _e('Enable Country Blocking', 'all-in-one-wp-security-and-firewall-premium'); ?></label></h3>
		<div class="inside">
		<div class="aio_blue_box">
			<?php
			echo '<p>'.__('Each country and territory around the world has an IP address range which is allocated to it.', 'all-in-one-wp-security-and-firewall-premium').
			'<br />'.__('When a visitor lands on your site, this feature will determine which country they are from by examining their IP address and it will block users based their country if applicable.', 'all-in-one-wp-security-and-firewall-premium').
			'<br />'.__('We use the most professional, accurate and up-to-date Geo-IP database available to maximize the successful identification of users and their originating countries.', 'all-in-one-wp-security-and-firewall-premium').'</p>';
			?>
		</div>
		<?php
		$saved_blocked_countries = ($aio_wp_security_premium->configs->get_value('aiowps_cb_blocked_countries') == '') ? array() : $aio_wp_security_premium->configs->get_value('aiowps_cb_blocked_countries');
		?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php _e('Enable Country Blocking', 'all-in-one-wp-security-and-firewall-premium')?>:</th>                
				<td>
				<input name="aiowps_cb_enable_country_blocking" type="checkbox"<?php if($aio_wp_security_premium->configs->get_value('aiowps_cb_enable_country_blocking')=='1') echo ' checked="checked"'; ?> value="1"/>
				<span class="description"><?php _e('Check this if you want to enable the country blocking feature', 'all-in-one-wp-security-and-firewall-premium'); ?></span>
				</td>
			</tr>
		</table>
		</div></div>
		<div class="postbox">
		<h3 class="hndle"><label for="title"><?php _e('Country Blocking Options', 'all-in-one-wp-security-and-firewall-premium'); ?></label></h3>
		<div class="inside">
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php _e('Blocking Action', 'all-in-one-wp-security-and-firewall-premium')?>:</th>
				<td>
					<select id="aiowps_cb_blocking_action" name="aiowps_cb_blocking_action">
						<option value="0" <?php selected( $aio_wp_security_premium->configs->get_value('aiowps_cb_blocking_action'), '0' ); ?>><?php _e( 'redirect', 'all-in-one-wp-security-and-firewall-premium' ); ?></option>
					</select>
				<span class="description"><?php _e('Set the type of blocking action you would like to perform', 'all-in-one-wp-security-and-firewall-premium'); ?></span>
				</td> 
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Redirect URL', 'all-in-one-wp-security-and-firewall-premium')?>:</th>
				<td><input type="text" size="50" name="aiowps_cb_redirect_url" value="<?php echo $aio_wp_security_premium->configs->get_value('aiowps_cb_redirect_url'); ?>" />
				<span class="description"><?php _e('Set the value for the URL where you want to send the blocked visitor to', 'all-in-one-wp-security-and-firewall-premium'); ?></span>
				</td> 
			</tr>
			
			<tr valign="top">
				<th scope="row"><?php _e('Select Countries', 'all-in-one-wp-security-and-firewall-premium')?>:</th>
			<td>
				<input type="checkbox" id="aiowps_cb_general_select_all" name="aiowps_cb_general_select_all" value="1"/><label>Select/De-select All</label>
			<ul class="aiowps-checkbox-grid">
				<?php
				$countries_array = $aio_wp_security_premium->country_tasks_obj->country_codes;
				asort($countries_array);
				foreach($countries_array as $c_code => $c_name) {
					if (in_array($c_code, $saved_blocked_countries)) {
						$check_txt = ' checked="checked"';
					} else {
						$check_txt = '';
					} 
					echo '<li><input type="checkbox" class="aiowps_country_checkbox" id="aiowps_country_checkbox_' . $c_code . '" name="aiowps_country_checkbox_' . $c_code . '"'.$check_txt.' value="' . $c_code . '" /><label for="c_txt">' . $c_name . '</label></li>';
				}
				?>
			</ul>
			</td>
			</tr>
		</table>
		<?php 
		?>
		</div></div>
		<input type="submit" name="aiowps_save_country_blocking_settings" value="<?php _e('Save Settings', 'all-in-one-wp-security-and-firewall-premium')?>" class="button-primary" />
		</form>
		<script type="text/javascript">
		jQuery(document).ready(function($){

			$('.form-table').on('click', '#aiowps_cb_general_select_all', function(){
				if (this.checked) {
					$('.aiowps_country_checkbox').prop('checked', true);
				} else {
					$('.aiowps_country_checkbox').prop('checked', false);
				}
			});
		});
		</script>
	<?php
	}
	
	/**
	 * Renders the submenu's secondary settings tab
	 *
	 * @return void
	 */
	public function render_tab2() {
		global $aiowps_feature_mgr;
		global $aio_wp_security, $aio_wp_security_premium;
		$countries_array = $aio_wp_security_premium->country_tasks_obj->country_codes;
		
		if (isset($_POST['aiowps_save_secondary_cb_settings'])) { // Do form submission tasks
			$post_array = $_POST;
			$post_ids = '';
			$error = '';

			$nonce = $_REQUEST['_wpnonce'];
			if (!wp_verify_nonce($nonce, 'aiowpsec-secondary-country-blocking-settings-nonce')) {
				$aio_wp_security->debug_logger->log_debug("Nonce check failed for country blocking options save!",4);
				die("Nonce check failed for country blocking options save!");
			}

			$redirect_url = trim($_POST['aiowps_cb_secondary_redirect_url']);
			$redirect_url = esc_url($redirect_url, array('http', 'https'));
			if ($redirect_url == '') {
				$error .= __(' You entered an incorrect format for the "Redirect URL" field, it has been set to the default value.','all-in-one-wp-security-and-firewall-premium');
				$redirect_url = 'http://127.0.0.1';
			}
			
			if (isset($_POST['aiowps_cb_enable_secondary_country_blocking']) && empty($_POST['aiowps_secondary_cb_protected_post_ids'])) {
				$this->show_msg_error(__('You must submit at least one page or post ID!','all-in-one-wp-security-and-firewall-premium'));
			} else {
				if (!empty($_POST['aiowps_secondary_cb_protected_post_ids'])) {
					$post_ids = str_replace(' ', '', $_POST['aiowps_secondary_cb_protected_post_ids']); // strip white spaces
				}
			}
			
			
			$blocked_countries = array();
			foreach ($post_array as $key => $val){
				if (strpos($key, 'aiowps_secondary_country_checkbox') === false) {
					continue;
				} else {
					if (!array_key_exists($val, $countries_array)) continue;
					$blocked_countries[] = $val;
				}
			}
			
			if ($error) {
				$this->show_msg_error(__('Attention!','all-in-one-wp-security-and-firewall-premium').$error);
			}

			// Save all the form values to the options
			$aio_wp_security_premium->configs->set_value('aiowps_cb_enable_secondary_country_blocking', isset($_POST['aiowps_cb_enable_secondary_country_blocking']) ? '1' : '');
			$aio_wp_security_premium->configs->set_value('aiowps_cb_secondary_blocking_action', $_POST['aiowps_cb_secondary_blocking_action']);
			$aio_wp_security_premium->configs->set_value('aiowps_secondary_cb_protected_post_ids',$post_ids);
			$aio_wp_security_premium->configs->set_value('aiowps_cb_secondary_blocked_countries',$blocked_countries);
			$aio_wp_security_premium->configs->set_value('aiowps_cb_secondary_redirect_url',$redirect_url);
			$aio_wp_security_premium->configs->save_config();

			// Recalculate points after the feature status/options have been altered
			// $aiowps_feature_mgr->check_feature_status_and_recalculate_points();
			$aio_wp_security_premium->show_msg_settings_updated();
		}
	 ?>
		<h2><?php _e('Secondary Country Blocking Settings', 'all-in-one-wp-security-and-firewall-premium')?></h2>
		<form action="" method="POST">
		<?php wp_nonce_field('aiowpsec-secondary-country-blocking-settings-nonce'); ?>

		<div class="postbox">
		<h3 class="hndle"><label for="title"><?php _e('Enable Secondary Country Blocking', 'all-in-one-wp-security-and-firewall-premium'); ?></label></h3>
		<div class="inside">
			<div class="aio_blue_box">
				<?php
				echo '<p>'.__('The secondary settings on this page allow you to further refine how you block visitors based on the specific pages and posts they try to visit.', 'all-in-one-wp-security-and-firewall-premium').
				'<br />'.__('The countries you selected in the "General Settings" tab will be blocked from the whole site (You can think of the "General Settings" as the first line of blocking defence).', 'all-in-one-wp-security-and-firewall-premium').
				'<br />'.__('The settings on this page are for those countries which have not been blocked sitewide.', 'all-in-one-wp-security-and-firewall-premium') . ' ' . __('These settings allow you to select which countries will be blocked or allowed access to specific pages or posts specified in the configuration below (You can think of this as the second line of blocking defence)', 'all-in-one-wp-security-and-firewall-premiu').
				'<br />'.__('This feature can be very useful for eCommerce situations such as when merchants wish to confine their online sales to people from certain countries due to shipping or tax constraints.', 'all-in-one-wp-security-and-firewall-premium').'</p>';
				//'<br />'.__('NOTE: These settings will apply to those visitors who were not blocked by the "General Settings" configuration,  If your "General Settings" config is disabled or no country is selected, then the "Secondary Settings" will apply to all visitors.', 'all-in-one-wp-security-and-firewall-premium').'</p>';
				
				$aiowps_premium_geodb_dir = $aio_wp_security_premium->get_aiowps_premium_geodb_dir_path();
				?>
			</div>
			<div class="aiowps_cb_yellow_box">
				<?php 
				echo __('NOTE: These settings will apply to visitors who are not blocked by the "General Settings" configuration.', 'all-in-one-wp-security-and-firewall-premium') . ' ' . __('If your "General Settings" configuration is disabled or no country is selected, then the "Secondary Settings" will apply to all visitors.', 'all-in-one-wp-security-and-firewall-premium');
				?>
			</div>
			<?php
			$saved_blocked_countries = ($aio_wp_security_premium->configs->get_value('aiowps_cb_secondary_blocked_countries') == '')?array():$aio_wp_security_premium->configs->get_value('aiowps_cb_secondary_blocked_countries');
			?>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><?php _e('Enable Secondary Country Blocking', 'all-in-one-wp-security-and-firewall-premium')?>:</th>
					<td>
					<input name="aiowps_cb_enable_secondary_country_blocking" type="checkbox"<?php if($aio_wp_security_premium->configs->get_value('aiowps_cb_enable_secondary_country_blocking')=='1') echo ' checked="checked"'; ?> value="1"/>
					<span class="description"><?php echo __('Check this if you want to enable the secondary country blocking feature.', 'all-in-one-wp-security-and-firewall-premium') . ' ' . __('This will allow you to block countries based on certain posts or pages.', 'all-in-one-wp-security-and-firewall-premium') ?></span>
					</td>
				</tr>
			</table>
		</div></div>
		<div class="postbox">
		<h3 class="hndle"><label for="title"><?php _e('Country Page Blocking Options', 'all-in-one-wp-security-and-firewall-premium'); ?></label></h3>
		<div class="inside">
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php _e('Blocking Action', 'all-in-one-wp-security-and-firewall-premium')?>:</th>
				<td>
					<select id="aiowps_cb_secondary_blocking_action" name="aiowps_cb_secondary_blocking_action">
						<option value="0" <?php selected( $aio_wp_security_premium->configs->get_value('aiowps_cb_secondary_blocking_action'), '0' ); ?>><?php _e( 'redirect', 'all-in-one-wp-security-and-firewall-premium' ); ?></option>
					</select>
				<span class="description"><?php _e('Set the type of blocking action you would like to perform', 'all-in-one-wp-security-and-firewall-premium'); ?></span>
				</td> 
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Enter Post/Page IDs To Protect:', 'all-in-one-wp-security-and-firewall-premium')?></th>
				<td>
					<textarea name="aiowps_secondary_cb_protected_post_ids" rows="5" cols="50"><?php echo $aio_wp_security_premium->configs->get_value('aiowps_secondary_cb_protected_post_ids'); ?></textarea>
					<br />
					<span class="description"><?php _e('Enter one or more page/post IDs you wish to block/redirect certain countries for (Each entry must be on a new line).','all-in-one-wp-security-and-firewall-premium');?></span>
					<span class="aiowps_more_info_anchor"><span class="aiowps_more_info_toggle_char">+</span><span class="aiowps_more_info_toggle_text"><?php _e('More Info', 'all-in-one-wp-security-and-firewall-premium'); ?></span></span>
					<div class="aiowps_more_info_body">
							<?php 
							echo '<p class="description">'.__('Each post/page ID must be on a new line.', 'all-in-one-wp-security-and-firewall-premium').'</p>';
							echo '<p class="description">'.__('To find the ID simply edit the post or page and look in your browser bar, the ID is value after the "post." parameter.', 'all-in-one-wp-security-and-firewall-premium').'</p>';
							echo '<p class="description">'.__('Example: wp-admin/post.php?post=<strong>528</strong>&action=edit', 'all-in-one-wp-security-and-firewall-premium').'</p>';
							echo '<p class="description">'.__('The post/page ID in this example is 528.', 'all-in-one-wp-security-and-firewall-premium').'</p>';
							?>
					</div>

				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Redirect URL', 'all-in-one-wp-security-and-firewall-premium')?>:</th>
				<td><input type="text" size="50" name="aiowps_cb_secondary_redirect_url" value="<?php echo $aio_wp_security_premium->configs->get_value('aiowps_cb_secondary_redirect_url'); ?>" />
				<span class="description"><?php _e('Set the value for the URL where you want to send the blocked visitor to', 'all-in-one-wp-security-and-firewall-premium'); ?></span>
				<span class="aiowps_more_info_anchor"><span class="aiowps_more_info_toggle_char">+</span><span class="aiowps_more_info_toggle_text"><?php _e('More Info', 'all-in-one-wp-security-and-firewall-premium'); ?></span></span>
				<div class="aiowps_more_info_body">
						<?php 
						echo '<p class="description">'.__('Example: If you sell products which ship only in your country, you could create a special page for visitors from other countries.', 'all-in-one-wp-security-and-firewall-premium'). ' ' . __('On this page you can explain why they can\'t view your store pages.', 'all-in-one-wp-security-and-firewall-premium').'</p>';
						echo '<p class="description">'.__('You would then enter the URL of that page in this setting.', 'all-in-one-wp-security-and-firewall-premium').'</p>';
						?>
				</div>
				</td> 
			</tr>
			
			<tr valign="top">
				<th scope="row"><?php _e('Select Countries To Block/Redirect', 'all-in-one-wp-security-and-firewall-premium')?>:</th>
			<td>
				<input type="checkbox" id="aiowps_cb_secondary_select_all" name="aiowps_cb_general_select_all" value="1"/><label>Select/De-select All</label>
			<ul class="aiowps-checkbox-grid">
				<?php
				$countries_array = $aio_wp_security_premium->country_tasks_obj->country_codes;
				asort($countries_array);
				foreach($countries_array as $c_code => $c_name) {
					if (in_array($c_code, $saved_blocked_countries)) {
						$check_txt = ' checked="checked"';
					} else {
						$check_txt = '';
					} 
					echo '<li><input type="checkbox" class="aiowps_secondary_country_checkbox" id="aiowps_secondary_country_checkbox_' . $c_code . '" name="aiowps_secondary_country_checkbox_' . $c_code . '"'.$check_txt.' value="' . $c_code . '" /><label for="c_txt">' . $c_name . '</label></li>';
				}
				?>
			</ul>
			</td>
			</tr>
		</table>
		</div></div>
		<input type="submit" name="aiowps_save_secondary_cb_settings" value="<?php _e('Save Settings', 'all-in-one-wp-security-and-firewall-premium')?>" class="button-primary" />
		</form>
		<script type="text/javascript">
		jQuery(document).ready(function($){

			$('.form-table').on('click', '#aiowps_cb_secondary_select_all', function(){
				if (this.checked) {
					$('.aiowps_secondary_country_checkbox').prop('checked', true);
				} else {
					$('.aiowps_secondary_country_checkbox').prop('checked', false);
				}
			});
		});
		</script>
	<?php
	}
	
	/**
	 * Renders the submenu's whitelist tab
	 *
	 * @return void
	 */
	public function render_tab3() {
		global $aio_wp_security;
		global $aio_wp_security_premium;
		
		$result = 1;
		if (isset($_POST['aiowps_save_cb_whitelist_settings'])) {
			$nonce = $_REQUEST['_wpnonce'];
			if (!wp_verify_nonce($nonce, 'aiowpsec-cb-whitelist-settings-nonce')) {
				$aio_wp_security->debug_logger->log_debug("Nonce check failed for save country block whitelist settings!",4);
				die(__('Nonce check failed for save country block whitelist settings!','all-in-one-wp-security-and-firewall-premium'));
			}
			
			if (isset($_POST['aiowps_cb_enable_whitelisting']) && empty($_POST['aiowps_cb_allowed_ip_addresses'])) {
				$this->show_msg_error(__('You must submit at least one IP address!','all-in-one-wp-security-and-firewall-premium'));
			} else {
				if (!empty($_POST['aiowps_cb_allowed_ip_addresses'])) {
					$ip_addresses = stripslashes($_POST['aiowps_cb_allowed_ip_addresses']);
					$ip_list_array = AIOWPSecurity_Utility_IP::create_ip_list_array_from_string_with_newline($ip_addresses);
					$payload = AIOWPSecurity_Utility_IP::validate_ip_list($ip_list_array, 'whitelist');
					if ($payload[0] == 1) {
						// success case
						$result = 1;
						$list = $payload[1];
						$banned_ip_data = implode(PHP_EOL, $list);
						$aio_wp_security_premium->configs->set_value('aiowps_cb_allowed_ip_addresses',$banned_ip_data);
						$_POST['aiowps_cb_allowed_ip_addresses'] = ''; // Clear the post variable for the banned address list
					} else {
						$result = -1;
						$error_msg = $payload[1][0];
						$this->show_msg_error($error_msg);
					}
				} else {
					$aio_wp_security_premium->configs->set_value('aiowps_cb_allowed_ip_addresses',''); // Clear the IP address config value
				}

				if ($result == 1) {
					$aio_wp_security_premium->configs->set_value('aiowps_cb_enable_whitelisting', isset($_POST['aiowps_cb_enable_whitelisting']) ? '1' : '');
					$aio_wp_security_premium->configs->save_config(); // Save the configuration
					$aio_wp_security_premium->show_msg_settings_updated();
				}
			}
		}
		?>
		<h2><?php _e('Country Blocking Login Whitelist', 'all-in-one-wp-security-and-firewall-premium')?></h2>
		<div class="aio_blue_box">
			<?php
			echo '<p>'.__('This feature gives you the option of allowing certain IP addresses or ranges from blocked countries to have access to your WordPress site.', 'all-in-one-wp-security-and-firewall-premium').'
			</p>';
			?>
		</div>
		<div class="postbox">
		<h3 class="hndle"><label for="title"><?php _e('Country Blocking IP Whitelist Settings', 'all-in-one-wp-security-and-firewall-premium'); ?></label></h3>
		<div class="inside">
		<form action="" method="POST">
		<?php wp_nonce_field('aiowpsec-cb-whitelist-settings-nonce'); ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php _e('Enable Country Blocking IP Whitelist', 'all-in-one-wp-security-and-firewall-premium')?>:</th>
				<td>
				<input name="aiowps_cb_enable_whitelisting" type="checkbox" <?php checked($aio_wp_security_premium->configs->get_value('aiowps_cb_enable_whitelisting')); ?> value="1"/>
				<span class="description"><?php _e('Check this if you want to enable the whitelisting of selected IP addresses specified in the settings below', 'all-in-one-wp-security-and-firewall-premium'); ?></span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Enter Whitelisted IP Addresses:', 'all-in-one-wp-security-and-firewall-premium')?></th>
				<td>
					<textarea name="aiowps_cb_allowed_ip_addresses" rows="5" cols="50"><?php echo esc_textarea(wp_unslash(-1 == $result ? $_POST['aiowps_cb_allowed_ip_addresses'] : $aio_wp_security_premium->configs->get_value('aiowps_cb_allowed_ip_addresses'))); ?></textarea>
					<br />
					<span class="description"><?php _e('Enter one or more IP addresses or IP ranges you wish to include in your whitelist.', 'all-in-one-wp-security-and-firewall-premium');?> <?php _e('The addresses specified here will have access to your site even if they come from a country which is blocked.', 'all-in-one-wp-security-and-firewall-premium');?></span>
					<?php $aio_wp_security->include_template('info/ip-address-ip-range-info.php');?>

				</td>
			</tr>
		</table>
		<input type="submit" name="aiowps_save_cb_whitelist_settings" value="<?php _e('Save Settings', 'all-in-one-wp-security-and-firewall-premium')?>" class="button-primary" />
		</form>
		</div></div>
		<?php
		
	}
	
	/**
	 * Renders the submenu's advanced settings tab
	 *
	 * @return void
	 */
	public function render_tab4() {
		global $aio_wp_security;
		global $aio_wp_security_premium;
		
		$result = 1;
		if (isset($_POST['aiowps_save_cb_advanced_settings'])) {
			$nonce = $_REQUEST['_wpnonce'];
			if (!wp_verify_nonce($nonce, 'aiowpsec-cb-ip-settings-nonce')) {
				$aio_wp_security->debug_logger->log_debug("Nonce check failed for save country block advanced settings!",4);
				die(__('Nonce check failed for save country block advanced settings!','all-in-one-wp-security-and-firewall-premium'));
			}
			
			$aio_wp_security_premium->configs->set_value('aiowps_cb_ajax_enabled', isset($_POST['aiowps_cb_ajax_enabled']) ? '1' : '');
			$aio_wp_security_premium->configs->save_config(); // Save the configuration
			$aio_wp_security_premium->show_msg_settings_updated();
		}
		?>
		<h2><?php _e('Country Blocking Advanced Settings', 'all-in-one-wp-security-and-firewall-premium')?></h2>
		<div class="postbox">
		<h3 class="hndle"><label for="title"><?php _e('Use Ajax For Country Blocking Checks', 'all-in-one-wp-security-and-firewall-premium'); ?></label></h3>
		<div class="inside">
		<div class="aio_blue_box">
			<?php
			echo '<p>'.__('If you are using a caching solution on your website such as WP Optimize or similar, then you will need to enable this feature.', 'all-in-one-wp-security-and-firewall-premium').'</p>';
			?>
		</div>
			
		<form action="" method="POST">
		<?php wp_nonce_field('aiowpsec-cb-ip-settings-nonce'); ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php _e('Enable Country Blocking AJAX', 'all-in-one-wp-security-and-firewall-premium')?>:</th>
				<td>
				<input name="aiowps_cb_ajax_enabled" type="checkbox"<?php if($aio_wp_security_premium->configs->get_value('aiowps_cb_ajax_enabled')=='1') echo ' checked="checked"'; ?> value="1"/>
				<span class="description"><?php _e('Check this if you are using caching such as WP Optimize etc.', 'all-in-one-wp-security-and-firewall-premium'); ?></span>
				</td>
			</tr>
		</table>
		<input type="submit" name="aiowps_save_cb_advanced_settings" value="<?php _e('Save Settings', 'all-in-one-wp-security-and-firewall-premium')?>" class="button-primary" />
		</form>
		</div></div>
		<?php
	}
} //end class
