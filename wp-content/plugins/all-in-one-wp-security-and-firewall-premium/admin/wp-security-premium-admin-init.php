<?php
/* 
 * Inits the admin dashboard side of things.
 * Main admin file which loads all settings panels and sets up admin menus. 
 */
class AIOWPSecurity_Premium_Admin_Init {

	public $settings_menu;

	/**
	 * Includes admin dependencies and hook admin actions.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->admin_includes();
		add_action('admin_print_styles', array($this, 'admin_menu_page_styles'));
		
		add_action('aiowps_dashboard_setup',array($this, 'add_premium_dashboard_widgets'));
		add_filter('aiowpsecurity_menu_items', array($this, 'premium_admin_menus'));
		add_filter('list_404_get_columns', array($this, 'list_404_add_columns'));
		add_filter('list_404_get_sortable_columns', array($this, 'list_404_add_sortable_columns'));
	}
	
	/**
	 * This function will include any files needed for the admin dashboard
	 *
	 * @return void
	 */
	public function admin_includes() {
		
	}
	
	/**
	 * Enqueue admin styles.
	 *
	 * @return Void
	 */
	public function admin_menu_page_styles() {
		wp_enqueue_style('dashboard');
		wp_enqueue_style('thickbox');
		wp_enqueue_style('global');
		wp_enqueue_style('wp-admin');
		wp_enqueue_style('aiowps-premium-admin-css', AIOWPS_PREMIUM_URL. '/css/aiowps-premium-styles.css', array(), filemtime(AIOWPS_PREMIUM_PATH.'/css/aiowps-premium-styles.css'));
	}

	/**
	 * Sets up the menu items array which is used to build admin menus
	 *
	 * @return void
	 */
	public function premium_admin_menus($menu_items) {
		$menu_items[] = array(
			'page_title' => __('Country Blocking', 'all-in-one-wp-security-and-firewall'),
			'menu_title' => __('Country Blocking', 'all-in-one-wp-security-and-firewall'),
			'menu_slug' => AIOWPS_CB_SETTINGS_MENU_SLUG,
			'render_callback' => array($this, 'handle_country_blocking_menu_rendering'),
			'icon' => 'country_blocking',
			'order' => 160,
		);
		$menu_items[] = array(
			'page_title' => __('Smart 404', 'all-in-one-wp-security-and-firewall'),
			'menu_title' => __('Smart 404', 'all-in-one-wp-security-and-firewall'),
			'menu_slug' => AIOWPS_SMART_404_SETTINGS_MENU_SLUG,
			'render_callback' => array($this, 'handle_smart_404_menu_rendering'),
			'icon' => 'smart_404',
			'order' => 170,
		);

		return $menu_items;
	}
	
	/**
	 * Renders 'Country Blocking' submenu page.
	 *
	 * @return Void
	 */
	public function handle_country_blocking_menu_rendering() {
		include_once('aiowps-cb-settings-menu.php');
		$this->settings_menu = new AIOWPS_CB_Settings_Menu();
	}
	
	/**
	 * Renders 'Smart 404' submenu page.
	 *
	 * @return Void
	 */
	public function handle_smart_404_menu_rendering() {
		include_once('smart-404-settings-menu.php');
		$this->settings_menu = new AIOWPS_SMART_404_Settings_Menu();
	}
 
	/**
	 * This function adds all the premium dashboard widgets
	 *
	 * @return void
	 */
	public function add_premium_dashboard_widgets() {
		wp_add_dashboard_widget('smart_404', __('Smart 404', 'all-in-one-wp-security-and-firewall-premium'), array($this, 'widget_smart404'));
	}

	/**
	 * Adds the smart404 widget to the main AIOWPS dashboard page
	 */
	public function widget_smart404() {
		global $wpdb;
		$now = current_time('mysql');
		$sql = $wpdb->prepare('SELECT blocked_date FROM '.AIOWPSEC_TBL_PERM_BLOCK.' WHERE block_reason=%s', '404');
		$total_res = $wpdb->get_results($sql);
		// Get number of 404 events for today
		$sql_404 = $wpdb->prepare("SELECT COUNT(event_date) FROM ".AIOWPSEC_TBL_EVENTS." WHERE DATE(event_date) = DATE('".$now."') AND event_type=%s",'404');
		$num_404_today = $wpdb->get_var($sql_404);
		$todays_blocked_count = 0;

		if (empty($total_res)) {
			$total_count = '0';
			$msg = '<p><strong>'.__('You currently have no IP addresses permanently blocked due to 404s.', 'all-in-one-wp-security-and-firewall-premium').'</strong></p>';
		} else {
			$total_count = count($total_res);
			foreach ($total_res as $blocked_item) {
				$now_date_time = get_date_from_gmt($now, 'Y-m-d');
				$blocked_date = get_date_from_gmt($blocked_item->blocked_date, 'Y-m-d');
				if ($blocked_date == $now_date_time) {
					//there was an IP added to permanent block list today
					++$todays_blocked_count;
				}
			}
			if (empty($todays_blocked_count)) $todays_blocked_count = '0';
		}
		
		?>
		<div class="aio_yellow_box">
			<p><strong><?php echo __('# 404 Events Today:', 'all-in-one-wp-security-and-firewall-premium') . ' ' . $num_404_today; ?></strong></p>
			<p><strong><?php echo __('# IPs Permanently Blocked Today:', 'all-in-one-wp-security-and-firewall-premium') . ' ' . $todays_blocked_count; ?></strong></p>
			<hr><p><strong><?php echo __('All Time Total IPs Blocked:', 'all-in-one-wp-security-and-firewall-premium') . ' ' . $total_count; ?></strong></p>
		</div>
		<p><a class="button" href="admin.php?page=<?php echo AIOWPS_SMART_404_SETTINGS_MENU_SLUG; ?>&tab=tab2" target="_blank"><?php _e('View Blocked IPs','all-in-one-wp-security-and-firewall-premium'); ?></a></p>
		<?php
	}

	/**
	 * Uses the AIOWPS filter
	 * Will add a country column to the 404 list table in the main AIOWPS plugin
	 * @param $columns
	 */
	public function list_404_add_columns($columns) {
		if (empty($columns)) return;
		$columns['country_code'] = __('Country', 'all-in-one-wp-security-and-firewall-premium');
		return $columns;
	}

	/**
	 * Uses the AIOWPS filter
	 * Will make country column sortable in the 404 list table in the main AIOWPS plugin
	 * @param $sortable_columns
	 */
	public function list_404_add_sortable_columns($sortable_columns) {
		if (empty($sortable_columns)) return;
		$sortable_columns['country_code'] = array('country_code', false);
		return $sortable_columns;

	}
} // End of class
