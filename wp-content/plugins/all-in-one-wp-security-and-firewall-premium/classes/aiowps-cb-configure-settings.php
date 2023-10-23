<?php

class AIOWPS_CB_Configure_Settings
{    
    function __construct(){
        
    }
    
    public static function set_default_settings()
    {
        global $aio_wp_security, $aio_wp_security_premium;

        //Main country blocking flag
        $aio_wp_security_premium->configs->set_value('aiowps_cb_enable_country_blocking','');//Checkbox
        
        //Blocking preferences
        $aio_wp_security_premium->configs->set_value('aiowps_cb_blocking_action','redirect');
        $aio_wp_security_premium->configs->set_value('aiowps_cb_redirect_url','http://127.0.0.1');
        $aio_wp_security_premium->configs->set_value('aiowps_cb_blocked_countries','');

        //Secondary Blocking preferences
        $aio_wp_security_premium->configs->set_value('aiowps_cb_enable_secondary_country_blocking','');//Checkbox
        
        $aio_wp_security_premium->configs->set_value('aiowps_cb_secondary_blocking_action','redirect');
        $aio_wp_security_premium->configs->set_value('aiowps_cb_secondary_redirect_url','http://127.0.0.1');
        $aio_wp_security_premium->configs->set_value('aiowps_secondary_cb_protected_post_ids','');
        $aio_wp_security_premium->configs->set_value('aiowps_cb_secondary_blocked_countries','');
        $aio_wp_security_premium->configs->set_value('aiowps_cb_ajax_enabled',''); //checkbox
        //TODO - keep adding default options for any fields that require it
        
        //Save it
        $aio_wp_security_premium->configs->save_config();
    }
    
    public static function add_option_values()
    {
        global $aio_wp_security, $aio_wp_security_premium;

        //Main country blocking flag
        $aio_wp_security_premium->configs->add_value('aiowps_cb_enable_country_blocking','');//Checkbox
        
        //Blocking preferences
        $aio_wp_security_premium->configs->add_value('aiowps_cb_blocking_action','redirect');
        $aio_wp_security_premium->configs->add_value('aiowps_cb_blocked_countries','');
        
        //Secondary Blocking preferences
        $aio_wp_security_premium->configs->add_value('aiowps_cb_enable_secondary_country_blocking','');//Checkbox
        
        $aio_wp_security_premium->configs->add_value('aiowps_cb_secondary_blocking_action','redirect');
        $aio_wp_security_premium->configs->add_value('aiowps_cb_secondary_redirect_url','http://127.0.0.1');
        $aio_wp_security_premium->configs->add_value('aiowps_secondary_cb_protected_post_ids','');
        $aio_wp_security_premium->configs->add_value('aiowps_cb_secondary_blocked_countries','');
        $aio_wp_security_premium->configs->add_value('aiowps_cb_ajax_enabled',''); //checkbox
        
        //TODO - keep adding default options for any fields that require it
        
        //Save it
        $aio_wp_security_premium->configs->save_config();
    }
}
