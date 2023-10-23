<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

class AIOWPS_SMART_404_Settings_Menu extends AIOWPSecurity_Admin_Menu {

	/**
	 * Smart 404 menu slug
	 *
	 * @var string
	 */
	protected $menu_page_slug = AIOWPS_SMART_404_SETTINGS_MENU_SLUG;

	/**
	 * Constructor adds menu for Smart 404
	 */
	public function __construct() {
		parent::__construct(__('Smart 404', 'all-in-one-wp-security-and-firewall'));
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
				'title' => __('Blocked IPs', 'all-in-one-wp-security-and-firewall'),
				'render_callback' => array($this, 'render_tab2'),
			),
			'tab3' => array(
				'title' => __('Statistics', 'all-in-one-wp-security-and-firewall'),
				'render_callback' => array($this, 'render_tab3'),
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
		global $aio_wp_security, $aio_wp_security_premium;
		$result = '';
		if (isset($_POST['aiowps_smart404_settings_save'])) { // Do form submission tasks
			$error = '';
			$nonce = $_REQUEST['_wpnonce'];
			if (!wp_verify_nonce($nonce, 'aiowpsec-smart-404-nonce')) {
				$aio_wp_security->debug_logger->log_debug("Nonce check failed on smart 404 settings save!",4);
				die("Nonce check failed on smart 404 settings save!");
			}

			$max_404_attempt_val = sanitize_text_field($_POST['aiowps_max_404_attempts']);
			if (!is_numeric($max_404_attempt_val)) {
				$error .= '<br />'.__('You entered a non numeric value for the max login attempts field, it has been set to the default value.','all-in-one-wp-security-and-firewall-premium');
				$max_404_attempt_val = '10';//Set it to the default value for this field
			}

			$time_period_404 = sanitize_text_field($_POST['aiowps_404_retry_time_period']);
			if (!is_numeric($time_period_404)) {
				$error .= '<br />'.__('You entered a non numeric value for the 404 retry time period field, it has been set to the default value.','all-in-one-wp-security-and-firewall-premium');
				$time_period_404 = '10';//Set it to the default value for this field
			}


			if ($error) {
				$this->show_msg_error(__('Attention!','all-in-one-wp-security-and-firewall-premium').$error);
			}

			//Save all the form values to the options
			$aio_wp_security_premium->configs->set_value('aiowps_enable_smart_404', isset($_POST['aiowps_enable_smart_404']) ? '1' : '');
			$aio_wp_security_premium->configs->set_value('aiowps_max_404_attempts', absint($max_404_attempt_val));
			$aio_wp_security_premium->configs->set_value('aiowps_404_retry_time_period', absint($time_period_404));

			$aio_wp_security_premium->configs->save_config();
			$aio_wp_security_premium->show_msg_settings_updated();
		}

		// instant blocking settings save
		if (isset($_POST['save_404_instant_block_settings'])) {
			$nonce = $_REQUEST['_wpnonce'];
			if (!wp_verify_nonce($nonce, 'aiowpsec-instant-404-block-nonce')) {
				$aio_wp_security->debug_logger->log_debug("Nonce check failed for save smart 404 whitelist settings!",4);
				die(__('Nonce check failed for save smart 404 whitelist settings!','all-in-one-wp-security-and-firewall-premium'));
			}

			if (isset($_POST['aiowps_enable_instant_404_string_block']) && empty($_POST['smart_404_instant_block_strings'])) {
				$result = -1;
				$this->show_msg_error(__('You must submit at least one blocking string!','all-in-one-wp-security-and-firewall-premium'));
			} else {
				if (!empty($_POST['smart_404_instant_block_strings'])) {
					$blocking_strings = $_POST['smart_404_instant_block_strings'];
					$aio_wp_security_premium->configs->set_value('smart_404_instant_block_strings',$blocking_strings);
					$_POST['smart_404_instant_block_strings'] = ''; //Clear the post variable

					$aio_wp_security_premium->configs->set_value('aiowps_enable_instant_404_string_block', isset($_POST['aiowps_enable_instant_404_string_block']) ? '1' : '');
				} else {
					$aio_wp_security_premium->configs->set_value('smart_404_instant_block_strings',''); //Clear the config value
				}
				$aio_wp_security_premium->configs->save_config(); //Save the configuration
				$aio_wp_security_premium->show_msg_settings_updated();
				$result = 1;
			}
		}


		// whitelist settings
		$your_ip_address = AIOWPSecurity_Utility_IP::get_user_ip_address();
		if (isset($_POST['save_smart_404_whitelist_settings'])) {
			$nonce = $_REQUEST['_wpnonce'];
			if (!wp_verify_nonce($nonce, 'smart-404-whitelist-nonce')) {
				$aio_wp_security->debug_logger->log_debug("Nonce check failed for save smart 404 whitelist settings!",4);
				die(__('Nonce check failed for save smart 404 whitelist settings!','all-in-one-wp-security-and-firewall-premium'));
			}

			if (isset($_POST['enable_smart_404_whitelist']) && empty($_POST['smart_404_ip_whitelist'])) {
				$this->show_msg_error(__('You must submit at least one IP address!','all-in-one-wp-security-and-firewall-premium'));
			} else {
				if (!empty($_POST['smart_404_ip_whitelist'])) {
					$ip_addresses = $_POST['smart_404_ip_whitelist'];
					$ip_list_array = AIOWPSecurity_Utility_IP::create_ip_list_array_from_string_with_newline($ip_addresses);
					$payload = AIOWPSecurity_Utility_IP::validate_ip_list($ip_list_array, 'whitelist');
					if ($payload[0] == 1) {
						//success case
						$result = 1;
						$list = $payload[1];
						$whitelist_ip_data = implode(PHP_EOL, $list);
						$aio_wp_security_premium->configs->set_value('smart_404_ip_whitelist',$whitelist_ip_data);
						$_POST['smart_404_ip_whitelist'] = ''; //Clear the post variable for the banned address list
					} else {
						$result = -1;
						$error_msg = $payload[1][0];
						$this->show_msg_error($error_msg);
					}

				} else {
					$aio_wp_security_premium->configs->set_value('smart_404_ip_whitelist',''); //Clear the IP address config value
					$result = 1;
				}

				if ($result == 1) {
					$aio_wp_security_premium->configs->set_value('enable_smart_404_whitelist', isset($_POST['enable_smart_404_whitelist']) ? '1' : '');
					$aio_wp_security_premium->configs->save_config(); //Save the configuration

					$aio_wp_security_premium->show_msg_settings_updated();
				}
			}
		}

		?>
		
		<h2><?php _e('Smart 404 Configuration', 'all-in-one-wp-security-and-firewall-premium')?></h2>
		<div class="aio_blue_box">
			<?php
			echo '<p>'.__('Hackers often use automated scripts and bots to constantly probe your site looking for certain URLs they think they can exploit.', 'all-in-one-wp-security-and-firewall-premium').'
			<br />'.__('This repeated probing will cause a lot of http 404 events.', 'all-in-one-wp-security-and-firewall-premium').'
			<br />'.__('This addon allows you to monitor and permanently block IP addresses if they cause more than a certain number of 404 events.', 'all-in-one-wp-security-and-firewall-premium').'
			</p>';
			?>
		</div>

		<div class="postbox">
			<h3 class="hndle"><label for="title"><?php _e('Smart 404 Settings', 'all-in-one-wp-security-and-firewall-premium'); ?></label></h3>
			<div class="inside">
				<?php
				if($aio_wp_security_premium->configs->get_value('aiowps_enable_smart_404')=='1') {
					//display yellow blocked count summary
					global $wpdb;
					//Get number of 404 events for today
					$events_404_today = AIOWPS_Premium_Utilities::get_todays_404_events();
					$num_404_today = empty($events_404_today)?0:count($events_404_today);

					//Get blocked IPs due to 404
					$sql = $wpdb->prepare('SELECT * FROM ' . AIOWPSEC_TBL_PERM_BLOCK . ' WHERE block_reason=%s', '404');
					$total_res = $wpdb->get_results($sql);
					?>
					<div class="aio_yellow_box">
						<?php
						if (empty($total_res)) {
							echo '<p><strong>' . __('You currently have no IP addresses permanently blocked due to 404.', 'all-in-one-wp-security-and-firewall-premium') . '</strong></p>';
						} else {
							$total_count = count($total_res);
							$todays_blocked_count = 0;
							foreach ($total_res as $blocked_item) {
								$now = current_time('mysql');
								$now_date_time = new DateTime($now);
								$blocked_date = new DateTime($blocked_item->blocked_date);
								if ($blocked_date->format('Y-m-d') == $now_date_time->format('Y-m-d')) {
									//there was an IP added to permanent block list today
									++$todays_blocked_count;
								}
							}
							echo '<p><strong>' . __('# 404 Events Today: ', 'all-in-one-wp-security-and-firewall-premium') . $num_404_today . '</strong></p>' .
								'<p><strong>' . __('# IPs Permanently Blocked Today: ', 'all-in-one-wp-security-and-firewall-premium') . $todays_blocked_count . '</strong></p>' .
								'<hr><p><strong>' . __('All Time Total IPs Blocked: ', 'all-in-one-wp-security-and-firewall-premium') . $total_count . '</strong></p>' .
								'<p><a class="button" href="admin.php?page=' . AIOWPS_SMART_404_SETTINGS_MENU_SLUG . '&tab=tab2" target="_blank">' . __('View Blocked IPs', 'all-in-one-wp-security-and-firewall-premium') . '</a></p>';
						}
						?>
					</div>
				<?php
				}
				?>
				<form action="" method="POST">
					<?php wp_nonce_field('aiowpsec-smart-404-nonce'); ?>
					<table class="form-table">
						<tr valign="top">
							<th scope="row"><?php _e('Enable Smart 404 Feature', 'all-in-one-wp-security-and-firewall-premium')?>:</th>
							<td>
								<input name="aiowps_enable_smart_404" type="checkbox"<?php if($aio_wp_security_premium->configs->get_value('aiowps_enable_smart_404')=='1') echo ' checked="checked"'; ?> value="1"/>
								<span class="description"><?php _e('Check this if you want to enable smart404 blocking and apply the settings below', 'all-in-one-wp-security-and-firewall-premium'); ?></span>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e('Max 404 Events', 'all-in-one-wp-security-and-firewall-premium')?>:</th>
							<td><input type="text" size="5" name="aiowps_max_404_attempts" value="<?php echo $aio_wp_security_premium->configs->get_value('aiowps_max_404_attempts'); ?>" />
								<span class="description"><?php _e('Set the value for the maximum number of 404 events before IP address is blocked', 'all-in-one-wp-security-and-firewall-premium'); ?></span>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e('404 Retry Time Period (min)', 'all-in-one-wp-security-and-firewall-premium')?>:</th>
							<td><input type="text" size="5" name="aiowps_404_retry_time_period" value="<?php echo $aio_wp_security_premium->configs->get_value('aiowps_404_retry_time_period'); ?>" />
								<span class="description"><?php _e('If the maximum number of http 404 events for a particular IP address occur within this time period the plugin will permanently block that address.', 'all-in-one-wp-security-and-firewall-premium'); ?></span>
							</td>
						</tr>
					</table>
					<input type="submit" name="aiowps_smart404_settings_save" value="<?php _e('Save Settings', 'all-in-one-wp-security-and-firewall-premium')?>" class="button-primary" />
				</form>
			</div></div>

		<div class="aio_blue_box">
			<?php
			echo '<p><strong>'.__('Instant 404 Blocking Based On String Match', 'all-in-one-wp-security-and-firewall-premium').'</strong></p>';
			echo '<p>'.__('This feature allows you instantly block an IP address based on whether a certain string is contained within the URL which produced the 404 event.', 'all-in-one-wp-security-and-firewall-premium').'
			<br />'.__('The settings below allow you to specify the strings you wish to look out for inside a URL which causes a 404.', 'all-in-one-wp-security-and-firewall-premium').'
				<br />'.__('If the plugin detects one of the strings inside the URL which caused the 404, it will instantly add the IP address to the permanent block list.', 'all-in-one-wp-security-and-firewall-premium').'
			</p>';
			?>
		</div>
		<div class="postbox">
			<h3 class="hndle"><label for="title"><?php _e('Instant 404 Block By String Match', 'all-in-one-wp-security-and-firewall-premium'); ?></label></h3>
			<div class="inside">
				<form action="" method="POST">
					<?php wp_nonce_field('aiowpsec-instant-404-block-nonce'); ?>
					<table class="form-table">
						<tr valign="top">
							<th scope="row"><?php _e('Enable Instant 404 Block Based On String Match', 'all-in-one-wp-security-and-firewall-premium')?>:</th>
							<td>
								<input name="aiowps_enable_instant_404_string_block" type="checkbox"<?php if($aio_wp_security_premium->configs->get_value('aiowps_enable_instant_404_string_block')=='1') echo ' checked="checked"'; ?> value="1"/>
								<span class="description"><?php _e('Check this if you want to instantly block an IP address if the URL contains one of the strings listed below AND a 404 event.', 'all-in-one-wp-security-and-firewall-premium'); ?></span>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e('Enter Strings You Wish To Base 404 Blocking On:', 'all-in-one-wp-security-and-firewall-premium')?></th>
							<td>
								<textarea name="smart_404_instant_block_strings" rows="5" cols="50"><?php echo ($result == -1)?htmlspecialchars($_POST['smart_404_instant_block_strings']):htmlspecialchars($aio_wp_security_premium->configs->get_value('smart_404_instant_block_strings')); ?></textarea>
								<br />
								<span class="description">
									<?php _e('Enter one or more strings you want to listen for during 404 events and based on this block the IP address instantly.','all-in-one-wp-security-and-firewall-premium');?>
									<br /><strong><?php _e('Each string must be on a new line.','all-in-one-wp-security-and-firewall-premium');?></strong>
								</span>
							</td>
						</tr>
					</table>
					<input type="submit" name="save_404_instant_block_settings" value="<?php _e('Save Settings', 'all-in-one-wp-security-and-firewall-premium')?>" class="button-primary" />
				</form>
			</div></div>

		<div class="aio_blue_box">
			<?php
			echo '<p><strong>'.__('Smart 404 White List', 'all-in-one-wp-security-and-firewall-premium').'</strong></p>';
			echo '<p>'.__('In certain cases you may want to prevent particular IP addresses from being permanently blocked due to 404 events.', 'all-in-one-wp-security-and-firewall-premium').'
			<br />'.__('One common case is if you are using a malware scanning service, because the malware scanning bots can often produce 404 events as part of their checks.', 'all-in-one-wp-security-and-firewall-premium').'
				<br />'.__('This feature gives you the option of allowing certain IP addresses or ranges to be immune from being blocked permanently due to too many 404 events.', 'all-in-one-wp-security-and-firewall-premium').'
			</p>';
			?>
		</div>

		<div class="postbox">
			<h3 class="hndle"><label for="title"><?php _e('Smart 404 Whitelist Settings', 'all-in-one-wp-security-and-firewall-premium'); ?></label></h3>
			<div class="inside">
				<?php
				?>
				<form action="" method="POST">
					<?php wp_nonce_field('smart-404-whitelist-nonce'); ?>
					<table class="form-table">
						<tr valign="top">
							<th scope="row"><?php _e('Enable IP Whitelisting', 'all-in-one-wp-security-and-firewall-premium')?>:</th>
							<td>
								<input name="enable_smart_404_whitelist" type="checkbox" <?php checked($aio_wp_security_premium->configs->get_value('enable_smart_404_whitelist')); ?> value="1"/>
								<span class="description"><?php _e('Check this if you want to enable the whitelisting of selected IP addresses specified in the settings below', 'all-in-one-wp-security-and-firewall-premium'); ?></span>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e('Your Current IP Address', 'all-in-one-wp-security-and-firewall-premium')?>:</th>
							<td>
								<input size="20" name="aiowps_user_ip" type="text" value="<?php echo $your_ip_address; ?>" readonly="readonly"/>
								<span class="description"><?php _e('You can copy and paste this address in the text box below if you want to include it in your login whitelist.', 'all-in-one-wp-security-and-firewall-premium'); ?></span>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e('Enter Whitelisted IP Addresses:', 'all-in-one-wp-security-and-firewall-premium')?></th>
							<td>
								<textarea name="smart_404_ip_whitelist" rows="5" cols="50"><?php echo esc_textarea(wp_unslash(-1 == $result ? $_POST['smart_404_ip_whitelist'] : $aio_wp_security_premium->configs->get_value('smart_404_ip_whitelist'))); ?></textarea>
								<br />
								<span class="description"><?php _e('Enter one or more IP addresses or IP ranges you wish to include in your whitelist.', 'all-in-one-wp-security-and-firewall-premium');?></span>
								<?php $aio_wp_security->include_template('info/ip-address-ip-range-info.php');?>

							</td>
						</tr>
					</table>
					<input type="submit" name="save_smart_404_whitelist_settings" value="<?php _e('Save Settings', 'all-in-one-wp-security-and-firewall-premium')?>" class="button-primary" />
				</form>
			</div></div>

	<?php
	}
	
	/**
	 * Renders the submenu's blocked IPs tab
	 *
	 * @return void
	 */
	public function render_tab2() {
		global $wpdb;
		// echo "<script type='text/javascript' src='//www.google.com/jsapi'></script>";//Include the google chart library

		echo '<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>';
		include_once 'smart-404-list-blocked-ip.php'; //For rendering the AIOWPSecurity_List_Table
		$blocked_ip_list = new AIOWPSecurity_List_404_Blocked_IP(); // For rendering the AIOWPSecurity_List_Table

		if (isset($_REQUEST['action'])) { // Do list table form row action tasks
			if ($_REQUEST['action'] == 'unblock_ip') { // Unblock link was clicked for a row in list table
				$blocked_ip_list->unblock_ip_address(strip_tags($_REQUEST['blocked_id']));
			}
		}

		?>
		<div class="aio_blue_box">
			<?php
			echo '<p>' . __('This tab displays the list of all permanently blocked IP addresses due to 404 events.', 'all-in-one-wp-security-and-firewall-premium') . '</p>' .
				'<p>' . __('NOTE: This feature does NOT use the .htaccess file to permanently block the IP addresses so it should be compatible with all web servers running WordPress.', 'all-in-one-wp-security-and-firewall-premium') . '</p>'.
				'<p>' . __('You can also view a list of all 404 events by clicking the following button:', 'all-in-one-wp-security-and-firewall-premium') . '</p>'.
				'<p><a class="button" href="admin.php?page=' . AIOWPSEC_FIREWALL_MENU_SLUG . '&tab=404-detection" target="_blank">' . __('View All 404 Events', 'all-in-one-wp-security-and-firewall-premium') . '</a></p>';
			?>
		</div>

		<div class="postbox">
			<h3 class="hndle"><label
					for="title"><?php _e('Permanently Blocked IP Addresses', 'all-in-one-wp-security-and-firewall-premium');?></label>
			</h3>

			<div class="inside">
				<?php
				//Fetch, prepare, sort, and filter our data...
				$blocked_ip_list->prepare_items();
				?>
				<form id="tables-filter" method="get">
					<!-- For plugins, we also need to ensure that the form posts back to our current page -->
					<input type="hidden" name="page" value="<?php echo esc_attr($_REQUEST['page']); ?>"/>
					<?php
					$blocked_ip_list->search_box('Search', 'search_permanent_block');
					if (isset($_REQUEST["tab"])) {
						echo '<input type="hidden" name="tab" value="' . $_REQUEST["tab"] . '" />';
					}
					?>
					<!-- Now we can render the completed list table -->
					<?php $blocked_ip_list->display(); ?>
				</form>
			</div>
		</div>

	<?php
	}
	
	/**
	 * Renders the submenu's statistics tab
	 *
	 * @return void
	 */
	public function render_tab3() {
		global $aio_wp_security;
		global $aio_wp_security_premium;
		$data_blocked = AIOWPS_Premium_Utilities::get_all_404_blocked();
		$blocked_ip_count = AIOWPS_Premium_Utilities::count_blocked_countries($data_blocked); // sort into simple array showing country and count

		/***** For line chart - start *****/
		// for line chart showing top 5 countries producing most 404 in last x days
		$last_x = 10; // set last number of days for line chart
		$last_x_days_404_data = AIOWPS_Premium_Utilities::get_last_n_days_404($last_x); //get 404 events for last x days
		$last_x_days_count = AIOWPS_Premium_Utilities::count_last_n_days($last_x_days_404_data);

		//extract countries
		$countries_10day_array = $last_x_days_count[0]; // list of countries
		$data_10day_array = $last_x_days_count[1]; // data
		// Let's get the dates for the last 10 days
		$dates_last_x = array();
		for ($i=0; $i<$last_x; $i++) {
			$dates_last_x[] = date("d M", strtotime($i." days ago"));
		}
		$dates_last_x = array_reverse($dates_last_x);
		$line_chart_data = "";
		//$line_chart_data .= "['Date', '404 Count', ";
		$line_chart_data .= "['Date', ";
		foreach ($countries_10day_array as $key=>$value) {
			$line_chart_data .= "'" . $key . "', ";
		}
		$line_chart_data = substr($line_chart_data, 0, -2); //remove the comma and space at end
		$line_chart_data .= '],';

		$line_chart_values = "";
		$sorted_data = array();
		foreach ($dates_last_x as $day_mth) {
			$current_404_total = 0;
			$line_chart_values .= "['".$day_mth."', ";
			foreach ($countries_10day_array as $key=>$value) {
				$current_404_total = 0;
				foreach ($data_10day_array as $xyz) {
					$xyz_date = $xyz['event_date'];
					$datetime = new DateTime($xyz_date);
					$f_date = $datetime->format('d M');
					if ($f_date==$day_mth && $key==$xyz['country_code']) {
						++$current_404_total;
					}

				}
				$sorted_data[$day_mth][$key] = $current_404_total;
			}
		}

		//we now have our sorted data so lets put together the rest of the google charts string
		foreach ($sorted_data as $key=>$value) {
			$line_chart_data .= "['".$key."', ";
			foreach($value as $tot){
				$line_chart_data .= $tot.", ";
			}
			$line_chart_data = substr($line_chart_data, 0, -2); //remove the comma and space at end
			$line_chart_data .= '],';
		}
		$line_chart_data = substr($line_chart_data, 0, -1); //remove the comma at end
		$line_chart_widget_title = 'Last '.$last_x.' Days - 404 Events By Country';
		/******** For line chart - end *****/
		$data_404 = AIOWPS_Premium_Utilities::get_all_404_events();

		//Get all-time top 5 404 counts by country
		$country_404_count = $aio_wp_security_premium->configs->get_value('smart_404_all_time_404_count');
		$country_404_count = maybe_unserialize($country_404_count);
		if (!empty($country_404_count)) {
			arsort($country_404_count);
			$country_404_count = array_slice($country_404_count, 0, 5, true); //get top 5
		}

		$ip_top_10_count = AIOWPS_Premium_Utilities::count_404_ips($last_x_days_404_data); //sort into simple array showing IP and count
		$ip_top_10_count = array_slice($ip_top_10_count, 0, 10, true);


		//Now add widgets for each google chart and include the relevant javascript etc
		echo '<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>';
		?>
		<h2><?php _e('Smart 404 Stats', 'all-in-one-wp-security-and-firewall-premium')?></h2>
		<div id="smart_404_dashboard_widget_content">
			<script type="text/javascript">
				google.charts.load('current', {'packages':['bar','corechart']});
			</script>
			<div class="aiowps_dashboard_box_large">
				<div class="postbox">
					<h3 class="hndle"><label for="title"><?php _e($line_chart_widget_title, 'all-in-one-wp-security-and-firewall-premium'); ?></label></h3>
					<div class="inside">
						<?php
						if (empty($last_x_days_404_data)) {
							echo '<div class="aio_yellow_box"><p>'.__('No data to display yet','all-in-one-wp-security-and-firewall-premium').'</p></div>';
						} else {
						?>
							<script type="text/javascript">
								google.charts.setOnLoadCallback(drawChartLine);
								function drawChartLine() {
									var data = google.visualization.arrayToDataTable([
										<?php echo $line_chart_data; ?>
									]);

									var options = {
										title: 'Top 5 Countries - 404 Events',
										height: '300'
									};

									var chart = new google.visualization.LineChart(document.getElementById('line_404_chart_div'));

									chart.draw(data, options);
								}
							</script>
							<div id='line_404_chart_div'></div>

						<?php
						}
						?>

					</div></div>
			</div>
			<div class="aiowps_dashboard_box_large">
				<div class="postbox">
					<h3 class="hndle"><label for="title"><?php _e('Last 10 Days - 404 Events By IP', 'all-in-one-wp-security-and-firewall-premium'); ?></label></h3>
					<div class="inside">
						<?php
						if(empty($last_x_days_404_data)){
							echo '<div class="aio_yellow_box"><p>'.__('No data to display yet','all-in-one-wp-security-and-firewall-premium').'</p></div>';
						}else{
							$pt_src_chart_data3 = "";
							$pt_src_chart_data3 .= "['IP Address', '404 Count'],";
							foreach ($ip_top_10_count as $key=>$value) {
								$pt_src_chart_data3 .= "['" . $key . "', " . $value . "],";
							}

							?>
							<script type="text/javascript">
								google.charts.setOnLoadCallback(drawChart);
								function drawChart() {
									var data = google.visualization.arrayToDataTable([
										<?php echo $pt_src_chart_data3; ?>
									]);

									var options = {
										height: '300',
										width: '550',
										backgroundColor: 'F6F6F6',
										colors: ['#990099']
									};

									var chart = new google.charts.Bar(document.getElementById('count_top_404_ip_chart_div'));
									chart.draw(data, options);
								}
							</script>
							<div id='count_top_404_ip_chart_div'></div>
						<?php
						}
						?>
					</div></div>
			</div>
			<div class="aiowps_dashboard_box_large">
			<div class="postbox">
			<h3 class="hndle"><label for="title"><?php _e('All Time Top 5 - 404 Events By Country', 'all-in-one-wp-security-and-firewall-premium'); ?></label></h3>
			<div class="inside">
				<?php
				if(empty($country_404_count)){
					echo '<div class="aio_yellow_box"><p>'.__('No data to display yet','all-in-one-wp-security-and-firewall-premium').'</p></div>';
				}else{
					$pt_src_chart_data = "";
					$pt_src_chart_data .= "['Country', '404 Count'],";
					foreach ($country_404_count as $key=>$value) {
						$pt_src_chart_data .= "['" . $key . "', " . $value . "],";
					}
					?>
					<script type="text/javascript">
						//google.charts.load('current', {'packages':['bar']});
						google.charts.setOnLoadCallback(drawChart);
						function drawChart() {
							var data = google.visualization.arrayToDataTable([
								<?php echo $pt_src_chart_data; ?>
							]);

							var options = {
								height: '300',
								width: '550',
								backgroundColor: 'F6F6F6'
							};

							var chart = new google.charts.Bar(document.getElementById('count_404_chart_div'));
							chart.draw(data, options);
						}
					</script>
					<div id='count_404_chart_div'></div>
				<?php
				}
				?>
			</div></div>
			</div>
			<div class="aiowps_dashboard_box_large">
				<div class="postbox">
				<h3 class="hndle"><label for="title"><?php _e('All Time Top 10 - Number of IPs Blocked By Country', 'all-in-one-wp-security-and-firewall-premium'); ?></label></h3>
					<?php
					?>
				<div class="inside">
					<?php
					if(empty($data_blocked)){
						echo '<div class="aio_yellow_box"><p>'.__('No data to display yet','all-in-one-wp-security-and-firewall-premium').'</p></div>';
					}else{
						$top10_blocked_ip_count = array_slice($blocked_ip_count, 0, 10, true); //get top 10
						$pt_src_chart_data2 = "";
						$pt_src_chart_data2 .= "['Country', '# Blocked IPs'],";
						foreach ($top10_blocked_ip_count as $key=>$value) {
							$pt_src_chart_data2 .= "['" . $key . "', " . $value . "],";
						}

						?>
						<script type="text/javascript">
							google.charts.setOnLoadCallback(drawChartBlocked);
							function drawChartBlocked() {
								var data = google.visualization.arrayToDataTable([
									<?php echo $pt_src_chart_data2; ?>
								]);

								var options = {
									height: '300',
									width: '550',
									backgroundColor: 'F6F6F6',
									colors: ['#dc3912']
								};

								var chart = new google.charts.Bar(document.getElementById('ips_blocked_chart_div'));
								chart.draw(data, options);
							}
						</script>
						<div id='ips_blocked_chart_div'></div>
					<?php
					}
					?>
				</div></div>
			</div>
		</div>
		

		<style>
			.aiowps_dashboard_box_large {
				width: 600px;
			}
		</style>
	   

		<?php
		
	}
} //end class
