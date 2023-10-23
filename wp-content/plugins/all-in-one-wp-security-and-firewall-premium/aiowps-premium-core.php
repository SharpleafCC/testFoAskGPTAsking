<?php
if (!defined('ABSPATH')) die('No direct access.');

if (!class_exists('AIOWPS_PREMIUM')) {

class AIOWPS_PREMIUM {
    public $version = '1.0.3';
	public $db_version = '1.0.0';
	public $plugin_url;
	public $plugin_path;
	public $configs;
	public $admin_init;
	//var $debug_logger; //Don't need?? Use parent plugin logger
	public $country_tasks_obj;
	public $smart_404_tasks_obj;

    /**
     * Whether the page is admin dashboard page.
     * @var boolean
     */
    private $is_admin_dashboard_page;

    /**
     * Whether the page is admin plugin page.
     * @var boolean
     */
    private $is_plugin_admin_page;

    /**
     * Whether the page is admin AIOWPS page.
     * @var boolean
     */
    private $is_aiowps_admin_page;

    /**
     * Whether the page is MaxMind Integration page.
     * @var boolean
     */
    private $is_aiowps_maxmind_integration_tab_page;

	/**
	 * Class constructor
	 */
    public function __construct() {
		$this->load_configs();
		$this->define_constants();
		$this->includes();
		$this->loader_operations();

		add_action('init', array($this, 'aios_premium_plugin_init'), 1);
		add_action('admin_init', array($this, 'aios_premium_admin_init'), 1);
		add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts_styles'), 15);
		do_action('aiowps_cb_loaded');
		add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_global_scripts'), 9);
		add_action('wp_ajax_aiowps_premium_dismiss_maxmind_admin_notice', array(__CLASS__, 'aiowps_premium_dismiss_maxmind_admin_notice'));
		add_action('aiowpsecurity_loaded', array(__CLASS__, 'load_premium_tfa'));
		add_action('aios_reset_all_settings', array($this, 'aios_premium_reset_all_settings'));
	}

	public function enqueue_scripts_styles() {
		global $aio_wp_security_premium, $post;
		if ('1' == $aio_wp_security_premium->configs->get_value('aiowps_cb_ajax_enabled')) {
			$redirect_url = $aio_wp_security_premium->configs->get_value('aiowps_cb_redirect_url');
			$redirect_secondary_url = $aio_wp_security_premium->configs->get_value('aiowps_cb_secondary_redirect_url');
			$post_id = empty($post) ? 0 : $post->ID;
			wp_register_script('ajax-country-check', AIOWPS_PREMIUM_URL . '/js/aiowps-cb.js', array('jquery'), $this->version);
			wp_enqueue_script('ajax-country-check');
			wp_localize_script('ajax-country-check', 'AIOWPSCB', array(
				'ajaxurl' => admin_url('admin-ajax.php'), // URL to wp-admin/admin-ajax.php to process the request
				'redirect_url' => $redirect_url,
				'redirect_secondary_url' => $redirect_secondary_url,
				'security' => wp_create_nonce('wp_nonce'), // generate a nonce
				'post_id' => $post_id // pass the post id to check for the secondary country block particular page / post block
			));
		}
	}

	/**
	 * Check maxmind key notice clicked by user.
	 */
	public static function aiowps_premium_dismiss_maxmind_admin_notice() {
		check_ajax_referer('aiowps-premium-maxmind-dismiss-notice', 'nonce');
        global $aio_wp_security_premium;
        $aio_wp_security_premium->configs->set_value('aiowps_premium_is_maxmind_notice_dismissed', '1');
        $aio_wp_security_premium->configs->save_config();
        wp_die();
	}
	
	public function plugin_url() { 
		if ($this->plugin_url) return $this->plugin_url;
		return $this->plugin_url = plugins_url('', __FILE__);
	}

	public function plugin_path() { 	
		if ($this->plugin_path) return $this->plugin_path;
		return $this->plugin_path = untrailingslashit(plugin_dir_path(__FILE__));
	}
	
	public function load_configs() {
		include_once('classes/aiowps-premium-config.php');
		$this->configs = AIOWPS_Premium_Config::get_instance();
	}
	
	public function define_constants() {
		define('AIOWPS_PREMIUM_VERSION', $this->version);
		define('AIOWPS_PREMIUM_URL', $this->plugin_url());
		define('AIOWPS_PREMIUM_PATH', $this->plugin_path());
		define('AIOWPS_PREMIUM_DB_VERSION', $this->db_version);
		define('AIOWPS_PREMIUM_MANAGEMENT_PERMISSION', 'add_users');
		
		define('AIOWPS_CB_MENU_SLUG_PREFIX', 'wppg');
		define('AIOWPS_CB_MAIN_MENU_SLUG', 'wppg_main');
		define('AIOWPS_CB_SETTINGS_MENU_SLUG', 'aiowpsec_country_blocking');
		define('AIOWPS_INTEGRATION_TAB_SLUG', admin_url('admin.php?page=aiowpsec_settings&tab=integration'));
        define('AIOWPS_CB_ENABLE_SPECIAL_DEBUG', '0');

		define('AIOWPS_SMART_404_MENU_SLUG_PREFIX', 'aiowps_sm_404');
		define('AIOWPS_SMART_404_MAIN_MENU_SLUG', 'aiowps_sm_main');
		define('AIOWPS_SMART_404_SETTINGS_MENU_SLUG', 'aiowpsec_smart_404');
		define('AIOWPS_MAXMIND_DATABASE', 'GeoLite2-Country.mmdb');
		if (!defined('AIOWPSECURITY_NOADS_B'))  define('AIOWPSECURITY_NOADS_B', true);
		global $wpdb;
	}

	public function includes() {
		//Load common files
		include_once(AIOWPS_PREMIUM_PATH.'/classes/aiowps-premium-utilities.php');
		include_once('classes/aiowp-cb-general-init-tasks.php');
		include_once(AIOWPS_PREMIUM_PATH.'/classes/aiowps-premium-base-tasks.php');
		include_once('classes/aiowps-cb-country-tasks.php');
		include_once('classes/smart-404-general-init-tasks.php');
		include_once('classes/smart-404-tasks.php');
		include_once(AIOWPS_PREMIUM_PATH.'/classes/aiowps-wpcli-commands.php');
		include_once('classes/aiowps-cb-configure-settings.php');
		include_once('classes/smart-404-configure-settings.php');
		
		if (is_admin()) { // Load admin side only files
			include_once('admin/wp-security-premium-admin-init.php');
			include_once(AIOWPS_PREMIUM_PATH.'/admin/aiowps-premium-maxmind-settings.php');
		} else { // Load front end side only files

		}
	}

	/**
	 * Enqueue scripts and styles on admin pages.
	 */
	public function admin_enqueue_global_scripts() {
        if (!current_user_can(apply_filters('aios_management_permission', 'manage_options'))) {
            return;
        }
		$enqueue_version = (defined('WP_DEBUG') && WP_DEBUG) ? AIOWPS_PREMIUM_VERSION.'.'.time() : AIOWPS_PREMIUM_VERSION;
        if (
            $this->is_admin_dashboard_page()
            ||
            $this->is_plugin_admin_page()
            ||
            $this->is_aiowps_admin_page()
        ) {
            wp_enqueue_script(
                'aiowps-premium-global',
                AIOWPS_PREMIUM_URL . '/js/aiowps-premium-global.js',
                array('jquery'),
                $enqueue_version,
                true
            );
            wp_localize_script(
                'aiowps-premium-global',
                'aiowps_premium_global_data',
                array(
                    'aiowps_maxmind_dismiss_notice_nonce' => wp_create_nonce('aiowps-premium-maxmind-dismiss-notice'),
                )
            );
        }

        if ($this->is_aiowps_maxmind_integration_tab_page()) {
            wp_enqueue_script(
                'aiowps-premium-integration',
                AIOWPS_PREMIUM_URL . '/js/aiowps-premium-integration.js',
                array('jquery'),
                $enqueue_version,
                true
            );
            wp_localize_script(
                'aiowps-premium-integration',
                'aiowps_premium_integration_data',
                array(
                    'maxmind_show_str' => __('Show', 'all-in-one-wp-security-and-firewall-premium'),
                    'maxmind_show_password_str' => __('Show password', 'all-in-one-wp-security-and-firewall-premium'),
                    'maxmind_hide_str' => __('Hide', 'all-in-one-wp-security-and-firewall-premium'),
                    'maxmind_hide_password_str' => __('Hide password', 'all-in-one-wp-security-and-firewall-premium')
                )
            );
        }
	}
	
	/**
     * The AIOS Premium loader operations
     *
     * @global AIO_WP_Security $aio_wp_security
     * 
     * @return void
     */
	public function loader_operations() {
		add_action('plugins_loaded',array($this, 'plugins_loaded_handler')); // plugins loaded hook
		if (is_admin()) {
			register_activation_hook(__FILE__, array($this, 'activate_handler')); // activation hook
			$this->admin_init = new AIOWPSecurity_Premium_Admin_Init();
            add_action('all_admin_notices', array($this, 'aiowps_premium_show_admin_notice'));
		}
	}

	/**
     * The AIOWPS Premium Plugin activation handler.
     *
     * @return void
     */
	public static function activate_handler() {
		$aiowps_plugin_name = htmlspecialchars('All In One WP Security & Firewall');
		$aiowps_link = '<a href="' . esc_url('http://wordpress.org/plugins/all-in-one-wp-security-and-firewall/"') . '" target="_blank">'.$aiowps_plugin_name.'</a>';
		if (!class_exists('AIO_WP_Security')) {
			// add_action('admin_notices', array($this, 'aiowps_plugin_not_active'));
			$msg =  __('Attention:', 'all-in-one-wp-security-and-firewall-premium').' '.sprintf(__('You do not have the plugin %s active.', 'all-in-one-wp-security-and-firewall-premium').' '.__('The %s plugin needs to be installed and active in order for the %s Premium plugin to work.', 'all-in-one-wp-security-and-firewall-premium'), $aiowps_link, $aiowps_plugin_name, $aiowps_plugin_name);
			wp_die('<p>'.$msg.'</p>');
		}

		if (version_compare(AIO_WP_SECURITY_VERSION, '4.0.4', '<')) {
			// Upgrade needed
			$msg = '<p>'.sprintf(__('Please upgrade the %s Plugin.', 'all-in-one-wp-security-and-firewall-premium').' '.__('You need at least version 4.0.4 in order for the %s Premium addon to work.', 'all-in-one-wp-security-and-firewall-premium'), $aiowps_link, $aiowps_plugin_name).'</p>';
			wp_die($msg);
		}
		//Only runs when the plugin activates
		include_once ('classes/aiowps-cb-installer.php');
		AIOWPS_CB_Installer::run_installer();
		include_once ('classes/smart-404-installer.php');
		AIOWPS_SMART_404_Installer::run_installer();
	}

    /**
     * The AIOWPS Premium Plugin deactivation handler.
     *
     * @return void
     */
    public static function deactivate_handler() {
        $geoip_db_update_schedule_hook_name = 'aiowps_premium_daily_geoip_update';
        if (wp_next_scheduled($geoip_db_update_schedule_hook_name)) {
            wp_clear_scheduled_hook($geoip_db_update_schedule_hook_name);
        }
    }

	/**
	 * Download the MaxMind Country database from the MaxMind server.
	 *
	 * @param string $license_key The license key to be used when downloading the database.
	 * @return string|WP_Error The path to the database file or an error if invalid.
	 */
	public function aiowps_premium_download_maxmind_database($license_key) {
		global $aio_wp_security_premium;
		$this->create_folders();
		$aiowps_premium_geodb_path = $this->get_aiowps_premium_geodb_dir_path().'/'.AIOWPS_MAXMIND_DATABASE;
		
		$download_uri = add_query_arg(
			array(
				'edition_id'  => 'GeoLite2-Country',
				'license_key' => urlencode($license_key),
				'suffix'      => 'tar.gz',
			),
			'https://download.maxmind.com/app/geoip_download'
		);

		// Make the scope of $wp_file_descriptions global, so that when wp-admin/includes/file.php assigns to it, it is adjusting the global variable as intended
		global $wp_file_descriptions; // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable

		// Needed for the download_url call right below.
		require_once ABSPATH . 'wp-admin/includes/file.php';

		$tmp_archive_path = download_url(esc_url_raw($download_uri));
		
		if (is_wp_error($tmp_archive_path)) {
			// Transform the error into something more informative.
			$error_data = $tmp_archive_path->get_error_data();
			
			if (isset($error_data['code'])) {
				
				switch ($error_data['code']) {
					case 401:
					$error_msg = __('The MaxMind license key is invalid, if you have recently created this key, you may need to wait for it to become active.', 'all-in-one-wp-security-and-firewall-premium');
					echo'<div id="message" class="aio_red_box"><p><strong>'.$error_msg.'</strong></p></div>';
						return new WP_Error(
							'aiowpsecurity_maxmind_geolocation_database_license_key',$error_msg
						);
						
				}
			}

			return new WP_Error('aiowpsecurity_maxmind_geolocation_database_download', __('Failed to download the MaxMind database.', 'all-in-one-wp-security-and-firewall-premium'));
		}

		// Extract the database from the archive.
		try {
			$file = new PharData($tmp_archive_path);

			$tmp_database_path = trailingslashit(dirname($tmp_archive_path)) . trailingslashit($file->current()->getFilename()) . AIOWPS_MAXMIND_DATABASE;
			$file->extractTo(
				dirname($tmp_archive_path),
				trailingslashit($file->current()->getFilename()) . AIOWPS_MAXMIND_DATABASE,
				true
			);
		} catch (Exception $exception) {
			/* translators: %s: MaxMind database download exception error */
			$error_msg = sprintf(__('MaxMind database download error: %s', 'all-in-one-wp-security-and-firewall-premium'), $exception->getMessage());
			return new WP_Error('aiowpsecurity_maxmind_geolocation_database_download_error', $error_msg);
		} finally {
			// Remove the archive since we only care about a single file in it.
			@unlink($tmp_archive_path);// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged
		}

		
		if (!@copy($tmp_database_path, $aiowps_premium_geodb_path)) {
			/* translators: $1: Temp MaxMind database file downloaded path, $2 MaxMind database storage file path */
			$error_msg = sprintf(__('Move MaxMind database file from %1$s to %2$s', 'all-in-one-wp-security-and-firewall-premium'),$tmp_database_path, $aiowps_premium_geodb_path);
			return new WP_Error('aiowpsecurity_maxmind_geolocation_database_move_error', $error_msg);
		}
		@unlink($tmp_database_path);// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged
		@rmdir(dirname($tmp_database_path));// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged
		return $tmp_database_path;
	}

	/**
	 * Show maxmind license key notice in corresponding admin pages.
	 *
	 * @return void
	 */
	public function aiowps_premium_show_admin_notice() {
		if (!current_user_can(apply_filters('aios_management_permission', 'manage_options')) || AIOWPS_Premium_Utilities::woocommerce_maxmind_db_exists()) {
			return;
		}

		global $aio_wp_security_premium;
		$aiowps_premium_geodb_path = $this->get_aiowps_premium_geodb_dir_path().'/'.AIOWPS_MAXMIND_DATABASE;
		if (empty($aio_wp_security_premium->configs->get_value('aiowps_premium_maxmind_key'))) {
			if ($this->is_maxmind_notice_shown() &&
                (
                    $this->is_admin_dashboard_page()
                    ||
                    $this->is_plugin_admin_page()
                    ||
                    $this->is_aiowps_admin_page()
                )
                &&
                !$this->is_aiowps_maxmind_integration_tab_page()
            ) {
				echo '<div class="notice notice-warning is-dismissible aiowps-premium-maxmind-admin-notice">
                        <p>';
                            /* translators: %s: MaxMind Integration tab URL */
                            printf(__('Please add the MaxMind license key <a href="%s">here</a> to use the Country blocking and Smart 404 features.', 'all-in-one-wp-security-and-firewall-premium'), esc_url(AIOWPS_INTEGRATION_TAB_SLUG));
				echo '    </p>
                    </div>';
			}
		} elseif (!is_readable($aiowps_premium_geodb_path)) {
				if ($this->is_admin_dashboard_page()
                    ||
                    $this->is_plugin_admin_page()
                    ||
                    $this->is_aiowps_admin_page())
                {
                    echo '<div class="notice notice-error">
                            <p>';
                                /* translators: %s: MaxMind Geo Database path. */
                                printf(
                                __('The %s file is not readable, ensure that the file exists.', 'all-in-one-wp-security-and-firewall-premium') . ' ' . __('If it exists, please ensure that the file path is writable and readable.', 'all-in-one-wp-security-and-firewall-premium'),
                                $aiowps_premium_geodb_path
                                );
                    echo '</p>
                         </div>';
				}
		}
	}

    /**
     * Check whether the MaxMind admin notice shown.
     *
     * @return boolean True if the MaxMind Admin notice is shown, Otherwise false.
     */
    private function is_maxmind_notice_shown() {
        global $aio_wp_security_premium;
        $is_maxmind_notice_dismissed = $aio_wp_security_premium->configs->get_value('aiowps_premium_is_maxmind_notice_dismissed');
        return (1 != $is_maxmind_notice_dismissed);
    }

    /**
     * Check whether current admin page is Admin Dashboard page or not.
     *
     * @return boolean True if Admin Dashboard page, Otherwise false.
     */
    private function is_admin_dashboard_page() {
        if (isset($this->is_admin_dashboard_page)) {
            return $this->is_admin_dashboard_page;
        }
        global $pagenow;
        $this->is_admin_dashboard_page = 'index.php' == $pagenow;
        return $this->is_admin_dashboard_page;
    }

    /**
     * Check whether current admin page is plugin page or not.
     *
     * @return boolean True if Admin Plugin page, Otherwise false.
     */
    private function is_plugin_admin_page() {
        if (isset($this->is_plugin_admin_page)) {
            return $this->is_plugin_admin_page;
        }
        global $pagenow;
        $this->is_plugin_admin_page = 'plugins.php' == $pagenow;
        return $this->is_plugin_admin_page;
    }

    /**
     * Check whether current admin page is All In One WP Security admin page or not.
     *
     * @return boolean True if All In One WP Security admin page, Otherwise false.
     */
    private function is_aiowps_admin_page() {
        if (isset($this->is_aiowps_admin_page)) {
            return $this->is_aiowps_admin_page;
        }
        global $pagenow;
        $this->is_aiowps_admin_page = ('admin.php' == $pagenow && isset($_GET['page']) && false !== strpos($_GET['page'], AIOWPSEC_MENU_SLUG_PREFIX));
        return $this->is_aiowps_admin_page;
    }

    /**
     * Check whether current admin page is MaxMind Integration tab page or not.
     *
     * @return boolean True if MaxMind Integration tab page, Otherwise false.
     */
    private function is_aiowps_maxmind_integration_tab_page() {
        if (isset($this->is_aiowps_maxmind_integration_tab_page)) {
            return $this->is_aiowps_maxmind_integration_tab_page;
        }
        global $pagenow;
        $this->is_aiowps_maxmind_integration_tab_page = ('admin.php' == $pagenow
                                                        && isset($_GET['page'])
                                                        && 'aiowpsec_settings' == $_GET['page']
                                                        && isset($_GET['tab'])
                                                        && 'integration' == $_GET['tab']
        );
        return $this->is_aiowps_maxmind_integration_tab_page;
    }

	/**
	 * Get maxmind geodb directory path.
	 *
	 * @return string
	 */
	public function get_aiowps_premium_geodb_dir_path() {
		global $aio_wp_security_premium;
		if(empty($aio_wp_security_premium->configs->get_value('aiowps_premium_geodb_dir'))){
			$aio_wp_security_premium->configs->set_value('aiowps_premium_geodb_dir', wp_generate_password(12, false));
		}
		$upload_dir = wp_upload_dir();
		$upload_path = $upload_dir['basedir'];
		$aiowps_premium_geodb_dir = $upload_path.'/'.$aio_wp_security_premium->configs->get_value('aiowps_premium_geodb_dir');
		return $aiowps_premium_geodb_dir;
	}

	/**
	 * Create aiowps-premium folder inside uploads directory in order to save the maxming gelocation db. If folder can't created then show error message to user.
	 */
	private function create_folders() {
		
		$aiowps_premium_geodb_dir = $this->get_aiowps_premium_geodb_dir_path();
		if (!is_dir($aiowps_premium_geodb_dir) && !wp_mkdir_p($aiowps_premium_geodb_dir)) {
			/* translators: %s: Maxmind db download location. */
			return new WP_Error('create_folders', sprintf(__('The request to the filesystem failed: unable to create directory %s.', 'all-in-one-wp-security-and-firewall-premium') . ' ' . __(' Please check your file permissions.', 'all-in-one-wp-security-and-firewall-premium'), str_ireplace(ABSPATH, '', $aiowps_premium_geodb_dir)));
		}
		return true;
	}
	
	/**
	 * Show settings successfully updated message.  
	 */
	public function show_msg_settings_updated() {
        echo '<div id="message" class="updated fade"><p><strong>';
        _e('Settings successfully updated.','aiowpsecurity');
        echo '</strong></p></div>';
    }

	/**
	 * The AIOWPS Premium Plugin uninstall actions.
	 */
	public static function uninstall_handler(){
		global $aio_wp_security_premium;
		$aiowps_premium_geodb_dir = $aio_wp_security_premium->get_aiowps_premium_geodb_dir_path();
		$aiowps_premium_geodb_path = $aiowps_premium_geodb_dir.'/'.AIOWPS_MAXMIND_DATABASE;
        if (file_exists($aiowps_premium_geodb_path)) wp_delete_file($aiowps_premium_geodb_path);
		if (is_dir($aiowps_premium_geodb_dir)) @rmdir($aiowps_premium_geodb_dir);
		return $aio_wp_security_premium->configs->delete_config();
	}
	
	public function do_db_upgrade_check() {
		if(is_admin()){//Check if DB needs to be updated
			if (get_option('aiowps_premium_db_version') != AIOWPS_PREMIUM_DB_VERSION) {
				include_once ('classes/aiowps-cb-installer.php');
				AIOWPS_CB_Installer::run_installer();
				include_once ('classes/smart-404-installer.php');
				AIOWPS_SMART_404_Installer::run_installer();
			}
		}
	}
	
	/**
	 * The AIOWPS Premium loader handler runs when plugins loaded.
	 *
	 * @global AIO_WP_Security $aio_wp_security
	 * 
	 * @return void
	 */
	
	public function plugins_loaded_handler() {//Runs when plugins_loaded action gets fired
		global $aio_wp_security;
		if (!class_exists('Updraft_Manager_Updater_1_8')) {
			include_once(AIOWPS_PREMIUM_PATH.'/vendor/davidanderson684/simba-plugin-manager-updater/class-udm-updater.php');
		}
		try {
			// defined in wp-config the premium plugin update url.
			$premium_plugin_update_url = defined('AIOWPS_PREMIUM_PLUGIN_UPDATE_URL') ? AIOWPS_PREMIUM_PLUGIN_UPDATE_URL : 'https://aiosplugin.com/';
			new Updraft_Manager_Updater_1_8($premium_plugin_update_url, 1, 'all-in-one-wp-security-and-firewall-premium/aiowps-premium.php', array('require_login' => false));
		} catch (Exception $e) {
			$aio_wp_security->debug_logger->log_debug("AIOWPS Update Manager: ". $e->getMessage() . ' at ' . $e->getFile() . ' line ' . $e->getLine());
		}
		
		if(is_admin()){//Do admin side plugins_loaded operations
			if(!class_exists('AIO_WP_Security')){
				add_action('admin_notices', array($this, 'aiowps_plugin_not_active'));		
				return;
			}

			$this->do_db_upgrade_check();
			//$this->settings_obj = new WP_Security_Settings_Page();//Initialize settins menus
			
		}
	}
	
	public function aiowps_plugin_not_active() {
		$aiowps_plugin_name = htmlspecialchars('All In One WP Security & Firewall');
		$aiowps_link = '<a href="' . esc_url('http://wordpress.org/plugins/all-in-one-wp-security-and-firewall/"') . '" target="_blank">'.$aiowps_plugin_name.'</a>';
		
		$msg = '<p>'.sprintf(__('Attention! You do not have the %s active.', 'all-in-one-wp-security-and-firewall-premium') . ' ' . __('The %s plugin needs to be installed and active in order for the %s Premium addon to work!', 'all-in-one-wp-security-and-firewall-premium'), $aiowps_link, $aiowps_plugin_name, $aiowps_plugin_name).'</p>';
		echo '<div class="error fade">'.$msg.'</div>';
	}
	
	/**
	 * AIOS premium country blocking and smart404 event logs and blocking tasks
	 *
	 * @return void
	 */
	public function aios_premium_plugin_init() {
		//CRON or WP CLI do not required country blocking or 404 event log etc.
		if (empty($_SERVER['REQUEST_URI']) || (defined('DOING_CRON') && DOING_CRON) || 'cli' == PHP_SAPI) return;
		  
		$this->country_tasks_obj = new AIOWPS_Country_Tasks();//For country blocking tasks
		$this->smart_404_tasks_obj = new AIOWPS_Smart404_Tasks();//For smart 404 event logs and IP blocking tasks
		new AIOWPS_CB_General_Init_Tasks();
		new AIOWPS_SMART_404_General_Init_Tasks();
	}
	
	/**
	 * AIOS premium admin settings for maxmind license key and update maxmind database schedule cron event
	 *
	 * @return void
	 */
	 public function aios_premium_admin_init() {
		if (false === AIOWPS_Premium_Utilities::woocommerce_maxmind_db_exists() && is_admin()) {
			new AIOWPS_Premium_MaxMind_settings();
			if (!wp_next_scheduled('aiowps_premium_daily_geoip_update')) {
				// schedule cron event for downlading the updated maxmind databse.
				wp_schedule_event(time() , 'daily', 'aiowps_premium_daily_geoip_update');
			}
			add_action('aiowps_premium_daily_geoip_update', array($this, 'daily_geoip_update'));
		}
	}

	/**
	 * Download the MaxMind database based on the license key.
	 */
	public function daily_geoip_update() {
        global $aio_wp_security_premium;
        $license_key = $aio_wp_security_premium->configs->get_value('aiowps_premium_maxmind_key');
        if ('' !== $license_key) {
            $download_maxmind_db = $aio_wp_security_premium->aiowps_premium_download_maxmind_database($license_key);
		}
    }

	/**
	 * Load premium Two Factor Authentication.
	 *
	 * @return void
	 */
	public static function load_premium_tfa() {
		if (empty($GLOBALS['simba_two_factor_authentication_premium']) && isset($GLOBALS['simba_two_factor_authentication'])) {
			if (!class_exists('Simba_Two_Factor_Authentication_Premium')) include_once(AIOWPS_PREMIUM_PATH.'/includes/simba-tfa/premium/loader.php');

			$GLOBALS['simba_two_factor_authentication_premium'] = new Simba_Two_Factor_Authentication_Premium($GLOBALS['simba_two_factor_authentication']);
		}
	}
	
	/**
	 * Reset AIOS premium all config settings.
	 *
	 * @return void
	 */
	public function aios_premium_reset_all_settings() {
		AIOWPS_CB_Configure_Settings::set_default_settings();
		AIOWPS_SMART_404_Configure_Settings::set_default_settings();
	}
	
}//End of class

}//End of class not exists check

$GLOBALS['aio_wp_security_premium'] = new AIOWPS_PREMIUM();
