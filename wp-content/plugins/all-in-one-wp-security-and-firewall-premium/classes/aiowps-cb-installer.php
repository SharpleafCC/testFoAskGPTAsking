<?php

class AIOWPS_CB_Installer
{
    static function run_installer($networkwide='')
    {
        global $wpdb;
        if (function_exists('is_multisite') && is_multisite() && $networkwide) {
            // check if it is a network activation - if so, run the activation function for each blog id
                $blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
                foreach ($blogids as $blog_id) {
                    switch_to_blog($blog_id);
                    AIOWPS_CB_Configure_Settings::add_option_values();
                    restore_current_blog();
                }
        } else {
            AIOWPS_CB_Configure_Settings::add_option_values();
        }
        self::update_geoip_db();
    }
    
    static function run_installer_old()
    {	
        global $wpdb;
        if (function_exists('is_multisite') && is_multisite()) 
        {
            // check if it is a network activation - if so, run the activation function for each blog id
            if (isset($_GET['networkwide']) && ($_GET['networkwide'] == 1)) 
            {
                $old_blog = $wpdb->blogid;
                // Get all blog ids
                $blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
                foreach ($blogids as $blog_id) {
                    switch_to_blog($blog_id);
                    //AIOWPS_CB_Installer::create_db_tables();
                    AIOWPS_CB_Configure_Settings::add_option_values();
                }
                switch_to_blog($old_blog);
                return;
            }
        }
        //AIOWPS_CB_Installer::create_db_tables();
        AIOWPS_CB_Configure_Settings::add_option_values();
    }
    
    static function create_db_tables()
    {
        //global $wpdb;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        
        //Table name variables
//        $aio_wp_security_premium_settings_tbl_name = WPPG_TBL_SETTINGS;
//        
//        $cs_tbl_sql = "CREATE TABLE " . $aio_wp_security_premium_settings_tbl_name . " (
//        wps_key varchar(128) NOT NULL,
//        value text NOT NULL,
//        PRIMARY KEY  (wps_key)
//        )ENGINE=MyISAM DEFAULT CHARSET=utf8;";
//        dbDelta($cs_tbl_sql);

	update_option("aiowps_premium_db_version", AIOWPS_PREMIUM_DB_VERSION);
    }
    
    public static function update_geoip_db()
    {
        
    }
}
